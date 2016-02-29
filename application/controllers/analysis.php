<?php
    class AnalysisController extends BaseController
    {
        public function index()
        {

            $this->view->city_id = $this->city->getId();
            $this->view->latitude = $this->city->lat;
            $this->view->longitude = $this->city->lng;

            $this->view->menu_active = 'analysis';

            $this->view->load_map = TRUE;

            $this->view->page_title = 'Найти лабораторию - «'.SITE_NAME.'»';
            $this->view->page_description = 'Найти лабораторию - вся информация обо всех известных заболеваниях на сервисе '.SITE_NAME.'';

            $this->view->canonical_link = '/analysis';
        }



        public function ajaxSearchLaboratory()
        {
            $this->layout = 'ajax';

            $landing = $this->request('landing');

            $this->view->landing_page = $landing;

            $params = new LaboratorySearchParams();
            $this->initLaboratorySearchParams($params);

			/**
			 * @var LaboratoryManager $laboratory_manager
			 */
			$laboratory_manager = ModelManagerFactory::getByName('laboratory');
            $laboratories = $laboratory_manager->getListByModelSearchCriteria($params);

            if($params->geo_point)
            {
                $bounds = $laboratory_manager->getBoundsByGeoPointAndDistance($params->geo_point, $params->distance);
            }

			$this->renderMapDataFile($laboratories, $params->getHash());

            $city = ModelManagerFactory::getByName('city')->getOneById($params->city_id);

            $result = array(
                'map'                     => $params->getHash(),
                'city_name'               => $city->name,
                'bounds' => isset($bounds) ? $bounds : null
            );

            JsonResponse::result($result);
        }

        private function initLaboratorySearchParams(LaboratorySearchParams $params)
        {
            $params->city_id = $this->request('city_id');

            $params->metro_station_name = $this->request('metro_station_name', '');
            $params->metro_branch_name = $this->request('metro_branch_name', '');

            $params->urgent_tests = (float)$this->request('urgent_tests', 0);
            $params->card_pay = (float)$this->request('card_pay', 0);
            $params->work_seven_days = (float)$this->request('work_seven_days', 0);
            $params->easy_entry = (float)$this->request('easy_entry', 0);
            $params->without_turn = (float)$this->request('without_turn', 0);
            $params->day_and_night = (float)$this->request('day_and_night', 0);

            $latitude = (float)$this->request('latitude', 0);
            $longitude = (float)$this->request('longitude', 0);
            $is_metro = $this->request('is_metro', 0);

            if ($latitude && $longitude) {
                $params->geo_point = new GeoPoint($latitude, $longitude);
                $params->is_metro = $is_metro;
            }

        }


        private function renderMapDataFile(array $laboratories, $hash)
        {
            if (!file_exists('/media/map/' . $hash . '.js')) {

                $str = '';

                if ($laboratories)
                    foreach ($laboratories as $laboratory) {
                        $str .= $laboratory->getId() . ':' .
                            $laboratory->address . ':' . $laboratory->name . ':' .
                            $laboratory->latitude . ':' . $laboratory->longitude . ':4|';
                    }

                $str = trim($str, '|');
                $this->view->info = $str;
                $filedata = $this->renderInString('blocks/map-data');

                file_put_contents('./media/map/' . $hash . '.js', $filedata);
            }
        }

		public function ajaxGetExistsOfStatusesByCityId()
		{
			$city_id = $this->request('city_id');

			/**
			 * @var LaboratoryManager $laboratory_manager
			 */
			$laboratory_manager = ModelManagerFactory::getByName('laboratory');

			$availability = $laboratory_manager->checkExistsOfStatusesByCityId($city_id);

			JsonResponse::result($availability);
		}
    }