<?php
	/**
	 * @var View $this
	 * @var bool $show_popup
	 * @var bool $show_popup_forgot_pass
	 * @var DoctorModel[] $doctors
	 * @var CityModel $city
	 * @var DistrictModel[] $districts
	 * @var SpecialtyModel $specialty
	 */
?>
<?php if (isset($_GET['email_is_confirmed'])) $show_popup = 1; else $show_popup = 0; ?>
<?php if (isset($_GET['is_change_password'])) $show_popup_forgot_pass = 1; else $show_popup_forgot_pass = 0; ?>
<script>
    $(document).ready(function(){
        var controller = new IndexPageController(<?php echo $show_popup;?>, <?php echo $show_popup_forgot_pass; ?>);
        controller.init();
        var header_controller = new HeaderController();
        header_controller.init();
        var disease_controller = new DiseaseSearchPageController('disease-search');
        disease_controller.init();
    });
</script>
<div class="inner-home-main">
    <div class="center">
        <div class="left_search">
            <div class="search-form">
                <ul class="tabs flo">
                    <li class="active">
                        <span id="find_doctor_tab"><i></i>Найти врача</span>
                    </li>
                    <li>
                        <span id="find_clinic_tab"><i></i>Найти клинику</span>
                    </li>
                </ul>

                <div class="section visible">
                    <?php echo $this->show_title = false; ?>
                    <?php $this->home_page = 1; ?>
                    <?php $this->block('doctor/blocks/search-form'); ?>
                </div>
                <div class="section find-clinic search-form-main-page-container">
                    <?php $this->block('clinic/blocks/search-form'); ?>
                </div>
            </div>

            <div class="abs abs2">Выберите врача<br/>
                <small>или клинику</small></div>
            <div class="abs abs3">Выберите<span class="flo"></span> специализацию<br/>
                <small>и цель визита</small></div>
            <div class="abs abs4">Найди лучшего<br/>
                <small>специалиста</small></div>
    </div>
        <div class="right_owl">
            <p class="h-text">Online сервис записи к врачу и в клинику</p>
            <span class="abs abs1"><small>
                Или используйте мобильное<br/>
                приложение <?php echo SITE_NAME; ?><br/>
                это удобно! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </small>
            </span>
            <a target="_blank" href="https://itunes.apple.com/ru/app/lookmedbook/id726213572">
                <span  class="abs abs5"></span>
                <span  class="abs abs6"></span>
            </a>
        </div>
        <p class="clear_line"></p>
    </div>
</div>
<div class="footer-top-line"></div>

<div id="footer">
    <?php if(!empty($doctors)) { ?>
        <?php $this->doctors = $doctors; ?>
        <?php $this->block('/doctor/blocks/slider_cards_doctors'); ?>
    <?php } ?>


</div><!-- #footer -->
<?php if($city->getId() == 2) { ?>
<div class="about_dis">
    <div class="center">
        <p class="h-txt"><a href="/disease">Читайте о заболеваниях на понятном языке</a></p>
        <p class="l_txt">
            Читайте <a href="/disease">о заболеваниях</a> на доступном языке, понятном для обычных людей.
                </p>
        <p class="l_txt none_pb">
            Наша команда врачей подготовила информацию о каждом <a href="/disease">заболевании</a>, следуя четкой структуре.
                </p>
        <p class="l_txt none_pt">
            Благодаря этому можно найти то, что интересно именно Вам.
        </p>

        <div class="search-block search-block-mainpage flo">
            <form action="/disease/searchResults" method="GET">
                <input type="text" class="txt illness-search-input" autocomplete="off" name="disease_query" placeholder="Введите название заболевания" />
                <input type="submit" class="btn-6 illness-search-submit" data-action-for-counters="disease" value="Искать" />
                <ul class="drop-menu">
    </ul>
            </form>
        </div>
    </div>
</div>
<?php } ?>
<?php if($city->getId() == 2) { ?>
    <div class="learn-more">
<?php } else { ?>
        <div class="learn-more not-disease-about">
            <div class="wave-separator"></div>
<?php } ?>
    <div class="learn-txt">
        <p class="h-txt">
            Вы представитель<span class="flo"></span> клиники или доктор?
        </p>
        <p class="l_txt">
            <?php echo SITE_NAME; ?> — это лучший способ для того,<br/>
            чтобы найти лучших пациентов и рассказать
            <span class="flo"></span>о себе.
            <br/><br/>
            С помощью нашего сервиса к вам придет гораздо<br/>
            больше пациентов!
        </p>
        <a href="/example">Узнать больше</a>
    </div>
    <div class="line-rgba"></div>
</div>
<div class="thisis">
    <div class="line-shadow"></div>
</div>

<?php echo $this->block('index/specialties_groups');?>







