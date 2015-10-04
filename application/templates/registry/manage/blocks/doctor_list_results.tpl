<?if ($doctors):?>
    <div class="doctor-list">
        <?php $this->block('registry/manage/blocks/doctor_results'); ?>
    </div>
<?else:?>
    <p class="no-results">По вашему запросу врачей не найдено</p>
<?endif?>