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
                <div class="choose-section flo">
                    <div class="col colleft">
                        <label class="head-label">Врач для:</label>
                        <ul class="choose-list">
                            <li>
                                <div class="radioBox doctor-type doctor-type-adult act"><span></span> Взрослых
                                    <input type="hidden" value="1">
                                </div>
                            </li>
                            <li>
                                <div class="radioBox doctor-type doctor-type-children"><span></span> Детей
                                    <input type="hidden">
                                </div>
                            </li>
                        </ul>
                        <br>

                    </div>

                    <div class="col colright">
                        <div class="gender"><span class="legend">Пол врача</span>
                            <input type="hidden"/>
                            <span class="man sex-1"></span>
                            <input type="hidden"/>
                            <span class="woman sex-2"></span></div>
                    </div>
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