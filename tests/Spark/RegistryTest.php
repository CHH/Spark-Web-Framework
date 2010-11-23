<?php

class Spark_RegistryTest extends PHPUnit_Framework_Testcase
{
    protected $registry;

    public function setUp()
    {
        $this->registry = new Spark_Registry();
    }
    
    public function getTestData()
    {
        $object = new stdClass;
        $object->key1 = "value1";
        $object->key2 = "value2";
        
        return array(
            array("key1", "value1"),
            array("key2", $object),
            array("key3", array("foo", "bar", "baz"))
        );
    }

    /**
     * @dataProvider getTestData
     */
    public function testNonStatic($key, $value)
    {
        $this->registry->set($key, $value);
        
        $this->assertTrue($this->registry->has($key));
        $this->assertEquals($value, $this->registry->get($key));
    }
    
    /** 
     * @dataProvider getTestData
     */
    /*
    public function testStaticSet($key, $value)
    {
        Spark_StaticRegistry::set($key, $value);
        
        $this->assertTrue(Spark_StaticRegistry::has($key));
        $this->assertEquals($value, Spark_StaticRegistry::get($key));
    }*/
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetNotExisting()
    {
        $this->registry->get("foo");
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetExisting()
    {
        $this->registry->set("key1", "foo");
    }
    
    /**
     * Registry key must be a string
     * 
     * @expectedException InvalidArgumentException
     */
    public function testInvalidKey()
    {
        $this->registry->set(array("foo" => "bar"), "bar");
    }
}
