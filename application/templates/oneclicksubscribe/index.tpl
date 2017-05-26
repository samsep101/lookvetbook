
<script language="javascript">
$(document).ready(function(){
    $(".nav-table").hide();
    $(".quick-search").hide();
    $("#authorization-block-on-disease-page").hide();
    $(".footer-inner-bottom").hide();
    $(".link_bottom").hide();
});
</script>
<style>
    .adv_text{
    width: 55%;
    font-size: 16px;
}    
.content .ocs {
    width          : 276px;
    margin         : 0 auto;
    padding-bottom : 25px;
    padding-top    : 28px;
}

</style>
<div align="center">
<div class="inner-2 flo" style="width:300px">
<div id="doctor-search-form" class="refactor" style="align: center">
    <div class="search-form">
        <div class="box flo">
            <?php if (!isset($show_title) || $show_title): ?>
            <div class="adv_text">
            <div class="like_p">Подберем лучшего профессионала по цене от 1,500 руб рядом с Вами</div>
            </div>
            <h2 id="box_h1">Записаться к врачу</h2>
            <?php endif; ?>

            <div class="colapse"> </div>
            <div class="in_colapse">
                <div class="sel-box doctor-box">
                    <select id="specialties_to_search_doctor" data-placeholder="Специальность врача" class="chzn-select" name="specialty_id" style="width:100%;">
                        <?php $this->block('oneclicksubscribe/blocks/specialties_options'); ?>
                    </select>
                </div>
                <div class="lmb">
                    <a class="btn-appoint" href="#record-to-the-doctor-popup" onclick="recordController.showForm(0,0,0)">Записаться</a> 
                </div>
            </div>
        </div>
    </div>
</div>
</div>    
</div>