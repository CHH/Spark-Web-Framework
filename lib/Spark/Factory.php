<?php
/**
 * Spark Framework
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Factory
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Factory
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Factory
{
  /**
   * @var array
   */
  protected $_bindings = array();

  /**
   * @var array
   */
  protected $_sharedInstances = array();
   
  /**
   * getInstance() - Returns a shared Instance of a Class, 
   *  instantiates a new Object only on first call
   *
   * @param string $className
   * @return object
   */
  public function getInstance($class, array $options = array())
  { 
    if(isset($this->_sharedInstances[$class])) {
      return $this->_sharedInstances[$class];
    }
    
    return $this->_instantiateClass($class, $options);
  }

  /**
   * _instantiateClass() - Does the heavy lifting in object instantiation
   *
   * @param string $className
   * @param array  $args Numerical indexed array, which contains the arguments
   *                     for the object constructor
   * @return object
   */
  protected function _instantiateClass($className, array $options = array())
  {
    if(!$className) {
      throw new BadMethodCallException("The first argument must contain the classname, NULL given");
    }
    
    
    if($this->hasBinding($className)) {
      $defaultOptions = $this->getBinding($className)->getOptions();
    } else {
      $defaultOptions = array();
    }

    $options = array_merge($defaultOptions, $options);

    $object  = new $className;

    if (!$object instanceof Spark_Configurable) {
      throw new UnexpectedValueException("Object does not implement the Configurable Interface");
    }

    $object->setOptions($options);
    
    return $object;
  }
  
  public function bind(array $options)
  {
    $binding = $this->createBinding();
    $this->addBinding($binding);
    
    $binding->bind($options);
    return $binding;
  }
  
  public function share($subject)
  {
    if (!is_string($subject) and !is_object($subject)) {
      throw new InvalidArgumentException("Supply either an Object or a Class
       which you want to add to the shared Instances");
    }
    
    if (is_string($subject) and class_exists($subject)) {
      $subject = $this->_instantiateClass($subject);
    }
    
    $this->_sharedInstances[get_class($subject)] = $subject;
  }
  
  public function shareNot($subject)
  {
    if (!is_string($subject) and !is_object($subject)) {
      throw new InvalidArgumentException("Supply either an Object or a Class
       which you want to remove from the shared Instances");
    }
    
    if (is_object($subject)) {
      $subject = get_class($subject);
    }
    
    unset($this->_sharedInstances[$subject]);
  }
  
  /**
   * addBinding() - Binds a set of options to a class through an Option Binding Object
   *
   * @param Spark_Object_OptionBinding $binding
   */
  public function addBinding(Spark_Options_Binding $binding)
  {
    $this->_bindings[$binding->getClass()] = $binding;
  }

  /**
   * getBinding() - Returns the Option Binding for the given Class
   *
   * @param string $class
   * @return Spark_Object_OptionBinding
   */
  public function getBinding($class)
  {
    if(!$this->hasBinding($class)) {
      throw new UnexpectedValueException("No Binding was registered for class {$class}");
    }
    return $this->_bindings[$class];
  }

  /**
   * hasBinding() - Checks if a Binding for the given Class exists
   *
   * @param string class
   * @return bool
   */
  public function hasBinding($class)
  {
    return array_key_exists($class, $this->_bindings) ? true : false;
  }
  
  public function createBinding()
  {
    return new Spark_Options_Binding;
  }
}
