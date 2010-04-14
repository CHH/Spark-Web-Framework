<?php

class Spark_Controller_FrontController implements Spark_UnifiedConstructorInterface
{
  
  protected $_request = null;
  
  protected $_response = null;
  
  protected $_resolver = null;
  
  protected $_router = null;
  
  protected $_eventDispatcher = null;
  
  private $_filterClass = "Spark_Controller_FilterInterface";
  
  private $_errorCommandName = "Error";
  
  const EVENT_ROUTE_STARTUP = "spark.controller.front_controller.route_startup";
  const EVENT_ROUTE_SHUTDOWN = "spark.controller.front_controller.route_shutdown";
  const EVENT_BEFORE_DISPATCH = "spark.controller.front_controller.before_dispatch";
  const EVENT_AFTER_DISPATCH = "spark.controller.front_controller.after_dispatch";
  
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
    $request = $this->getRequest();
    $response = $this->getResponse();
    
    $eventDispatcher = $this->getEventDispatcher();
    
    $event = new Spark_Controller_Event;
    $event->setRequest($request)->setResponse($response);
    
    $eventDispatcher->trigger(self::EVENT_ROUTE_STARTUP, null, $event);
    
    $this->getRouter()->route($request);
    
    $eventDispatcher->trigger(self::EVENT_ROUTE_SHUTDOWN, null, $event);
    
    try {
      $command = $this->getResolver()->getCommand($request);
      
      $eventDispatcher->trigger(self::EVENT_BEFORE_DISPATCH, null, $event);
      
      if($command) {
          $command->execute($request, $response);
      } else {
        // Call the Error Command
        $request->setParam("code", 404);
        $this->getResolver()->getCommandByName($this->_errorCommandName)
             ->execute($request, $response);
      }
      
    } catch(Exception $e) {
      $request->setParam("code", 500);
      $request->setParam("exception", $e);

      $this->getResolver()->getCommandByName($this->_errorCommandName)
           ->execute($request, $response);
    }
    
    $request->setDispatched(true);
    
    $eventDispatcher->trigger(self::EVENT_AFTER_DISPATCH, null, $event);
    
    $response->sendResponse();
  }
  
  public function handleException(Exception $e) 
  {
    $errorCommand = $this->getResolver()->getCommandByName($this->_errorCommandName);
    
    $request = $this->getRequest();
    $response = $this->getResponse();
    
    $request->setParam("code", $e->getCode());
    $request->setParam("exception", $e);
    
    $event = new Spark_Controller_Event;
    $event->setRequest($request)->setResponse($response);
    
    $errorCommand->execute($request, $response);
    
    $this->getEventDispatcher()->trigger("spark.controller.front_controller.after_dispatch", null, $event);
  }
  
  public function getRequest()
  {
    if(is_null($this->_request)) {
      $this->_request = Spark_Object_Manager::create("Spark_Controller_HttpRequest");
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
      $this->_response = Spark_Object_Manager::create("Spark_Controller_HttpResponse");
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
      $this->_resolver = Spark_Object_Manager::create("Spark_Controller_CommandResolver");
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
      $this->_router = Spark_Object_Manager::create("Zend_Controller_Router_Rewrite");
    }
    return $this->_router;
  }
  
  public function setRouter(Zend_Controller_Router_Interface $router) {
    $this->_router = $router;
    return $this;
  }
  
  public function setEventDispatcher(Spark_Event_DispatcherInterface $dispatcher)
  {
    $this->_eventDispatcher = $dispatcher;
    return $this;
  }
  
  public function getEventDispatcher()
  {
    if(is_null($this->_eventDispatcher)) {
      $this->_eventDispatcher = Spark_Event_Dispatcher::getInstance();
    }
    return $this->_eventDispatcher;
  }
}