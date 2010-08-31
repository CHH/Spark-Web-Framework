<?php

class Spark_Db_Query_Alias
{
    
    protected $name;
    protected $alias;
    
    public function __construct($name, $alias)
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