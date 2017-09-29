<div id="doctor-search-form">
    <div class="search-form">
        <div class="box flo">
            <?php if (!isset($show_title) || $show_title): ?>
                <h2 id="box_h1">Найти врача</h2>
            <?php endif; ?>

            <div class="colapse"> </div>
            <div class="in_colapse">
                <div class="sel-box doctor-box" style="height:auto">
                    <select id="specialties_to_search_doctor" data-placeholder="Специальность врача" class="chzn-select" name="specialty_id" style="width:390px;">                        
                        <?php
                        $this->show_all_option = 0;
                        $this->block('blocks/specialties_options');
                        ?>
                    </select>
                    <span class="label">Кто у вас:</span>
                    <select id="pettype_to_search_doctor" data-placeholder="Животное" class="chzn-select" name="pettype_id" style="width:390px;">
                        <?php if (count($pettypes)):?>
                            <?php foreach($pettypes as $pettype): ?>
                                <option <?php if ( (isset($current_pettype) && $current_pettype->id == $pettype->getId()) ) echo 'selected="selected"'; ?>
                                value="<?php echo $pettype->getId(); ?>">

                                <?php echo StringHelper::startProposalWord($pettype->name); ?>

                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class="label">Виды услуг:</span>
                    <select id="petservice_to_search_doctor" data-placeholder="Животное" class="chzn-select" name="petservice_id" style="width:390px;">
                        <?php if (count($petservices)):?>
                        <?php foreach($petservices as $petservice): ?>
                            <option <?php if ( (isset($current_petservice) && $current_petservice->id == $petservice->getId()) ) echo 'selected="selected"'; ?>
                            value="<?php echo $petservice->getId(); ?>">

                            <?php echo StringHelper::startProposalWord($petservice->name); ?>

                            </option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                </div>


                <div class="find-txt" id="doctor-find-txt"> Искать по
                    <a href="javascript:void(0)">имени</a> или <a href="javascript:void(0)">полу</a>.
                </div>

                <div class="choose-section-2 flo doctor_search_options" style="display: none">
                    <div class="fields">
                        <div class="search-by-name flo">
                            <input type="text" class="txt" name="doctor_name" placeholder="Введите имя врача"/>
                            <input type="submit" class="search-btn" value=""/>
                            <a class="show_input" href="javascript:void(0)">Искать врача по имени</a>
                        </div>
                    </div>
                    <div class="btn-box flo">
                        <input class="btn-1 btn-doctor" type="submit" value="Найти врача" data-category-for-counters="find-doctor" data-action-for-counters="find-doctor"/>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
