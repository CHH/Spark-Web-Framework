<?php

abstract class Spark_Controller_PluginAbstract implements Spark_Event_HandlerInterface
{
  
  final public function handleEvent($event)
  {
    if(!($event instanceof Spark_Controller_Event)) {
      return;
    }
    
    $eventName = explode(".", $event->getName());
    $method = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($eventName[sizeof($eventName) - 1]))));
    $method[0] = strtolower($method[0]);
    
    if(method_exists($this, $method)) {
      return $this->$method($event->getRequest(), $event->getResponse());
    }
  }
  
}