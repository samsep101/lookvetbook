<?php
	class MetroStationManager extends AliasManager
	{
		protected $table_name = 'metro_station';
		protected $model_name = 'MetroStationModel';

		protected $transliterated_field = 'seo_name';

        /**
         * @param $metro_branch_id
         * @return MetroStationModel[]
         */
        public function getListByMetroBranchId($metro_branch_id)
		{
			$data = $this->orm_model->select()->where('metro_branch_id = ?', $metro_branch_id)->fetchAll();
			return $this->initList($data);
		}

        /**
         * return MetroStationModel[]
         */
        public function getListByCityId($city_id)
        {
            $sql = 'SELECT ms.*
					FROM metro_station ms
					INNER JOIN metro_branch mb ON ms.metro_branch_id = mb.id
					INNER JOIN metro m ON m.id = mb.metro_id
					WHERE m.city_id = ' . (int)$city_id . '
					ORDER BY ms.name';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

		public function getHavingDoctorsList()
		{
			$sql = 'SELECT *
					FROM metro_station ms
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.metro_station_id = ms.id
								AND dc.is_active = 1
						)
					ORDER BY `name`';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingDoctorsListById($city_id)
		{
            $data = array();
            $city_id = intval($city_id);

            if($city_id) {
                $sql = 'SELECT *
                        FROM metro_station ms
                        WHERE EXISTS (
                                SELECT *
                                FROM doctor dc
                                INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
                                INNER JOIN clinic c ON c.id = d2c.clinic_id
                                WHERE
                                    c.metro_station_id = ms.id
                                    AND dc.is_active = 1
                                    AND c.is_active = 1
                                    AND c.city_id = ' . $city_id . '
                                    AND dc.first_name IS NOT NULL
                                    AND dc.second_name IS NOT NULL
                                    AND dc.last_name IS NOT NULL
                            )
                        ORDER BY `name`';

                $data = $this->db->query($sql);
            }

			return count($data) > 0 ? $this->initList($data) : array();
		}
		
		public function getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty_id, $district_id)
		{
			$sql = 'SELECT DISTINCT ms.*
                    FROM metro_station ms
                    INNER JOIN clinic c ON c.metro_station_id = ms.id
                    INNER JOIN region r ON ms.region_id = r.id
                    INNER JOIN district d ON r.district_id = d.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
                    INNER JOIN doctor doc ON ds2c.doctor_id = doc.id
                    WHERE ds2c.specialty_id = ' .(int)$specialty_id .'
                    AND d.id = ' .(int)$district_id .'
                    AND doc.is_active = 1
                    AND c.is_active = 1
                    ORDER BY ms.name';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingClinicListByTypeOrService($item_id, $district_id, $type)
		{
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

			$sql = 'SELECT DISTINCT ms.*
                    FROM metro_station ms
                      INNER JOIN clinic                                           AS c  ON c.metro_station_id = ms.id
                      INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = c.id
                      INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                      INNER JOIN region                                           AS r  ON c.region_id = r.id
                      INNER JOIN district                                         AS d  ON r.district_id = d.id
                    WHERE
                      j.' . $tables['join_id'] . ' = ' . (int)$item_id . ' AND
                      d.id = ' .(int)$district_id . '                      AND
                      c.is_active = 1
                    ORDER BY ms.name';

			$data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
		}

		public function getHavingDoctorsListBySpecialtyIdAndRegionId($specialty_id, $region_id)
		{
			$sql = 'SELECT DISTINCT ms.*
                    FROM metro_station ms
                    INNER JOIN clinic c ON c.metro_station_id = ms.id
                    INNER JOIN region r ON ms.region_id = r.id
                    INNER JOIN district d ON r.district_id = d.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
                    INNER JOIN doctor doc ON ds2c.doctor_id = doc.id
                    WHERE ds2c.specialty_id = ' .(int)$specialty_id .'
                    AND r.id = ' .(int)$region_id .'
                    AND doc.is_active = 1
                    AND c.is_active = 1
                    ORDER BY ms.name';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

        public function getHavingClinicListByTypeOrServiceForRegion($item_id, $region_id, $type)
        {
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

            $sql = 'SELECT DISTINCT ms.*
                    FROM ' . $this->table_name . '                                AS ms
                      INNER JOIN clinic                                           AS c  ON c.metro_station_id = ms.id
                      INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = c.id
                      INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                      INNER JOIN region                                           AS r  ON ms.region_id = r.id
                      INNER JOIN district                                         AS d  ON r.district_id = d.id
                    WHERE
                      j.' . $tables['join_id'] . ' = ' . (int)$item_id . ' AND
                      r.id = ' .(int)$region_id . '                        AND
                      c.is_active = 1
                    ORDER BY ms.name';

            $data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
        }

		/**
		* @return MetroStationModel[]
		 */
		public function getListByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchAll();
			return $this->initList($data);
		}

		/**
		* @return MetroStationModel[]
		 */
		public function getListByRegionId($region_id)
		{
			$data = $this->orm_model->select()->where('region_id = ?', $region_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return MetroStationModel
		 */
		public function getOneByNameAndMetroBranchId($name, $metro_branch_id)
		{
			$data = $this->orm_model->select()->where('name = ? AND metro_branch_id = ?', $name, $metro_branch_id)->fetchOne();
			return $this->initOne($data);
		}

        
		/**
		 * @return MetroStationModel[]
		 */
		public function getListByMetroBranchIdAndNumberRange($metro_branch_id, $number_from, $number_to)
		{
			$data = $this->orm_model->select()->where('metro_branch_id = ? AND number >= ? AND number <= ?', $metro_branch_id, $number_from, $number_to)->fetchAll();
			return $this->initList($data);
		}

	}