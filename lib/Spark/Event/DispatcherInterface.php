<?php

interface Spark_Event_DispatcherInterface
{
  
  public function on($event, $callback);
  
  public function trigger($event, $message = null);
  
}