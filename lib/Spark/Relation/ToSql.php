<?php

class Spark_Relation_ToSql extends Spark_Relation_AbstractVerb
{
    protected $aggregatedVerbs  = array();

    protected $formatters;
    
    public function direct()
    {
        $this->formatters = new Spark_Relation_ExtensionLoader(array(
            "select" => "Spark_Relation_Sql_Select",
            "where"  => "Spark_Relation_Sql_Where",
            "limit"  => "Spark_Relation_Sql_Limit",
        ));
        $this->formatters->setShareInstances(true);
    }
    
    public function toSql()
    {
        if (!$this->getPrevious()) {
            throw new InvalidArgumentException("You cannot produce a query without verbs");
        }
        
        $this->aggregate($this->getPrevious());
        
        return $sql;
    }
    
    protected function aggregate(Spark_Relation_AbstractVerb $verb)
    {
        $className = get_class($verb);

        if (!isset($this->aggregatedVerbs[$className])) {
            $this->aggregatedVerbs[$className] = array();
        }
        
        $this->aggregatedVerbs[$className][] = $verb;

        if (!$previous = $verb->getPrevious()) {
            return;
        }
        return $this->aggregate($previous);
    }
    
    protected function getSelects()
    {
        $project = current($this->aggregatedVerbs["Spark_Relation_Operation_Project"]);
        $select  = $this->formatters->getInstance("select")->toSql($project);
        return $select;
    }

    protected function getWheres()
    {
        
    }
    
    protected function renderSelect(Spark_Relation_Project $project)
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

    public function registerFormatter($shortName, $className)
    {
        $this->formatters->register($shortName, $className);
        return $this;
    }
    
    public function unregisterFormatter($shortName)
    {
        $this->formatters->unregister($shortName);
        return $this;
    }
    
    public function __toString()
    {
        return $this->toSql();
    }
}
