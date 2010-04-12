<?php

interface Spark_Event_HandlerInterface
{
  
  /**
   * This method gets called by the dispatcher when the event occurs
   * @param  Spark_Event $event Contains event information, such as 
   *                                     timestamp, message, event name
   * @return int The handler should return a status code (true/false, status code 
   *             constants from Spark_Event)
   */
  public function handleEvent(Spark_Event $event);
  
}

?>