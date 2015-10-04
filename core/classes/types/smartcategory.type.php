<?php

    class SmartcategoryType extends Type
    {

        public function getFormValue($val = '')
        {
            $db = Register::get('db');

            $valid = @$val[$this->fieldName];

            $szNameField = $this->fieldInfo['cross_name'];
            $szIndex = $this->fieldInfo['cross_index'];
            $szTable = $this->fieldInfo['cross_table'];
            $szOrder = $this->fieldInfo['ordered'];

            $first_field = $this->fieldInfo['f_select'];
            $second_field = $this->fieldInfo['t_select'];
            $first_name = $this->fieldInfo['name_f'];
            $two_name = $this->fieldInfo['name_t'];

            if (!empty($this->fieldInfo['cross_cond'])) {
                $szCond = ' WHERE ' . $this->fieldInfo['cross_cond'];
                if (Acl::userGrant(str_replace(DB_PREFIX, '', $this->table) . '_list_my')) {
                    if (!empty($this->fieldInfo['cross_cond_grant'])) {
                        $szCond .= ' ' . $this->fieldInfo['cross_cond_grant'];
                    }
                }
            } elseif (!empty($this->fieldInfo['cross_group'])) {
                $szCond = ' WHERE `' . $this->fieldInfo['cross_parent'] . '`="' . $data->nID . '"';
            } else $szCond = '';

            if (!empty($szOrder)) {
                $szCond .= ' ORDER BY `' . $szOrder . '`';
            } else {
                $szCond .= ' ORDER BY `' . $szIndex . '`';
            }


            $aResult = array();
            $aData = $db->query('SELECT ' . '`' . $szNameField . '`,`' . $szIndex . '` FROM `' . $szTable . '`' . $szCond);

            $aParse = array();
            $result = '<select name="form[' . $this->getFieldName() . ']" id="fselect" style="width:50%">';

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
            foreach ($aData as $key=> $value) {

                if ($_REQUEST['fk'] == $value[$szIndex])
                    $selected = 'selected';
                elseif ($value[$szIndex] == $this->value)
                    $selected = 'selected'; elseif ($value[$szIndex] == $valid)
                    $selected = 'selected'; else
                    $selected = '';

                $result .= '<option value="' . $value[$szIndex] . '" ' . $selected . '>' . htmlspecialchars(str_replace('<br />', '', $value[$szNameField])) . '</option>';
            }
            $result .= "</select>";

            $result .= '<tr>
                            <td class="label">' . $first_name . ':</td>
                            <td><select id="sselect" name="form[' . $first_field . ']" style="width:50%"></select></td>
                        </tr>';

            $result .= '<tr>
                            <td class="label">' . $two_name . ':</td>
                            <td><select id="tselect" name="form[' . $second_field . ']" style="width:50%"></select></td>
                        </tr>';

            $result .= '<script type="text/javascript">
                        
                        $(document).ready(function(){
                            
                            ajaxNew("/ajax/getBrandByCategory", {category_id: $("#fselect").val()}, "sselect"); 
                            
                            $("#fselect").change(function(){
                                ajaxNew("/ajax/getBrandByCategory", {category_id: $(this).val()}, "sselect"); 
                            });

                            $("#sselect").change(function(){
                                $("#form[' . $first_field . ']").val($(this).val());
                                ajaxNew("/ajax/getProductsByBrand", {brand_id: $(this).val()}, "tselect"); 
                            });

                            $("#tselect").change(function(){
                                $("#form[' . $second_field . ']").val($(this).val());
                            });
                        });
                        
                    </script>
                    ';
            return $result;
        }

        public function getViewValue()
        {
            $current = $this->getValue();
            if (empty($current)) {
                if (isset($this->fieldInfo['if_null']))
                    return $this->fieldInfo['if_null'];
                else
                    return NULL;
            }
            $db = Register::get('db');
            $szTable = $this->fieldInfo['cross_table'];
            $szIndex = $this->fieldInfo['cross_index'];
            $szNameField = $this->fieldInfo['cross_name'];

            $aResult = $db->query('SELECT * FROM `' . $szTable . '` WHERE `' . $szIndex . '`=' . $this->value . ' LIMIT 0,1');
            $szDefault = isset($this->fieldInfo['default']) ? $this->fieldInfo['default'] : '&nbsp;';
            $szResult = (!empty($aResult[0][$szNameField]) ? $aResult[0][$szNameField] : $szDefault);

            if (!$szResult && (isset($this->fieldInfo['if_null'])))
                $szResult = $this->fieldInfo['if_null'];


            return $szResult;
        }

    }