<?php

abstract class Spark_Relation_AbstractVerb
{
    protected $previous;

    protected $operators = array(
        "project"  => "Spark_Relation_Operation_Project",
        "alias"    => "Spark_Relation_Operation_Alias",
        "join"     => "Spark_Relation_Operation_Join",
        "select"   => "Spark_Relation_Operation_Select",
        "orSelect" => "Spark_Relation_Operation_OrSelect"
    );
    
    final public function __construct(Spark_Relation_AbstractVerb $previous = null)
    {
        $this->operators = new Spark_Relation_ExtensionLoader($this->operators);
        $this->previous  = $previous;
    }

    final public function setPrevious(Spark_Relation_AbstractVerb $previousVerb)
    {
        $this->previous = $previousVerb;
        return $this;
    }

    final public function getPrevious()
    {
        return $this->previous;
    }
    
    public function __call($verb, Array $args)
    {
        $nextVerb = $this->operators->getInstance($verb);
        $nextVerb->setPrevious($this);
        
        if (!$nextVerb instanceof Spark_Relation_AbstractVerb) {
            throw new UnexpectedValueException("Verb is no instance of AbstractVerb");
        }
        
        if (!is_callable(array($nextVerb, "direct"))) {
            throw new BadMethodCallException("Extension must implement the direct() method");
        }
        
        call_user_func_array(array($nextVerb, "direct"), $args);
        return $nextVerb;
    }

    /**
     * Registers a plugin for loading
     *
     * @param  string $alias     The short name for the plugin
     * @param  string $className The class name of the plugin
     * @return Spark_Relation_AbstractVerb
     */
    public function registerPlugin($alias, $className)
    {
        $this->operators->register($alias, $className);
        return $this;
    }

    public function unregisterPlugin($alias)
    {
        $this->operators->unregister($alias);
        return $this;
    }
}
