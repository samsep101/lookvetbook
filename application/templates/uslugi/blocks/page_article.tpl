<?php if($this->article['description'] != "" ): ?>
<div>
    <div class="about-ilness-content w100">
        <div class="main-cont panel-post">

            <?=$this->article['description'];?>

        </div>
    </div>
</div>
<?php endif; ?>

<a href="<?=$this->btnslug?>" class="btn-default"><i class="glyphicon glyphicon-menu-left"></i> <?=$this->btnback?></a>

<div class="clinic-list row">

    <?php foreach($this->clinics as $clinic) :
        $childs = $clinic->childs;
    ?>
    <div class="column col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="box-shadow">
            <div class="item" data-clinic-id="<?=$clinic->id?>">
                <div class="avatar">
                    <?=ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31); ?>
                </div>
                <div class="info">
                    <div class="name ff-bold"><a href="<?=ClinicPageLinkViewHelper::getLink($clinic); ?>"><?=$clinic->name?></a></div>
                    <div class="rate"><?=RateViewHelper::view($clinic->rate, 0, $clinic->is_best); ?></div>
                    <?php include Application::getTemplatesDir(true).'/__common/clinic_item_additional.tpl'?>
                    <?php //if($clinic->phone) : ?>
                    <div class="phone ff-medium"><i class="glyphicon glyphicon-phone"></i>&nbsp;<?=$clinic->phone?><?=  \StringHelpers\format_phone('+7920-12-12-356')?></div>
                    <?php //endif; ?>
                </div>
                <?php if($clinic->address) : ?>
                <div class="info-address">
                    <?php include Application::getTemplatesDir(true).'/__common/clinic_item_address_and_time.tpl'?>
                </div>
                <?php endif;?>
            </div>
            <?php if(!empty($childs)) : ?>
            <div class="subclinic-list">
                <?php foreach($childs as $filial) : //debug($filial)?>
                    <div class="sub-item">
                        <div class="name"><a href="/clinic/<?=$filial['alias']?>"><?=$filial['name']?></a></div>
                        <div class="rate"><?=RateViewHelper::view($filial['rate'], 0, $filial['is_best']); ?></div>
                        <div class="address"><i class="glyphicon glyphicon-map-marker"></i><?=$filial['address']?></div>
                        <div class="shedule"><?=ScheduleViewHelper::get_format_days($filial)?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="clearfix"></div>

</div>

<?php if($this->pagination->total > $this->pagination->perpage) : echo $this->pagination->html($this->base_url); endif;?>
