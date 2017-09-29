<?php
$cms_account = array(
    'table'     => DB_PREFIX . 'petservice',
    'title'     => 'Услуги для животных',
    'fields'    => array(
        'id'               => 'index',
        'name'       => 'input',
        'alias'        => 'input',
        'is_active'      => array(
            'type'  => 'checkbox',
            'label' => 'Да/нет'
        )
    ),
    'generator' => array(
        'fields' => array(
            'id'               => 'ID',
            'name'       => 'Название',
            'alias'        => 'Синоним',
            'is_active'      => 'Включено'
        ),
        'list'   => array(
            'fields'  => array(
                'name',
                'is_active'
            ),
            'title'   => 'Список услуг животных',
            'sort_by' => array(
                array(
                    'field' => 'id',
                    'desc'  => 'DESC'
                ),
            ),
            'extra'     => [],
            'filters' => [],
        ),
        'edit'   => array(
            'fields'  => array(
                'Данные аккаунта' => array(
                    'name',
                    'alias',
                    'is_active'
                ),
            ),
            'tooltip' => '',
            'title'   => 'Редактирование',
            'submit'  => 'Сохранить',
        ),
        'add'    => array(
            'fields'  => array(
                'Тип аккаунта' => array(
                    'name',
                    'alias',
                    'is_active'
                ),
            ),
            'tooltip' => '',
            'title'   => 'Добавить',
            'submit'  => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('petservice', $cms_account);