<?php

class ExtendedRemoteCompilerHelper extends \Closure\RemoteCompiler
{
    protected $url = 'https://closure-compiler.appspot.com/compile';

    protected $port = 443;
}