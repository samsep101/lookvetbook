<div id="learn_form_container" style="display:none">
    <form action="/ajax/recordToTheVisit" method="POST" class="learnPopupForm linkMapper" rel=".learnFormResult" onComplete="recordComplete()" onsubmit="if (!$(this).find('input[name=full_name]').val() || !$(this).find('input[name=phone]').val()){alert('Вы не заполнили поля имя или телефон'); $(this).find('.doSubmit').val('false');}else{$(this).find('.doSubmit').val('');}">
        <div class="booking record-to-the-doctor-popup" style="display:block">
            <div class="all">
                <h1>Получить информацию</h1>
                <div class="step-block-1 flo" style="display: none">
                    <!-- place for info where user want to visit -->
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
                        <input type="text" id="inputPhone" class="inputPhone" name="phone" placeholder="+7 (___) ___-__-__">
                    </div>
                </div>
                <div class="row flo m-b-20">
                    <div class="record_process_result"></div>
                    <div class="inner-top-info" style="text-align: center;font-size: 1.2em;">
                        Мы всегда рады вам помочь! <span class="info-phone"><a href="tel:+7(495)215-09-27">8 495 215 09 27</a></span>
                    </div>
                </div>
                <div class="row flo m-b-10 a-c">
                    <input style="width:200px;" type="submit" class="btn-1 resume-btn js-hide-on-record-complete" value="Записаться">
                </div>

            </div>
            <input type="hidden" name="clinic_id">
            <input type="hidden" name="doctor_id">
            <input type="hidden" name="disease_id">
            <input type="hidden" class="doSubmit">
    </form>
    <div class="recordFormResult" style="display: none"></div>
    <div class="recordFormSuccess" style="display: none">Вы успешно записаны!</div>
    <div class="recordFormFail" style="display: none">Запись не удалась!</div>
</div>

<!-- record default form ------>