<?php
    header("Content-Type: text/html; charset=UTF-8");
    define('debug', 0);

	if(!debug)
	{
		ini_set('display_errors', 'Off');
	}

	try
	{
		if(!empty($argc))
		{
			chdir(dirname(__FILE__));
			unset($argv[0]);
			$uri = '/' . join('/', $argv);
		}
		else
		{
			$uri = '';
		}
		require('application/config/init.php');

		// редирект со страницы со слешем на конце на страницу без слеша на конце
		if(preg_match('/^(.+)\/$/ims', $_SERVER['REQUEST_URI'], $matches))
		{
			RedirectManager::redirect301($matches[1]);
		}

		$controller = new Dispatcher();
		$controller->process($uri);
	} catch(Exception $exception)
	{
		error404($exception);
	}

	function error404($exception = null)
	{
		if(debug)
		{
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
			foreach($exception->getTrace() as $key => $item)
			{
				echo '<div class="error-item"><div class="error-num">' . ($key + 1) . '.</div>';
				echo '<div class="error-descr">';
				echo '<span>' . $item['class'] . ' ' . $item['type'] . ' ' . $item['function'] . ' ' . '( ' . join(', ', $item['args']) . ' )' . '</span><br>';
				echo $item['file'] . ' (Line: ' . $item['line'] . ')<br>';
				echo '</div><br clear="all" /></div>';
			}
			echo '</div></center>';
			echo '</body></html>';
		}
		else
		{
			ErrorPageViewHelper::page404('404');
			exit();
		}
	}

?>
