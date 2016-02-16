<?php
    $memcache = new Memcache();
    $memcache->connect('127.0.0.1',11211);

    define('MEMCACHE_ENABLED', false);
    define('MEMCACHE_MODEL_MANAGER', 0);

    Register::add('memcache', $memcache);