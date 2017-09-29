<?php

class ActionController extends BaseController
{
    function get()
    {
        $action = (new ActionManager())->getOneByIdOrAlias($this->request('id'));
        $this->view->action = $action;

    }

    public function index()
    {
        $actions = (new ActionManager())->getList();
        $this->view->actions = $actions;
    }
}