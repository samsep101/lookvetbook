<?php
	class ElasticSearchLaboratoryIndexControl extends ElasticSearchModelIndexControl
	{
		function __construct()
		{
			$this->object_factory = new LaboratoryElasticSearchObjectsFactory();
		}


		/**
		 * Получение типа, с которым работает данный менеджер
		 *
		 * @return \Elastica\Type
		 */
		protected function getType()
		{
			return $this->getIndex()->getType('laboratory');
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
			 * @var LaboratorySearchParams $criteria
			 */
			$query = new \Elastica\Query\Match();

			$filter_and = new \Elastica\Filter\BoolAnd();

            /*if($criteria->geo_point)
			{
				$location = array(
					'lat' => $criteria->geo_point->getLatitude(),
					'lon' => $criteria->geo_point->getLongitude()
				);
				$distance = ($criteria->distance/1000).'km';
				$filter = new \Elastica\Filter\GeoDistance('geo_point', $location, $distance);
				$filter_and->addFilter($filter);
			} */


			if($criteria->urgent_tests)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_has_urgent_tests', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->card_pay)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_has_card_pay', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->work_seven_days)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_work_seven_days', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->easy_entry)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_easy_entry', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->without_turn)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_without_turn', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->day_and_night)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_day_and_night', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->city_id)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('city', $criteria->city_id);
				$filter_and->addFilter($filter);
			}

			$result_query = new \Elastica\Query();
            if($criteria->geo_point) {
                $result_query->addSort(array(
                    '_script' => array(
                        'script' => '((doc[\'geo_point\'].arcDistanceInKm('.$criteria->geo_point->getLatitude().', '.$criteria->geo_point->getLongitude().') < '.($criteria->distance/1000).') ? 1 : 0)',
                        "type" => "number",
                        "order" => "desc"
                    )
                ));
            }

			if(count($query->getParams()))
			{
				$result_query->setQuery($query);
			}

			if(count($filter_and->getFilters()))
			{
				$result_query->setFilter($filter_and);
			}

			$this->addPaging($criteria, $result_query);
			$result_query->setFields(array('id'));

			return $result_query;
		}

        /**
         * Получение списка записей по координатам и дистанции
         *
         * @param GeoPoint $geo_point
         * @param $distance
         * @return array
         */
        public function getListByGeoPointAndDistance(GeoPoint $geo_point, $distance)
        {
            /**
             * @var LaboratorySearchParams $criteria
             */
            $filter_and = new \Elastica\Filter\BoolAnd();

            $location = array(
                'lat' => $geo_point->getLatitude(),
                'lon' => $geo_point->getLongitude()
            );
            $distance = (($distance)/1000).'km';
            $match = new \Elastica\Filter\GeoDistance('geo_point', $location, $distance);
            $filter_and->addFilter($match);

            $result_query = new \Elastica\Query();
            $result_query->setFilter($filter_and);
            $result_query->setSize(1000);

            $data = $this->getType()->search($result_query);

            $result = array();
            foreach($data as $v)
            {
                /**
                 * @var \Elastica\Result $v
                 */
                $result[] = $v->getData();
            }


            return $result;
        }

	}