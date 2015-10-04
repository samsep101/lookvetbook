<?php
	class SeoLinkViewHelper
	{
		public static function getCityPageLink($specialty, CityModel $city, $location = 'doctor')
		{
			$href = LinkHelper::getSiteUrlByCity($city).'/' . $location . '/'.$specialty->alias;
			return $href;
		}

		public static function getDistrictPageLink($specialty, DistrictModel $district, $location = 'doctor')
		{
			$href = self::getCityPageLink($specialty, $district->city, $location);
			return $href.'/'.$district->alias;
		}

		public static function getRegionPageLink($specialty, RegionModel $region, $location = 'doctor')
		{
			$href = self::getDistrictPageLink($specialty, $region->district, $location);
			return $href.'/'.$region->alias;
		}

		public static function getStreetPageLink($specialty, StreetModel $street, $location = 'doctor')
		{
			$href = self::getDistrictPageLink($specialty, $street->regions[0]->district, $location);
			return $href.'/'.$street->alias;
		}

		public static function getMetroStationPageLink($specialty, MetroStationModel $metro_station, $location = 'doctor')
		{
			$href = self::getRegionPageLink($specialty, $metro_station->region, $location);
			return $href.'/'.$metro_station->alias;
		}


		public static function getSpecialtyPageLink($specialty, DynamicModel $address_object_model, $location = 'doctor')
		{
			switch (get_class($address_object_model))
			{
				case 'StreetModel':
					return self::getStreetPageLink($specialty, $address_object_model, $location);
				case 'MetroStationModel':
					return self::getMetroStationPageLink($specialty, $address_object_model, $location);
				case 'RegionModel':
					return self::getRegionPageLink($specialty, $address_object_model, $location);
				case 'DistrictModel':
					return self::getDistrictPageLink($specialty, $address_object_model, $location);
				case 'CityModel':
					return self::getCityPageLink($specialty, $address_object_model, $location);
				default:
					return '';
			}
		}

        /*
         * Заменяем внешние ссылки на преобразованные
         */
        public static function convertLinks($html) {
            include_once dirname(dirname(__FILE__)) . '/classes/simple_html_dom.php';

            $regPermissibleLinks = '/http(.){0,1}:\/\/(?!(.)*lookmedbook)/';
            $linksProcessed = array();
            $externalReference = array();

            $strHtml = str_get_html($html);

            if($strHtml->innertext != '' and count($strHtml->find('a'))) {
                foreach($strHtml->find('a') as $a) {
                    if(preg_match($regPermissibleLinks, $a->href)) {
                        $externalReference[] = $a->outertext;
                        $a->class = $a->class . ' jsLinkHidingIndexing';
                        $a->{'data-link'} = $a->href;

                        unset($a->href);

                        $linksProcessed[] = $a->outertext;
                    }
                }
            }

            $html = str_replace($externalReference, $linksProcessed, $html);

            return $html;

        }
	}