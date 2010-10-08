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
        
        var_dump($this->aggregatedVerbs); return;
        
        return $sql;
    }
    
    protected function aggregate(Spark_Relation_AbstractVerb $verb)
    {
        $className = get_class($verb);

        if ($verb instanceof Spark_Relation_Operation_Select) {
            $className = "Spark_Relation_Operation_Select";
        }

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
        return $this->formatters->getInstance("select")->toSql($project);
    }

    protected function getWheres()
    {
        $selects = $this->aggregatedVerbs["Spark_Relation_Operation_Select"];
        $selects = array_reverse($selects);
        
        foreach ($selects as &$select) {
            $select = $this->formatters->getInstance("where")->toSql($select);
        }
        
        return join($selects, " ");
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
