<?php

class ElasticSearchClinicIndexControl extends ElasticSearchModelIndexControl
{

  public function __construct()
  {
    $this->object_factory = new ClinicElasticSearchObjectsFactory();
  }

  /**
   * Получение типа, с которым работает данный менеджер
   *
   * @return \Elastica\Type
   */
  protected function getType()
  {
    return $this->getIndex()->getType('clinic');
  }

  /**
   * Построение объекта запроса по критериям поиска
   *
   * @param ModelSearchCriteria $criteria
   *
   * @return mixed
   */
  protected function buildQueryObject(ModelSearchCriteria $criteria)
  {
    /**
     * @var ClinicSearchParams $criteria
     */
    $filter_and = new \Elastica\Filter\BoolAnd();
    $query = new \Elastica\Query\Match();

    if ($criteria->specialty_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('specialties.id', $criteria->specialty_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->services) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('services.id', $criteria->services);
      $filter_and->addFilter($match);
    }

    if ($criteria->types) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('types.id', $criteria->types);
      $filter_and->addFilter($match);
    }

    if ($criteria->city_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('city', (int)$criteria->city_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->specialization_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('specializations', $criteria->specialization_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->purpose_of_visit_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('specialties.purposes_of_visit', $criteria->purpose_of_visit_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->children) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_children', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->handicapped) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_handicapped', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->pregnant) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_pregnant', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->day_and_night) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_day_and_night', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->only_children) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('only_children', TRUE);
      $filter_and->addFilter($match);
    } else if (is_bool($criteria->only_children) && $criteria->only_children === FALSE) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('only_children', FALSE);
      $filter_and->addFilter($match);
    }

    if ($criteria->clinic_name) {
      $query->setFieldQuery('name', $criteria->clinic_name);
      $query->setFieldOperator('name', 'AND');
    }

    if ($criteria->doctor_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('doctors', $criteria->doctor_id);
      $filter_and->addFilter($match);
    }


    if ($criteria->is_active) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_active', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->not_show_example) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_not_example', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->district_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('district', $criteria->district_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->region_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('region', $criteria->region_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->street_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('street', $criteria->street_id);
      $filter_and->addFilter($match);
    }
    /*
     if($criteria->geo_point)
     {
         $location = array(
             'lat' => $criteria->geo_point->getLatitude(),
             'lon' => $criteria->geo_point->getLongitude()
         );
         $distance = (($criteria->distance)/1000).'km';
         $match = new \Elastica\Filter\GeoDistance('geo_point', $location, $distance);
         $filter_and->addFilter($match);
     }*/

    if ($criteria->registry_user_id) {
      /**
       * @var UserManager $user_manager
       * @var UserModel $user
       */
      $user_manager = ModelManagerFactory::getByName('user');
      $user = $user_manager->getOneById($criteria->registry_user_id);

      $roles = array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_REGISTRY, RoleModel::FREELANCE_MANAGER);

      if (in_array($user->role_id, $roles)) {
        $match = new \Elastica\Filter\Term();
        $match->setTerm('registry_users', $criteria->registry_user_id);
        $filter_and->addFilter($match);
      }
    }

    if ($criteria->freelancer_id) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('freelancers', $criteria->freelancer_id);
      $filter_and->addFilter($match);
    }

    if ($criteria->is_region) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_region', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->status) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('status', $criteria->status);
      $filter_and->addFilter($match);
    }

    if ($criteria->regions) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('region', $criteria->regions);
      $filter_and->addFilter($match);
    }

    if ($criteria->twenty_four_hours) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('twenty_four_hours', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->is_card_pay) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('is_card_pay', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->have_ramp) {
      $match = new \Elastica\Filter\Term();
      $match->setTerm('have_ramp', TRUE);
      $filter_and->addFilter($match);
    }

    if ($criteria->publish_date_from || $criteria->publish_date_to) {
      $match = new \Elastica\Filter\NumericRange();
      $value = array();
      if ($criteria->publish_date_from) {
        $value['from'] = $criteria->publish_date_from;
      }

      if ($criteria->publish_date_to) {
        $value['to'] = $criteria->publish_date_to;
      }
      $match->addField('date_publish', $value);
      $filter_and->addFilter($match);
    }


    $result_query = new \Elastica\Query();

    if ($criteria->geo_point and 0) {//TODO: починить запрос дальности от гео-точки. сейчас выдает ошибку у эластика
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'geo_point\'].arcDistanceInKm(' . $criteria->geo_point->getLatitude() . ', ' . $criteria->geo_point->getLongitude() . ') < ' . ($criteria->distance / 1000) . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    }

    // Если выполняется, то мы сортируем результаты группами
    // Изначально мы получаем результаты, которые соответствют геопоиску (данные результаты сортирутся по
    // баллам).
    // Затем мы получем все остальные результаты, которые между собой также сортируются по баллам
    // Для этого добавляем к запросу одно псевдополе, в котором указываем, соответсвует ли оно критериям
    // геопоиска
    if ($criteria->street_id) {
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'street\'].value == ' . $criteria->street_id . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    } elseif ($criteria->region_id) {
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'district\'].value == ' . $criteria->district_id . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    } elseif ($criteria->district_id) {
      $result_query->addSort(array(
        '_script' => array(
          'script' => '((doc[\'district\'].value == ' . $criteria->district_id . ') ? 1 : 0)',
          "type" => "number",
          "order" => "desc"
        )
      ));
    }

    if (count($query->getParams())) {
      $result_query->setQuery($query);
    }

    if (count($filter_and->getFilters())) {
      $result_query->setFilter($filter_and);
    }

    if (!$criteria->clinic_name && $criteria->sort_by) {
      switch ($criteria->sort_by) {
        case 'dt_edit':
          $result_query->addSort(array(
            'dt_edit' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'date_publish':
          $result_query->addSort(array(
            'date_publish' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'dt_publish':
          $result_query->addSort(array(
            'dt_publish' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'raw_clinics_in_start':
          //$search_params->addSortParam('clinic_status_id', array(ClinicStatusModel::RAW), 'DESC');
          break;
        case 'recomend':
          $result_query->addSort(array(
            'balls' => array(
              'order' => 'desc',
            )
          ));
          break;
        case 'rate':
          $result_query->addSort(array(
            'rate' => array(
              'order' => 'desc',
            )
          ));
          break;
      }
    }

    $this->addPaging($criteria, $result_query);

    return $result_query;
  }

  public function getListByGeoPointAndDistance(GeoPoint $geo_point, $distance)
  {
    /**
     * @var ClinicSearchParams $criteria
     */
    $filter_and = new \Elastica\Filter\BoolAnd();

    $match = new \Elastica\Filter\Term();
    $match->setTerm('is_active', TRUE);
    $filter_and->addFilter($match);

    $location = array(
      'lat' => $geo_point->getLatitude(),
      'lon' => $geo_point->getLongitude()
    );
    $distance = (($distance) / 1000) . 'km';
    $match = new \Elastica\Filter\GeoDistance('geo_point', $location, $distance);
    $filter_and->addFilter($match);

    $result_query = new \Elastica\Query();
    $result_query->setFilter($filter_and);
    $result_query->setSize(1000);

    $data = $this->getType()->search($result_query);

    $result = array();
    foreach ($data as $v) {
      /**
       * @var \Elastica\Result $v
       */
      $result[] = $v->getData();
    }


    return $result;
  }

}