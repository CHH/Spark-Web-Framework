<?php

class Spark_Relation_Sql_Where implements Spark_Relation_Sql_Formatter
{
    static $count = 0;    
    
    public function toSql(Spark_Relation_AbstractVerb $verb)
    {
        $value    = $verb->getValue();
        $operator = $verb->getOperator();
        
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
