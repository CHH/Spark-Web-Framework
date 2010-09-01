<?php

class Spark_Relation_SqlQuery
{
    protected $query;
    
    public function __construct(Spark_Relation_Query $query)
    {
        $this->query = $query;
    }
    
    public function toSql()
    {
        $query = $this->query;
        
        $tokens = $query->getTokens();
        
        $sql = $this->renderSelect()
             . $this->renderFrom()
             . $this->renderWhere()
             . $this->renderLimit();
        
        return $sql;
    }
    
    protected function renderSelect()
    {
        $project = $this->query->getToken(Spark_Relation_Query::R_PROJECT);
        
        if (!$project instanceof Spark_Relation_Project) {
            return " *";
        }
        
        $attributes = $project->getAttributes();

        if (!$attributes) {
            return " *";
        }
        
        foreach ($attributes as &$attribute) {
            if ($attribute instanceof Spark_Relation_Alias) {
                $attribute = $attribute->getName() . " AS " . $attribute->getAlias();
            }
        }
        
        $fields = join(", ", $attributes);
        
        return "SELECT {$fields}";
    }
    
    protected function renderFrom()
    {
        $relation = $this->query->getToken(Spark_Relation_Query::R);
        
        if (!$relation) {
            throw new UnexpectedValueException("No Relation set");
        }
        
        if (is_array($relation)) {
            $relation = join(", ", $relation);
        }
        
        return " FROM {$relation}";
    }
    
    protected function renderWhere()
    {
        $select = $this->query->getToken(Spark_Relation_Query::R_SELECT);
        
        if (!$select) {
            return;
        }
        
        $i = 0;
        
        foreach ($select as &$condition) {
            list($where, $conjunction) = $condition;
            
            $where = $this->renderCondition($where);
            
            switch ($conjunction) {
                case Spark_Relation_Query::R_OR:
                    $conjunction = "OR";
                    break;
                case Spark_Relation_Query::R_AND:
                    /*
                     * break omitted
                     */
                default:
                    $conjunction = "AND";
            }
            
            if ($i++ === 0) {
                $condition = $where;
                continue;
            }
            
            $condition = $conjunction . " " . $where;
        }
        
        return " WHERE " . join(" ", $select);
    }

    public function renderLimit()
    {
        $limit  = $this->query->getToken(Spark_Relation_Query::R_TAKE);
        $offset = $this->query->getToken(Spark_Relation_Query::R_SKIP);        
        
        if ($limit === 0) {
            return " OFFSET " . $offset;
        }
        
        $sql = " LIMIT " . $offset . "," . $limit;
        
        return $sql; 
    }
    
    public function setQuery(Spark_Relation_Query $query)
    {
        $this->query = $query;
        return $this;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
    
    protected function renderCondition(Spark_Relation_Select $where)
    {
        $value    = $where->getValue();
        $operator = $where->getOperator();
        
        switch ($operator) {
            case Spark_Relation_Comparable::EQUAL:
                $operator = "=";
                break;
            case Spark_Relation_Comparable::GREATER_THAN:
                $operator = ">";
                break;
            case Spark_Relation_Comparable::GREATER_THAN_EQUAL:
                $operator = ">=";
                break;
            case Spark_Relation_Comparable::LESS_THAN:
                $operator = "<";
                break;
            case Spark_Relation_Comparable::LESS_THAN_EQUAL:
                $operator = "<=";
                break;
            case Spark_Relation_Comparable::IN:
                $operator = "IN ";
                $value    = "(" . join(",", $value) . ")";
                break;
            default:
                $operator = "=";
        }
        
        return $where->getAttribute() . $operator . $value;
    } 
    
    public function __toString()
    {
        return $this->toSql();
    }
}
