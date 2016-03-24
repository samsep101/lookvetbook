<?php

class ElasticSearchDoctorIndexControl extends ElasticSearchModelIndexControl
{

  public function __construct()
  {
    $this->object_factory = new DoctorElasticSearchObjectFactory();
  }

  /**
   * @return \Elastica\Type
   */
  protected function getType()
  {
    return $this->getIndex()->getType('doctor');
  }


  protected function buildQueryObject(ModelSearchCriteria $criteria)
  {
    /**
     * @var DoctorSearchParams $criteria
     */
    $filter_and = new Elastica\Filter\BoolAnd();

    $query = new Elastica\Query\Match();

    if ($criteria->doctor_name) {
      $query->setFieldQuery('full_name', $criteria->doctor_name);
      $query->setFieldOperator('full_name', 'AND');
      $criteria->specialty_id = null;
    } else {
      if ($criteria->not_virtual) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_virtual', false);
        $filter_and->addFilter($match);
      }

      if ($criteria->specialty_id) {
        $specialties = array();

        $specialties[] = $criteria->specialty_id;

        if ($criteria->suitable_specialties_ids) {
          foreach ($criteria->suitable_specialties_ids as $specialty_id)
            $specialties[] = $specialty_id;
        }

        $match = new \Elastica\Filter\Term();
        $match->setTerm('specialties', $specialties);
        $filter_and->addFilter($match);
      }

      if ($criteria->purpose_of_visit_id) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('purposes_of_visit', $criteria->purpose_of_visit_id);
        $filter_and->addFilter($match);

      }

      if ($criteria->visit_type == 'home') {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_leave_the_house', 1);
        $filter_and->addFilter($match);
      }

      if ($criteria->doctor_sex_id) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('sex', $criteria->doctor_sex_id);
        $filter_and->addFilter($match);
      }

      if ($criteria->morning_time) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_has_morning_time', $criteria->morning_time);
        $filter_and->addFilter($match);
      }

      if ($criteria->evening_time) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_has_evening_time', $criteria->evening_time);
        $filter_and->addFilter($match);
      }

      if ($criteria->weekend_time) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_has_weekend_time', $criteria->weekend_time);
        $filter_and->addFilter($match);
      }

      if ($criteria->doctor_type == 'adult') {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_adult', 1);
        $filter_and->addFilter($match);
      }

      if ($criteria->doctor_type == 'children') {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_children', 1);
        $filter_and->addFilter($match);
      }

      if ($criteria->doctor_type == 'pregnant') {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_pregnant', 1);
        $filter_and->addFilter($match);
      }

      if ($criteria->has_avatar) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('is_has_avatar', true);
        $filter_and->addFilter($match);
      }

      if ($criteria->primary_doctors_ids) {
        foreach ($criteria->primary_doctors_ids as $id) {
          $match = new \Elastica\Filter\Term();
          $match->setTerm('id', $id);
          $match_not = new \Elastica\Filter\BoolNot($match);
          $filter_and->addFilter($match_not);
        }
      }
    }

    if ($criteria->registry_user_id) {
      /**
       * @var UserManager $user_manager
       * @var UserModel $user
       */
      $user_manager = ModelManagerFactory::getByName('user');
      $user = $user_manager->getOneById($criteria->registry_user_id);

      $roles = array(RoleModel::FREELANCE_MANAGER, RoleModel::ACCOUNT_MANAGER);
      if (in_array($user->role_id, $roles)) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('registry_users', $criteria->registry_user_id);
        $filter_and->addFilter($match);
      }
    }

    if ($criteria->is_active) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_active', true);
      $filter_and->addFilter($match);
    }

    if ($criteria->city_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('cities', $criteria->city_id);
      $filter_and->addFilter($match);
    }

    if (!empty($criteria->clinic_id)) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('clinics.id', $criteria->clinic_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->district_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('clinics.district', $criteria->district_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->region_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('clinics.region', $criteria->region_id);
      $filter_and->addFilter($match);
    }

    if (!empty($criteria->_id)) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('_id', $criteria->_id);
      $filter_and->addFilter($match);
    }

    if (!empty($criteria->ids) and count($criteria->ids)) {
      $filter_or = new Elastica\Filter\BoolOr();
      foreach ($criteria->ids as $_id) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('_id', $_id);
        $filter_or->addFilter($match);
      }
      $filter_and->addFilter($filter_or);
    }


    if (!empty($criteria->ids_no) and count($criteria->ids_no)) {
      $filter_or = new Elastica\Filter\BoolOr();
      foreach ($criteria->ids_no as $_id) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('_id', $_id);
        $filter_no = new \Elastica\Filter\BoolNot($match);
        $filter_or->addFilter($filter_no);
      }
      $filter_and->addFilter($filter_or);
    }


    if ($criteria->street_id && !$criteria->region_id) {
      $region_manager = ModelManagerFactory::getByName('street');
      $street = $region_manager->getOneById($criteria->street_id);

      $region_id = $street->regions[0]->getId();
      $match = new \Elastica\Filter\Term();
      $match->setTerm('clinics.region', $region_id);
      $filter_and->addFilter($match);
    }

    /*
if($criteria->geo_point)
{
$point = array(
  'lat' => $criteria->geo_point->getLatitude(),
  'lon' => $criteria->geo_point->getLongitude()
);

$match = new \Elastica\Filter\GeoDistance('clinics.geo_point', $point, ($criteria->distance / 1000) . 'km');
$filter_and->addFilter($match);
}
    */

    if ($criteria->is_has_clinic !== null) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_has_clinic', $criteria->is_has_clinic);
      $filter_and->addFilter($match);
    }

    if ($criteria->is_has_active_clinic) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_has_active_clinic', true);
      $filter_and->addFilter($match);
    }

    if ($criteria->has_visit_slots) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_has_visit_slots', true);
      $filter_and->addFilter($match);
    }

    $result_query = new \Elastica\Query();
    $result_query->setFields(array('id', 'is_equal_to_geo'));

    if (count($query->getParams())) {
      $result_query->setQuery($query);
    }

    // Если выполняется, то мы сортируем результаты группами
    // Изначально мы получаем результаты, которые соответствют геопоиску (данные результаты сортирутся по
    // баллам).
    // Затем мы получем все остальные результаты, которые между собой также сортируются по баллам
    // Для этого добавляем к запросу одно псевдополе, в котором указываем, соответсвует ли оно критериям
    // геопоиска
    if ($criteria->street_id) {
      //$result_query->addScriptField('is_equal_to_geo', new \Elastica\Script('((doc[\'clinics.street\'].value == '.$criteria->street_id.') ? 1 : 0)'));
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'clinics.street\'].value == ' . $criteria->street_id . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    } elseif ($criteria->region_id) {
      //$result_query->addScriptField('is_equal_to_geo', new \Elastica\Script('((doc[\'clinics.region\'].value == '.$criteria->region_id.') ? 1 : 0)'));
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'clinics.region\'].value == ' . $criteria->region_id . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    } elseif ($criteria->district_id) {
      //$result_query->addScriptField('is_equal_to_geo', new \Elastica\Script('((doc[\'clinics.district\'].value == '.$criteria->district_id.') ? 1 : 0)'));
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'clinics.district\'].value == ' . $criteria->district_id . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    }

    if ($criteria->geo_point and 0) {//TODO: починить запрос дальности от гео-точки. сейчас выдает ошибку у эластика
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'clinics.geo_point\'].arcDistanceInKm(' . $criteria->geo_point->getLatitude() . ', ' . $criteria->geo_point->getLongitude() . ') < ' . ($criteria->distance / 1000) . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    }

    if ($filter_and->getFilters()) {
      $result_query->setFilter($filter_and);
    }


    switch (!$criteria->doctor_name && $criteria->sort_by) {
      case 'balls':
      case 'rate':
        $result_query->addSort(array(
          'rate' => array(
            'order' => 'desc',
          )
        ));
        $result_query->addSort(array(
          'reviews_count' => array(
            'order' => 'desc',
          )
        ));
//					$result_query->addSort(array(
//						'balls' => array(
//							'order' => 'desc',
//						)
//					));
        break;
      case 'rand':
        $result_query->addSort(array(
          'id' => array(
            'order' => 'RAND'
          )
        ));
        break;
    }

    if ($criteria->page && $criteria->by_page) {
      $size = $criteria->by_page+2;//TODO: какая-то хрень с количеством. Говоришь вывести два, выводит одного. Сделал четыре, неплохо было бы понять, какого хрена так...

//TODO:  эти врачи могут быть и не найдены, поэтому закоментил. Пушшай себе пока впустую ищет, чуть по-позже разберемся
//      if (count($criteria->primary_doctors_ids) and ($criteria->page == 1)) {
//        $size -= count($criteria->primary_doctors_ids);
//      }

      if ($criteria->get_extra_item) {
        $result_query->setSize($size + 1);
      } else {
        $result_query->setSize($size);
      }
//echo '!'.$criteria->page.'!'.$criteria->by_page.'!'.$size.'!!!!';
      $result_query->setFrom(($criteria->page - 1) * $criteria->by_page);
    } else {
      $result_query->setSize(10000);
      $result_query->setFrom(0);
    }
    return $result_query;
  }

  public function search(ModelSearchCriteria $criteria, $need_to_get_total_hits = false)
  {
    $result = parent::search($criteria, $need_to_get_total_hits);
    if ($criteria->page == 1) {
      $result = array_merge($criteria->primary_doctors_ids, $result);
    }

    return $result;
  }


}