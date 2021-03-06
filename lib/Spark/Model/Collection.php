<?php
/**
 * Base Class for Collections, which can be sorted and iterated over
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Model
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Model
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Model_Collection implements IteratorAggregate, ArrayAccess, Countable
{
  const SORT_ASC  = "asc";
  const SORT_DESC = "desc";
  
  /**
   * @var array
   */
  protected $_data = array();
  
  /**
   * @var int
   */
  protected $_pos = 0;
  
  /**
   * @var $_sortField Used to pass the name of the entity property on which the 
   *                  sorting should be done
   */
  protected $_sortField = null;
  
  public function __construct(array $data = array()) 
  {
    $this->_data = $data;
  }
  
  public function prepend($data)
  {
    $this->_data = $this->_merge($data, $this->_data);
    return $this;
  }
  
  public function append($data) 
  {
    $this->_data = $this->_merge($this->_data, $data);
    return $this;
  }

  public function first()
  {
    reset($this->_data);
    return current($this->_data);
  }
  
  public function last()
  {
    return end($this->_data);
  }
  
  public function sortBy($field, $mode = self::SORT_ASC)
  {
    $mode = strtolower($mode);
    
    $this->_sortField = $field;
    
    switch($mode) {
      case self::SORT_ASC:
        uasort($this->_data, array($this, "_sortAsc"));
      break;
      
      case self::SORT_DESC:
        uasort($this->_data, array($this, "_sortDesc"));
      break;
    }
    
    unset($this->_sortField);
    
    return $this;
  }

  public function count()
  {
    return sizeof($this->_data);
  }
  
  public function getIterator()
  {
    return new ArrayIterator($this->_data);
  }
  
  protected function _sortAsc($a, $b)
  {
    $field = $this->_sortField;
    
    $a = $a->$field;
    $b = $b->$field;
    
    if($a == $b) {
      return 0;
    }
    
    return ($a > $b) ? 1 : -1;
  }
  
  protected function _sortDesc($a, $b)
  {
    $field = $this->_sortField;
    
    $a = $a->$field;
    $b = $b->$field;
    
    if($a == $b) {
      return 0;
    }
    
    return ($a > $b) ? -1 : 1;
  }
  
  public function toArray()
  {
    return $this->_data;
  }
  
  public function offsetSet($offset, $value)
  {
    if($offset == null) {
      $this->_data[] = $value;
    } else {
      $this->_data[$offset] = $value;
    }
  }
  
  public function offsetGet($offset)
  {
    return $this->_data[$offset];
  }
  
  public function offsetExists($offset)
  {
    return isset($this->_data[$offset]);
  }
  
  public function offsetUnset($offset)
  {
    unset($this->_data[$offset]);
  }
  
  
  protected function _merge($data1, $data2)
  {
    if($data1 instanceof Spark_Model_Collection) {
      $data1 = $data1->toArray();
    }
    
    if($data2 instanceof Spark_Model_Collection) {
      $data2 = $data2->toArray();
    }
    
    array_values($data1);
    array_values($data2);
    
    return array_merge($data1, $data2);
  }
  
}

