<?php
/**
 * The Command Resolver, resolves the request to a command Instance by 
 * module, command and action parameters
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
class Spark_Controller_StandardResolver 
  implements Spark_Controller_Resolver, Spark_Configurable
{
  
  protected $_controllerDirectory;
  
  protected $_defaultControllerName = "Default";
  
  protected $_controllerSuffix = "Controller";
  
  protected $_moduleDirectory;
  
  protected $_moduleControllerDirectory = "controllers";
  
  public function __construct(array $options = array())
  {
    if(!is_null($options)) {
      $this->setOptions($options);
    }
  }
  
  public function setOptions(array $options)
  {
    Spark_Options::setOptions($this, $options);
    return $this;
  }
  
  public function getInstance(Zend_Controller_Request_Abstract $request)
  { 
    if(!is_null($request->getModuleName())) {
      $controller = $this->_loadCommand($request->getControllerName(), $request->getModuleName());
      return $controller;
      
    } elseif(!is_null($request->getControllerName())) {
      $controller = $this->_loadCommand($request->getControllerName());
      return $controller;
      
    } else {
      return $this->_loadCommand($this->getDefaultControllerName());
    }
  }
  
  public function getControllerByName($controllerName, $moduleName = null)
  {
    return $this->_loadCommand($controllerName, $moduleName);
  }
  
  protected function _loadCommand($controllerName, $moduleName = null)
  {
    $className = ucfirst($controllerName) . $this->getControllerSuffix();
    
    if($moduleName) {
      $path = $this->getModuleDirectory() . DIRECTORY_SEPARATOR 
            . $moduleName . DIRECTORY_SEPARATOR
            . $this->getModuleControllerDirectory() . DIRECTORY_SEPARATOR 
            . $className . ".php";
      
      $className = $this->getControllerPrefix($moduleName) . $className;
      
    } else {
      $path = $this->getControllerDirectory() . DIRECTORY_SEPARATOR
              . $className . ".php";
    }
    
    if(!file_exists($path)) {
      return false;
    }
    
    include_once $path;
    
    if(!class_exists($className, false)) {
      return false;
    }
    
    $controller = new $className;
    
    if(!($controller instanceof Spark_Controller_Controller)) {
      return false;
    }
    return $controller;
  }
  
  public function getControllerDirectory()
  {
    return $this->_controllerDirectory;
  }
  
  public function setControllerDirectory($controllerDirectory)
  {
    $this->_controllerDirectory = $controllerDirectory;
    return $this;
  }
  
  public function getControllerSuffix()
  {
    return $this->_controllerSuffix;
  }
  
  public function setControllerSuffix($suffix)
  {
    $this->_controllerSuffix = $suffix;
    return $this;
  }
  
  public function getDefaultControllerName()
  {
    return $this->_defaultControllerName;
  }
  
  public function setDefaultControllerName($controllerName)
  {
    $this->_defaultControllerName = $controllerName;
    return $this;
  }
  
  public function getModuleDirectory()
  {
    return $this->_moduleDirectory;
  }
  
  public function setModuleDirectory($directory)
  {
    $this->_moduleDirectory = $directory;
    return $this;
  }
  
  public function getModuleControllerDirectory()
  {
    return $this->_moduleControllerDirectory;
  }
  
  public function setModuleControllerDirectory($directory)
  {
    $this->_moduleControllerDirectory = $directory;
    return $this;
  }
  
  public function getControllerPrefix($module)
  {
    $prefix = str_replace(" ", null, ucwords(str_replace("_", " ", $module))) . "_";
    return $prefix;
  }
}
