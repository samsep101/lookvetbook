<?php
    class EqualDoctorManager extends ModelManager
    {
        protected $table_name = 'equal_doctor';
        protected $model_name = 'EqualDoctorModel';

        public function getListByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                    FROM equal_doctor
                    WHERE doctor_id = ' .(int)$doctor_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function deleteListByDoctorId($doctor_id)
        {
            $sql = 'DELETE
                    FROM equal_doctor
                    WHERE doctor_id = ' .(int)$doctor_id;

            $this->db->query($sql);
        }

        public function deleteListOfNotActiveDoctors()
        {
            $sql = 'DELETE ed.*
                    FROM equal_doctor ed
                    INNER JOIN doctor d ON d.id = ed.equal_doctor_id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = d.id
                    INNER JOIN clinic c ON c.id = ds2c.clinic_id
                    WHERE d.is_active IS NULL
                    OR d.is_active != 1
                    OR (SELECT COUNT(c1.id)
                            FROM clinic c1
                            INNER JOIN doctor_specialty_to_clinic ds2c1 ON ds2c1.clinic_id = c1.id
                            WHERE ds2c1.doctor_id = ed.equal_doctor_id
                            AND c1.is_active = 1) = 0
                    OR (SELECT COUNT(ds2c2.id)
                            FROM doctor_specialty_to_clinic ds2c2
                            WHERE ds2c2.doctor_id = ed.equal_doctor_id) = 0';

            $this->db->query($sql);
        }
    }