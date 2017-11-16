<?php

class ExtendedRemoteCompilerHelper extends \Closure\RemoteCompiler
{
    protected $url = 'https://closure-compiler.appspot.com/compile';

    protected $port = 443;

    public function getRequestHandler()
    {
        if (!isset($this->requestHandler)) {
            $requestHandler = new \Zend\Http\Client();
            $requestHandler->setOptions(array(
                'timeout'=> 60,
                'adapter' => 'Zend\Http\Client\Adapter\Curl'
            ));
            $this->setRequestHandler($requestHandler);
        }

        return $this->requestHandler;
    }
}