<?php

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

    protected $container = [];

    /** @return ServicesModel */
    public function services_model() {

        static $model = null;

        if(is_null($model)){
            require_once ABS_ROOT.'/application/models/services.categories.simplemodel.php';
            $model = new ServicesCategoriesSimpleModel();
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

        if(!empty($this->current['id']) AND array_key_exists($this->current['id'], $this->container['tree'])){

            $this->view->current_tree = [ $this->current['id'] => $this->container['tree'][$this->current['id']] ];
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

}

/* END CLASS: UslugiController extends BaseController */