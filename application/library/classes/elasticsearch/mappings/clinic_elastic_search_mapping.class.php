<?php

    class ClinicElasticSearchMapping implements IElasticSearchMapping
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
                    'type'           => 'integer',
                    'include_in_all' => TRUE,
                ),
                'specialties' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                            'include_in_all' => TRUE,
                        ),
                        'purposes_of_visit' => array(
                            'type'           => 'integer',
                            'include_in_all' => FALSE,
                        )
                    ),
                ),
                'services' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                            'include_in_all' => TRUE,
                        )
                    ),
                ),
                'types' => array(
                    'properties' => array(
                        'id' => array(
                            'type'           => 'integer',
                            'include_in_all' => TRUE,
                        )
                    ),
                ),
                'address' => array(
                    'type'           => 'string',
                    'include_in_all' => TRUE
                ),
                'specializations' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'primary_clinic_id' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'is_children' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE,
                ),
                'is_handicapped' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE,
                ),
                'is_pregnant' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE,
                ),
                'is_day_and_night' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE,
                ),
                'name' => array(
                    'type'           => 'string',
                    'include_in_all' => TRUE
                ),
                'doctors' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE
                ),
                'city' => array(
                    'type'           => 'integer',
                    'include_in_all' => TRUE,
                ),
                'is_active' => array(
                    'type'           => 'boolean',
                    'include_in_all' => TRUE,
                ),
                'is_not_example' => array(
                    'type'           => 'boolean',
                    'include_in_all' => TRUE,
                ),
                'geo_point' => array(
                    'type'           => 'geo_point',
                    'include_in_all' => FALSE,
                ),
                'registry_users' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'freelancers' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'is_region' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE
                ),
                'region' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'status' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'street' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'district' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE,
                ),
                'balls' => array(
                    'type'           => 'float',
                    'include_in_all' => FALSE
                ),
                'date_publish' => array(
                    'type'           => 'date',
                    'include_in_all' => FALSE
                ),
                'dt_publish' => array(
                    'type'           => 'long',
                    'include_in_all' => FALSE,
                ),
                'rate' => array(
                    'type'           => 'float',
                    'include_in_all' => FALSE
                ),
                'only_children' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE
                ),
                'is_card_pay' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE
                ),
                'twenty_four_hours' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE
                ),
                'metro_station_id' => array(
                    'type'           => 'integer',
                    'include_in_all' => FALSE
                ),
                'have_ramp' => array(
                    'type'           => 'boolean',
                    'include_in_all' => FALSE
                )
            );
        }
    }