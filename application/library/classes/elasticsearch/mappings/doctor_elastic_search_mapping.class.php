<?php

	/**
	 * Задает меппинг для данных
	 *
	 * @link http://www.elasticsearch.org/guide/reference/mapping/
	 */
	class DoctorElasticSearchMapping implements IElasticSearchMapping
	{
		/**
		 * Получить меппинг для данной сущности
		 *
		 * @return array
		 */
		public function getFieldsMapping()
		{
			return array(
				'id' => array(
					'type' => 'integer',
					'include_in_all' => true
				),
				'specialties' => array(
					'type' => 'integer',
					'include_in_all' => false
				),
				'cities' => array(
					'type' => 'integer',
					'include_in_all' => false
				),
				'purposes_of_visit' => array(
					'type' => 'integer',
					'include_in_all' => false
				),
				'is_leave_the_house' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_has_morning_time' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_has_evening_time' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_has_weekend_time' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_has_any_time' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'full_name' => array(
					'type' => 'string',
					'include_in_all' => true,
					'search_analyzer' => 'autocomplete',
					'store' => true,
				),
				'sex' => array(
					'type' => 'integer',
					'include_in_all' => true
				),
				'doctor_type' => array(
					'type' => 'integer',
					'include_in_all' => false
				),
				'clinics' => array(
					'properties' => array(
						'id' => array(
							'type' => 'integer',
							'include_in_all' => false,
						),
						'geo_point' => array(
							'type' => 'geo_point',
							'include_in_all' => false
						),
						'district' => array(
							'type' => 'integer',
							'include_in_all' => false
						),
						'region' => array(
							'type' => 'integer',
							'include_in_all' => false
						),
						'street' => array(
							'type' => 'integer',
							'include_in_all' => false
						),
						'specialties' => array(
							'type' => 'integer',
						)
					),
				),
				'is_has_visit_slots' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_active' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_has_avatar' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_virtual' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_unbounded' => array(
					'type' => 'boolean',
					'include_in_all' => true
				),
				'is_adult' => array(
					'type' => 'boolean',
					'include_in_all' => true,
				),
				'is_pregnant' => array(
					'type' => 'boolean',
					'include_in_all' => true,
				),
				'balls' => array(
					'type' => 'float',
					'include_in_all' => true
				),
				'rate' => array(
					'type' => 'float',
					'include_in_all' => true
				),
				'is_has_clinic' => array(
					'type' => 'boolean',
					'include_in_all' => true,
				),
                'is_has_active_clinic' => array(
                    'type' => 'boolean',
                ),
				'registry_users' => array(
					'type' => 'integer'
				),
                'reviews_count' => array(
                    'type' => 'integer',
                    'include_in_all' => true
                ),
				'_boost' => array(
					'type' => 'float',
					'include_in_all' => true
				),
			);
		}

	}