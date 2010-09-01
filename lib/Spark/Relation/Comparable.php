<?php

interface Spark_Relation_Comparable
{
    const EQUAL              = "=";
    const GREATER_THAN       = ">";
    const LESS_THAN          = "<";
    const GREATER_THAN_EQUAL = ">=";
    const LESS_THAN_EQUAL    = "<=";
    const IN                 = "IN";
    
    public function isEqual($value);
    public function isGreaterThan($value);
    public function isGreaterThanEqual($value);
    public function isLessThan($value);
    public function isLessThanEqual($value);
    public function isIn(array $values);
}
