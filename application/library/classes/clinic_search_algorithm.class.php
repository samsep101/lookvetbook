<?php
    class ClinicSearchAlgorithm
    {
        private $manager;
        private $good_search_flag = true;
        private $next_page_flag = true;
        private $hash = '';
        private $primary_clinics_ids = null;
        private $use_discard_criteria_algorithm = true;

        /**
         * @var ClinicSearchParams
         */
        private $search_params = null;

        public function __construct()
        {
            $this->manager = new ClinicManager();
        }

        public function setUseDiscardCriteriaAlgorithm($value)
        {
            $this->use_discard_criteria_algorithm = (bool)$value;
        }

        public function getGoodSearchFlag()
        {
            return $this->good_search_flag;
        }

        public function getNextPageFlag()
        {
            return $this->next_page_flag;
        }

        public function getHash()
        {
            return $this->hash;
        }

        public function getPrimaryClinicsIds()
        {
            return $this->primary_clinics_ids;
        }

        public function search(ClinicSearchParams $params)
        {
            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');
			$params->get_extra_item = true;
            $clinics = $clinic_manager->getListByClinicSearchParams($params);

            if (!$clinics)
            {
                $clinics = $this->removeCriteriaAlgorithm($params);
            }

            if (count($clinics) == ($params->by_page + 1))
            {
                $this->next_page_flag = true;
                unset($clinics[$params->by_page]);
            } else {
                $this->next_page_flag = false;
            }

            return $clinics;
        }

        private function removeCriteriaAlgorithm(ClinicSearchParams $clinic_search_params)
        {
            $clinics = array();

            while (!$clinics) {
                if ($clinic_search_params->geo_point && $clinic_search_params->distance < 64000) {
                    $clinic_search_params->distance *= 2;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->distance >= 64000)
                {
                    $clinic_search_params->geo_point = null;
                    $clinic_search_params->distance = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    $clinic_search_params->metro_station_name = null;
                    continue;
                }

                $this->good_search_flag = false;

                if(!$this->use_discard_criteria_algorithm)
                    break;

                if ($clinic_search_params->clinic_name) {
                    $clinic_search_params->clinic_name = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->children) {
                    $clinic_search_params->children = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->pregnant) {
                    $clinic_search_params->pregnant = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->handicapped) {
                    $clinic_search_params->handicapped = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->day_and_night) {
                    $clinic_search_params->day_and_night = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->purpose_of_visit_id) {
                    $clinic_search_params->purpose_of_visit_id = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                if ($clinic_search_params->specialty_id) {
                    $clinic_search_params->specialty_id = null;
                    $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
                    continue;
                }

                break;
            }

            return $clinics;
        }
    }