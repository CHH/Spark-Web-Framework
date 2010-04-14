<?php

class Spark_Event_Dispatcher implements Spark_Event_DispatcherInterface
{
  
  /**
   * Contains the events and their callbacks
   * @var mixed
   */
  protected $_registry;
  
  /**
   * Instance of the class for singleton
   * @var Spark_Event_Dispatcher
   */
  protected static $_instance;
  
  /**
   * Protected Constructor to prevent instantiation
   */
  protected function __construct() 
  {}
  
  /**
   * Private clone method to prevent duplication
   */
  private function __clone() 
  {}
  
  /**
   * Get an instance of the class
   * @return Spark_Event_Dispatcher
   */
  public static function getInstance()
  {
    if( null === self::$_instance ) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }
  
  /**
   * Connects a callback to an event
   * @param  string $event     Name of the event
   * @param  mixed  $callback  Could be an Object implementing the 
   *                           Spark_Event_Handler_Interface, the name of a global
   *                           callback function or a closure (as of PHP > 5.3.0)
   *
   * @return Spark_Event_Dispatcher Providing a fluent interface
   */
  public function on($event, $callback)
  {
    if(!is_string($event)) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }
    
    if(!$this->hasCallbacks($event) and 
       !$this->_registry[$event] instanceof Spark_Event_HandlerQueue) 
    {
      $this->_registry[$event] = new Spark_Event_HandlerQueue;
    }
    
    $this->_registry[$event]->enqueue($callback);
    
    return $this;
  }
  
  /**
   * Triggers (fires) an event
   * @param  string $event   Name of the Event which should be triggered
   * @param  object $context The Object context in which the Event got triggered
   *
   * @return Spark_Event_Event Contains status information about the event
   */
  public function trigger($event, $context = null, $eventObj = null)
  {
    if(!is_string($event)) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }
    
    if(is_null($eventObj)) {
      $eventObj = new Spark_Event_Event;
    }
    
    $eventObj->setName($event);
    
    if(!is_null($context)) {
      $eventObj->setContext($context);
    }
    
    if($this->hasCallbacks($event)) {
      
      foreach($this->_registry[$event] as $callback) {
        if($callback instanceof Spark_Event_HandlerInterface) {
          $callback->handleEvent($eventObj);
          
        } elseif($callback instanceof Closure) {
          $callback($eventObj);
          
        } elseif(is_string($callback) and function_exists($callback)) {
          call_user_func_array($callback, array($eventObj));
          
        } else {
          throw new Spark_Event_InvalidHandlerException("The handler must be an object
            implementing the Spark_Event_HandlerInterface, a Lamda Function, Closure or 
            the name of a defined callback function");
        }
      }
    }
    
    return $eventObj;
  }
  
  /**
   * Checks if callbacks were registered for a certain event
   * @param  string  event
   * @return boolean
   */
  public function hasCallbacks($event)
  {
    if( !is_string($event) ) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }
    return isset($this->_registry[$event]) ? true : false;
  }
  
}

?>