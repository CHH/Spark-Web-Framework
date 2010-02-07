<?php

class Spark_RegistryTest extends PHPUnit_Framework_Testcase
{
  
  public function testSet()
  {
    $value = "Foo";
    
    Spark_Registry::set("foo", $value);
    
    $this->assertTrue(Spark_Registry::has("foo"));
    
    return $value;
  }
  
  /**
   * @depends testSet
   */
  public function testGet($expected)
  {
    $actual = Spark_Registry::get("foo");
    
    $this->assertEquals($expected, $actual);
  }
  
}
