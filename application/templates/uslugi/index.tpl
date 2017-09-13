<?php

    $slice_count = 4;

    $tree = $this->tree;
    foreach($tree as $id => $one){
        if(!empty($one['subslugs'])){
            $one['showmore_slugs'] = array_slice($one['subslugs'], $slice_count, null, true);
            $one['subslugs'] = array_slice($one['subslugs'], 0, $slice_count, true);
        }
        $tree[$id] = $one;
    }
    $this->tree = $tree;
    unset($tree);
?>

<link rel="stylesheet" href="/media/uslugi/styles.css" type="text/css">
<script type="text/javascript" src="/media/uslugi/services.js"></script>
<link rel="stylesheet" href="/media/css/product-article.css?rnd=<?= Articles_Viewer::RND?>" type="text/css">
<script type="text/javascript" src="/media/js/articles-spoiler.js?rnd=<?= Articles_Viewer::RND?>"></script>



<div class="services-events">

    <?=$this->renderInString('responsive/includes/breadcrumbs', false)?>

    <div class="container">
        <div class="header-phone ff-medium">
            <div class="text">Есть вопросы? Не нашел нужную услугу?</div>
            <div class="phone"><span>Звони, мы поможем</span><br><span class="number_set">+7(800) 333-27-00</span><br></div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 services-menu">
                <div class="box-form">
                    <div class="box-header">Найти услугу</div>
                    <select class="chosen-select" name="service" id="service_name">
                        <option selected>Выбрать услугу</option>
                        <?php foreach($this->tree as $one) :
                            $attrs = ($this->current_slug == $one['slug']) ? 'selected' : '';
                        ?>
                            <option value="/uslugi/<?=$one['slug']?>" <?=$attrs?>><?=$one['name']?></option>
                            <?php if(!empty($one['subslugs'])) : foreach($one['subslugs'] as $subone) :
                                $attrs = ($this->current_slug == $subone['full_slug']) ? 'selected' : '';
                            ?>
                                <option class="sub" value="/uslugi/<?=$subone['full_slug']?>" <?=$attrs?>><?=$subone['name']?></option>
                            <?php endforeach; endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <?php /* <select class="chosen-select" name="service">
                        <option>Выбрать округ</option>
                        <?php foreach($this->districts as $one) : ?>
                            <option value="<?=$one['alias']?>" data-id="<?=$one['id']?>"><?=$one['name']?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="chosen-select" name="service">
                        <option>Выбрать станцию метро</option>
                    </select> */?>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12"></div>
        </div>
    </div>

    <div class="container page-h1 ff-regular">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?=$h1?></h1>
            </div>
        </div>
    </div>



    <?php if(!empty($page)) : ?>

        <div class="container">
            <?php if($page == 'slug') : echo $this->renderInString('uslugi/blocks/page_slug', false); endif;?>
            <?php if($page == 'article') : echo $this->renderInString('uslugi/blocks/page_article', false); endif;?>
        </div>

    <?php else : ?>

        <div class="container tree-services">
            <div class="row">
                <?php foreach($this->tree as $one) : ?>
                <div class="column col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <div class="ts-header ff-medium"><a href="/uslugi/<?=$one['slug']?>"><?=$one['name']?></a><span><?=($one['count'] > 0) ? $one['count'] : ''?></span></div>
                    <?php if($one['count'] > 0) : ?>

                        <ul>
                            <?php foreach($one['subslugs'] as $subone) : ?>
                            <li><a href="/uslugi/<?=$subone['full_slug']?>"><?=$subone['name']?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if(!empty($one['showmore_slugs'])) : ?>
                            <div class="showmore">
                                <ul class="spoiler">
                                    <?php foreach($one['showmore_slugs'] as $subone) : ?>
                                    <li><a href="/uslugi/<?=$subone['full_slug']?>"><?=$subone['name']?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="javascript:void(0)" data-fliptext="скрыть" data-showmore=".spoiler">показать все</a>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endif; ?>

    <div class="container" style="padding-top:15px;">
        <?=$this->renderInString('uslugi/blocks/roots', false);?>
    </div>

</div>

<script>
    $(function(){
       $(document).on('click', '[data-showmore]', function(e){
           e.preventDefault();
           var _ = $(this);
           if(_.hasClass('open')){
               _.removeClass('open');
               _.parent().find(_.data('showmore')).slideUp(200);
               _.text(_.data('flipold'));
           } else {
               _.addClass('open');
               _.parent().find(_.data('showmore')).slideDown(200);
               _.data('flipold', _.text());
               _.text(_.data('fliptext'));
           }
       });
    });
</script>