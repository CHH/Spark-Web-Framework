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
class Spark_Options_Binding
{
  /**
   * @var array Holds the options which should be bound
   */
  protected $_options   = null;
  
  /**
   * @var string Contains the name of the class this binding refers to
   */
  protected $_className = null;
  
  /**
   * setOptions() - Setter for the Options
   * @param mixed $options
   * @return Spark_Object_OptionBinding
   */
  public function setOptions($options)
  {
    $this->_options = $options;
    return $this;
  }
  
  /**
   * getOptions() - Getter for the options
   * @return mixed
   */
  public function getOptions()
  {
    return $this->_options;
  }
  
  /**
   * setClass() - Setter for the classname
   * @param string $className
   * @return Spark_Object_Optionbinding
   */
  public function setClass($className)
  {
    $this->_className = $className;
    return $this;
  }
  
  /**
   * getClass() - Getter for the classname
   * @return string
   */
  public function getClass()
  {
    return $this->_className;
  }
  
  /**
   * bind() - Alias for setOptions()
   * @see setOptions()
   */
  public function bind($options)
  {
    return $this->setOptions($options);
  }
  
  /**
   * to() - Alias for setClass()
   * @see setClass()
   */
  public function to($className)
  {
    return $this->setClass($className);
  }
  
}
