<?php foreach ($disease_blocks_content as $block):?>
    <?php $field_anchor = 'b'.$block->id;?>

    <?php if ($block->disease_block_type_id == 5): ?>
        <div class="section">

            <div class="like_p with-sign">
                <?php echo SITE_NAME; ?> напоминает: чем раньше Вы обратитесь за помощью к специалисту, тем больше шансов сохранить здоровье и снизить риск развития осложнений:
            </div>
            <?php if ($disease_specialties):?>
                <div class="doing-box not-hide in-middle">
                    <ol class="todo-list">
                        <li>
                            <?php if (!Acc::isAuthed()): ?>
                            <p>Врач
                                <?php foreach ($disease_specialties as $specialty):?>
                                    <a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Center" data-text="<?php echo $specialty->plural_name; ?>" data-url="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend" href="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend"><?php echo $specialty->name; ?></a>
                                <?php endforeach;?>
                                поможет при лечении заболевания                              
                            </p>
                            <?php foreach ($disease_specialties as $specialty):?>
                                <a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-action-for-counters="find-doctor" data-category-counters="find-doctor" data-action="FindDocButton" data-position="Right" data-url="<?php echo $specialty->specialtyUrl ?>" data-id="<?php echo $specialty->specialty_id; ?>" href="<?php echo $specialty->specialtyUrl ?>">
  									<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
                                    <span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
                                </a>
                            <?php endforeach;?>
                            <?php else: ?>
                            <p>Врач
                                <?php foreach ($disease_specialties as $specialty):?>
                                    <a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Center" href="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend" data-text="<?php echo $specialty->plural_name; ?>"><?php echo $specialty->name; ?></a>
                                <?php endforeach;?>
                                поможет при лечении заболевания
                            </p>
                            <?php foreach ($disease_specialties as $specialty):?>
                                <a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="find-doctor" data-action="FindDocButton" data-position="Center" href="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend">
 									<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
                                    <span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
                                </a>
                            <?php endforeach;?>
                            <?php endif; ?>
                            <?php $this->block('disease/blocks/adv_after_reason_block'); ?>
                        </li>
                    </ol>
                </div>
            <?php endif?>
        </div>
    <?php endif; ?>

    <div class="section" id="<?php echo $field_anchor; ?>">
        <h2>
            <?php if ($block->disease_block_type_id == 1 || $block->disease_block_type_id == 6 || $block->disease_block_type_id == 8): ?>
                <?php echo $block->disease_block_type->name . ' ' . $disease->genitive_name; ?>
            <?php else: ?>
                <?php echo $block->disease_block_type->name; ?>
            <?php endif; ?>
        </h2>
        <div class="like_p">
            <?php $block->content = preg_replace('/<br \/>/','',$block->content);?>
            <?php $block->content = preg_replace('/<br\/>/','',$block->content);?>
            <?php echo html_entity_decode($block->content,ENT_COMPAT,'UTF-8'); ?>
        </div>
    </div>

    <div class="disease-doc-block">
        <div class="col-10 disease-doc-text avatar_buttons">
            <p>Когда (как давно) появились и сколько длятся выделения из половых путей, какой они имеют характер, беспокоит ли неприятный запах, с чем женщина связывает возникновение этих симптомов. <a href="javascript:void(0);" onclick="$('.disease-doc-hide').slideToggle('slow');">Записаться на прием</a></p>
            <a class="btn-appoint" href="#record-to-the-doctor-popup-1635" onclick="$('.disease-doc-hide').slideToggle('slow');">Записаться</a>
        </div>
        <div class="col-2 disease-doc-image">
            <div class="avatar">
                <a href="http://lookmedbook.ru/doctor/sharshnevaov"><img src="/media/upload/clinic/license/74x111-crop-1378124745-eEfE5Sr57d.jpg" alt="Аллерголог-иммунолог, терапевт, гомеопат Шаршнева Оксана Владимировна"></a>
            </div>
        </div>
    </div>
    <div class="disease-doc-hide" style="display:none;padding-left: 50px">
            <script type="text/javascript">
                $( document ).ready(function() {
                    $("#datepicker").datepicker();
                    $("#inputPhone").mask("(999999) 999-9999");
                });
            </script>
            <h1>Запись на прием</h1>
            <div class="step-block-1 flo" style="display: none">
                <!-- place for info where user want to visit -->
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Когда нужно к врачу:
                </div>
                <div class="shadow-input">
                    <input id="datepicker" type="text" name="visit_start" placeholder="01.01.2016">
                </div>
            </div>
            <div class="row flo m-b-10">
                <div style="width: 155px;float:left;">&nbsp;</div>
                <div class="shadow-checkbox">
                    <div class="chekBox act"><span></span> <em>после работы</em>
                        <input type="hidden" name="after_work" value="1">
                    </div>
                    <!-- <input type="checkbox" name="after_work" value="1"> - после работы -->
                </div>
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Ваше имя:
                </div>
                <div class="shadow-input">
                    <input type="text" name="full_name" placeholder="Иван">
                </div>
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Ваш телефон:
                </div>
                <div class="shadow-input">
                    <input type="text" id="inputPhone" name="phone" placeholder="+7 ___ ___ __ __">
                </div>
            </div>
            <div class="row flo m-b-10">
                <div class="text-shadow-input">
                    Ваш email:
                </div>
                <div class="shadow-input">
                    <input type="text" name="email" placeholder="example@email.ru">
                </div>
            </div>
            <div class="row flo m-b-20">
                <div class="record_process_result"></div>
                <div class="inner-top-info" style="text-align: center;font-size: 1.2em;">
                    Мы всегда рады вам помочь! <span class="info-phone">8 495 215 09 07</span>
                </div>
            </div>
            <div class="row flo m-b-10 a-c">
                <input style="width:200px;" type="submit" class="btn-1 resume-btn" value="Записаться">
            </div>

    </div>
    
    <?php /***** pediatr banner *****/ ?>
    <?php $is_children = false; ?>
    <?php foreach ($disease_specialties as $specialty):?>
    	<?php if ($specialty->is_children) { $is_children = true; break; } ?>
    <?php endforeach;?>

    <?php /* Баннер для педиаторов ?>

        <?php if ($block->disease_block_type_id == 1 && $is_children) {?>
            <?php if (isset($show_pediatr_banner) && $show_pediatr_banner == 1) {?>
            <div class="item-row pediatr-banner-wrapper">
                <div class="pediatr-banner pediatr-banner-1">
                    <table>
                        <tr>
                            <td rowspan="2" class="td-big-pad">
                                <img src="/media/images/pediatr_image.png" />
                            </td>
                            <td class="title" valign="bottom" colspan="3">Беспокоитесь о  безопасности и здоровье вашего малыша?</td>
                        </tr>
                        <tr>
                            <td width="280px"><span>Получите консультацию <b>врачей<br/>педиатров</b> в любое время суток <b>24/7</b></span></td>
                            <td width="36px"><img src="/media/images/pediatr_arrow.png" /></td>
                            <td class="td-no-pad"><a href="https://pediatr247.ru/" target="_blank">Получить онлайн консультацию</a></td>
                        </tr>
                    </table>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                    $('.pediatr-banner-1 a').click(function(){
                        setNewCounters(<?php echo $counter_number;?>, 'DesPage/Page', 'AwayButton', 'Push');
                    });
                });
            </script>

            <?php } else if (isset($show_pediatr_banner) && $show_pediatr_banner == 0) {?>
            <div class="pediatr-banner pediatr-banner-2">
                <table>
                    <tr>
                        <td class="big-title" colspan="2" valign="bottom">Онлайн консультация с врачом<br/>в любое время суток 24/7</td>
                        <td class="td-img" valign="bottom">
                            <img src="/media/images/pediatr_image.png" />
                        </td>
                    </tr>
                    <tr><td class="title" colspan="3" height="60px">Беспокоитесь о безопасности и здоровье вашего малыша?</td></tr>
                    <tr>
                        <td valign="top"><span>Получите консультацию от <b>лучших<br/>врачей педиатров</b> прямо сейчас</span></td>
                        <td class="td-arrow" valign="top"><img src="/media/images/pediatr_arrow.png" /></td>
                        
                    </tr>
                </table>
            </div>
            <script>
                $(document).ready(function(){
                    $('.pediatr-banner-2 a').click(function(){
                        setNewCounters(<?php echo $counter_number;?>, 'DesPage/Page', 'AwayButton', 'Push');
                    });
                });
            </script>
            <?php } ?>
        <?php } ?>

    <?php */ ?>
<?php endforeach?>