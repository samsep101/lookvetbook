<?php
	require_once 'PHPUnit/Autoload.php';
    require_once 'base.php';

    chdir('D:/sites/home/lookmedbook.loc/www');

    function load($className)
    {
        if ($className == 'Application') {
            require_once('core/classes/application.class.php');
        } else {
            Application::loadClass($className);
        }
    }

    spl_autoload_register('load');
    Application::setDefaultDirs();
    Application::loadConfig('db');
    Application::loadConfig('memcache');

    Register::add('db', new Db());





