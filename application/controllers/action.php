<?php

class ActionController extends BaseController
{
    public function __construct() {

        RedirectManager::redirect301('http://cashback.lookmedbook.ru/');
    }

    function get()
    {
        $action = (new ActionManager())->getOneByIdOrAlias($this->request('id'));
        $this->view->action = $action;

    }

    public function index()
    {
        $actions = (new ActionManager())->getList();
        $actions=array_reverse($actions);
        $this->view->actions = $actions;

    }
}