<?php
/**
 * Unify the calling of Callbacks for Event Handlers
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Event
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Event
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Event_Handler
{
    /**
     * @var array|Closure Closure or Array
     */
    protected $_callback;

    /**
     * Constructor
     *
     * @throws InvalidArgumentException
     * @param  array|Closure|string $context, if no $callback is given, then something callable is assumed 
     * @param  string $callback
     * @return void
     */
    public function __construct($context, $callback = null)
    {
        if (is_array($context)) {
            $callback = $context;
        } else if ($callback === null) {
            $callback = $context;
        } else {
            $callback = array($context, $callback);
        }

        if (!is_callable($callback)) {
            throw new InvalidArgumentException("Callback is not callable");
        }
        
        $this->_callback = $callback;
    }

    /**
     * Calls the encapsulated Callback
     * 
     * @param  Spark_Event_Event $event
     * @return mixed
     */
    public function call(Spark_Event_Event $event)
    {
        $callback = $this->getCallback();
        return $callback($event);
    }

    /** 
     * Returns the Callback, callable as Closure
     *
     * @return Closure
     */
    public function getCallback()
    {
        $callback = $this->_callback;
        
        if ($callback instanceof Closure) {
            return $callback;
        }
        return function() use ($callback) {
            return call_user_func_array($callback, func_get_args());
        };
    }
}
