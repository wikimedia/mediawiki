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
// $id$

require_once PT_IP . "/Types/OString.php";
require_once PT_IP . "/Types/OHash.php";

/**
 * Common php string that extract parameters from an associative array.
 *
 * This string is appended to the begin of the produced php function. It
 * takes function context argument and extract it in named variables.
 * 
 * @access private
 */
define('_TYPES_CODE_EXTRACT_CODE_CONTEXT_',
'// BEGIN EXTRACT __context__ 
if (is_array($__context__)) { extract($__context__); }
if (is_object($__context__)) { extract($__context__->toHash()); }
// END EXTRACT __context__
');

/**
 * Code class handle and evaluate php code.
 *
 * The aim of this class is to dynamically generate executable php code from
 * php string.
 *
 * This kind of object can be safely serialized as the code it represents is
 * stored in the _code member variable.
 *
 * When setting code to this object, a new anonymous function is created
 * waiting to be invoqued using the execute() method.
 *
 * As we can't know how many parameters this function should take, a 'context'
 * hashtable is used as only parameter. This hash  may contains any number
 * of arguments with var name compliant keys.
 *
 * Code object automatically append 'extract' code string to produced code.
 *
 * It's up to the code to assert parameters using 'isset()' php function.
 *
 *      <?php
 *      require_once PT_IP . "/Types/Code.php";
 * 
 *      $o_code = Code()
 *      $o_code->setCode('return $foo . $bar . $baz;');
 *      $res = $o_code->execute(
 *          'foo', 'foo value ',
 *          'baz', 'bar value ',
 *          'baz', 'baz value'
 *      );
 *  
 *      // will print 'foo value bar value baz value'
 *      echo $res, end;
 *      ?>
 *  
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class Code 
{
    var $_code;
    var $_function;
    var $_compiled = false;

    /**
     * Construct a new code object.
     *
     * @param mixed $code (optional) 
     *        php code string or object implementing toString method.
     */
    function Code($code=false)
    {
        $this->__construct($code);
    }

    function __construct($code=false)
    {
        if (!$code) { 
            return $this; 
        }
        if (is_object($code) && get_class($code) == 'code') {
            $this->_code = $code->getCode();
        } else {
            $this->setCode(Types::toString($code));
        }
    }
    
    /**
     * Execute code with specific context.
     *
     * @param  mixed ...
     *         The function execution context. This may be an associative
     *         array, an OHash object, a list of key/value pairs that will 
     *         be transformed into an associative array
     *         
     * @return mixed The execution result.
     */
    function &execute()
    {
        if (!$this->_compiled) {
            $err =& $this->compile();
            if (PEAR::isError($err)) { 
                return $err;
            }
        }
        $argv = func_get_args();
        $argc = func_num_args();
        switch ($argc) {
            case 1:
                $context = $argv[0];
                break;
            default:
                $context = OHash::ArrayToHash($argv);
                break;
        }
        $func = $this->_function;
        return $func($context);
    }

    /**
     * Compile php code.
     *
     * This function may produce parse errors.
     *
     * @throws CodeError
     */
    function compile() 
    {
        ob_start();
        $this->_function = create_function('$__context__', $this->_code);
        $ret = ob_get_contents();
        ob_end_clean();

        if (!$this->_function) {
            return PEAR::raiseError($ret);
        }
        
        $this->_compiled = true;
    }
    
    /**
     * Set function code.
     *
     * @param  string $str
     *         The php code string
     */
    function setCode($str)
    {
        // add extract code to function code
        $str = _TYPES_CODE_EXTRACT_CODE_CONTEXT_ . $str;
        $this->_code = $str;
    }
    
    /**
     * Retrieve code.
     *
     * @return string
     */
    function getCode()
    {
        return $this->_code;
    }

    /**
     * On serialization, we store only code, not additional variables.
     * 
     * @access private
     */
    function __sleep()
    {
        return array("_code");
    }

    /**
     * Make a string representation of this object.
     *
     * @return string
     */
    function toString()
    {
        if ($this->_compiled) {
            return '<Code \''.$this->_function.'\'>';
        } else {
            return '<Code \'not compiled\'>';
        }
    }
}

/**
 * Code compilation error.
 *
 * This error handles parse errors an other problems that may occurs while
 * compiling a php code.
 */
class CodeError extends PEAR_Error 
{
}

?>
