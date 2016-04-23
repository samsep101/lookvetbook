<?php

    date_default_timezone_set('Europe/Moscow');

    function __autoload_application($className)
    {
        if ($className == 'Application') {
            require_once('core/classes/application.class.php');
        } else {
            if ((strpos($className, 'PHPExcel') === FALSE)) {
                Application::loadClass($className);
            } else {
                PhpExcel_Autoloader::Load($className);
            }
        }
    }

	spl_autoload_register('__autoload_application');

    require_once __DIR__ . '/../../vendor/autoload.php';

    if (debug == 1)
    {
        ini_set('display_errors', 1);
    }

    $application = new Application();
    Application::setDefaultDirs();
    Application::loadConfig('db');
    Application::loadConfig('db_profiler');
    Application::loadConfig('db_vidal');
    Application::loadConfig('map');
    Application::loadConfig('site');
    Application::loadConfig('controller_folders');
    Application::loadConfig('validation_rules');
    Application::loadConfig('cache');
    Application::loadConfig('profiler');
    Application::loadConfig('memcache');
    Application::loadConfig('controller_decorators');
    Application::loadConfig('elasticsearch');


	Application::afterInit();

		if (isset($_SERVER['REQUEST_URI'])) {
			if (strpos($_SERVER['REQUEST_URI'], 'admin') !== FALSE) {
				Application::loadAllConfigInFolder('cms_generator_configs');
			}
			if (strpos($_SERVER['REQUEST_URI'], 'registry') !== FALSE) {
				Application::loadAllConfigInFolder('manage_configs');
			}
		}

    Register::add('db', new Db());
    Register::add('utils', new Utils());

    require_once('core/funcs/funcs.php');