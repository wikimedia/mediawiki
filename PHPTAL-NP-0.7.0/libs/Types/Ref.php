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
 * Create a new Ref object referencing specified object or variable.
 *
 * Any modification of the source object modify the Refence content, any
 * modification of the reference obj modify the source object.
 *
 * The referenced object can be reached using the reference object using the
 * 'obj' property.
 *
 * <?php
 *
 * $a = new Foo();
 * $ref_a = Ref($a);
 *
 * $ref_a->obj->doBar();   // call doBar() on $a
 *
 * ?>
 *
 * @param mixed $obj -- the object or variable to reference.
 * @return Ref
 */
function ref(&$obj)
{
    return new Ref($obj);
}

/**
 * Returns true if the object is a Ref object.
 * 
 * @param mixed $obj -- any variable that must be tested.
 *
 * @return boolean
 */
function is_ref($obj)
{
    return is_object($obj) && get_class($obj) == "ref";
}


/**
 * Reference class.
 *
 * There's a lot to say about references in PHP. The main problem come from the
 * fact that until ZendEngine2, objects are copied if not referenced.
 *
 * This class helps keeping references to objects even while modifying arrays.
 *
 * Example :
 * 
 * If an array stores objects, using array_values will copy its
 * elements.
 *
 * If an array stores Ref objects referencing real objects, array_values copy
 * Ref objects but these copies still reference the source object.
 *
 * Until ZendEngine2, it is safer to use Ref objects in stead of object
 * references.
 *
 * <?php
 * 
 * $a = new Foo('bar');
 * $v = new Vector();
 * $v->push(Ref($a));
 * 
 * ?>
 *
 * Important :
 *
 * This object will have no meaning in php 4.4 as PHP will then use references
 * everywhere objects are involved.
 * 
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class Ref
{
    /**
     * php reference to data.
     */
    var $obj;
    
    /**
     * Reference constructor.
     *
     * Important:
     *
     * If $r is a reference, the new reference will just be a copy of the
     * parameter.
     *
     * A Ref cannot reference a Ref.
     * 
     * @param mixed $r -- the object or data to reference.
     */
    function Ref(&$r)
    {
        if (is_ref($r)) {
            $this->obj =& $r->obj;
        } else {
            $this->obj =& $r;
        }
    }

    /**
     * Retrieve the type or the referenced variable.
     * 
     * @return string
     */
    function type()
    {
        return gettype($this->obj);
    }
    
    /**
     * Return the class name of the referenced variable (if object).
     *
     * @return string (empty if not an object)
     */
    function getClassName()
    {
        return get_class($this->obj);
    }
}

?>
