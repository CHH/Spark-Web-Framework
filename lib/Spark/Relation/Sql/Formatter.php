<?php

interface Spark_Relation_Sql_Formatter
{
    public function toSql(Spark_Relation_AbstractVerb $verb);
}
