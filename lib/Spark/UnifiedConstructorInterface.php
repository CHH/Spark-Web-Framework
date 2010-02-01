<?php

interface Spark_UnifiedConstructorInterface
{
  /**
   * __construct()
   *
   * @param mixed $options Usually an Array of underscored_key => value pairs 
   *                       representing the options
   * @return object An instance of the class
   */
  public function __construct($options = null);
  
  /**
   * setOptions()
   *
   * @param $options Options which should be set on the object
   *
   */
  public function setOptions($options);
  
}