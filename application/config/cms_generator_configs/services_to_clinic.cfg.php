<?php

    CmsGeneratorConfigRegister::add('services_to_clinic', [

        'table'     => 'services_to_clinic', /*имя таблицы*/
        'title'     => 'Связи услуг и клиник', /*меняется "ролей"*/
        'fields'    => array(
            'pid'        => 'index', /*всегда*/
            'services_categories_id'          => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => 'services_categories',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'clinic_id'          => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'pid'        => 'ID',
                'services_categories_id'      => 'Название услуги',
                'clinic_id'      => 'Клиника',
            ),
            'list'   => array(
                'fields'  => ['pid', 'services_categories_id', 'clinic_id'], /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей',
                'sort_by' => array(
                    ['field' => 'pid', 'desc'  => 'DESC'],
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => ['pid', 'services_categories_id', 'clinic_id']
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => ['pid', 'services_categories_id', 'clinic_id']
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),

    ]);