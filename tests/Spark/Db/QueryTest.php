<?php

class Spark_Db_QueryTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $query = new Spark_Db_Query;
        
        $project        = array("id", "username", "password", "first_name", "last_name");
        $selectUsername = Spark_Db_Query_Select::attribute("username")->isEqual("foo");
        $selectPassword = Spark_Db_Query_Select::attribute("password")->isEqual("bar");
        
        $query->setRelation("users")->project($project)->select($selectUsername)->select($selectPassword);
        
        $sqlSelect = new Spark_Db_SqlQuery($query);
        $assertSql = "SELECT id, username, password, first_name, last_name FROM users WHERE username=foo AND password=bar";
        
        $this->assertEquals($sqlSelect->toSql(), $assertSql);
        $this->assertEquals($project, $query->getProject()->getAttributes());
        $this->assertEquals(2, count($query->getSelect()));
    }
}
