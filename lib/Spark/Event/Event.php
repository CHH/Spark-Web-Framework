<?php

class Spark_Event
{
  /**
   * Event was accepted by the handler
   */
  const ACCEPTED = 2;
  
  /**
   * Event was not accepted by the handler
   */
  const REJECTED = -2;
  
  /**
   * Event was not dispatched by the dispatcher (no callback registered)
   */ 
  const NOT_DISPATCHED = -3;
  
  /**
   * Execution of the event was successful
   */
  const SUCCESS = 1;
  
  /**
   * Execution produced a failure
   */
  const FAILURE = 0;
  
  /**
   * Execution got canceled
   */
  const CANCELED = -1;
  
  /**
   * Name of the event
   * @var string
   */
  protected $_event;
  
  /**
   * Data for the callback
   * @var mixed
   */
  protected $_message;
  
  /**
   * Timestamp for the occurence of the event
   * @var int
   */
  protected $_timestamp;
  
  /**
   * Contains the transmission state of the event
   * Could have the states ACCEPTED, REJECTED or NOT_DISPATCHED
   * @var int
   */
  protected $_transmissionState = self::NOT_DISPATCHED;
  
  /**
   * Contains the executionState of the event
   * States: SUCCESS, FAILURE, CANCELED
   * @var int
   */
  protected $_executionState = self::FAILURE;
  
  /**
   * Constructor
   * @param  string $event
   * @param  mixed  $message
   * @param  int    $timestamp
   *
   * @return Spark_Event_Event
   */
  public function __construct($event, $message, $timestamp)
  {
    $this->_setName($event);
    $this->_setMessage($message);
    $this->_setTimestamp($timestamp);
  }
  
  /**
   * Sets the event name
   * @param  string $eventName
   * @return Spark_Event_Event Providing a fluent interface
   */
  protected function _setName($eventName)
  {
    $this->_eventName = $eventName;
    return $this;
  }
  
  /**
   * Returns the name of the event
   * @return string
   */
  public function getName()
  {
    return $this->_eventName;
  }
  
  /**
   * Sets the message (data) for the callbacks
   * @param  mixed $message
   * @return Spark_Event_Event Providing a fluent interface
   */
  protected function _setMessage($message)
  {
    $this->_message = $message;
    return $this;
  }
  
  /**
   * Returns the attached data
   * @return mixed
   */
  public function getMessage()
  {
    return $this->_message;
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
  
  /**
   * Sets the execution state of the event
   * @param  int $state one of the constants SUCCESS, FAILURE or CANCELED
   * @return Spark_Event_Event Providing a fluent interface
   */
  public function setExecutionState($state)
  {
    switch($state) {
      case self::SUCCESS:
      case self::CANCELED:
      case self::FAILURE:
        $this->_executionState = $state;
        break;
      default:
        throw new InvalidArgumentException("Argument must be one of the constants
          SUCCESS, CANCELED or FAILURE");
    }
    
    return $this;
  }
  
  /**
   * Returns the execution state of the event
   * @return int
   */
  public function getExecutionState()
  {
    return $this->_executionState;
  }
  
  /**
   * Sets the transmission state of the event
   * @param  int $state one of the constants ACCEPTED, REJECTED or NOT_DISPATCHED
   * @return Spark_Event_Event Providing a fluent interface
   */
  public function setTransmissionState($state)
  {
    switch($state) {
      case self::ACCEPTED:
      case self::REJECTED:
      case self::NOT_DISPATCHED:
        $this->_executionState = $state;
        break;
      default:
        throw new InvalidArgumentException("Argument must be one of the constants
          ACCEPTED, REJECTED or NOT_DISPATCHED");
    }

    return $this;
  }
  
  /**
   * Returns the transmission state of the event
   * @return int
   */
  public function getTransmissionState()
  {
    return $this->_transmissionState;
  }
  
  /**
   * Sets the transmission state to ACCEPTED
   * @return Spark_Event_Event Providing a fluent interface
   */
  public function accept()
  {
    $this->_transmissionState = self::ACCEPTED;
    return $this;
  }
  
  /**
   * Sets the transmission state to REJECTED
   * @return Spark_Event_Event Providing a fluent interface
   */
  public function reject()
  {
    $this->_transmissionState = self::REJECTED;
    return $this;
  }
  
  /**
   * Sets the execution state to CANCELED
   * @return Spark_Event_Event Providing a fluent interface
   */
  public function cancel()
  {
    $this->_executionState = self::CANCELED;
    return $this;
  }
  
  /**
   * Checks if the transmission state of the event is ACCEPTED
   * @return boolean
   */
  public function wasAccepted()
  {
    return ( self::ACCEPTED === $this->_transmissionState );
  }
  
  /**
   * Checks if the transmission state of the event is REJECTED
   * @return boolean
   */
  public function wasRejected()
  {
    return ( self::REJECTED === $this->_transmissionState );
  }
  
  /**
   * Checks if the execution state of the event is SUCCESSFUL
   * @return boolean
   */
  public function wasSuccessful()
  {
    return ( self::SUCCESS === $this->_executionState );
  }
  
  /**
   * Checks if the execution state of the event is CANCELED
   * @return boolean
   */
  public function wasCanceled()
  {
    return ( self::CANCELED === $this->_executionState );
  }
  
  /**
   * Checks if the event got dispatched by the dispatcher
   * @return boolean
   */
  public function wasDispatched()
  {
    return ( self::NOT_DISPATCHED !== $this->_transmissionState );
  }
  
}

?>