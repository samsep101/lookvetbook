<?php
	/**
	 * @var View $this
	 * @var int $city_id
	 * @var float $latitude
	 * @var float $longitude
	 * @var StreetModel|null $street
	 * @var DistrictModel|null $district
	 * @var RegionModel|null $region
	 * @var CityModel|DistrictModel|RegionModel|StreetModel $address_object
     * @var MetroStationModel $metro_station
	 */

	if (isset($_COOKIE['already_registred_account']))
        $already_registred_account = 1;
    else
        $already_registred_account = 0;
?>


<script type="text/javascript">
    $(document).ready(function () {

        window.controller = new DoctorSearchPageController( <?php echo (isset($landing_page) && !Acc::isAuthed()) ? false : true; ?>, <?php echo $already_registred_account; ?>,"<?php echo $_SERVER['REQUEST_URI']; ?>");
        <?php if ($specialty): ?>
            window.controller.specialty_id = <?php echo $specialty->getId(); ?>;
			window.controller.specialty_alias = '<?php echo $specialty->alias; ?>';
        <?php endif; ?>
        <?php if ($district): ?>
            window.controller.district_id = <?php echo $district->getId(); ?>;
        <?php endif; ?>
        <?php if ($region): ?>
            window.controller.region_id = <?php echo $region->getId(); ?>;
        <?php endif; ?>
        <?php if ($street): ?>
            window.controller.street_id = <?php echo $street->getId(); ?>;
        <?php endif; ?>
        <?php if ($metro_station): ?>
            window.controller.metro_station_id = <?php echo $metro_station->getId(); ?>;
        <?php endif; ?>
        window.controller.city_id = <?php echo $address->city_id; ?>;

        window.controller.init();

        window.doctor_form_controller = controller.form_controller;
    });
</script>



<?php
    $this->doctors_page = 1;
    $this->is_seo_page = $is_seo_page;
?>
<div class="inner-2 flo" style="position: relative;">
    <?php $this->block('doctor/blocks/search-form-refactor'); ?>
    <div class="map-box refactor <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
        <div id="map"></div>
        <span class="resize">Увеличить</span>
    </div>

    <div class="search-box flo doctor-map-search refactor">
        <input class="txt" type="text" id="address-input" placeholder="Искать по адресу или станции метро"/>
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
<div class="search-count-block <?php echo (isset($is_red) && $is_red) ? 'red' : ''; ?>">
    <ol itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb">
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            &nbsp;
            &nbsp;
            <a itemprop="item" href="/">
                <span itemprop="name">Главная</span></a> -&nbsp;
            <meta itemprop="position" content="1" />
        </li>
        <li itemprop="itemListElement" itemscope
            itemtype="http://schema.org/ListItem">
            <span itemprop="item" href="/doctor">
                <span itemprop="name">Врачи</span></span>
            <meta itemprop="position" content="2" />
        </li>
    </ol>
    <p class="count">
        Мы нашли для Вас <span class="count-digit"></span> <span class="count-doctor"></span> <span class="count-specialty"></span>
    </p>
    <div class="divider-shadow" id="divider-shadow"></div>
</div>
<div align="center" class="adv_text_bf_search_result">
    Мы сравним для вас цены и найдем лучшее предложение.<br> Звоните <a href="tel:+7(495)215-09-07">+7(495)215-09-07</a>
</div>

<div class="inner-2" style="padding-top: 0;">
    <div id="our-doctors">
        <a class="view-more"><i class="icon-loader"></i></a>
    </div>
</div>

<div class="doctor-special-links">
    <?php $this->block('doctor/blocks/seo_block'); ?>
</div>