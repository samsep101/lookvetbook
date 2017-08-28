<?php

class RelationsSimpleModel extends SimpleModel {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    public function addServiceToClinic($service_id, $clinic_id) {

        $exists = $this->getServicesRelationsClinics($service_id);

        if( ! in_array($clinic_id, $exists)){

            return $this->insert('services_to_clinic', [
                'services_categories_id' => (int)$service_id,
                'clinic_id' => (int)$clinic_id
            ]);
        }
        
        return false;
    }

    public function getServicesRelationsClinics($services_ids) {

        is_array($services_ids) AND $services_ids = implode(', ', array_map('intval', $services_ids));

        // SELECT * FROM `services_to_clinic` s2c INNER JOIN services_categories sc ON s2c.services_categories_id = sc.id WHERE sc.id = 1
        $q = str_replace(['{ids}'], [
            $services_ids
        ], 'SELECT clinic_id
            FROM `services_to_clinic` s2c
            INNER JOIN services_categories sc ON s2c.services_categories_id = sc.id
            WHERE sc.id IN ({ids})');

        return $this->fetch_column($q, 'clinic_id');
        
    }

    public function removeServiceToClinic($service_id, $clinic_id) {

        $q = sprintf('DELETE FROM services_to_clinic WHERE services_categories_id = %d AND clinic_id = %d', (int)$service_id, (int)$clinic_id);

        return $this->db->post($q);
    }

    public function addServiceToDoctor($service_id, $doctor_id) {

        $exists = $this->getServicesRelationsDoctors($service_id);

        if(is_array($doctor_id)){

            foreach($doctor_id as $one){

                if( ! in_array($one, $exists)){

                    return $this->insert('services_to_doctor', [
                        'services_categories_id' => (int)$service_id,
                        'doctor_id' => (int)$one
                    ]);
                }
            }

        } else {

            if( ! in_array($doctor_id, $exists)){

                return $this->insert('services_to_doctor', [
                    'services_categories_id' => (int)$service_id,
                    'doctor_id' => (int)$doctor_id
                ]);
            }
        }

    }

    public function getServicesRelationsDoctors($services_ids) {

        is_array($services_ids) AND $services_ids = implode(', ', array_map('intval', $services_ids));

        // SELECT * FROM `services_to_clinic` s2c INNER JOIN services_categories sc ON s2c.services_categories_id = sc.id WHERE sc.id = 1
        $q = str_replace(['{ids}'], [
            $services_ids
        ], 'SELECT doctor_id
            FROM `services_to_doctor` s2d
            INNER JOIN services_categories sc ON s2d.services_categories_id = sc.id
            WHERE sc.id IN ({ids})');

        return $this->fetch_column($q, 'doctor_id');
    }

    public function removeServiceToDoctor($service_id, $doctor_id) {

        $q = sprintf('DELETE FROM services_to_doctor WHERE services_categories_id = %d AND doctor_id = %d', (int)$service_id, (int)$doctor_id);

        return $this->db->post($q);
    }
}

/* END CLASS: RelationsSimpleModel extends SimpleModel */