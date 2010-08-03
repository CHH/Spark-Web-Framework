<?php
/**
 * Controller Event, used by the Front Controller to signal Plugins
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
class Spark_Controller_Event extends Spark_Event_Event
{
  
  protected $_request;
  protected $_response;

  public function __construct(
    Zend_Controller_Request_Abstract $request,
    Zend_Controller_Response_Abstract $response
  )
  {
    $this->_request  = $request;
    $this->_response = $response;
  }
  
  public function getRequest()
  {
    return $this->_request;
  }
  
  public function getResponse()
  {
    return $this->_response;
  }
  
}
