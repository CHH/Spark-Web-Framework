<?php

class Spark_Relation_QueryTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $query = new Spark_Relation_Query;
        
        $project = array("id", "username", "password", "first_name", "last_name");
        
        $query->setRelation("users")->project($project)
              ->select("username = ?", "foo")->select("password = ?", "bar")
              ->take(5)->skip(10);
        
        $sqlSelect = new Spark_Relation_SqlQuery($query);
        
        $assertSql = "SELECT id, username, password, first_name, last_name " 
                   . "FROM users "
                   . "WHERE username=foo AND password=bar "
                   . "LIMIT 10,5";
        
        $selectId = Spark_Relation_Select::parseString("id = ?", 897);
        
        $this->assertEquals(897, $selectId->getValue());
        $this->assertEquals(Spark_Relation_Comparable::EQUAL, $selectId->getOperator());
        $this->assertEquals("id", $selectId->getAttribute());
        
        $this->assertEquals($assertSql, $sqlSelect->toSql());
    }
}
