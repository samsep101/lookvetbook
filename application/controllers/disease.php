<?php
    class DiseaseController extends BaseController
    {
        public function get($disease_id = null)
        {
            if(!$disease_id)
            {
                $disease_id = $this->request('id');
            }
            $card = $this->request('card');

            $diseaseManager = ModelManagerFactory::getByName('disease');
            $disease = $diseaseManager->getOneByIdOrAlias($disease_id);

            $keepTrackLinks = array(
                '/disease/aktinomikoz/adult',
                '/disease/alimentarnaya-lihoradka/children',
                '/disease/alimentarnaya-lihoradka/newborn',
                '/disease/allergicheskiy-dermatit/adult',
                '/disease/allergicheskiy-dermatit/children',
                '/disease/allergicheskiy-dermatit/newborn',
                '/disease/allergicheskiy-dermatit/pregnant',
                '/disease/aspiraciya',
                '/disease/blennoreya',
                '/disease/bolezn-kavasaki',
                '/disease/dvurogaya-matka',
                '/disease/dvurogaya-matka/children',
                '/disease/endometrit',
                '/disease/endometrit/pregnant',
                '/disease/gelmintoz-parazitarnye-invazii/children',
                '/disease/gelmintoz-parazitarnye-invazii/male',
                '/disease/gelmintoz-parazitarnye-invazii/pregnant',
                '/disease/gepatoz/female',
                '/disease/gonoblennoreya',
                '/disease/hlamidiynaya-infekciya/pregnant',
                '/disease/hronicheskiy-yuvenilnyy-artrit/adult',
                '/disease/hronicheskiy-yuvenilnyy-artrit/children',
                '/disease/hronicheskiy-yuvenilnyy-artrit',
                '/disease/hronicheskiy-yuvenilnyy-artrit',
                '/disease/istinnaya-eroziya-sheyki-matki/pregnant',
                '/disease/opuschenie-matki',
                '/disease/ostryy-bronhit',
                '/disease/ostryy-gastroenterit',
                '/disease/ostryy-gastroenterit/adult',
                '/disease/ostryy-gastroenterit/children',
                '/disease/ostryy-gastroenterit/pregnant',
                '/disease/piodermii-stafilokokkovye',
                '/disease/piodermii-stafilokokkovye/children',
                '/disease/piodermii-stafilokokkovye/female',
                '/disease/piodermii-stafilokokkovye/male',
                '/disease/piodermii-stafilokokkovye/pregnant',
                '/disease/polip-placentarnyy',
                '/disease/polip-placentarnyy/female',
                '/disease/rozovyy-lishay',
                '/disease/seboreynaya-ekzema/newborn',
                '/disease/toksicheskaya-eritema-novorozhdennyh/children',
                '/disease/trahoma/newborn',
                '/disease/vyvih-patologicheskiy',
                '/disease/vyvih-patologicheskiy/children',
                '/disease/zhenskoe-besplodie/female'
            );

            $redirectList = array(
                '/disease/aktinomikoz/male',
                '/disease/lihoradka-gemorragicheskaya-lassa/children',
                '/disease/lihoradka-gemorragicheskaya-lassa/children',
                '/disease/allergicheskiy-kontaktnyy-dermatit/adult',
                '/disease/allergicheskiy-kontaktnyy-dermatit/children',
                '/disease/allergicheskiy-kontaktnyy-dermatit/children',
                '/disease/allergicheskiy-kontaktnyy-dermatit/pregnant',
                '/disease/aspiraciya-inorodnyh-tel',
                '/disease/aktinomikoz',
                '/disease/sindrom-kavasaki',
                '/disease/dvurogaya-matka0',
                '/disease/dvurogaya-matka0/children',
                '/disease/poslerodovoy-endometrit',
                '/disease/poslerodovoy-endometrit/pregnant',
                '/disease/gelmintoz',
                '/disease/gelmintoz/adult',
                '/disease/gelmintoz/pregnant',
                '/disease/gepatoz/pregnant',
                '/disease/aktinomikoz',
                '/disease/hlamidioz/pregnant',
                '/disease/hlamidioz/male',
                '/disease/hlamidioz/female',
                '/disease/yuvenilnyy-revmatoidnyy-artrit',
                '/disease/yuvenilnyy-revmatoidnyy-artrit/children',
                '/disease/yuvenilnyy-revmatoidnyy-artrit/children',
                '/disease/opuschenie-tazovyh-organov',
                '/disease/bronhit-ostryy-hronicheskiy',
                '/disease/gastroenterit-ostryy',
                '/disease/gastroenterit-ostryy/adult',
                '/disease/gastroenterit-ostryy/children',
                '/disease/gastroenterit-ostryy/pregnant',
                '/disease/stafilokokkovye-piodermii',
                '/disease/stafilokokkovye-piodermii/children',
                '/disease/stafilokokkovye-piodermii/female',
                '/disease/stafilokokkovye-piodermii/male',
                '/disease/stafilokokkovye-piodermii/pregnant',
                '/disease/polip-placentarnyy0',
                '/disease/polip-placentarnyy0/female',
                '/disease/rozovyy-lishay-zhibera',
                '/disease/seboreynaya-ekzema/children',
                '/disease/toksicheskaya-eritema-novorozhdennyh/newborn',
                '/disease/trahoma/children',
                '/disease/vyvih-bedra-vrozhdennyy',
                '/disease/vyvih-bedra-vrozhdennyy/children',
                '/disease/besplodie-zhenskoe',
            );

	          $redirectList = array_map(function($line) { return SITE_URL.$line; }, $redirectList);

            if(in_array($_SERVER['REQUEST_URI'], $keepTrackLinks)) {
                $link = '';
                foreach($keepTrackLinks AS $ktlKey => $ktlValue) {
                    if($_SERVER['REQUEST_URI'] == $ktlValue) {
                        $link = $redirectList[$ktlKey];
                    }
                }

                if($link) {
                    RedirectManager::redirect301($link);
                }
            }



            if (!$disease || !$disease->is_active) {
                ErrorPageViewHelper::page404('404');
                exit();
            }
            $disease_block_manager = new DiseaseBlockManager();
            $disease_tabs_flags = $disease_block_manager->getActiveDiseaseTabsFlagsByDiseaseId($disease->getId());

            /* Получение случайных заболеваний относящихся к полученным специальностям по id болезни */
            $similarDisease = $diseaseManager->getSimilarDisease($disease_id);
            $this->view->similarDisease = $similarDisease;
            DiseasePageLinkViewHelper::getLink($disease);

            // выбор первой активной вкладки
            if (!$card)
            {
                foreach($disease_tabs_flags as $key => $v)
                {
                    if ($v)
                    {
                        $card = $key;
                        break;
                    }
                }
            }

            $this->view->card = $card;

            if (!$this->view->is_test && (SITE_URL . $_SERVER['REQUEST_URI'] != DiseasePageLinkViewHelper::getLink($disease))){
                if (!in_array($card, array('male', 'female', 'children','pregnant','adult','newborn')))
                ErrorPageViewHelper::page404('404');
            }
			if (is_numeric($disease_id) && $disease->alias)
			{
				RedirectManager::redirect301(DiseasePageLinkViewHelper::getLink($disease));
			}

            $this->view->disease = $disease;

            $account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
            $this->view->account = $account;

            $disease_blocks = $disease_block_manager->getActiveListByDiseaseId($disease->getId());
            $disease_blocks_content = $disease_block_manager->getActiveListByDiseaseIdAndFlag($disease->getId(),$card);
            $this->view->disease_blocks = $disease_blocks;
            $this->view->disease_blocks_content = $disease_blocks_content;
            $this->view->disease_tabs_flags = $disease_tabs_flags;
            $disease_specialties = ModelManagerFactory::getByName('specialty')->getMainListByDiseaseId($disease->getId());

            foreach($disease_specialties AS $dsKey => $dsValue) {
                $disease_specialties[$dsKey]->specialtyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/doctor/' . $dsValue->alias;
            }

            $this->view->disease_specialties = $disease_specialties;
            /*
                Шаблон заголовка страницы ($pageTitleTemplate)
                Использует три параметра:
                <название болезни> - $diseaseName
                <название болезни> - $diseaseName
                <перечисление по типу> - $diseaseTypes

                Старый шаблон: $this->view->page_title = $disease->title.' - «'.SITE_NAME.''.SITE_NAME.'»'
            */

            $pageTitleTemplate = '%s симптомы, причины, диагностика, лечение. %s у %s ';
            $diseaseName = !empty($disease->title) ? '' : $disease->title;
            $diseaseTypes = array();

            foreach($disease_tabs_flags AS $dtfKey => $dtfValue)
            {
                if($dtfValue)
                {
                    $diseaseTypes[] = mb_convert_case(DiseaseTabNameViewHelper::getNameByTabFlag($dtfKey, 1), MB_CASE_LOWER, "UTF-8");
                }
            }
            $this->view->page_title = sprintf($pageTitleTemplate, $diseaseName, $diseaseName, implode(', ', $diseaseTypes));
            $this->view->label_for_counters = 'disease-page';

            $this->view->page_description = $disease->description;
            $this->view->canonical_link = DiseasePageLinkViewHelper::getLink($disease);
            $this->view->site_url_not_using = 1;

            $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
            $v_param = $this->request('v');
            $this->view->show_pediatr_banner = ($v_param && $v_param == 'child')?1:0;
            $dis_param = $this->request('dis');
            $this->view->disease_green_btn = ($dis_param && $dis_param == 'new3')?1:0;
            
            $this->render('disease/get');
        }

        public function ajaxGetDiseaseCardContent()
        {
            $this->layout = 'ajax';

            $disease_id = $this->request('disease_id');
            $card = $this->request('disease_card');

            $disease_manager = new DiseaseManager();
            $disease = $disease_manager->getOneById($disease_id);
            $this->view->disease = $disease;

            $disease_block_manager = new DiseaseBlockManager();
            $disease_blocks_content = $disease_block_manager->getActiveListByDiseaseIdAndFlag($disease_id, $card);
            $this->view->disease_blocks_content = $disease_blocks_content;
            $this->view->card = $card;

            $disease_specialties = ModelManagerFactory::getByName('specialty')->getMainListByDiseaseId($disease->getId());

            foreach($disease_specialties AS $dsKey => $dsValue) $disease_specialties[$dsKey]->specialtyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/doctor/' . $dsValue->alias;

            $this->view->disease_specialties = $disease_specialties;
            
            $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
            $v_param = $this->request('v');
            $this->view->show_pediatr_banner = ($v_param && $v_param == 'child')?1:0;
            $dis_param = $this->request('dis');
            $this->view->disease_green_btn = ($dis_param && $dis_param == 'new3')?1:0;
            
            $this->render('disease/blocks/disease_blocks_content');

            exit();
        }

        public function ajaxAddToMyDiseaseList()
        {
            if (!Acc::isAuthed()) $this->redirectUrl('/');

            $disease_id = $this->request->post('disease_id');
            $account_id = Acc::accountId();
            $date = date('Y-m-d H:i:s');

            if (!ModelManagerFactory::getByName('my_disease')->checkExistsByDiseaseIdAndAccountId($disease_id, $account_id)) {
                $my_disease = new MyDiseaseModel();
                $my_disease->account_id = $account_id;
                $my_disease->disease_id = $disease_id;
                $my_disease->dt = $date;
                $my_disease->is_archive = 0;
                if (ModelManagerFactory::getByName('my_disease')->save($my_disease)) {
                    JsonResponse::result(array('my_disease' => TRUE));
                } else {
                    JsonResponse::error(2);
                }
            } else {
                $my_disease = ModelManagerFactory::getByName('my_disease')->getOneByDiseaseIdAndAccountId($disease_id, $account_id);
                ModelManagerFactory::getByName('my_disease')->delete($my_disease);
                JsonResponse::result(array('my_disease' => FALSE));
            }
        }

        public function ajaxAddUnderstandOpinion()
        {
            $disease_id = $this->request->post('disease_id');
            $opinion = $this->request->post('opinion');
            if (Acc::isAuthed()) {
                $account_id = Acc::accountId();
                $check_understand = ModelManagerFactory::getByName('disease_understand')->checkExistsByDiseaseIdAndAccountId($disease_id, $account_id);
            } else {
                $account_id = 0;
                if (isset($_COOKIE['understand_' . $disease_id]) && $_COOKIE['understand_' . $disease_id] == 'done')
                    $check_understand = TRUE;
                else {
                    setcookie("understand_" . $disease_id, "done");
                    $check_understand = FALSE;
                }
            }

            if (!$check_understand) {
                $disease_understand = new DiseaseUnderstandModel();
                $disease_understand->account_id = $account_id;
                $disease_understand->disease_id = $disease_id;
                $disease_understand->understand_flag = $opinion;
                if (ModelManagerFactory::getByName('disease_understand')->save($disease_understand)) {
                    $understand_string = 'Голос учтен';
                    JsonResponse::result(array('understand_text' => $understand_string));
                }
            }
        }

        public function search()
        {
            if ($_SERVER['REQUEST_URI'] == '/disease/search'){
                ErrorPageViewHelper::page404('404');
            }
            $disease_manager = new DiseaseManager();
            $diseases = $disease_manager->getActiveListByPage(false, false);

            $this->view->diseases = $diseases;
            $this->view->menu_active = 'disease';

            $this->view->page_title = 'Найти заболевание - «'.SITE_NAME.'»';
            $this->view->page_description = 'Найти заболевание - вся информация обо всех известных заболеваниях на сервисе '.SITE_NAME.'';

            $this->view->label_for_counters = 'disease-search';
        }

        public function searchResults()
        {
            $disease_query = $this->request('disease_query');
            $by_page = $this->request('by_page', 10);
            $page = 1;

			$disease_query = trim($disease_query);
            $this->view->query = trim($disease_query);

            if ($disease_query == 'Найти заболевание')
			{
				$disease_query = '';
			}

            if ($disease_query) {
				$criteria = new DiseaseSearchCriteria();
				$criteria->name = $disease_query;
				$criteria->is_active = true;
				$criteria->page = $page;
				$criteria->by_page = $by_page;

				$algorithm = new DiseaseSearchAlgorithm();
				$diseases = $algorithm->search($criteria);

				if($redirect = $algorithm->getRedirectUrl())
				{
					RedirectManager::redirect($redirect);
				}

                $next_page_flag = $algorithm->getNextPageFlag();

                $this->view->diseases = $diseases;

                foreach ($diseases as $disease) {
                    if ($disease->medicine) {
                        $this->view->medicine = $disease->medicine;
                        break;
                    }
                }

                if ($next_page_flag) {
                    $next_button = '<a class="view-more"><i></i>Показать ещё 10 заболеваний</a>';
                    $this->view->next_button = $next_button;
                }
            }
            else
			{
				$this->redirectUrl('/disease');
			}

	        $this->view->page_title = 'Результаты поиска';
        }

        public function moreSearchResults()
        {
            $disease_query = $this->request('disease_query');
            $by_page = 10;
            $page = $this->request('page');
            $get_extra_entry = 1;

            $diseases = ModelManagerFactory::getByName('disease')->getActiveListByTitleOrAltName($disease_query, $by_page, $page, $get_extra_entry);

            if (count($diseases) == 0) {
                $disease_tags = ModelManagerFactory::getByName('disease_tag')->getListByTag($disease_query);
                if ($disease_tags)
                    $diseases = ModelManagerFactory::getByName('disease')->getListByDiseaseTagId($disease_tags, $by_page, $page, $get_extra_entry);
            }

            $next_page_flag = (isset($diseases[$by_page]));
            unset($diseases[$by_page]);

            $diseases_string = '';
            foreach ($diseases as $disease){
                $diseases_string.='
                <li>
                    <div class="into">
                        <h2><a href="#">'.$disease->title.'</a></h2>
                        <p>'.mb_substr($disease->content,0,170,'UTF-8').'...</p>
                        <p class="more"><a href="/disease/get?id='.$disease->id.'">Подробнее</a></p>
                    </div>
                </li>';
            }

            $button_string = '';
            if ($next_page_flag) {
                $button_string.= '<a class="view-more"><i></i>Показать ещё 10 заболеваний</a>';
            }

            JsonResponse::result(array('diseases' => $diseases_string,'next_page_button' => $button_string));
        }

        public function ajaxGetUnderstandBlock()
        {
            $disease_id = $this->request('disease_id');

            if (Acc::isAuthed()) {
                $account_id = Acc::accountId();
                $check_understand = ModelManagerFactory::getByName('disease_understand')->checkExistsByDiseaseIdAndAccountId($disease_id, $account_id);
            } else {
                if (isset($_COOKIE['understand_' . $disease_id]) && $_COOKIE['understand_' . $disease_id] == 'done')
                    $check_understand = TRUE;
                else {
                    $check_understand = FALSE;
                }
            }

            $understand_string = '';
            if (!$check_understand) {
                $understand_string .= '
                    <a class="btn">Да</a>
                    <a class="btn">Нет</a>';
                JsonResponse::result(array('understand_text' => $understand_string));
            } else {
                $understand_string .= 'Голос учтен';
                JsonResponse::result(array('understand_text' => $understand_string));
            }
        }

        public function parseDiseasesAndDiseaseBlocks()
        {
            set_time_limit(0);

            ini_set("memory_limit", "128M");
            $xml_data = simplexml_load_file('http://admin:21506@content.lookmedbook.ru/media/xml/Test.xml');

            if ($xml_data)
            {
                self::clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease();
                ModelManagerFactory::getByName('disease')->setIsActive(0);

                $disease_manager = ModelManagerFactory::getByName('disease');
                $disease_alt_name_manager = ModelManagerFactory::getByName('disease_alt_name');
                $disease_tag_manager = ModelManagerFactory::getByName('disease_tag');
                $disease_to_disease_tag_manager = ModelManagerFactory::getByName('disease_to_disease_tag');
                $disease_block_manager = ModelManagerFactory::getByName('disease_block');
                $specialty_to_disease_manager = ModelManagerFactory::getByName('specialty_to_disease');
                $specialty_manager = ModelManagerFactory::getByName('specialty');

                foreach($xml_data->disease as $disease_data)
                {
                    $disease = ModelManagerFactory::getByName('disease')->getOneByContentProjectId($disease_data->info->project_id);

                    if ($disease) {
                        $disease->is_active = 1;

                        if ($disease->title != $disease_data->info->title)
                        {
                            $disease->title = $disease_data->info->title;
                            $disease->genitive_name = null;
                            $disease->prepositional_name = null;
                        }

                        if ($disease->date_update != $disease_data->info->dt_edit) {
                            $disease->date_update = $disease_data->info->dt_edit;
                        }

                        if ($disease->content != $disease_data->info->project_desc)
                        {
                            $disease->content = $disease_data->info->project_desc;
                        }

                        if ($disease_data->info->extended_desc == "&lt;br /&gt;<br />\r\n")
                        {
                            $disease->extended_content = null;
                        } else if ($disease->extended_content != $disease_data->info->extended_desc)
                        {
                            $disease->extended_content = $disease_data->info->extended_desc;
                        }

                        if ($disease_data->info->sources == "&lt;br /&gt;<br />\r\n")
                        {
                            $disease->sources = null;
                        } else if ($disease->sources != $disease_data->info->sources)
                        {
                            $disease->sources = $disease_data->info->sources;
                        }

                        $disease->save();
                    }
                    else {
                        $disease = new DiseaseModel();

                        $disease->title = $disease_data->info->title;
                        $disease->content = $disease_data->info->project_desc;
                        $disease->extended_content = $disease_data->info->extended_desc;
                        $disease->sources = $disease_data->info->sources;
                        $disease->is_active = 1;
                        $disease->content_project_id = $disease_data->info->project_id;

                        if ($disease_data->info->dt_edit) {
                            $disease->date_update = $disease_data->info->dt_edit;
                        }

                        $disease->save();
                    }

                    $disease_id = $disease->getId();

                    if ($disease_id) {

                        if ($disease_data->info->alt_name) {

                            $alt_names_string = str_replace('.', '', $disease_data->info->alt_name);
                            $alt_names = explode(",", $alt_names_string);
                            foreach ($alt_names as $alt_name) {
                                $disease_alt_name = new DiseaseAltNameModel();

                                $disease_alt_name->alt_name = trim($alt_name);
                                $disease_alt_name->disease_id = $disease_id;
                                $disease_alt_name_manager->save($disease_alt_name);
                            }
                        }

                        foreach ($disease_data->specialty as $specialty) {

                            $first_doctor = true;
                            $doctors_string = str_replace('.','',$specialty->name);
                            $doctors = explode(",", $doctors_string);
                            foreach ($doctors as $doctor) {
                                $specialty_to_disease = new SpecialtyToDiseaseModel();
                                $specialty_id = $specialty_manager->getIdByName(trim($doctor));

                                if ($specialty_id) {
                                    $specialty_to_disease->specialty_id = $specialty_id;
                                    $specialty_to_disease->disease_id = $disease_id;
                                    $specialty_to_disease->is_adult = $specialty->is_adult;
                                    $specialty_to_disease->is_male = $specialty->is_male;
                                    $specialty_to_disease->is_female = $specialty->is_female;
                                    $specialty_to_disease->is_children = $specialty->is_children;
                                    $specialty_to_disease->is_newborn = $specialty->is_newborn;
                                    $specialty_to_disease->is_pregnant = $specialty->is_pregnant;
                                    if ($first_doctor) $specialty_to_disease->main_flag = 1;
                                    $specialty_to_disease_manager->save($specialty_to_disease);

                                    $first_doctor = false;
                                }
                            }
                        }

                        if ($disease_data->info->tags) {
                            $tags = explode(",", $disease_data->info->tags);
                            foreach ($tags as $tag) {

                                $tag_id = ModelManagerFactory::getByName('disease_tag')->getIdByTag(trim($tag));

                                if (!$tag_id){
                                    $disease_tag = new DiseaseTagModel();

                                    $disease_tag->tag = trim($tag);
                                    $disease_tag_manager->save($disease_tag);
                                }

                                $tag_id = ModelManagerFactory::getByName('disease_tag')->getIdByTag(trim($tag));

                                if ($tag_id) {

                                    $disease_to_disease_tag = new DiseaseToDiseaseTagModel();

                                    $disease_to_disease_tag->disease_id = $disease_id;
                                    $disease_to_disease_tag->disease_tag_id = $tag_id;

                                    $disease_to_disease_tag_manager->save($disease_to_disease_tag);
                                }
                            }
                        }

                        foreach ($disease_data->block as $block) {

                            $disease_block = new DiseaseBlockModel();

                            $disease_block->disease_id = $disease_id;

                            if ($block->name == 'Симптомы')                         $disease_block_type_id = 1;
                            else if ($block->name == 'Инкубационный период')        $disease_block_type_id = 2;
                            else if ($block->name == 'Формы')                       $disease_block_type_id = 3;
                            else if ($block->name == 'Причины')                     $disease_block_type_id = 4;
                            else if ($block->name == 'Диагностика')                 $disease_block_type_id = 5;
                            else if ($block->name == 'Лечение')                     $disease_block_type_id = 6;
                            else if ($block->name == 'Осложнения и последствия')    $disease_block_type_id = 7;
                            else if ($block->name == 'Профилактика')                $disease_block_type_id = 8;
                            else if ($block->name == 'Дополнительно')               $disease_block_type_id = 9;

                            $disease_block->disease_block_type_id = $disease_block_type_id;
                            $disease_block->content = $block->content;
                            $disease_block->is_active = $block->is_active;
                            $disease_block->male_flag = $block->is_male;
                            $disease_block->female_flag = $block->is_female;
                            $disease_block->adult_flag = $block->is_adult;
                            $disease_block->children_flag = $block->is_children;
                            $disease_block->newborn_flag = $block->is_newborn;
                            $disease_block->pregnant_flag = $block->is_pregnant;

                            $disease_block_manager->save($disease_block);
                        }
                    }
                }
            }

            $this->redirectUrl('/admin/disease');
        }

        public function clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease()
        {
            $disease_block_manager = new DiseaseBlockManager();
            $disease_block_manager->deleteAll();
            $disease_block_manager->resetAutoIncrement();

            $disease_to_disease_tag_manager = new DiseaseToDiseaseTagManager();
            $disease_to_disease_tag_manager->deleteAll();
            $disease_to_disease_tag_manager->resetAutoIncrement();

            $disease_tag_manager = new DiseaseTagManager();
            $disease_tag_manager->deleteAll();
            $disease_tag_manager->resetAutoIncrement();

            $disease_alt_name_manager = new DiseaseAltNameManager();
            $disease_alt_name_manager->deleteAll();
            $disease_alt_name_manager->resetAutoIncrement();

            $specialty_to_disease_manager = new SpecialtyToDiseaseManager();
            $specialty_to_disease_manager->deleteAll();
            $specialty_to_disease_manager->resetAutoIncrement();
            /*
                        $disease_manager = new DiseaseManager();
                        $disease_manager->deleteAll();
                        $disease_manager->resetAutoIncrement();*/
            //$this->redirectUrl('/admin/disease');
            //exit();
        }

        public function ajaxAddToArchive()
        {
            if (!Acc::isAuthed()) $this->redirectUrl('/');

            $disease_id = $this->request->post('my_disease_id',0);

            $my_disease_manager = new MyDiseaseManager();

            if ($disease = $my_disease_manager->getOneById($disease_id))
            {
                $disease->is_archive = 1;
                $my_disease_manager->save($disease);
                JsonResponse::result(true);
            } else {
                JsonResponse::error(ValidationErrorCodes::WRONG_DISEASE);
            }
        }
    }