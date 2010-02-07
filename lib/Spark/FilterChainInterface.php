<?php

interface Spark_FilterChainInterface
{

  public function add($filter);
  
  public function process($in, $out);

}