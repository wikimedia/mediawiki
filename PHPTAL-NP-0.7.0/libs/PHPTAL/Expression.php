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

define('_PHPTAL_ES_RECEIVER_IS_CONTEXT', 1);
define('_PHPTAL_ES_RECEIVER_IS_NONE', 2);
define('_PHPTAL_ES_RECEIVER_IS_TEMP', 4);
define('_PHPTAL_ES_RECEIVER_IS_OUTPUT', 8);


// Important note:
//
// The PHPTALES system works quite well but it's not yet fully optimized, it 
// should be refactored at some points of my TODO list.
//


class PHPTAL_Expression
{
    var $_tag;
    var $_gen;
    var $_src;
    var $_subs = array();
    var $_policy;
    var $_receiver = false;
    var $_prepared = false;
    var $_structure = false;

    function PHPTAL_Expression(&$generator, &$tag, $str)
    {
        $this->_tag =& $tag;
        $this->_gen =& $generator;
        $this->_src = $str;
    }

    function setPolicy($policy)
    {
        $this->_policy = $policy;
    }

    /**
     * Prepare the expression.
     *
     * This method explode the expression into sub expression and prepare each 
     * expression for parsing.
     *
     * @throws PHPTAL_ExpressionError 
     *         If receiver policy fail.
     */
    function prepare()
    {
        if ($this->_prepared) { return; }
        $test = $this->_src;

        // some sub expression detected
        while (preg_match('/^(.*?)(?<!;);(?!;)/sm', $test, $m)) {
            list($src, $exp) = $m;

            $x = new PHPTAL_Expression($this->_gen, $this->_tag, $exp);
            $x->setPolicy($this->_policy);
            $x->setReceiver($this->getReceiver());
            $err = $x->prepare();
            if (PEAR::isError($err)) { 
                return $err; 
            }
            $this->_subs[] = $x;

            $test = substr($test, strlen($src));
        }
        // if subs, insert last one
        if ($this->countSubs() > 0 && strlen(trim($test)) > 0) {
            $exp = $test;
            $x = new PHPTAL_Expression($this->_gen, $this->_tag, $exp);
            $x->setPolicy($this->_policy);
            $x->setReceiver($this->getReceiver());
            $err = $x->prepare();
            if (PEAR::isError($err)) { 
                return $err; 
            }
            $this->_subs[] = $x;
        } else {        
            // otherwise, just remove expression delimiters from source
            // and apply the receiver policy
            $exp = $test;
            $exp = str_replace(';;', ';', $exp);
            $this->_src = $exp;
            if (strlen($exp) == 0) return;

            $err = $this->_extractReceiver();
            if (PEAR::isError($err)){ 
                return $err; 
            }

            if (!$this->_receiver 
                && ($this->_policy & _PHPTAL_ES_RECEIVER_IS_CONTEXT 
                    || $this->_policy & _PHPTAL_ES_RECEIVER_IS_TEMP)) {
                $str = sprintf('Receiver required in expression \'%s\' from %s:%d',
                               $this->_src, 
                               $this->_tag->_parser->_file, 
                               $this->_tag->line);
                $err = new PHPTAL_ExpressionError($str);
                return PEAR::raiseError($err);
            }

            if ($this->_policy & _PHPTAL_ES_RECEIVER_IS_NONE && $this->_receiver) {
                $str = sprintf('Unexpected receiver \'%s\' in  expression \'%s\' from %s:%d', 
                               $this->_receiver, 
                               $this->_src,
                               $this->_tag->_parser->_file, 
                               $this->_tag->line);
                $err = new PHPTAL_ExpressionError($str);
                return PEAR::raiseError($err);
            }
        }

        $this->_prepared = true;
    }

    function _extractReceiver()
    {
        global $_phptal_es_namespaces;
        
        $this->_src = preg_replace('/^\s+/sm', '', $this->_src); 
        if (preg_match('/^([a-z:A-Z_0-9]+)\s+([^\|].*?)$/sm', $this->_src, $m)) {
            // the receiver looks like xxxx:aaaa
            //
            // we must ensure that it's not a known phptales namespaces
            if (preg_match('/^([a-zA-Z_0-9]+):/', $m[1], $sub)) {
                $ns = $sub[1];
                // known namespace, just break
                if (function_exists('phptal_es_'.$ns)) {
                    // in_array(strtolower($ns), $_phptal_es_namespaces)) {
                    return;
                }
            }
            
            if ($this->_receiver) {
                $str = sprintf('Receiver already set to \'%s\' in \'%s\'', 
                               $this->_receiver, 
                               $this->_src);
                $err = new PHPTAL_ExpressionError($str);
                return PEAR::raiseError($err);
            }
            
            $this->_receiver = $m[1];
            // 
            // that the way to replace : in setters (usually this this should
            // only be used under tal:attributes tag !!!!
            // 
            $this->_receiver = str_replace(':', '__phptales_dd__', $this->_receiver);
            $this->_src      = $m[2];
            if ($this->_receiver == "structure") {
                $this->_structure = true;
                $this->_receiver  = false;
                $this->_extractReceiver();
            }
        }
    }

    /** 
     * Retrieve the number of sub expressions.
     */
    function countSubs()
    {
        return count($this->_subs);
    }

    function &subs()
    {
        return $this->_subs;
    }

    /**
     * Returns true if a receiver is set for this expression.
     */
    function hasReceiver()
    {
        return $this->_receiver != false;
    }

    /**
     * Retrieve receiver's name.
     */
    function getReceiver()
    {
        return $this->_receiver;
    }

    /**
     * Set expression receiver.
     */
    function setReceiver($name)
    {
        $this->_receiver = $name;
    }

    /**
     * Generate php code for this expression.
     */
    function generate()
    {
        $err = $this->prepare();
        if (PEAR::isError($err)) {
            return $err;
        }

        if ($this->countSubs() > 0) {
            foreach ($this->_subs as $sub) {
                $err = $sub->generate();
                if (PEAR::isError($err)) {
                    return $err;
                }
            }
        } else {
            $exp = $this->_src;
            if (strlen($exp) == 0) return;

            // expression may be composed of alternatives | list of expression
            // they are evaluated with a specific policy : _PHPTAL_ES_SEQUENCE
            //
            // 'string:' break the sequence as no alternative exists after a
            // string which is always true.
            if (preg_match('/\s*?\|\s*?string:/sm', $exp, $m)) {
                $search = $m[0];
                $str = strpos($exp, $search);
                // $str = strpos($exp, ' | string:');
                // if ($str !== false) {
                $seq  = preg_split('/(\s*?\|\s*?)/sm', substr($exp, 0, $str));
                $seq[]= substr($exp, $str + 2);
            } else {
                $seq = preg_split('/(\s*?\|\s*?)/sm', $exp);
            }

            // not a sequence
            if (count($seq) == 1) {
                $code = PHPTAL_Expression::_GetCode($this, $exp);
                if (PEAR::isError($code)) { return $code; }
                
                $temp = $this->_gen->newTemporaryVar();
                $this->_gen->doAffectResult($temp, $code);
                return $this->_useResult($temp);
            } else {
                return $this->_evaluateSequence($seq);
            }
        }
    }


    function _evaluateSequence($seq)
    {
        $temp = $this->_gen->newTemporaryVar();
        $this->_gen->doIf('!$__ctx__->_errorRaised');
        $this->_gen->doDo();
        foreach ($seq as $s) {
            // skip empty parts
            if (strlen(trim($s)) > 0) {
                $code = PHPTAL_Expression::_GetCode($this, $s);
                if (PEAR::isError($code)) { return $code; }

                $this->_gen->doUnset($temp);
                $this->_gen->doAffectResult($temp, $code);
                $this->_gen->doIf('!PEAR::isError(' . $temp . ') && ' . 
                                  // $temp .' != false && '.
                                  $temp . ' !== null');
                $this->_gen->execute('$__ctx__->_errorRaised = false');
                $this->_gen->execute('break');
                $this->_gen->endBlock();
            }
        }
        $this->_gen->doEndDoWhile('0');
        // test errorRaised
        $this->_gen->doElse();
        
        // $this->_gen->doAffectResult($temp, '""'); // $__ctx__->_errorRaised');
        $this->_gen->doAffectResult($temp, '$__ctx__->_errorRaised');
        $this->_gen->execute('$__ctx__->_errorRaised = false');
        $this->_gen->endBlock();
        // ----------------

        $err = $this->_useResult($temp);
                                    
        // $this->_gen->endBlock();
        return $err;
    }
    
    function _useResult($temp)
    {
        if ($this->_policy & _PHPTAL_ES_RECEIVER_IS_TEMP) {
            $this->_gen->doReference($this->_receiver, $temp);
        } else if ($this->_policy & _PHPTAL_ES_RECEIVER_IS_OUTPUT) {
            $this->_gen->doPrintVar($temp, $this->_structure);
        } else if ($this->_policy & _PHPTAL_ES_RECEIVER_IS_CONTEXT) {
            $this->_gen->doContextSet($this->_receiver, $temp);
        } else {
            $err = new PHPTAL_ExpressionError("Expression '$this->_src' Don't know what to do with result.");
            return PEAR::raiseError($err);
        }
    }

    /**
     * Retrieve a function namespace for given string and the associated
     * expression.
     *
     * Examples:
     *
     * The function namespace of 'php:XXXX' is 'php'
     * The function namespace of 'XXXX' is 'path'
     * The function namespace of 'foo:bar::baz' is 'foo'
     * 
     * @param string $str 
     *        Expression string without receiver
     *        
     * @return array
     *        An array composed as follow : array('ns', 'exp'),
     *        Where 'ns' is the function namespace and 'exp', is the 
     *        source string without the 'ns:' part.
     */
    function _FindFunctionNamespace($str)
    {
        $str = preg_replace('/^\s/sm', '', $str);
        if (preg_match('/^([a-z0-9\-]+):(?!>:)(.*?)$/ism', $str, $m)) {
            list($ns, $path) = array_slice($m, 1);
            $ns = str_replace('-', '_', $ns);
            return array($ns, $path);
        }
        return array('path', $str);
    }

    /**
     * Get the code for a ns:args string.
     */
    function _GetCode(&$exp, $str)
    {
        list($ns, $args) = PHPTAL_Expression::_FindFunctionNamespace($str);
        $func = "PHPTAL_ES_$ns";
        if (!function_exists($func)) {
            $err = new PHPTAL_ExpressionError("Unknown function $func in '$str'");
            return PEAR::raiseError($err);
        }
        return $func($exp, $args);
    }
}

/**
 * Thrown on expression parsing error.
 */
class PHPTAL_ExpressionError extends PEAR_Error 
{
}

?>
