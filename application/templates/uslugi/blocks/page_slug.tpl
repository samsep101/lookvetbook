<?php

    $current_tree = $this->current_tree;
    empty($current_tree) AND $current_tree = $this->tree;

    $class_pricepage = '';
    $collapsed = false;

    $subslugs = current($current_tree)['subslugs'];
    if(count($subslugs) > 15) {
        $class_pricepage = 'collapsed';
        $collapsed = true;
    }

?>
<a href="/uslugi" class="btn-default"><i class="glyphicon glyphicon-menu-left"></i> Список услуг</a>

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="pricepage" class="simple-page <?=$class_pricepage?>">
                <ul class="pricelist">
                    <?php foreach ($subslugs as $subone) : ?>
                        <?php if($subone['total'] > 0) :
                            $subone['title'] = 'Услугу оказывают в '.$subone['total'].  StringHelpers\plural($subone['total'], [' клинике',' клиниках',' клиниках']);
                        ?>
                            <li class="ff-regular"><a class="hint--top-right" aria-label="<?=$subone['title']?>" href="/uslugi/<?= $subone['full_slug'] ?>"><?= $subone['name'] ?></a><span class="ff-medium"> от <?= $subone['price'] ?> руб.</span></li>
                        <?php else : ?>
                            <li class="ff-regular"><a href="/uslugi/<?= $subone['full_slug'] ?>"><?= $subone['name'] ?></a><span class="ff-medium"> от <?= $subone['price'] ?> руб.</span></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php if($collapsed) : ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a class="view-more" href="#">Показать все услуги</a>
        </div>
        <?php endif; ?>

    </div>

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
                    <div class="name"><a href="<?=ClinicPageLinkViewHelper::getLink($clinic); ?>"><?=$clinic->name?></a></div>
                    <div class="rate"><?=RateViewHelper::view($clinic->rate, 0, $clinic->is_best); ?></div>
                    <?php include Application::getTemplatesDir(true).'/__common/clinic_item_additional.tpl'?>
                    <div class="phone"><i class="glyphicon glyphicon-phone"></i>&nbsp;<?=$clinic->phone?></div>
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