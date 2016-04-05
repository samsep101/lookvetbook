<?php
	/**
	 * @var View $this
	 * @var CityModel $city
	 * @var int $city_id
	 * @var float $latitude
	 * @var float $longitude
	 * @var View $this
     * @var AccountModel $current_account
     * @var ClinicModel $clinic
	 */
 ?>
<?php
    if (isset($_COOKIE['already_registred_account']))
        $already_registred_account = 1;
    else
        $already_registred_account = 0;
?>
<script type="text/javascript">
    $(document).ready(function () {
        window.controller = new ClinicSearchPageController( <?php echo (isset($landing_page)) ? 'true' : 'false'; ?>, <?php echo $already_registred_account; ?>,"<?php echo $_SERVER['REQUEST_URI']; ?>");

        <?php if(!  empty($specialization)) { ?>
            window.controller.specialty_id = <?php echo $specialization->getId(); ?>;
            window.controller.specialty_alias = '<?php echo $specialization->alias; ?>';
        <?php } ?>
        <?php if (!empty($district) and is_object($district)): ?>
            window.controller.district_id = <?php echo $district->getId(); ?>;
        <?php endif; ?>
        <?php if (!empty($region) and is_object($region)): ?>
            window.controller.region_id = <?php echo $region->getId(); ?>;
        <?php endif; ?>
        <?php if (!empty($street) and is_object($street)): ?>
            window.controller.street_id = <?php echo $street->getId(); ?>;
        <?php endif; ?>
        <?php if (!empty($metro_station) and is_object($metro_station)): ?>
            window.controller.metro_station_id = <?php echo $metro_station->getId(); ?>;
        <?php endif; ?>


        window.controller.city_id = <?php echo $city->getId(); ?>;
        window.controller.city_alias = "<?php echo $city->alias; ?>";

        controller.init();

        window.clinic_form_controller = controller.form_controller;
    });
</script>
<?php $this->block('blocks/top_number'); ?>

<div class="inner-2 flo" style="position: relative;">


        <?php $this->block('clinic/blocks/search-form'); ?>


    <div class="map-box refactor clinic-map <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
        <div id="map" class="map__canvas" data-map="small"></div>

        <span class="resize">Увеличить</span>
    </div>

    <div class="search-box flo clinic-map-search">
        <input class="txt" type="text" id="address-input" placeholder="Искать по адресу или станции метро" />
        <input class="btn-search" id="address-submit" type="submit" value="Найти"/>
        <ul class="drop-menu" id="address-drop">

        </ul>
        <div class="map-city">
            <nobr>
                <a><?php echo (isset($city)) ? $city->name : 'Москва' ?></a>
            </nobr>
        </div>
    </div>
</div>
<!--
<div class="full-width filter">
    <div class="inner flo"> <span class="label">Фильтр:</span>
        <ul>
            <li class="sortby" id="recomend"><a><span>Рекомендуемые</span></a></li>
            <li class="sortby" id="rate"><a><span>Рейтинг пользователей</span></a></li>
        </ul>
    </div>
</div>
-->

<div class="search-count-block clinic-search <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
    <p class="count">
        Мы нашли для Вас <span class="count-digit"></span> <span class="count-doctor"></span> <span class="specialty-label">по специализации <span class="count-specialty"></span></span>
    </p>
    <div class="divider-shadow" id="divider-shadow"></div>
</div>

<div class="inner-2 clinic-search-result <?php if($current_account  && $current_account->is_call_centre_operator) echo 'account_active'; ?>">
    <div id="our-doctors">

        <a class="view-more"><i class="icon-loader"></i></a>
    </div>
</div>
<?php $this->block('blocks/adv/content_page'); ?>