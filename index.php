<?php
if (php_sapi_name()!='cli') {
  header("Content-Type: text/html; charset=UTF-8");
}

require('application/config/site.cfg.php');

if (!debug) {
  ini_set('display_errors', 'Off');
} else {
  ini_set('display_errors', 'On');
  ini_set('display_startup_errors', 'On');
  //xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}


require_once 'vendor/sentry/sentry/lib/Raven/Autoloader.php';
Raven_Autoloader::register();
$client = new Raven_Client('https://3eddb6b698414aa28519bd1b864ef789:92e01d140f5c448c81348fd7834e201c@sentry.io/157050');

try {
  if (!empty($argc)) {
    chdir(dirname(__FILE__));
    unset($argv[0]);
    $uri = '/' . join('/', $argv);
  } else {
    $uri = '';
  }
  require('application/config/init.php');

  define('CURRENT_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
  // абсолютный путь до корня сайта
  define('ABS_ROOT', realpath(dirname(__FILE__)));

    if(Application::getUriPath(false) == '/robots.txt'){

        $subdomain = str_replace(['.lookmedbook.ru', 'lookmedbook.ru', '.citrus.one'], '', $_SERVER['SERVER_NAME']);
        $robots_filePath = ABS_ROOT.'/application/templates/robots_txt/'.$subdomain.'.robots.txt';
        // домены с недефолтным robots
        if( ! in_array($subdomain, [
            'sankt-peterburg',
            'novosibirsk',
            'chelyabinsk',
            'omsk',
            'samara',
            'kazan',
            'nizhniy-novgorod',
            'ekaterinburg'
        ]) OR ! file_exists($robots_filePath)){
            // во всех остальных случаях отдаем дефолтный
            $robots_filePath = ABS_ROOT.'/application/templates/robots_txt/default.robots.txt';
        }

        header('Content-Type:text/plain; charset=utf8', true);
        ob_start();
        include $robots_filePath;
        exit(ob_get_clean());
    }

  $redirect_domen = $redirect_uri = '';
  if (isset($_SERVER['SERVER_NAME'])) {
    $excluded_subdomens = ['account', 'test', 'sankt-peterburg', 'novosibirsk', 'chelyabinsk', 'omsk', 'samara', 'kazan', 'nizhniy-novgorod', 'ekaterinburg'];
    $m = [];
    if (preg_match('|^(www\.)?(([a-z0-9-]+)\.)?\w+\.\w+$|', $_SERVER['SERVER_NAME'], $m)) {
      if (!empty($m[1])) {
        $redirect_domen = str_replace($m[1], '', $_SERVER['SERVER_NAME']);
      }
      if (!empty($m[2]) and !in_array($m[3], $excluded_subdomens)) {
        $redirect_domen = str_replace($m[1] . $m[2], '', $_SERVER['SERVER_NAME']);
      }
    }
  }
  // редирект со страницы со слешем на конце на страницу без слеша на конце
  if (isset($_SERVER['REQUEST_URI']) and preg_match('/^(.+)\/$/ims', $_SERVER['REQUEST_URI'], $matches)) {
    $redirect_uri = $matches[1];
  }

  if ($redirect_domen or $redirect_uri) {
    if (!$redirect_domen) {
      $redirect_domen = $_SERVER['SERVER_NAME'];
    }
    if (!$redirect_uri) {
      $redirect_uri = $_SERVER['REQUEST_URI'];
    }
    RedirectManager::redirect301('http://' . $redirect_domen . $redirect_uri);
  }

  $controller = new Dispatcher();
  $controller->process($uri);
} catch (Exception $exception) {
  if (debug) {
    echo $exception->getFile() . ":" . $exception->getLine() . " " . $exception->getMessage();
    echo "<pre>";
    print_r($exception->getTraceAsString());
    echo "</pre>";
  }
  if (!debug && (!in_array(php_sapi_name(), array('cgi-fcgi', 'cli')))) {
    error404($exception);
  }
  if (in_array(php_sapi_name(), array('cli'))) {
    display_cli_error($exception);
  }
}

if (debug) {
  echo "<br><br><br><hr>
			Peak memory usage: " . number_format(memory_get_peak_usage()) . "
			";
}

function error404($exception = null)
{
  if (debug) {
    echo '
    <html>
    <head>
    <style>
    * { font-family: Verdana, Tahoma, Arial; font-size: 13px; }
    body {}
    .error {width: 860px;text-align: left;}
    .error h1 {font-size: 18px;margin-left: 36px;}
    .error-num {float: left;width: 30px;background-color: #fff;padding: 3px;text-align: left;}
    .error-descr {float: left;background-color: #f5f5f5;margin-bottom: 10px;padding: 3px;width: 800px;text-align: left;}
    .error-descr span {font-weight: bold;}
    </style>
    </head>
    </body>
    ';
    echo '<center><div class="error"><h1>' . $exception->getMessage() . '</h1>';
    foreach ($exception->getTrace() as $key => $item) {
      echo '<div class="error-item"><div class="error-num">' . ($key + 1) . '.</div>';
      echo '<div class="error-descr">';
      echo '<span>' . $item['class'] . ' ' . $item['type'] . ' ' . $item['function'] . ' ' . '( ' . join(', ', $item['args']) . ' )' . '</span><br>';
      echo $item['file'] . ' (Line: ' . $item['line'] . ')<br>';
      echo '</div><br clear="all" /></div>';
    }
    echo '</div></center>';
    echo '</body></html>';
  } else {
    ErrorPageViewHelper::page404('404');
    exit();
  }
}

function display_cli_error($exception = null)
{
  if (!$exception) {
    return;
  }
  echo 'Error! ' . $exception->getMessage() . "\n\n";
  echo 'File: ' . $exception->getFile() . "\n\n";
  echo 'Line: ' . $exception->getLine() . "\n\n";
  //echo 'Trace: '.print_r($exception->getTrace())."\n\n\n";
  //echo 'Exception: '.print_r($exception, true)."\n\n\n";

}

if (debug) {

//$xhprof_data = xhprof_disable();
//include_once $_SERVER['DOCUMENT_ROOT']."/third_party/xhprof/xhprof_lib/utils/xhprof_lib.php";
//include_once $_SERVER['DOCUMENT_ROOT']."/third_party/xhprof/xhprof_lib/utils/xhprof_runs.php";
//$xhprof_runs = new XHProfRuns_Default();
//$run_id = $xhprof_runs->save_run($xhprof_data, "test");
}
