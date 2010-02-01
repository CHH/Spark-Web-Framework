<?php

class Spark_Model_Mapper_DbTable extends Spark_Model_Mapper_Abstract
  implements Spark_Model_Mapper_Interface, Spark_Model_SelectableInterface
{
  
  protected $_tableGateway = null;
  
  protected $_tableName    = null;
  
  protected $_idProperty   = "id";
  
  public function __construct(Zend_Db_Table_Abstract $table = null)
  {
    if(is_null($table)) {
      if(is_null($this->_tableName)) {
        throw new Spark_Model_Exception("You must override the protected 
         _tableName Property, to supply the name of the table this 
         mapper maps the data to");
      }
      
      $this->_tableGateway = new Zend_Db_Table($this->_tableName);
      
    } else {
      $this->_tableGateway = $table;
    }
    
    parent::__construct();
  }
  
  public function find($id)
  {
    if($this->_hasIdentity($id)) {
      return $this->_getIdentity($id);
    }
    
    $idProperty = $this->_idProperty;
    
    if($result = $this->_getTableGateway()->find($id)->current()) {
      $result = $result->toArray();
    } else {
      return false;
    }
    
    $entity = $this->newEntity($result);
    
    $this->_setIdentity($entity->$idProperty, $entity);
    
    return $entity;
  }
  
  public function findAll(Zend_Db_Select $select = null)
  { 
    return $this->findBySelect($select);
  }
  
  public function findBySelect(Zend_Db_Select $select)
  { 
    $resultSet = $this->_getTableGateway()->fetchAll($select)->toArray();
    
    $dataSet = $this->mapToEntities($resultSet);
    
    return $dataSet;
  }
  
  public function save($entity)
  {
    if(!($entity instanceof $this->_entityClass)) {
      throw new Spark_Model_Exception("The Entity must be an Instance of the 
       {$this->_entityClass} Class");
    }
    
    $idProperty = $this->_idProperty;
    
    if(!$entity->$idProperty) {
    
      $this->_preSaveFilters->processFilters($entity);
      $entity->$idProperty = $this->_getTableGateway()->insert($entity->asSavable());
      $this->_postSaveFilters->processFilters($entity);
      
    } else {
      $where = $this->_getTableGateway()->getAdapter()
                    ->quoteInto("{$idProperty} = ?", $entity->$idProperty);
      
      $this->_preUpdateFilters->processFilters($entity);
      $this->_getTableGateway()->update($entity->asSavable(), $where);
      $this->_postUpdateFilters->processFilters($entity);
    }
    
    $this->_setIdentity($entity->$idProperty, $entity);
    
    return $entity;
    
  }
  
  public function delete($entity)
  {
    $idProperty = $this->_idProperty;
    
    if($entity instanceof $this->_entityClass) {
      $where = $this->_getTableGateway()->getAdapter()
                    ->quoteInto("{$idProperty} = ?", $entity->$idProperty);
    } else {
      $where = $this->_getTableGateway()->getAdapter()
                    ->quoteInto("{$idProperty} = ?", $entity);
    }
    
    $this->_getTableGateway()->delete($where);
    
    return $entity;
  }
  
  public function mapToEntities(array $dataSet)
  { 
    if(is_array($dataSet)) {
      $newDataSet = array();
      $idProperty = $this->_idProperty;
      
      foreach($dataSet as $data) {
        if($this->_hasIdentity($data[$idProperty])) {
          $entity = $this->_getIdentity($data[$idProperty]);
        } else {
          $entity = new $this->_entityClass($data);
          $this->_setIdentity($entity->$idProperty, $entity);
        }
        $newDataSet[] = $entity;
      }
      
    } elseif($dataSet == null) {
      return null;
      
    } else {
      throw new Spark_Model_Exception("The supplied Dataset must be an Array!");
    }
    
    return $newDataSet;
  }
  
  public function getSelect()
  {
    return $this->getTableGateway()->select();
  }
  
  public function getTableGateway()
  {
    return $this->_getTableGateway();
  }
  
  protected function _getTableGateway()
  {
    return $this->_tableGateway;
  }
}

?>
