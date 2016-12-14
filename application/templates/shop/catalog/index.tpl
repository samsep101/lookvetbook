<?php
    /**
     * @var View $this
     * @var ProductCategoryModel $product_category
     * @var ProductCategoryModel $parent_product_category
     * @var ProductCategoryModel $root_product_category
     * @var ProductCategoryModel[] $product_categories
     * @var ProductCategoryModel[] $parent_product_category_children
     * @var array $product_categories_ids
     * @var string $pattern
     * @var int $by_page
     * @var bool $is_leader
     * @var bool $show_total_count
     */
?>

<script type="text/javascript">
    $(document).ready(function() {
        var productSearchController = new ProductSearchController();
        productSearchController.product_category = <?php echo isset($product_category) ? $product_category->getId() : 0; ?>;
        productSearchController.is_leader = <?php echo isset($is_leader) ? $is_leader : 0; ?>;
        productSearchController.by_page = <?php echo isset($by_page) ? $by_page : 10; ?>;
        productSearchController.pattern = <?php echo isset($pattern) ? "'$pattern'" : "'*'"; ?>;
        productSearchController.product_itself = '';
        productSearchController.init();

        var carousel_controller = new CarouselController(1100);
        carousel_controller.init();
    });
</script>

<div class="magazine inner-2 flo">
    <?php $this->pattern = isset($pattern) ? $pattern : "'*'"; ?>
    <?php $this->block('shop/blocks/live_search'); ?>

    <?php if(isset($product_category) && $product_category): ?>
        <?php $this->product_category = $product_category; ?>
        <?php $this->parent_product_category = $parent_product_category; ?>
        <ul class="way_line m0">
        <?php $this->block('shop/blocks/breadcrumbs'); ?>
        </ul>
    <?php else:  ?>
        <?php if(!isset($pattern) || !$pattern): ?>
            <h2 style="color: #818080">Каталог лекарств</h2>
        <?php else: ?>
            <hr class="separator_h"/>
        <?php endif; ?>
    <?php endif; ?>
    <div class="block_left" style="width: auto; float: none;">
        <?php if(isset($product_category) && $product_category): ?>
            <?php if(isset($parent_product_category) || $product_categories): ?>
                <div class="bg_gradient catalog_more_h flo">
            <?php endif; ?>
            <h3>
                <?php if(isset($parent_product_category) && $parent_product_category): ?>
                    <a class="category" href="<?php echo ProductCategoryLinkViewHelper::getLink($parent_product_category); ?>">
                        <?php echo $parent_product_category->name; ?>
                    </a>
                <?php elseif((isset($product_categories) && $product_categories)): ?>
                    <a class="category" href="<?php echo ProductCategoryLinkViewHelper::getLink($product_category); ?>">
                        <?php echo $product_category->name; ?>
                    </a>
                <?php endif; ?>
                <?php if(!isset($parent_product_category) && isset($product_categories) && $product_categories): ?>
                    <a class="back" href="/shop/catalog">к списку лекарств</a>
                <?php endif; ?>
                <?php if(isset($parent_product_category) && $parent_product_category && $product_category): ?>
                    <a class="back" href="<?php echo ProductCategoryLinkViewHelper::getLink($parent_product_category); ?>">
                        к списку категорий
                    </a>
                <?php endif; ?>
            </h3>
            <?php if(!isset($parent_product_category) && isset($product_categories) && $product_categories): ?>
                <ul class="catalog_more_ul">
                    <?php foreach($product_categories as $item): ?>
                        <li>
                            <a href="<?php echo ProductCategoryLinkViewHelper::getLink($item); ?>">
                                <?php echo $item->name; ?> (<?php echo (isset($show_total_count) && $show_total_count) ? $item->products_total_count : $item->products_count; ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if(isset($parent_product_category) && $parent_product_category && $product_category): ?>
                <ul class="catalog_more_ul">
                    <?php foreach($parent_product_category_children as $item): ?>
                        <li <?php echo ($item->getId() == $product_category->getId()) ? 'class="active"' : ''; ?>>
                            <a href="<?php echo ProductCategoryLinkViewHelper::getLink($item); ?>">
                                <?php echo $item->name; ?> (<?php echo (isset($show_total_count) && $show_total_count) ? $item->products_total_count : $item->products_count; ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if(isset($parent_product_category) || $product_categories): ?>
                </div>
            <?php endif; ?>

        <?php if(isset($product_category) && $product_category && $product_category->id == 1434): ?>
            <style>
                .adv_text p, .adv_text h2{
                    display: none;
                }
                .adv_text div{
                    display: none;
                }
                .adv_text p.showme{
                    display: block;
                }
                .adv_text{
                    float: left;
                    width: 55%;
                    font-size: 16px;
                }
                .goods_with_adv{
                    float: right;
                    width: 40%;
                }

            </style>
            <div class="adv_text">
                <p class="showme">
                    <h1>Фосфоглив или Эссливер: сравнение</h1>
                </p>
                <p class="showme">
                    Профилактика и лечение заболеваний печени остается серьезной проблемой здравоохранения в России и за рубежом. По данным Европейского регионального бюро ВОЗ
                    из 43 стран региона уровень смертности от хронических заболеваний печени является высоким (более 25 случаев смерти на 100.000 населения) в семи странах СНГ
                    и пяти странах Центральной и Восточной Европы, составляя в среднем по Европейскому региону - 17. За последние 10 лет в пяти странах уровень смертности
                    увеличился более, чем на 50%.
                </p>
                <a onclick="$('.adv_text p, .adv_text div, .adv_text h2').show(); $(this).hide()">Читать далее</a>
                <p>
                    Чтобы выявить заболевания печени, необходимо своевременно пройти диагностику, так как первые стадии болезни часто проходят бессимптомно. Для этого
                    применяются такие методы исследования, как биопсия печени, компьютерная и магнитно-резонансная томография (КТ и МРТ), ультразвуковое исследование (УЗИ).
                </p>
                <p>
                    Рынок фармацевтических препаратов для печени настолько широк, что пациенты подчас путаются в предложенных медикаментах. Фосфоглив и Эссливер некоторое
                    время неоправданно считались аналогами. Но как воздействие на организм, так и результат от приема этих лекарств разный. Так что лучше? Фосфоглив или
                    Эссливер? Попробуем разобраться.
                </p>
                <p>
                    <h2>Различия в составе</h2>
                </p>
                <p>
                    Оба лекарственных средства относятся к группе гепатопротекторов — медикаментов, защищающих клетки печени. Фосфоглив или Эссливер назначают для лечения
                    воспалительных и вирусных заболеваний печени и для профилактики ее патологий.
                </p>
                <p>
                <div align="center"><img src="/media/images/banners/fosfogliv.png" width="500"></div>
                </p>
                <p>
                    Почему так важно понять, какой гепатопротектор подходит именно вам? Дело в том, что разница в составе, показаниях, противопоказаниях и фармакологических
                    свойствах указанных лекарств может сказаться на результате лечения.
                </p>
                <p>
                    Чтобы разобраться, что лучше — Фосфоглив или Эссливер, разберем их состав.
                </p>
                <p>
                    • В основе обоих лекарственных средств есть фосфолипиды, однако в Эссливере <u>содержится 29% фосфатидилхолина</u>,а в Фосфогливе 76%.
                </p>
                <p>
                    Фосфоглив дополнительно содержит глицирризиновую кислоту (ГК), а Эссливер — комплекс витаминов.
                </p>
                <p>
                    Сочетание фосфолипидов и ГК входит в стандарты оказания медицинской помощи. Наличие в составе этих двух веществ способствует тому, что лекарство лучше
                    усваивается.
                </p>
                <p>
                    Глицирризиновая кислота – крайне важный компонент. Она оказывает противовоспалительное, антиоксидантное и антифибротическое действие.
                </p>
                <p>
                    По сведениям государственного реестра медикаментов, Фосфоглив — единственный гепатопротектор, в который входит глицирризиновая кислота, таким образом,
                    можно сделать вывод, что аналогов на отечественном рынке Фосфогливу нетЭссливер, в свою очередь, помимо фосфолипидов, содержит витамины В1, В2, В6, В12 и
                    РР – эти биологически активные вещества призваны ускорять обмен веществ. Но стоит помнить, что длительный прием препарата может вызвать гипервитаминоз —
                    отравление витаминами. Передозировка витамина В6 провоцирует патологии сердечно-сосудистой и нервной систем, нарушает координацию движений. Поэтому, не
                    будет преувеличением ставить под сомнение эффективность и безопасность неконтролируемого приема препарата Эссливер.
                </p>
                <p>
                    Фосфоглив, в отличие от Эссливера, является гепатопротектором, обладающим доказанным противовоспалительным действием.
                </p>
                <p>
                    <h2>Различия в показаниях</h2>
                </p>
                <p>
                    Для профилактики заболеваний печени рекомендуют принимать две капсулы Фосфоглива трижды в день или те же дозы Эссливера. Курс приема обоих лекарственных
                    средств — до 3-6 месяцев (для взрослых).
                </p>
                <p>
                    Фосфоглив или Эссливер также можно принимать для лечения разрастания жировой, соединительной и рубцовой тканей в печени. Механизм предотвращения
                    патологического процесса у гепатопротекторов схожий из-за присутствия в составе обоих фосфолипидов, однако, стоит не забывать о противовоспалительном
                    воздействии Фосфоглива благодаря глицирризиновой кислоте, которой у Эссливер в составе, к сожалению, нет.
                </p>
                <p>
                    Оба лекарства применяют в составе комбинационного лечения псориаза. Для выведения продуктов распада опасных соединений после передозировки алкоголем или
                    лекарственными средствами назначают адсорбенты (активированный уголь) и гепатопротектор, например, Фосфоглив. Эссливер в данном случае не эффективен, зато
                    его используют при отравлении высокими дозами радиации.
                </p>
                <p>
                    При токсикозе беременных назначают Эссливер. Механизм воздействия Фосфоглива на здоровье женщины и плода до конца не изучен. Кроме того, витаминный
                    комплекс, входящий в состав Эссливера, позволяет использовать препарат для подготовки организма к хирургическим вмешательствам. Также это лекарственное
                    средство назначается и в период реабилитации после операций.
                </p>
                <p>
                    <h2>Особенности приема</h2>
                </p>
                <p>
                    Оба лекарственных средства имеют свои противопоказания и особенности приема, когда терапия должна проходить под контролем врача. Фосфоглив или Эссливер
                    принимают с осторожностью при склонности к аллергии и непереносимости фосфолипидов (антифосфолипидного синдрома).
                </p>
                <p>
                    Фосфоглив не используется для лечения детей до 12 лет, беременных и кормящих женщин. Это связано с недостатком сведений об эффективности и безопасности
                    действия медикамента на эти группы пациентов.
                </p>
                <p>
                    Эссливер с осторожностью следует принимать пациентам с заболеваниями сердца и сосудов, почек, язвой желудка и двенадцатиперстной кишки, новообразованиями,
                    почечнокаменной болезнью, склонностью к чрезмерному сворачиванию крови. Не стоит использовать препарат людям с повышенным содержанием мочевой кислоты и
                    эритроцитов (красных кровяных клеток). Относительным противопоказанием является эритремия — патология, при которой нарушено образование форменных элементов
                    крови.
                </p>
                <p>
                    Из-за этих особенностей круг пациентов, которым показан Эссливер без опасения побочных эффектов, значительно сужается. Не стоит использовать препарат, если
                    вы не уверены в собственном диагнозе.
                </p>
                <p>
                    Так что лучше — Фосфоглив или Эссливер? Несомненным преимуществом Фосфоглива является его уникальная формула, которая позволяет ему лучше усваиваться, а
                    также не только восстанавливать печень, но и лечить ее. Эссливер, сочетая в себе фосфолипиды и витаминный комплекс, ставит свою эффективность под сомнение
                    ввиду того, что витамины при неконтролируемом приеме могут сыграть с человеком злую шутку.
                </p>
                <p>
                    В любом случае, подсказать, что лучше именно для вас, сможет только лечащий врач на основании анамнеза, симптомов и результатов диагностики. Будьте
                    аккуратны при выборе медикамента и принимайте лекарства только в соответствии с инструкцией!
                </p>
            </div>
        <div class="goods_with_adv">
            <ul class="goods_blocks">
                <li class="bg_gradient buy product-card product-card-20906" data-id="20906">
                    <a href="/shop/product/fosfogliv-forte-kapsuly-30065-mg-50-sht">
                        <img src="/media/upload/product/130x130-resize-1391592395-Nf8n6y4Bit.jpg">
                    </a>
                    <p class="good_name"><a href="/shop/product/fosfogliv-forte-kapsuly-30065-mg-50-sht">Фосфоглив форте капсулы 300+65 мг, 50 шт.</a>
                        Фармстандарт                    </p>
                </li>
                <li class="bg_gradient buy product-card product-card-19042" data-id="19042">
                    <a href="/shop/product/essliver-forte-kapsuly-50-sht">
                        <img src="/media/upload/product/130x130-resize-1390808051-s3BBHH64HB.jpg">
                    </a>
                    <p class="good_name"><a href="/shop/product/essliver-forte-kapsuly-50-sht">Эссливер форте капсулы, 50 шт.</a>
                        Наброс Фарма                    </p>
                </li>
            </ul>
        </div>
        <br clear="all">
        <?php endif; ?>


        <?php if(isset($product_category) && $product_category && $product_category->id == 1943): ?>
        <style>
            .adv_text p, .adv_text h2{
                display: none;
            }
            .adv_text div{
                display: none;
            }
            .adv_text p.showme{
                display: block;
            }
            .adv_text{
                float: left;
                width: 55%;
                font-size: 16px;
            }
            .goods_with_adv{
                float: right;
                width: 40%;
            }

        </style>
        <div class="adv_text">
            <p class="showme">
            <h1>Диваза или Ноопепт?</h1>
            </p>
            <p class="showme">
                Почему возраст после 50 многих пугает? Казалось бы, самое время пожить «для себя».
                Но особенно для женщин это время, когда день рождения не радует, а, скорее, вводит в тоску.
                Как выяснили американские ученые, каждый шестой житель Земли предпочел бы умереть в молодости,
                чем наблюдать свое старение. Мужчины и женщины по-разному относятся к возрастным изменениям,
                и если женщин больше заботит отражение в зеркале, то мужчины переживают за свои интеллектуальные
                возможности и познавательные (когнитивные) функции, важнейшая из которых - память.
            </p>
            <a onclick="$('.adv_text p, .adv_text div, .adv_text h2').show(); $(this).hide()">Читать далее</a>
            <p>
                Наша память складывается из трех этапов: запоминание, хранение и воспроизведение информации.
                То, насколько хорошо выполняется каждый из этапов, зависит от активности процесса обмена веществ в нервных клетках нашего мозга. Память с годами становится хуже у всех людей без исключения - для этого недуга не нужны особенные условия,
                ведь старение сложный процесс.
                С годами многие витамины перестают усваивается также хорошо, как в молодости.
            </p>
            <p>
                Например, важнейший для памяти витамин В12. А негативные факторы, такие, как стресс, вредные привычки, хроническая усталость, или болезни усугубляют состояние памяти.
                Существует заблуждение, что если тренировать память, то можно избежать ее ухудшений. Чтение книг и прогулки на свежем воздухе, безусловно, полезные занятия, но их эффекта не хватает, чтобы в должной мере поддерживать когнитивные функции.
                Как и любая другая болезнь, нарушение памяти и внимания требуют лечения.
            </p>
            <p>
            <h2>Как действует Диваза или Ноопепт.</h2>
            </p>
            <p>
                Помочь своей памяти можно ноотропными препаратами. К группе таких препаратов относится лекарственный препарат Ноопепт и гомеопатическое средство ДивазаЭти препараты можно приобрести в аптеке без рецепта. Мы рассмотрим оба препарата с точки зрения эффективности, безопасности и цены.
                Ноопепт - инновационный ноотроп с пептидным строением, выполняет две функции: улучшает память, воздействуя на три ее функции, и питает клетки мозга, защищая их от неблагоприятных воздействий и делая их устойчивыми к травмам и токсическим воздействиям.
                Препарат рекомендован пациентам с нарушениями памяти и внимания, в том числе из-за черепно-мозговой травмы.
                Диваза - это вазоактивное лекарство, предназначенное для лечения хронических нарушений кровообращения мозга. Препарат Диваза – гомеопатический, компоненты содержатся в препарате в сверхмалых дозах,. Его активные вещества разведены в очень малых дозах: в 100 в 12 степени, в 100 в 30 степени и даже в 100 в 200 степени.

            </p>
            <p>
            <div align="center"><img src="/media/images/banners/noopept.jpg" width="300"></div>
            </p>
            <h2>Вызывают ли зависимость и сонливость Диваза или Ноопепт.</h2>
            </p>
            <p>
                Пациенты не хотят менять свой обычный распорядок дня, даже когда лечатся. Поэтому вопрос о зависимости и сонливости интересует всех.
                Оба препарата не вызывают зависимость, поэтому без опасений можно отменить прием лекарства в один день. Вам не придется убирать ключи от автомобиля в далекий ящик, если вы решили улучшить свою память с помощью этих ноотропов – они не вызывают и сонливости.
            </p>
            <p>
            <h2>Кому нельзя принимать препарат Диваза или Ноопепт.</h2>
            </p>
            <p>
                Оба препарата не рекомендуют людям с повышенной чувствительностью к компонентам лекарств, и детям до 18 лет.
                Также не рекомендуется использовать препараты беременным и кормящим женщинам,
                так как его действие недостаточно исследовано на этой категории пациентов.
            </p>
            <p>
            <h2>Способы приема препаратов для памяти.</h2>
            </p>
            <p>
                Прием препарата Ноопепт нужно принимать по 20 мг. в день (по 10 мг. утром и до 18:00, сдвигать прием Ноопепта на поздний вечер не рекомендуют).
                При наличии показаний можно повысить дозу Ноопепта до 30 мг. в день. Курс лечения составляет от полутора до трех месяцев.
                Перерыв между курсами должен составлять один месяц. Эффект улучшания памяти можно отметить уже на второй неделе приема.
                Ноотроп Диваза рекомендован к приему по 1-2 таблетки 3 раза в сутки не во время приема пищи.
                Таблетки необходимо рассасывать. Доза может быть увеличена до 4-6 раз в сутки при тяжелом состоянии.
                В инструкции препарата не указано, сколько составляет курс, поэтому мы не можем даже предполагать, к какому сроку при приеме лекарства можно достичь максимального эффекта.
            </p>
            <div align="center"><img src="/media/images/banners/divaza.jpg" width="300"></div>
            <p>
            <h2>Цена - качество.</h2>
            </p>
            <p>
                Стоимость лекарства Диваза - 302 рубля за упаковку с 20 таблетками.
                Ноопепт обойдется в 320 рублей за упаковку с 50 таблетками по 10 мг.
                Несмотря на сверхмалые дозы активных веществ в препарате,
                Диваза дороже, так как одной упаковки 20 таблеток хватит в среднем всего на 3-4 дня приема.
                Для одного курса лечения препаратом Ноопепт достаточно двух упаковок.
            </p>
            <p>
            <h2>Память - это жизнь.</h2>
            </p>
            <p>
                Не будет преувеличением сказать, что память - это жизнь.
                Как только человек становится рассеянным и забывчивым, вместе с частичкой памяти он теряет целый океан возможностей.
                Заботясь о своей памяти, люди проявляют внимание к своим близким, потому что для каждого человека рассеянный родственник - это постоянные переживания и тяжкое бремя.
                Сегодня поддерживать свою память легко благодаря ноотропным препаратам. Что лучше: Диваза и Ноопепт - выбирать вам.
                В данном вопросе важно лишь определиться, на что вы полагаетесь: использование инновационных лекарств или веру в гомеопатию.
            </p>
        </div>
        <div class="goods_with_adv">
            <ul class="goods_blocks">
                <li class="bg_gradient buy product-card product-card-20906" data-id="20906">
                    <a href="/shop/product/noopept-tabletki-10-mg-50-sht">
                        <img src="/media/upload/product/130x130-resize-1390805922-fi9FNTDdSi.jpg">
                    </a>
                    <p class="good_name"><a href="/shop/product/fosfogliv-forte-kapsuly-30065-mg-50-sht">Ноопепт таблетки 10 мг, 50 шт.</a>
                        Валента                    </p>
                </li>
                <li class="bg_gradient buy product-card product-card-divaza">
                    <a>
                    <img align="center" src="/media/images/banners/divaza.jpg" width="130" height="130">
                    </a>
                    <p class="good_name"><a>Диваза таблетки, 100 шт.</a>
                        НПФ Материа Медика Холдинг                    </p>
                </li>
            </ul>
        </div>
        <br clear="all">
        <?php endif; ?>

            <?php if(isset($product_category) && $product_category): ?>
                    <h4>
                        <a class="category" href="<?php echo ProductCategoryLinkViewHelper::getLink($product_category); ?>">
                            <?php echo $product_category->name; ?>
                        </a>
                        <?php if(isset($product_category) && $product_category && !$product_categories && !$parent_product_category): ?>
                            <a class="back" href="/shop/catalog">к списку лекарств</a>
                        <?php endif; ?>
                    </h4>
                <?php endif; ?>

                <a class="load-next-page view-more" href="javascript:void(0);" data-page="1"><i class="icon-loader"></i></a>
                <?php if ($product_category->description): ?>
                    <div class="bg_gradient about_good">
                        <p class="h-txt"><?php echo $product_category->name; ?></p>
                        <p class="txt"><?php echo $product_category->description; ?></p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="bg_gradient catalog_list_container">
                    <?php if(!isset($pattern) && isset($root_product_category) && $root_product_category): ?>
                        <?php $this->product_category = $root_product_category; ?>
                        <?php $this->product_categories = $product_categories; ?>
                        <?php $this->block('shop/catalog/product_categories'); ?>
                    <?php else: ?>
                        <ul class="catalog_list" style="height: 30px">
                            <li class="search_pattern"><?php echo 'Результаты поиска по запросу ' .'"' .$pattern .'"'; ?></li>
                            <h3><a class="back" href="/shop/catalog">вернуться в каталог</a></h3>
                        </ul>
                    <?php endif; ?>
                </div>

                <?php if(!isset($pattern)): ?>
                    <h2 style="color: #818080">Популярные лекарства</h2>
                <?php endif; ?>
                <a class="load-next-page view-more" href="javascript:void(0);" data-page="1"><i class="icon-loader"></i></a>
            <?php endif; ?>
    </div>

    <?php /*
        <div class="block_right">
            <?php $this->block('shop/blocks/basket_info'); ?>
            <?php $this->block('shop/blocks/orders_phone'); ?>
            <?php $this->block('shop/blocks/orders'); ?>
            <?php $this->block('shop/blocks/payments'); ?>
            <?php $this->block('shop/blocks/choice'); ?>
        </div>
    */ ?>
    <?php /*
        <?php if(!isset($product_category)): ?>
            <div class="product-license-block">
                <p class="all-elements">
                    <a class="show-all" href="javascript:void(0)">Лицензия и реквизиты</a>
                </p>
                <div class="product-license-container">
                    <div class="line"></div>
                    <p class="license-text">
                        <span class="bigger">ООО «Аптечный сервис»</span><br/><br/>
                        Р/с 40702810800760003208 в ОАО "Московский кредитный банк" г. Москва<br/>
                        к/с 30101810300000000659,<br/>
                        БИК 044585659<br/>
                        ИНН/КПП:  7725719998 / 771401001<br/>
                        Юридический адрес: 127137, г.Москва, ул. Правды, д.24, стр.5<br/><br/>
                        Лицензия: № ФС-99-02-003264 от 15.08.2013 г.
                    </p>

                    <div class="connected-carousels">
                        <div class="stage">
                            <div class="carousel carousel-stage">
                                <ul>
                                    <?php $i = 1; ?>
                                    <?php while($i < 5): ?>
                                        <li>
                                            <a data-fancybox-type="iframe" href="/shop/catalog/getLicenseImages">
                                                <img src="/media/images/license_<?php echo $i .'.jpg'; ?>" alt="" style="width: 110px;"/>
                                            </a>
                                        </li>
                                        <?php $i++; ?>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
            </div>
        <?php endif; ?>
    */ ?>
</div>

<script>
    $(function() {
        $('[data-jcarousel]').each(function() {
            var el = $(this);
            el.jcarousel(el.data());
        });

        $('[data-jcarousel-control]').each(function() {
            var el = $(this);
            el.jcarouselControl(el.data());
        });

        $(".main-cont .schedule-extended ul").each(function(e){
            $(this).parent('.schedule-extended').addClass('schedule-extended-'+e);
            $(this).nextAll('a').addClass('nav-'+e);
            $('.main-cont .location-box .tabs li').on('click', function(){
                $('.main-cont .schedule-extended-' + e + ' ul').carouFredSel({
                    auto: false,
                    prev: '.main-cont .prev-nav.nav-'+ e,
                    next: '.main-cont .next-nav.nav-'+ e,
                    scroll:{items:1},
                    circular: false,
                    infinite:false
                });
            });
            $('.main-cont .location-box .tabs li:first-child').trigger('click');
        });
    });

    $(".connected-carousels .carousel-stage li a").fancybox({
        maxWidth	: 660,
        maxHeight	: 1100,
        fitToView	: false,
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none'
    });
</script>