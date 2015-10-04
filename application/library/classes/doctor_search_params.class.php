<?php
    class DoctorSearchParams extends ModelSearchCriteria
    {
        public $specialty_id;

		public $suitable_specialties_ids = array();

		public $city_id;

        public $purpose_of_visit_id;
        public $visit_type;
        public $morning_time;
        public $evening_time;
        public $weekend_time;
        public $any_time;
        public $doctor_name;
        public $doctor_sex_id;
        public $sort_by;

		public $doctor_type;
        public $clinic_id;
        public $not_work;
        /**
         * @var GeoPoint
         */
        public $geo_point;
        public $is_metro;

		public $district_id;
		public $region_id;
		public $street_id;

		public $has_visit_slots = null;

        public $metro_station_name;
        public $metro_branch_name;
        public $metro_station_id;
        public $distance = 2000;

        public $primary_doctors_ids = array();

        public $page;
        public $by_page;

		public $get_extra_item = false;


        public $is_active = 1;
        public $clinic_is_active = null;

        public $disease_doctor = FALSE;

		public $registry_user_id = NULL;

        public $for_api = null;

		public $has_avatar = null;

        public $calc_found_rows = false;

        public $not_virtual;
        public $unbounded = null;
        public $without_filters = null;

        public $is_has_clinic = true;
        public $is_has_active_clinic = true;

        public $exclude_ids = array();

        public function getParamsHash()
        {
            $str = 	SITE_URL.'specialty='.$this->specialty_id.
					'purpose='.$this->purpose_of_visit_id.
					'visit_type'.$this->visit_type.
					'morning'.$this->morning_time.
					'evening'.$this->evening_time.
					'weekend'.$this->weekend_time.
					'any'.$this->any_time.
					'doctor_name'.$this->doctor_name.
					'doctor_type'.$this->doctor_type.
					'sort_by'.$this->sort_by.
					'is_metro'.$this->is_metro.
					'metro_station_name'.$this->metro_station_name.
					'metro_branch_name'.$this->metro_branch_name.
					'distance'.$this->distance.
					'page'.$this->page.
					'by_page'.$this->by_page.
					'doctor_sex_id'.$this->doctor_sex_id.
					'city='.$this->city_id.
					'district='.$this->district_id.
					'region='.$this->region_id.
					'street='.$this->street_id.
                    'is_active=' . $this->is_active .
                    'not_work=' . $this->not_work .
					'registry_user_id='.$this->registry_user_id.
                    'primary_doctors_ids='.join(',', $this->primary_doctors_ids).
                    'has-Visit_lots='.$this->has_visit_slots.
                    'without_filters='.$this->without_filters.
                    'is_has_clinics='.(int)$this->is_has_clinic;


            if ($this->geo_point){
                $str .= 'lat='.$this->geo_point->getLatitude().
                		'long='.$this->geo_point->getLongitude();
            }

	        if ($this->primary_doctors_ids)
	        {
		        $str .= 'primary_doctors=';
		        foreach($this->primary_doctors_ids as $primary_doctor_id)
		        {
			        $str .= $primary_doctor_id.',';
		        }
	        }

            return md5($str);
        }
    }