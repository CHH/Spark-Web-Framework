<?php

class Spark_Db_Query
{
    const SELECT_AND = "and";
    const SELECT_OR  = "or";
    
    protected $relation;
    
    protected $project;
    
    protected $select = array();
    protected $joins  = array();
    
    public function __construct($relation = null)
    {
        $this->relation = $relation;
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
    
    public function project($projection)
    {
        $this->project = new Spark_Db_Query_Project($projection);
        return $this;
    }
    
    public function getProject()
    {
        return $this->project;
    }
    
    public function select($selection)
    {   
        $this->select[] = array($selection, self::SELECT_AND);
        return $this;
    }
    
    public function orSelect($selection)
    {
        $this->select[] = array($selection, self::SELECT_OR);
        return $this;
    }

    public function getSelect()
    {
        return $this->select;
    }
        
    public function join($relation, $on)
    {
        $this->joins[] = new Spark_Db_Query_Join($relation, $on);
        return $this;
    }
    
    public function getJoins()
    {
        return $this->joins;
    }
}
