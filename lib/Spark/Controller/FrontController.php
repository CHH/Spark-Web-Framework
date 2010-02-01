<?php

class Spark_Controller_FrontController
{
  
  protected $_request = null;
  protected $_response = null;
  
  public function getRequest()
  {
    if(is_null($this->_request)) {
      $this->_request = Spark_Object_Manager::createInstance("Zend_Controller_Request_Http");
    }
    return $this->_request;
  }
  
  public function setRequest(Zend_Controller_Request_Abstract $request)
  {
    $this->_request = $request;
    return $this;
  }
  
  public function getResponse()
  {
    if(is_null($this->_response)) {
      $this->_response = Spark_Object_Manager::createInstance("Zend_Controller_Response_Http");
    }
    return $this->_response;
  }
  
  public function setResponse(Zend_Controller_Response_Abstract $response)
  {
    $this->_response = $response;
    return $this;
  }
}