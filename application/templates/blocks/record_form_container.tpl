<!-- record default form ------>
<div id="record_form_container" style="display:none">
    <form action="/ajax/recordToTheVisit" method="POST" class="recordPopupForm linkMapper" rel=".recordFormResult" onComplete="recordComplete()">
    <div class="booking record-to-the-doctor-popup" style="display:block">
        <div class="all">
            <h1>Запись на прием</h1>
            <div class="step-block-1 flo" style="display: none">
                <!-- place for info where user want to visit -->
            </div>

                Когда нужно к врачу:
                <input type="text" name="visit_start">
                <input type="checkbox" name="after_work" value="1"> - после работы

                Ваше имя:
                <input type="text" name="full_name">

                Ваш телефон:
                <input type="text" name="phone">

                Ваш email:
                <input type="text" name="email">

                <div class="btns flo">
                    <input type="submit" class="btn-1 resume-btn" value="Записаться">
                </div>


            <div class="record_process_result"></div>

            <div class="inner-top-info" style="text-align: center;">
                Мы всегда рады вам помочь! <span class="info-phone">8 495 215 09 07</span>
            </div>

        </div>

    </div>
        <input type="hidden" name="clinic_id">
        <input type="hidden" name="disease_id">
    </form>
    <div class="recordFormResult" style="display: none"></div>
    <div class="recordFormSuccess" style="display: none">Вы успешно записаны!</div>
    <div class="recordFormFail" style="display: none">Запись не удалась!</div>
</div>

<!-- record default form ------>