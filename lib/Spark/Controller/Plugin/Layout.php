<?php
/**
 * Wraps the Response with a Layout common to all views of the site
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Controller
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Controller
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Controller_Plugin_Layout 
  extends Spark_Controller_AbstractPlugin
  implements Spark_Configurable
{
  
  protected $_layoutName;
  protected $_layoutPath;
  protected $_layout;
  
  protected $_disabled = false;

  protected $defaults = array(
    "layout_name" => "layout.phtml",
  );
  
  public function __construct(array $options = array())
  {
    $this->setOptions(array_merge($this->defaults, $options));
  }

  public function setOptions(array $options)
  {
    Spark_Options::setOptions($this, $options);
    return $this;
  }
  
  public function postDispatch($request, $response)
  {
    if(!$this->_disabled) {
      $body = $response->getBody();
  
      $layout = $this->getLayout();
      $layout->content = $body;
      
      $response->setBody($layout->render($this->_layoutName));
    }
  }

  public function setLayoutName($layoutName)
  {
    $this->_layoutName = $layoutName;
    return $this;
  }

  public function getLayoutName()
  {
    return $this->_layoutName;
  }
  
  public function setLayoutPath($layoutPath)
  {
    $this->_layoutPath = $layoutPath;
    $this->getLayout()->setScriptPath($layoutPath);
    return $this;
  }
  
  public function getLayoutPath()
  {
    return $this->_layoutPath;
  }
  
  public function getLayout()
  {
    if(is_null($this->_layout)) {
      $this->_layout = new Zend_View;
      
      if(is_null($this->_layoutPath)) {
        throw new Spark_Controller_Exception("Please set the layout_path
          config directive for this filter");
      }
      
      $this->_layout->setScriptPath($this->_layoutPath);
    }
    
    return $this->_layout;
  }
  
  public function disableLayout($flag = true)
  {
    $this->_disabled = $flag;
    return $this;
  }
  
}
