<?php

class Spark_Db_Table implements Spark_Relation_Relation
{
    protected $table;
    protected $query;
    
    protected $attributes = array();
    
    public function __construct($table = null)
    {
        $this->table = $table;
    }
    
    public function getName()
    {
        return $this->table;
    }
    
    public function project($projection = array())
    {
        $query = new Spark_Relation_Query($this);
        $query->project($projection);
        
        return $query;
    }
    
    public function __set($attribute, $value)
    {
        throw new Exception("Creation of attributes is not allowed");
    }
    
    public function __get($attribute)
    {
        if (!isset($this->attributes[$attribute])) {
            $this->attributes[$attribute] = new Spark_Relation_Attribute($attribute);
        }
        return $this->attributes[$attribute];
    }
    
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }
    
    public function getQuery()
    {
        if (null === $this->query) {
            $this->query = $this->project();
        }
        return $this->query;
    }
    
    public function create(array $row)
    {
        $values = join($row, ", ");
        $fields = join(array_keys($row), ", ");
        
        $insert = "INSERT INTO {$this->getName()} ({$fields}) VALUES ({$values})";
        
    }
    
    public function update(array $row, $select = null)
    {
    
    }
    
    public function delete($select = null)
    {
    
    }
 
    public function rewind()
    {
    }
    
    public function valid()
    {
    }
    
    public function current()
    {
    }
    
    public function key()
    {
    }
    
    public function next()
    {
    }
    
    
}
