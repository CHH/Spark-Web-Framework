<?php

interface Spark_Relation_Relation
{
    /**
     * Returns the Name of the relation, used to create queries
     *
     * @return string
     */
    public function getName();
    
    /**
     * Creates a projection of the relation
     *
     * @return Spark_Relation_Query
     */
    public function project($projection = array());
    
    /**
     * Creates a tupel
     *
     * @param array $row
     */
    public function create(Array $row);
    public function update(Array $row, $select = null);
    public function delete($select = null);
}
