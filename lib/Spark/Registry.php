<?php

class Spark_Registry
{
  
  static protected $_registry = array();
  
  static public function set($key, $value)
  {
    self::$_registry[$key] = $value;
  }
  
  static public function get($key)
  {
    if(self::has($key) {
      return self::$_registry[$key];
    }
    return null;
  }
  
  static public function has($key)
  {
    return array_key_exists($key, self::$_registry) ? true : false;
  }
  
}