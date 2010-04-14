<?php

class Spark_Controller_Event extends Spark_Event_Event
{
  
  protected $_request;
  protected $_response;
  
  public function setRequest(Spark_Controller_RequestInterface $request)
  {
    $this->_request = $request;
    return $this;
  }
  
  public function getRequest()
  {
    return $this->_request;
  }
  
  public function setResponse(Zend_Controller_Response_Abstract $response)
  {
    $this->_response = $response;
    return $this;
  }
  
  public function getResponse()
  {
    return $this->_response;
  }
  
}