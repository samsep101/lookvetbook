<?php
    class HelpPhoneNumberViewHelper
    {
        public static function getPhoneNumber($city)
        {
            $city_manager = ModelManagerFactory::getByName('city');
            $city = $city_manager->getOneById($city->getId());

            $phone = '';
            /*if ($city->name == 'Москва' || $city->region == 'Московская область'){
                $phone = SettingsManager::get('moscow_top_number');
            } else {
                $phone = SettingsManager::get('other_cities_top_number');
            }*/

            if ($_SERVER['HTTP_HOST'] == 'lookmedbook.ru')
            {
                $phone = '+7(495) 215-09-07';
            }else{
                $phone = '+7(800) 333-27-00';
            }

            return $phone;
        }
    }