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

class PHPTAL_OutputControl
{
    var $_buffer = "";
    var $_buffers = array();
    var $_quoteStyle = ENT_COMPAT;
    var $_encoding   = 'UTF-8';
    var $_context;
    
    function PHPTAL_OutputControl(&$context, $encoding='UTF-8')
    {
        $this->_context = $context;
        $this->_encoding = $encoding;
    }

    function pushBuffer()
    {
        $this->_buffers[] = $this->_buffer;
        $this->_buffer = "";
    }
    
    function popBuffer()
    {
        $res = $this->_buffer;
        $this->_buffer = array_pop($this->_buffers);
        return $res;
    }
    
    function write(&$str)
    {
        if (is_object($str)) {
            if (PEAR::isError($str)) {
                $str = "[". get_class($str) . ": " . $str->message . "]";
                // $this->_context->cleanError();
            } else {
                $str = Types::toString($str);
            }
        }

        if (defined("PHPTAL_DIRECT_OUTPUT") && count($this->_buffers) == 0) {
            // echo htmlentities($str);
            // support for cyrillic strings thanks to Igor E. Poteryaev
            echo htmlentities($str, $this->_quoteStyle, $this->_encoding);
        } else {
            // $this->_buffer .= htmlentities($str);
            // support for cyrillic strings thanks to Igor E. Poteryaev
            // **** hacked to htmlspecialchars() to avoid messing with text.
            // **** PHP prior to 4.3.7 contains bugs that mess up Greek.
	    // **** removed encoding parameter, this messed up titles in older versions of php
	    // **** we deal with encoding in Language.php
            $this->_buffer .= htmlspecialchars($str, $this->_quoteStyle);
        }
    }

    function writeStructure($str)
    {
        if (is_object($str)) {
            if (PEAR::isError($str)) {
                $str = "[" . $str->message . "]";
                // $this->_context->cleanError();
            } else {
                $str = Types::toString($str);
            }
        }
        
        if (defined("PHPTAL_DIRECT_OUTPUT") && count($this->_buffers) == 0) {
            echo $str;
        } else {
            $this->_buffer .= $str;
        }
    }

    function &toString()
    {
        return $this->_buffer;
    }
}

?>
