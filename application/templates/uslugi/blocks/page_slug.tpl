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
                        <li class="ff-regular"><a href="/uslugi/<?= $subone['full_slug'] ?>"><?= $subone['name'] ?></a><span class="ff-medium"> от <?= $subone['price'] ?> руб.</span></li>
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
