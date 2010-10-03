<?php

class Spark_Relation_Operation_Alias extends Spark_Relation_AbstractVerb
{
    protected $name;
    protected $alias;
    
    public function direct($name, $alias)
    {
        $this->name  = $name;
        $this->alias = $alias;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getName() 
    {
        return $this->name;
    }
    
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
    
    public function getAlias()
    {
        return $this->alias;
    }
}
