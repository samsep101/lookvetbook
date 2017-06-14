<?php
	/**
	 * @var View $this
	 * @var AccountModel $current_account
	 * @var bool $show_canonical_link
	 * @var string $canonical_link
	 * @var int $doctor_search_page
	 * @var CityModel $city
     * @var string $csrf
	 */
	DEFINE('RELEASE__NUMBER', '0.1');
?>
    <?php
    if (!isset($canonical_link))
        $canonical_link = SITE_URL.$_SERVER['REQUEST_URI'];


    if (strpos($canonical_link, '?')){
        $canonical_link = preg_replace('/^([^?]+)(\?.*?)?(#.*)?$/', '$1$3', $canonical_link);
    }

    if (isset($canonical_link) && $canonical_link): ?>
        <?php if ($canonical_link != 'none' && !isset($site_url_not_using)) { ?>
            <link rel="canonical" href="<?php echo SITE_URL.$canonical_link; ?>" />
        <?php } elseif(isset($site_url_not_using) && $site_url_not_using) { ?>
            <link rel="canonical" href="<?php echo $canonical_link; ?>" />
        <?php } ?>
    <?php elseif(($city) && $city->name):  ?>
        <link rel="canonical" href="<?php echo SITE_URL.$_SERVER['REQUEST_URI']; ?>" />
    <?php endif; ?>
<link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/styles.css?<?php echo filemtime(__DIR__.'/../../../media/css/'.CSS_DIR.'/styles.css')?>" type="text/css"/>
<link rel="stylesheet" href="/media/css/fonts.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
<link rel="stylesheet" href="/media/css/chosen.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
<link rel="stylesheet" href="/media/css/jquery.fancybox.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
<link rel="stylesheet" href="/media/css/jquery.jscrollpane.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
<link rel="stylesheet" href="/media/css/jquery-ui.min.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie.css?<?php echo RELEASE__NUMBER?>"/>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js?<?php echo RELEASE__NUMBER?>"></script>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie9.css?<?php echo RELEASE__NUMBER?>"/>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie8.css?<?php echo RELEASE__NUMBER?>"/>
<![endif]-->
<!--[if IE 11]>
<link rel="stylesheet" type="text/css" media="screen,projection" href="/media/css/for_ie11.css?<?php echo RELEASE__NUMBER?>"/>
<![endif]-->

<script type="text/javascript" src="/media/js/jquery-1.8.3.min.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery-ui-1.10.2.custom.min.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.maskedinput.min.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.carouFredSel-6.2.0-packed.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/chosen.jquery.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.maskedinput-1.3.min.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/bootstrap-affix.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/waypoints.min.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.fancybox.pack.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.jscrollpane.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.jcarousel.min.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jcarousel.connected-carousels.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/js/validation"></script>
<script type="text/javascript" src="/media/js/jquery.raty.min.js?<?php echo RELEASE__NUMBER?>"></script>

<script type="text/javascript" src="/media/js/cookies.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/linkHidingIndexing.js?<?php echo RELEASE__NUMBER?>"></script>

<!--[if lte IE 9]>
<script src="/media/js/jquery.placeholder.min.js?<?php echo RELEASE__NUMBER?>"></script>
<![endif]-->

<?php if (debug): ?>
  <script type="text/javascript" src="/media/js/actions.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/popup.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/modal_window.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/popup_message.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/init.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/history.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/citymap.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/simple-timer.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/jquery.form.js?<?php echo RELEASE__NUMBER?>"></script>
  <script type="text/javascript" src="/media/js/jquery.form.validation.js?<?php echo RELEASE__NUMBER?>"></script>
<?php else: ?>
  <script type="text/javascript" src="/media/js/js_core.js?<?php echo RELEASE__NUMBER?>"></script>
<?php endif; ?>
<!--[if lt IE 10]>
<script type="text/javascript" src="/media/js/flashcanvas.js?<?php echo RELEASE__NUMBER?>"></script>
<![endif]-->

<?php $this->block('blocks/js-library'); ?>

<!-- dev -->
<link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/my.css?<?php echo RELEASE__NUMBER?>"/>

<script type="text/javascript" src="/media/js/inputmask/jquery.inputmask.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/inputmask/jquery.inputmask.numeric.extensions.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/inputmask/jquery.inputmask.date.extensions.js?<?php echo RELEASE__NUMBER?>"></script>

<?php if (isset($load_map) && $load_map): ?>
    <script src="http://api-maps.yandex.ru/2.0/?load=package.full,package.clusters,package.overlays&lang=ru-RU"
            type="text/javascript"></script>
    <script src="/media/js/geolacation-button.js?<?php echo RELEASE__NUMBER?>"></script>
<?php endif; ?>

<script type="text/javascript" src="/media/js/jquery-rating/js/jquery.rating-2.0.js?<?php echo RELEASE__NUMBER?>"></script>
<link rel="stylesheet" type="text/css" href="/media/js/jquery-rating/styles/jquery.rating.css?<?php echo RELEASE__NUMBER?>"/>

<script src="/media/js/jquery.event.move.js?<?php echo RELEASE__NUMBER?>"></script>
<style>
    .dd-button{
        width: 100% !important;
        font-size: 15px !important;
    }

    #our-doctors .clinic-card .btns div {
        display: inline-block;
        float: left;
        width: 50%;
    }
</style>
<script>
	<?php if($city): ?>
    $(document).ready(function () {
        window.city_controller = new CityController(<?php echo $city->getId();?>,<?php echo (float)$city->lat;?>,<?php echo (float)$city->lng;?>);
    });
	<?php endif; ?>
</script>

<script type="text/javascript">
    <?php if (Acc::isAuthed()): ?>
    SessionInfo.is_authed = true;
    SessionInfo.email = '<?php echo $current_account->email; ?>';
    <?php endif; ?>
    SessionInfo.domain = "<?php echo LinkHelper::getDomain(); ?>";
    SessionInfo.csrf = <?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>;
</script>

<script type="text/javascript" src="/media/js/magazine_total_price.js?<?php echo RELEASE__NUMBER?>"></script>
<script type="text/javascript" src="/media/js/image_preview.js?<?php echo RELEASE__NUMBER?>"></script>

<link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/styles-widget.css?<?php echo RELEASE__NUMBER?>" type="text/css"/>
<!--2496 -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MTC9Q6C');</script>
<!-- End Google Tag Manager -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MTC9Q6C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- /2496 -->