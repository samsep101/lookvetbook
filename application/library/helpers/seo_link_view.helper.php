<?php

class SeoLinkViewHelper
{
  public static function getCityPageLink($specialty, CityModel $city, $location = 'doctor')
  {
    $href = LinkHelper::getSiteUrlByCity($city) . '/' . $location . '/' . $specialty->alias;
    return $href;
  }

  public static function getDistrictPageLink($specialty, DistrictModel $district, $location = 'doctor')
  {
    $href = self::getCityPageLink($specialty, $district->city, $location);
    return $href . '/' . $district->alias;
  }

  public static function getRegionPageLink($specialty, RegionModel $region, $location = 'doctor')
  {
    $href = self::getDistrictPageLink($specialty, $region->district, $location);
    return $href . '/' . $region->alias;
  }

  public static function getStreetPageLink($specialty, StreetModel $street, $location = 'doctor')
  {
    $href = self::getDistrictPageLink($specialty, $street->regions[0]->district, $location);
    return $href . '/' . $street->alias;
  }

  public static function getMetroStationPageLink($specialty, MetroStationModel $metro_station, $location = 'doctor')
  {
    if (empty($metro_station->region) or !is_object($metro_station->region)) {
      return '';
    }
    $href = self::getRegionPageLink($specialty, $metro_station->region, $location);
    return $href . '/' . $metro_station->alias;
  }


  public static function getSpecialtyPageLink($specialty, DynamicModel $address_object_model, $location = 'doctor')
  {
    switch (get_class($address_object_model)) {
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
    * Модфицирует внешнюю ссылку добавляя rel="nofollow" class="jsLinkHidingIndexing" и перемещая href -> data-link
   */
  function catchOuterLinks($matches){
    $linkOuter = $matches[0];
    $site_url = 'lookmedbook.ru';
    $innerUrlPattern='/href=.*'.$site_url.'[^.]*/is';

    if (!preg_match($innerUrlPattern, $linkOuter) && strpos($linkOuter,'//')) {
      if (strpos($linkOuter, 'rel') === false) {
        $linkOuter = preg_replace("%(href=\S(?!$site_url))%i", 'rel="nofollow" $1', $linkOuter);
      } elseif (preg_match("%href=\S(?!$site_url)%i", $linkOuter)) {
        $linkOuter = preg_replace('/rel=S(?!nofollow)\S*/i', 'rel="nofollow"', $linkOuter);
      }
      if (strpos($linkOuter, 'class') === false) {

        $linkOuter = preg_replace("%(href=\S(?!$site_url))%i", 'class="jsLinkHidingIndexing" $1', $linkOuter);
      } elseif (preg_match("%href=\S(?!$site_url)%i", $linkOuter)) {
        $linkOuter = preg_replace('/class="(.*?)"/i', 'class="$1 jsLinkHidingIndexing"', $linkOuter);
      }

      if (preg_match("%href=\S(?!$site_url)%i", $linkOuter)) {
        $regV = '#(<a[a-z\-_\s\"\#\=]*)(href=")((https?|ftp)://)#i';
        $replace = '$1$2" data-link="$3';
        $linkOuter = preg_replace($regV, $replace, $linkOuter);
      }
    }

    return $linkOuter;
  }

  /*
     * Заменяем внешние ссылки на преобразованные
  */
  public static function convertLinks($html)
  {
    $html = preg_replace_callback('/<a[^>]+/', 'self::catchOuterLinks', $html);
    return $html;
  }
}