<?php

class DiseaseController extends BaseController
{
    private $_segment_name = false;
    private $_segment_section = 'default';
  //зачем-то зашиты урлы редиректа болезней
  private $redirectList = [
      'aktinomikoz/adult' => 'aktinomikoz/male',
      'alimentarnaya-lihoradka/children' => 'lihoradka-gemorragicheskaya-lassa/children',
      'alimentarnaya-lihoradka/newborn' => 'lihoradka-gemorragicheskaya-lassa/children',
      'allergicheskiy-dermatit/adult' => 'allergicheskiy-kontaktnyy-dermatit/adult',
      'allergicheskiy-dermatit/children' => 'allergicheskiy-kontaktnyy-dermatit/children',
      'allergicheskiy-dermatit/newborn' => 'allergicheskiy-kontaktnyy-dermatit/children',
      'allergicheskiy-dermatit/pregnant' => 'allergicheskiy-kontaktnyy-dermatit/pregnant',
      'aspiraciya' => 'aspiraciya-inorodnyh-tel',
      'blennoreya' => 'aktinomikoz',
      'bolezn-kavasaki' => 'sindrom-kavasaki',
      'dvurogaya-matka' => 'dvurogaya-matka0',
      'dvurogaya-matka/children' => 'dvurogaya-matka0/children',
      'endometrit' => 'poslerodovoy-endometrit',
      'endometrit/pregnant' => 'poslerodovoy-endometrit/pregnant',
      'gelmintoz-parazitarnye-invazii/children' => 'gelmintoz',
      'gelmintoz-parazitarnye-invazii/male' => 'gelmintoz/adult',
      'gelmintoz-parazitarnye-invazii/pregnant' => 'gelmintoz/pregnant',
      'gepatoz/female' => 'gepatoz/pregnant',
      'gonoblennoreya' => 'aktinomikoz',
      'hlamidiynaya-infekciya/pregnant' => 'hlamidioz/pregnant',
      'hronicheskiy-yuvenilnyy-artrit/adult' => 'hlamidioz/male',
      'hronicheskiy-yuvenilnyy-artrit/children' => 'hlamidioz/female',
      'hronicheskiy-yuvenilnyy-artrit' => 'yuvenilnyy-revmatoidnyy-artrit',
      'hronicheskiy-yuvenilnyy-artrit' => 'yuvenilnyy-revmatoidnyy-artrit/children',
      'istinnaya-eroziya-sheyki-matki/pregnant' => 'yuvenilnyy-revmatoidnyy-artrit/children',
      'opuschenie-matki' => 'opuschenie-tazovyh-organov',
      'ostryy-bronhit' => 'bronhit-ostryy-hronicheskiy',
      'ostryy-gastroenterit' => 'gastroenterit-ostryy',
      'ostryy-gastroenterit/adult' => 'gastroenterit-ostryy/adult',
      'ostryy-gastroenterit/children' => 'gastroenterit-ostryy/children',
      'ostryy-gastroenterit/pregnant' => 'gastroenterit-ostryy/pregnant',
      'piodermii-stafilokokkovye' => 'stafilokokkovye-piodermii',
      'piodermii-stafilokokkovye/children' => 'stafilokokkovye-piodermii/children',
      'piodermii-stafilokokkovye/female' => 'stafilokokkovye-piodermii/female',
      'piodermii-stafilokokkovye/male' => 'stafilokokkovye-piodermii/male',
      'piodermii-stafilokokkovye/pregnant' => 'stafilokokkovye-piodermii/pregnant',
      'polip-placentarnyy' => 'polip-placentarnyy0',
      'polip-placentarnyy/female' => 'polip-placentarnyy0/female',
      'rozovyy-lishay' => 'rozovyy-lishay-zhibera',
      'seboreynaya-ekzema/newborn' => 'seboreynaya-ekzema/children',
      'toksicheskaya-eritema-novorozhdennyh/children' => 'toksicheskaya-eritema-novorozhdennyh/newborn',
      'trahoma/newborn' => 'trahoma/children',
      'vyvih-patologicheskiy' => 'vyvih-bedra-vrozhdennyy',
      'vyvih-patologicheskiy/children' => 'vyvih-bedra-vrozhdennyy/children',
      'zhenskoe-besplodie/female' => 'besplodie-zhenskoe',
    ];

    public function __construct() {

        parent::__construct();

        $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        !empty($uri[1]) AND $this->_segment_name = $uri[1];
        !empty($uri[2]) AND $this->_segment_section = $uri[2];
    }

  public function get($disease_id = null)
  {

    if (!$disease_id) {
      $disease_id = $this->request('id');
    }
    $card = $this->request('card');

    $currDeseaseUrl = str_replace('/disease/', '', $_SERVER['REQUEST_URI']);
    if (isset($this->redirectList[$currDeseaseUrl])) {
      RedirectManager::redirect301(SITE_URL . '/disease/' .$this->redirectList[$currDeseaseUrl]);
    }

    $disease_id_orig = $disease_id;
    $disease_id = preg_replace('/\d+$/', '', $disease_id);
    if($disease_id != $disease_id_orig and !in_array($disease_id_orig, $this->redirectList)) {
      RedirectManager::redirect301(SITE_URL . '/disease/' .$disease_id);
    }

    $diseaseManager = ModelManagerFactory::getByName('disease');
      /* @var DiseaseModel $disease */
    $disease = $diseaseManager->getOneByIdOrAlias($disease_id);

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
    if (!$card) {
      foreach ($disease_tabs_flags as $key => $v) {
        if ($v) {
          $card = $key;
          break;
        }
      }
    }

    $this->view->card = $card;

    if (!$this->view->is_test && (SITE_URL . $_SERVER['REQUEST_URI'] != DiseasePageLinkViewHelper::getLink($disease))) {
      if (!in_array($card, array('male', 'female', 'children', 'pregnant', 'adult', 'newborn')))
        ErrorPageViewHelper::page404('404');
    }
    if (is_numeric($disease_id) && $disease->alias) {
      RedirectManager::redirect301(DiseasePageLinkViewHelper::getLink($disease));
    }

    $this->view->disease = $disease;

    $account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
    $this->view->account = $account;

    $disease_blocks = $disease_block_manager->getActiveListByDiseaseId($disease->getId());
    $disease_blocks_content = $disease_block_manager->getActiveListByDiseaseIdAndFlag($disease->getId(), $card);
    $this->view->disease_blocks = $disease_blocks;
    $this->view->disease_blocks_content = $disease_blocks_content;
    $this->view->disease_tabs_flags = $disease_tabs_flags;
    $disease_specialties = ModelManagerFactory::getByName('specialty')->getMainListByDiseaseId($disease->getId());

    foreach ($disease_specialties AS $dsKey => $dsValue) {
      $disease_specialties[$dsKey]->specialtyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/doctor/' . $dsValue->alias;
    }

    $this->view->disease_specialties = $disease_specialties;

    $seo_method = '_seo_'.$this->_segment_section;
    $this->view->section = $this->_segment_section;
    if(method_exists($this, $seo_method)){
        // если есть спецметод генерации сео - выполняем его
        $this->{$seo_method}();
        
    } else {
        // дефолтная генерация title,desc
        $pageTitleTemplate = '%s симптомы, причины, диагностика, лечение. %s у %s ';
        $diseaseName = !empty($disease->title) ? '' : $disease->title;
        $diseaseTypes = array();

        foreach ($disease_tabs_flags AS $dtfKey => $dtfValue) {
          if ($dtfValue) {
            $diseaseTypes[] = mb_convert_case(DiseaseTabNameViewHelper::getNameByTabFlag($dtfKey, 1), MB_CASE_LOWER, "UTF-8");
          }
        }
        $this->view->page_title = sprintf($pageTitleTemplate, $diseaseName, $diseaseName, implode(', ', $diseaseTypes));
        $this->view->page_description = $this->_getDescription();
    }

    $this->view->label_for_counters = 'disease-page';
    
    $this->view->canonical_link = DiseasePageLinkViewHelper::getLink($disease);
    $this->view->site_url_not_using = 1;

    $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
    $v_param = $this->request('v');
    $this->view->show_pediatr_banner = ($v_param && $v_param == 'child') ? 1 : 0;
    $dis_param = $this->request('dis');
    $this->view->disease_green_btn = ($dis_param && $dis_param == 'new3') ? 1 : 0;
    $this->view->actions = (new ActionManager())->getListForDisease($disease->getId());
    if ($this->view->actions)
	    $this->view->actions = array($this->view->actions[array_rand($this->view->actions)]);

      $videos = ['1','2','3'];
      $video = $videos[array_rand($videos)];
      $this->view->video_file_path = '/media/images/vids/'.$video.'.mp4';
      $this->view->video_thumb_path = '/media/images/vids/'.$video.'.jpg';



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

    foreach ($disease_specialties AS $dsKey => $dsValue) $disease_specialties[$dsKey]->specialtyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/doctor/' . $dsValue->alias;

    $this->view->disease_specialties = $disease_specialties;

    $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
    $v_param = $this->request('v');
    $this->view->show_pediatr_banner = ($v_param && $v_param == 'child') ? 1 : 0;
    $dis_param = $this->request('dis');
    $this->view->disease_green_btn = ($dis_param && $dis_param == 'new3') ? 1 : 0;

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
    if ($_SERVER['REQUEST_URI'] == '/disease/search') {
      ErrorPageViewHelper::page404('404');
    }
    $disease_manager = new DiseaseManager();
    $diseases = $disease_manager->getActiveListByPage(false, false);

    $this->view->diseases = $diseases;
    $this->view->menu_active = 'disease';

    $this->view->page_title = 'Найти заболевание - «' . SITE_NAME . '»';
    $this->view->page_description = 'Найти заболевание - вся информация обо всех известных заболеваниях на сервисе ' . SITE_NAME . '';

    $this->view->label_for_counters = 'disease-search';
  }

  public function searchResults()
  {
    $disease_query = $this->request('disease_query');
    $by_page = $this->request('by_page', 10);
    $page = 1;

    $disease_query = trim($disease_query);
    $this->view->query = trim($disease_query);

    if ($disease_query == 'Найти заболевание') {
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

      if ($redirect = $algorithm->getRedirectUrl()) {
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
    } else {
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
    foreach ($diseases as $disease) {
      $diseases_string .= '
      <li>
        <div class="into">
        <h2><a href="#">' . $disease->title . '</a></h2>
        <p>' . mb_substr($disease->content, 0, 170, 'UTF-8') . '...</p>
        <p class="more"><a href="/disease/get?id=' . $disease->id . '">Подробнее</a></p>
        </div>
      </li>';
    }

    $button_string = '';
    if ($next_page_flag) {
      $button_string .= '<a class="view-more"><i></i>Показать ещё 10 заболеваний</a>';
    }

    JsonResponse::result(array('diseases' => $diseases_string, 'next_page_button' => $button_string));
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
    $xml_data = simplexml_load_file(CONTENT_DISEASE_URL);

    if ($xml_data) {
      self::clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease();
      ModelManagerFactory::getByName('disease')->setIsActive(0);

      $disease_manager = ModelManagerFactory::getByName('disease');
      $disease_alt_name_manager = ModelManagerFactory::getByName('disease_alt_name');
      $disease_tag_manager = ModelManagerFactory::getByName('disease_tag');
      $disease_to_disease_tag_manager = ModelManagerFactory::getByName('disease_to_disease_tag');
      $disease_block_manager = ModelManagerFactory::getByName('disease_block');
      $specialty_to_disease_manager = ModelManagerFactory::getByName('specialty_to_disease');
      $specialty_manager = ModelManagerFactory::getByName('specialty');

      foreach ($xml_data->disease as $disease_data) {
        $disease = ModelManagerFactory::getByName('disease')->getOneByContentProjectId($disease_data->info->project_id);

        if ($disease) {
          $disease->is_active = 1;

          if ($disease->title != $disease_data->info->title) {
            $disease->title = $disease_data->info->title;
            $disease->genitive_name = null;
            $disease->prepositional_name = null;
          }

          if ($disease->date_update != $disease_data->info->dt_edit) {
            $disease->date_update = $disease_data->info->dt_edit;
          }

          if ($disease->content != $disease_data->info->project_desc) {
            $disease->content = $disease_data->info->project_desc;
          }

          if ($disease_data->info->extended_desc == "&lt;br /&gt;<br />\r\n") {
            $disease->extended_content = null;
          } else if ($disease->extended_content != $disease_data->info->extended_desc) {
            $disease->extended_content = $disease_data->info->extended_desc;
          }

          if ($disease_data->info->sources == "&lt;br /&gt;<br />\r\n") {
            $disease->sources = null;
          } else if ($disease->sources != $disease_data->info->sources) {
            $disease->sources = $disease_data->info->sources;
          }

          $disease->save();
        } else {
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
            $doctors_string = str_replace('.', '', $specialty->name);
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

              if (!$tag_id) {
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

            if ($block->name == 'Симптомы') $disease_block_type_id = 1;
            else if ($block->name == 'Инкубационный период') $disease_block_type_id = 2;
            else if ($block->name == 'Формы') $disease_block_type_id = 3;
            else if ($block->name == 'Причины') $disease_block_type_id = 4;
            else if ($block->name == 'Диагностика') $disease_block_type_id = 5;
            else if ($block->name == 'Лечение') $disease_block_type_id = 6;
            else if ($block->name == 'Осложнения и последствия') $disease_block_type_id = 7;
            else if ($block->name == 'Профилактика') $disease_block_type_id = 8;
            else if ($block->name == 'Дополнительно') $disease_block_type_id = 9;

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

    $disease_id = $this->request->post('my_disease_id', 0);

    $my_disease_manager = new MyDiseaseManager();

    if ($disease = $my_disease_manager->getOneById($disease_id)) {
      $disease->is_archive = 1;
      $my_disease_manager->save($disease);
      JsonResponse::result(true);
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DISEASE);
    }
  }

    protected function _seo_default() {
        
        $disease = $this->view->disease;
        // Лабиринтит: симптомы, причины, диагностика и лечение лабиринтита
        $this->view->page_title = sprintf('%s: симптомы, причины, диагностика и лечение %s', $disease->title, $disease->genitive_name);
        $this->view->page_description = $this->_getDescription();
        // окончание заголовка h2
        $disease->h2_extend = $disease->genitive_name;
    }

    protected function _seo_adult() {

        $disease = $this->view->disease;
        // Лабиринтит у взрослых: симптомы, причины, диагностика и лечение лабиринтита у взрослого
        $this->view->page_title = sprintf('%s у взрослых: симптомы, причины, диагностика и лечение %s у взрослого', $disease->title, $disease->genitive_name);
        $this->view->page_description = str_ireplace($disease->title, $disease->title.' у взрослых', $this->_getDescription());
        $disease->title = $disease->title . ' у взрослых';
        $disease->h2_extend = $disease->genitive_name . ' у взрослого';
    }

    protected function _seo_children() {

        $disease = $this->view->disease;
        // Лабиринтит у детей: симптомы, причины, диагностика и лечение лабиринтита у ребенка
        $this->view->page_title = sprintf('%s у детей: симптомы, причины, диагностика и лечение %s у ребенка', $disease->title, $disease->genitive_name);
        $this->view->page_description = str_ireplace($disease->title, $disease->title.' у детей', $this->_getDescription());
        $disease->title = $disease->title . ' у детей';
        $disease->h2_extend = $disease->genitive_name . ' у ребенка';
    }

    protected function _seo_pregnant() {

        $disease = $this->view->disease;
        // Лабиринтит у беременных: симптомы, причины, диагностика и лечение лабиринтита у беременной
        $this->view->page_title = sprintf('%s у беременных: симптомы, причины, диагностика и лечение %s у беременной', $disease->title, $disease->genitive_name);
        $this->view->page_description = str_ireplace($disease->title, $disease->title.' у беременных', $this->_getDescription());
        $disease->title = $disease->title . ' у беременных';
        $disease->h2_extend = $disease->genitive_name . ' у беременной';
    }

    protected function _seo_male() {

        $disease = $this->view->disease;
        // Желтая лихорадка у мужчин: симптомы, причины, диагностика и лечение желтой лихорадки у мужчины
        $this->view->page_title = sprintf('%s у мужчин: симптомы, причины, диагностика и лечение %s у мужчины', $disease->title, $disease->genitive_name);
        $this->view->page_description = str_ireplace($disease->title, $disease->title.' у мужчин', $this->_getDescription());
        $disease->title = $disease->title . ' у мужчин';
        $disease->h2_extend = $disease->genitive_name . ' у мужчины';
    }

    protected function _seo_female() {

        $disease = $this->view->disease;
        // Желтая лихорадка у женщин: симптомы, причины, диагностика и лечение желтой лихорадки у женщины
        $this->view->page_title = sprintf('%s у женщин: симптомы, причины, диагностика и лечение %s у женщины', $disease->title, $disease->genitive_name);
        $this->view->page_description = str_ireplace($disease->title, $disease->title.' у женщин', $this->_getDescription());
        $disease->title = $disease->title . ' у женщин';
        $disease->h2_extend = $disease->genitive_name . ' у женщины';
    }

    protected function _getDescription() {

        $disease = $this->view->disease;

        $pervoe_predlozhenie = '';
        if (preg_match('$\s*?([A-ZА-ЯЁ].*?\.)$', strip_tags($disease->content), $a)) {
            $pervoe_predlozhenie = $a[1];
        }
        return $pervoe_predlozhenie ? $pervoe_predlozhenie : $disease->description;
    }
}