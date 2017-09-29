<?php
    /**
     * @var View $this
     * @var AccountModel $current_account
     * @var ClinicModel $clinic
     */
?>
<?php if ($current_account  && $current_account->is_call_centre_operator && ($clinic->phones || $clinic->site)): ?>
    <div class="phone_a clinic-info-hint clinic-info-hint-<?php echo $clinic->getId(); ?>">
        <?php if ($clinic->phones): ?>
            <span class="h-blue">Телефон:</span>
            <span class="txt" itemprop="tel"><?php echo PhoneNumberViewHelper::getView($clinic->phones[0]->phone_number); ?></span>
        <?php endif; ?>
        <?php if ($clinic->site): ?>
            <div>
                <span class="h-blue">Сайт:</span>
                <span class="txt"><a href="<?php echo UrlViewHelper::getLinkView($clinic->site); ?>" target="_blank"><?php echo UrlViewHelper::getShortView($clinic->site); ?></a></span>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>