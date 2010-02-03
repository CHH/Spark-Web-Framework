<?php

class Spark_Object_Manager
{
  
  static protected $_bindings = array();
  static protected $_singletons = array();
  static protected $_config;
  
  /**
   * createInstance() - creates a new Instance of the class on every call
   *
   * @param string $className
   * @return object
   */
  static public function createInstance()
  {
    $args = func_get_args();
    $className = $args[0];
    unset($args[0]);
    
    return self::_instantiateClass($className, $args);
  }
  
  /**
   * getInstance() - Returns a shared Instance of a Class, 
   *  instantiates a new Object only on first call
   *
   * @param string $className
   * @return object
   */
  static public function getInstance()
  {
    $args = func_get_args();
    $className = $args[0];
    unset($args[0]);
    
    if(!isset(self::$_singletons[$className])) {
      self::$_singletons[$className] = self::_instantiateClass($className, $args);
    }
    
    return self::$_singletons[$className];
  }
  
  static protected function _instantiateClass($className, $args)
  {
    if(!$className) {
      throw new BadMethodCallException("The first argument must contain the classname, NULL given");
    }
    
    $class = new ReflectionClass($className);

    if($class->implementsInterface("Spark_UnifiedConstructorInterface")) {
      if(isset($args[1]) and is_array($args[1])) {
        $options = $args[1];
      } elseif(self::hasBinding($className)) {
        $options = self::getBinding($className)->getOptions();
      } elseif(isset(self::$_config[$className])) {
        $options = self::$_config[$className];
      } else {
        $options = null;
      }

      return $class->newInstance($options);
    }
    
    if(sizeof($args) > 0) {
      return $class->newInstanceArgs($args);
    }
    
    return $class->newInstance();
  }
  
  static public function getConfig()
  {
    return self::$_config;
  }
  
  /**
   * setConfig() - Sets a config array/object.
   * On Instantiation the classname is looked up in this config and the value
   * for the classname is used as options for the class
   * 
   * @param mixed $config
   */
  static public function setConfig($config)
  {
    if($config instanceof Zend_Config) {
      self::$_config = $config->toArray();
    } elseif(is_array($config)) {
      self::$_config = $config;
    } else {
      throw new InvalidArgumentException("Config must be an Instance of 
       Zend_Config or an Array");
    }
  }
  
  static public function addBinding(Spark_Object_OptionBinding $binding)
  {
    self::$_bindings[$binding->getClass()] = $binding;
  }
  
  static public function getBinding($class)
  {
    if(self::hasBinding($class)) {
      return self::$_bindings[$class];
    }
    return null;
  }
  
  static public function hasBinding($class)
  {
    return array_key_exists($class, self::$_bindings) ? true : false;
  }
}
