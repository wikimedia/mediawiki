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


// What are Context and Resolvers
// ==============================
//
// In phptal, variables and methods are referenced by path without distinction,
// also, arrays and hashtable elements are accessed using a path like
// myarray/10 to reach the 11's element of myarray or myhash/mykey to
// reach the value of mykey in myhash.
//
// This allow things like 'myobject/mymethod/10/othermethod' which is usefull
// in templates.
// 
// The Context is an array containing all template variables.
// When asked to reach a path, it look for the variable type of the first path
// element and generate a resolver matching the variable type.
// Then this resolver is asked to resolve the rest of the path, etc... up to 
// the final path element.
//
// Resolver knows how to handle a given path relatively to the object they
// handle.
//
// For instance, an array resolver can handle access to its array elements
// given a key (string) or the element id (int).
//
// The array resolver also bring an array method : count which return the
// number of elements in the array.
//
// Note
// ----
// 
// If a name conflict occurs between an object's variable and one of its
// methods, the variable is return in stead of the method.
// 

/**
 * Template context handler.
 *
 * This object produce a translation between variables passed to the template
 * and regular template pathes.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_Context
{
    var $_array = array();
    var $_errorRaised = false;

    /**
     * Context constructor.
     *
     * @param hashtable $hash (opt)
     */
    function PHPTAL_Context($hash=array())
    {
        $this->_array = $hash;
    }
    
    /**
     * Set a value by reference.
     *
     * @param string $path  -- Variable path
     * @param mixed  $value -- Reference to variable.
     */
    function setRef($path, &$value)
    { 
        $this->remove($path);
        $this->_array[$path] =& $value;
    }

    /**
     * Set a context variable.
     *
     * @param string $path  -- Variable path
     * @param mixed  $value -- Value
     */
    function set($path, $value)
    {
        $this->remove($path);
        $this->_array[$path] = $value;
    }

    /**
     * Remove a context variable.
     *
     * @param string $path -- Context path 
     */
    function remove($path)
    {
        if (array_key_exists($path, $this->_array)) {
            unset($this->_array[$path]); 
        }
    }

    /**
     * Test if the context can resolve specified path.
     *
     * @param string $path 
     *        Path to a context resource.
     *
     * @return boolean
     */
    function has($path)
    {
        if (array_key_exists($path, $this->_array)) {
            return true;
        }
        $resp =& PHPTAL_ArrayResolver::get($this->_array, $path);
        if (PEAR::isError($resp)) {
            return false;
        } else if ($resp === null) {
            return false;
        }
        return true;
    }

    /**
     * Return the context associative object.
     */
    function &getHash()
    {
        return $this->_array;
    }

    /**
     * Retrieve specified context resource.
     *
     * @param string $path
     *        Path to a context resource.
     *        
     * @return mixed
     * @throws NameError
     */
    function &get($path)
    {
        if (array_key_exists($path, $this->_array)) {
            return $this->_array[$path];
        }
        $resp =& PHPTAL_ArrayResolver::get($this->_array, $path);
        if (PEAR::isError($resp)) {
            $this->_errorRaised = $resp; 
        } else if ($resp === null) {
            $this->_errorRaised = true;
        }
        return $resp;
    }

    /**
     * Retrieve specified context resouce as a string object
     *
     * @param string $path
     *        Path to a context resource.
     *
     * @return string
     */
    function &getToString($path)
    {
        $o = $this->get($path);
        if (is_object($o) && method_exists($o, "toString")) {
            return $o->toString();
        }
        if (is_object($o) || is_array($o)) {
            ob_start();
            print_r($o); // var_dump($o);
            $res = ob_get_contents();
            ob_end_clean();
            return $res;
        }
        return $o;
    }
    
    /**
     * Tells if an error was raised by this context accessing an unknown path.
     *
     * @return boolean
     */
    function errorRaised()
    {
        $r = $this->_errorRaised;
        $this->_errorRaised = false;
        return $r;
    }

    /**
     * Clean error handler.
     */
    function cleanError()
    {
        $this->_errorRaised = false;
    }
}

/**
 * Object resolver.
 *
 * Resolver pathes relatives to object.
 *
 * @access private
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_ObjectResolver
{
    function &get(&$obj, $subpath)
    {       
        list($first, $next) = PHPTAL_path_explode($subpath);
        
        if (method_exists($obj, $first)) { 
            // reference to a variable of the handled object
            $value =& $obj->$first(); 
        } elseif (array_key_exists($first, $obj)) {
            // reference to an object variable
            $value =& $obj->$first;
        } elseif (is_a($obj, 'OHash') && $obj->containsKey($first)) {
            return $obj->get($first);
        } elseif (is_a($obj, 'OArray') && preg_match('/^[0-9]+$/', $first)) {
            return $obj->get((int)$first);
        } else {
            $err = new NameError($subpath . " not found");
            return $err;
        }
        // more to resolve in value
        if ($next) {
            return PHPTAL_resolve($next, $value, $obj);
        }
        return $value;        
    }
}


/**
 * Array resolver.
 *
 * Resolve pathes relative to arrays and hashtables.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_ArrayResolver
{
    function &get(&$array, $subpath)
    {
        list($first, $next) = PHPTAL_path_explode($subpath);

        if ($first == "length" && !$next && !array_key_exists("length", $array)) {
            return count($array);
        }
        
        if ($next === false) {
            if ($first == 'count') { return count($array); }
            if ($first == 'keys')  { return array_keys($array); }
            if ($first == 'values'){ return array_values($array); }
            if (!array_key_exists($first, $array)) {
                $err = new NameError("(ArrayResolver) Context does not contains key '$first'");
                return $err;
            }
            return $array[$first];
        }

        if (!array_key_exists($first, $array)) {
            $err = new NameError("(ArrayResolver) Context does not contains key '$first'");
            return $err;
        }
        $temp =& $array[$first];
        if ($temp) {
            return PHPTAL_resolve($next, $temp, $array);
        }
    }
}


/**
 * String resolver.
 *
 * Add method to string type.
 */
class PHPTAL_StringResolver
{
    function get(&$str, $subpath)
    {
        list($first, $next) = PHPTAL_path_explode($subpath);
        if ($next) {
            $err = new TypeError("string methods have no sub path (string.$subpath)");
            return $err;
        }
        if ($first == "len") {
            return strlen($str);
        }
        $err = new NameError("string type has no method named '$first'");
        return $err;
    }
};

/**
 * Retrieve a resolver given a value and a parent.
 */
function &PHPTAL_resolve($path, &$value, &$parent)
{
    if (is_array($value))  return PHPTAL_ArrayResolver::get($value, $path);
    if (is_object($value)) return PHPTAL_ObjectResolver::get($value, $path);
    if (is_string($value)) return PHPTAL_StringResolver::get($value, $path);
    $err = new TypeError("unable to find adequate resolver for '".gettype($value)."' remaining path is '$path'");
    return $err;
}

/**
 * Shift the first elemetn of a phptal path.
 *
 * Returns an array containing the first element of the path and the rest of
 * the path.
 */
function PHPTAL_path_explode($path)
{
    if (preg_match('|^(.*?)\/(.*?)$|', $path, $match)) {
        array_shift($match);
        return $match;
    } else {
        return array($path, false);
    }

    $pos = strpos($path,".");
    if ($pos !== false) { 
        $first = substr($path, 0, $pos);
        $next  = substr($path, $pos+1);
        return array($first, $next);
    }
    return array($path, false);
}

?>
