<?php

class Spark_Object_Manager
{
  
  static protected $_bindings = array();
  static protected $_singletons = array();
  
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
      if(is_array($args[1])) {
        $options = $args[1];
      } elseif(self::hasBinding($className)) {
        $options = self::getBinding($className)->getOptions();
      }

      return $class->newInstance($options);
    }
    
    return $class->newInstanceArgs($args);
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