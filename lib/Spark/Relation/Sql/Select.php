<?php

class Spark_Relation_Sql_Select implements Spark_Relation_Sql_Formatter
{
    public function toSql(Spark_Relation_AbstractVerb $verb)
    {
        $attributes = $project->getAttributes();
        
        return sprintf(
            "%s FROM %s",
            $attributes ? join($attributes, ",") : "*",
            $project->getRelation();
        );
    }
}
