<?php
/**
 * Simple Implementation of a Front Controller.
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Controller
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Controller
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Controller_FrontController implements Spark_Configurable
{
  /**
   * @var Zend_Controller_Request_Abstract
   */
  protected $_request = null;
  
  /**
   * @var Zend_Controller_Request_Abstract
   */
  protected $_response = null;
  
  /**
   * @var Spark_Controller_CommandResolverInterface
   */
  protected $_resolver = null;
  
  /**
   * @var Zend_Controller_Router_Interface
   */
  protected $_router = null;
  
  /** 
   * @var Spark_Event_Dispatcher
   */
  protected $_eventDispatcher = null;
  
  /**
   * @var string
   */
  private $_errorController = "Error";
  
  const EVENT_ROUTE_STARTUP = "spark.controller.front_controller.route_startup";
  const EVENT_ROUTE_SHUTDOWN = "spark.controller.front_controller.route_shutdown";
  const EVENT_BEFORE_DISPATCH = "spark.controller.front_controller.before_dispatch";
  const EVENT_AFTER_DISPATCH = "spark.controller.front_controller.after_dispatch";
  
  public function __construct(array $options = array())
  {
    $this->setOptions($options);
  }
  
  /**
   * setOptions() - Used to set configuration options on the object
   * @param mixed $options Either an array of key value pairs matching the 
   *                       Names of the Setter Methods or an Zend_Config Instance
   *
   * @return Spark_Controller_FrontController
   */
  public function setOptions(array $options)
  {
    Spark_Options::setOptions($this, $options);
    return $this;
  }
  
  /**
   * handleRequest() - This is where the heavy lifting in the Front Controller is
   *   done. Routes the request, loads the command, throws an exception if something
   *   goes wronng and triggers events for you to handle them.
   *   Four events are thrown throughout the dispatching cycle, they alle have
   *   the common namespace of "spark.controller.front_controller.":
   *     - route_startup, is called before routing is done
   *     - route_shutdown, routing is done
   *     - before_dispatch, before the command gets loaded and executed
   *     - after_dispatch, after the command was executed and before the response
   *       gets sent to the client
   * 
   * @return void
   */
  public function handleRequest()
  {
    $request  = $this->getRequest();
    $response = $this->getResponse();
    
    $eventDispatcher = $this->getEventDispatcher();
    
    $event = new Spark_Controller_Event($request, $response);
    
    /**
     * Routing Process
     */
    $eventDispatcher->trigger(self::EVENT_ROUTE_STARTUP, $event);
    
    $this->getRouter()->route($request);
    
    $eventDispatcher->trigger(self::EVENT_ROUTE_SHUTDOWN, $event);

    /*
     * Dispatching Process
     */
    $eventDispatcher->trigger(self::EVENT_BEFORE_DISPATCH, $event);
    
    $this->dispatch($request, $response);
    
    $eventDispatcher->trigger(self::EVENT_AFTER_DISPATCH, $event);

    $response->sendResponse();
    return $this;
  }

  protected function dispatch($request, $response)
  {
    if ($request->isDispatched()) {
      return $this;
    }
    
    $controller = $this->getResolver()->getInstance($request);

    if(!$controller) {
        throw new Spark_Controller_Exception("There was no matching command for this
            Request found. Make sure the Command {$request->getControllerName()} exists", 404);
    }
    
    $request->setDispatched(true);
    
    $controller->execute($request, $response);

    if (!$request->isDispatched()) {
      return $this->dispatch($request, $response);
    }
    return $this;
  }
  
  /**
   * handleException() - Can either be registered as fallback exception handler
   *   with set_exception_handler for more friendly uncatched Exceptions or you can pass
   *   an Exception directly to it, like in the handleRequest Method
   *
   * @param Exception $e
   */
  public function handleException(Exception $exception) 
  {
    if(is_array($this->_errorController)) {
      list($module, $controller) = $this->_errorController;
    } else {
      $controller = $this->_errorController;
      $module     = null;
    }
    
    $request  = $this->getRequest();
    $response = $this->getResponse();

    $request->setControllerName($controller);
    $request->setModuleName($module);
    
    $request->setParam("code", $exception->getCode());
    $request->setParam("exception", $exception);
    
    $response->clearBody();
    
    try {
      $this->dispatch($request, $response);
      $this->getEventDispatcher()
           ->trigger(self::EVENT_AFTER_DISPATCH, new Spark_Controller_Event($request, $response));

      $response->sendResponse();
      
      return $this;

    } catch (Exception $e) {
      restore_exception_handler();
      throw $e;
    }
  }
  
  public function getRequest()
  {
    if(is_null($this->_request)) {
      $this->_request = new Spark_Controller_HttpRequest;
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
      $this->_response = new Spark_Controller_HttpResponse;
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
      $this->_resolver = new Spark_Controller_Resolver;
    }
    return $this->_resolver;
  }
  
  public function setResolver(Spark_Controller_ResolverInterface $resolver)
  {
    $this->_resolver = $resolver;
    return $this;
  }
  
  public function getRouter()
  {
    if(is_null($this->_router)) {
      $this->_router = new Zend_Controller_Router_Rewrite;
    }
    return $this->_router;
  }
  
  public function setRouter(Zend_Controller_Router_Interface $router) {
    $this->_router = $router;
    return $this;
  }
  
  public function addPlugin(
    Spark_Event_HandlerInterface $plugin, 
    $listenTo = array(self::EVENT_ROUTE_STARTUP, self::EVENT_ROUTE_SHUTDOWN, self::EVENT_BEFORE_DISPATCH, self::EVENT_AFTER_DISPATCH)
  )
  {
    $eventDispatcher = $this->getEventDispatcher();

    if (is_string($listenTo)) {
      $listenTo = array($listenTo);
    }
    
    foreach ($listenTo as $event) {
      $eventDispatcher->on($event, $plugin);
    }
    
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
      $this->_eventDispatcher = new Spark_Event_Dispatcher;
    }
    return $this->_eventDispatcher;
  }
  
  public function setErrorController($controller)
  {
    $this->_errorController = $controller;
    return $this;
  }
  
  public function getErrorController()
  {
    return $this->_errorController;
  }
}
