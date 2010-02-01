<?php

interface Spark_Controller_CommandInterface extends Spark_CommandInterface
{
  public function execute(Zend_Controller_Request_Http $request, Zend_Controller_Response_Http $response);
}