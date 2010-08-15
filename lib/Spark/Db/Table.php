<?php

class Spark_Db_Table
{   
    protected $name;

    protected $fields = array();
    
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function query()
    {
        $query = new Spark_Db_Query;
        $query->setRelation($this);
        return $query;
    }
    
    public function __get($field)
    {
        if (!isset($this->fields[$field])) {
            $this->fields[$field] = new Spark_Db_Field($field);
        }
        return $this->fields[$field];
    }

    public function __set($field, $value)
    {
        throw new RuntimeException("Fields cannot be set");
    }
}
