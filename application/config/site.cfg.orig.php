<?php
define('debug', 0);

define('CONTACT_EMAIL','');
define('OUTPUT_LANGUAGE','RUSSIAN');
define('USE_SET_NAMES',1);
define('HTTP_ROOT','');
define('MEDIA_UPLOAD_PATH', '/media/upload/');
define('UPLOAD_IMAGES_WIDTH', 1000);
define('UPLOAD_IMAGES_HEIGHT', 800);
define('ADMIN_FOLDER', '/admin');
define('MANAGE_FOLDER', '/registry/manage');
define('REGISTRY_FOLDER', '/registry');

if (isset($_SERVER['HTTP_X_SCHEME']) && $_SERVER['HTTP_X_SCHEME'] == 'https') {
    define('SITE_SCHEME', 'https');
} elseif (php_sapi_name() === 'cli') {
    define('SITE_SCHEME', 'https');
} else {
    define('SITE_SCHEME', 'http');
}
define('SITE_URL', SITE_SCHEME . "://lookmedbook.ru");
define('SITE_DOMAIN', "lookmedbook.ru");
define('SITE_NAME', "LookMedBook");


define('SHOP_ENABLE', 1);
define('RELEASE_NUMBER', 43);
define('SITE_PHONE_CODE', '495');
define('SITE_PHONE', '215-09-07');
define('CSS_DIR', 'vet');
define('PAGE_TITLE', 'Портал медицинских услуг в ');
define('JUR_ADDRESS', 'Гамсоновский переулок, 2');
define('JUR_ADDRESS_FULL', '115191, г.Москва, Гамсоновский переулок, д.2');
define('CONTENT_DISEASE_URL', 'https://admin:21506@content.'.SITE_DOMAIN.'/media/xml/Test.xml');


define('DOCTOR', 'врач');
define('DOCTORA', 'врача');

define('MEDICYNY', 'медицины');
