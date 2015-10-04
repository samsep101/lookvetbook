<?php
    /**
     * @var string $instance_name
     */
?>

<?if ($instances):?>
    <ul class="updates-list <?php echo $instance_name;?>-list">
        <?foreach ($instances as $instance):?>
            <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($instance, $instance_name); ?></li>
        <?endforeach?>
    </ul>
    <a class="show-all" href="/registry/manage/<?php echo $instance_name;?>s">Показать все</a>
<?else:?>
    <div style="clear: both; margin-top: 10px;">
        нет данных
    </div>
<?endif?>