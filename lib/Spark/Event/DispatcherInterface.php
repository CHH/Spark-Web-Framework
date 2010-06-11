<?php

interface Spark_Event_DispatcherInterface
{
  
  public function register($event, $callback);
  
  public function trigger($event, $message = null);
  
}
