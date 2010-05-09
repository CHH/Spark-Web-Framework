<?php

abstract class Spark_Controller_ActionController implements Spark_Controller_CommandInterface
{
  public function execute(
    Spark_Controller_RequestInterface $request,
    Zend_Controller_Response_Abstract $response
  )
  {
    $action = $request->getActionName();
    
    if($action == null) {
      $action = "index";
    }
    
    $method = str_replace(" ", "", ucwords(str_replace("_", " ", str_replace("-", " ", strtolower($action)))));
    $method[0] = strtolower($method[0]);
    
    $method = $action . "Action";
    
    if(method_exists($this, $method)) {
      $this->$method($request, $response);
    } else {
      $controller = get_class($this);
      throw new Spark_Controller_Exception("The action {$action} was not found in
        the controller {$controller}. Please make sure the method {$method} exists.", 404);
    }
    
  }
}