<?php

class Spark_Object_ManagerTest extends PHPUnit_Framework_Testcase
{
  
  public function testInstantiationWithoutOptions()
  {
    $sampleObject = Spark_Object_Manager::create("SampleObject");
    
    $this->assertTrue($sampleObject instanceof SampleObject);
    $this->assertTrue(is_object($sampleObject));
  }
  
  public function testSingletonInstantiation()
  {
    $id1 = spl_object_hash(Spark_Object_Manager::get("SampleObject"));
    $id2 = spl_object_hash(Spark_Object_Manager::get("SampleObject"));
    
    $this->assertEquals($id1, $id2);
  }
  
  public function testInstantiationWithOptions()
  {
    $options = array(
                 "foo_bar" => "fooBar",
                 "foo_bar_baz" => "fooBarBaz"
               );
    
    $sampleObject = Spark_Object_Manager::create("SampleObject", $options);
    
    $this->assertTrue($sampleObject instanceof SampleObject);
    $this->assertEquals("fooBar", $sampleObject->getFooBar());
    $this->assertEquals("fooBarBaz", $sampleObject->getFooBarBaz());
  }
  
  public function testInstantiationWithOptionBinding()
  {
    $options = array(
                 "foo_bar" => "fooBar",
                 "foo_bar_baz" => "fooBarBaz"
               );
    
    Spark_Object_Manager::addBinding(
      Spark_Object_OptionBinding::staticBind($options)->to("SampleObject")
    );
    
    $this->assertTrue(Spark_Object_Manager::hasBinding("SampleObject"));
    $this->assertNotNull(Spark_Object_Manager::getBinding("SampleObject"));
    
    $sampleObject = Spark_Object_Manager::create("SampleObject");
    
    $this->assertTrue($sampleObject instanceof SampleObject);
    
    $this->assertEquals("fooBar", $sampleObject->getFooBar());
    $this->assertEquals("fooBarBaz", $sampleObject->getFooBarBaz());
  }
  
}

