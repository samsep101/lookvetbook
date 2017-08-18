<?php

    CmsGeneratorConfigRegister::add('services_categories', [

        'table'     => 'services_categories', /*имя таблицы*/
        'title'     => 'Управление услугами', /*меняется "ролей"*/
        'fields'    => array(
            'id'        => 'index', /*всегда*/
            'slug'      => 'input',
            'name'      => 'input',
            'genitive_name' => 'input',
            'parent_id' => [
                'type' => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => 'services_categories',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'false',
                'sort_by'     => 'name',
            ],
            'status'      => 'checkbox',
            'price' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'slug'      => 'ЧПУ',
                'name'      => 'Название',
                'genitive_name' => 'Падеж',
                'parent_id' => 'Родительская услуга',
                'status'      => 'Активна?',
                'price' => 'Стоимость услуги'
            ),
            'list'   => array(
                'fields'  => ['id', 'name', 'slug', 'parent_id', 'price'], /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список услуг',
                'sort_by' => array(
                    ['field' => 'parent_id', 'desc'  => 'ASC'],
                    ['field' => 'slug', 'desc'  => 'ASC'],
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Услуга' => ['slug', 'name', 'genitive_name', 'parent_id', 'price', 'status']
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Услуга' => ['slug', 'name', 'genitive_name', 'parent_id', 'price', 'status']
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
        
        'extra' => [
            'relations_to_clinic' => [
                'type' => 'view',
                'title' => 'Связи с клиниками',
                'view' => function($view){
                    return $view->renderInString('admin/edit_sections/related_clinic', false);
                }
            ],
        ]

    ]);