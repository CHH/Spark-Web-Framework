<?php

class Spark_Relation_Query implements IteratorAggregate
{
    const R_AND = "and";
    const R_OR  = "or";
    
    const R         = "R";
    const R_PROJECT = "R_PROJECT";
    const R_SELECT  = "R_SELECT";
    const R_ALIAS   = "R_ALIAS";
    const R_JOIN    = "R_JOIN";
    const R_TAKE    = "R_TAKE";
    const R_SKIP    = "R_SKIP";
    
    protected $tokens = array(
        self::R_SELECT => array(),
        self::R_JOIN   => array(),
        self::R_TAKE   => 0,
        self::R_SKIP   => 0
    );
    
    public function __construct(Spark_Relation_Relation $relation = null)
    {
        $this->setRelation($relation);
    }
    
    public function getIterator()
    {
        $this->relation->setQuery($this);
        return $this->relation;
    }
    
    public function setToken($token, $value)
    {
        $this->tokens[$token] = $value;
        return $this;
    }
    
    public function getToken($token)
    {
        if (!isset($this->tokens[$token])) {
            return null;
        }
        return $this->tokens[$token];
    }
    
    public function getTokens()
    {
        return array_filter($this->tokens);
    }
        
    public function setRelation(Spark_Relation_Relation $relation)
    {
        return $this->setToken(self::R, $relation);
    }
    
    public function getRelation()
    {
        return $this->getToken(self::R);
    }
    
    public function project($projection)
    {
        if (!$projection instanceof Spark_Relation_Project) {
            $projection = new Spark_Relation_Project($projection);
        }
        $this->setToken(self::R_PROJECT, $projection);
        return $this;
    }
    
    public function select($selection, $value = null)
    {
        if (is_string($selection) and $value !== null) {
            $selection = Spark_Relation_Select::parseString($selection, $value);
        }
        if (!$selection instanceof Spark_Relation_Select) {
            throw new InvalidArgumentException("No valid arguments for a selection given");
        }
        $this->tokens[self::R_SELECT][] = array($selection, self::R_AND);
        return $this;
    }
    
    public function orSelect($selection)
    {
        $this->tokens[self::R_SELECT][] = array($selection, self::R_OR);
        return $this;
    }
    
    public function join($relation, $on)
    {
        $this->tokens[self::R_JOIN][] = new Spark_Relation_Join($relation, $on);
        return $this;
    }

    public function take($number)
    {
        if (!is_numeric($number)) {
            throw new InvalidArgumentException("Must be a valid number");
        }
        return $this->setToken(self::R_TAKE, $number);
    }

    public function skip($number)
    {
        if (!is_numeric($number)) {
            throw new InvalidArgumentException("Must be a valid number");
        }
        return $this->setToken(self::R_SKIP, $number);        
    }
}
