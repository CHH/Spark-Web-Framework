<?php

class Spark_Relation_Sql_Where extends Spark_Relation_AbstractVerb
{
    static $count = 0;    
    
    public function direct()
    {
        $value    = $this->getPrevious()->getValue();
        $operator = $this->getPrevious()->getOperator();
        
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
        
        return ((self::$count++ > 0) ? $verb->getConjunction() . " " : "")
            . $verb->getAttribute() 
            . $operator 
            . $value;
    }
}
