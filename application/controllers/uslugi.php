<?php

require_once ABS_ROOT.'/core/funcs/string.helpers.php';

class Uslugi_SeoController extends BaseController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    protected $slug = false;
    protected $subslug = false;
    protected $metro = false;
    protected $district = false;
    protected $area = false;
    protected $street = false;

    protected $current = [
        'name' => '',
        'genitive_name' => ''
    ];

    protected $article = [
        'name' => '',
        'genitive_name' => ''
    ];

    protected $seo_method = 'seo_index';

    /** @var View */
    public $view;
    
    protected function replace_seo($str) {
        
        $replace = [
            '%city%' => $this->city->prepositional_name,
            '%usluga-spec%' => $this->current['genitive_name'],
            '%article%' => $this->article['genitive_name'],
        ];

        return str_replace(array_keys($replace), array_values($replace), $str);
        
    }

    protected function seo_index() {

        # Медицинские услуги в %city%
        $this->view->h1 = $this->replace_seo('Медицинские услуги в %city%');
        # Медицинские услуги в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook
        $this->view->page_title = $this->replace_seo('Медицинские услуги в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook.');
        # Интересуют медицинские услуги в Москве? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости. Заходите и записывайтесь!
        $this->view->page_description = $this->replace_seo('Интересуют медицинские услуги в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости. Заходите и записывайтесь!');

        $this->view->breadcrumbs = [
            ['Главная', '/', 'home'],
            ['Все услуги', '/uslugi', '']
        ];
    }
    
    protected function seo_slug() {

        $this->view->h1 = $this->replace_seo('Медицинские услуги в области %usluga-spec%');
        $this->view->h2 = $this->replace_seo('Услуги в области %usluga-spec%');
        $this->view->page_title = $this->replace_seo('Медицинские услуги в области %usluga-spec% в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook.');
        $this->view->page_description = $this->replace_seo('Интересуют медицинские услуги в области %usluga-spec% в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости.');

        $this->view->breadcrumbs = [
            ['Главная', '/', 'home'],
            ['Все услуги', '/uslugi', ''],
            [$this->current['name'], false, '']
        ];
    }

    protected function seo_article() {

        $this->view->h1 = $this->replace_seo('Медицинские услуги в области %article%');
        $this->view->h2 = $this->replace_seo('Услуги в области %article%');
        $this->view->page_title = $this->replace_seo('Медицинские услуги в области %article% в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook.');
        $this->view->page_description = $this->replace_seo('Интересуют медицинские услуги в области %article% в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости.');

        $this->view->breadcrumbs = [
            ['Главная', '/', 'home'],
            ['Все услуги', '/uslugi', ''],
            [$this->current['name'], '/uslugi/'.$this->current['slug'], ''],
            [$this->article['name'], false, ''],
        ];
    }

    public function afterAction() {

        if(method_exists($this, $this->seo_method)){

            return $this->{$this->seo_method}();
        }

        return $this->seo_index();
    }
}

/* END CLASS: SeoUslugi extends BaseController */

class UslugiController extends Uslugi_SeoController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    public $layout = 'responsive';
    public $template = 'index';

    protected $id = false;
    protected $parent_id = 0;
    /** @var ClinicManager */
    protected $clinic_manager = false;

    protected $container = [];

    public function __construct() {
        
        parent::__construct();

        if(false === Application::config('section.services.available')){
            ErrorPageViewHelper::page404('404');
            exit();
        }

        $this->clinic_manager = ModelManagerFactory::getByName('clinic');
        $this->clinic_manager->setCityID($this->city->id);
    }

    /** @return ServicesCategoriesSimpleModel */
    public function services_model() {

        static $model = null;

        if(is_null($model)){
            require_once ABS_ROOT.'/application/models/services.categories.simplemodel.php';
            $model = new ServicesCategoriesSimpleModel();
            $model->setCityID($this->city->id);
        }

        return $model;
    }

    /** @return RelationsSimpleModel */
    public function relations_model() {

        static $model = null;

        if(is_null($model)){
            require_once ABS_ROOT.'/application/models/relations.simplemodel.php';
            $model = new RelationsSimpleModel();
            $model->setCityID($this->city->id);
        }

        return $model;
    }

    public function setSegments() {

        $this->slug = $this->request('slug', false);
        $this->subslug = $this->request('subslug', false);
        $this->metro = $this->request('metro', false);
        $this->district = $this->request('district', false);
        $this->area = $this->request('area', false);
        $this->street = $this->request('street', false);
    }

    # /uslugi
    public function index() {

        $this->setSegments();

        $this->container['tree'] = $this->services_model()->getTree();
        $this->view->tree = $this->container['tree'];
    }
    # /uslugi/akusherstvo
    public function slug() {

        $this->index();
        $this->seo_method = 'seo_slug';
        $this->view->page = 'slug';

        $this->current = $this->services_model()->getBySlug($this->slug);

        if(!empty($this->current['id'])){

            $this->id = (int)$this->current['id'];
            $this->parent_id = (int)$this->current['parent_id'];
            
            if(array_key_exists($this->id, $this->container['tree'])){
                
                $this->view->current_tree = [ $this->id => $this->container['tree'][$this->id] ];
            }

            $clinics_count = $this->services_model()->getClinicsCount($this->id);
            $clinics = [];
            if($clinics_count > 0){
                $clinics = $this->clinic_manager->getClinicsByServicesID($this->id);
            } else {
                if($this->parent_id > 0){
                    $clinics = $this->clinic_manager->getClinicsByServicesID($this->parent_id);
                }
            }

            foreach($clinics as $cKey => $clinic){
                $clinics[$cKey] = $this->processedClinicItem($clinic);
            }

            $this->view->clinics = $clinics;
        }

        $this->container['roots'] = [];
        
        foreach($this->container['tree'] as $id => $one){

            if($one['count'] > 0){
                $this->container['roots'][$id] = [
                    'slug' => $one['slug'],
                    'name' => $one['name'],
                    'price' => $one['price'],
                    'count' => !empty($one['count']) ? $one['count'] : 0
                ];
            }
        }

        $this->view->roots = $this->container['roots'];

    }
    
    # /uslugi/andrologija/mar-test
    public function article() {

        $this->slug();
        $this->seo_method = 'seo_article';
        $this->view->page = 'article';

        $article_slug = $this->request('article', false);

        if(!empty($article_slug)){

            $this->view->btnback = $this->replace_seo('Услуги в области %usluga-spec%');
            $this->view->btnslug = '/uslugi/'.$this->current['slug'];
            $this->article = $this->services_model()->getBySlug($article_slug);
            
        }

    }
    
    # /uslugi/district-vao
    public function district() {}
    # /uslugi/area-sokolinaya-gora
    public function area() {}
    # /uslugi/metro-baumanskaya
    public function metro() {}
    # /uslugi/street-scherbakovskaya
    public function street() {}
    # /uslugi/akusherstvo/district-vao
    public function slug_district() {}
    # /uslugi/akusherstvo/area-sokolinaya-gora
    public function slug_area() {}
    # /uslugi/akusherstvo/metro-baumanskaya
    public function slug_metro() {}
    # /uslugi/akusherstvo/street-scherbakovskaya
    public function slug_street() {}

    public function render() {

        $this->beforeRender();
        $this->afterAction();

        $this->view->clearscripts = true;
        $this->view->page_type = $this->action;
        $this->view->controller = $this->controller;

        $this->view->setLayout($this->layout);
        $templatePath = $this->getTemplatePath($this->template);

        return $this->view->render($templatePath);
    }

    private function processedClinicItem(ClinicModel $clinic) {

        $additional_params = array();

        foreach ($clinic->types AS $type) {
            $id = $type->getId();

            switch ($id) {
                case 5: {
                        $additional_params['multidisciplinary'] = 1;
                        break;
                    }
                case 11: {
                        $additional_params['accepts-children'] = 1;
                        break;
                    }
                case 28: {
                        $additional_params['twenty-four-hours'] = 1;
                        break;
                    }
            }
        }

        foreach ($clinic->features AS $feature) {
            if ($feature->getId() == 14) {
                $additional_params['have-ramp'] = 1;
                break;
            }
        }

        foreach ($clinic->services AS $service) {
            if ($service->getId() == 1) {
                $additional_params['medical-certificates'] = 1;
                break;
            }
        }

        $count_doctors = 0;
        foreach ($clinic->doctors AS $doctor) {
            $count_doctors++;
            if ($doctor->is_leave_the_house) {
                $additional_params['leave-the-house'] = 1;
                break;
            }
        }

        $clinic->total_doctors = $count_doctors;

        $clinic->total_specializations = count($clinic->specializations);

        if ($clinic->only_adult) {
            $additional_params['accepts-children'] = 0;
        }

        if ($clinic->is_card_pay) {
            $additional_params['payment-cards'] = 1;
        }

        $clinic->additional_params = $additional_params;

        return $clinic;
    }

}

/* END CLASS: UslugiController extends BaseController */