<?php

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