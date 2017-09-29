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
                            <a class="btn-double-floor des-page disease-doctor
	<?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?>
	<?php if ($specialty->is_male){?>male-block <?php }?>
    <?php if ($specialty->is_female){?>female-block <?php }?>
    <?php if ($specialty->is_children){?>children-block <?php }?>
    <?php if ($specialty->is_newborn){?>newborn-block <?php }?>
    <?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>"

                               data-action-for-counters="find-doctor"
                               data-category-counters="find-doctor"
                               data-action="FindDocButton"
                               data-position="Right"
                               data-url="<?php echo $specialty->specialtyUrl ?>" data-id="<?php echo $specialty->specialty_id; ?>"
                               onclick="recordController.showForm(0,0,<?=$disease->id?>)"
                            >
                                <?php $btn_text = (!$disease_green_btn)?'Записаться к '.$specialty->dative_name:'Найти '.$specialty->genitive_name?>
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
                            <a class="btn-double-floor des-page disease-doctor
	<?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?>
	<?php if ($specialty->is_male){?>male-block <?php }?>
    <?php if ($specialty->is_female){?>female-block <?php }?>
    <?php if ($specialty->is_children){?>children-block <?php }?>
    <?php if ($specialty->is_newborn){?>newborn-block <?php }?>
    <?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>"

                               data-action-for-counters="find-doctor"
                               data-category-counters="find-doctor"
                               data-action="FindDocButton"
                               data-position="Right"
                               data-url="<?php echo $specialty->specialtyUrl ?>" data-id="<?php echo $specialty->specialty_id; ?>"
                               onclick="recordController.showForm(0,0,<?=$disease->id?>)"
                            >
                                <?php $btn_text = (!$disease_green_btn)?'Записаться к '.$specialty->dative_name:'Найти '.$specialty->genitive_name?>
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