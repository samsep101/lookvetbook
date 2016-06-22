<?php

/**TODO
 * add data description
 * Class ActionManager
 */
class ActionManager extends ModelManager
{
    protected $table_name = 'action';
    protected $model_name = 'ActionModel';

    public function getListByClinic($clinic_id){
        $clinic_id = intval($clinic_id);
        if (!$clinic_id)
            return false;

        $criteria = new ActionSearchCriteria();
        $criteria->setSearchParams((new SearchParams())->addCriteria('clinic_id'),$clinic_id);
        return $this->getListByModelSearchCriteria($criteria);
    }
}