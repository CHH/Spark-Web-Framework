<?php
/**
 * Event Dispatcher
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Event
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Event
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Event_Dispatcher implements Spark_Event_DispatcherInterface
{ 
  /**
   * Contains the events and their callbacks
   * @var array
   */
  protected $_registry = array();
  
  /**
   * Connects a callback to an event
   * @param  string $event     Name of the event
   * @param  mixed  $callback  Could be an Object implementing the 
   *                           Spark_Event_Handler_Interface, the name of a global
   *                           callback function or a closure (as of PHP > 5.3.0)
   *
   * @return Spark_Event_Dispatcher Providing a fluent interface
   */
  public function register($event, $callback)
  {
    if(!is_string($event)) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }

    if (!isset($this->_registry[$event])) {
      $this->_registry[$event] = array();
    }
    
    if ($callback instanceof Spark_Event_HandlerInterface) {
        $callback = new Spark_Event_Handler($callback, "handleEvent");
    } else  {
        $callback = new Spark_Event_Handler($callback);
    }
    
    array_unshift($this->_registry[$event], $callback);
    
    return $this;
  }
  
  /**
   * Shortcut for registering a handler on an event
   * @see register()
   */
  public function on($event, $callback)
  {
    return $this->register($event, $callback);
  }
  
  /**
   * Triggers (fires) an event
   * @param  string $event   Name of the Event which should be triggered
   * @param  object $context The Object context in which the Event got triggered
   *
   * @return Spark_Event_Event Contains status information about the event
   */
  public function trigger($event, $eventObj = null)
  {
    if(!is_string($event)) {
      throw new InvalidArgumentException("The name of the event must be a string");
    }
    
    if(is_null($eventObj)) {
      $eventObj = new Spark_Event_Event;
    }
    
    $eventObj->setName($event);
    
    if($this->hasCallbacks($event)) {
      
      foreach($this->_registry[$event] as $callback) {
        $callback->call($eventObj);
        $eventObj->setDispatched();
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
