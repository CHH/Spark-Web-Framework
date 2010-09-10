<?php

interface Spark_Relation_Relation extends Iterator
{
    public function project($projection = array());
    public function create(array $row);
    public function update(array $row, $select = null);
    public function delete($select = null);
    
    public function getName();
}
