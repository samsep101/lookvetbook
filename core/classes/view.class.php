<?php

class View extends Dynamic
{

  public $sufix = 'ViewHelper';

  private $__values;

  private $__extension = '.tpl';

  private $__layout;

  private $__template;

  /**
   * @var ViewFilter[]
   */
  private $view_filters = array();

  public function addViewFilter(ViewFilter $filter)
  {
    $this->view_filters[] = $filter;
  }

  public function __construct()
  {
    $this->__values = array();
  }

  public function __set($varName, $value)
  {
    $this->__values[$varName] = $value;
  }

  public function __get($varName)
  {
    if (!empty($this->__values[$varName]))
      return $this->__values[$varName];
    else
      return NULL;
  }

    private function filterCanonical(){
        $a = 1;
    }

  public function render($templateName)
  {
    ob_start();
      $this->filterCanonical();
    extract($this->__values);
    $this->__template = $templateName . $this->__extension;
    if (is_file($this->__layout)) {
      include($this->__layout);
    } else {
      include($this->__template);
    }
    $html = ob_get_contents();
    //ob_clean();
    ob_end_clean();

    echo $this->filter($html);
  }

    /**
     * Рендерим отображение из папки {application}/{templates}/_name_.tpl
     * - второй параметр позволяет изключить создание переменных в этой области видимости (по сути дублируются)
     * @param type $templateName
     * @param type $extractValues
     * @return type
     */
    public function renderInString($templateName, $extractValues = true)
    {
        ob_start();
        $extractValues AND extract($this->__values);
        $this->__template = Application::getTemplatesDir(TRUE) . '/' . $templateName . $this->__extension;
        include($this->__template);
        $html = ob_get_contents();
        ob_end_clean();

        return $this->filter($html);
    }

        public function block($templateName, $params = null)
        {
            if (0 && debug == 1)
                echo "<!--".$templateName."-->";

    extract($this->__values);
    if ($params)
      extract($params);
    $file = Application::getTemplatesDir(TRUE) . '/' . $templateName . $this->__extension;
    include($file);
  }

  public function content()
  {
    extract($this->__values);
    if(file_exists($this->__template)) {
      include($this->__template);
    }
  }

  public function clear()
  {
    $this->__values = array();
  }

  public function setLayout($templatePath)
  {
    $this->__layout = Application::getTemplatesDir(TRUE) . '/' . $templatePath . $this->__extension;
  }

  public function getLayout()
  {
    return $this->__layout;
  }

  public function getExtension()
  {
      return $this->__extension;
  }

  protected function filter($html)
  {
    if ($this->view_filters) {
      foreach ($this->view_filters as $filter) {
        $html = $filter->filter($html);
      }
    }

    return $html;
  }
}