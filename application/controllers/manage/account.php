<?php

class AccountManageController extends BaseController
{
  public function __construct()
  {
    $this->layout = 'registry';
    parent::__construct();
  }

  public function index()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      RedirectManager::redirect(ADMIN_FOLDER);

    $account_search_params = new AccountSearchParams();
    foreach(['first_name', 'middle_name', 'last_name', 'phone_number', 'email', 'id'] as $fName) {
      //$fValue = $this->request($fName);
      $fValue = $this->request->get($fName);
      $account_search_params->$fName = $fValue;
      $this->view->$fName = $fValue;
    }

//    $account_search_params->registry_user_id = Acl::userId();

    $account_manager = new AccountManager();
    $accounts = $account_manager->getListByModelSearchCriteria($account_search_params);




    $this->view->accounts = $accounts;

  }

  public function create()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      RedirectManager::redirect(ADMIN_FOLDER);

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getListByManagerAccountId(Acl::userId());

    $this->view->clinics = $clinics;
  }

  public function ajaxCreateAccount()
  {
    if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $login = $this->request->post('login');
    $password = $this->request->post('password');
    $role_id = $this->request->post('role_id');

    $clinic_id = $this->request->post('clinic_id');

    if (!$clinic_id) {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, 'Клиника обязательно должна быть указана');
    }

    $user = new AccountModel();
    $user->login = $login;
    $user->password = sha1($password);
    $user->role_id = $role_id;

    if (!$user->save()) {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $user->getValidator()->getErrorMessages());
    }

    $clinic_to_user = new ClinicToAccountModel();
    $clinic_to_user->clinic_id = $clinic_id;
    $clinic_to_user->user_id = $user->getId();
    $clinic_to_user->save();

    JsonResponse::result(true);
  }

  public function edit()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request('id');


    $account_manager = new AccountManager();
    $account = $account_manager->getOneById($acc_id);

    $this->view->account = $account;

    $clinic_manager = new ClinicManager();
    $this->view->clinics = $clinic_manager->getListByManagerAccountId(Acl::userId(), $additional_access);

    $by_page = 15;
    $page = $this->request('page', 1);

    $this->view->page = $page;

    $this->view->current_page = $page;
    $this->view->page_url = '/manage/account/edit';

    $city_id = $this->request->get('city_id', null);
    $this->view->city_id = $city_id;

    Environment::set('get_total_count', true);

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->page = $page;
    $clinic_search_params->by_page = $by_page;
    $clinic_search_params->registry_account_id = $account->getId();
    $clinic_search_params->city_id = $city_id;

    $account_clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);
    $this->view->account_clinics = $account_clinics;
    $total_count = $clinic_manager->getTotalHits();

    $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

    /**
     * @var CityManager $city_manager
     * @var CityModel[] $cities
     */
    $city_manager = ModelManagerFactory::getByName('city');

    $cities = $city_manager->getListToEditByAccountId($acc_id);
    $this->view->cities = $cities;
  }

  public function ajaxEditAccount()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request->post('account_id');
    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($acc_id);
    if (!RegistryAccessHelper::checkAccessToAccount($acc_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }
    $account_manager = new AccountManager();
    $account = $account_manager->getOneById($acc_id);

    $login = $this->request->post('login');
    $account->login = $login;

    $password = $this->request->post('password');
    if ($password)
      $account->password = sha1($password);

    $role_id = $this->request->post('role_id');
    $account->role_id = $role_id;

    if ($account->save()) {
      JsonResponse::result();
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $account->getValidator()->getErrorMessages());
    }
  }

  public function ajaxDeleteClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request->post('account_id');
    $clinic_id = $this->request->post('clinic_id');

    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($acc_id);
    if (!RegistryAccessHelper::checkAccessToClinic($clinic_id, $additional_access) || !RegistryAccessHelper::checkAccessToAccount($acc_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $clinic_to_account_manager = new ClinicToAccountManager();
    $clinic_to_account_manager->deleteByAccountIdAndClinicId($acc_id, $clinic_id);

    JsonResponse::result();
  }

  public function ajaxAddClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request->post('account_id');
    $clinic_id = $this->request->post('clinic_id');

    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($acc_id);
    if (!RegistryAccessHelper::checkAccessToClinic($clinic_id, $additional_access) || !RegistryAccessHelper::checkAccessToAccount($acc_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $clinic_to_account_model = new ClinicToAccountModel();
    $clinic_to_account_model->clinic_id = $clinic_id;
    $clinic_to_account_model->account_id = $acc_id;
    $clinic_to_account_model->save();
    JsonResponse::result();
  }

  public function ajaxGetClinicsByAccountId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request('account_id');
    $page = $this->request('page');

    $clinic_manager = ModelManagerFactory::getByName('clinic');

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->is_region = null;
    $clinic_search_params->registry_account_id = $acc_id;

    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

    $this->view->clinics = $clinics;
    $this->view->page = $page;

    $html = $this->renderInString('/registry/manage/blocks/clinic_results');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }
}