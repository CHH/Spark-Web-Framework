<?php

class Spark_Db_Query_Project
{
    protected $attributes = array();

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }
}
