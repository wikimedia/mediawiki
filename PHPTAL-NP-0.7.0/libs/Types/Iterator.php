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

/**
 * Iterable interface.
 *
 * An iterable object can produce Iterator(s) related to itself.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class Iterable
{
    /**
     * Retrieve iterable size.
     */
    function size(){}

    /**
     * Get an initialized iterator for this object.
     */
    function &getNewIterator(){}
}


/**
 * Iterator interface.
 *
 * This class provides common methods for Iterator objects. 
 *
 * An iterator is a 'pointer' to a data item extracted from some collection of
 * resources.
 *
 * The aim of Iterator is to allow some abstraction between your program
 * resources and the way you fetch these resources.
 *
 * Thus, you can loop over a file content and later replace this file with a
 * database backend whithout changing the program logic.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class Iterator
{
    /**
     * Reset iterator to first item.
     *
     * This method should throw an exception for once only iterators.
     * 
     * @throws ResetFailed
     */
    function reset()
    {
        return PEAR::raiseError('\'reset\' method not implemented');
    }
    
    /**
     * Test if current item is not the end of iterator.
     *
     * @return boolean
     */
    function isValid()
    {
        return PEAR::raiseError('\'isValid\' method not implemented');
    }
    
    /**
     * Iterate on the next element and returns the next item.
     * 
     * @return mixed (by reference)
     */
    function &next()
    {
        return PEAR::raiseError('\'next\' method not implemented');
    }
    
    // ----------------------------------------------------------------------
    // getters
    // ----------------------------------------------------------------------
    
    /**
     * Retrieve the current item index.
     * 
     * @return int
     */
    function index()
    {
        return PEAR::raiseError('\'index\' method not implemented');
    }
    
    /**
     * Retrieve current item value.
     * 
     * @return mixed (by reference)
     */
    function &value()
    {
        return PEAR::raiseError('\'value\' method not implemented');
    }

    // ----------------------------------------------------------------------
    // optional methods
    // ----------------------------------------------------------------------
    
    /**
     * (optional) Additional index for hashes.
     * 
     * @return string
     */
    function key()
    {
        return $this->index();
    }
    
    /**
     * (optional) Remove the current value from container.
     *
     * Implement this method only when the iterator can do a secured remove
     * without breaking other iterators works.
     */
    function remove()
    {
        return PEAR::raiseError('\'remove\' method not implemented');
    }
}

/**
 * Exception thrown by iterators that can be reseted only once.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class ResetFailed extends PEAR_Error 
{
}

?>
