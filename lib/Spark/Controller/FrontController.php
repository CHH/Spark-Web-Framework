<?php

class Spark_Controller_FrontController implements Spark_UnifiedConstructorInterface
{
  
  protected $_request = null;
  
  protected $_response = null;
  
  protected $_resolver = null;
  
  protected $_router = null;
  
  public function __construct($options = null)
  {
    if(!is_null($options)) {
      $this->setOptions($options);
    }
  }
  
  public function setOptions($options)
  {
    Spark_Object_Options::setOptions($this, $options);
    return $this;
  }
  
  public function handleRequest()
  {
    $request = $this->getRouter()->route($this->getRequest());
    $response = $this->getResponse();
    
    $command = $this->getResolver()->getCommand($request);
    
    if($command) {
      $command->execute($request, $response);
    }
    
    $response->sendResponse();
  }
  
  public function getRequest()
  {
    if(is_null($this->_request)) {
      $this->_request = Spark_Object_Manager::createInstance("Spark_Controller_HttpRequest");
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
      $this->_response = Spark_Object_Manager::createInstance("Spark_Controller_HttpResponse");
    }
    return $this->_response;
  }
  
  public function setResponse(Zend_Controller_Response_Abstract $response)
  {
    $this->_response = $response;
    return $this;
  }
  
  public function getResolver()
  {
    if(is_null($this->_resolver)) {
      $this->_resolver = Spark_Object_Manager::createInstance("Spark_Controller_CommandResolver");
    }
    return $this->_resolver;
  }
  
  public function setResolver(Spark_Controller_CommandResolverInterface $resolver)
  {
    $this->_resolver = $resolver;
    return $this;
  }
  
  public function getRouter()
  {
    if(is_null($this->_router)) {
      $this->_router = Spark_Object_Manager::createInstance("Zend_Controller_Router_Rewrite");
    }
    return $this->_router;
  }
  
  public function setRouter(Zend_Controller_Router_Interface $router) {
    $this->_router = $router;
    return $this;
  }
}