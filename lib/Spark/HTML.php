<?php
/**
 * Simple PHP Class to aid in building HTML Markup
 *
 * @author Christoph Hochstrasser <c.hochstrasser@szene1.at>
 * @package HTML
 */

/**
 * Simple PHP Class to aid in building HTML Markup
 */
class Spark_HTML implements ArrayAccess, Countable
{
    /**
     * Child nodes of this element
     * @var array
     */
    protected $_innerHtml = array();
    
    /**
     * Attributes and their values
     * @var array
     */
    protected $_attributes = array();
    
    /**
     * Element name (e.g. "a", "div", "h1",...)
     * @var string
     */
    protected $_tagName;
    
    /**
     * Constructor
     *
     * @param  string $tagName   The name of the element to create
     * @param  mixed  $innerHtml Either Array of child nodes or plain text
     * @return Spark_HTML
     */
    public function __construct($tagName, $innerHtml = array())
    {
        $this->setTagName($tagName);
        $this->setHtml($innerHtml);
    }
    
    /**
     * Alias to constructor which allows chaining of an element method
     * @see __construct()
     */
    public static function create($tagName, $innerHtml = array())
    {
        return new self($tagName, $innerHtml);
    }
    
    /**
     * Sets the Tagname
     
     * @param  string $tagName
     * @return Spark_HTML
     */
    public function setTagName($tagName)
    {
        if (is_string($tagName)) {
            $this->_tagName = strtolower($tagName);
        }
        return $this;
    }
    
    /**
     * Returns the Tagname
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->_tagName;
    }
    
    /**
     * Set an array of attribute-value-pairs on the element
     *
     * @param  array $attributes
     * @return Spark_HTML
     */
    public function setAttributes(array $attributes = array())
    {
        $this->_attributes = $attributes;
        return $this;
    }
    
    /**
     * Returns the array of attributes with their values
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }
    
    /**
     * Sets a single attribute, boolean values are output as "true" or "false", 
     * multiple values get separated by a single space. This method a variable 
     * list of arguments, the first argument is the Attribute, all following 
     * arguments get treated as values for this attribute.
     *
     * @param string $attribute Name of the Attribute, dash separated attribute names
     *                          can also be written in camelCase, e.g. dataRemote gets data-remote
     * @param mixed $value
     * [@param mixed $value]...
     * @return Spark_HTML
     */
    public function setAttribute($attribute)
    {
        $args = array_slice(func_get_args(), 1);
        
        if (sizeof($args) === 1) {
        	$value = $args[0];
        } elseif (sizeof($args) >  1) {
            $value = $args;
        } else {
        	$value = null;
        }
        
        $attribute = $this->_camelCaseToDashed($attribute);
        $this->_attributes[$attribute] = $value;
        
        return $this;
    }
    
    /**
     * Returns the value of a single Attribute
     *
     * @param  string $attribute
     * @return string
     */
    public function getAttribute($attribute)
    {
    	return $this->hasAttribute($attribute) ? $this->_attributes[$attribute] : null;
    }
    
    /**
     * Checks if a Attribute is set
     *
     * @param  string $attribute
     * @return bool
     */
    public function hasAttribute($attribute)
    {
        return isset($this->_attributes[$attribute]);
    }
    
    /**
     * Sets the content of the tag
     *
     * @param  mixed $html Array of nodes or plain text
     * @return Spark_HTML
     */
    public function setHtml($html)
    {
        if (!is_array($html)) {
            $html = array($html);
        }
        $this->_innerHtml = $html;
        return $this;
    }
    
    /**
     * Return all nodes as Array
     *
     * @return array
     */
    public function getNodes()
    {
        return $this->_innerHtml;
    }
    
    /**
     * Returns the HTML as string
     *
     * @return string
     */
    public function getHtml()
    {
        /**
         * If no content is set, return empty element tag, so HTML::br() converts to <br />
         */
        if (!$this->getNodes()) {
            return $this->_buildStartTag($this->getTagName(), true);
        }

        $startTag = $this->_buildStartTag($this->getTagName());
        $innerHtml = join(null, $this->getNodes());
        $closeTag = $this->_buildCloseTag($this->getTagName());

        return $startTag . $innerHtml . $closeTag;
    }
    
    /**
     * Prepends the node to the element's content
     *
     * @param  mixed $node Either text or another instance of HTML
     * @return Spark_HTML
     */
    public function prepend()
    {
    	$nodes = func_get_args();
    	
    	return $this->_insert("before", $nodes);
    }
    
    /**
     * Appends the node to the element's content
     *
     * @param  mixed $node Either text or another instance of HTML
     * @return Spark_HTML
     */
    public function append()
    {
		$nodes = func_get_args();
    	
    	return $this->_insert("after", $nodes);
    }
    
    /**
     * Wraps the container around the content of the current element and returns the container
     *
     * @param  HTML $container Wrapper Element
     * @return Spark_HTML The Container
     */
    public function wrapIn(HTML $container)
    {
    	$container->setHtml($this->getNodes());
    	return $container;
    }
    
    /**
     * Add the class to the element
     * 
     * @param  string $class
     * @return Spark_HTML
     */
    public function addClass($class)
    {
    	return $this->_alterClass("add", $class);
    }
    
    /**
     * Remove the class from the element
     * 
     * @param  string $class
     * @return Spark_HTML
     */
    public function removeClass($class)
    {
    	return $this->_alterClass("remove", $class);
    }
    
    /**
     * Returns the number of child nodes the element has, gets called when
     * the count() function is applied on an instance
     *
     * @return int
     */
    public function count()
    {
        return sizeof($this->_innerHtml);
    }
    
    /**
     * Methods for ArrayAccess Interface, allows the User to access nodes like 
     * array indices
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->_innerHtml[$offset];
        }
        return null;
    }
    
    public function offsetSet($offset, $node)
    {
        if (is_null($offset)) {
            return $this->append($node);
        }
        return $this->_innerHtml[$offset] = $node;
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_innerHtml);
    }
    
    public function offsetUnset($offset)
    {
        unset($this->_innerHtml[$offset]);
    }
    
    /**
     * Provide Shortcut for Element creation, for example HTML::div() creates a div element
     * 
     * @param string $method    Name of the not found method, gets treated as 
     *                          tag name of the element
     * @param array  $arguments The Arguments the static method got called with, 
     *                          the first argument gets treated as content of the 
     *                          element, the other arguments get ignored
     * @return Spark_HTML
     */
    public static function __callStatic($method, $arguments)
    {
        $innerHtml = array();
        if (sizeof($arguments) === 1) {
            $innerHtml = $arguments[0];
            if (!is_array($innerHtml)) {
                $innerHtml = array($innerHtml);
            }
        }
        return new self($method, $innerHtml);
    }
    
    /**
     * jQuery-like Shortcut for setting and retrieving Attributes
     *
     * @param  string $method    Gets treated as Attribute name, dash-separated Attributes
     *                            can be written in camelCase, e.g. dataRemote gets data-remote
     * @param  array  $arguments Array of arguments this method got called with,
     *                            are treated as attribute values, if no given then the current value is returned
     * @return Spark_HTML
     */
    public function __call($method, $arguments)
    {
        /**
         * Convert camelCase Attribute accessor to dash-separated attribute value
         * neccessary to set attributes with dashs in them via the attribute methods
         */
        $attribute = $this->_camelCaseToDashed($method);

        /**
         * If the only argument of the function is an array, then assume 
         * the user wanted to set an array of argument values, if no argument is given
         * assume the user wanted the value of the attribute
         */
        if (sizeof($arguments) === 1) {
            $arguments = $arguments[0];
        } elseif (sizeof($arguments) === 0) {
            return $this->getAttribute($attribute);
        }

        $this->setAttribute($attribute, $arguments);

        return $this;
    }
    
    /**
     * Returns the HTML for this element on output on print, echo or using the
     * object as argument for string functions
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getHtml();
    }
    
	protected function _insert($position = "after", $node)
    {
    	if (is_array($node)) {
    		foreach ($node as $n) {
    			$this->_insert($position, $n);
    		}
    		return $this;
    	}
    	
    	if ($position == "after") {
        	$this->_innerHtml[] = $node;
    	}
    	
    	if ($position == "before") {
    		array_unshift($this->_innerHtml, $node);
    	}
    	
        return $this;
    }
    
	protected function _alterClass($mode = "add", $class)
    {
    	if (!isset($this->_attributes['class'])) {
    		$this->_attributes['class'] = array();
    	}
    	
    	/**
    	 * If the element has one class than convert it to an Array, so we can append/remove
    	 */
    	if (isset($this->_attributes['class']) and !is_array($this->_attributes['class'])) {
    		$this->_attributes['class'] = array($this->_attributes['class']);
    	}
    	
    	if (is_array($class)) {
    		foreach ($class as $c) {
    			$this->_alterClass($mode, $c);
    		}
    		return $this;
    	}
    	
    	if ($mode == "add") {
    		$this->_attributes['class'][] = $class;
    	}
    	
    	if ($mode == "remove" and $key = array_search($class, $this->_attributes['class'])) {
    		unset($this->_attributes['class'][$key]);
    	}
    	
    	return $this;
    }
    
    /**
     * Builds the Start Tag for the Element for output
     *
     * @param  string $tagName
     * @param  bool   $short   Wether the tag should be a self closing tag
     * @return string
     */
    protected function _buildStartTag($tagName, $short = false)
    {
        $startTag = '<' . $tagName;
        
        if($this->getAttributes()) {
            $startTag .= $this->_buildAttributes($this->getAttributes());
        }
        
        if ($short) {
            return $startTag .= ' />';
        }
        return $startTag .= '>';
    }
    
    /**
     * Returns HTML Closing Tag
     *
     * @param  string $tagName
     * @return string
     */
    protected function _buildCloseTag($tagName)
    {
        return '</' . $tagName . '>';
    }
    
    /**
     * Builds the attributes for the Start Tag
     *
     * @param  array $attributes
     * @return string
     */
    protected function _buildAttributes(array $attributes = array())
    {
        $htmlAttributes = '';
        
        foreach ($this->getAttributes() as $attribute => $value) {
            if ($attribute === "style" and is_array($value)) {
                $value = $this->_buildStyle($value);
            }
            if (is_bool($value)) {
                $value = $value ? "true" : "false";
            }
            if (is_array($value)) {
                $value = join(' ', $value);
            }
            if (is_null($value)) {
                continue;
            }
            
            $htmlAttributes .= ' ' . $attribute . '="' . $value . '"';
        }
        
        return $htmlAttributes;
    }
    
    protected function _buildStyle(array $cssProperties)
    {
        $style = '';
        foreach ($cssProperties as $property => $value) {
            $style .= $this->_camelCaseToDashed($property) . ": " . $value . '; ';
        }
        return rtrim($style);
    }
    
    protected function _camelCaseToDashed($string) 
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $string));
    }
    
}
