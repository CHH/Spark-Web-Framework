<?php

class Spark_Event_DispatcherTest 
    extends PHPUnit_Framework_Testcase
    implements Spark_Event_HandlerInterface
{
    
    const EVENT1 = "event.foo";
    const EVENT2 = "event.bar";
    
    protected $_dispatcher;
    
    public function setUp()
    {
        $this->_dispatcher = new Spark_Event_Dispatcher;
    }
    
    public function testRegisteringHandler()
    {
        $this->_dispatcher->on(self::EVENT1, $this);
        
        $this->_dispatcher->on(self::EVENT2, function($e) {
            return;
        });
        
        $result1 = $this->_dispatcher->trigger(self::EVENT1);
        $result2 = $this->_dispatcher->trigger(self::EVENT2);
        
        $this->assertTrue($result1->isDispatched());
        $this->assertTrue($result2->isDispatched());
    }
    
    public function handleEvent($event)
    {
        return;
    }
    
}
