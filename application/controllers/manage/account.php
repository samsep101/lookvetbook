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

    $sortBy = $this->request->get('sort');
    $sortAsc = $this->request->get('asc');

    $account_search_params = new AccountSearchParams();
    $search_line = '';
    foreach($this->requestFieldList as $fName) {
      //$fValue = $this->request($fName);
      $fValue = $this->request->get($fName);
      $account_search_params->$fName = $fValue;
      if($fValue) $search_line .= ($search_line?'&':'').$fName.'='.urlencode($fValue);
      $this->view->$fName = $fValue;
    }
    $page = (int)$this->request->get('page');
    if($page) {
      $account_search_params->page=$page;
    }

    if(in_array($sortBy, ['first_name', 'middle_name', 'last_name', 'nick', 'phone', 'email'])) {
      $account_search_params->sort_by = $sortBy;
    }elseif($sortBy=='regdate'){
      $account_search_params->sort_by = 'dt';
    }
    if($account_search_params->sort_by){
      $asc = 1;
      if($sortAsc==-1) {
        $asc = -1;
        $account_search_params->sort_asc = -1;
      }
      $search_line .= ($search_line?'&':'').'sort='.$sortBy.'&asc='.$asc;
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

    if ($account->save() and $acc_id=$account->getId()) {
      $errors = $account->save_phones($acc_id, $account->phone);
      if($errors) {
        JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $errors);
      }else {
        JsonResponse::result(['account_id' => $acc_id]);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $account->getValidator()->getErrorMessages());
    }
  }


}