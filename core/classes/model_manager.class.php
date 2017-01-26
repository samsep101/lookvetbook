<?php

class ModelManager implements ICachedModelManager
{
  protected $table_name = '';
  protected $id_field_name = 'id';
  protected $selected_fields = '*';
  /**
   * @var Orm
   */
  protected $orm_model;
  protected $model_name;
  /**
   * @var Db
   */
  protected $db;

  protected $fields = array();

  protected $model_register_enable = 1;

  protected static $model_register_enable_global = 1;

  /**
   * @var array
   */
  public $models_register = array();

  protected $insert_type = 'normal';

  private $is_cached = true;

  /**
   * @var DbField[]
   */
  protected $db_fields = array();
  protected $db_field_types = array();

  protected $insert_delayed = false;

  protected $total_hits = null;

  public function __construct($table = '')
  {
    if ($table) {
      $this->table_name = $table;
      $this->model_name = $table . 'Model';
    }

    if (!$this->table_name && !($this instanceof CustomModelManager) && !($this instanceof StaticDataModelManager))
      throw new Exception('Не указана таблица для модели!');

    $this->orm_model = new Orm(DB_PREFIX . $this->table_name);
    $this->db = Register::get('db');
  }

  public function getGroupName()
  {
    return $this->table_name;
  }

  public function getModelName()
  {
    return $this->model_name;
  }


  public function getNotCachedMethods()
  {
    return array();
  }

  public function getCachedMethods()
  {
    return array();
  }

  public function isCached()
  {
    return $this->is_cached;
  }

  public function setCached()
  {
    $this->is_cached = true;
  }

  public function setNoCached()
  {
    $this->is_cached = false;
  }

  public function getTotalHits()
  {
    if ($this->total_hits) {
      return $this->total_hits;
    } else {
      return Db::getCountWithoutLimit();
    }
  }

  /**
   * @param $id
   *
   * @return DynamicModel
   */
  public function getOneById($id)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . DB_PREFIX . $this->table_name . '`
                    WHERE `' . $this->id_field_name . '` = ' . (int)$id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? self::initOne($data[0]) : NULL;
  }


  /**
   * @param integer[] $id_list
   * @return DynamicModel[]
   */
  public function getSortedListByIdList(array $id_list)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM ' . $this->table_name . '
                    WHERE id IN (' . join(', ', $id_list) . ')
                    ORDER BY FIELD(id, ' . join(', ', $id_list) . ')';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @return DynamicModel[]
   */
  public function getList()
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . $this->table_name . '`';


    $id_list = $this->db->query($sql);

    return $this->initList($id_list);
  }

  /**
   * @param $sort_field
   *
   * @return DynamicModel[]
   */
  public function getSortedList($sort_field)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM ' . $this->table_name . '
                    ORDER BY ' . $sort_field;

    $data = Register::get('db')->query($sql);

    return $this->initList($data);
  }

  public function getFields()
  {
    return $this->fields;
  }

  public function getListBySearchParams_with_shuffle(SearchParams $search_params)
  {
    list($offset,$limit) = $search_params->setLimit(0);
    $join_select_fields = $search_params->setJoinSelectFields([]);
    $select_fields = $search_params->setSelectFields([$this->id_field_name]);

    $search_params->setIdFieldName($this->id_field_name);
    $sql = $search_params->buildQuery($this->table_name);
    $data = $this->db->query($sql);
    shuffle($data);
    $found_ids = array_map(function($a){ return $a[$this->id_field_name]; }, array_slice($data, $offset, $limit+1));
    $search_params->setSelectFields($select_fields);
    $search_params->setJoinSelectFields($join_select_fields);

    $search_params->addParam($this->id_field_name.' IN ', $found_ids);
    $sql = $search_params->buildQuery($this->table_name);
    $data = $this->db->query($sql);

    return $this->initList($data,$search_params->joined_field_models());
  }

  public function getListBySearchParams(SearchParams $search_params)
  {
    $search_params->setIdFieldName($this->id_field_name);
    $search_params->calcFoundRows();
    $sql = $search_params->buildQuery($this->table_name);
    $data = $this->db->query($sql);
    return $this->initList($data,$search_params->joined_field_models());
  }



  protected function initList($entries_list, $joined_models=[])
  {
    $result = array();
    if (count($entries_list)) {
      foreach ($entries_list as $entry) {
        $res = $this->initOne($entry);
        $result[] = $res;
      }
    }
    return $result;
  }


  protected function initOne($info)
  {
    if (!$info) {
      return NULL;
    }
    $id = $info[$this->id_field_name];

//            if (isset($this->models_register[$id]) && $this->model_register_enable && static::$model_register_enable_global)
//                return $this->models_register[$id];

    if (count($info)) {
      if (!class_exists($this->model_name, FALSE) && !Application::tryToLoadClass($this->model_name)) {
        throw new Exception('Не удалось найти класс ' . $this->model_name);
      }
      $this->models_register[$id] = new $this->model_name();
      $this->models_register[$id]->setParams($id, $info);

      return $this->models_register[$id];
    } else {
      return NULL;
    }
  }

  public function save(DynamicModel $model)
  {
    if ($model->validate()) {
      if ($model && (strtolower($this->model_name) == strtolower(get_class($model)))) {
        if (!$model->getSaveProcessFlag()) {
          $model->setSaveProcessFlag();
          $this->filter($model);
          $this->beforeSave($model);
        }

        if (!$model->getId()) {
          self::create($model);
        } else {
          self::update($model);
        }
        $this->afterSave($model);
      } else {
        throw new Exception('Передана неверная модель!');
      }
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function disableEntityMap()
  {
    $this->model_register_enable = 0;
  }

  public static function disableEntityMapGlobal()
  {
    static::$model_register_enable_global = false;
  }

  protected function create(DynamicModel $model)
  {
    $params_array = $this->formParamsArrayFromModel($model);
    $id = $this->orm_model->insert($params_array);
    $this->models_register[$id] = $model->setId($id);

    $model->setId($id);
  }

  public function delete(DynamicModel $object)
  {
    $this->deleteById($object->getId());
  }


  protected function afterDelete(DynamicModel $object)
  {

  }

  public function deleteById($id)
  {
    $object = $this->getOneById($id);
    if ($object) {
      $this->orm_model->delete($this->id_field_name . ' = "' . $id . '"');
    }

    if (isset($this->models_register[$id])) {
      unset($this->models_register[$id]);
    }
    if ($object) {
      $this->afterDelete($object);
    }
  }

  public function deleteByIds($ids)
  {
    $objects = [];
    foreach($ids as $id) {
      $obj = $this->getOneById($id);
      if($obj) $objects[$id] = $obj;
    }
    $this->orm_model->delete($this->id_field_name . ' = "' . implode('" or '.$this->id_field_name . ' = "', $ids).'"');

    foreach($objects as $id=>$object) {
      if (isset($this->models_register[$id])) {
        unset($this->models_register[$id]);
      }
      $this->afterDelete($object[$id]);
    }
  }

  protected function update(DynamicModel $model)
  {
    if (!$this->id_field_name) {
      throw new Exception('Не указан первичный ключ');
    }

    $params_array = $this->formParamsArrayFromModel($model);

    $this->orm_model->update($params_array, $this->id_field_name . ' = "' . $model->getId() . '"');
  }

  private function formParamsArrayFromModel(DynamicModel $model)
  {
    if (!count($this->db_fields)) {
      $this->getDbFields();
    }

    $params_array = array();

    foreach ($this->db_fields as $field) {
      if ((preg_match('/^(.+)_id$/', $field->getName(), $matches))
        && ($model->{$matches[1]} !== NULL)
        && is_object($model->{$matches[1]})
        && ($model->{$matches[1]} instanceof DynamicModel)
        && ($model->{$matches[1]}->getId())
      ) {
        if (($model->{$field->getName()} === NULL) || ($model->{$field->getName()} != $model->{$matches[1]}->getId())) {
          $params_array[$field->getName()] = $model->{$matches[1]}->getId();
        }
      }

      $value = $model->{$field->getName()};

      if ((in_array($field->getType(), array('int(11)', 'float')) && $value === '')) {
        $value = NULL;
      }

      if ($field->getForeignKey() && !$value)
        $value = NULL;

      $params_array[$field->getName()] = $value;
    }

    return $params_array;
  }

  protected function getDbFields()
  {
    $sql = 'SHOW COLUMNS FROM `' . $this->table_name . '`';

    $data = $this->db->query($sql);

    foreach ($data as $v) {
      if ($v['Field'] != $this->id_field_name) {
        $name = $v['Field'];
        $foreign_key = ($v['Key'] == 'MUL' && preg_match('/_id$/', $name)) ? TRUE : FALSE;
        $type = $v['Type'];
        $this->db_fields[] = new DbField($name, $foreign_key, $type);
      }
    }
  }

  protected function beforeSave(DynamicModel $model)
  {

  }

  protected function afterSave(DynamicModel $model)
  {

  }

  public function checkExistsById($id)
  {
    $sql = 'SELECT COUNT(*) as `result`
                    FROM ' . $this->table_name . '
                    WHERE `' . $this->id_field_name . '` = "' . $this->db->escape($id) . '"';

    $data = $this->db->query($sql);

    return (bool)$data[0]['result'];
  }

  public function clearRegister()
  {
    $this->models_register = array();
  }

    public function getListByQuery($sql)
    {
        $data = $this->db->query($sql);

        return $this->initList($data);
    }

  public function getListWithLimit($limit)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . $this->table_name . '`
                    LIMIT ' . (int)$limit;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getListWithPaging($page = 1, $by_page = 5)
  {
    $limit = $by_page;
    $offset = ($page - 1) * $by_page;

    $sql = 'SELECT ' . $this->selected_fields . '
					FROM `' . $this->table_name . '`
					LIMIT ' . (int)$offset . ', ' . (int)$limit;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getRandomListWithLimit($limit)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . $this->table_name . '`
                    ORDER BY RAND()
                    LIMIT ' . (int)$limit;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function deleteAll()
  {
    $sql = 'DELETE FROM ' . $this->table_name;
    $this->db->query($sql);
  }

  public function resetAutoIncrement()
  {
    $sql = 'ALTER TABLE ' . $this->table_name . ' auto_increment=1';
    $this->db->query($sql);
  }

  public function truncateTable()
  {
    $sql = 'TRUNCATE TABLE ' . $this->table_name;
    $this->db->query($sql);
  }


  public function getIdList()
  {
    $sql = 'SELECT ' . $this->id_field_name . '
					FROM ' . $this->table_name;

    $data = $this->db->query($sql);;

    $result = array();

    if ($data)
      foreach ($data as $v) {
        $result[] = $v['id'];
      }

    return $result;
  }


  public function createModel()
  {
    return new $this->model_name();
  }

  public function getIterator()
  {
    $iterator = new ModelIterator();
    $iterator->setManager($this);

    return $iterator;
  }

  public function getTableName()
  {
    return $this->table_name;
  }

  public function getListByModelSearchCriteria(ModelSearchCriteria $model_search_criteria)
  {
    $search_params = $model_search_criteria->getSearchParams();

    if (!$search_params) {
      $search_params = new SearchParams();
    }

    if ($model_search_criteria->page) {
      $search_params->setPagingParams($model_search_criteria->page, $model_search_criteria->by_page);
    }

    return $this->getListBySearchParams($search_params);
  }

  public function getCountByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    $criteria = clone $criteria;
    $criteria->by_page = null;
    $criteria->page = null;
    return count($this->getListByModelSearchCriteria($criteria));
  }

  public function getListByIds(array $ids)
  {
    if (!count($ids)) {
      return array();
    }

    $sql = 'SELECT ' . $this->selected_fields . '
					FROM `' . $this->table_name . '`
					WHERE id IN (' . join(', ', $ids) . ')
					ORDER BY FIELD (`id`, ' . join(', ', $ids) . ')';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function insertList(array $data, array $fields)
  {
    if (!$data || !$fields)
      return;

    $sql = 'INSERT INTO ' . $this->table_name . ' (' . join(', ', $fields) . ')
					VALUES ';

    foreach ($data as $entry_data) {
      $sql .= '(';

      foreach ($fields as $field_name) {
        $sql .= $entry_data[$field_name] . ', ';
      }
      $sql = trim($sql, ', ');

      $sql .= '), ';
    }
    $sql = trim($sql, ', ');

    $this->db->query($sql);
  }

  public function filter($model)
  {
    /**
     * @var DynamicModel $model
     */
    if (!$model->isNeedToFilter()) {
      return;
    }

    if (!count($this->db_fields)) {
      $this->getDbFields();
    }

    foreach ($this->db_fields as $field) {
      $model->{$field->getName()} = XssHelper::check($model->{$field->getName()});
    }
  }
}