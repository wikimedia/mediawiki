<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//  
//  Copyright (c) 2003 Laurent Bedubourg
//  
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//  
//  Authors: Laurent Bedubourg <laurent.bedubourg@free.fr>
//  


require_once "Types.php";
require_once "Types/Errors.php";

/**
 * Represents end of line string sequence '\n' of '<br/>' depending on
 * script context.
 */
if (!defined('endl')) { 
    if (isset($GLOBALS['REQUEST_URI'])) { 
        define('endl', "<br/>\n"); 
    } else {
        define('endl', "\n");
    }
}

/**
 * Object String wrapper class.
 *
 * This class is an attempt to normalize php strings in an oo way.
 *
 * PHP string related functions have different arguments scheme that decrease
 * code readability in OO programs. This class provides some php functions
 * in a comprehensible String class.
 *
 * The second interest reside in the StringIterator class which normalize
 * iteration over strings just like any other iterable object.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class OString 
{
    var $_str;

    /**
     * String constructor.
     *
     * @param mixed $str 
     *        May be an object from a class implementing the
     *        'toString()' method or a php variable that can be 
     *        casted into a string.
     */
    function OString($str="")
    {
        return $this->__construct($str);
    }
    
    /**
     * String php4.4 constructor.
     *
     * @param mixed $str 
     *        May be an object from a class implementing the
     *        'toString()' method or a php variable that can be 
     *        casted into a string.
     *
     * @throws TypeError
     */
    function __construct($str="")
    {
        if (is_object($str)) {
            if (!method_exists($str, "toString")) {
                $err = new TypeError('String constructor requires string or '
                                     . ' object implementing toString() method.',
                                     PEAR_ERROR_DIE);
                // return EX::raise($err);
                return PEAR::raiseError($err); 
            }
            $this->_str = $str->toString();
        } else {
            $this->_str = $str;
        }
    }

    /**
     * Append string values of arguments to this string.
     *
     * @param mixed ... 
     *        php variables or objects implementing the toString()
     *        method.
     */
    function append()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_object($arg)) {
                $this->_str .= $arg->toString();
            } else {
                $this->_str .= $arg;
            }
        }
    }
    
    /**
     * Retrieve char at specified position.
     *
     * @param  int $i Character position
     * @return char
     */
    function charAt($i)
    {
        return $this->_str[$i];
    }
    
    /**
     * Find first index of needle in current string.
     *
     * Return the first position of needle in string or *false* if 
     * not found.
     * 
     * @param mixed $needle  object implementing toString or php variable.
     * @param int   $pos     Search start position 
     * @return int 
     */
    function indexOf($needle, $pos=0)
    {
        if (is_object($needle)) { $needle = $needle->toString(); }
        return strpos($this->_str, $needle, $pos);
    }
    
    /**
     * Find last occurence of needle in current string.
     *
     * Returns the last position of needle in string or *false* if 
     * not found.
     *
     * @param mixed needle object implementing toString or php variable.
     * @return int 
     */
    function lastIndexOf($needle)
    {
        if (is_object($needle)) { $needle = $needle->toString(); }
        return strrpos($this->_str, $needle);
    }
    
    /**
     * Returns true if the string match the specified regex.
     *
     * @param mixed $regex object implementing toString or php string.
     * @return boolean
     */
    function matches($regex)
    {
        if (is_object($regex)) { $regex = $regex->toString(); }
        return preg_match($regex, $this->_str);
    }

    /**
     * Returns true if the string contains specified substring.
     *
     * @param mixed $str  object implementing toString or php string.
     * @return boolean
     */
    function contains($str)
    {
        if (is_object($str)){ $str = $str->toString(); }
        return (strpos($this->_str, $str) !== false);
    }
    
    /**
     * Returns true if the string begins with specified token.
     *
     * @param  mixed $str object implementing toString or php string.
     * @return boolean
     */
    function startsWith($str)
    {
        if (is_object($str)){ $str = $str->toString(); }
        return preg_match('|^'. preg_quote($str) . '|', $this->_str);
    }
    
    /**
     * Returns true if the string ends with specified token.
     *
     * @param  mixed $str object implementing toString or php string.
     * @return boolean
     */
    function endsWith($str)
    {
        if (is_object($str)){ $str = $str->toString(); }
        return preg_match('|'. preg_quote($str) . '$|', $this->_str);
    }
    
    /**
     * Replace occurences of 'old' with 'new'.
     * 
     * @param  mixed $old token to replace
     * @param  mixed $new new token
     * @return string
     */
    function replace($old, $new)
    {
        if (is_object($old)){ $old = $old->toString(); }
        if (is_object($new)){ $new = $new->toString(); }
        return str_replace($old, $new, $this->_str);
    }
    
    /**
     * Split this string using specified separator.
     *
     * @param mixed $sep Separator token
     *        
     * @return array of phpstrings
     */
    function split($sep)
    {
        if (is_object($sep)){ $sep = $sep->toString(); }
        return split($sep, $this->_str);
    }
    
    /**
     * Retrieve a sub string.
     *
     * @param int $start 
     *        start offset
     *        
     * @param int $end   
     *        End offset (up to end if no specified)
     *        
     * @return string
     */
    function substr($start, $end=false)
    {
        if ($end === false){ return substr($this->_str, $start); }
        return substr($this->_str, $start, ($end-$start));
    }

    /**
     * Retrieve a sub string giving its length.
     *
     * @param int $start  
     *        start offset
     *        
     * @param int $length 
     *        length from the start offset
     *        
     * @return string
     */
    function extract($start, $length)
    {
        return substr($this->_str, $start, $length);
    }
    
    /**
     * Return this string lower cased.
     * @return string
     */
    function toLowerCase()
    {
        return strtolower($this->_str);
    }
    
    /**
     * Return this string upper cased.
     * @return string
     */
    function toUpperCase()
    {
        return strtoupper($this->_str);
    }
    
    /**
     * Remove white characters from the beginning and the end of the string.
     * @return string
     */
    function trim()
    {
        return trim($this->_str);
    }
    
    /**
     * Test if this string equals specified object.
     *
     * @param mixed $o   
     *        php string or object implementing the toString() method.
     * @return boolean
     */
    function equals($o)
    {
        if (is_object($o)) {
            return $this->_str == $str->toString();
        } else { 
            return $this->_str == $o;
        }
    }
    
    /**
     * Return the length of this string.
     * @return int
     */
    function length()
    {
        return strlen($this->_str);
    }
    
    /**
     * Return the php string handled by this object
     * @return string
     */
    function toString()
    {
        return $this->_str;
    }

    /**
     * Create a string iterator for this String object.
     *
     * StringIterators iterate at a character level.
     *
     * @return StringIterator
     */
    function &getNewIterator()
    {
        return new StringIterator($this);
    }
}

/**
 * String buffer class.
 *
 * This class represents a buffer that concatenate appended string or objects
 * into a simple php string.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class StringBuffer extends OString
{
    /**
     * Append some elements to the buffer with an endl terminator.
     * 
     * @param mixed ... 
     *        List of php variables and/or objects implementing the
     *        toString() method.
     */
    function appendln()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            if (is_object($arg)) {
                $this->_str .= $arg->toString();
            } else {
                $this->_str .= $arg;
            }
        }        
        $this->_str .= endl;
    }
}

/**
 * Iterator for php strings and objects implementing toString method.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class StringIterator
{
    var $_str;
    var $_index = -1;
    var $_end = false;
    var $_value;
    
    /**
     * Constructor a string iterator.
     *
     * @param mixed $str 
     *        String object, string variable, or Object implementing
     *        the toString() method.
     */
    function StringIterator(&$str)
    {
        $this->__construct($str);
    }

    /**
     * Constructor a string iterator.
     *
     * @param mixed $str 
     *        object implementing toString or php string variable
     */
    function __construct(&$str)
    {
        if (is_object($str)) {
            $this->_string = new OString($str->toString());
        } else if (is_string($str)) {
            $this->_string = new OString($str);
        }
        $this->reset();
    }

    /**
     * Reset iterator to the begining of the string.
     *
     * If empty string, this iterator assume it is at the end of string.
     */
    function reset()
    {
        $this->_end = false;
        $this->_index = 0;
        if ($this->_string->length() == 0) {
            $this->_end = true;
        } else {
            $this->_value = $this->_string->charAt(0);
        }
    }
    
    /**
     * Return next character.
     * 
     * @return char
     */
    function next()
    {
        if ($this->_end || ++$this->_index >= $this->_string->length()) {
            $this->_end = true;
            return null;
        }
        
        $this->_value = $this->_string->charAt($this->_index);
        return $this->_value;
    }
    
    /**
     * Return true if the end of the string is not reached yet.
     *
     * @return boolean
     */
    function isValid()
    {
        return !$this->_end;
    }
    
    /**
     * Retrieve current iterator index.
     * 
     * @return int
     */
    function index()
    {
        return $this->_index;
    }
    
    /**
     * Retrieve current iterator value.
     *
     * @return char
     */
    function value()
    {
        return $this->_value;
    }
}

?>
