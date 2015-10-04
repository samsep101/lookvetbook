<?php
	class SeoTextViewHelper
	{
        public static function getDoctorPageDescription(DynamicModel $specialty, DynamicModel $address_object) {
            $geo = '';
            $specialtyPlural = $specialty->lp_genitive_name_plural;
            $specialtyGenitive = $specialty->genitive_name;

            switch(get_class($address_object)) {
                case 'CityModel' : {
                    $geo = 'в ' . $address_object->prepositional_name;
                    break;
                } case 'DistrictModel' : {
                    $geo = 'в ' . $address_object->formal_name;
                    break;
                } case 'RegionModel' : {
                    $geo = 'района ' . $address_object->name;
                    break;
                } case 'StreetModel' : {
                    $geo = 'улица ' . $address_object->name;
                    break;
                } case 'MetroStationModel' : {
                    $geo = $address_object->name;
                    break;
                }

            }

            $specialtyAndGeo = $specialtyPlural . ' ' . $geo;

            $seoText = 'На нашем сервисе вы всегда сможете найти %s.
            Мы работаем с лучшими и проверенными специалистами, которые готовы помочь вам в решении проблемы.
            Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.
            Благодаря этому мы можем вам посоветовать хорошего %s.';

            return sprintf($seoText, $specialtyAndGeo, $specialtyGenitive);
        }

        public static function getClinicPageDescription($page, DynamicModel $address_object) {
            $geo = '';
            $pluralName = $page->plural_name;
            $genitiveName = $page->genitive_name;

            switch(get_class($address_object)) {
                case 'CityModel' : {
                    $geo = 'в ' . $address_object->prepositional_name;
                    break;
                } case 'DistrictModel' : {
                    $geo = 'в ' . $address_object->formal_name;
                    break;
                } case 'RegionModel' : {
                    $geo = 'района ' . $address_object->name;
                    break;
                } case 'StreetModel' : {
                    $geo = $address_object->name;
                    break;
                } case 'MetroStationModel' : {
                    $geo = $address_object->name;
                    break;
                }

            }

            if(get_class($page) == 'ClinicServicesModel')
            {
                $specialtyAndGeo = $genitiveName . ' ' . $geo;

                $seoText = 'На нашем сервисе вы всегда сможете найти клинику предоставляющую %s.
                Мы работаем с лучшими и проверенными клиниками, которые готовы помочь вам в решении проблемы.
                Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.
                Благодаря этому мы можем вам посоветовать хорошую клинику оказывающей услугу "%s".';

                $result_text = sprintf($seoText, $specialtyAndGeo, $pluralName);
            }
            else if(get_class($page) == 'ClinicTypeModel')
            {
                $specialtyAndGeo = $pluralName . ' ' . $geo;

                $seoText = 'На нашем сервисе вы всегда сможете найти %s.
                Мы работаем с лучшими и проверенными клиниками, которые готовы помочь вам в решении проблемы.
                Все отзывы на нашем сервисе оставлены пациентами, которые были на приеме у врача.
                Благодаря этому мы можем вам посоветовать хорошую %s.';

                $result_text = sprintf($seoText, $specialtyAndGeo, $genitiveName);
            }
            else return '';

            return $result_text;
        }

		public static function getTextBySpecialtyIdAndAddressObject($specialty_id, DynamicModel $address_object)
		{
			if ($address_object) {
				$seo_text_manager = new SeoTextManager();
				$text = $seo_text_manager->getOneBySpecialtyIdAndAddressObject($specialty_id, $address_object);

				return $text ? $text->text : '';
			} else {
				return '';
			}
		}

		public static  function getAddressObjectName(DynamicModel $model = NULL)
		{
			if ($model) {
				if (get_class($model) == 'MetroStationModel')
					return 'возле метро '.$model->name.' района '.$model->region->name;
				elseif(get_class($model) == 'StreetModel')
					return 'возле '.$model->street_type->genitive_name.' '.$model->name;
				elseif(get_class($model) == 'RegionModel')
					return 'в районе '.$model->name.' округа '.$model->parent->formal_name;
				elseif(get_class($model) == 'DistrictModel')
					return 'в округе '.$model->formal_name.' города '.$model->city->name;
				elseif(get_class($model) == 'CityModel')
					return 'в '.$model->prepositional_name;

			}
			return '';
		}

		public static  function getAddressObjectNamePagesForTop(DynamicModel $model = NULL)
		{
			if ($model) {
				if (get_class($model) == 'MetroStationModel')
					return 'возле метро '.$model->name.' района '.$model->region->name;
				elseif(get_class($model) == 'StreetModel')
					return 'возле '.$model->street_type->genitive_name.' '.$model->name;
				elseif(get_class($model) == 'RegionModel')
					return $model->name.' в '.$model->parent->city->prepositional_name;
				elseif(get_class($model) == 'DistrictModel')
					return ' в ' . $model->city->prepositional_name . ' ' . $model->formal_name;
				elseif(get_class($model) == 'CityModel')
					return 'в '.$model->prepositional_name;

			}
			return '';
		}

		public static  function getAddressObjectOnlyName(DynamicModel $model = NULL)
		{
            if ($model) {
                if (get_class($model) == 'MetroStationModel')
                    return $model->name;
                elseif(get_class($model) == 'StreetModel')
                    return $model->street_type->genitive_name;
                elseif(get_class($model) == 'RegionModel')
                    return $model->name;
                elseif(get_class($model) == 'DistrictModel')
                    return $model->formal_name;
                elseif(get_class($model) == 'CityModel')
                    return $model->name;

            }
			return '';
		}

		public static function getH1($specialty, $address_object)
		{
			return StringHelper::startProposalWord($specialty->plural_name).' '.SeoTextViewHelper::getAddressObjectName($address_object);
		}

		public static function getTitle($specialty, $address_object, $hideAddress = 0, $defaultTitle = 0)
		{
            $html = '';
            if($specialty && !$defaultTitle) {
                if($hideAddress) $html = 'Лучшие ' . $specialty->plural_name . ' ' . SeoTextViewHelper::getAddressObjectNamePagesForTop($address_object) . '. Найти хорошего ' . $specialty->genitive_name . ' ' . SeoTextViewHelper::getAddressObjectOnlyName($address_object) . ', запись на прием онлайн, рейтинг, отзывы – Lookmedbook';
                else $html = self::getH1($specialty, $address_object).' | Выбор хорошего '.$specialty->genitive_name.' '.SeoTextViewHelper::getAddressObjectName($address_object).', отзывы, рейтинг и запись на прием на Lookmedbook.';
            } elseif($address_object) {
                $html = 'Найти хорошего врача '.SeoTextViewHelper::getAddressObjectName($address_object) . ' онлайн. Поиск врачей по всем специальностям, отзывы, рейтинг, запись на прием – Lookmedbook';
            } else {
                $html = 'Найти хорошего врача в Москве онлайн. Поиск врачей по всем специальностям, отзывы, рейтинг, запись на прием – Lookmedbook';
            }

			return $html;

		}

		public static function getDescription($specialty, $address_object)
		{
            if($specialty) {
                $html = 'Сервис Lookmedbook поможет выбрать хорошего '.$specialty->genitive_name.' '.SeoTextViewHelper::getAddressObjectName($address_object).'
			по отзывам клиентов, узнать стоимость приема врачей и посмотреть их фото.';
            } else {
                $html = 'Сервис Lookmedbook поможет выбрать хорошего врача '.SeoTextViewHelper::getAddressObjectName($address_object).'
			по отзывам клиентов, узнать стоимость приема врачей и посмотреть их фото.';
            }

			return $html;
		}

        public static function getTopNumberH1($specialty, $address_object) {
            if (!$specialty)
            {
                $text = 'Найти врача ' . self::getAddressObjectNamePagesForTop($address_object) . ' онлайн';
            }
            else
            {
                $text = 'Найти врача ' . $specialty->genitive_name . ' ' . self::getAddressObjectNamePagesForTop($address_object) . ' онлайн';
            }

            return $text;
        }
	}