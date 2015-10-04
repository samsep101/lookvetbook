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
					'type' => 'string',
					'include_in_all' => true,
					'analyzer' => 'autocomplete',
					'_boost' => 5,
				),
				'alt_name' => array(
					'type' => 'string',
					'include_in_all' => false,
					'analyzer' => 'autocomplete'
				),
				'tags' => array(
					'type' => 'string',
					'include_in_all' => false,
				),
				'is_active' => array(
					'type' => 'boolean',
					'include_in_all' => true,
				)
			);
		}

	}