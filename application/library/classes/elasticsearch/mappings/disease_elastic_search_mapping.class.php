<?php
	class DiseaseElasticSearchMapping implements IElasticSearchMapping
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
				'name' => array(
					'type' => 'text',
					'index' => true,
					'include_in_all' => true,
					'analyzer' => 'autocomplete',
					'search_analyzer' => 'searchAnalyzer',
					'boost' => 2,
				),
				'alt_name' => array(
					'type' => 'text',
					'index' => true,
					'include_in_all' => false,
					'analyzer' => 'autocomplete',
          'search_analyzer' => 'searchAnalyzer',
				),
				'tags' => array(
					'type' => 'keyword',
					'index' => true,
					'include_in_all' => false,
				),
				'is_active' => array(
					'type' => 'boolean',
					'include_in_all' => true,
				)
			);
		}

	}