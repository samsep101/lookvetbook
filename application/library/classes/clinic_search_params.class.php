<?php

    class ClinicSearchParams extends ModelSearchCriteria
    {
        public $specialty_id;
        public $specialization_id;
        public $purpose_of_visit_id;
        public $children;
        public $handicapped;
        public $pregnant;
        public $day_and_night;
        public $clinic_name;
        public $sort_by;
        public $address;

        public $doctor_id;

        public $city_id;
        public $is_active;
        public $not_work;
        public $redirect_list;
        public $not_show_example;

        /**
         * @var GeoPoint
         */
        public $geo_point;
        public $is_metro;

        public $district_id;
        public $region_id;
        public $street_id;
        public $metro_station_id;

        // параметры в регистратуре
        public $registry_user_id;
        public $freelancer_id;

        public $is_region = 0;

        public $metro_station_name;
        public $metro_branch_name;
        public $distance = 2000;

        public $page;
        public $by_page;

        public $regions;
        public $status;

        public $publish_date_from;
        public $publish_date_to;

        public $services;
        public $types;

        public $calc_found_rows = FALSE;

        public $only_children;
        public $is_card_pay;
        public $twenty_four_hours;
        public $have_ramp;

        public function getParamsHash()
        {
            $str = SITE_URL . 'specialty=' . $this->specialty_id .
                'services=' . $this->services .
                'types=' . $this->types .
                'purpose=' . $this->purpose_of_visit_id .
                'specialization=' . $this->specialization_id .
                'doctor=' . $this->doctor_id .
                'children' . $this->children .
                'pregnant' . $this->pregnant .
                'handicapped' . $this->handicapped .
                'clinic_name' . $this->clinic_name .
                'sort_by' . $this->sort_by .
                'is_metro' . $this->is_metro .
                'metro_station_name' . $this->metro_station_name .
                'metro_branch_name' . $this->metro_branch_name .
                'distance' . $this->distance .
                'page' . $this->page .
                'by_page' . $this->by_page .
                'city=' . $this->city_id .
                'address=' . $this->address .
                'registry_user=' . $this->registry_user_id .
                'freelancer=' . $this->freelancer_id .
                'is_active=' . $this->is_active .
                'not_work=' . $this->not_work .
                'redirect_list=' . $this->redirect_list .
                'is_region=' . $this->is_region .
                'status=' . $this->status .
                'regions=' . $this->regions .
                'not_show_example=' . $this->not_show_example .
                'calc_found_rows=' . $this->calc_found_rows .
                'publish_date_from=' . $this->publish_date_from .
                'publish_date_to=' . $this->publish_date_to .
                'only_children=' . $this->is_card_pay .
                'is_card_pay=' . $this->only_children .
                'twenty_four_hours=' . $this->twenty_four_hours .
                'have_ramp=' . $this->have_ramp;

            if($this->geo_point)
            {
                $str .= 'lat=' . $this->geo_point->getLatitude() .
                    'long=' . $this->geo_point->getLongitude();
            }

            return md5($str);
        }
    }