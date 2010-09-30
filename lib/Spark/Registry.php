<?php
/**
 * Provides an embeddable registry class
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Registry
 * @author     Christoph Hochstrasser <christoph.hochstrasser@gmail.com>
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Registry
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_Registry implements Countable, IteratorAggregate
{
    protected $registry = array();
    
    public function __construct(Array $registry = array())
    {
        $this->registry = $registry;
    }
    
    public function set($key, $value)
    {
        if (!is_string($key) or empty($key)) {
            throw new InvalidArgumentException("Key must be a string "
                . gettype($key) . " given");
        }
        $this->registry[$key] = $value;
        return $this;
    }
    
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new InvalidArgumentException("Undefined key {$key}");
        }
        return $this->registry[$key];
    }
    
    public function has($key)
    {
        if (!is_string($key) or empty($key)) {
            throw new InvalidArgumentException("Key must be a string "
                . gettype($key) . " given");
        }
        return isset($this->registry[$key]);
    }
    
    public function toArray()
    {
        return $this->registry;
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->registry);
    }
    
    public function count()
    {
        return count($this->registry);
    }
}
