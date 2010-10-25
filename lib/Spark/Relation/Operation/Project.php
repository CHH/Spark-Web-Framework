<?php

class Spark_Relation_Operation_Project extends Spark_Relation_AbstractVerb
{
    protected $attributes = array();
    protected $relation;
    
    public function direct($relation, Array $attributes = array())
    {
        $this->registerPlugin("toSql", "Spark_Relation_Sql_Select");
        
        $this->relation   = $relation;
        $this->attributes = $attributes;
    }
    
    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }
    
    public function getRelation()
    {
        return $this->relation;
    }
    
    public function setAttributes(Array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }
}
