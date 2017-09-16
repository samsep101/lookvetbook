<?php
	class ProductElasticSearchMapping implements IElasticSearchMapping
	{
		/**
		 * Получить меппинг для данной сущности
		 *
		 * @link http://www.elasticsearch.org/guide/reference/mapping/
		 * @return array
		 */
		public function getFieldsMapping()
		{
			return array(
				'id' => array(
					'type' => 'integer',
					'include_in_all' => true,
				),
				'full_name' => array(
					'type' => 'text',
					'include_in_all' => true,
					'analyzer' => 'autocomplete',
          'search_analyzer' => 'searchAnalyzer',
          'index' => true,
          'store' => true,
				),
				'full_name_sort' => array(
					'type' => 'keyword',
					'index' => true
				),
				'product_category' => array(
					'type' => 'integer',
					'include_in_all' => false,
				),
				'is_active' => array(
					'type' => 'boolean',
					'include_in_all' => false,
				),
				'is_leader' => array(
					'type' => 'boolean',
					'include_in_all' => true,
				),
				'manufacturer' => array(
					'type' => 'integer',
					'include_in_all' => true
				),
				'image_find_status' => array(
					'type' => 'integer',
					'include_in_all' => true
				),
				'fill_information_status' => array(
					'type' => 'integer',
					'include_in_all' => true,
				),
			);
		}

	}