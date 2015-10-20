<?php
	class AjaxAdminController extends Controller
	{
		public function __construct()
		{
			$this->layout = 'ajax';
		}

		public function setCheckbox()
		{
			$table = $this->request->post('table');
			$id = $this->request->post('id');
			$field_name = $this->request->post('field_name');
			$value = $this->request->post('value');

			if(!Acl::userGrant($table.'_edit'))
			{
				JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
			}

			$manager = ModelManagerFactory::getByName($table);

			if ($manager)
			{
				$model = $manager->getOneById($id);

				if ($model){
					$model->{$field_name} = $value;
					$model->checkbox_set = 1;
					if ($model->save()){
						JsonResponse::result($model->{$field_name});
					}
				}
			}

			JsonResponse::result(true);
		}

		public function setCheckboxes()
		{
			$table = $this->request('table');
			$field_name = $this->request('field_name');
			$values = $this->request('values');

			$manager = ModelManagerFactory::getByName($table);


			if ($values)
			{
				foreach($values as $id => $value)
				{
					if ($manager)
					{
						$model = $manager->getOneById($value['id']);

						if ($model){
							$model->{$field_name} = $value['value'];
							$model->save();
						}
					}
				}

				JsonResponse::result(true);
			}

		}

		public function saveItemsOrder()
		{
			if (!Acl::isAuthed())
			{
				JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
			}

			$model_name = $this->request->post('model_name');
			$order = $this->request->post('order');

			$manager = ModelManagerFactory::getByName($model_name);

			$manager->setOrder($order);

			JsonResponse::result();
		}

		public function getPurposesOfVisitBySpecialtyId()
		{
			if (!Acl::isAuthed())
				JsonResponse::result(ValidationErrorCodes::NOT_AUTHED);

			$specialty_id = $this->request('specialty_id');

			$purpose_of_visit_manager = new PurposeOfVisitManager();

			$purposes_of_visit = $purpose_of_visit_manager->getListBySpecialtyId($specialty_id);

			$result = array();

			if ($purposes_of_visit)
				foreach($purposes_of_visit as $purpose_of_visit)
				{
					$result[] = array(
						'id' => $purpose_of_visit->getId(),
						'name' => $purpose_of_visit->name
					);
				}

			JsonResponse::result($result);
		}

		public function saveToken()
		{
			if (!Acl::isAuthed())
				JsonResponse::error(4);

			$auth_code = $this->request('token');

			$google_api_adapter = new GoogleClientAuthAdapter();
			$token = $google_api_adapter->getTokenByAuthCode($auth_code);

			if ($token) {
				SettingsManager::set('google_api_token', $token);
				JsonResponse::result(true);
			} else {
				JsonResponse::error(5);
			}
		}

		public function getYandexContentStatistic()
		{
			if (!Acl::isAuthed())
				JsonResponse::result(ValidationErrorCodes::NOT_AUTHED);

				$disease_manager = new DiseaseManager();
				$yandex_counters = new YandexContentCounters();
				$yandex_counters->content_active = $disease_manager->getCountActiveList();
				$yandex_counters->content_not_in_yandex = $disease_manager->getCountNotInYandex();
				$yandex_counters->content_in_yandex = $disease_manager->getCountInYandex();
				$yandex_counters->content_to_update = $disease_manager->getCountInYandexToUpdate();

				$this->view->yandex_counters = $yandex_counters;
				$html = $this->renderInString('/admin/blocks/yandex_content_counters');
				JsonResponse::result(array('html' => $html));
		}
	}