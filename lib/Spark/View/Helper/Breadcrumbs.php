<?phpclass Spark_View_Helper_Breadcrumbs extends Zend_View_Helper_Abstract{  protected $_separator = " / ";    protected $_breadcrumbs;    public function breadcrumbs()  {    return $this;  }    public function __toString()  {    $breadcrumbs = $this->_getSessionNamespace();        $html = "";        $itemsCount = count($breadcrumbs->items);    foreach($breadcrumbs->items as $crumb=>$url) {      if(is_array($url)) {        $url = $this->view->url($url);      }            $html .= $this->_separator;            $itemsCount--;            if($itemsCount>0) {        $html .= "<a href=\"$url\" class=\"breadcrumb\">$crumb</a>";      } else {        $html .= $crumb;      }          }        return $html;  }    public function add($crumb,$url)  {    $breadcrumbs = $this->_getSessionNamespace();        if(!isset($breadcrumbs->items[$crumb])) {      $breadcrumbs->items[$crumb] = $url;    } else {      $array = array_reverse($breadcrumbs->items);      foreach($array as $old=>$url) {        if($old == $crumb) {          break;        } else {          unset($breadcrumbs->items[$old]);        }      }    }        return $this;  }    public function setSeparator($separator) {    $this->_separator = $separator;    return $this;  }    protected function _getSessionNamespace()  {    if(null === $this->_breadcrumbs) {      $this->_breadcrumbs = new Zend_Session_Namespace('breadcrumbs');    }    return $this->_breadcrumbs;  }    public function reset()  {    unset($this->_getSessionNamespace()->items);  }}?>