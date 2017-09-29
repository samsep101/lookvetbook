<?php
	/**
	 * @var CityModel $city
	 * @var int $city_id
	 * @var float $latitude
	 * @var float $longitude
	 */
?>
<script>
    $(document).ready(function(){
        var analysis_page_controller = new AnalysisPageController();
		analysis_page_controller.city_id = <?php echo $city->getId(); ?>;
		analysis_page_controller.city_alias = "<?php echo $city->alias; ?>";

        analysis_page_controller.card_pay = <?php echo $card_pay; ?>;
        analysis_page_controller.urgent_tests = <?php echo $urgent_tests; ?>;
        analysis_page_controller.day_and_night = <?php echo $day_and_night; ?>;
        analysis_page_controller.work_seven_days = <?php echo $work_seven_days; ?>;
        analysis_page_controller.easy_entry = <?php echo $easy_entry; ?>;

        analysis_page_controller.init();
    });
</script>

<div class="flo">
    <div class="map-box map-box-analizes">
        <div id="map" class="map__canvas"></div>

        <div class="analizes-center">
            <div class="simple-text">
                <p class="h-txt">Анализы</p>
                <p class="text">
                    На карте представлены лаборатории Вашего города с указанием времени работы и приема биоматериала для анализа
                </p>
            </div>

            <div class="search-box flo" style="left: 670px;">
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
            <div class="criteria">
                <p class="h-txt">Выбрать критерии:</p>
                <ul class="choose-list">
                    <li class="urgent_tests">
                        <div class="chekBox <? if ($urgent_tests) echo 'act';?>"><span></span>Срочно<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="day_and_night">
                        <div class="chekBox <? if ($day_and_night) echo 'act';?>"><span></span>24 часа<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="work_seven_days">
                        <div class="chekBox <? if ($work_seven_days) echo 'act';?>"><span></span>Без выходных<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="easy_entry">
                        <div class="chekBox <? if ($easy_entry) echo 'act';?>"><span></span>Вход для колясок<input type="hidden" value="0">
                        </div>
                    </li>
                    <li class="card_pay">
                        <div class="chekBox <? if ($card_pay) echo 'act';?>"><span></span>Оплата по карте<input type="hidden" value="1">
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>