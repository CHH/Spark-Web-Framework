<?php

class Spark_Event_HandlerQueue implements Countable, Iterator
{
  /**
   * Contains the queue items
   * @var array
   */
  protected $_callbacks = array();
  
  /**
   * State of the next item (for Iterator interface)
   * @var boolean
   */
  protected $_valid = false;
  
  /**
   * Adds the item to the end of the queue
   * @param  mixed $item
   * @return Spark_Event_Handler_Queue Providing a fluent interface
   */
  public function enqueue($callback)
  {
    $this->_callbacks[] = $callback;
    return $this;
  }
  
  /**
   * Removes an item from the front of a queue and returns it
   * @return mixed The dequeued item
   */
  public function dequeue()
  {
    
    $item = array_shift($this->_callbacks);
    
    return $item;
  }
  
  /**
   * Required by the Countable interface
   * @return int
   */
  public function count()
  {
    return count($this->_callbacks);
  }
  
  /**
   * Required by the Iterator interface
   * @return mixed
   */
  public function current()
  {
    return current($this->_callbacks);
  }
  
  /**
   * Required by the Iterator interface
   * @return void
   */
  public function next()
  {
    $this->_valid = (false !== next($this->_callbacks));
  }
  
  /**
   * Required by the Iterator interface - Returns the array key
   * @return mixed
   */
  public function key()
  {
    return key($this->_callbacks);
  }
  
  /**
   * Required by the Iterator interface
   * @return boolean
   */
  public function valid()
  {
    return $this->_valid;
  }
  
  /**
   * Required by the Iterator interface
   * @return void
   */
  public function rewind()
  {
    $this->_valid = (false !== reset($this->_callbacks));
  }

}

?>