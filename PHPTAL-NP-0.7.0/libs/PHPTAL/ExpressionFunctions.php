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
 * @var $_phptal_es_namespaces
 * @type array
 *
 * This array contains phptales namespaces, it means php:, string:, exists:,
 * not: and path: at this time.
 */
global $_phptal_es_namespaces;
$_phptal_es_namespaces = array('not', 'php', 'string', 'exists', 'path', 'not-exists');

/**
 * @var $_phptal_es_keywords
 * @type array
 *
 * List of reserved phptales keywords.
 */
global $_phptal_es_keywords;
$_phptal_es_keywords = array('nothing', 'default', 'structure');

global $_phptal_es_php_types;
$_phptal_es_php_types = array('true', 'false', 'null');

/**
 * string: expression type
 */
function PHPTAL_ES_string(&$exp, $value)
{
    $value = PHPTAL_ES_path_in_string($value);
    // $value = str_replace('$$', '\$\$', $value);
    $value = '"' . $value . '"';
    $value = preg_replace('/^\" ?\"\. /', '', $value);
    $value = preg_replace('/\.\"\"$/', '', $value);
    return $value;
}

/**
 * not: expression type
 */
function PHPTAL_ES_not(&$exp, $value)
{
    return "! " . PHPTAL_ES_path($exp, $value);
}

/**
 * not-exists: expression type
 */
function PHPTAL_ES_not_exists(&$exp, $value) 
{
    return "! " . PHPTAL_ES_exists($exp, $value);
}

/**
 * path: expression type
 *
 * @todo default  -- existing text inside tag
 *       options  -- ?? keyword arguments passed to template ??
 *       repeat   -- repeat variables
 *       attrs    -- initial values of tag attributes
 *       CONTEXTS -- list of standard names (above list) in case one of these
 *                   names was hidden by a local variable.
 */
function PHPTAL_ES_path(&$exp, $value)
{
    $value = trim($value);
    // special case : 'string' authorized
    if ($value[0] == "'") { return PHPTAL_ES_path_in_string($value,"'");  }
    // number case
    if (preg_match('/^[0-9\.]*$/', $value)) { return $value; }
    // php constants are accessed though @ keyword
    if (preg_match('/^@[_a-z][0-9a-z_]*$/i', $value)) { return substr($value, 1); }
    if ($value == "nothing") { return 'null'; }
    if ($value == "default") { return '$__default__'; }
    $value = PHPTAL_ES_path_in_string($value);
    return '$__ctx__->get("'. $value .'")';
}


function PHPTAL_ES_path_toString(&$exp, $value)
{
    $value = trim($value);
    if ($value == "nothing") { return 'null'; }
    if ($value == "default") { return '$__default__'; }
    if (preg_match('/^@[_a-z][0-9a-z_]*$/i', $value)) { return substr($value, 1); }
# HACKHACKHACKHACKHACK! <- for fixing ${foo} not being escaped.
# In PHPTAL 1.0.0 it _is_ escaped, and apparently it's supposed to be.
# We need to be consistent to work on both.
#    return '$__ctx__->getToString("'. $value .'")';
    return 'htmlspecialchars($__ctx__->get("'. $value .'"))';
}


/**
 * exists: expression type
 */
function PHPTAL_ES_exists(&$exp, $value)
{
    return '$__ctx__->has("'. trim($value). '")';
}

/**
 * php: expression type
 */
function PHPTAL_ES_php(&$exp, $str)
{
    $parser = new PHPTAL_ES_PHP_Parser($exp, $str);
    return $parser->evaluate();
}


//
// utility functions (internal)
//

function PHPTAL_ES_path_in_string($arg, $c='"')
{
    list($match,$repl) = _PHPTAL_context_accessed($arg);
    for ($i=0; $i<count($match); $i++) {
        $null = "";
        $repl[$i] = $c . ". " . PHPTAL_ES_path_toString($null, $repl[$i]) . " ." . $c;
        $pos = strpos($arg, $match[$i]);
        $arg = substr($arg, 0, $pos)
            .  $repl[$i]
            .  substr($arg, $pos + strlen($match[$i]));
    }
    if ($c == '"') {
        $arg = str_replace('$$','\$', $arg);
    } else {
        $arg = str_replace('$$', '$', $arg);
    }
    return $arg;
}

function _PHPTAL_context_accessed($str)
{
    if (preg_match_all('/((?<!\$)\$\{?([@_a-zA-z0-9.\/\-]+)\}?)/', $str, $match)) {
        return array_slice($match, 1);
    }
    return array(array(),array());
}

//
// php: expression parser
//

class PHPTAL_ES_PHP_Parser
{
    var $_exp;
    var $_gen;
    var $_str;
    var $_aliases = array();
    var $_code    = "";
    var $_last_was_array = false;

    function PHPTAL_ES_PHP_Parser(&$expression, $str)
    {
        $this->_exp =& $expression;
        $this->_gen =& $expression->_gen;
        $this->_str =  $str;
    }

    function evaluate()
    {
        $value   = $this->_str;
        $strings = array();

        // extract strings and replace context calls
        if (preg_match_all('/(\'.*?(?<!\\\)\')/sm', $value, $m)) {
            list(,$m) = $m;
            foreach ($m as $str) {
                $s_rplc = PHPTAL_ES_path_in_string($str, "'");
                $s_rplc = preg_replace('/^\' ?\'\. /', '', $s_rplc);
                $s_rplc = preg_replace('/\.\'\'$/', '', $s_rplc);
                $value = str_replace($str, '#_STRING_'. count($strings) . '_#', $value);
                $strings[] = $s_rplc;
            }
        }

        list($match, $replc) = _PHPTAL_context_accessed($value);
        $contexts = array();
        foreach ($match as $m) {
            $i = count($contexts);
            $contexts[] = $replc[$i];
            $value = str_replace($m, '#_CONTEXT_'. $i . '_#', $value);
        }

        // replace or, and, lt, gt, etc...
        $value = $this->_php_test_modifiers($value);
        $value = $this->_php_vars($value);

        // restore strings
        $i = 0;
        foreach ($strings as $str) {
            $value = str_replace('#_STRING_'. $i . '_#', $str, $value);
            $i++;
        }

        $i = 0;
        foreach ($contexts as $c) {
            $value = str_replace('#_CONTEXT_' . $i . '_#', PHPTAL_ES_path($this->_exp, $c), $value);
            $i++;
        }

        // experimental, compile php: content

        require_once PT_IP . "/Types/Code.php";
        
        $code = new Code();
        $code->setCode($value .";");
        $err = $code->compile();
        if (PEAR::isError($err)) {
            return $this->_raiseCompileError($value, $err);
        }
        
        return $value;
    }

    function _php_test_modifiers($exp)
    {
        $exp = preg_replace('/\bnot\b/i', ' !', $exp);
        $exp = preg_replace('/\bne\b/i', ' != ', $exp);
        $exp = preg_replace('/\band\b/i', ' && ', $exp);
        $exp = preg_replace('/\bor\b/i', ' || ', $exp);
        $exp = preg_replace('/\blt\b/i', ' < ', $exp);
        $exp = preg_replace('/\bgt\b/i', ' > ', $exp);
        $exp = preg_replace('/\bge\b/i', ' >= ', $exp);
        $exp = preg_replace('/\ble\b/i', ' <= ', $exp);
        $exp = preg_replace('/\beq\b/i', ' == ', $exp);
        return $exp;
    }

    function _php_vars($arg)
    {
        $arg = preg_replace('/\s*\/\s*/',' / ', $arg);
        $arg = preg_replace("/\s*\(\s*/","(", $arg );
        $arg = preg_replace('/\s*\)\s*/',') ', $arg );
        $arg = preg_replace('/\s*\[\s*/','[', $arg );
        $arg = preg_replace('/\s*\]\s*/',']', $arg );
        $arg = preg_replace('/\s*,\s*/',' , ', $arg );

        $result = "";
        $path = false;
        $last_path = false;
        $last_was_array = false;

        $i = 0;
        while ($i < strlen($arg)) {
            $c = $arg[$i];
            if (preg_match('/[a-z_]/i', $c)) {
                $path .= $c;
            } else if (preg_match('/[0-9]/', $c) && $path) {
                $path .= $c;
            } else if (preg_match('/[\/\.]/', $c) && $path) {
                $last_path = $path;
                $path .= $c;
            } else if (preg_match('/[\/\.]/', $c) && $this->_last_was_array) {
                $result .= '->';
            } else if ($c == '(') {
                if ($last_path) {
                    $result .= $this->pathRequest($last_path);
                    $result .= '->';
                    $path    = substr($path, strlen($last_path) +1);
                    $last_path = false;
                }
                $result .= $path . '(';
                $this->_last_was_array = false;
                $path = false;
            } else if ($c == '#') {
                if ($path) {
                    $result .= $this->pathRequest($path);
                    $path    = false;
                    $last_path = false;
                }
                $next = strpos($arg, '#', $i+1);
                $result .= substr($arg, $i, $next - $i +1);
                $i = $next;
            } else if ($c == ':') {
                if ($arg[$i+1] != ':') { // error
                }
                $i++;
                $result .= $path;
                $result .= '::';
                $path = false;
                $last_path = false;
                
            } else if ($c == '@') {
                // access to defined variable, append to result
                // up to non word character
                $i++;   // skip @ character
                do {
                    $c = $arg[$i];
                    $result .= $c;
                    $i++;
                } while ($i < strlen($arg) && preg_match('/[a-z_0-9]/i', $arg[$i]));
                $i--;   // reset last character
                
            } else {
                if ($path) {
                    $result .= $this->pathRequest($path);
                    $path    = false;
                }
                $result .= $c;
                if ($c == ']') {
                    $last_path = false;
                    $path = false;
                    $this->_last_was_array = true;
                } else {
                    $this->_last_was_array = false;
                }
            }
            $i++;
        }
        
        // don't forget the last bit
        if (isset($path) && $path) {
            $result .= $this->pathRequest($path);
        }

        return $result;
    }

    function pathRequest($path)
    {
        global $_phptal_es_php_types;

        if (!preg_match('/[a-z]/i', $path)){ 
            return $path; 
        }
        if ($this->_last_was_array) { 
            return str_replace('.', '->', $path); 
        }
        if (in_array($path, $_phptal_es_php_types)) {
            return $path;
        }

        $concatenate = false;
        $path = str_replace('.','/', $path);
        if (substr($path,-1) == '/') { 
            $path = substr($path, 0, -1);
            $concatenate = true;
        }

        if (array_key_exists($path, $this->_aliases)) {
            $res = $this->_aliases[$path];
        } else {
            $res = $this->_gen->newTemporaryVar();
            $this->_aliases[$path] = $res;
            $this->_gen->doContextGet($res, $path);
        }

        if ($concatenate) { 
            $res .= ' .'; 
        }
        return $res;
    }

    function _raiseCompileError($code, $err) 
    {
        $str = sprintf("Expression 'php:' compilation error in %s:%d".endl,
                       $this->_exp->_tag->_parser->_file,
                       $this->_exp->_tag->line);
        return PEAR::raiseError($str);
    }
}


?>
