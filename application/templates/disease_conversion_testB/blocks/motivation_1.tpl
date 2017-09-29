<div class="disease-doc-block">
    <div class="col-10 disease-doc-text avatar_buttons">
        <p>
            Клещевой энцефалит опасен для проживающих в эндемичных регионах или выезжающих туда людей.
            Укус клеща, если не было прививки, может стать причиной развития вирусного энцефалита, последствия которого - тяжелая инвалидизация или смерть.
            В эндемичных по клещевому энцефалиту регионах необходима вакцинация, чтобы не заразиться в случае укуса клеща.
            <br>
            <a class="btn-appoint" href="#record-to-the-doctor-popup-1635" onclick="$('.disease-doc-hide').slideToggle('slow');" style="width: 250px">Записаться на вакцинауию</a>
        </p>
    </div>
    <div class="col-2 disease-doc-image">
        <div class="avatar">
            <img src="http://lookmedbook.ru/media/upload/clinic/license/74x111-crop-1390897859-GNTQf82KG8.jpg">
        </div>
    </div>
    <br clear="all">
    <div align="right">
        Чекулаева  Наталья  Петровна (Врач инфекционист)
    </div>
</div>
<div class="disease-doc-hide" style="display:none;padding-left: 50px">
    <script type="text/javascript">
        $( document ).ready(function() {
            $(".datepicker").datepicker();
            $(".inputPhone").mask("(999999) 999-9999");
        });
    </script>
    <h1>Запись на прием</h1>
    <form action="/ajax/recordToTheVisit" method="POST" class="recordPopupForm linkMapper" rel=".recordFormResult" onComplete="recordComplete()">
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
        <input style="width:200px;" type="submit" class="btn-1 resume-btn" value="Записаться" onclick="yaCounterLookmedbook.reachGoal('entsifolit_record');">
    </div>
        <input type="hidden" name="clinic_id">
        <input type="hidden" name="doctor_id">
        <input type="hidden" name="disease_id" value="181">
    </form>
    <div class="recordFormResult" style="display: none"></div>
    <div class="recordFormSuccess" style="display: none">Вы успешно записаны!</div>
    <div class="recordFormFail" style="display: none">Запись не удалась!</div>
</div>