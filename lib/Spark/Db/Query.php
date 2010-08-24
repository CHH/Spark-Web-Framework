<?php

class Spark_Db_Query
{
    const SELECT_AND = "and";
    const SELECT_OR  = "or";
    
    protected $relation;
    
    protected $project;
    
    protected $select = array();
    protected $joins  = array();
    protected $limit;
    
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
        if (!$projection instanceof Spark_Db_Query_Project) {
            $projection = new Spark_Db_Query_Project($projection);
        }
        $this->project = $projection;
        return $this;
    }
    
    public function getProject()
    {
        return $this->project;
    }
    
    public function select(Spark_Db_Query_Select $selection)
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

    public function take($amount)
    {
        if (!$this->limit instanceof Spark_Db_Query_Limit) {
            $this->limit = new Spark_Db_Query_Limit;
        }
        
        if (is_numeric($amount)) {
            $this->limit->take($amount);
            
        } else if ($amount instanceof Spark_Db_Query_Limit) {
            $this->limit = $amount;
        }
        
        return $this;
    }

    public function skip($amount)
    {
        if (!$this->limit instanceof Spark_Db_Query_Limit) {
            $this->limit = new Spark_Db_Query_Limit;
        }

        if (is_numeric($amount)) {
            $this->limit->skip($amount);
            
        } else if ($amount instanceof Spark_Db_Query_Limit) {
            $this->limit = $amount;
        }
        
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }
}
