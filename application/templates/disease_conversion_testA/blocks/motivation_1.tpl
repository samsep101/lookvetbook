<div class="disease-doc-block">
    <div class="col-10 disease-doc-text avatar_buttons">
        <p>
            Пневмонией может заболеть любой. Даже физически крепкий человек под влиянием стресса и перенесенной на ногах простуды может неожиданно стать жертвой данного недуга.
            Если вы обнаружили у себя симптомы данного заболевания, не затягивайте с визитом к врачу, <a href="javascript:void(0);" onclick="$('.disease-doc-hide').slideToggle('slow');">запишитесь сейчас</a>.
            Несвоевременное или неправильное лечение может привести к серьезным осложнениям, берегите свое здоровье и помните, вылечить пневомнию на начальной стадии в разы проще.<br>
            <a class="btn-appoint" href="#record-to-the-doctor-popup-1635" onclick="$('.disease-doc-hide').slideToggle('slow');" style="width: 250px">Записаться на прием</a>
        </p>
    </div>
    <div class="col-2 disease-doc-image">
        <div class="avatar">
            <img src="http://lookmedbook.ru//media/upload/clinic/license/74x111-crop-1411717977-D3RfYaeS5G.jpg">
        </div>
    </div>
    <br clear="all">
    <div align="right">
        Солощенко  Владимир  Владимирович (Врач терапевт-пульмонолог)
    </div>
</div>
<div class="disease-doc-hide" style="display:none;padding-left: 50px">
    <script type="text/javascript">
        $( document ).ready(function() {
            $("#datepicker").datepicker();
            $("#inputPhone").mask("(999999) 999-9999");
        });
    </script>
    <h1>Запись на прием</h1>
    <div class="step-block-1 flo" style="display: none">
        <!-- place for info where user want to visit -->
    </div>
    <div class="row flo m-b-10">
        <div class="text-shadow-input">
            Когда нужно к врачу:
        </div>
        <div class="shadow-input">
            <input id="datepicker" type="text" name="visit_start" placeholder="01.01.2016">
        </div>
    </div>
    <div class="row flo m-b-10">
        <div style="width: 155px;float:left;">&nbsp;</div>
        <div class="shadow-checkbox">
            <div class="chekBox act"><span></span> <em>после работы</em>
                <input type="hidden" name="after_work" value="1">
            </div>
            <!-- <input type="checkbox" name="after_work" value="1"> - после работы -->
        </div>
    </div>
    <div class="row flo m-b-10">
        <div class="text-shadow-input">
            Ваше имя:
        </div>
        <div class="shadow-input">
            <input type="text" name="full_name" placeholder="Иван">
        </div>
    </div>
    <div class="row flo m-b-10">
        <div class="text-shadow-input">
            Ваш телефон:
        </div>
        <div class="shadow-input">
            <input type="text" id="inputPhone" name="phone" placeholder="+7 ___ ___ __ __">
        </div>
    </div>
    <div class="row flo m-b-10">
        <div class="text-shadow-input">
            Ваш email:
        </div>
        <div class="shadow-input">
            <input type="text" name="email" placeholder="example@email.ru">
        </div>
    </div>
    <div class="row flo m-b-20">
        <div class="record_process_result"></div>
        <div class="inner-top-info" style="text-align: center;font-size: 1.2em;">
            Мы всегда рады вам помочь! <span class="info-phone">8 495 215 09 07</span>
        </div>
    </div>
    <div class="row flo m-b-10 a-c">
        <input style="width:200px;" type="submit" class="btn-1 resume-btn" value="Записаться">
    </div>

</div>