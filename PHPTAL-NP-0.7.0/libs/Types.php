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
 * Static class used in common types internals.
 *
 * @static
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class Types
{
    /**
     * Generate a string representation of specified variable.
     *
     * @param mixed $var -- variable to represent in a string form.
     * @static
     */
    function toString(&$var)
    {
        if (is_object($var)) {
            return Types::_objToString($var);
        } elseif (is_array($var)) {
            if (array_key_exists(0, $var) || count($var) == 0) {
                return Types::_arrayToString($var);
            } else {
                return Types::_hashToString($var);
            }
        } elseif (is_resource($var)) {
            return '#'.gettype($var).'#';
        }
        return $var;
    }

    /**
     * Generate a string representation of an object calling its toString
     * method of using its class name.
     * 
     * @access protected
     * @static
     */
    function _objToString(&$var)
    {
        if (method_exists($var, "toString")) {
            return $var->toString();
        } else {
            return '<' . get_class($var) . ' instance>';
        }        
    }

    /**
     * Generate a string representation of a php array.
     * 
     * @access protected
     * @static
     */
    function _arrayToString(&$var)
    {
        $values = array();
        foreach ($var as $val) {
            $values[] = Types::toString($val);
        }
        return '[' . join(', ', $values) . ']';        
    }

    /**
     * Generate a string representation of an associative array.
     *
     * @access protected
     * @static 
     */
    function _hashToString(&$var)
    {
        $values = array();
        foreach ($var as $key=>$val) {
            $values[] = '\''. $key . '\': ' . Types::toString($val);
        }
        return '{' . join(', ', $values) . '}';        
    }
}

?>
