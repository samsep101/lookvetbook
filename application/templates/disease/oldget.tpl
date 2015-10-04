<?php
if (isset($_COOKIE['already_registred_account']))
    $already_registred_account = 1;
else
    $already_registred_account = 0;
?>

<script>
    $(function() {
    <?php if (isset($disease_tabs)): ?>
        var first_tab = 0;
        <?php foreach ($disease_tabs as $key=>$value): ?>
            <?php if ($value): ?>
                if (first_tab==0)
                    first_tab = '<?echo $key?>';
                else
                    $('.sub-nav-<?echo $key?>').hide();
                <?php endif; ?>
            <?php endforeach; ?>


        $('.sub-nav-'+first_tab+'.carousel ul').carouFredSel({
            auto: false,
            prev: '.prev',
            next: '.next',
            scroll:{items:1},
            circular: false,
            infinite:false
        });

        $('.ancor-'+first_tab).each(function(i) {
            var id = $(this).attr('id')
            var of = $(this).offset().top
            ancor.push({id:id,of:of})
        })
        <?php endif; ?>

        $(document).ready(function(){
            var disease_controller = new DiseasePageController('<?php echo $disease->id?>',<?php echo (!Acc::isAuthed()) ? 1 : 0; ?>, <?php echo $already_registred_account; ?>, first_tab);
            disease_controller.changeSpecialtyBlock(first_tab);
            disease_controller.init();
        });


    <?if ($disease->my_disease) {?>
        $('.btn-bookmark-illness').addClass('btn-bookmark-added');
        $('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
        <?} else if (Acc::isAuthed()) {?>
        $('.btn-bookmark-illness').removeClass('btn-bookmark-added');
        $('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
        <?}?>

    });

</script>

<?if ($disease) :?>

<div class="inner">
    <div class="about-ilness-content flo">
        <div class="main-column">
            <div class="main-cont flo">
                <div class="illness-header flo">
                    <h1><?=$disease->title?></h1>
                    <?if (Acc::isAuthed()):?>
                    <a class="btn-bookmark btn-bookmark-illness"></a>
                    <?endif?>
                </div>
                <div class="illness-description">
                    <?$disease->content = preg_replace('/<br \/>/','',$disease->content);?>
                    <div class="like_p"><?=html_entity_decode($disease->content,ENT_COMPAT,'UTF-8')?></div>
                </div>

                <?if ($disease_blocks) :?>
                <div id="tabs">
                    <div class="illness-nav-wrap">
                        <div class="illness-nav" data-spy="affix">
                            <div class="nav">
                                <ul>
                                    <?foreach ($disease_tabs as $key=>$value):?>
                                    <?if ($value):?>
                                        <li class="tab-people tab-<?=$key?>"><a href="#tabs-<?=$key?>" id="<?=$key?>"><?=$value?></a></li>
                                        <?endif?>
                                    <?endforeach?>
                                </ul>
                            </div>
                            <!--add class "carousel" to sub-nav for sliding-->

                            <?foreach ($disease_tabs as $key=>$value):?>
                            <?if ($value):?>

                                <div class="sub-nav sub-nav-<?=$key?>" id="<?=$key?>">
                                    <ul>
                                        <?$sub_counter = 1;?>
                                        <?foreach ($disease_blocks as $block):?>

                                        <?php $field_name = $key.'_flag';?>
                                        <?php $field_anchor = $key.'_'.$block->id;?>

                                        <?if ($block->$field_name == 1):?>
                                            <li><a data-t="<?=$field_anchor?>" href="#<?=$field_anchor?>"><?=$block->disease_block_type->name?></a></li>
                                            <?$sub_counter++;?>
                                            <?endif?>
                                        <?endforeach?>
                                    </ul>
                                    <?if ($sub_counter>7):?>
                                    <script>
                                        $('.sub-nav-<?=$key?>').addClass('carousel');
                                    </script>
                                    <?endif?>
                                    <a class="prev" href="#"></a> <a class="next" href="#"></a>
                                </div>
                                <?endif?>
                            <?endforeach?>

                        </div>
                    </div>

                    <?$ancor_counter = 1;?>
                    <?foreach ($disease_tabs as $key=>$value):?>
                    <?if ($value):?>
                        <div id="tabs-<?=$key?>" class="content">

                            <?foreach ($disease_blocks as $block):?>

                            <?php $field_name = $key.'_flag';?>
                            <?php $field_anchor = $key.'_'.$block->id;?>

                            <?if ($block->$field_name == 1):?>

                                <div id="<?=$field_anchor?>" class="section ancor-<?=$key?>">
                                    <h2><?=$block->disease_block_type->name?></h2>
                                    <div class="like_p">
                                        <?$block->content = preg_replace('/<br \/>/','',$block->content);?>
                                        <?=html_entity_decode($block->content,ENT_COMPAT,'UTF-8')?>
                                    </div>
                                </div>

                                <?endif?>
                            <?endforeach?>

                            <div class="section section-help flo"> <span class="info-title">Текст понятен?</span>
                                <div class="info-buttons"></div>
                                <div class="info-help"> <strong>ИНФОРМАЦИЯ ДЛЯ ОЗНАКОМЛЕНИЯ</strong> Необходима консультация с врачом </div>
                            </div>


                        </div>
                        <?endif?>
                    <?$ancor_counter++?>
                    <?endforeach?>
                </div>
                <?endif?>

                <?if ($disease->extended_content || $disease->sources):?>
                <div class="other-links">
                    <ul>
                        <?if ($disease->sources):?>
                        <li> <span>Источники</span>
                            <div class="drop-box">
                                <p><?=html_entity_decode($disease->sources,ENT_COMPAT,'UTF-8')?></p>
                            </div>
                        </li>
                        <?endif?>
                        <?if ($disease->extended_content):?>
                        <li> <span>Расширенное описание</span>
                            <div class="drop-box">
                                <p><?=html_entity_decode($disease->extended_content,ENT_COMPAT,'UTF-8')?></p>
                            </div>
                        </li>
                        <?endif?>
                    </ul>
                </div>
                <?endif?>

            </div>
            <div id="cards-wrap">
            </div>
        </div>

        <div class="side-column" data-spy="affix" data-offset-top="100">
            <?if ($disease_specialties):?>
            <?foreach ($disease_specialties as $specialty):?>
                <div class="info-box doing-box <?if ($specialty->is_adult){?>adult-block <?}?><?if ($specialty->is_male){?>male-block <?}?><?if ($specialty->is_female){?>female-block <?}?><?if ($specialty->is_children){?>children-block <?}?><?if ($specialty->is_newborn){?>newborn-block <?}?><?if ($specialty->is_pregnant){?>pregnant-block<?}?>">
                    <h3>Что делать</h3>
                    <ol class="todo-list">
                        <li>
                            <?php if (!Acc::isAuthed()): ?>
                            <p>Врач
                                <a class="disease-doctor" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-id="<?=$specialty->specialty_id?>" data-text="<?=$specialty->plural_name?>" data-url="/doctor/search?specialty_id=<?=$specialty->specialty_id?>&time_of_visit=any&sort_by=recomend" href="javascript:void(0);">
                                    <?=$specialty->name?>
                                </a> поможет при лечении заболевания
                            </p>
                            <a class="btn-find-doctor disease-doctor" data-action-for-counters="find-doctor" data-url="/doctor/search?specialty_id=<?=$specialty->specialty_id?>&time_of_visit=any&sort_by=recomend" data-id="<?=$specialty->specialty_id?>" href="javascript:void(0);">
                                Найти врача
                            </a>
                            <?php else: ?>
                            <p>Врач
                                <a class="disease-doctor" data-action-for-counters="disease-right-doctor" data-id="<?=$specialty->specialty_id?>" href="/doctor/search?specialty_id=<?=$specialty->specialty_id?>&time_of_visit=any&sort_by=recomend">
                                    <?=$specialty->name?>
                                </a> поможет при лечении заболевания
                            </p>
                            <a class="btn-find-doctor disease-doctor" data-action-for-counters="find-doctor" data-id="<?=$specialty->specialty_id?>" href="/doctor/search?specialty_id=<?=$specialty->specialty_id?>&time_of_visit=any&sort_by=recomend">
                                Найти врача
                            </a>
                            <?php endif; ?>
                        </li>
                        <!--
                        <li>
                            <p>Сдайте анализы:</p>
                            <ul>
                                <li><a href="#">- анализ крови</a></li>
                            </ul>
                            <a class="btn-find-lab" href="#">Найти лабораторию</a> </li> -->
                    </ol>
                </div>
                <?endforeach?>
            <?endif?>
            <?if ($disease->medicine):?>
            <div class="info-box">
                <h3>Медикаменты</h3>
                <div class="medicament-block">
                    <p class="medicament-title"><?=$disease->medicine->name?></p>
                    <a class="medicament-url" href="#">Список аптек</a>
                    <?if ($disease->medicine->image):?>
                    <img src="<?=$disease->medicine->image->resize(234,200)->path?>" class="medicament-logo" alt="" />
                    <? else:?>
                    <img src="/media/images/no-photo.gif" class="medicament-logo" alt="" />
                    <?endif?>
                    <div class="center-align"><a class="medicament-btn" href="#">Купить онлайн <?=$disease->medicine->price?> P</a></div>
                    <p class="medicament-info">Перед приемом лекарства проконсультируйтесь у варача</p>
                </div>
            </div>
            <?endif?>

            <?php if (!Acc::isAuthed()): ?>
            <div class="info-box we-good">
                <img src="/media/images/owl-face-pic.png">
                <h3>О LookMedBook</h3>
                <ul class="list">
                    <li><p>Находи врачей и записывайся на прием online</p></li>
                    <li><p>Читай о заболеваниях на твоем языке</p></li>
                    <li><p>Храни историю визитов в личном кабинете</p></li>
                    <li><p>Получай настоящий сервис и качество</p></li>
                    <li><p>Зарегистрируйся. Это бесплатно!</p></li>
                    <a class="btn-reg" href="#registration-popup">Зарегистрироваться</a>
                </ul>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="inner-2">
    <div id="our-doctors">
        <a class="view-more"><i class="icon-loader"></i></a>
    </div>
</div>

<?endif?>