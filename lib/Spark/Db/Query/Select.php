<?php

class Spark_Db_Query_Select
{
    const EQUAL              = "=";
    const GREATER_THAN       = ">";
    const LESS_THAN          = "<";
    const GREATER_THAN_EQUAL = ">=";
    const LESS_THAN_EQUAL    = "<=";
    const IN                 = "IN";
    
    protected $attribute;
    protected $value;
    protected $operator;

    public static function attribute($attribute)
    {
        return new self($attribute);
    }

    public function __construct($attribute = null)
    {
        $this->_attribute = $attribute;
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
