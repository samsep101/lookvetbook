<div class="type-and-service-area">
    <?php if (!empty($clinic->additional_params['multidisciplinary'])) : ?>
        <div class="multidisciplinary">
            Многопрофильная клиника
        </div>
    <?php endif; ?>
    
    <div class="types-services-list">
        <?php if (!empty($clinic->additional_params['twenty-four-hours'])) : ?>
            <div class="tsl-item">
                <div class="tsl-description">
                    <div class="tsl-text">Круглосуточная</div>
                    <div class="tsl-pointer"></div>
                </div>
                <div class="tsl-icon icon-twenty-four-hours"></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($clinic->additional_params['accepts-children'])) : ?>
            <div class="tsl-item">
                <div class="tsl-description">
                    <div class="tsl-text">Принимает детей</div>
                    <div class="tsl-pointer"></div>
                </div>
                <div class="tsl-icon icon-accepts-children"></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($clinic->additional_params['have-ramp'])) : ?>
            <div class="tsl-item">
                <div class="tsl-description">
                    <div class="tsl-text">Есть пандус</div>
                    <div class="tsl-pointer"></div>
                </div>
                <div class="tsl-icon icon-have-ramp"></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($clinic->additional_params['payment-cards'])) : ?>
            <div class="tsl-item">
                <div class="tsl-description">
                    <div class="tsl-text">Оплата картой</div>
                    <div class="tsl-pointer"></div>
                </div>
                <div class="tsl-icon icon-payment-cards"></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($clinic->additional_params['medical-certificates'])) : ?>
            <div class="tsl-item">
                <div class="tsl-description">
                    <div class="tsl-text">Больничные листы</div>
                    <div class="tsl-pointer"></div>
                </div>
                <div class="tsl-icon icon-medical-certificates"></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($clinic->additional_params['leave-the-house'])) : ?>
            <div class="tsl-item">
                <div class="tsl-description">
                    <div class="tsl-text">Выезд на дом</div>
                    <div class="tsl-pointer"></div>
                </div>
                <div class="tsl-icon icon-leave-the-house"></div>
            </div>
        <?php endif; ?>
        <div class="clearfix"></div>
    </div>
</div>