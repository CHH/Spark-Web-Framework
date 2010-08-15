<?php

class Spark_Db_Query
{

    const PROJECT = "PROJECT";
    const SELECT  = "SELECT";
    const JOIN    = "JOIN";
    const ALIAS   = "ALIAS";
    const AND     = "AND";
    const OR      = "OR";
    
    protected $parts = array(
        self::PROJECT => array(),
        self::SELECT  => array()
    );

    protected $relation;
    
    public function __construct()
    {}

    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }
    
    public function setPart($part, $value)
    {
        $this->_part[$part] = $value;
        return $this;
    }

    public function getPart($part)
    {
        if (!isset($this->_parts[$part])) {
            throw new InvalidArgumentException("Part {$part} is not defined");
        }
        return $this->_parts[$part];
    }
    
    public function project($projection)
    {
        $this->setPart(self::PROJECT, new Spark_Db_Query_Project($projection));
        return $this;
    }
    
    public function select($selection)
    {   
        $selects   = $this->getPart(self::SELECT);
        $selects[] = array($selection, self::AND);

        return $this->setPart(self::SELECT, $selects);
    }

    public function orSelect($selection)
    {
        $selects   = $this->getPart(self::SELECT);
        $selects[] = array($selection, self::OR);

        return $this->setPart(self::SELECT, $selects);
    }
    
    public function join($relation, $on)
    {}
    
}
