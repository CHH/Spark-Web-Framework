<?php

class Spark_Db_SqlQuery
{
    protected $query;
    
    public function __construct(Spark_Db_Query $query)
    {
        $this->query = $query;
    }
    
    public function toSql()
    {
        $query = $this->query;
        
        $relation = $query->getRelation(); 
        $project  = $query->getProject();
        $select   = $query->getSelect();
        $joins    = $query->getJoins();
        
        $sql = $this->renderSelect($project)
             . $this->renderFrom($relation)
             . $this->renderWhere($select);
        
        return $sql;
    }
    
    protected function renderSelect(Spark_Db_Query_Project $project)
    {
        $attributes = $project->getAttributes();
        
        foreach ($attributes as &$attribute) {
            if ($attribute instanceof Spark_Db_Query_Alias) {
                $attribute = $attribute->getName() . " AS " . $attribute->getAlias();
            }
        }
        
        $fields = join(", ", $attributes);
        
        return "SELECT {$fields}";
    }
    
    protected function renderFrom($relation)
    {
        if (!$relation) {
            throw new UnexpectedValueException("No Relation set");
        }
        
        if (is_array($relation)) {
            $relation = join(", ", $relation);
        }
        
        return " FROM {$relation}";
    }
    
    protected function renderWhere(array $select)
    {
        if (!$select) {
            return;
        }
        
        $i = 0;
        
        foreach ($select as &$condition) {
            list($where, $conjunction) = $condition;
            
            $where = $this->renderCondition($where);
            
            switch ($conjunction) {
                case Spark_Db_Query::SELECT_OR:
                    $conjunction = "OR";
                    break;
                case Spark_Db_Query::SELECT_AND:
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
    
    public function setQuery(Weblife1_Db_Query $query)
    {
        $this->query = $query;
        return $this;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
    
    protected function renderCondition(Spark_Db_Query_Select $where)
    {
        $value    = $where->getValue();
        $operator = $where->getOperator();
        
        switch ($operator) {
            case Spark_Db_Comparable::EQUAL:
                $operator = "=";
                break;
            case Spark_Db_Comparable::GREATER_THAN:
                $operator = ">";
                break;
            case Spark_Db_Comparable::GREATER_THAN_EQUAL:
                $operator = ">=";
                break;
            case Spark_Db_Comparable::LESS_THAN:
                $operator = "<";
                break;
            case Spark_Db_Comparable::LESS_THAN_EQUAL:
                $operator = "<=";
                break;
            case Spark_Db_Comparable::IN:
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
