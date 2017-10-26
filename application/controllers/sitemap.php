<?php

class sitemapController extends BaseController
{
    public $layout = 'home';
    public function get()
    {
        $landing = $this->request('landing');
        $recording = $this->request('recording');
        die('ddd');
        $this->view->landing_page = $landing;
        $this->view->recording = $recording;
        $sitemap_manager = ModelManagerFactory::getByName('doctor');
    }
    
    public function index() {
        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;
        $this->render('sitemap/index');
    }
    
    public function showDoctors() {
        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;
        $location=$this->request('location');
        $this->view->location=$location;
        $specialty=$this->request('specialty');
        $this->view->specialty=$specialty;
        $doctor_search_params = new DoctorSearchParams();  
        $doctor_search_algorithm = new DoctorSearchAlgorithm();
        $doctor_search_params->city_id=$this->city->getId();
        $p=$this->request('p');
        $this->view->doctor_type = null;
        $this->view->visit_type = null;
        $this->view->location_id = null;
        $this->view->location_name = null;
        $this->view->location_type = null;
        if ($p=='na-dom') {
            $doctor_search_params->visit_type='home';
            $this->view->visit_type='home';
        }
        if ($p=='detskie') {
            $doctor_search_params->doctor_type='children';
            $this->view->doctor_type='children';
        }
        $this->view->p=$p;
            
        //получаем округи и районы
        $district = new DistrictManager();
        $districts=$district->getListByCityId($this->city->getId());
        $districtsList = [];
        $regionsList = [];
        if ($districts) {
            $region = new RegionManager();
            foreach ($districts as $d) {
                if ($d->alias==$location) {
                    $doctor_search_params->district_id=$district->getOneByAlias($location)->getId();
                    $this->view->location_id=$d->id;
                    $this->view->location_name=$d->name;
                    $this->view->location_type='district';
                }
               $regions = $region->getListByDistrictId($d->getId());
               if ($regions) {
                   foreach($regions as $r) {
                       $regionsList[]=array('name'=>$r->name,'alias'=>$r->alias);
                       if ($r->alias==$location) {
                         $doctor_search_params->region_id=$region->getOneByAlias($location)->getId();
                         $this->view->location_id=$r->id;
                         $this->view->location_name='район '.$r->name;
                         $this->view->location_type='region';
                       }
                   }
               }
               $districtsList[]=array('name'=>$d->name,'alias'=>$d->alias,'regions'=>$regionsList);
            }    
            asort($regionsList);
            asort($districtsList);
        }
        
        //метро
        $metro_stations = new MetroStationManager();
        $stations = $metro_stations->getHavingDoctorsListById($this->city->getId());
        $metroStationsList = [];
        if ($stations) {
            foreach($stations as $s) {
                $metroStationsList[]=array('name'=>$s->name,'alias'=>$s->alias);
                if ($s->alias==$location) {
                    $doctor_search_params->metro_station_id=$metro_stations->getOneByAlias($location)->getId();
                    $this->view->location_id=$s->id;
                    $this->view->location_name='метро '.$s->name;
                    $this->view->location_type='metro';
                }
            }
            asort($metroStationsList);
        }

        //улицы
        $streetManager = new StreetManager();
        $streets = $streetManager->getHavingDoctorsListByCityId($this->city->getId());
        $streetsList = [];
        if ($streets) {
            foreach($streets as $s) {
                $streetsList[]=array('name'=>$s->prefix.' '.$s->name,'alias'=>$s->alias);
                if ($s->alias==$location) {
                    $doctor_search_params->street_id=$streetManager->getOneByAlias($location)->getId();
                    $this->view->location_id=$s->id;
                    $this->view->location_name=$s->prefix.' '.$s->name;
                    $this->view->location_type='street';
                }
            }
            //asort($streetsList);
        }
        
        //специальности
        $specialtyManager = new SpecialtyManager();
        if ($this->view->location_type=='district')
            $specialities = $specialtyManager->getHavingDoctorsListByDistrictId($this->view->location_id);
        else if ($this->view->location_type=='region')
            $specialities = $specialtyManager->getHavingDoctorsListByRegionId($this->view->location_id);
        else if ($this->view->location_type=='metro')
            $specialities = $specialtyManager->getHavingDoctorsListByMetroStationId($this->view->location_id);
        else if ($this->view->location_type=='street')
            $specialities = $specialtyManager->getHavingDoctorsListByStreetId($this->view->location_id);
        else
             $specialities = $specialtyManager->getHavingDoctorsListByCityId($this->city->getId());

        $specialitiesList = [];
        $this->view->specialty_id = null;
        $this->view->specialty_name = null;
        if ($specialities) {
            foreach($specialities as $s) {
                $doctor_search_params->specialty_id=$s->id;
                if ($specialty==$s->alias) {
                    $this->view->specialty_id=$s->id;
                    $this->view->specialty_name=$s->name;
                }
                $doctors = $doctor_search_algorithm->search($doctor_search_params);
                $doctorsList=array();
                foreach($doctors as $doc) {
                    if ($doc->last_name!='' && $doc->first_name!='' && $doc->second_name!='') {
                    $doctorsList[]=array('name'=>$doc->last_name.' '.$doc->first_name.' '.$doc->second_name,'alias'=>$doc->alias);
                    }
                }
                asort($doctorsList);
                //if (count($doctorsList)>0)
                $specialitiesList[$s->id]=array('name'=>$s->name,'alias'=>$s->alias,'doctors'=>$doctorsList);
            }
            //asort($streetsList);
        }
        $this->view->streetsList = $streetsList;
        $this->view->regionsList = $regionsList;
        $this->view->districtsList = $districtsList;
        $this->view->metroStationsList = $metroStationsList;
        $this->view->specialitiesList = $specialitiesList;
        $this->render('sitemap/doctors');    
        
    }
    
    public function showClinics() {
        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;
        $location=$this->request('location');
        $this->view->location=$location;
        $specialization=$this->request('specialization');
        $this->view->specialty=$specialization;
        $this->view->location_id = null;
        $this->view->location_name = null;
        $this->view->location_type = null;

        $clinic_search_params = new ClinicSearchParams();  
        $clinic_search_algorithm = new ClinicSearchAlgorithm();
        $clinic_search_params->city_id=$this->city->getId();
            
        //получаем округи и районы
        $district = new DistrictManager();
        $districts=$district->getListByCityId($this->city->getId());
        $districtsList = [];
        $regionsList = [];
        if ($districts) {
            $region = new RegionManager();
            foreach ($districts as $d) {
                if ($d->alias==$location) {
                    $clinic_search_params->district_id=$district->getOneByAlias($location)->getId();
                    $this->view->location_id=$d->id;
                    $this->view->location_name=$d->name;
                    $this->view->location_type='district';
                }
               $regions = $region->getListByDistrictId($d->getId());
               if ($regions) {
                   foreach($regions as $r) {
                       $regionsList[]=array('name'=>$r->name,'alias'=>$r->alias);
                       if ($r->alias==$location) {
                         $clinic_search_params->region_id=$region->getOneByAlias($location)->getId();
                         $this->view->location_id=$r->id;
                         $this->view->location_name='район '.$r->name;
                         $this->view->location_type='region';
                       }
                   }
               }
               $districtsList[]=array('name'=>$d->name,'alias'=>$d->alias,'regions'=>$regionsList);
            }    
            asort($regionsList);
            asort($districtsList);
        }
        
        //метро
        $metro_stations = new MetroStationManager();
        $stations = $metro_stations->getHavingDoctorsListById($this->city->getId());
        $metroStationsList = [];
        if ($stations) {
            foreach($stations as $s) {
                $metroStationsList[]=array('name'=>$s->name,'alias'=>$s->alias);
                if ($s->alias==$location) {
                    $clinic_search_params->metro_station_id=$metro_stations->getOneByAlias($location)->getId();
                    //$clinic_search_params->metro_station_name=$metro_stations->getOneByAlias($location)->name;
                    //$clinic_search_params->metro_branch_name='Арбатско-Покровская';//$metro_stations->getOneByAlias($location)->name;
                    //$clinic_search_params->is_metro=1;
                    $this->view->location_id=$s->id;
                    $this->view->location_name='метро '.$s->name;
                    $this->view->location_type='metro';
                    //print_r($clinic_search_params);
                }
            }
            asort($metroStationsList);
        }

        //улицы
        $streetManager = new StreetManager();
        $streets = $streetManager->getHavingDoctorsListByCityId($this->city->getId());
        $streetsList = [];
        if ($streets) {
            foreach($streets as $s) {
                $streetsList[]=array('name'=>$s->prefix.' '.$s->name,'alias'=>$s->alias);
                if ($s->alias==$location) {
                    $clinic_search_params->street_id=$streetManager->getOneByAlias($location)->getId();
                    $this->view->location_id=$s->id;
                    $this->view->location_name=$s->prefix.' '.$s->name;
                    $this->view->location_type='street';
                }
            }
            //asort($streetsList);
        }
        
        //специальности
        $specializationManager = new SpecializationManager();
        $specializations = $specializationManager->getSpecializationForCityIDInWhichHaveDoctors($this->city->getId());
        $specializationsList = [];
        if ($specializations) {
            foreach ($specializations as $s) {
                $clinic_search_params->specialization_id=$specializationManager->getOneByName ($s->name)->getId();
                $clinicsList=array();
                
                $clinics = $clinic_search_algorithm->search($clinic_search_params);
                if ($specialization==$s->alias) {
                    $this->view->specialization=$specialization;
                    $this->view->specialization_name=$s->name;
                    //print_r($clinic_search_params);
                    $this->view->specialization_id=$s->id;
                    foreach($clinics as $c) {
                        $clinicsList[]=array('name'=>$c->name,'alias'=>$c->alias);
                    }
                }
                
                $specializationsList[$s->id]=array('name'=>$s->name,'alias'=>$s->alias,'clinics'=>$clinicsList);
            }
        }
        
        $this->view->streetsList = $streetsList;
        $this->view->regionsList = $regionsList;
        $this->view->districtsList = $districtsList;
        $this->view->metroStationsList = $metroStationsList;
        $this->view->specializationsList = $specializationsList;
        $this->render('sitemap/clinics');    
        
    }
}