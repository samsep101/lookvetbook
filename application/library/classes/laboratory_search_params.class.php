<?php
    class LaboratorySearchParams extends ModelSearchCriteria
    {
        /**
         * @var GeoPoint
         */
        public $geo_point;
        public $is_metro;


        public $urgent_tests;
        public $card_pay;
        public $work_seven_days;
        public $easy_entry;
        public $without_turn;
        public $day_and_night;

        public $metro_station_name;
        public $metro_branch_name;
        public $distance = 2000;

        public $city_id;

		public function getHash()
		{

			$str = '';
			if($this->geo_point)
			{
				$str .= 'latitude'.$this->geo_point->getLatitude().'long'.$this->geo_point->getLongitude();
			}
			$str .= 'urgent_tests'.$this->urgent_tests.
					'card_pay'.$this->card_pay.
					'work_seven_days'.$this->work_seven_days.
					'easy_entry'.$this->easy_entry.
					'without_turn'.$this->without_turn.
					'day_and_night'.$this->day_and_night.
					'metro_station_name'.$this->metro_station_name.
					'distance'.$this->distance.
					'city_id'.$this->city_id.
					'is_metro'.$this->is_metro;

			return md5($str);
		}
    }