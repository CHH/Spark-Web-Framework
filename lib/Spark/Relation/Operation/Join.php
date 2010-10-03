<?php

class Spark_Relation_Operation_Join extends Spark_Relation_AbstractVerb
{
    protected $relation;
    protected $selection;
    
    public function direct($relation, $selection)
    {
        if (is_string($selection)) {
            $on = Spark_Relation_Select::parseString($selection);
        }
        $this->selection = $selection;
    }
}
