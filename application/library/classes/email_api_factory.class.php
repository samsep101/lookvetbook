<?php
    class EmailApiFactory {
        private static $api;

        public static function getApi()
        {
            if (!self::$api)
            {
                self::$api = new UniSenderApi(SettingsManager::get('unisender_api_key'));
            }

            return self::$api;
        }
    }