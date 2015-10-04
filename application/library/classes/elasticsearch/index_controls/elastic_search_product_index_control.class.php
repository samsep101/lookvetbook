<?php

	class ElasticSearchProductIndexControl extends ElasticSearchModelIndexControl
	{

		public function __construct()
		{
			$this->object_factory = new ProductElasticSearchObjectsFactory();
		}

		/**
		 * Получение типа, с которым работает данный менеджер
		 *
		 * @return \Elastica\Type
		 */
		protected function getType()
		{
			return $this->getIndex()->getType('product');
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
			 * @var ProductSearchCriteria $criteria
			 */
			$query = new \Elastica\Query\Match();

			$filter_and = new \Elastica\Filter\BoolAnd();
			if($criteria->is_active)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_active', true);

				$filter_and->addFilter($filter);
			}

			if($criteria->full_name)
			{
                $name = preg_replace('/\-/', '\\-', $criteria->full_name);
				$query->setFieldQuery('full_name', $name);
				$query->setFieldOperator('full_name', 'AND');
			}

			if($criteria->product_categories)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('product_category', $criteria->product_categories);
				$filter_and->addFilter($filter);
			}

			if($criteria->is_leader)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_leader', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->manufacturer_id)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('manufacturer', true);
				$filter_and->addFilter($filter);
			}

			if($criteria->image_find_status_id)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('image_find_status', $criteria->image_find_status_id);
				$filter_and->addFilter($filter);
			}

			if($criteria->fill_information_status_id)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('fill_information_status', $criteria->fill_information_status_id);
				$filter_and->addFilter($filter);
			}

			if($criteria->product_itself)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('id', $criteria->product_itself);
				$not = new \Elastica\Filter\BoolNot($filter);
				$filter_and->addFilter($not);
			}

			$result_query = new \Elastica\Query();
			if(count($query->getParams()))
			{
				$result_query->setQuery($query);
			}

			if(count($filter_and->getFilters()))
			{
				$result_query->setFilter($filter_and);
			}

			switch(!$criteria->full_name && $criteria->sort_by)
			{
				case 'name':
					$result_query->addSort(array(
						'full_name_sort' => array(
							'order' => 'ASC',
						)
					));
					break;
			}

			$this->addPaging($criteria, $result_query);
			$result_query->setFields(array('id'));

			return $result_query;
		}

	}