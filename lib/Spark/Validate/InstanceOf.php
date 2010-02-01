<?php

class Spark_Validate_InstanceOf extends Zend_Validate_Abstract
{
  
  const NOT_INSTANCE = "notInstanceOf";
  
  protected $_messageTemplates = array(
    self::NOT_INSTANCE => "'%value%' is not an Instance of '%class%'"
  );
  
  protected $_messageVariables = array(
    'class' => '_class'
  );
  
  protected $_class;
  
  public function __construct($class)
  {
    $this->setClass($class);
  }
  
  public function getClass()
  {
    return $this->_class;
  }
  
  public function setClass($class)
  {
    $this->_class = $class;
    return $this;
  }
  
  public function isValid($value)
  {
    $this->_setValue($value);
    
    if(!($value instanceof $this->_class)) {
      $this->_error(self::NOT_INSTANCE);
      return false;
    }
    return true;
  }
  
}
