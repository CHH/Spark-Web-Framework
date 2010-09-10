<?php

class Spark_Relation_Join
{
    protected $relation;
    protected $selection;
    
    public function __construct($relation, $selection)
    {
        if (is_string($selection)) {
            $on = Spark_Relation_Select::parseString($selection);
        }
        $this->selection = $selection;
    }
}
