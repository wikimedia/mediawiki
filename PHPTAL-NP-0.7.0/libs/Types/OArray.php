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
require_once "Types/Iterator.php";

/** 
 * Class wrapper for oriented object arrays.
 *
 * This class implements a simple interface quite like java to handle array in
 * a good old oo way.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class OArray extends Iterable 
{
    var $_array;
    var $_size = 0;

    /**
     * OArray constructor.
     *
     * @param array $array optional 
     *        The php array this vector will manage.
     */
    function OArray($array=array())
    {
        $this->__construct($array);
    }
    
    /**
     * OArray php4.4 compliant constructor.
     *
     * @param array $array optional 
     *        The php array this vector will manage.
     */
    function __construct($array=array())
    {
        $this->_array = array();
        for ($i = 0; $i < count($array); $i++) {
            $this->pushRef($array[$i]);
        }
    }

    /**
     * Retrieve an iterator ready to used.
     * @return ArrayIterator
     */
    function getNewIterator()
    {
        return new ArrayIterator($this);
    }

    /**
     * Returns the vector number of elements.
     * @return int
     */
    function size()
    {
        return count($this->_array);
    }

    /**
     * Returns true if this vector is empty, false otherwise.
     * @return boolean
     */
    function isEmpty()
    {
        return $this->size() == 0;
    }

    /**
     * Retrieve element at specified index.
     * @return mixed
     * @throws OutOfBounds
     */
    function &get($i)
    {
        if ($i > $this->size() || $i < 0) { 
            $err = new OutOfBounds("OArray index out of bounds ($i)");
            // return EX::raise($err);
            return PEAR::raiseError($err);
        }
        return $this->_array[$i]->obj;
    }

    /**
     * Retrieve index of specified element.
     *
     * @return int The element index of false if element not in vector.
     */
    function indexOf($element)
    {
        for ($i=0; $i < count($this->_array); $i++) {
            if ($this->_array[$i]->obj === $element) { 
                return $i; 
            }
        }
        return false;
    }

    /**
     * Set the item at specified index.
     *
     * @throws OutOfBounds if index is greated that vector limit
     * @return Old element
     */
    function &set($i, $item)
    {
        return $this->setRef($i, $item);
    }

    /**
     * Set the item at specified index (by reference).
     *
     * @throws OutOfBounds if index is greated that vector limit
     * @return Old element
     */
    function &setRef($i, &$item)
    {
        if ($i > $this->size() || $i < 0) { 
            $err = new OutOfBounds("OArray index out of bounds ($i)");
            // return EX::raise($err);
            return PEAR::raiseError($err);
        }
        $temp = $this->_array[$i];
        $this->_array[$i] = Ref($item);
        return $temp->obj;        
    }

    /**
     * Test if the vector contains the specified element.
     *
     * @param mixed $item The item we look for.
     */
    function contains($o)
    {
        for ($i = 0; $i < $this->size(); $i++) {
            if ($this->_array[$i]->obj === $o) return true;
        }
        return false;
    }

    /**
     * Add an element to the vector.
     *
     * @param mixed $o The item to add.
     */
    function add($o)
    {
        $this->push($o);
    }

    /**
     * Remove object from this vector.
     *
     * @param  mixed $o Object to remove.
     * @return boolean true if object removed false if not found
     */
    function remove($o)
    {
        $i = $this->indexOf($o);
        if ($i !== false) {
            $this->removeIndex($i);
            return true;
        }
        return false;
    }

    /**
     * Remove specified index from vector.
     *
     * @param  int $i Index
     * @throws OutOfBounds
     */
    function removeIndex($i)
    {
        if ($i > $this->size() || $i < 0 ) { 
            $err = new OutOfBounds("OArray index out of bounds ($i)");
            // return EX::raise($err);
            return PEAR::raiseError($err);
        }
        
        // $this->_array = array_splice($this->_array, $i, 1);
        array_splice($this->_array, $i, 1);
    }

    /**
     * Clear vector.
     */
    function clear()
    {
        $this->_array = array();
    }

    /**
     * Add an element at the end of the vector (same as add()).
     *
     * @param mixed $o Item to append to vector.
     */
    function push($o)
    {
        array_push($this->_array, Ref($o));
    }

    /**
     * Add an element at the end of the vector (same as add()).
     *
     * @param mixed $o Item to append to vector.
     */
    function pushRef(&$o)
    {
        $this->_array[] = Ref($o);
    }

    /**
     * Retrieve vector values.
     *
     * The returned array contains references to internal data.
     *
     * @return array
     */
    function &values()
    {
        $v = array();
        for ($i = 0; $i < $this->size(); $i++) {
            $v[] =& $this->_array[$i]->obj;
        }
        return $v;
    }

    /**
     * Remove the last element of the vector and returns it.
     *
     * @return mixed
     */
    function &pop()
    {
        $ref = array_pop($this->_array);
        return $ref->obj;
    }

    /**
     * Extract and return first element of OArray.
     * @return mixed
     */
    function &shift()
    {
        $ref = array_shift($this->_array);
        return$ $ref->obj;
    }

    /**
     * Retrieve a php array for this vector.
     *
     * @return array
     */
    function &toArray()
    {
        return $this->values();
    }

    /**
     * Retrieve a string representation of the array.
     */
    function toString()
    {
        return Types::_arrayToString($this->values());
    }
};

/**
 * OArray itertor class.
 *
 * This kind of iterators are used to walk throug a OArray object.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class ArrayIterator extends Iterator
{
    var $_src;
    var $_values;
    var $_value;
    var $_i;

    /**
     * Iterator constructor.
     *
     * @param mixed $oarray OArray object or php array.
     */
    function ArrayIterator(&$oarray)
    {
        return $this->__construct($oarray);
    }
    
    /**
     * PHP 4.4 compliant constructor.
     *
     * @param mixed $oarray OArray object or php array.
     */
    function __construct(&$oarray)
    {
        if (is_array($oarray)) {
            $this->_src = new OArray($oarray);
        } else if (is_a($oarray, 'OArray')) {
            $this->_src = &$oarray;
        } else {
            // ignore error, should throw a bad type error or something like
            // that but pear does not handle errors in constructors yet
            $err = new TypeError("ArrayIterator requires OArray object or php array.",
                                 PEAR_ERROR_DIE);
            // return EX::raise($err);
            return PEAR::raiseError($err);
        }
        $this->reset();
    }

    /**
     * Reset iterator to first array position.
     */
    function reset()
    {
        $this->_i = 0;
        $this->_end = false;
        $this->_values = $this->_src->_array;
        if (count($this->_values) == 0) {
            $this->_end = true;
            return;
        }

        $this->_value = $this->_values[0];
    }

    /**
     * Returns next vector item value.
     *
     * @return mixed
     * @throws OutOfBounds if iterator overpass the vector size.
     */
    function &next()
    {
        if ($this->_end || ++$this->_i >= count($this->_values)) {
            $this->_end = true;
            return null;
        }

        $this->_value = $this->_values[$this->_i];
        return $this->_value->obj;
    }

    /**
     * Test if the iteractor has not reached its end.
     * @return boolean
     */
    function isValid()
    {
        return !$this->_end;
    }

    /**
     * Retrieve current iterator index.
     * @return int
     */
    function index()
    {
        return $this->_i;
    }

    /**
     * Retrieve the current iterator value.
     * @return mixed
     */
    function &value()
    {
        return $this->_value->obj;
    }
    
    /**
     * Delete current index.
     */
    function remove()
    {
        $this->_src->remove($this->_value->obj);
    }
}

?>
