<?if ($clinics):?>
    <?$clinics_count = 0;?>
    <ul class="updates-list">
        <?foreach ($clinics as $clinic):?>
            <?if (($clinics_count != 10)):?>
                <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($clinic, 'clinic'); ?></li>
            <?endif?>
            <?$clinics_count++;?>
        <?endforeach?>
    </ul>
    <?php //Test::dump($pages_total); ?>
    <?php echo PagingViewHelper::paging($page_url.'?query='.$query.'&page=:page:', $pages_total, $current_page); ?>
<?endif?>