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

/*
 * Macro invoker.
 */
class PHPTAL_Macro extends PHPTAL_Template
{
    var $_path = false;
    var $_name;
    
    /**
     * Macro constructor.
     *
     * @param PHPTAL_Template $caller 
     *        The macro caller may be a template or a macro.
     * @param string $path 
     *        The macro path/name
     */
    function PHPTAL_Macro(&$caller, $path)
    {
        $this->_name = $path;
        
        // extract macro path part, if none found, we'll assume macro is 
        // in the caller template.

        if (preg_match('/(.*?)\/([a-zA-Z0-9_]*?)$/', $this->_name, $match)) {
            list(, $this->_path, $this->_name) = $match;
        } else {
            $this->_sourceFile = $caller->_sourceFile;
        }

        // call parent constructor
        $this->PHPTAL_Template($this->_path, 
                               $caller->_repository, 
                               $caller->_cacheDir);
        
        $this->setParent($caller);
        $this->setEncoding($caller->getEncoding());
    }
    
    /**
     * Execute macro with caller context.
     *
     * @return string
     */
    function execute()
    {
        if ($this->_path !== false) {
            $err = $this->_prepare();
            if (PEAR::isError($err)) {
                return $err;
            }
        }
        return $this->_cacheManager->macro($this,
                                            $this->_sourceFile,
                                            $this->_name,
                                            $this->_parent->getContext());
    }

    /**
     * Really process macro parsing/invocation.
     *
     * @return string
     */
    function _process()
    {
        if ($this->_path !== false) {
            $err = $this->_load();
            if (PEAR::isError($err)) {
                return $err;
            }
        } else {
            $this->_funcName = $this->_parent->_funcName;
        }            
        
        $func = $this->_funcName . '_' . $this->_name;
        
        if (!function_exists($func)) {
            $err = "Unknown macro '$this->_name'";
            return PEAR::raiseError($err);
        }
        
        return $func($this->_parent);
    }
}

?>
