<!-- record default form ------>
<div id="record_form_container" style="display:none">
    <form action="" method="POST" rel="" class="linkMapper">
    <div class="booking record-to-the-doctor-popup" style="display:block">
        <div class="all">
            <h1>Запись на прием</h1>
            <div class="step-block-1 flo" style="display: none">
                <!-- place for info where user want to visit -->
            </div>


                Где вам удобно посетить врача:
                <select name="okrug_id">
                    <option value=""></option>
                    <option value="1">ЦАО</option>
                    <option value="2">СВАО</option>
                </select>

                Когда нужно к врачу:
                <input type="text" name="visit_start">
                <input type="checkbox" name="after_work"> - после работы

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
    </form>
</div>

<!-- record default form ------>