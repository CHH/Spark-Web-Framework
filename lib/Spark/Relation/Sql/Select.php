<?php

class Spark_Relation_Sql_Select extends Spark_Relation_AbstractVerb
{
    public function direct()
    {
        $attributes = $project->getAttributes();
        
        return sprintf(
            "%s FROM %s",
            $attributes ? join($attributes, ",") : "*",
            $project->getRelation();
        );
    }
}
