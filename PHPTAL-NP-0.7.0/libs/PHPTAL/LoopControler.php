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

require_once "Types/OArray.php";
require_once "Types/OHash.php";
require_once "Types/Iterator.php";

/**
 * Template loop execution controler.
 *
 * This object is instantiated by the template on each loop. 
 *
 * LoopControlers accept different types of loop target.
 * 
 * - array
 * - objects having an getNewIterator() method returning an Iterator object.
 * - Iterator objects which produce isValid(), next(), key() and index() 
 *   methods.
 *   please note that key() return index() for non associative data.
 *
 * Other types are rejected by the loop controler on runtime producing
 * a TypeError exception.
 *
 * The loop controler install its iterator under the path "repeat/item"
 * where "item" is the name of the output var (defined in the template).
 *
 * Thus, the template can access differents methods of the iterator:
 *    repeat/someitem/key
 *    repeat/someitem/index
 *    repeat/someitem/next
 *    repeat/someitem/last
 *
 * If not all of these methods are implemented in iterators, ask 
 * iterator maintainers.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 * 
 */
class PHPTAL_LoopControler
{
    var $_context;
    var $_data;
    var $_data_name;
    var $_iterator;
    var $_error;
    
    /**
     * Controler constructor.
     *
     * @param PHPTAL_Context $context
     *        The template context.
     * @param string $data_name
     *        The item data name.
     * @param mixed  $data
     *        Loop resource.
     */
    function PHPTAL_LoopControler(&$context, $data_name, $data)
    {
        $this->_context   =& $context;
        $this->_data      =& $data;
        $this->_data_name =  $data_name;
        
        // ensure that data is not an error
        if (PEAR::isError($data)) {
            $this->_error =& $data;
            return $data;
        }
        
        // accepted objects
        // 
        // - iteratable implementing getNewIterator() method
        // - iterator implementing next(), isValid() and index() methods
        // - db_result produced by PEAR::DB package
        // 
        if (is_object($data)) {
            if (method_exists($data, "getNewIterator")) {
                
                $this->_iterator =& $data->getNewIterator();
                
            } elseif (is_a("iterator", $data)
                      || (method_exists($data, 'next')
                          && method_exists($data, 'isValid')
                          && method_exists($data, 'index'))) {
                
                $this->_iterator =& $data;
                
            } elseif (get_class($data) == 'db_result') {
                
                $this->_iterator = new PHPTAL_DBResultIterator($data);
                
            } else {
                
                $err = new TypeError("PHPTAL loop controler received a non Iterable object ("
                                     . get_class($data) . ")");
                $this->_error =& $err;
                return PEAR::raiseError($err);
            }
        } elseif (is_array($data)) {
            // 
            // array are accepted thanks to OArrayIterator
            // 
            reset($data);
            if (count($data) > 0 && array_key_exists(0, $data)) {
                // echo "OArray::iterator", RxObject::ClassName($data), endl;
                $this->_data = new OArray($data);
                $this->_iterator =& $this->_data->getNewIterator();
            } else {
                // echo "OHash::iterator", RxObject::ClassName($data), endl;
                $this->_data = new OHash($data);
                $this->_iterator =& $this->_data->getNewIterator();
            }
        } else {
            $err = new TypeError("phptal loop controler received a non Iterable value ("
                                 . gettype($data) . ")");
            $this->_error =& $err;
            return PEAR::raiseError($err);
        }

        // 
        // install loop in repeat context array
        // 
        $repeat =& $this->_context->get("repeat");
        if (array_key_exists($this->_data_name, $repeat)) {
            unset($repeat[$this->_data_name]);
        }
        $repeat[$this->_data_name] =& $this;        

        // $this->_context->setRef($this->_data_name, $temp);
        $temp =& $this->_iterator->value();
        $this->_context->set($this->_data_name, $temp);
        return $temp;
    }

    /**
     * Return current item index.
     * 
     * @return int
     */
    function index()
    {
        return $this->_iterator->index();
    }

    /**
     * Return current item key or index for non associative iterators.
     *
     * @return mixed
     */
    function key()
    {
        if (method_exists($this->_iterator, "key")) {
            return $this->_iterator->key();
        } else {
            return $this->_iterator->index();
        }
    }

    /**
     * Index is in range(0, length-1), the number in in range(1, length).
     *
     * @return int
     */
    function number()
    {
        return $this->index() + 1;
    }

    /**
     * Return true if index is even.
     *
     * @return boolean
     */
    function even()
    {
        return !$this->odd();
    }

    /**
     * Return true if index is odd.
     *
     * @return boolean
     */
    function odd()
    {
        return ($this->index() % 2);
    }

    /**
     * Return true if at the begining of the loop.
     *
     * @return boolean
     */
    function start()
    {
        return ($this->index() == 0);
    }

    /**
     * Return true if at the end of the loop (no more item).
     *
     * @return boolean
     */
    function end()
    {
        return ($this->length() == $this->number());
    }

    function isValid()
    {
        return $this->_iterator->isValid();
    }

    /**
     * Return the length of the data (total number of iterations). 
     *
     * @return int
     */
    function length()
    {
        return $this->_data->size();
    }
   
    /**
     * Retrieve next iterator value.
     *
     * @return mixed
     */
    function &next()
    {
        $temp =& $this->_iterator->next();
        if (!$this->_iterator->isValid()) {
            return false;
        }
        
        // $this->_context->setRef($this->_data_name, $temp);
        $this->_context->set($this->_data_name, $temp);
        return $temp;
    }
};


/**
 * Iterator for DB_Result PEAR object.
 *
 * This class is an implementation of the Iterator Interface
 * for DB_Result objects produced by the usage of PEAR::DB package.
 * 
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_DBResultIterator extends Iterator
{
    var $_src;
    var $_index = -1;
    var $_end   = false;
    var $_value;
    
    /**
     * Iterator constructor.
     *
     * @param DB_Result $result
     *        The query result.
     */
    function PHPTAL_DBResultIterator(&$result)
    {
        $this->_src =& $result;
        $this->reset();
    }

    function reset()
    {
        if ($this->size() == 0) {
            $this->_end = true;
            return;
        }

        $this->_index = 0;        
        $this->_end = false;
        unset($this->_value);
        $this->_value = $this->_src->fetchRow();        
    }
    
    /**
     * Return the number of rows in this result.
     *
     * @return int
     */
    function size()
    {
        if (!isset($this->_size)) {
            $this->_size = $this->_src->numRows();
        }
        return $this->_size;
    }

    /**
     * Returns true if end of iterator has not been reached yet.
     */
    function isValid()
    {
        return !$this->_end;
    }

    /**
     * Return the next row in this result.
     *
     * This method calls fetchRow() on the DB_Result, the return type depends
     * of the DB_result->fetchmod. Please specify it before executing the 
     * template.
     *
     * @return mixed
     */
    function &next()
    {
        if ($this->_end || ++ $this->_index >= $this->size()) {
            $this->_end = true;
            return false;
        }

        unset($this->_value);
        $this->_value = $this->_src->fetchRow();
        return $this->_value;
    }
    
    /**
     * Return current row.
     *
     * @return mixed
     */
    function &value()
    {
        return $this->_value;
    }

    /**
     * Return current row index in resultset.
     *
     * @return int
     */
    function index()
    {
        return $this->_index;
    }
}

?>
