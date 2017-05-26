<?php

    $map = array(
        array(
            'url'        => '/',
            'action'     => 'index',
            'controller' => 'index',
        ),
        array(
            'url'        => '/passwordRecovery',
            'action'     => 'passwordRecovery',
            'controller' => 'account',
        ),
        array(
            'url'        => '/passwordNew',
            'action'     => 'passwordNew',
            'controller' => 'account',
        ),
        array(
            'url'        => '/admin/index/log/exportCSVWithAccountActivity',
            'action'     => 'exportCSVWithAccountActivity',
            'controller' => 'account',
        ),
        array(
            'url'        => '/admin/index/log/exportCSVSearchLog',
            'action'     => 'exportCSVSearchLog',
            'controller' => 'account',
        ),
        array(
            'url'        => '/account/my_disease/archive',
            'action'     => 'my_disease_archive',
            'controller' => 'account',
        ),
        array(
            'url'        => '/account/message/get',
            'action'     => 'get',
            'controller' => 'message',
        ),
        array(
            'url'        => '/about',
            'action'     => 'about',
            'controller' => 'index',
        ),
        array(
            'url'        => '/view_table',
            'action'     => 'index_table',
            'controller' => 'index',
        ),
        array(
            'url'        => '/map',
            'action'     => 'map',
            'controller' => 'index',
        ),
        array(
            'url'        => '/page/:code',
            'action'     => 'index',
            'controller' => 'page',
        ),
        array(
            'url'        => '/news/view/:id',
            'action'     => 'view',
            'controller' => 'news',
        ),
        array(
            'url'        => '/articles/view/:id',
            'action'     => 'view',
            'controller' => 'articles',
        ),
        array(
            'url'        => '/license',
            'action'     => 'license',
            'controller' => 'index',
        ),

        // роутинги для контроллера DoctorController
        array(
            'url'        => '/doctor/search',
            'action'     => 'search',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/get',
            'action'     => 'get',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSearch',
            'action'     => 'ajaxSearch',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxAddToMyDoctorList',
            'action'     => 'ajaxAddToMyDoctorList',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetReviewsList',
            'action'     => 'ajaxGetReviewsList',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetScheduleSingleDay',
            'action'     => 'ajaxGetScheduleSingleDay',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetScheduleTimes',
            'action'     => 'ajaxGetScheduleTimes',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetScheduleTimesByDay',
            'action'     => 'ajaxGetScheduleTimesByDay',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxGetVisitPrice',
            'action'     => 'ajaxGetVisitPrice',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSaveAccountFullName',
            'action'     => 'ajaxSaveAccountFullName',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSaveVisitData',
            'action'     => 'ajaxSaveVisitData',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxSetPhoneNumber',
            'action'     => 'ajaxSetPhoneNumber',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/ajaxUpdateVisitTime',
            'action'     => 'ajaxUpdateVisitTime',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/getDoctorPhotos',
            'action'     => 'getDoctorPhotos',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:specialty/:district/:region/:metro',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:specialty/:district/:region_street',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:specialty/:district',
            'action'     => 'index',
            'controller' => 'doctor'
        ),
        array(
            'url'        => '/doctor/:id',
            'action'     => 'get',
            'controller' => 'doctor'
        ),

        // роутинги для контроллера Clinic
        array(
            'url'        => '/clinic/search',
            'action'     => 'search',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic',
            'action'     => 'index',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxAddToMyClinicList',
            'action'     => 'ajaxAddToMyClinicList',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxGetDoctorsList',
            'action'     => 'ajaxGetDoctorsList',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxGetReviewsList',
            'action'     => 'ajaxGetReviewsList',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/ajaxSearch',
            'action'     => 'ajaxSearch',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/get',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:id/:district/:region/:metro',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:id/:district/:region_street',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:id/:district',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:id',
            'action'     => 'get',
            'controller' => 'clinic'
        ),
        array(
            'url'        => '/clinic/:landing_page_alias',
            'action'     => 'landingPage',
            'controller' => 'clinic'
        ),

        // роутинги для контроллера Action
        array(
            'url'        => '/action/:id',
            'action'     => 'get',
            'controller' => 'action'
        ),

        // роутинги для контроллера Disease
        array(
            'url'        => '/disease',
            'action'     => 'search',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxAddToArchive',
            'action'     => 'ajaxAddToArchive',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/test',
            'action'     => 'test',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxAddToMyDiseaseList',
            'action'     => 'ajaxAddToMyDiseaseList',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxAddUnderstandOpinion',
            'action'     => 'ajaxAddUnderstandOpinion',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxGetUnderstandBlock',
            'action'     => 'ajaxGetUnderstandBlock',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease',
            'action'     => 'clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/moreSearchResults',
            'action'     => 'moreSearchResults',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/parseDiseasesAndDiseaseBlocks',
            'action'     => 'parseDiseasesAndDiseaseBlocks',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/search',
            'action'     => 'search',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/searchResults',
            'action'     => 'searchResults',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/get',
            'action'     => 'get',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/ajaxGetDiseaseCardContent',
            'action'     => 'ajaxGetDiseaseCardContent',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/:id/:card',
            'action'     => 'get',
            'controller' => 'disease'
        ),
        array(
            'url'        => '/disease/:id',
            'action'     => 'get',
            'controller' => 'disease'
        ),

        // роутинги для контроллера Catalog
        array(
            'url'        => '/shop/catalog/',
            'action'     => 'index',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/ajaxSearch',
            'action'     => 'ajaxSearch',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/search',
            'action'     => 'search',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/ajaxLiveSearch',
            'action'     => 'ajaxLiveSearch',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/ajaxSearchLog',
            'action'     => 'ajaxSearchLog',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
//        array(
//            'url'        => '/shop/catalog/payments',
//            'action'     => 'payments',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
//        array(
//            'url'        => '/shop/catalog/delivery',
//            'action'     => 'delivery',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
//        array(
//            'url'        => '/shop/catalog/choise',
//            'action'     => 'choise',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
//        array(
//            'url'        => '/shop/catalog/getLicenseImages',
//            'action'     => 'getLicenseImages',
//            'controller' => 'catalog',
//            'folder'     => 'shop'
//        ),
        array(
            'url'        => '/shop/catalog/:category_alias',
            'action'     => 'index',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),
        array(
            'url'        => '/shop/catalog/:parent_category_alias/:category_alias',
            'action'     => 'index',
            'controller' => 'catalog',
            'folder'     => 'shop'
        ),

        // роутинги для контроллера Product
        array(
            'url'        => '/shop/product/:id',
            'action'     => 'get',
            'controller' => 'product',
            'folder'     => 'shop'
        ),

        // Роутинги для Widget API
        array(
            'url'        => '/widget_api/specialty/getList',
            'action'     => 'getList',
            'controller' => 'specialty',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/widget_api/city/getList',
            'action'     => 'getList',
            'controller' => 'city',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/widget_api/accountPhone/sendCode',
            'action'     => 'sendCode',
            'controller' => 'AccountPhone',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/widget_api/accountPhone/confirm',
            'action'     => 'sendCode',
            'controller' => 'AccountPhone',
            'folder'     => 'api'
        ),
        array(
            'url'        => '/landing/:id',
            'action'     => 'index',
            'controller' => 'landing'
        ),
        array(
            'url'        => '/smap',
            'action'     => 'index',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/:p',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/location/:location',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/:p/location/:location',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/location/:location/specialty/:specialty',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/:p/location/:location/specialty/:specialty',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/specialty/:specialty',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/:p/specialty/:specialty',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/specialty/:specialty/location/:location',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/doctors/:p/specialty/:specialty/location/:location',
            'action'     => 'showDoctors',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/clinics/specialization/:specialization/location/:location',
            'action'     => 'showClinics',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/clinics/location/:location/specialization/:specialization',
            'action'     => 'showClinics',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/clinics',
            'action'     => 'showClinics',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/clinics/location/:location',
            'action'     => 'showClinics',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/smap/clinics/specialization/:specialization',
            'action'     => 'showClinics',
            'controller' => 'sitemap'
        ),
        array(
            'url'        => '/venerolog-urolog-ginekolog',
            'action'     => 'index',
            'controller' => 'oneClickSubscribe'
        ),
);

    Register::add('map', $map);