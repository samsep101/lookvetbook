<?php
    class UrlViewHelper {
        public static function getShortView($url)
        {
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.','' , $url);
            $url = preg_replace('/\/$/', '', $url);

            return $url;
        }

        public static function getLinkView($url)
        {
            if (!preg_match('/^http/', $url))
            {
                return 'http://'.$url;
            } else {
                return $url;
            }
        }
    }