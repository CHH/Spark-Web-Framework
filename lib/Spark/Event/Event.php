<?php

class Spark_Event_Event
{
  
  /**
   * Name of the event
   * @var string
   */
  protected $_name;
  
  /**
   * Context in which event got triggered
   * @var object
   */
  protected $_context = null;
  
  /**
   * Timestamp for the occurence of the event
   * @var int
   */
  protected $_timestamp;
  
  /**
   * Constructor
   * @param  string $event
   * @param  mixed  $message
   * @param  int    $timestamp
   *
   * @return Spark_Event_Event
   */
  public function __construct()
  {
    $this->_timestamp = time();
  }
  
  /**
   * Sets the event name
   * @param  string $eventName
   * @return Spark_Event_Event Providing a fluent interface
   */
  public function setName($eventName)
  {
    $this->_name = $eventName;
    return $this;
  }
  
  /**
   * Returns the name of the event
   * @return string
   */
  public function getName()
  {
    return $this->_name;
  }
  
  public function hasName()
  {
    return ($this->_name != null) ? true : false;
  }
  
  public function setContext($context)
  {
    $this->_context = $context;
    return $this;
  }
  
  public function getContext()
  {
    return $this->_context;
  }
  
  public function hasContext()
  {
    return ($this->_context !== null) ? true : false;
  }
  
  /**
   * Sets the timestamp of the occurence of the event
   * @param  int $timestamp
   * @return Spark_Event_Event Providing a fluent interface
   */
  protected function _setTimestamp($timestamp)
  {
    $this->_timestamp = $timestamp;
    return $this;
  }
  
  /**
   * Returns the timestamp of the event occurence
   * @return int
   */
  public function getTimestamp()
  {
    return $this->_timestamp;
  }
  
  
}

?>