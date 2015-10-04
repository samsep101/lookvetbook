<?php

    class SpecializationManager extends AliasManager
    {
        protected $table_name = 'specialization';
        protected $model_name = 'SpecializationModel';

        public function __construct()
        {
            $this->transliterated_field = 'name';
            parent::__construct();
        }

        public function beforeSave(DynamicModel $model)
        {
            $specialty_to_specialization_manager = new SpecialtyToSpecializationManager();

            if($model->main_specialty_id)
            {
                $specialty_to_specialization = $specialty_to_specialization_manager->getOneBySpecializationIdAndSpecialtyId($model->getId(), $model->main_specialty_id);

                if(!$specialty_to_specialization)
                {
                    $specialty_to_specialization = new SpecialtyToSpecializationModel();

                    $specialty_to_specialization->specialization_id = $model->getId();
                    $specialty_to_specialization->specialty_id      = $model->main_specialty_id;

                    $specialty_to_specialization->save();
                }

                $specialty_to_specialization_manager->setMainBySpecializationIdAndSpecialtyId($model->getId(), $model->main_specialty_id);
            }

            //leading to lower case Specialization Name first letter
            if($model->name && $model->name != 'ЛФК')
            {
                $name_letter = mb_substr($model->name, 0, 1, 'utf-8');
                $model->name = str_replace($name_letter, mb_strtolower($name_letter, 'utf-8'), $model->name);
            }
        }

        public function getAdjectiveNameBySpecialtyId($specialty_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT s.adjective_name
                    FROM specialization s
                    INNER JOIN specialty_to_specialization s2s ON s2s.specialization_id = s.id
                    WHERE s2s.specialty_id = ' . (int)$specialty_id . '
                    AND s2s.is_main = 1';

            $data = $db->query($sql);

            if(!count($data))
            {
                $sql = 'SELECT s.adjective_name
                    FROM specialization s
                    INNER JOIN specialty_to_specialization s2s ON s2s.specialization_id = s.id
                    WHERE s2s.specialty_id = ' . (int)$specialty_id;
            }

            return (count($data)) ? $data[0]['adjective_name'] : "";
        }

        /**
         * return SpecializationModel[]
         */
        public function getListByClinicId($clinic_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                FROM specialization
                WHERE (
                    SELECT COUNT(*)
                    FROM specialization_to_clinic s2c
                    WHERE s2c.specialization_id = specialization.id
                    AND clinic_id = ' . $clinic_id . '
                )>0
                ORDER BY name ASC';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        /**
         * return SpecializationModel[]
         */
        public function getListBySpecialtyId($specialty_id)
        {
            $sql = 'SELECT *
                    FROM specialization s
                    WHERE
                        (
                            SELECT COUNT(*)
                            FROM specialty_to_specialization sp2s
                            WHERE sp2s.specialty_id = ' . (int)$specialty_id . '
                                AND sp2s.specialization_id = s.id
                        ) >  0';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        /**
         * return SpecializationModel
         */
        public function getOneByName($name)
        {
            $data = $this->orm_model->select()->where('name = ?', mysql_real_escape_string($name))->fetchOne();

            return $this->initOne($data);
        }

        public function getSpecializationsForClinicWithDoctors($clinic_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT DISTINCT s.*
                    FROM specialization s
                    INNER JOIN specialty_to_specialization s2s ON s2s.specialization_id = s.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = s2s.specialty_id
                    INNER JOIN doctor d ON d.id = ds2c.doctor_id
                    WHERE ds2c.clinic_id = ' . (int)$clinic_id . ' AND d.is_active = 1
                    ORDER BY s.`name` ASC';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getListWithoutDoctorsByClinicId($clinic_id)
        {
            $sql = 'SELECT DISTINCT s.*
                    FROM specialization s
                    INNER JOIN specialization_to_clinic s2c ON s2c.specialization_id = s.id
                    WHERE
                        s2c.clinic_id = ' . (int)$clinic_id . '
                       AND (SELECT COUNT(*)
                            FROM doctor_specialty_to_clinic ds2c
                            INNER JOIN specialty_to_specialization s2s ON s2s.specialty_id = ds2c.specialty_id
                            WHERE ds2c.clinic_id = s2c.clinic_id
                            AND s2s.specialization_id = s2c.specialization_id
                            LIMIT 1) = 0';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getSpecializationForCityIDInWhichHaveDoctors($city_id)
        {
            $city_manager = new CityManager();
            $city         = $city_manager->getOneById($city_id);

            $sql = 'SELECT DISTINCT s.*
                        FROM
                            specialization AS s
                            INNER JOIN specialty_to_specialization AS sts ON sts.specialization_id = s.id
                            INNER JOIN doctor_specialty_to_clinic AS ds2c ON ds2c.specialty_id = sts.specialty_id
                            INNER JOIN clinic                     AS c    ON ds2c.clinic_id = c.id
                            INNER JOIN doctor                     AS d    ON ds2c.doctor_id = d.id
                        WHERE
                            ds2c.doctor_id IS NOT NULL AND
                            d.first_name IS NOT NULL AND
                            d.second_name IS NOT NULL AND
                            d.last_name IS NOT NULL AND
                            (c.city_id = ' . (int)$city_id . '
                            ';
            if($city && $city->region == 'Московская область')
            {
                $sql .= ' OR c.city_id = ' . (int)CityModel::MOSCOW_ID;
            }
            $sql .= ' )';

            $sql .= ' ORDER BY s.name ASC;';

            $data = $this->db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }
    }