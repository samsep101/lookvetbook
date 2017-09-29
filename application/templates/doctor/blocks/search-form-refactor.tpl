<div id="doctor-search-form" class="refactor">
    <div class="search-form">
        <div class="box flo">
            <?php if (!isset($show_title) || $show_title): ?>
            <h2 id="box_h1">Найти врача</h2>
            <?php endif; ?>

            <div class="colapse"> </div>
            <div class="in_colapse">
                <div class="sel-box doctor-box">
                    <select id="specialties_to_search_doctor" data-placeholder="Специальность врача" class="chzn-select" name="specialty_id" style="width:100%;">
                        <?php $this->show_all_option = FALSE; ?>
                        <?php $this->block('blocks/specialties_options'); ?>
                    </select>
                </div>
                <div class="sel-box doctor-box">
                <span class="label">Кто у вас:</span>
                <select id="pettype_to_search_doctor" data-placeholder="Животное" class="chzn-select" name="pettype_id" style="width:100%;">
                    <?php if (count($pettypes)):?>
                    <?php foreach($pettypes as $pettype): ?>
                    <option <?php if ( (isset($current_pettype) && $current_pettype->id == $pettype->getId()) ) echo 'selected="selected"'; ?>
                    value="<?php echo $pettype->getId(); ?>">

                    <?php echo StringHelper::startProposalWord($pettype->name); ?>

                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                </div>
                <div class="sel-box doctor-box">
                <span class="label">Виды услуг:</span>
                <select id="petservice_to_search_doctor" data-placeholder="Животное" class="chzn-select" name="petservice_id" style="width:100%;">
                    <?php if (count($petservices)):?>
                    <?php foreach($petservices as $petservice): ?>
                    <option <?php if ( (isset($current_petservice) && $current_petservice->getId() == $petservice->getId()) ) echo 'selected="selected"'; ?>
                    value="<?php echo $petservice->getId(); ?>">

                    <?php echo StringHelper::startProposalWord($petservice->name); ?>

                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                </div>

                
                <div class="choose-section-refactor flo">
                    <div class="col">
                        <ul class="choose-list like-head-label">
                            <li>
                                <div class="chekBox visit-type-home"><span></span>Врач на дом<input type="hidden">
                                </div>
                            </li>
                            <li>
                                <div class="chekBox time-weekend"><span></span>Прием в выходные дни<input type="hidden">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lmb">
                    <p class="pad_tb">
                        <a class="a_dashed show_inp" href="javascript:void(0)">Искать врача по имени</a>
                        <input class="search_txt" type="text" placeholder="Введите имя врача" name="doctor_name">
                    </p>
                    <input class="btn_red btn-doctor" type="submit" value="Найти врача" data-category-for-counters="find-doctor" data-action-for-counters="find-doctor">
                </div>
            </div>
        </div>
    </div>
</div>