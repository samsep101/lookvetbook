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
    $search_line = '';
    foreach(['first_name', 'middle_name', 'last_name', 'phone_number', 'email', 'id'] as $fName) {
      //$fValue = $this->request($fName);
      $fValue = $this->request->get($fName);
      $account_search_params->$fName = $fValue;
      $search_line .= ($search_line?'&':'').$fName.'='.urlencode($fValue);
      $this->view->$fName = $fValue;
    }
    $page = (int)$this->request->get('page');
    if($page) {
      $account_search_params->page=$page;
    }

//    $account_search_params->registry_user_id = Acl::userId();

    $account_manager = new AccountManager();
    $accounts = $account_manager->getListByModelSearchCriteria($account_search_params);
    $count = $account_manager->getTotalHits();

    $this->view->accounts = $accounts;
    $this->view->records_count = $count;
    $this->view->page_nm = $page?$page:1;
    $this->view->page_size = $account_search_params->by_page;
    $this->view->search_line = $search_line;
  }

  public function create()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      RedirectManager::redirect(ADMIN_FOLDER);


//    $this->view->account = $account;
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
    $account = $account_manager->getOneById($acc_id, 'w_phone');

    $fields = ['id', 'first_name', 'middle_name', 'last_name', 'nick', 'email', 'phones', ];
    foreach($fields as $field) {
      $fld_val = $account->$field;
      $this->view->$field = empty($fld_val)?'':$fld_val;
    }
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