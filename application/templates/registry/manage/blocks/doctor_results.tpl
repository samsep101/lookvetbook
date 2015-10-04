<?if ($doctors):?>
        <?$doctors_count = 0;?>
        <ul class="updates-list">
            <?foreach ($doctors as $doctor):?>
                <?if (($doctors_count != 10)):?>
                    <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($doctor, 'doctor');?></li>
                <?endif?>
                <?$doctors_count++;?>
            <?endforeach?>
        </ul>
    <?php
     echo PagingViewHelper::paging($page_url.'?query='.$query.'&page=:page:', $pages_total, $current_page);
    ?>
<?endif?>