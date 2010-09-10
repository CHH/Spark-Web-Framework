<?php

class Spark_Relation_Select implements Spark_Relation_Comparable
{
    protected $attribute;
    protected $value;
    protected $operator;

    public static function attribute($attribute)
    {
        return new self($attribute);
    }
    
    /**
     * Parse select string in form of "<attribute> <operator> ?", e.g. id = ?
     * and return an selection object
     *
     * @param  string $string 
     * @param  mixed  $value
     * @return Spark_Relation_Query_Select
     */
    public static function parseString($string, $value)
    {   
        $search = array(
            self::EQUAL,
            self::GREATER_THAN,
            self::GREATER_THAN_EQUAL,
            self::LESS_THAN,
            self::LESS_THAN_EQUAL,
            self::IN
        );
        
        foreach ($search as $s) {
            $attribute = rtrim(substr($string, 0, strpos($string, $s)));
            
            if ($attribute) {
                break;
            }
        }
        
        foreach ($search as $s) {
            $operator = trim(substr($string, strpos($string, $s), strlen($s)));
            
            if (in_array($operator, $search)) {
                break;
            }
        }
        
        return static::attribute($attribute)->compare($operator, $value);
    }
    
    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }
    
    public function getSelect()
    {
        return array($this->attribute, $this->operator, $this->value);
    }
    
    public function setSelect(array $select)
    {
        list($attribute, $operator, $value) = $select;
        
        $this->attribute = $attribute;
        $this->operator  = $operator;
        $this->value     = $value;
        
        return $this;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }
    
    public function getOperator()
    {
        return $this->operator;
    }
    
    public function isEqual($value)
    {
        return $this->compare(self::EQUAL, $value);
    }

    public function isGreaterThan($value)
    {
        return $this->compare(self::GREATER_THAN, $value);
    }

    public function isGreaterThanEqual($value)
    {
        return $this->compare(self::GREATER_THAN_EQUAL, $value);
    }

    public function isLessThan($value)
    {
        return $this->compare(self::LESS_THAN, $value);
    }

    public function isLessThanEqual($value)
    {
        return $this->compare(self::LESS_THAN_EQUAL, $value);
    }   

    public function isIn(array $values)
    {
        return $this->compare(self::IN, $values);
    }
    
    protected function compare($operator, $value)
    {
        switch ($operator) {
            case self::EQUAL:
            case self::GREATER_THAN:
            case self::LESS_THAN:
            case self::GREATER_THAN_EQUAL:
            case self::LESS_THAN_EQUAL:
            case self::IN:
                $this->operator = $operator;
            break;

            default:
                throw new InvalidArgumentException("Operator \"{$operator}\" is not defined");
        }
        
        $this->value = $value;

        return $this;
    }
}
