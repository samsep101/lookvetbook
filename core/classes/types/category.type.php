<?php

    class CategoryType extends Type
    {

        public function getFormValue($val = '', $model = false)
        {
            $valid = $val;

            $manager = ModelManagerFactory::getByName($this->fieldInfo['cross_table']);

            $search_params = new SearchParams();

            if (isset($this->fieldInfo['sort_by']))
                $search_params->addSortParam($this->fieldInfo['sort_by'], 'ASC');
            if (isset($this->fieldInfo['where']))
                $search_params->addParam($this->fieldInfo['where']['param'], $this->fieldInfo['where']['value']);

			$style = isset($this->fieldInfo['style']) ? $this->fieldInfo['style'] : 'width: 50%';

            if (isset($this->fieldInfo['search_params']) && $model)
            {
                foreach ($this->fieldInfo['search_params'] as $param => $field_name){
                    if ($param == 'join'){
                        $search_params->addJoin($field_name);
                    } else {
                        if($field_name) {
                            if(is_array($field_name)) {
                                $search_params->addParam($field_name['field_name'], $field_name['value']);
                            } else {
                                $search_params->addParam($param,$model->{$field_name});
                            }
                        } else {
                            $search_params->addParam($param,$model->{$field_name});
                        }
                    }
                }
            }

            $aData = $manager->getListBySearchParams($search_params);

            $result = '<select name="form[' . $this->getFieldName() . ']" style="'.$style.'">';

            if (!empty($this->fieldInfo['first'])) {
                foreach ($this->fieldInfo['first'] as $key=> $value) {
                    if ($value == $this->value)
                        $selected = 'selected';
                    elseif ($value == $valid)
                        $selected = 'selected'; else
                        $selected = '';
                    $result .= '<option value="' . $key . '" ' . $selected . '>' . htmlspecialchars($value) . '</option>';
                }
            }
            foreach ($aData as $value) {

                if ($value->getId() == $val)
                    $selected = 'selected';
                else
                    $selected = '';

                $result .= '<option value="' . $value->getId() . '" ' . $selected . '>' . htmlspecialchars(str_replace('<br />', '', $value->{$this->fieldInfo['cross_name']})) . '</option>';
            }
            $result .= "</select>";
            if (isset($this->fieldInfo['script']))
            {
                $result .= '<script>'.$this->fieldInfo['script'].'</script>';
            }
            return $result;
        }



        public function getViewValue($value)
        {
            $model = ModelManagerFactory::getByName($this->fieldInfo['cross_table'])->getOneById($value);
            if (!$model) {
                if (isset($this->fieldInfo['if_null']))
                    return $this->fieldInfo['if_null'];
                else
                    return NULL;
            }

            $field = $this->fieldInfo['cross_name'];
            return '<a href="' . ADMIN_FOLDER . '/' . $this->fieldInfo['cross_table'] . '/edit?id=' . $model->getId() . '" target="_blank">' . $model->$field . '</a>';
        }

    }