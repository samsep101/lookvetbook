<?php
    class ApiLogger
    {
        public static function log()
        {
            $request = serialize($_REQUEST);

            $message = date('Y-m-d H:i:s').": \t".$_REQUEST['REMOTE_ADDR'].':  '.$_SERVER['REQUEST_URI'];
            $message .= "\t ".$request."\r\n\r\n-------------------\r\n\r\n";

            $f = fopen('./media/logs/api/'.date('Y-m-d').'.txt', 'a+');
            fwrite($f, $message);

            fclose($f);
        }
    }