<?php

class Spark_Relation_QueryTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $query = new Spark_Relation_Query;
        
        $project        = array("id", "username", "password", "first_name", "last_name");
        $selectUsername = Spark_Relation_Select::attribute("username")->isEqual("foo");
        $selectPassword = Spark_Relation_Select::attribute("password")->isEqual("bar");
        
        $query->setRelation("users")->project($project)
              ->select($selectUsername)->select($selectPassword)
              ->take(5)->skip(1);
        
        $sqlSelect = new Spark_Relation_SqlQuery($query);
        
        $assertSql = "SELECT id, username, password, first_name, last_name " 
                   . "FROM users "
                   . "WHERE username=foo AND password=bar "
                   . "LIMIT 1,5";
        
        $selectId = Spark_Relation_Select::parseString("id = ?", 897);
        
        $this->assertEquals($selectId->getValue(), 897);
        $this->assertEquals($selectId->getOperator(), Spark_Relation_Comparable::EQUAL);
        $this->assertEquals($selectId->getAttribute(), "id");
        
        $this->assertEquals($sqlSelect->toSql(), $assertSql);
    }
}
