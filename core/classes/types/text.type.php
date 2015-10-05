<?php

    class TextType extends Type
    {

        public function getViewValue($val)
        {
        	if ($val)
            	return mb_substr(htmlspecialchars($val), 0, 50, 'UTF-8').'...';
            return '';
        }

        public function getFormValue($val = '')
        {
            $result = '<textarea ';
            if (isset($this->fieldInfo['style'])) {
                $result .= ' style="' . $this->fieldInfo['style'] . '" ';
            } else {
                $result .= ' rows=12 ';
            }
            $result .= 'name="form[' . $this->fieldName . ']" ';
            if (isset($this->fieldInfo['class']))
                $result .= 'class="' . $this->fieldInfo['class'] . '" ';
            $result .= '>';

            if ($val)
                $result .= htmlspecialchars(str_replace('<br />', '', $val));

            $result .= '</textarea>';

            return $result;
        }

        public function getSaveValue($value)
        {
            if (!empty($value)) {
                return trim(Register::get('db')->escape($value));
            } else {
                return null;
            }
        }

    }