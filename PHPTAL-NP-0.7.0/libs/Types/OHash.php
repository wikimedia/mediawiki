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
require_once "Types/Ref.php";

/**
 * Wrapper for oriented object associative arrays.
 *
 * This class implements a simple interface quite like java to handle
 * associatives arrays (Hashtables).
 *
 * Note:
 *
 * Some problems may occurs with object references until php4.4 to avoid
 * unwanted object copy, use setRef() method with objects or pass Ref objects
 * to set().
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class OHash 
{
    var $_hash;

    /**
     * OHash constructor.
     *
     * php4.4 compliant constructor
     * 
     * @param array $array optional -- An associative php array
     */
    function __construct($array=array())
    {
        $this->_hash = array();
        $keys = array_keys($array);
        foreach ($keys as $key) {
            $this->_hash[$key] = ref($array[$key]);
        }                        
    }

    /**
     * OHash constructor.
     *
     * php4.4 compliant constructor
     * 
     * @param array $array optional  -- An associative php array
     */
    function OHash($array=array())
    {
        $this->__construct($array);
    }

    /**
     * Set a value in this hash.
     *
     * @param string $key
     * @param string $value
     */
    function set($key, $value)
    {
        $this->_hash[$key] = ref($value);
    }

    /**
     * Reference set.
     *
     * Until php4.4, it's the only way to avoid object/variable copy.
     *
     * @param string $key
     * @param reference $value
     */
    function setRef($key, &$value)
    {
        $this->_hash[$key] = ref($value);
    }

    /**
     * Set a map of values.
     *
     * @param mixed $hash An Hash object or an associative php array.
     */
    function setAll($hash)
    {
        if (!is_array($hash) && is_a($hash, 'OHash')) {
            $hash = $hash->toHash();
        }
        $keys = array_keys($hash);
        foreach ($keys as $key) {
            $this->_hash[$key] = ref($hash[$key]);
        }
    }

    /**
     * Retrieve value associated to specified key.
     * 
     * @param  string $key
     * @return reference
     */
    function &get($key)
    {
        if ($this->containsKey($key)) {
            return $this->_hash[$key]->obj;
        }
        return null;
    }

    /**
     * Remove element associated to specified key from hash.
     *
     * @param string $key
     */
    function remove($key)
    {
        $keys = $this->keys();
        $i = array_search($key, $keys);
        if ($i !== false) {
            // unset hash element to fix many bugs that should appear while
            // iterating and using references to this element.
            unset($this->_hash[$key]);
            // return array_splice($this->_hash, $i, 1);
        }
    }

    /**
     * Remove an element from the Hash.
     *
     * @param  mixed $o -- Element to remove from Hash
     */
    function removeElement(&$o)
    {
        $i = 0;
        $found = false;
        foreach ($this->_hash as $key => $value) {
            if ($value->obj === $o || $value == $o) {
                $found = $i;
                break;
            }
            $i++;
        }
        if ($found !== false) {
            return array_splice($this->_hash, $found, 1);
        }
    }

    /**
     * Returns true is hashtable empty.
     * 
     * @return boolean
     */
    function isEmpty()
    {
        return $this->size() == 0;
    }

    /**
     * Retrieve hash size (number of elements).
     * 
     * @return int
     */
    function size()
    {
        return count($this->_hash);
    }

    /**
     * Retrieve hash values array.
     * 
     * @return hashtable
     */
    function &values()
    {
        $v = array();
        foreach ($this->_hash as $key => $ref) {
            $v[] =& $ref->obj;
        }
        return $v;
    }

    /**
     * Retrieve hash keys array.
     * 
     * @return array
     */
    function keys()
    {
        return array_keys($this->_hash);
    }

    /**
     * Retrieve an hash iterator ready to use.
     * 
     * @return HashIterator
     */
    function getNewIterator()
    {
        return new HashIterator($this);
    }

    /**
     * Test if this hash contains specified key.
     * 
     * @param  string $key
     * @return boolean
     */
    function containsKey($key)
    {
        return array_key_exists($key, $this->_hash);
    }

    /**
     * Test if this hash contains specified value.
     * 
     * @param mixed $value -- The value to search
     * @return boolean
     */
    function containsValue($value)
    {
        foreach ($this->_hash as $k => $v) {
            if ($v->obj === $value || $v == $value) { 
                return true; 
            }
        }
        return false;
    }

    /**
     * Returns the php array (hashtable) handled by this object.
     * 
     * @return hashtable
     */
    function &toHash()
    {
        $result = array();
        foreach ($this->_hash as $key => $value) {
            $result[$key] =& $value->obj;
        }
        return $result;
    }

    /**
     * Create an Hash object from a simple php array variable.
     *
     * This method assumes that the array is composed of serial key, value
     * elements. For each pair of array element, the former will be used as key
     * and the latter as value.
     *
     * @param array $array -- php array.
     *
     * @return Hash
     * @static 1
     */
    function ArrayToHash($array)
    {
        $h = new OHash();
        while (count($array) > 1) {
            $key = array_shift($array);
            $h->set($key, array_shift($array));
        }
        return $h;
    }

    /**
     * Generate and return a string representation of this hashtable.
     *
     * @return string
     */
    function toString()
    {
        return Types::_hashToString($this->toHash());
    }

    /**
     * Sort hashtable on its keys.
     */
    function sort()
    {
        ksort($this->_hash);
    }
}


/**
 * Hash iterator.
 *
 * This kind of iterators are used to walk throug Hash objects.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class HashIterator 
{
    var $_src;
    var $_values;
    var $_keys;
    var $_key;
    var $_value;
    var $_i   = -1;
    var $_end = false;

    /**
     * Iterator constructor.
     *
     * @param mixed $hash -- OHash object or associative php array.
     */
    function HashIterator(&$hash)
    {
        return $this->__construct($hash);
    }
    
    /**
     * Iterator php4.4 compliant constructor.
     *
     * @param  mixed $hash -- OHash object or associative php array.
     * 
     * @throws TypeError 
     *         When $hash is not an array or an Hash object.
     */
    function __construct(&$hash)
    {
        if (is_array($hash)) {
            $this->_src = new OHash($hash);
        } else if (is_a($hash, 'ohash')) {
            $this->_src  = &$hash;
        } else {
            $err = new TypeError('HashIterator requires associative array or OHash');
            // return EX::raise($err);
            die($err->toString());
            return PEAR::raiseError($err);
        }
        $this->reset();
    }

    //
    // iterator logics
    //

    /**
     * Reset iterator to first element.
     */
    function reset()
    {
        // store a copy of hash references so a modification of source data
        // won't affect iterator.
        $this->_values = $this->_src->_hash;
        $this->_keys = $this->_src->keys();
        
        if (count($this->_keys) == 0) {
            $this->_end = true;
            return;
        }

        $this->_i = 0;        
        $this->_end = false;
        $this->_key = $this->_keys[0];
        $this->_value = $this->_values[$this->_key];
    }

    /**
     * Test is end of iterator is not reached.
     */
    function isValid()
    {
        return !$this->_end;
    }

    /**
     * Return next Hash item.
     *
     * This method also set _key and _value.
     *
     * @return mixed (by reference) or false if end reached
     */
    function &next()
    {
        if ($this->_end || ++$this->_i >= count($this->_keys)){
            $this->_end = true;
            return null;
        }

        $this->_key   = $this->_keys[$this->_i];
        $this->_value = $this->_values[$this->_key];
        return $this->_value->obj;
    }

    // 
    // getters
    // 

    /**
     * Return current iterator key.
     *
     * @return string
     */
    function key()
    {
        return $this->_key;
    }

    /**
     * Return current index (position in iteration).
     *
     * @return int
     */
    function index()
    {
        return $this->_i;
    }

    /**
     * Return current iterator value.
     *
     * @return mixed
     */
    function &value()
    {
        return $this->_value->obj;
    }

    // 
    // optional implementations
    // 
    
    /**
     * Remove current iterated item from source.
     */
    function remove()
    {
        $this->_src->remove($this->_key);
    }
}

?>
