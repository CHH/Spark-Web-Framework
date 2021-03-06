<?php
/**
 * A static Registry
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   Spark
 * @package    Spark_Registry
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */

/**
 * @category   Spark
 * @package    Spark_Registry
 * @copyright  Copyright (c) 2010 Christoph Hochstrasser
 * @license    MIT License
 */
class Spark_StaticRegistry
{
    /**
     * @var Spark_Registry
     */
    protected static $registry;

    /**
     * set() - Sets a key in the Registry
     * 
     * @param string $key
     * @param mixed  $value
     */
    static public function set($key, $value)
    {
        self::getInstance()->set($key, $value);
    }

    /**
     * get() - Returns the value stored under the given key
     *
     * @param string $key
     * @return mixed
     */
    static public function get($key)
    {
        self::getInstance()->get($key);
    }

    /**
     * has() - Checks if a key is set in the Registry
     *
     * @param $key
     * @return bool
     */
    static public function has($key)
    {
        return self::getInstance()->has($key);
    }
    
    public static function setInstance(Spark_Registry $registry)
    {
        self::$registry = $registry;
    }
    
    public static function getInstance()
    {
        if (null === self::$registry) {
            self::$registry = new Spark_Registry;
        }
        return self::$registry;
    }
}
