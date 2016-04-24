<?php

class AccountManageController extends BaseController
{
  private $requestFieldList = ['first_name', 'middle_name', 'last_name', 'phone', 'email', 'id', 'nick', 'password'];

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
    foreach($this->requestFieldList as $fName) {
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
    $this->edit();
  }


  public function edit()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request('id');
    if($acc_id) {
      $account_manager = new AccountManager();
      $account = $account_manager->getOneById($acc_id, 'w_phone');
    }else{
      $account = new stdClass();
    }

    foreach($this->requestFieldList as $field) {
      $fld_val = @$account->$field;
      $this->view->$field = empty($fld_val)?'':$fld_val;
    }
  }

  public function ajaxEditAccount()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request->post('account_id');
    if($acc_id>0) {
      $account_manager = new AccountManager();
      $account = $account_manager->getOneById($acc_id);

      foreach($this->requestFieldList as $fName) {
        if($fName=='id') continue;
        $fld_val = @$account->$fName;
        $account->$fName = $this->request->post($fName);
      }

    }

    if(empty($account)) {
      $account = new AccountModel();
    }
    foreach($this->requestFieldList as $fName) {
      if($fName=='id') continue;
      $account->$fName = $this->request->post($fName);
    }

    if ($account->save() and $account->id) {
      $errors = $account->save_phones($account->id, $account->phone);
      if($errors) {
        JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $errors);
      }else {
        JsonResponse::result(['account_id' => $account->id]);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $account->getValidator()->getErrorMessages());
    }
  }


}