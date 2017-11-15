<?php
	class StreetManager extends AliasManager
	{
		protected $table_name = 'street';
		protected $model_name = 'StreetModel';

		protected $transliterated_field = 'full_name';

        /**
		 * return StreetModel
		 */
		public function getOneByName($name){
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * return StreetModel
		 */
		public function getOneByPrefixAndName($prefix, $name){
			$data = $this->orm_model->select()->where('prefix = ? AND name = ?', $prefix, $name)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * return StreetModel[]
		 */
		public function getListByDistrictId($district_id)
		{
			$sql = 'SELECT *
					FROM street s
					WHERE EXISTS (
							SELECT *
							FROM street_to_region s2r
							INNER JOIN region r ON r.id = s2r.region_id
							WHERE r.district_id = ' . (int)$district_id . '
								AND s2r.street_id = s.id
						)';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getHavingDoctorsList()
		{
			$sql = 'SELECT *
					FROM street s
					WHERE id IN (
							SELECT DISTINCT c.street_id
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE dc.is_active = 1
						)
					ORDER BY `name`';
			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingDoctorsListByCityId($city_id)
		{
            $data = array();
            $city_id = intval($city_id);

            if($city_id) {
                $sql = 'SELECT *
					FROM street s
					WHERE id IN (
							SELECT DISTINCT c.street_id
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								dc.is_active = 1
                                AND c.city_id = '.$city_id.'
						)
					ORDER BY `name`';

                $data = $this->db->query($sql);
            }

			return count($data) > 0 ? $this->initList($data) : array();
		}

		public function getHavingDoctorsListBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT *
					FROM street s
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.street_id = s.id
								AND ds2c.clinic_id = c.id
								AND dc.is_active = 1
								AND ds2c.specialty_id = ' . (int)$specialty_id . '
						)
					ORDER BY `name`';
			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty_id, $district_id)
		{
			$sql = 'SELECT DISTINCT s.*
					FROM street s
					INNER JOIN clinic c ON c.street_id = s.id
					INNER JOIN street_to_region s2r ON s2r.street_id = s.id
					INNER JOIN region r ON s2r.region_id = r.id
					INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
					INNER JOIN doctor d ON ds2c.doctor_id = d.id
					WHERE d.is_active = 1
					    AND c.is_active = 1
						AND r.district_id = '.(int)$district_id.'
						AND ds2c.doctor_id = d.id
						AND s2r.region_id = r.id
						AND ds2c.specialty_id = '.(int)$specialty_id.'
					ORDER BY s.name';
			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingClinicListByTypeOrService($item_id, $district_id, $region_id, $type)
		{
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

			$sql = 'SELECT DISTINCT s.*
					FROM street                                                   AS s
					  INNER JOIN clinic                                           AS c   ON c.street_id = s.id
					  INNER JOIN street_to_region                                 AS s2r ON s2r.street_id = s.id
					  INNER JOIN region                                           AS r   ON s2r.region_id = r.id
                      INNER JOIN `' . $tables['table_name_join'] . '`             AS j   ON j.' . $tables['page_id'] . ' = c.id
                      INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp  ON lp.id = j.' . $tables['join_id'] . '
					WHERE
					  c.is_active = 1                                      AND
					  c.region_id = ' . $region_id . '                     AND
                      j.' . $tables['join_id'] . ' = ' . (int)$item_id . ' AND
				      r.district_id = '.(int)$district_id . '
					ORDER BY s.name';

			$data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
		}

		/**
		 * return StreetModel[]
		 */
		public function getListByRegionId($region_id)
		{
			$sql = 'SELECT s.*
					FROM street s
					INNER JOIN street_to_region s2r ON s2r.street_id = s.id
					WHERE s2r.region_id = ' . (int)$region_id;

			$data = $this->db->query($sql);

			return $this->initList($data);
		}
	}