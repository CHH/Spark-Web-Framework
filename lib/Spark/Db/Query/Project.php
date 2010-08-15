<?php

class Spark_Db_Query_Project
{
    protected $attributes = array();

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }
}
