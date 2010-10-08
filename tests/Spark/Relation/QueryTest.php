<?php

class Spark_Relation_QueryTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $project = new Spark_Relation_Operation_Project();
        $project->setRelation("foo");

        $project->select("foo=?", "bar")->select("baz=?", "foo")->toSql()->toSql();
    }
}
