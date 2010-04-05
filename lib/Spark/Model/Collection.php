<?php

class Spark_Model_Collection implements Iterator, ArrayAccess
{
  
  protected $_data = array();
  
  /**
   * @var int
   */
  protected $_pos = 0;
  
  protected $_sortField = null;
  
  public function __construct($data = array()) {
    $this->_data = $data;
  }
  
  public function sort($field, $mode = "asc")
  {
    $mode = strtolower($mode);
    
    $this->_sortField = $field;
    
    switch($mode) {
      case "asc":
        uasort($this->_data, array($this, "_sortAsc"));
      break;
      
      case "desc":
        uasort($this->_data, array($this, "_sortDesc"));
      break;
    }
    
    unset($this->_sortField);
    
    return $this;
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
  
  /**
   * current() - Returns the value of the current key as defined in Iterator Interface
   *
   * @return mixed
   */
  public function current()
  {
    return current($this->_data);
  }

  /**
   * rewind() - Resets the position in the array as defined in Iterator Interface
   */
  public function rewind()
  {
    $this->_pos = 0;
    reset($this->_data);
  }

  /**
   * valid() - Checks if the current position has a valid item
   * 
   * @return bool
   */
  public function valid()
  {
    return ($this->_pos < sizeof($this->_data));
  }

  /**
   * next() - Moves to the next position within the array
   */
  public function next()
  {
    $this->_pos++;
    next($this->_data);
  }

  /**
   * key() - Returns the key at the current position
   * @return mixed
   */
  public function key()
  {
    return key($this->_data);
  }
}
