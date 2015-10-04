<?php

    class ClinicController extends BaseController
    {
        public function get()
        {
            /**
             * @var ClinicManager         $clinic_manager
             * @var ClinicModel           $clinic
             * @var PurposeOfVisitManager $purposes_manager
             * @var EqualClinicManager    $equal_clinic_manager
             * @var EqualClinicModel[]    $equal_clinics
             */
            $landing = $this->request('landing');

            $this->view->landing_page = $landing;

            $clinic_id    = $this->request('id');
            $current_item = $this->getLandingPageItem($clinic_id);

            if($current_item)
            {
                $this->landingPage($clinic_id);
                exit;
            }

            $clinic_manager         = ModelManagerFactory::getByName('clinic');
            $specialization_manager = ModelManagerFactory::getByName('specialization');

            $clinic         = $clinic_manager->getOneByIdOrAliasAndIsActive($clinic_id);
            $specialization = $specialization_manager->getOneByAlias($clinic_id);

            if($specialization)
            {
                $this->index($clinic_id);
            }
            else
            {
                if(is_numeric($clinic_id) && $clinic->alias)
                {
                    RedirectManager::redirect301(ClinicPageLinkViewHelper::getLink($clinic));
                }

                LinkHelper::checkLinkIsCorrectIfThereIsNoAttemptRedirect($clinic, array('city' => $this->city, 'model' => 'clinic'));

                if(isset($clinic) && $clinic && $this->city->name == 'Москва')
                {
                    $equal_clinic_manager = ModelManagerFactory::getByName('equal_clinic');
                    $equal_clinics        = $equal_clinic_manager->getListByClinicId($clinic->getId());

                    if($equal_clinic_manager)
                    {
                        $this->view->equal_clinics = $equal_clinics;
                    }
                }

                $texts = array(
                    'about' => $clinic->about
                );

                if(!empty($texts) && count($texts) > 0)
                {
                    foreach($texts AS $tKey => $tValue)
                    {

                        preg_match_all("|<iframe(.*)/>|U", $tValue, $out, PREG_PATTERN_ORDER);
                        if(count($out[0]))
                        {
                            $replace = array();
                            foreach($out[0] AS $oValue) $replace[] = substr($oValue, 0, strlen($oValue) - 2) . '></iframe>';
                            $text          = str_replace($out[0], $replace, $tValue);
                            $clinic->$tKey = $text;
                        }
                    }
                }

                $this->view->clinic    = $clinic;
                $this->view->clinic_id = $clinic_id;

                $specialty_manager = new SpecialtyManager();

                $this->view->specialties = $specialty_manager->getSpecialtyListForClinic($clinic->getId());

                $clinic_review_manager      = new ClinicReviewManager();
                $clinic_rewies              = $clinic_review_manager->getConfirmedListByClinicIdWithPagging($clinic->getId(), 0, 4);
                $this->view->clinic_reviews = $clinic_rewies;

                $all_reviews             = $clinic_review_manager->getConfirmedListByClinicId($clinic->getId());
                $this->view->all_reviews = count($all_reviews);

                $purposes_manager     = ModelManagerFactory::getByName('purpose_of_visit');
                $purposes             = array();
                $purposes[]           = $purposes_manager->getOneByName('Первичный прием');
                $purposes[]           = $purposes_manager->getOneByName('Повторный прием');
                $this->view->purposes = $purposes;

                $clinic_metro           = ($clinic->metro_station_name) ? ', метро ' . $clinic->metro_station_name : '';
                $this->view->page_title = $clinic->name . ', ' . $clinic->city->name . $clinic_metro . ', ' . $clinic->address . ', отзывы, телефон, запись на прием - «LookMedBook»';
            }

            $spzn_id = $this->request('spzn_id', 0);

            if($spzn_id && empty($specialization))
            {
                $specialty_manager = ModelManagerFactory::getByName('specialty');

                $specialization = $specialization_manager->getOneByIdOrAlias($spzn_id);

                $main_specialty             = $specialty_manager->getMainOneBySpecializationId($specialization->getId());
                $this->view->main_specialty = $main_specialty;
            }
        }

        public function ajaxAddToMyClinicList()
        {
            $clinic_id  = $this->request->post('clinic_id');
            $account_id = Acc::accountId();
            $date       = date('Y-m-d H:i:s');

            if(!ModelManagerFactory::getByName('my_clinic')->checkExistsByClinicIdAndAccountId($clinic_id, $account_id))
            {
                $my_clinic             = new MyClinicModel();
                $my_clinic->account_id = $account_id;
                $my_clinic->clinic_id  = $clinic_id;
                $my_clinic->dt         = $date;
                if(ModelManagerFactory::getByName('my_clinic')->save($my_clinic))
                {
                    JsonResponse::result(array('my_clinic' => TRUE));
                }
                else
                {
                    JsonResponse::error(2);
                }
            }
            else
            {
                $my_clinic = ModelManagerFactory::getByName('my_clinic')->getOneByClinicIdAndAccountId($clinic_id, $account_id);
                ModelManagerFactory::getByName('my_clinic')->delete($my_clinic);
                JsonResponse::result(array('my_clinic' => FALSE));
            }
        }

        public function index($specialization_alias = NULL)
        {
            if($_SERVER['REQUEST_URI'] == '/clinic/search') ErrorPageViewHelper::page404('404');
            $landing = $this->request('landing');

            /**
             * @var SpecialtyManager $specialty_manager
             */
            $specialization_manager = ModelManagerFactory::getByName('specialization');
            $specialty_manager      = ModelManagerFactory::getByName('specialty');

            if(isset($_GET['specialty_id']))
            {
                $specialty = $specialty_manager->getOneById($_GET['specialty_id']);

                $query_string = preg_replace('/specialty_id=([0-9]+)?&?/', '', $_SERVER['QUERY_STRING']);

                $url = '';

                if($specialty)
                {
                    if($query_string)
                    {
                        if($query_string{0} != '?')
                        {
                            $query_string = '?' . $query_string;
                        }
                    }
                    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/clinic/' . $specialty->alias . $query_string;
                }
                else
                {
                    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/clinic' . $query_string;
                }

                RedirectManager::redirect301($url);
            }

            if(!$specialization_alias) $specialization_alias = $this->request('specialty');

            $specialization = NULL;

            if($specialization_alias)
            {
                $this->view->is_seo_page = 1;

                $specialization = $specialization_manager->getOneByAlias($specialization_alias);

                if(!$specialization)
                {
                    ErrorPageViewHelper::page404('404');
                }
            }

            $city      = $this->city;
            $latitude  = $this->city->lat;
            $longitude = $this->city->lng;
            $city_id   = $this->city->getId();

            $address_object = NULL;

            if($city)
            {
                $address_object             = $city;
                $this->view->address_object = $address_object;
            }

            $this->view->city            = $city;
            $this->view->city_id         = $city_id;
            $this->view->landing_page    = $landing;
            $this->view->specialization  = $specialization;
            $this->view->specializations = $specialization_manager->getSpecializationForCityIDInWhichHaveDoctors($city_id);

            $this->view->specialties        = $specialty_manager->getHavingDoctorsListByCityId($city_id);
            $this->view->specialties_groups = SpecialtyHelper::getSpecialtiesLetterGroups($this->view->specialties, array(), 1);
            $this->view->menu_active        = 'clinic';

            $this->view->city_id   = $city_id;
            $this->view->latitude  = $latitude;
            $this->view->longitude = $longitude;

            $this->view->load_map = TRUE;

            $this->view->page_title       = $this->getClinicPageTitle($specialization);
            $this->view->page_description = 'Найти клинику - вся информация обо всех известных заболеваниях на сервисе lookmedbook';

            $this->view->canonical_link = '/clinic';
            $this->view->page_type      = 'clinic';

            $this->render('clinic/search');
        }

        public function ajaxSearch()
        {
            $this->layout = 'ajax';

            $landing = $this->request('landing');

            $this->view->landing_page = $landing;

            $params = new ClinicSearchParams();
            $this->initClinicSearchParams($params);

            $clinic_search_algorithm = new ClinicSearchAlgorithm();
            $clinics                 = $clinic_search_algorithm->search($params);

            $specialization_manager = ModelManagerFactory::getByName('specialization');
            $specialization         = $specialization_manager->getOneById($params->specialization_id);

            if(!empty($params->clinic_name) && count($clinics) > 1)
            {
                $clinicsIds = $tmpClinics = array();

                foreach($clinics AS $cKey => $cVal)
                {
                    $similarNumber       = 0;
                    $clinicName          = $cVal->name;
                    $clinicNameMod       = str_replace('-', ' ', trim($cVal->name));
                    $paramsClinicName    = $params->clinic_name;
                    $paramsClinicNameMod = str_replace('-', ' ', trim($params->clinic_name));

                    if(stripos($clinicName, $paramsClinicName) !== FALSE || stripos($clinicNameMod, $paramsClinicNameMod) !== FALSE)
                    {
                        similar_text($paramsClinicName, $clinicName, $similarNumber);
                        $clinicsIds[$cKey] = $similarNumber;
                    }
                }

                if(count($clinicsIds) > 0)
                {
                    arsort($clinicsIds);

                    foreach($clinicsIds AS $ciKey => $ciValue)
                    {
                        $tmpClinics[] = $clinics[$ciKey];
                        unset($clinics[$ciKey]);
                    }
                    $clinics = array_merge($tmpClinics, $clinics);
                }
            }

            // Получаем результаты для карты
            $map_file = '';
            if($params->page == 1)
            {
                $map_file_generator = new ClinicMapDataGenerator();
                $map_file           = $map_file_generator->generate($params);

                if($params->geo_point)
                {
                    /**
                     * @var ClinicManager $clinic_manager
                     */
                    $clinic_manager = ModelManagerFactory::getByName('clinic');
                    $bounds         = $clinic_manager->getBoundsByGeoPointAndDistance($params->geo_point, $params->distance);
                }
            }

            // Получаем новый тайтл
            $page_title = '';
            if($params->specialty_id || $params->specialization_id)
            {
                $item = $params->specialty_id;

                if($params->specialization_id)
                {
                    $item = $specialization;
                }
                $page_title = $this->getClinicPageTitle($item);
            }

            foreach($clinics AS $cKey => $cValue)
            {
                $clinics[$cKey] = $this->processedClinicItem($cValue, $params, $specialization);
            }

            $this->view->clinics        = $clinics;
            $this->view->specialization = $specialization;
            $html                       = $this->renderInString('clinic/card_small_list');

            $is_empty_city = 0;
            $city          = ModelManagerFactory::getByName('city')->getOneById($params->city_id);
            $city_clinic   = ModelManagerFactory::getByName('clinic')->getOneByCityId($params->city_id);
            if($city->service_flag == 0 || (($city->service_flag == 1) && (!$city_clinic))) $is_empty_city = 1;

            $doctor_manager     = ModelManagerFactory::getByName('clinic');
            $clinic_total_count = $doctor_manager->getCountByModelSearchCriteria($params);
            $clinic_word_form   = SpecialtyHelper::getClinicWordForm($clinic_total_count);

            $specialty_name = '';

            if($params->specialty_id)
            {
                $specialty_name = SpecialtyHelper::getNameByCount($params->specialty_id, $clinic_total_count);
            }
            else if($params->specialization_id)
            {

                $specialization = $specialization_manager->getOneById($params->specialization_id);
                $specialty_name = $specialization->name;
            }

            $result = array(
                'html'                    => $html,
                'next_page'               => $clinic_search_algorithm->getNextPageFlag(),
                'full_search'             => $clinic_search_algorithm->getGoodSearchFlag(),
                'primary_clinics_id_list' => $clinic_search_algorithm->getPrimaryClinicsIds(),
                'map'                     => $map_file,
                'is_empty_city'           => $is_empty_city,
                'city_name'               => $city->name,
                'page_title'              => $page_title,
                'bounds'                  => (isset($bounds)) ? $bounds : NULL,
                'clinic_total_count'      => $clinic_total_count,
                'specialty_name'          => $specialty_name,
                'clinic_word_form'        => $clinic_word_form
            );

            JsonResponse::result($result);
        }

        private function processedClinicItem(ClinicModel $clinic, ClinicSearchParams $params, $specialization = NULL)
        {
            $additional_params = array();

            foreach($clinic->types AS $type)
            {
                $id = $type->getId();

                switch($id)
                {
                    case 5:
                    {
                        $additional_params['multidisciplinary'] = 1;
                        break;
                    }
                    case 11:
                    {
                        $additional_params['accepts-children'] = 1;
                        break;
                    }
                    case 28:
                    {
                        $additional_params['twenty-four-hours'] = 1;
                        break;
                    }
                }
            }

            foreach($clinic->features AS $feature)
            {
                if($feature->getId() == 14)
                {
                    $additional_params['have-ramp'] = 1;
                    break;
                }
            }

            foreach($clinic->services AS $service)
            {
                if($service->getId() == 1)
                {
                    $additional_params['medical-certificates'] = 1;
                    break;
                }
            }

            foreach($clinic->doctors AS $doctor)
            {
                if($doctor->is_leave_the_house)
                {
                    $additional_params['leave-the-house'] = 1;
                    break;
                }
            }

            if($clinic->only_adult)
            {
                $additional_params['accepts-children'] = 0;
            }

            if($clinic->is_card_pay)
            {
                $additional_params['payment-cards'] = 1;
            }

            if($params->specialization_id)
            {
                $params = clone $params;

                $doctor_search_algorithm = new DoctorSearchAlgorithm();
                $doctor_params           = new DoctorSearchParams();

                $specialty_manager = ModelManagerFactory::getByName('specialty');
                $main_specialty    = $specialty_manager->getMainOneBySpecializationId($params->specialization_id);

                if(!empty($main_specialty))
                {
                    $doctor_params->primary_doctors_ids = NULL;
                    $doctor_params->page                = NULL;
                    $doctor_params->by_page             = NULL;
                    $doctor_params->specialty_id        = $main_specialty->getId();
                    $doctor_params->clinic_id           = $clinic->getId();

                    $search_result = $doctor_search_algorithm->search($doctor_params);

                    $additional_params['doctors_main_specialty']['doctors'] = $search_result;
                    $additional_params['doctors_main_specialty']['count']   = count($search_result);

                }
            }

            if(empty($specialization))
            {
                $additional_params['doctors_main_specialty']['total_doctors']         = $clinic->doctors;
                $additional_params['doctors_main_specialty']['total_specializations'] = $clinic->specializations;
            }

            $clinic->additional_params = $additional_params;

            return $clinic;
        }

        private function initClinicSearchParams(ClinicSearchParams $params)
        {
            $landing_item = $this->request('landing_item_id', '');

            if(!empty($landing_item) && is_string($landing_item))
            {
                $type_page = $this->getLandingPageItem($landing_item);

                if($type_page instanceof ClinicServicesModel)
                {
                    $params->services = $type_page->id;
                }
                else
                {
                    $params->services = 0;
                }

                if($type_page instanceof ClinicTypeModel)
                {
                    $params->types = $type_page->id;
                }
                else
                {
                    $params->types = 0;
                }
            }

            $specialization_id = intval($this->request('specialization_id', 0));

            if($specialization_id > 0)
            {
                $params->specialization_id = $specialization_id;
            }
            else
            {
                $params->specialty_id = (int)$this->request('specialty_id', 0);
            }

            $params->purpose_of_visit_id = (int)$this->request('purpose_of_visit_id', 0);
            $params->children            = $this->request('children', 0);
            $params->handicapped         = $this->request('handicapped', 0);
            $params->pregnant            = $this->request('pregnant', 0);
            $params->day_and_night       = $this->request('day_and_night', 0);
            $params->clinic_name         = $this->request('clinic_name', '');
            $params->sort_by             = $this->request('sort_by', 'rate');
            $params->city_id             = $this->city->getId();
            $params->not_show_example    = TRUE;
            $params->is_active           = 1;
            $params->metro_station_name  = $this->request('metro_station_name', '');
            $params->metro_branch_name   = $this->request('metro_branch_name', '');
            $params->district_id         = $this->request('district_id');
            $params->region_id           = $this->request('region_id');
            $params->street_id           = $this->request('street_id');
            $params->twenty_four_hours   = $this->request('twenty_four_hours', 0);
            $params->is_card_pay         = $this->request('is_card_pay', 0);
            $params->have_ramp           = $this->request('have_ramp', 0);

            $latitude  = (float)$this->request('latitude', 0);
            $longitude = (float)$this->request('longitude', 0);
            $is_metro  = $this->request('is_metro', 0);

            if($latitude && $longitude)
            {
                $params->geo_point = new GeoPoint($latitude, $longitude);
                $params->is_metro  = $is_metro;
            }
            $clinic_type = $this->request('clinic_type');
            if(!empty($clinic_type) && $clinic_type == 'children')
            {
                $params->only_children = 1;
            }

            $params->page    = $this->request('page', 1);
            $params->by_page = $this->request('by_page', 10);
        }

        public function ajaxGetDoctorsList()
        {
            $this->layout = 'ajax';

            $clinic_id           = $this->request('clinic_id', 0);
            $specialty_id        = $this->request('specialty_id', 0);
            $purpose_of_visit_id = $this->request('purpose_of_visit_id', 0);
            $time_of_visit       = $this->request('time_of_visit', '');

            $page = $this->request('page');

            $search_params = new SearchParams();

            if($clinic_id)
            {
                $search_params->addJoin('doctor_specialty_to_clinic');
                $params = array(
                    'doctor_specialty_to_clinic.clinic_id'    => $clinic_id,
                    'doctor_specialty_to_clinic.is_to_delete' => NULL,
                );
                $search_params->setParamsList($params);
                //$search_params->addParam('doctor_specialty_to_clinic.clinic_id', $clinic_id);

                $clinic_manager     = new ClinicManager();
                $clinic             = $clinic_manager->getOneById($clinic_id);
                $this->view->clinic = $clinic;
            }

            if($specialty_id)
            {
                $specialty_manager = new SpecialtyManager();
                $specialty         = $specialty_manager->getOneById($specialty_id);

                $specialty_id_list = array();

                $specialty_id_list[] = $specialty->getId();

                // Убрал, чтобы не выводило дочерние specialty
                /*if ($specialty->childs) {
                    $search_params->addSortParam('doctor_specialty_to_clinic.specialty_id', array($specialty->getId()), 'DESC');
                    foreach($specialty->childs as $child_specialty)
                    {
                        $specialty_id_list[] = $child_specialty->getId();
                    }
                }*/

                $search_params->addJoin('doctor_specialty_to_clinic');
                $search_params->addParam('doctor_specialty_to_clinic.specialty_id IN', $specialty_id_list);
            }

            if($purpose_of_visit_id)
            {
                $search_params->addJoin('purpose_of_visit_to_doctor');
                $search_params->addParam('purpose_of_visit_to_doctor.purpose_of_visit_id', $purpose_of_visit_id);
            }

            if($time_of_visit == 'morning')
            {
                $search_params->addParam('is_has_morning_time', 1);
            }
            else if($time_of_visit == 'evening')
            {
                $search_params->addParam('is_has_evening_time', 1);
            }
            else if($time_of_visit == 'weekend')
            {
                $search_params->addParam('is_has_weekend_time', 1);
            }
            else if($time_of_visit == 'leave_house')
            {
                $search_params->addParam('is_leave_the_house', 1);
            }

            $search_params->addParam('is_active', 1);

            $search_params->setGetExtraEntry();


            if($page == 1)
            {
                $search_params->setOffsetAndLimit(0, 10);
            }
            else
            {
                $offset = 10 + ($page - 2) * 10;
                $search_params->setOffsetAndLimit($offset, 10);
            }


            $search_params->addJoin('specialty', '`specialty`.id', '`doctor_specialty_to_clinic`.specialty_id');

			$search_params->addSortParam('rate', 'DESC');
            $search_params->addSortParam('specialty.name', 'ASC');

            $doctor_manager = new DoctorManager();
            $doctors        = $doctor_manager->getListBySearchParams($search_params);

            /*$specialization_manager = new SpecializationManager();
            $specializations = $specialization_manager->getListByClinicId($clinic_id);
            $doctor_specializations = $specialization_manager->getSpecializationsForClinicWithDoctors($clinic_id);*/

            $count = count($doctors);

            if($count == 0) JsonResponse::error(2);

            if($count == 11)
            {
                unset($doctors[10]);
                $more_button = TRUE;
            }
            else
            {
                $more_button = FALSE;
            }

            $doctors             = DoctorPriceHelper::getPricesForDoctor($doctors, $clinic_id, $specialty_id);
            $this->view->doctors = $doctors;

            $this->view->specialty_id             = $specialty_id;
            $this->view->specialtyIDForDoctorCard = $specialty_id;
            $this->view->purpose_of_visit_id      = $purpose_of_visit_id;

            $html = $this->renderInString('doctor/card_big_list');
            JsonResponse::result(array(
                                     'html'        => $html,
                                     'count'       => $count,
                                     'more_button' => $more_button
                                 ));
        }

        function ajaxGetReviewsList()
        {
            $this->layout = 'ajax';

            $clinic_id = $this->request('clinic_id', 0);
            $page      = $this->request('page', 1);

            $clinic_review_manager = new ClinicReviewManager();
            $clinic_manager        = new ClinicManager();

            $offset  = 4 + ($page - 2) * 10;
            $reviews = $clinic_review_manager->getConfirmedListByClinicIdWithPagging($clinic_id, $offset, 10);

            $clinic = $clinic_manager->getOneById($clinic_id);

            $this->view->clinic  = $clinic;
            $this->view->reviews = $reviews;

            $count = count($reviews);
            $html  = $this->renderInString('blocks/reviews-list');

            JsonResponse::result(array(
                                     'html'  => $html,
                                     'count' => $count
                                 ));
        }

        private function getClinicPageTitle($specialty)
        {
            if(!is_integer($specialty))
            {
                if(get_class($specialty) == 'ClinicServicesModel')
                {
                    if($specialty->plural_name)
                    {
                        return 'Найти клинику оказывающую услугу "' . StringHelper::startProposalWord($specialty->plural_name) . '". Адреса и телефоны медицинских центров Москвы и других городов России - «LookMedBook»';
                    }
                }
                else if(get_class($specialty) == 'ClinicTypeModel')
                {
                    if($specialty->genitive_name)
                    {
                        return 'Найти ' . $specialty->genitive_name . '. Адреса и телефоны медицинских центров Москвы и других городов России - «LookMedBook»';
                    }
                }
            }

            if(is_object($specialty) && get_class($specialty) == 'SpecialtyModel' && $specialty->id)
            {
                $specialty_id = $specialty->id;

                /**
                 * @var SpecializationManager $specialization_manager
                 */
                $specialization_manager = ModelManagerFactory::getByName('specialization');
                $adj                    = $specialization_manager->getAdjectiveNameBySpecialtyId($specialty_id);

                if($adj)
                {
                    return StringHelper::startProposalWord($adj) . ' центры и клиники в Москве. Запись на прием онлайн, фото, цены, отзывы – Lookmedbook';
                }
            }

            if(is_object($specialty) && get_class($specialty) == 'SpecializationModel' && $specialty->id)
            {
                if($specialty->adjective_name)
                {
                    return StringHelper::startProposalWord($specialty->adjective_name) . ' центры и клиники в Москве. Запись на прием онлайн, фото, цены, отзывы – Lookmedbook';
                }
            }

            return $this->view->page_title = 'Найти клинику. Адреса и телефоны медицинских центров Москвы и других городов России - «LookMedBook»';
        }

        private function getLandingPageItem($landing_page_alias)
        {
            $clinic_services_manager = ModelManagerFactory::getByName('clinic_services');
            $service                 = $clinic_services_manager->getItemByAlias($landing_page_alias);

            $clinic_type_manager = ModelManagerFactory::getByName('clinic_type');
            $type                = $clinic_type_manager->getItemByAlias($landing_page_alias);

            $result_item = $service ? $service : ($type ? $type : array());

            if(empty($result_item) || !$result_item->perceived_as_page) return array();
            else return $result_item;
        }

        private function getMixedArrayConsistingOfServicesAndTypes()
        {
            $clinic_services_manager = ModelManagerFactory::getByName('clinic_services');
            $clinic_type_manager     = ModelManagerFactory::getByName('clinic_type');

            $services = $clinic_services_manager->getList();
            $types    = $clinic_type_manager->getList();

            $services_index_update = array();
            $types_index_update    = array();

            foreach($services AS $sValue)
            {
                if($sValue->alias && $sValue->perceived_as_page)
                {
                    $services_index_update[] = $sValue;
                }
            }

            foreach($types AS $tValue)
            {
                if($tValue->alias && $tValue->perceived_as_page)
                {
                    $types_index_update[] = $tValue;
                }
            }


            $mixed_array = array_merge($services_index_update, $types_index_update);

            return $mixed_array;
        }

        public function landingPage($landing_page_alias)
        {
            if(!$landing_page_alias) $landing_page_alias = $this->request('landing_page_alias');

            $current_item = $this->getLandingPageItem($landing_page_alias);

            if(!$current_item)
            {
                $this->get($landing_page_alias);
                exit;
            }

            $services_and_types = $this->getMixedArrayConsistingOfServicesAndTypes();

            $city    = $this->city;
            $city_id = $city->getId();

            if($city)
            {
                $address_object             = $city;
                $this->view->address_object = $address_object;
            }

            $district_manager = new DistrictManager();
            $district         = NULL;
            $district_alias   = $this->request('district');

            if($district_alias)
            {
                $district = $district_manager->getOneByAlias($district_alias);
                if(!$district || $district->city_id != $city->getId()) ErrorPageViewHelper::page404('404');
            }

            $region_manager = new RegionManager();
            $region         = NULL;
            $region_alias   = $this->request('region');

            if($region_alias)
            {
                $region = $region_manager->getOneByAlias($region_alias);

                if(!$region || $region->district_id != $district->getId()) ErrorPageViewHelper::page404();
            }

            $street_manager = new StreetManager();
            $street         = NULL;
            $street_alias   = $this->request('street');

            if($street_alias)
            {
                $street = $street_manager->getOneByAlias($street_alias);

                if(!$street || !$street->isBelongToDistrict($district->getId())) ErrorPageViewHelper::page404();
            }

            $metro_station_manager = new MetroStationManager();
            $metro_station         = NULL;
            $metro_alias           = $this->request('metro');

            if($metro_alias)
            {
                $metro_station = $metro_station_manager->getOneByAlias($metro_alias);

                if(!$metro_station || ($metro_station->region_id != $region->getId())) ErrorPageViewHelper::page404();
            }

            $region_street_alias = $this->request('region_street');

            if($region_street_alias)
            {
                $region = $region_manager->getOneByAlias($region_street_alias);

                if(!$region) $street = $street_manager->getOneByAlias($region_street_alias);

                if(!$region && !$street) ErrorPageViewHelper::page404();

                if($region && ($region->district_id != $district->getId())) ErrorPageViewHelper::page404();

                if($street && (!$street->isBelongToDistrict($district->getId()))) ErrorPageViewHelper::page404();
            }

            $this->view->district      = $district;
            $this->view->region        = $region;
            $this->view->street        = $street;
            $this->view->metro_station = $metro_station;

            if($current_item || $city || $street_alias || $region_alias || $region_street_alias || $district_alias)
            {

                $this->view->is_seo_page = 1;

                $address_object = NULL;

                if($metro_station)
                {
                    $address_object = $metro_station;
                }
                elseif($street)
                {
                    $address_object = $street;
                }
                elseif($region)
                {
                    $address_object = $region;
                }
                elseif($district)
                {
                    $address_object = $district;
                }
                elseif($city)
                {
                    $address_object = $city;
                }
            }

            $search_page_description = '';
            if((!empty($district) || !empty($region) || !empty($metro_station) || !empty($street)) && isset($address_object) && $address_object
            )
            {
                $search_page_description = SeoTextViewHelper::getClinicPageDescription($current_item, $address_object);
            }
            $specialty_manager      = ModelManagerFactory::getByName('specialty');
            $specialization_manager = ModelManagerFactory::getByName('specialization');

            $page_title = $this->getClinicPageTitle($current_item);

            $this->view->district                = $district;
            $this->view->region                  = $region;
            $this->view->street                  = $street;
            $this->view->metro_station           = $metro_station;
            $this->view->specialties             = $specialty_manager->getHavingDoctorsListByCityId($city_id);
            $this->view->specializations         = $specialization_manager->getSpecializationForCityIDInWhichHaveDoctors($city_id);
            $this->view->services_and_types      = $services_and_types;
            $this->view->menu_active             = 'clinic';
            $this->view->load_map                = TRUE;
            $this->view->landing_page            = TRUE;
            $this->view->page_title              = $page_title;
            $this->view->page_description        = $page_title;
            $this->view->canonical_link          = '/clinic';
            $this->view->page_type               = 'clinic';
            $this->view->is_seo_page             = 1;
            $this->view->current_item            = $current_item;
            $this->view->search_page_description = $search_page_description;

            $this->render('clinic/search');
        }
    }