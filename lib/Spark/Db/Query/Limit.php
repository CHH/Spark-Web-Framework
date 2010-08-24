<?php

class Spark_Db_Query_Limit
{
    protected $takes;
    protected $skips = 0;
        
    public function __construct($take = null, $skip = 0)
    {
        $this->takes = $take;
        $this->skips = $skip;
    }

    public function take($amount)
    {
        $this->takes = $amount;
    }
    
    public function getLimit()
    {
        return $this->takes;
    }

    public function skip($amount) 
    {
        $this->skips = $amount;
    }
    
    public function getOffset()
    {
        return $this->skips;
    }
}
