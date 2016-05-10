<?php $this->container = '#action-form'; ?>

<form action="/registry/clinic/actionSave" method="post" enctype="multipart/form-data">
    <div class="fields-block flo">
        <p>Добавление новой акции</p>
        <div class="row-record">
            <label>Название</label>
            <div class="row-record-data">
                <input type="text" name="form[name]" value="" style=""><br>
            </div>
        </div>
        <div class="row-record">
            <label></label>
            <div class="education-parameter short-parameter">
                <label>Дата начала</label>
                <input type="text" name="form[date_from]" value="<?=date('d-m-Y')?>" id="form_date_from">
                <input type="button" id="form_date_picker_date_from" value="Выбрать дату">
                <script>
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_from',
                                date: '<?=date('d-m-Y')?>'
                            }
                    );
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_picker_date_from',
                                date: '<?=date('d-m-Y')?>'
                                eventName: 'click'
                            }
                    );

                </script>
            </div>
            <div class="education-parameter short-parameter">
                <label>Дата окончания</label>
                <input type="text" name="form[date_to]" value="<?=date('d-m-Y')?>" id="form_date_to">
                <input type="button" id="form_date_picker_date_to" value="Выбрать дату">
                <script>
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_to',
                                date: '<?=date('d-m-Y')?>'
                            }
                    );
                    Calendar.setup(
                            {

                                ifFormat:"%d-%m-%Y", daFormat:"%d-%m-%Y",
                                inputField: 'license_issue_date',
                                button: 'form_date_picker_date_to',
                                date: '<?=date('d-m-Y')?>'
                            eventName: 'click'
                    }
                    );
                </script>
            </div>
        </div>
        <div class="row-record">
            <label>Описание</label>
            <div class="row-record-data">
                <textarea class="htmlarea" id="form_info" name="form[info]" style="width: 100%; height:350px;"></textarea>
                <script type="text/javascript">
                    $(document).ready(function() {
                        CKEDITOR.replace( 'form_info',
                                {
                                    filebrowserBrowseUrl : '/media/js/ckeditor/ckfinder.html',
                                    filebrowserUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Files',

                                    filebrowserImageBrowseUrl : '/media/js/ckeditor/ckfinder.html?type=Images',
                                    filebrowserFlashBrowseUrl : '/media/js/ckeditor/ckfinder.html?type=Flash',

                                    filebrowserImageUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                    filebrowserFlashUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Flash',

                                    filebrowserWindowWidth : '600',
                                    filebrowserWindowHeight : '600'
                                });

                        getValue(CKEDITOR.instances.form_info.getData());

                        function getValue(value) {
                            $("textarea#form_info").html(value);

                            setTimeout(function() {
                                getValue(CKEDITOR.instances.form_info.getData())
                            }, 1000);
                        };
                    });
                </script>
            </div>
        </div>
    <?php if ($specializations): ?>

        <p>Специализации</p>
        <div class="fields-block-inner white-inner">
            <div class="specialties-block" data-holder-for="specialization_to_clinic">
                <?php foreach ($specializations as $e):
                $specialization = $e->specialization;
                ?>
                <div class="clinic_specialty">
                    <label>
                        <?php
                                        if ($specialization->is_selected){
                        $class = 'act';
                        $value = 1;
                        } else {
                        $class = '';
                        $value = 0;
                        }
                        ?>
                        <div data-name="specialization_to_clinic" class="specialization_to_clinic">
                            <input type="hidden" name="specialization_id[]" value="<?php echo $specialization->getId() ?>" />
                            <div class="chekBox <?php echo $class; ?>">
                                <span></span>
                                <?=$specialization->name; ?>
                                <input type="hidden" name="is_selected" value="<?php echo $value; ?>" />
                            </div>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
        <div class="buttons flo">
            <!-- <input class="btn-appoint longest-button" type="submit" name="sent_back" value="Отправить на доработку" onclick="return false;">-->
            <input class="btn-1" type="submit" name="publish" value="Сохранить" onclick="return false;">
            <input class="btn-appoint" type="submit" name="cancel" value="Отменить" onclick="return false;">



        </div>
    </div>
</form>