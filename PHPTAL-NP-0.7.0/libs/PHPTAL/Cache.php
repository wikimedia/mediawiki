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
 * Interface to PHPTAL cache system.
 *
 * Implement this interface and use PHPTAL_Template::setCacheManager() method
 * to intercept macro and template execution with your own cache system.
 *
 * The aim of this system is to fine grain the caching of PHPTAL results
 * allowing the php coder to use whatever cache system he prefers with a 
 * good granularity as he can cache result of specific templates execution, 
 * and specific macros calls.
 *
 * @see         Documentation.txt 
 * @author      Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_Cache
{
    /**
     * Called each time a template has to be executed.
     *
     * This method must return the cache value or the template execution 
     * return.
     *
     * function template(&$tpl, $path, &$context)
     * {
     *     // return cache if exists
     *     // else realy process template
     *     $res = $tpl->_process();
     *     // cache the result if needed
     *     // and return it
     *     return $res;
     * }
     *
     * @param PHPTAL_Template     -- the template that must be cached/executed
     * @param string $path        -- the template path
     * @param PHPTAL_Context $ctx -- the execution context
     * 
     * @return string
     */
    function template(&$tpl, $path, &$context)
    {
        return $tpl->_process();
    }
    

    /**
     * Called each time a macro needs to be executed.
     *
     * This method allow cache on macro result. It must return the cache value
     * or at least the macro execution result.
     *
     * function macro(&$macro, $file, $name, &$context)
     * {
     *     // return cache if exists
     *     // else really process macro
     *     $res = $macro->_process();
     *     // cache the result if needed
     *     // and return it
     *     return $res
     * }
     * 
     *
     * @param PHPTAL_Macro -- the macro to executed
     * @param string $file -- the macro source file
     * @param string $name -- the macro name
     * @param PHPTAL_Context $context -- the current execution context
     *
     * @return string
     */
    function macro(&$macro, $file, $name, &$context)
    {
        return $macro->_process();
    }
}

?>
