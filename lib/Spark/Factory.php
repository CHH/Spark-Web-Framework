<?php
/**
 * Spark Framework
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Object
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Object
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Object_Manager
{
  /**
   * @var array
   */
  static protected $_bindings = array();

  /**
   * @var array
   */
  static protected $_sharedInstances = array();
    
  /**
   * getInstance() - Returns a shared Instance of a Class, 
   *  instantiates a new Object only on first call
   *
   * @param string $className
   * @return object
   */
  static public function getInstance($class, array $options = array())
  { 
    if(isset(self::$_sharedInstances[$class])) {
      return self::$_sharedInstances[$class];
    }
    
    return self::_instantiateClass($class, $options);
  }

  /**
   * _instantiateClass() - Does the heavy lifting in object instantiation
   *
   * @param string $className
   * @param array  $args Numerical indexed array, which contains the arguments
   *                     for the object constructor
   * @return object
   */
  static protected function _instantiateClass($className, array $options = array())
  {
    if(!$className) {
      throw new BadMethodCallException("The first argument must contain the classname, NULL given");
    }
    
    $class = new ReflectionClass($className);

    if(!$class->implementsInterface("Spark_UnifiedConstructorInterface")) {
      throw new InvalidArgumentException("{$className} does not implement the UnifiedConstructor Interface");
    }
    
	  if(self::hasBinding($className)) {
	    $defaultOptions = self::getBinding($className)->getOptions();
	  } else {
		  $defaultOptions = array();
	  }
	  
	  $options = array_merge($defaultOptions, $options);
	  
	  return $class->newInstance($options);
  }
  
  static public function bind(array $options)
  {
    $binding = self::createBinding();
    $this->addBinding($binding);
    
    $binding->bind($options);
    return $binding;
  }
  
  static public function share($subject)
  {
    if (!is_string($subject) and !is_object($subject)) {
      throw new InvalidArgumentException("Supply either an Object or a Class
       which you want to add to the shared Instances");
    }
    
    if (is_string($subject) and class_exists($subject)) {
      $subject = self::_instantiateClass($subject);
    }
    
    self::$_sharedInstances[get_class($subject)] = $subject;
  }
  
  static public function shareNot($subject)
  {
    if (!is_string($subject) and !is_object($subject)) {
      throw new InvalidArgumentException("Supply either an Object or a Class
       which you want to remove from the shared Instances");
    }
    
    if (is_object($subject)) {
      $subject = get_class($subject);
    }
    
    unset(self::$_sharedInstances[$subject]);
  }
  
  /**
   * addBinding() - Binds a set of options to a class through an OptionBinding Object
   *
   * @param Spark_Object_OptionBinding $binding
   */
  static public function addBinding(Spark_Object_OptionBinding $binding)
  {
    self::$_bindings[$binding->getClass()] = $binding;
  }

  /**
   * getBinding() - Returns the Option Binding for the given Class
   *
   * @param string $class
   * @return Spark_Object_OptionBinding
   */
  static public function getBinding($class)
  {
    if(self::hasBinding($class)) {
      return self::$_bindings[$class];
    }
    return null;
  }

  /**
   * hasBinding() - Checks if a Binding for the given Class exists
   *
   * @param string class
   * @return bool
   */
  static public function hasBinding($class)
  {
    return array_key_exists($class, self::$_bindings) ? true : false;
  }
  
  static public function createBinding()
  {
    return new Spark_Object_OptionBinding;
  }
}
