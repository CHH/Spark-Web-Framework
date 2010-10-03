<?php

class Spark_Relation_ExtensionLoader
{
    protected $shortNames     = array();
    protected $instances      = array();
    protected $shareInstances = false;
    
    public function __construct(Array $shortNames = array())
    {
        $this->shortNames = $shortNames;
        $this->instances  = new Spark_Registry;
    }

    public function setShareInstances($shareInstances = true)
    {
        $this->shareInstances = $shareInstances;
        return $this;
    }
    
    public function register($shortName, $className)
    {
        $this->shortNames[$shortName] = $className;
        return $this;
    }

    public function unregister($shortName)
    {
        if (!$this->isRegistered($shortName)) {
            throw new InvalidArgumentException(sprintf(
                "Plugin %s is not registered",
                $shortName
            ));
        }
        unset($this->shortNames[$shortName]);
        return $this;
    }

    public function isRegistered($shortName)
    {
        return isset($this->shortNames[$shortName]);
    }
        
    public function getInstance($shortName)
    {
        if (isset($this->instances[$shortName])) {
            return $this->instances[$shortName];
        }
        
        if (!$this->isRegistered($shortName)) {
            throw new InvalidArgumentException(sprintf(
                "Plugin %s is not registered",
                $shortName
            ));
        }

        $className = $this->shortNames[$shortName];
        $this->loadClass($className);
        $instance  = new $className;
        
        if ($this->shareInstances) {
            $this->instances[$shortName] = $instance;
        }
        return $instance;
    }
    
    public function loadClass($className)
    {
        $fileName = str_replace("_", DIRECTORY_SEPARATOR, $className) . ".php";
        require_once $fileName;
    }
}
