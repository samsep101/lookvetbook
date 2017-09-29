<?php
    /*
     * print_r argument var in pre tag
     */

    function pr($var, $die = false, $console = false, $showHtml = false, $showFrom = false)
    {
        if ($showFrom) {
            if(is_array($showFrom))
                $calledFrom = $showFrom;
            else
                $calledFrom = debug_backtrace();
            echo '<strong>' . $calledFrom[0]['file'] . '</strong>';
            echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
        }
        echo "\n<pre class=\"debug\">\n";

        $var = print_r($var, true);
        if ($showHtml) {
            $var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
        }
        echo $var . "\n</pre>\n\n";
        flush();
        if ($die)
            die;
    }
    function declension($digit, $expr, $onlyword = FALSE)
    {
        if (!is_array($expr)) $expr = array_filter(explode(' ', $expr));
        if (empty($expr[2])) $expr[2] = $expr[1];
        $i = preg_replace('/[^0-9]+/s', '', $digit) % 100; //intval не всегда корректно работает
        if ($onlyword) $digit = '';
        if ($i >= 5 && $i <= 20) $res = $digit . ' ' . $expr[2];
        else {
            $i %= 10;
            if ($i == 1) $res = $digit . ' ' . $expr[0];
            elseif ($i >= 2 && $i <= 4) $res = $digit . ' ' . $expr[1]; else $res = $digit . ' ' . $expr[2];
        }
        return trim($res);
    }

    function generateCode($characters)
    {
        // $characters = how many digits or characters to return.
        // You can use any set of characters you want.
        //$possible = '0123456789abcdefghijkmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $possible = '0123456789abcdifghijklmnopqrstuvwxyzABSDIFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            if ($i < $characters - 1) {
                $code .= "";
            }
            $i++;
        }
        return $code;
    }