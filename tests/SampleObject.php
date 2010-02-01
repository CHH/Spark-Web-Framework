<?php

class SampleObject implements Spark_UnifiedConstructorInterface
{
  
  protected $_fooBar;
  protected $_fooBarBaz;
  
  public function __construct($options = null)
  {
    $this->setOptions($options);
  }
  
  public function setOptions($options)
  {
    Spark_Object_Options::setOptions($this, $options);
  }
  
  public function setFooBar($fooBar)
  {
    $this->_fooBar = $fooBar;
  }
  
  public function getFooBar()
  {
    return $this->_fooBar;
  }
  
  public function setFooBarBaz($fooBarBaz)
  {
    $this->_fooBarBaz = $fooBarBaz;
  }
  
  public function getFooBarBaz()
  {
    return $this->_fooBarBaz;
  }
  
}