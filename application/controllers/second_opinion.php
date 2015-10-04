<?php
    class Second_opinionController extends BaseController
    {
        public $layout = 'home';

        public function index()
        {
            $this->view->page_title = 'Расшифровка снимков МРТ, КТ и других исследований, мнения экспертов';
            $this->view->is_second_opinion = true;
            $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
        }
    }

