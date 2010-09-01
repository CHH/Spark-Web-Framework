<?php

class Spark_Relation_Field implements Spark_Relation_Comparable
{
    protected $name;
    
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function isEqual($value)
    {
        return $this->getSelect()->isEqual($value);
    }

    public function isGreaterThan($value)
    {
        return $this->getSelect()->isGreaterThan($value);
    }

    public function isGreaterThanEqual($value)
    {
        return $this->getSelect()->isGreaterThanEqual($value);
    }

    public function isLessThan($value)
    {
        return $this->getSelect()->isLessThan($value);
    }

    public function isLessThanEqual($value)
    {
        return $this->getSelect()->isLessThanEqual($value);
    }   

    public function isIn(array $values)
    {
        return $this->getSelect()->isIn($values);
    }
    
    protected function getSelect()
    {
        return new Spark_Relation_Query_Select($this);
    }
}
