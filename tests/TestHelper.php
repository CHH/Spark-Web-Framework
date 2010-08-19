<?php

define("LIB", realpath(dirname(__FILE__) . "/../lib"));
define("TESTS", realpath(dirname(__FILE__)));

set_include_path(LIB . PATH_SEPARATOR . get_include_path());

function autoload_from_libraries($class)
{
  require_once pear_classname_to_path($class);
}

function autoload_from_tests($class)
{
  require_once TESTS . DIRECTORY_SEPARATOR . pear_classname_to_path($class);
}

function pear_classname_to_path($classname)
{
  return str_replace("_", DIRECTORY_SEPARATOR, $classname) . ".php";
}

spl_autoload_register("autoload_from_libraries");
spl_autoload_register("autoload_from_tests");
