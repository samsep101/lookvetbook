<?php
    class ModerateStatusViewHelper {

        public static function view($moderate_status_id)
        {
            $result = '';

            switch($moderate_status_id)
            {
                case ModerateStatusModel::EDIT:
                    $result = 'Статус: Редактируется, не отправлено в LookMedBook';
                    break;
                case ModerateStatusModel::MODERATE:
                    $result = 'Статус: Отправлено в LookMedBook';
                    break;
                case ModerateStatusModel::PUBLISHED:
                    $result = 'Статус: Опубликован на LookMedBook';
                    break;
                case ModerateStatusModel::SENT_BACK:
                    $result = 'Статус: Нужна доработка';
                    break;
            }

            return $result;
        }
    }