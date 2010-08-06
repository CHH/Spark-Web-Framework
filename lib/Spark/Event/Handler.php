<?php

class Spark_Event_Handler
{
    protected $_callback;

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

    public function call(Spark_Event_Event $event)
    {
        $callback = $this->getCallback();
        return $callback($event);
    }

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
