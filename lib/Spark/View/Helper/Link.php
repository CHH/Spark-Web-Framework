<?php

class Spark_View_Helper_Link
{
  public function link($innerHtml = null)
  {
    $link = new Spark_HTML("a", $innerHtml);
    return $link;
  }
}

?>
