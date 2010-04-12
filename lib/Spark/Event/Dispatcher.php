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
    
    if( !is_string($event) ) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }
    
    if( !$this->hasCallbacks($event) and 
        !$this->_registry[$event] instanceof Spark_Event_HandlerQueue ) 
    {
      $this->_registry[$event] = new Spark_Event_HandlerQueue;
    }
    
    $this->_registry[$event]->enqueue($callback);
    
    return $this;
  }
  
  /**
   * Triggers (fires) an event
   * @param  string $event   Name of the Event which should be triggered
   * @param  mixed  $message Optional message to be sent to the handler
   *
   * @return Spark_Event_Event Contains status information about the event
   */
  public function trigger($event, $message = null, $eventObject = null)
  {
    
    if( !is_string($event) ) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }
    
    if(is_null($eventObject)) {
      $eventObject = new Spark_Event($event, $message, time());
    }
    
    if( $this->hasCallbacks($event) ) {
      
      $queue = $this->_registry[$event];
      
      foreach( $queue as $callback ) {
        
        // Change the transmission state from NOT_DISPATCHED to ACCEPTED.
        // The Event could still be rejected by the callback after that.
        $eventObject->accept();
        
        try {
        
          if( $callback instanceof Spark_Event_HandlerInterface ) {
            $result = $callback->handleEvent($eventObject);
  
          } elseif( $callback instanceof Closure ) {
            $result = $callback($eventObject);
  
          } elseif( is_string($callback) and function_exists($callback) ) {
            $result = call_user_func_array($callback, array($eventObject));
  
          } else {
            throw new Spark_Event_InvalidHandlerException("The handler must be an object
              implementing the Spark_Event_HandlerInterface, an instance of the class
              Closure or the name of a defined callback function");
          }
          
          // The callback did not throw any Exception, therefore we assume it was successful
          $eventObject->setExecutionState(Spark_Event::SUCCESS);
          
          // Although if the return value of the callback was false, we assume
          // a failure occured
          if( Spark_Event::FAILURE === $result ) {
            $eventObject->setExecutionState(Spark_Event::FAILURE);
          }
          
        } catch( Spark_Event_Exception $e ) {
        
          $eventObject = $e->getEvent();
          
        }
        
      }
      
    }
    
    return $eventObject;
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
    
    if( isset($this->_registry[$event]) ) {
      return true;
    } else {
      return false;
    }
  }
  
}

?>