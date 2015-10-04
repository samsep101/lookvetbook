<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new ManageController();
        form_controller.init();
    });
</script>

<?php echo $this->block('registry/manage/blocks/city_filter');?>

<div class="fields-block manager-account-block">

    <div class="fields-block-inner manager-account-search search-results white-inner">
        <div class="row-record">
            <p>Врачи</p>
            <div class="search-box flo">
                <input data-id="doctor" class="txt placeholder" type="text" value="<?if ($query){echo strip_tags($query);}?>" placeholder="Петров" style="width: 350px;">
                <input data-id="doctor" class="btn-search" type="submit">
                <ul class="drop-menu"> </ul>
            </div>
            <div class="filter-doctor-list">
                <?if ($doctors):?>
                    <div class="doctor-list">
                        <?php $this->block('registry/manage/blocks/doctor_results'); ?>
                    </div>
                <?else:?>
                    <p class="no-results">По вашему запросу врачей не найдено</p>
                <?endif?>
            </div>
        </div>
    </div>

</div>