<?php

class Spark_Controller_CommandResolver 
  implements Spark_Controller_CommandResolverInterface, Spark_UnifiedConstructorInterface
{
  
  protected $_commandDirectory;
  
  protected $_defaultCommandName = "Default";
  
  protected $_commandSuffix = "Command";
  
  protected $_moduleDirectory;
  
  protected $_moduleCommandDirectory = "commands";
  
  public function __construct($options = null)
  {
    if(!is_null($options)) {
      $this->setOptions($options);
    }
  }
  
  public function setOptions($options)
  {
    Spark_Object_Options::setOptions($this, $options);
    return $this;
  }
  
  public function getCommand(Spark_Controller_RequestInterface $request)
  { 
  
    $commandName = ucfirst($request->getCommandName());
    
    if(!is_null($request->getModuleName())) {
      
    }
    
    if(!is_null($commandName)) {
      $command = $this->_loadCommand($commandName);
      
      if($command instanceof Spark_Controller_CommandInterface) {
        return $command;
      }
    }
    
    return $this->_loadCommand($this->getDefaultCommandName());
  }
  
  protected function _loadCommand($commandName)
  {
    $className = $commandName . $this->getCommandSuffix();
    
    $path = $this->getCommandDirectory() . DIRECTORY_SEPARATOR
            . $className . ".php";
    
    if(!file_exists($path)) {
      return false;
    }
    
    include_once $path;
    
    if(!class_exists($className, false)) {
      return false;
    }
    
    $command = new $className;
    return $command;
  }
  
  public function getCommandDirectory()
  {
    return $this->_commandDirectory;
  }
  
  public function setCommandDirectory($commandDirectory)
  {
    $this->_commandDirectory = $commandDirectory;
    return $this;
  }
  
  public function getCommandSuffix()
  {
    return $this->_commandSuffix;
  }
  
  public function setCommandSuffix($suffix)
  {
    $this->_commandSuffix = $suffix;
    return $this;
  }
  
  public function getDefaultCommandName()
  {
    return $this->_defaultCommandName;
  }
  
  public function setDefaultCommandName($commandName)
  {
    $this->_defaultCommandName = $commandName;
    return $this;
  }
  
  public function getModuleDirectory()
  {
    return $this->_moduleDirectory;
  }
  
  public function setModuleDirectory($directory)
  {
    $this->_moduleDirectory = $directory;
    return $this;
  }
  
  public function getModuleCommandDirectory()
  {
    return $this->_moduleCommandDirectory;
  }
  
  public function setModuleCommandDirectory($directory)
  {
    $this->_moduleCommandDirectory = $directory;
    return $this;
  }
  
}