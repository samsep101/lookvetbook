<?php if (isset($doctors_page) && $doctors_page):?>
    <div class="top_number refactor">
    <?php if (isset($is_seo_page) && $is_seo_page): ?>
        <?php echo SeoBreadcrumbsViewHelper::getViewForSpecialtyPages($specialty, $address_object, $setDefaultSpecialty, array('tnInnerStyle')); ?>
    <?php endif; ?>
        <h1 class="number_left refactor">
            <?php
                if($setDefaultSpecialty)
                {
                    echo SeoTextViewHelper::getTopNumberH1('', $city);
                }
                else
                {
                    echo SeoTextViewHelper::getTopNumberH1($specialty, $address_object);
                }
            ?>
        </h1>
        <?php
            $phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
            if(!empty($phone)) {
        ?>

            <div class="number_right refactor">
                <span class="refactor">Поможем подобрать врача</span><br />
                <span class="number_set refactor"><?php echo $phone; ?></span><br />
                <span class="work_time">с 09 до 21:</span>
            </div>

        <?php
            }
        ?>
    </div>
<?php elseif (isset($doctor_page) && $doctor_page):?>
    <div class="top_number refactor default_top_number">
        <?php if (isset($is_seo_page) && $is_seo_page): ?>
            <?php echo SeoBreadcrumbsViewHelper::getViewForSpecialtyPages($specialty, $address_object, $setDefaultSpecialty); ?>
        <?php endif; ?>
        <div class="number_left refactor-number-left-styles">
            <?php
                $data_return = SiteUriHelper::returnToSearchForm();
                if (!empty($data_return['count']) && !empty($data_return['link'])) {
            ?>
                <a class="back-to-search doctor-top-page" href="<?php echo $data_return['link']; ?>"> Вернуться к результатам поиска <span>(<?php echo $data_return['count'] . ' ' . SpecialtyHelper::getDoctorWordForm($data_return['count']); ?>)</span></a>
            <?php } elseif(SiteUriHelper::previousPageIsClinicPage()) { ?>
                <a class="back-to-search doctor-top-page" href="<?php echo $_SERVER['HTTP_REFERER']; ?>"> Вернуться на страницу клиники </a>
            <?php } ?>
        </div>
        <?php
            $phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
            if(!empty($phone)) {
        ?>

            <div class="number_right refactor">
                <span class="refactor">Поможем подобрать специалиста с 09 до 21:</span><br />
                <span class="number_set refactor"><?php echo $phone; ?></span><br />
            </div>

        <?php
            }
        ?>
    </div>
<?php else:?>
    <div class="top_number">
        <div class="number_left">Есть вопросы? Не нашел нужного специалиста?</div>
        <?php
            if($clinic->top_phone) {
                $phone = $clinic->top_phone;
            } else {
                $phone = HelpPhoneNumberViewHelper::getPhoneNumber($city);
            }
            if(!empty($phone)) {
        ?>

            <div class="number_right">
                <span>Звони, мы поможем</span><br />
                <span class="number_set"><?php echo $phone; ?></span><br />
            </div>

        <?php
            }
        ?>
    </div>
<?php endif;?>