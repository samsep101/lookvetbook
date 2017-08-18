<?php

class RelationsSimpleModel extends SimpleModel {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

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
}

/* END CLASS: RelationsSimpleModel extends SimpleModel */