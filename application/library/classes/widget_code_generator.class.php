<?php
    class WidgetCodeGenerator
    {
        public function generate(WidgetModel $widget)
        {
            $text = $widget->code;
            file_put_contents('./media/widget/'.$widget->identifier.'.js', $text);
        }
    }