<?php

class DoctorController extends BaseController
{
    public $layout = 'home';

    public function get()
    {
        $landing = $this->request('landing');
        $recording = $this->request('recording');

        $this->view->landing_page = $landing;
        $this->view->recording = $recording;
        $doctor_id = $this->request('id');

        /**
         * @var DoctorManager $doctor_manager
         * @var DoctorModel $doctor
         */
        $doctor_manager = ModelManagerFactory::getByName('doctor');

        /**
         * @var SpecialtyManager $specialty_manager
         */
        $specialty_manager = ModelManagerFactory::getByName('specialty');

        $landing_specialty_list = array('oftalmolog2', 'allergolog-immunolog2', 'nevrolog2', 'otolaringolog2');

        $landing_alias = $doctor_id;
        if (in_array($doctor_id, $landing_specialty_list)) {
            $doctor_id = str_replace('2', '', $doctor_id);
        }

        $specialty  =   null;

        
        $district_manager = new DistrictManager();
        $metro_manager = new MetroStationManager();
        $region_manager = new RegionManager();

        preg_match('/([A-Za-z]+)-((metro-[A-Za-z\-]+))/',$landing_alias,$v);

        $district   =   null;
        $metro      =   null;
        $region     =   null;
        if (isset($v) && count($v)) {
            foreach ($v as $val) {
                if ($district==null)  $district = $district_manager->getOneByAlias($val);
                if ($metro==null)     $metro    = $metro_manager->getOneByAlias($val);
                if ($region==null)    $region   = $region_manager->getOneByAlias($val);
                if ($specialty==null) $specialty= $specialty_manager->getOneByAlias($val);
                
            };
        } else {
          $district = $district_manager->getOneByAlias($landing_alias);
          $metro    = $metro_manager->getOneByAlias($landing_alias);
          $region   = $region_manager->getOneByAlias($landing_alias);
          $specialty = $specialty_manager->getOneByAlias($landing_alias);
        }
        if ($specialty!=null || $district!=null || $region!=null || $metro!=null) {
            $this->index($specialty->alias, $district, $metro, $region);
            unset($specialty);
            unset($district);
            unset($metro);
            unset($region);            
            
/*
        if ($region){
            $this->index(null, null, null, $region);
            unset($specialty);
            unset($district);
            unset($metro);
            unset($region);
        }elseif ($metro){
            $this->index(null, null, $metro);
            unset($specialty);
            unset($district);
            unset($metro);
        }elseif ($district){
            $this->index(null, $district);
            unset($specialty);
            unset($district);
        }elseif ($specialty) {
            $this->index($landing_alias);
            unset($specialty);
*/
        } else {
            $doctor = $doctor_manager->getOneByIdOrAlias($doctor_id);

            if (is_null($doctor)){
                RedirectManager::redirect301('/doctor');
            }


            $doc_id = $doctor->getId();

            if (is_numeric($doctor_id) && $doctor->alias) {
                RedirectManager::redirect301(DoctorPageLinkViewHelper::getLink($doctor));
            }

            LinkHelper::checkLinkIsCorrectIfThereIsNoAttemptRedirect($doctor, array('city' => $this->city, 'model' => 'doctor'));

            /**
             * @var DoctorReviewManager $doctor_review_manager
             */
            $doctor_review_manager = ModelManagerFactory::getByName('doctor_review');
            $reviews = $doctor_review_manager->getConfirmedListByDoctorIdWithPagging($doc_id, 0, 4);
            $this->view->reviews = $reviews;
            unset($reviews);

            $texts = array(
                'about' => $doctor->about,
                'education' => $doctor->education,
                'academic_title' => $doctor->academic_title,
                'course' => $doctor->course,
                'certificate' => $doctor->certificate,
            );

            if (!empty($texts) && count($texts) > 0) {
                foreach ($texts AS $tKey => $tValue) {
                    preg_match_all("|<iframe(.*)/>|U", $tValue, $out, PREG_PATTERN_ORDER);
                    if (count($out[0])) {
                        $replace = array();
                        foreach ($out[0] AS $oValue) {
                            $replace[] = substr($oValue, 0, strlen($oValue) - 2) . '></iframe>';
                        }
                        $text = str_replace($out[0], $replace, $tValue);
                        $doctor->$tKey = $text;
                    }
                }
            }

            $this->view->doctor = $doctor;

            $doctor_review_manager = new DoctorReviewManager();
            $reviews = $doctor_review_manager->getConfirmedListByDoctorIdWithPagging($doc_id, 0, 4);
            $this->view->reviews = $reviews;
            unset($reviews);

            $all_reviews = $doctor_review_manager->getConfirmedListByDoctorId($doc_id);
            $this->view->all_reviews = count($all_reviews);
            unset($all_reviews);


            $account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
            $this->view->account = $account;

            /**
             * @var DoctorScheduleManager $doctor_schedule_manager
             */
            $doctor_schedule_manager = ModelManagerFactory::getByName('doctor_schedule');
            $doctor_schedules = array();

            $account_phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId(Acc::accountId());
            $this->view->account_phone = $account_phone;
            unset($account_phone);


            $relations = ModelManagerFactory::getByName('family_relation_status')->getList();
            $this->view->relations = $relations;
            unset($relations);

            $this->view->page_title = 'Врач ' . mb_strtolower($doctor->specialties_names, 'utf-8') . ', ' . $doctor->full_name . ' - «'.SITE_NAME.'»';

            foreach ($doctor->clinics as $clinic) {
                foreach ($doctor->specialties as $specialty) {
                    $shed = $doctor_schedule_manager->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doc_id, $clinic->id, $specialty->id);
                    if ($shed) {
                        $doctor_schedules[] = $shed;
                    }
                }
            }
            $this->view->doctor_schedules = $doctor_schedules;
            unset($doctor_schedules);
        }



        $this->view->city_id = $this->city->getId();

        $purpose_prices = array();
        if (!empty($doctor->clinics)) {
            foreach ($doctor->clinics AS $clinic) {
                $first_price = $second_price = 0;
                foreach ($doctor->specialties AS $specialty) {
                    $first_price = $doctor->getFirstVisitPrice($clinic->id, $specialty->id);
                    $second_price = $doctor->getSecondVisitPrice($clinic->id, $specialty->id);
                }
                if (!$first_price) {
                    $first_price = $doctor->getFirstVisitPrice($clinic->id);
                }
                if (!$second_price) {
                    $second_price = $doctor->getSecondVisitPrice($clinic->id);
                }
                $purpose_prices[$clinic->id]['first_price']['name'] = 'Первичный прием';
                $purpose_prices[$clinic->id]['first_price']['price'] = $first_price;
                $purpose_prices[$clinic->id]['second_price']['name'] = 'Вторичный прием';
                $purpose_prices[$clinic->id]['second_price']['price'] = $second_price;
            }
        }


        if (isset($doctor) && $doctor && $this->city->name == 'Москва') {
            /**
             * @var EqualDoctorManager $equal_doctor_manager
             * @var EqualDoctorModel $equal_doctors
             */

            $equal_doctor_manager = ModelManagerFactory::getByName('equal_doctor');
            $equal_doctors = $equal_doctor_manager->getListByDoctorId($doc_id);

            if ($equal_doctors) {
                $this->view->equal_doctors = $equal_doctors;
                unset($equal_doctors);
            }
        }
        $this->view->purpose_prices = $purpose_prices;

        $doctor_to_slider = array();
        if (!empty($doctor)) {
            $this->setRecentlyViewedDoctor($doc_id);
            $doctor_to_slider = $this->getDoctorsToSlider($doc_id);
        }

        $this->view->doctor_to_slider = $doctor_to_slider;

        $this->view->single_doctor_page = 1;

        $this->view->page_type = 'doctor';
    }


    private function getDoctorsToSlider($doctor_id) {
        $clinics = [];
        $specialties = [];
        $doctor_search_params = new DoctorSearchParams();
        $doctor_search_params->doctor_id =$doctor_id;
        $doctor_search_params->_id =$doctor_id;
        $doctor_search_params->group_by = 'ds2c';
        $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
        $clinics_and_specialities = $doctor_specialty_to_clinic_manager->getListByDoctorSearchParams($doctor_search_params);
        foreach($clinics_and_specialities as $rec) {
            $clinics[$rec->clinic_id] = $rec->clinic;
            $specialties[$rec->specialty_id] = $rec->specialty;
        }

        $recentlyViewedDoctorIds = $this->getRecentlyViewedDoctorIds();
        $doctor_manager = ModelManagerFactory::getByName('doctor');
        $title = '';

        if (count($recentlyViewedDoctorIds) > 0) {
            foreach ($recentlyViewedDoctorIds AS $rvdiKey => $rvdiValue) {
                if ($rvdiValue == $doctor_id) {
                    unset($recentlyViewedDoctorIds[$rvdiKey]);
                }
            }
        }
        if (count($recentlyViewedDoctorIds)) {
            $doctors = $doctor_manager->getDoctorsTheListOfIdentifiers($recentlyViewedDoctorIds);
            $title = $this->getDoctorBlockTitle('recently');
        } else {
            $clinics_names = array();
            $specialties_names = array();

            $clinic_ids = [];
            //все врачи тех же специальностей, которые работают в клиниках текущего врача
            foreach ($clinics AS $clinic) {
                $clin_id = $clinic->getId();
                $clinic_ids[] = $clin_id;
                $clinics_names[$clin_id] = $clinic->name;
            }

            //все врачи специальностей текущего врача из всех базы
            foreach ($specialties AS $specialty) {
                $spec_id = $specialty->getId();
                $specialties_ids[] = $spec_id;
                $specialties_names[$spec_id] = $specialty->name;
            }

            $doctors = [];
            $doctor_search_params = new DoctorSearchParams();
            $doctor_search_params->specialty_ids = $specialties_ids;
            $doctor_search_params->clinics_ids = $clinic_ids;
            $doctor_search_params->group_by = 'doctor';

            $clinics_and_specialities = $doctor_specialty_to_clinic_manager->getListByDoctorSearchParams($doctor_search_params);
            foreach($clinics_and_specialities as $rec) {
                if($rec->doctor_id != $doctor_id) {
                    $doctors[] = $rec->doctor;
                }
                if(count($doctors)>50) { break; }
            }
            unset($doctor_search_params);

            $data = array(
                'type' => 'clinic_specialty',
                'data' => array(
                    'clinics' => $clinics,
                    'specialties' => $specialties
                )
            );

            $title = $this->getDoctorBlockTitle($data);
        }

        $doctors = count($doctors) > 0 ? $doctors : [];

        if (empty($doctors)) {
            $specialties_names = [];

            $regions_ids = [];
            foreach ($clinics AS $clinic) {
                $regions_ids[] = $clinic->region_id;
            }
            $specialties_ids = [];
            foreach ($specialties AS $specialty) {
                $spec_id = $specialty->getId();
                $specialties_ids[] = $spec_id;
                $specialties_names[$spec_id] = $specialty->name;
            }

            $doctor_search_params = new DoctorSearchParams();
            $doctor_search_params->specialty_ids = $specialties_ids;
            $doctor_search_params->regions_ids = $regions_ids;
            $doctor_search_params->group_by = 'doctor';

            $clinics_and_specialities = $doctor_specialty_to_clinic_manager->getListByDoctorSearchParams($doctor_search_params);
            foreach($clinics_and_specialities as $rec) {
                if($rec->doctor_id != $doctor_id) {
                    $doctors[] = $rec->doctor;
                }
                if(count($doctors)>50) { break; }
            }
            unset($doctor_search_params);

            $data = array(
                'type' => 'other',
                'data' => $specialties,
            );
            $title = $this->getDoctorBlockTitle($data);
        }

        return array(
            'doctors' => count($doctors) > 0 ? $doctors : array(),
            'title' => $title
        );
    }

    private function getDoctorBlockTitle($data)
    {
        $type = $title = '';

        if ((is_array($data) && !empty($data['type']))) {
            $type = $data['type'];
        } else if (is_string($data)) {
            $type = $data;
        }

        switch ($type) {
            case 'recently':
                $title = 'Вы недавно смотрели:';

                break;

            case 'clinic_specialty':
                $title = $this->getTitleForDoctorsSameClinic($data['data']);

                break;

            case 'other':
                $specialties_name = array();

                foreach ($data['data'] AS $dValue) {
                    $specialties_name[] = $dValue->plural_name;
                }

                $title = 'Другие врачи ' . implode(', ', $specialties_name) . ':';
        }

        return $title ? $title : "Другие врачи:";
    }

    private function getTitleForDoctorsSameClinic($data)
    {
        $clinics = $data['clinics'];
        $specialties = $data['specialties'];

        $specialties_for_title = array();
        $clinics_for_title = array();

        foreach ($specialties AS $sValue) {
            if ($sValue->plural_name) {
                $specialties_for_title[] = $sValue->plural_name;
            }
        }

        foreach ($clinics AS $cValue) {
            if ($cValue->name) {
                $clinics_for_title[] = $cValue->name;
            }
        }

        if (count($specialties_for_title) > 0) {
            $title = 'Врачи <span>' . implode('</span>, <span>', $specialties_for_title) . '</span>';
        } else return '';

        if (count($clinics_for_title) > 1) {
            $title .= ' в клиниках: <span>' . implode('</span>, <span>', $clinics_for_title) . '</span>:';
        } else if (count($clinics_for_title) > 0) {
            $title .= ' в клинике <span>' . $clinics_for_title[0] . '</span>:';
        } else return '';

        return $title;
    }


    private function createSessionPathForRecentlyViewedDoctor()
    {
        $_SESSION['doctor_page'] = array();
        $_SESSION['doctor_page']['recently_viewed'] = array(
            'ids' => array(),
            'timestamp' => array()
        );
    }

    private function getTimestampForRecentlyViewedDoctor()
    {
        $timestamp = $_SESSION['doctor_page']['recently_viewed']['timestamp'];

        return $timestamp ? $timestamp : 0;
    }

    private function getRecentlyViewedDoctorIds()
    {
        $ids = $_SESSION['doctor_page']['recently_viewed']['ids'];

        return count($ids) ? $ids : array();
    }

    private function getAddRecentlyViewedDoctorId($doctor_id)
    {
        $_SESSION['doctor_page']['recently_viewed']['ids'][] = $doctor_id;
        $_SESSION['doctor_page']['recently_viewed']['timestamp'] = time() + (60 * 60 * 24);
    }

    private function setRecentlyViewedDoctor($doctor_id)
    {
        if (empty($_SESSION['doctor_page'])) {
            $this->createSessionPathForRecentlyViewedDoctor();
        } else if ($this->getTimestampForRecentlyViewedDoctor() < time()) {
            unset($_SESSION['doctor_page']);
            $this->createSessionPathForRecentlyViewedDoctor();
        }

        if (!in_array($doctor_id, $this->getRecentlyViewedDoctorIds())) {
            $this->getAddRecentlyViewedDoctorId($doctor_id);
        }
    }


    public function index($specialty_alias = NULL, $district = 0, $metro_station = 0, $region = 0)
    {
        if ($_SERVER['REQUEST_URI'] == '/doctor/search')
            ErrorPageViewHelper::page404('404');

        $landing = $this->request('landing');
        
        $specialty_manager = ModelManagerFactory::getByName('specialty');

        if (isset($_GET['specialty_id']) && $_GET['specialty_id']) {
            $specialty = $specialty_manager->getOneById($_GET['specialty_id']);
            if ($specialty) {
                $query_string = preg_replace('/specialty_id=([0-9]+)?&?/', '', $_SERVER['QUERY_STRING']);
                if ($query_string)
                    $query_string = '?' . $query_string;
                $url = 'http://' . $_SERVER['HTTP_HOST'] . '/doctor/' . $specialty->alias . $query_string;
                RedirectManager::redirect301($url);
            }
        }

        $this->view->landing_page = $landing;

        $this->view->page_title = 'Найти врача - «'.SITE_NAME.'»';
        $this->view->page_description = 'Найти врача - вся информация обо всех известных заболеваниях на сервисе '.SITE_NAME.'';

        $this->view->menu_active = 'doctor';

        if (!$specialty_alias) {
            $specialty_alias = $this->request('specialty');
        }
        if (!$specialty_alias) {
            $specialty_alias = 'terapevt';
            $setDefaultSpecialty = 1;
        }

        $landing_specialty_list = array('oftalmolog2', 'allergolog-immunolog2', 'nevrolog2', 'otolaringolog2');

        if (in_array($specialty_alias, $landing_specialty_list)) {
            if (isset($_GET['v']) && $_GET['v'] == 'new') {

                $this->layout = 'landing';

                $specialty_alias = str_replace('2', '', $specialty_alias);

                if (!$specialty_alias) {
                    ErrorPageViewHelper::page404('404');
                    exit();
                }

                /**
                 * @var SpecialtyManager $specialty_manager
                 */

                $specialty_manager = ModelManagerFactory::getByName('specialty');
                $specialty = $specialty_manager->getOneByAlias($specialty_alias);

                if (!$specialty) {
                    ErrorPageViewHelper::page404('404');
                    exit();
                }

                $this->view->doctor_experiment = $specialty_alias;
                $this->view->counter_page_index = AbTestParameterHelper::getPageCodeBySpecialtyAlias($specialty_alias);
                $this->view->specialty = $specialty;
                $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
                $this->render('landing/index');
                exit();
            } else {
                $specialty_alias = str_replace('2', '', $specialty_alias);
                $this->view->doctor_experiment = $specialty_alias;
            }
        }


        $specialty = NULL;
        if ($specialty_alias) {
            $specialty = $specialty_manager->getOneByAlias($specialty_alias);
            if (!$specialty) {
                ErrorPageViewHelper::page404('404');
            }
        }

        $city = $this->city;
        $latitude = $city->lat;
        $longitude = $city->lng;
        $city_id = $city->getId();

        if (!$district){
            $district_manager = new DistrictManager();
            $district = NULL;
            $district_alias = $this->request('district');
            if ($district_alias) {
                $district = $district_manager->getOneByAlias($district_alias);
                if (!$district || $district->city_id != $city->getId())
                    ErrorPageViewHelper::page404('404');
            }
        }



        if (!$region)
        {
            $region_manager = new RegionManager();
            $region = NULL;
            $region_alias = $this->request('region');
            if ($region_alias) {
                $region = $region_manager->getOneByAlias($region_alias);
                if (!$region || $region->district_id != $district->getId())
                    ErrorPageViewHelper::page404();
            }
        }else{
            if (!$district){
                $district = $region->parent;
            }
        }


        $street_manager = new StreetManager();
        $street = NULL;
        $street_alias = $this->request('street');
        if ($street_alias) {
            $street = $street_manager->getOneByAlias($street_alias);
            if (!$street || !$street->isBelongToDistrict($district->getId()))
                ErrorPageViewHelper::page404();
        }


        if (!$metro_station)
        {
            $metro_station_manager = new MetroStationManager();
            $metro_station = NULL;
            $metro_alias = $this->request('metro');
            if ($metro_alias) {
                $metro_station = $metro_station_manager->getOneByAlias($metro_alias);
                if (!$metro_station || ($metro_station->region_id != $region->getId()))
                    ErrorPageViewHelper::page404();
            }
        }


        $region_street_alias = $this->request('region_street');
        if ($region_street_alias) {
            $region = $region_manager->getOneByAlias($region_street_alias);

            if (!$region)
                $street = $street_manager->getOneByAlias($region_street_alias);

            if (!$region && !$street)
                ErrorPageViewHelper::page404();

            if ($region && ($region->district_id != $district->getId()))
                ErrorPageViewHelper::page404();

            if ($street && (!$street->isBelongToDistrict($district->getId())))
                ErrorPageViewHelper::page404();
        }

        $this->view->specialties = $specialty_manager->getHavingDoctorsListByCityId($city_id);
        $this->view->specialties_groups = SpecialtyHelper::getSpecialtiesLetterGroups($this->view->specialties, array(), 1);

        $this->view->city = $city;
        $this->view->city_id = $city_id;
        $this->view->latitude = $latitude;
        $this->view->longitude = $longitude;

        $this->view->load_map = TRUE;

        $this->view->district = $district;
        $this->view->region = $region;
        $this->view->street = $street;
        $this->view->metro_station = $metro_station;

        if ($specialty_alias || $city || $street_alias || $region_alias || $region_street_alias || $district_alias) {

            $this->view->is_seo_page = 1;

            $address_object = NULL;
            $previous_address = '/doctor';
            $previous_address .= ($this->request('specialty')) ? '/' . $this->request('specialty') : '/terapevt';
            $doctor_manager = new DoctorManager();
            if ($metro_station) {
                $address_object = $metro_station;
                if ($district && $doctor_manager->checkExistsBySpecialtyIdAddressObject($specialty->getId(), $district)) {
                    $previous_address .= '/' . $this->request('district');
                    if (($street && $doctor_manager->checkExistsBySpecialtyIdAddressObject($specialty->getId(), $street)) ||
                        ($region && $doctor_manager->checkExistsBySpecialtyIdAddressObject($specialty->getId(), $region))
                    ) {
                        $previous_address .= '/' . $this->request('region');
                    }
                }
            } elseif ($street) {
                $address_object = $street;
                if ($district && $doctor_manager->checkExistsBySpecialtyIdAddressObject($specialty->getId(), $district)) {
                    $previous_address .= '/' . $this->request('district');
                }
            } elseif ($region && is_object($specialty)) {
                $address_object = $region;
                if ($doctor_manager->checkExistsBySpecialtyIdAddressObject($specialty->getId(), $district)) {
                    $previous_address .= '/' . $this->request('district');
                }
            } elseif ($district) {
                $address_object = $district;
            } elseif ($city) {
                $address_object = $city;
            }

            $this->view->address_object = $address_object;

            if ($specialty) {
                $doctor_manager = new DoctorManager();
                if (!$doctor_manager->checkExistsBySpecialtyIdAddressObject($specialty->getId(), $address_object)) {
                    RedirectManager::redirect($previous_address);
                }
            }

            if (isset($setDefaultSpecialty) && $setDefaultSpecialty) {
                $this->view->page_title = SeoTextViewHelper::getTitle($specialty, $address_object, 0, 1);
            } else {
                $this->view->page_title = SeoTextViewHelper::getTitle($specialty, $address_object, 1);
            }
            $this->view->page_description = SeoTextViewHelper::getDescription($specialty, $address_object);
        }
        $this->view->specialty = $specialty;

        $this->view->address = new Address();
        $this->view->address->city_id = $city ? $city->getId() : FALSE;
        $this->view->address->district_id = $district ? $district->getId() : FALSE;
        $this->view->address->region_id = $region ? $region->getId() : FALSE;
        $this->view->address->street_id = $street ? $street->getId() : FALSE;
        $this->view->setDefaultSpecialty = !empty($setDefaultSpecialty) ? $setDefaultSpecialty : 0;
        if (($this->view->address->district_id ||
                $this->view->address->region_id ||
                $this->view->address->street_id) &&
            isset($address_object) && $address_object
        ) {
            $this->view->search_page_description = SeoTextViewHelper::getDoctorPageDescription($specialty, $address_object);
        }
        $this->view->canonical_link = AliasLinkViewHelper::getLink('doctor', $specialty);
        $this->view->site_url_not_using = true;
        $this->view->page_type = 'doctor';

        $search_params = $this->getSessionSearchParams();
        $this->view->search_params_json = $search_params ? json_encode($search_params) : '';

        $b_param = $this->request('b');
        $this->view->is_green = ($b_param && $b_param == 'green') ? 1 : 0;
        
        $this->view->noWrap = 1;
        print_r($this);die();
        $this->render('doctor/search');
    }

    public function card()
    {
        $this->layout = 'ajax';

        // id врача
        $did = (int)$this->request('did');

        // id клиники
        $cid = (int)$this->request('cid');

        $doctor_manager = new DoctorManager();
        $doctor = $doctor_manager->getOneById($did);

        if (!$doctor || !$doctor->is_active) {
            $this->view->error_code = '#1';
            ErrorPageViewHelper::page404('404');
            exit;
        }

        $this->view->doctor = $doctor;
        if ($cid) {
            //Клиника
            $doctor_clinics = unserialize($doctor->small_card);
            if (isset($doctor_clinics[$cid])) {
                $this->render('card_small');

                return;
            } else {
                $this->view->error_code = '#2';
                ErrorPageViewHelper::page404('404');

                return;
            }
        } else {
            //Доктор
            $this->render('card_big');
        }

    }

    public function ajaxAddToMyDoctorList()
    {
        // todo: закомментировали, т.к. открыли страницы для неавторизованных пользователей
        /*if (!Acc::isAuthed())
          $this->redirectUrl('/');*/

        $doctor_id = $this->request->post('doctor_id');
        $account_id = Acc::accountId();
        $date = date('Y-m-d H:i:s');

        if (!ModelManagerFactory::getByName('my_doctor')->checkExistsByDoctorIdAndAccountId($doctor_id, $account_id)) {
            $my_doctor = new MyDoctorModel();
            $my_doctor->account_id = $account_id;
            $my_doctor->doctor_id = $doctor_id;
            $my_doctor->dt = $date;
            if (ModelManagerFactory::getByName('my_doctor')->save($my_doctor)) {
                JsonResponse::result(array('my_doctor' => TRUE));
            } else {
                JsonResponse::error(2);
            }
        } else {
            $my_doctor = ModelManagerFactory::getByName('my_doctor')->getOneByDoctorIdAndAccountId($doctor_id, $account_id);
            ModelManagerFactory::getByName('my_doctor')->delete($my_doctor);
            JsonResponse::result(array('my_doctor' => FALSE));
        }
    }

    public function ajaxGetScheduleTimes()
    {
        // todo: закомментировали, т.к. открыли страницы для неавторизованных пользователей
        //if (!Acc::isAuthed()) $this->redirectUrl('/');

        $week_counter = $_POST['week_counter'];
        $doctor_id = $_POST['doctor_id'];

        $doctor_manager = new DoctorManager();
        $doctor = $doctor_manager->getOneById($doctor_id);

        $schedule_manager = new ScheduleManager();
        $work_times = $schedule_manager->getListByDoctorIdAndWeekCounterAndIsBusy($doctor_id, $week_counter);

        $time_string = '';

        if (date("w") == 1) $cur_monday = date("d.m.Y");
        else $cur_monday = date("d.m.Y", strtotime("last monday"));
        $cur_monday = date_create($cur_monday);

        if (date("w") == 7) $cur_sunday = date("d.m.Y");
        else $cur_sunday = date("d.m.Y", strtotime("next sunday"));
        $cur_sunday = date_create($cur_sunday);

        if ($week_counter > 0) {
            $counter = ($week_counter * 7) . ' days';
            date_add($cur_monday, date_interval_create_from_date_string($counter));
            date_add($cur_sunday, date_interval_create_from_date_string($counter));
        } else if ($week_counter < 0) {
            $counter = ($week_counter * 7) . ' days';
            date_add($cur_monday, date_interval_create_from_date_string($counter));
            date_add($cur_sunday, date_interval_create_from_date_string($counter));
        }

        $cur_monday = date_format($cur_monday, 'd-m-Y');
        $cur_sunday = date_format($cur_sunday, 'd-m-Y');
        $time_string .= '<strong>Неделя ' . $cur_monday . ' - ' . $cur_sunday . '</strong>';

        if ($work_times) {
            foreach ($work_times as $time) {
                if ($time->dt_start >= date('Y-m-d H:i:s'))
                    $time_string .= '<p><a id="' . $time->id . '" class="time">' . $time->dt_start . '</a> в ' . $time->clinic->name . '</p> ';
            }
        } else {
            $time_string .= '<p>Извините, на этой неделе доктор ' . $doctor->full_name . ' не принимает или его расписание уже занято</p>';
        }
        JsonResponse::result(array('doctor_times' => $time_string));
    }

    public function ajaxGetScheduleTimesByDay()
    {
        // todo: закомментировали, т.к. открыли страницы для неавторизованных пользователей
        //if (!Acc::isAuthed()) $this->redirectUrl('/');

        $week_counter = $this->request('day_counter');
        $doctor_id = $this->request('doctor_id');

        $doctor_manager = new DoctorManager();
        $doctor = $doctor_manager->getOneById($doctor_id);

        $schedule_manager = new ScheduleManager();
        $work_times = $schedule_manager->getListByDoctorIdAndDayCounterAndIsBusy($doctor_id, $week_counter);

        $time_string = '';

        if ($work_times)
            foreach ($work_times as $time) {
                $cur_day = preg_replace('/[0-9]{2}\:[0-9]{2}\:[0-9]{2}/', '', $time->dt_start);
                $time_string = '<strong> Расписание на ' . $cur_day . '</strong>';
                break;
            }

        if ($work_times) {
            foreach ($work_times as $time) {
                if ($time->dt_start >= date('Y-m-d H:i:s')) {
                    $cur_day_date = preg_replace('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', '', $time->dt_start);
                    $time_string .= '<p><a id="' . $time->id . '" class="time" style="cursor:pointer">' . $cur_day_date . '</a> в ' . $time->clinic->name . '</p> ';
                    $time_string .= '<script>$("#doctor-day-next").css("display", "block"); </script>';
                }
            }
        } else {
            $time_string .= '<br><br><p>Извините, в этот день доктор ' . $doctor->full_name . ' не принимает или расписание уже занято</p>';
            $time_string .= '<script>$("#doctor-day-next").css("display", "none"); </script>';
        }
        JsonResponse::result(array('doctor_times' => $time_string));
    }

    public function ajaxGetScheduleSingleDay()
    {
        // todo: закомментировали, т.к. открыли страницы для неавторизованных пользователей
        //if (!Acc::isAuthed()) $this->redirectUrl('/');

        $schedule_id = $_POST['schedule_id'];
        $day_string = '';

        $schedule_day = ModelManagerFactory::getByName('schedule')->getOneById($schedule_id);
        $day = date_create($schedule_day->dt_start);
        $day = date_format($day, 'd F Y г. l');

        $day_string .= $day . '<br>';

        $selected_day = date_create($schedule_day->dt_start);
        $selected_day = date_format($selected_day, 'Y-m-d');
        $schedule_times = ModelManagerFactory::getByName('schedule')->getListByDoctorIdAndDtStartAndIsBusy($schedule_day->doctor_id, $selected_day);

        $day_string .=
            '<select class="doctor-single-time">
						<option value="0">Время</option>';
        foreach ($schedule_times as $time) {
            $time_from = date_create($time->dt_start);
            $time_from = date_format($time_from, 'H:i');

            if ($time_from >= date('H:i'))
                $day_string .= '<option value="' . $time->id . '">' . $time_from . '</option>';
        }
        $day_string .= '</select>';

        JsonResponse::result(array('doctor_day' => $day_string));
    }

    public function ajaxSaveAccountFullName()
    {
        if (!Acc::isAuthed()) $this->redirectUrl('/');

        $name = $_POST['name'];

        ModelManagerFactory::getByName('account')->setFullNameByAccountId(Acc::accountId(), $name);
        JsonResponse::result(array('is_data_saved' => TRUE));
    }

    public function ajaxSaveVisitData()
    {
        if (!Acc::isAuthed()) $this->redirectUrl('/');

        $schedule_id = $_POST['schedule_id'];
        $doctor_id = $_POST['doctor_id'];
        $purpose_id = $_POST['purpose_id'];
        $family_relation_status_id = $_POST['family_relation_status_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $code = $_POST['code'];
        $date = date('Y-m-d H:i:s');

        $account_phone = ModelManagerFactory::getByName('account_phone')->getOneByAccountIdAndPhoneAndCode(Acc::accountId(), $phone, $code);
        if ($account_phone) {

            $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);
            if ($doctor->was_first_visit) {
                $price = 1000;
                $is_first_visit = 0;
            } else {
                $price = 1200;
                $is_first_visit = 1;
            }
            $visit = new VisitModel();
            $visit->account_id = Acc::accountId();
            $visit->schedule_id = $schedule_id;
            $visit->account_phone_id = $account_phone->id;
            $visit->is_confirmed = 1;
            $visit->confirm_code = $code;
            $visit->confirm_dt = $date;
            $visit->price = $price;
            $visit->is_first_visit = $is_first_visit;
            $visit->purpose_of_visit_id = $purpose_id;

            $schedule = ModelManagerFactory::getByName('schedule')->getOneById($schedule_id);

            $dinner_hour = date('Y-m-d 12:00:00', strtotime($schedule->dt_start));
            if (strtotime($schedule->dt_start) > strtotime($dinner_hour)) {
                $visit->notification_dt = date('Y-m-d H:i:00', (strtotime($schedule->dt_start) - 60 * 60 * SettingsManager::get('notification_visit_evening_h')));
            } else {
                $visit->notification_dt = date('Y-m-d H:i:00', (strtotime($schedule->dt_start) - 60 * 60 * SettingsManager::get('notification_visit_morning_h')));
            }

            if (ModelManagerFactory::getByName('visit')->save($visit)) {
                ModelManagerFactory::getByName('account_phone')->setIsConfirmedById($account_phone->id);

                $visit = ModelManagerFactory::getByName('visit')->getOneByScheduleId($schedule_id);
                ModelManagerFactory::getByName('schedule')->setIsBusyAndVisitIdById($schedule_id, 1, $visit->id);

                $account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());

                if ($name != $account->full_name) {
                    $family_member = new FamilyRelationModerateModel();
                    $family_member->account_id = Acc::accountId();
                    $family_member->full_name = $name;
                    $family_member->family_relation_status_id = $family_relation_status_id;

                    $family_account = ModelManagerFactory::getByName('account')->getOneByFullName($name);
                    if ($family_account) {
                        $family_member->to_account_id = $family_account->id;
                    }
                    ModelManagerFactory::getByName('family_relation_moderate')->save($family_member);
                }

                JsonResponse::result(array('is_data_saved' => TRUE));
            } else {
                JsonResponse::result(array('is_data_saved' => FALSE, 'message' => 'Данное время уже прошло! Пожалуйста, выберите актуальное время'));
            }
        } else
            JsonResponse::result(array('is_data_saved' => FALSE, 'message' => 'Неправильно указан код подтверждения!'));
    }

    public function ajaxUpdateVisitTime()
    {
        if (!Acc::isAuthed()) $this->redirectUrl('/');

        $schedule_id = $this->request->post('schedule_id');
        $visit_id = $this->request->post('visit_id');

        $visit_manager = new VisitManager();
        $visit = $visit_manager->getOneById($visit_id);

        $schedule_manager = new ScheduleManager();
        $schedule = $schedule_manager->getOneById($schedule_id);

        $old_schedule = $schedule_manager->getOneById($visit->schedule_id);

        if ($visit && $schedule && $old_schedule) {

            $schedule->visit_id = $visit->getId();
            $schedule->is_busy = 1;
            $schedule_manager->save($schedule);

            $old_schedule->is_busy = NULL;
            $old_schedule->visit_id = NULL;
            $schedule_manager->save($old_schedule);

            $visit->schedule_id = $schedule_id;
            $dinner_hour = date('Y-m-d 12:00:00', strtotime($schedule->dt_start));
            if (strtotime($schedule->dt_start) > strtotime($dinner_hour)) {
                $visit->notification_dt = date('Y-m-d H:i:00', (strtotime($schedule->dt_start) - 60 * 60 * SettingsManager::get('notification_visit_evening_h')));
            } else {
                $visit->notification_dt = date('Y-m-d H:i:00', (strtotime($schedule->dt_start) - 60 * 60 * SettingsManager::get('notification_visit_morning_h')));
            }

            $visit_manager->save($visit);
            JsonResponse::result(TRUE);
        } else {
            JsonResponse::error(41);
        }


    }

    public function ajaxSetPhoneNumber()
    {
        if (!Acc::isAuthed()) $this->redirectUrl('/');

        $phone = $_POST['phone'];
        $code = $_POST['code'];
        $date = date('Y-m-d H:i:s');

        $account_phone = ModelManagerFactory::getByName('account_phone')->getOneByAccountIdAndPhone(Acc::accountId(), $phone);
        if ($account_phone) {
            ModelManagerFactory::getByName('account_phone')->setCodeAndDtById($account_phone->id, $code, $date);
            JsonResponse::result(array('is_code_saved' => TRUE));
        } else {
            $new_phone = new AccountPhoneModel();
            $new_phone->account_id = Acc::accountId();
            $new_phone->phone = $phone;
            $new_phone->code = $code;
            $new_phone->dt = $date;

            $new_phone->setValidator(new WithoutValidator());

            if (ModelManagerFactory::getByName('account_phone')->save($new_phone)) {
                JsonResponse::result(array('is_code_saved' => TRUE));
            } else {
                JsonResponse::result(array('is_code_saved' => FALSE));
            }
        }
    }

    public function ajaxGetVisitPrice()
    {
        if (!Acc::isAuthed()) $this->redirectUrl('/');

        $doctor_id = $_POST['doctor_id'];

        $day_string = '';

        $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);
        if ($doctor) {
            $day_string .= '<h2>Цена за прием</h2>';
            if (!$doctor->was_first_visit)
                $day_string .= '<div>Первый визит: 1200 руб.</div>';
            else
                $day_string .= '<div>Повторный визит: 1000 руб.</div>';

            JsonResponse::result(array('doctor_price' => $day_string));
        } else {
            JsonResponse::result(array('doctor_price' => FALSE));
        }
    }

    public function paramsFormFieldsSpecialty($specialtyID, DoctorSearchParams $doctor_search_params)
    {
        $params = array();

        $verifiableField = array(
            'specialty_id',
            'alias',
            'doctor_type',
        );

        if (gettype($specialtyID) == 'integer' && !$specialtyID) {
            $doctor_search_params->specialty_id = $specialtyID = 29;
            $doctor_search_params->alias = 'terapevt';
        }

        if ($specialtyID) {
            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $specialty = $specialty_manager->getOneByIdOrAlias($specialtyID);

            $specialtyAlias = $specialty->alias;

            $specialtyParams = array(
                'pediatr' => array(
                    'tableFields' => array(
                        'doctor_type' => 'children'
                    ),
                    'formFields' => array(
                        'disable' => array(
                            'doctor-type-adult'
                        ),
                        'activate' => array(
                            'doctor_type' => 'children'
                        )
                    )
                )
            );
            $params = isset($specialtyParams[$specialtyAlias]) ? $specialtyParams[$specialtyAlias] : array();

            if ($params) {
                foreach ($verifiableField AS $vfValue) {
                    if (!empty($params['tableFields'][$vfValue])) {
                        $doctor_search_params->$vfValue = $params['tableFields'][$vfValue];
                    }
                }
            }
        }

        return $params;
    }

    protected function getSessionSearchParams()
    {
        $class = 'DoctorModel';

        if (!empty($_SESSION[$class]['doctor_search_params'])) {
            return $_SESSION[$class]['doctor_search_params'];
        } else {
            return FALSE;
        }

    }

    protected function getSearchParams()
    {
        $doctor_search_params = new DoctorSearchParams();
        $this->initDoctorSearchParams($doctor_search_params);

        $key = 'search_params_return';
        $specialty_id = $doctor_search_params->specialty_id;

        $url_parts = explode("/", empty($_SERVER['HTTP_REFERER'])?'':$_SERVER['HTTP_REFERER']);
        $last_part = $url_parts[count($url_parts) - 1];
        $url_params = explode("?", $last_part);
        $search_params_key = $url_params[0];

        $_SESSION['last_search_params'] = $doctor_search_params;
        $_SESSION['last_search_params']->search_params_key = $search_params_key;

        if (count($url_parts) > 4 || $search_params_key) {
            if (
                !empty($_SESSION[$key][$specialty_id][$search_params_key]) &&
                isset($_SESSION[$key][$specialty_id][$search_params_key]->return) &&
                $_SESSION[$key][$specialty_id][$search_params_key]->return
            ) {
                $doctor_search_params = $_SESSION[$key][$specialty_id][$search_params_key];
                $_SESSION[$key][$specialty_id][$search_params_key]->return = 0;
            } else if (empty($_SESSION[$key][$specialty_id][$search_params_key])
                || $doctor_search_params->specialty_id != $_SESSION[$key][$specialty_id][$search_params_key]->specialty_id
                || !$_SESSION[$key][$specialty_id][$search_params_key]->return
            ) {
                $search_params = clone $doctor_search_params;
                $search_params->return = 0;
                $_SESSION[$key][$specialty_id][$search_params_key] = $search_params;
                $doctor_search_params = $search_params;
            }
        }

        return $doctor_search_params;
    }


    public function ajaxSearch__address_object__district($city_id) {
        $district_manager = new DistrictManager();
        $district = NULL;
        $district_id = $this->request('district_id');

        if ($district_id) {
            $district = $district_manager->getOneById($district_id);
            if (!$district || $district->city_id != $city_id) ErrorPageViewHelper::page404('404');
        }
        return $district;
    }


    public function ajaxSearch__address_object__region($district) {
        $region_manager = new RegionManager();
        $region = NULL;
        $region_id = $this->request('region_id');

        if ($region_id and $district) {
            $region = $region_manager->getOneById($region_id);

            if (!$region || $region->district_id != $district->getId()) ErrorPageViewHelper::page404();
        }
        return $region;
    }


    public function ajaxSearch__address_object__street($district) {
        $street_manager = new StreetManager();
        $street = NULL;
        $street_id = $this->request('street_id');

        if ($street_id and $district) {
            $street = $street_manager->getOneById($street_id);

            if (!$street || !$street->isBelongToDistrict($district->getId())) ErrorPageViewHelper::page404();
        }
        return $street;
    }


    public function ajaxSearch__address_object__metro($region) {
        $metro_station_manager = new MetroStationManager();
        $metro_station = NULL;
        $metro_id = $this->request('metro_station_id');

        if ($metro_id and $region) {
            $metro_station = $metro_station_manager->getOneById($metro_id);

            if (!$metro_station || ($metro_station->region_id != $region->getId())) ErrorPageViewHelper::page404();
        }


        return $metro_station;
    }

    public function ajaxSearch__address_object() {
        $city = $this->city;
        $city_id = $city->getId();

        $district = $this->ajaxSearch__address_object__district($city_id);
        $region = $this->ajaxSearch__address_object__region($district);
        $street = $this->ajaxSearch__address_object__street($district);
        $metro_station = $this->ajaxSearch__address_object__metro($region);

        $this->view->district = $district;
        $this->view->region = $region;
        $this->view->street = $street;
        $this->view->metro_station = $metro_station;

        $address_object = NULL;
        if ($metro_station) {
            $address_object = $metro_station;
        } elseif ($street) {
            $address_object = $street;
        } elseif ($region) {
            $address_object = $region;
        } elseif ($district) {
            $address_object = $district;
        } elseif ($city) {
            $address_object = $city;
        }

        return $address_object;
    }


    public function ajaxSearch_specialty($doctor_search_params) {
        $specialty_manager = ModelManagerFactory::getByName('specialty');
        $specialty = $specialty_manager->getOneByIdOrAlias($doctor_search_params->specialty_id);

        $this->view->specialty = $specialty;
        $this->view->specialties = $specialty_manager->getHavingDoctorsListByCityId($this->city->getId());
        $this->view->specialties_groups = SpecialtyHelper::getSpecialtiesLetterGroups($this->view->specialties);
        return $specialty;
    }

    // уебанский способ - выбирает всех врачей из базы прямо сюда, а потом делает срез, выкидывая остатки.
    // надо будет сделать нормальную выборку.
    public function ajaxSearch__search_wo_doctname($doctor_search_params, $doctor_manager, $specialty, $doctors, $exclude_doctor_ids) {
        if (!empty($doctor_search_params->doctor_name)) {
            return [$doctors, 0, 0];
        }

        $total_doctors_data = $doctor_manager->getTotalDoctorsForAllRelatedSpecialties($specialty, $doctor_search_params);

        $total_number_doctors = $total_doctors_data['doctors_total_count'];
        $total_doctors = $total_doctors_data['total_doctors'];

        if (!$total_number_doctors) {
            $total_doctors = $doctor_manager->doctorsForRelatedSpecialty($doctor_search_params, $specialty->getId());
            $total_number_doctors = count($total_doctors);
            if($exclude_doctor_ids) foreach($total_doctors as $i=>$doctor){
                if(in_array($doctor->id, $exclude_doctor_ids)){
                    unset($total_doctors[$i]);
                }
            }
        } //new DoctorModel()
        $rated_doctors = [];
        $total_cnt = count($total_doctors);
        for($i=0;$i<$total_cnt;$i++) {
            $doctor = array_shift($total_doctors);
            $rate = $doctor->rate;
            $rated_doctors[$rate][] = $doctor;
        }

        $doctors = [];
        krsort($rated_doctors);
        foreach($rated_doctors as $rate=>&$doc_list) {
            shuffle($rated_doctors[$rate]);
            $doc_list_cnt = count($doc_list);
            for($i=0;$i<$doc_list_cnt;$i++) {
                if(count($doctors)>=($doctor_search_params->by_page+1)) {
                    break;
                }
                $doctors[] = array_shift($rated_doctors[$rate]);
            }
        }

        if(isset($doctors[$doctor_search_params->by_page])) {
            $getNextPageFlag = 1;
            unset($doctors[$doctor_search_params->by_page]);
        }else{
            $getNextPageFlag = 0;
        }
        return [$doctors, $total_number_doctors, $getNextPageFlag];
    }

    public function ajaxSearch__clinics_count($doctors, $doctor_search_params) {
        $clinics_count = 0;
        if ($doctors) {
            /**
             * @var DoctorSpecialtyToClinicManager $doctor_specialty_to_clinic_manager
             * @var DoctorSpecialtyToClinicModel $doctor_clinics
             */

            $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
            $doctor_clinics = $doctor_specialty_to_clinic_manager->getListByDoctorSearchParams($doctor_search_params);
            $clinics_count = count($doctor_clinics);
        }

        return $clinics_count;
    }

    public function ajaxSearch__view_params($search_specialty_id, $specialty_id, $doctors, $purpose_of_visit_id) {
        $this->view->specialty_id = $search_specialty_id;
        $this->view->specialtyIDForDoctorCard = $specialty_id;

        $this->view->doctors = $doctors;
        $this->view->purpose_of_visit_id = $purpose_of_visit_id;
        $this->view->search_page = TRUE;

        $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
        $this->view->is_seo_page = TRUE;
        $this->view->noWrap = TRUE;

    }

    public function ajaxSearch__map_file($doctor_search_params) {
        $map_file = '';
        if ($doctor_search_params->page == 1) {
            $map_file_generator = new DoctorMapDataGenerator();
            $map_file = $map_file_generator->generate($doctor_search_params);
        }
        return $map_file;
    }


    public function ajaxSearch__bounds($doctor_search_params) {
        $bounds = null;

        if ($doctor_search_params->district_id || $doctor_search_params->region_id || $doctor_search_params->street_id) {
            $address = new Address();
            $address->region_id = $doctor_search_params->region_id;
            $address->district_id = $doctor_search_params->district_id;
            $address->street_id = $doctor_search_params->street_id;
            $address->metro_station_id = $doctor_search_params->metro_station_id;

            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $bounds = $clinic_manager->getBoundsByAddress($address);
        }

        if ($doctor_search_params->geo_point) {
            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $bounds = $clinic_manager->getBoundsByGeoPointAndDistance($doctor_search_params->geo_point, $doctor_search_params->distance);
        }

        return $bounds;
    }

    public function ajaxSearch__search_page_description($address_object, $specialty)  {
        $search_page_description = '';
        if ((isset($this->view->address->district_id) ||
                isset($this->view->address->region_id) ||
                isset($this->view->address->street_id))
            && isset($address_object) && $address_object
        ) {
            $search_page_description = SeoTextViewHelper::getDoctorPageDescription($specialty, $address_object);
        }

        return $search_page_description;
    }


    public function ajaxSearch() {
        $this->layout = 'ajax';
        $this->view->page_type = 'doctor';

        $landing = $this->request('landing');
        $exclude_doctor_ids = $this->request('exclude_doctor_ids', []);

        if (!$landing && !Acc::isAuthed()) {
            JsonResponse::error(4);
        }

        $this->view->landing_page = $landing;
        $doctor_search_params = $this->getSearchParams();

        $specialtyParams = $this->paramsFormFieldsSpecialty($doctor_search_params->specialty_id, $doctor_search_params);

        //поиск по имени - значит без остальной фильтрации
        if ($doctor_search_params->doctor_name) {
            $doctor_search_params->without_filters = 1;
        }

        //умолчальные настройки фильтра специальности
        if (empty($doctor_search_params->specialty_id) || !$doctor_search_params->specialty_id) {
            $doctor_search_params->specialty_id = 29;
            $doctor_search_params->alias = 'terapevt';
        }

        $address_object = $this->ajaxSearch__address_object();
        $this->view->address_object = $address_object;

        $specialty = $this->ajaxSearch_specialty($doctor_search_params);

        //for primary doctors
        $doctor_search_algorithm = new DoctorSearchAlgorithm();
        $doctors = $doctor_search_algorithm->search($doctor_search_params);

        $doctor_manager = ModelManagerFactory::getByName('doctor');
        list($doctors, $total_number_doctors, $getNextPageFlag) = $this->ajaxSearch__search_wo_doctname($doctor_search_params, $doctor_manager, $specialty, $doctors, $exclude_doctor_ids);

        $filter_active = 0;
        $clinics_count = $this->ajaxSearch__clinics_count($doctors, $doctor_search_params);

        $doctors_total_count = !empty($total_number_doctors) ? $total_number_doctors : $doctor_manager->getCountByModelSearchCriteria($doctor_search_params);

        if (empty($doctor_search_params->doctor_name)) {
            $doctor_word_form = SpecialtyHelper::getDoctorWordForm($total_number_doctors);
        }
        $doctor_word_form = !empty($doctor_word_form) ? $doctor_word_form : SpecialtyHelper::getDoctorWordForm($doctors_total_count);


        $specialty_name = SpecialtyHelper::getNameByCount($doctor_search_params->specialty_id, $doctors_total_count);

        $this->ajaxSearch__view_params($doctor_search_params->specialty_id, $specialty->id, $doctors, $doctor_search_params->purpose_of_visit_id);

        $html = $this->renderInString('doctor/card_big_list');

        $map_file = $this->ajaxSearch__map_file($doctor_search_params);

        $bounds = $this->ajaxSearch__bounds($doctor_search_params);

        $is_empty_city = 0;
        $city_doctor = NULL;
        if ($doctor_search_params->city_id and !$this->city->service_flag) {
            $is_empty_city = 1;
        }

        $search_page_description = $this->ajaxSearch__search_page_description($address_object, $specialty);

        $defaultSpecialty = 0;
        if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'terapevt') === FALSE && $doctor_search_params->specialty_id == 29) {
            $topNumberH1 = SeoTextViewHelper::getTopNumberH1('', $this->city);
            $defaultSpecialty = 1;
        } else {
            $topNumberH1 = SeoTextViewHelper::getTopNumberH1($specialty, $address_object);
        }

        if (!empty($doctor_search_params->doctor_name)) {
            $getNextPageFlag = $doctor_search_algorithm->getNextPageFlag();
        }

        $get_good_search_flag = count($doctors) == 0 ? false : $doctor_search_algorithm->getGoodSearchFlag();

        $canonicalLink = AliasLinkViewHelper::getLink('doctor', $specialty);

        $result = array(
            'html' => $html,
            'next_page' => $getNextPageFlag,
            'full_search' => $get_good_search_flag,
            'primary_doctors_ids' => $doctor_search_algorithm->getPrimaryDoctorsIds(),
            'map' => $map_file,
            'is_empty_city' => $is_empty_city,
            'city_name' => $this->city ? $this->city->name : 'Москва',
            'latitude' => $this->city ? $this->city->lat : '56,7558',
            'longitude' => $this->city ? $this->city->lng : '37,6176',
            'doctors_total_count' => $doctors_total_count,
            'specialty_name' => $specialty_name,
            'doctor_word_form' => $doctor_word_form,
            'bounds' => $bounds,
            'clinics_count' => $clinics_count,
            'filter_active' => $filter_active,
            'isset_region' => ($doctor_search_params->region_id && !$doctor_search_params->street_id && !$doctor_search_params->metro_station_id) ? 1 : 0,
            'specialty_params' => $specialtyParams,
            'search_page_description' => $search_page_description,
            'topNumberH1' => $topNumberH1,
            'canonicalLink' => $canonicalLink,
            'defaultSpecialty' => $defaultSpecialty
        );

        JsonResponse::result($result);
    }

    private function initDoctorSearchParams(DoctorSearchParams $doctor_search_params)
    {
        $doctor_search_params->specialty_id = (int)$this->request('specialty_id', 0);
        $doctor_search_params->purpose_of_visit_id = (int)$this->request('purpose_of_visit_id', 0);
        $doctor_search_params->visit_type = $this->request('visit_type', 'clinic');
        $doctor_search_params->doctor_type = $this->request('doctor_type', 'adult');
        $doctor_search_params->morning_time = $this->request('morning_time', 0);
        $doctor_search_params->evening_time = $this->request('evening_time', 0);
        $doctor_search_params->weekend_time = $this->request('weekend_time', 0);
        $doctor_search_params->any_time = $this->request('any_time', 0);
        $doctor_search_params->doctor_name = $this->request('doctor_name', '');
        $doctor_search_params->doctor_sex_id = (int)$this->request('doctor_sex_id', 0);
        $doctor_search_params->sort_by = $this->request('sort_by', 'recomend');
        $doctor_search_params->city_id = $this->city->getId();
        $doctor_search_params->district_id = $this->request('district_id');
        $doctor_search_params->region_id = $this->request('region_id');
        $doctor_search_params->street_id = $this->request('street_id');
        $doctor_search_params->metro_station_name = $this->request('metro_station_name', '');
        $doctor_search_params->metro_branch_name = $this->request('metro_branch_name', '');
        $doctor_search_params->metro_station_id = $this->request('metro_station_id', '');
        $doctor_search_params->primary_doctors_ids = $this->request('primary_doctors_ids', array());
        $doctor_search_params->disease_doctor = $this->request('disease_doctor', FALSE);

        $latitude = (float)$this->request('latitude', 0);
        $longitude = (float)$this->request('longitude', 0);
        $is_metro = $this->request('is_metro', 0);

        if ($latitude && $longitude) {
            $doctor_search_params->geo_point = new GeoPoint($latitude, $longitude);
            $doctor_search_params->is_metro = $is_metro;
        }

        if (!$doctor_search_params->morning_time && !$doctor_search_params->evening_time && !$doctor_search_params->weekend_time) {
            $doctor_search_params->any_time = 1;
        }

        if ($doctor_search_params->doctor_sex_id == 3) {
            $doctor_search_params->doctor_sex_id = 0;
        }

        $doctor_search_params->page = $this->request('page', 1);
        $doctor_search_params->by_page = $this->request('by_page', 10);
    }

    private function renderMapDataFile(SearchParams $search_params, $hash, $clinics_id_list = array(), $primary_doctors)
    {
        //if (!file_exists('./media/map/' . $hash . '.js')) {
        $search_params->removePaging();
        $doctors = ModelManagerFactory::getByName('doctor')->getListBySearchParams($search_params);

        $str = '';

        if ($doctors)
            foreach ($doctors as $doctor) {
                if ($doctor->clinics) {
                    foreach ($doctor->clinics as $clinic) {
                        if ($clinics_id_list && !isset($clinics_id_list[$clinic->getId()]))
                            continue;
                        $str .= $doctor->getId() . '-' . $clinic->getId() . ':' .
                            $clinic->address . ':' . $doctor->full_name . ':' .
                            $clinic->latitude . ':' . $clinic->longitude . ':4|';
                    }
                }
            }

        if ($primary_doctors)
            foreach ($primary_doctors as $doctor) {
                if ($doctor->clinics) {
                    foreach ($doctor->clinics as $clinic) {
                        if ($clinics_id_list && !isset($clinics_id_list[$clinic->getId()]))
                            continue;
                        $str .= $doctor->getId() . '-' . $clinic->getId() . ':' .
                            $clinic->address . ':' . $doctor->full_name . ':' .
                            $clinic->latitude . ':' . $clinic->longitude . ':4|';
                    }
                }
            }

        $str = trim($str, '|');
        $this->view->info = $str;
        $filedata = $this->renderInString('blocks/map-data');

        file_put_contents('./media/map/' . $hash . '.js', $filedata);
        //}
    }

    function getDoctorPhotos()
    {
        $this->layout = 'image_slider';

        //if (!Acc::isAuthed())
        //	JsonResponse::error(4);

        $doctor_id = $this->request('doctor_id');

        $doctor_manager = new DoctorManager();
        $doctor = $doctor_manager->getOneById($doctor_id);

        if (!$doctor || !$doctor_id) {
            ErrorPageViewHelper::page404('404');
            exit();
        }

        $this->view->doctor = $doctor;
        $this->render('blocks/doctor-photos');

    }

    function ajaxGetReviewsList()
    {
        $this->layout = 'ajax';
        $doctor_id = $this->request('doctor_id', 0);
        $page = $this->request('page', 1);

        $review_manager = new DoctorReviewManager();
        $doctor_manager = new DoctorManager();

        $offset = 4 + ($page - 2) * 10;



        $reviews = $review_manager->getConfirmedListByDoctorIdWithPagging($doctor_id, $offset, 10);

        $doctor = $doctor_manager->getOneById($doctor_id);


        $this->view->doctor = $doctor;
        $this->view->reviews = $reviews;

        $count = count($reviews);

        $html = $this->renderInString('doctor/blocks/card_review_list');
        JsonResponse::result(array('html' => $html, 'count' => $count));
    }

}