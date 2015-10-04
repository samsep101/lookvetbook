<?php
	class ElasticSearchDiseaseIndexControl extends ElasticSearchModelIndexControl
	{

		public function __construct()
		{
			$this->object_factory = new DiseaseElasticSearchObjectsFactory();
		}

		/**
		 * Получение типа, с которым работает данный менеджер
		 *
		 * @return \Elastica\Type
		 */
		protected function getType()
		{
			return $this->getIndex()->getType('disease');
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
			 * @var DiseaseSearchCriteria $criteria
			 */
			$query = new \Elastica\Query\Match();

			$filter_and = new \Elastica\Filter\BoolAnd();
			if($criteria->is_active)
			{
				$filter = new \Elastica\Filter\Term();
				$filter->setTerm('is_active', true);

				$filter_and->addFilter($filter);
			}

			if($criteria->name)
			{
				$query = new \Elastica\Query\MultiMatch();
				$query->setQuery($criteria->name);
				$query->setFields(array('name', 'alt_name'));
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

			$this->addPaging($criteria, $result_query);
			$result_query->setFields(array('id'));

			return $result_query;
		}

	}