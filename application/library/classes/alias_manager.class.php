<?php

class AliasManager extends ModelManager
{
  protected $transliterated_field = 'name';

  protected function beforeSave(DynamicModel $model)
  {
    if ($this->transliterated_field && !$model->alias) {
      $alias = StringTransliterationHelper::translit($model->{$this->transliterated_field});

      if ($alias) {
        $alias_model = $this->getOneByAlias($alias);

        // тут генерируем уникальный alias
        $i = 0;
        $result_alias = $alias;
        while ($alias_model && ($alias_model->getId() != $model->getId())) {
          $result_alias = $alias . $i;
          $alias_model = $this->getOneByAlias($result_alias);
          $i++;
        }

        $model->alias = $result_alias;
      }
    }
  }

  /**
   * @param $alias
   *
   * @return CityModel
   */
  public function getOneByAlias($alias)
  {
    $data = $this->orm_model->select()->where('alias = ?', $alias)->fetchOne();
    return $this->initOne($data);
  }

  /**
   * @param $alias
   * @return DoctorModel
   */
  public function getOneByIdOrAlias($alias)
  {
    if (is_numeric($alias)) {
      return $this->getOneById((int)$alias);
    } else {
      return $this->getOneByAlias($alias);
    }
  }

  public function getOneByIdOrAliasAndIsActive($alias)
  {
    if (is_numeric($alias)) {
      $data = $this->orm_model->select()->where('id = ?', (int)$alias)->fetchOne();
      return $this->initOne($data);
    } else {
      return $this->getOneByAlias($alias);
    }
  }
}