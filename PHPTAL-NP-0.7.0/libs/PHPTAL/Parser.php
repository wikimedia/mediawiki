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

$__d = dirname(__FILE__);
require_once _phptal_os_path_join($__d, 'XML_Parser.php');
require_once _phptal_os_path_join($__d, 'Attribute.php');
require_once _phptal_os_path_join($__d, 'Expression.php');
require_once _phptal_os_path_join($__d, 'ExpressionFunctions.php');
require_once _phptal_os_path_join($__d, 'Generator.php');
require_once _phptal_os_path_join($__d, 'Tag.php');

$_t = _phptal_os_path_join($__d, 'Attribute', 'TAL');
require_once _phptal_os_path_join($_t, 'Attributes.php');
require_once _phptal_os_path_join($_t, 'Comment.php');
require_once _phptal_os_path_join($_t, 'Content.php');
require_once _phptal_os_path_join($_t, 'Condition.php');
require_once _phptal_os_path_join($_t, 'Define.php');
require_once _phptal_os_path_join($_t, 'Omit_tag.php');
require_once _phptal_os_path_join($_t, 'Replace.php');
require_once _phptal_os_path_join($_t, 'On_error.php');
require_once _phptal_os_path_join($_t, 'Repeat.php');

$_t = _phptal_os_path_join($__d, 'Attribute', 'PHPTAL');
require_once _phptal_os_path_join($_t, 'Include.php');
require_once _phptal_os_path_join($_t, 'Src_include.php');

$_t = _phptal_os_path_join($__d, 'Attribute', 'METAL');
require_once _phptal_os_path_join($_t, 'Define_macro.php');
require_once _phptal_os_path_join($_t, 'Use_macro.php');
require_once _phptal_os_path_join($_t, 'Define_slot.php');
require_once _phptal_os_path_join($_t, 'Fill_slot.php');

$_t = _phptal_os_path_join($__d, 'Attribute', 'I18N');
require_once _phptal_os_path_join($_t, 'Translate.php');
require_once _phptal_os_path_join($_t, 'Name.php');
require_once _phptal_os_path_join($_t, 'Attributes.php');


/**
 * PHPTAL template parser.
 * 
 * This object implements PHPTAL_XML_Parser interface and will accept only 
 * well formed xml templates.
 * 
 * 
 * Parser object has two aims :
 *
 * - generate the template structure tree
 * - generate the php source code this structure represents
 *
 * Once this job is accomplished, the parser object should be destroyed and
 * MUST NOT be used to parse another template. It's a one time and drop 
 * object.
 * 
 *
 * Note about code generation :
 *
 * The final source code is ready to write into a php file.
 * 
 * The code generation process requires a function name which should represent
 * the template unique id (Template class makes an md5 over the source file
 * path to create this id).
 * 
 * @version 0.1
 * @author  Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_Parser extends PHPTAL_XML_Parser
{
    // root gat
    var $_root;
    // document headers (string)
    var $_headers = false;
    var $_started = false;

    var $_docType;
    var $_inDocType;

    // current tag
    var $_current;

    // source file name
    var $_file;

    // activate xhtml empty tags lookup 
    var $_outputFormat = PHPTAL_XHTML;

    // keep xmlns:* attributes ?
    var $_keepXMLNS = false;

    /**
     * Parse a template string.
     * 
     * @param string $file
     *        The template source file.
     * @param string $src
     *        The template source.
     *
     * @throws PHPTAL_ParseError
     */
    function parse($file, $src)
    {
        $this->_file  = $file;
        $this->_initRoot();
        return $this->parseString($src, true);
    }

    /**
     * Generate php code.
     * 
     * After template parsing, this method must be called to generate php code
     * from the template tree.
     * 
     * @param  string func_name 
     *         The template function name
     *         
     * @access private
     * @return string
     */
    function generateCode($func_name)
    {
        $gen = new PHPTAL_Generator($this->_file, $func_name);
        $gen->setHeaders($this->_headers);
        $err = $this->_root->generateCode($gen);
        if (PEAR::isError($err)) {
            return $err;
        }
        return $gen->getCode();
    }

    /**
     * Return a string representation of the underlying xhtml tree.
     *
     * This method is for debug purpose.
     *
     * @access private
     * @return string
     */
    function toString()
    {
        $buf = new StringBuffer();
        $buf->appendln('Template tree [\'', $this->_file, '\']');
        $buf->append($this->_root->toString());
        return $buf->toString();
    }


    // ----------------------------------------------------------------------
    // Private methods
    // ----------------------------------------------------------------------
    
    /**
     * Initialize root node.
     *
     * @access private
     */
    function _initRoot()
    {
        $this->_headers = "";
        $this->_started = false;
        $this->_root    =  new PHPTAL_Tag($this, "#root", array());
        $this->_current =& $this->_root;
    }

    /**
     * Push a node as the current one.
     *
     * @param  PHPTAL_Node tag
     * @access private
     */
    function _push(&$tag)
    {
        $this->_current->addChild($tag);
        unset($this->_current);
        $this->_current =& $tag;
    }

    /**
     * Push a node into the current one.
     *
     * @param  PHPTAL_Node tag
     * @access private
     */
    function _pushChild(&$tag)
    {
        $this->_current->addChild($tag);
    }
    
    /**
     * Pop the last node (go up a level in tree).
     *
     * @access private
     */
    function _pop()
    {
        $temp =& $this->_current;
        unset($this->_current);
        if ($temp != $this->_root) {
            $this->_current =& $temp->getParent();
        } else {
            $this->_current =& $this->_root;
        }
    }

    /*
     * getter/setter for the output mode.
     *
     * @param int $mode optional
     *        PHPTAL_XML or PHPTAL_XHTML
     */
    function _outputMode($mode=false)
    {
        if ($mode !== false) {
            $this->_outputFormat = $mode;
        }
        return $this->_outputFormat;
    }

    // ----------------------------------------------------------------------
    // XML callbacks methods
    // ----------------------------------------------------------------------
    
    /**
     * xml callback
     * 
     * @access private
     */
    function onElementStart($name, $attributes)
    {
        global $_phptal_namespaces;

        $this->_started = true;

        if (strpos($name, ':') !== false) {
            list($domain, $extend) = split(':', strtoupper($name));
            if (($extend == 'BLOCK') && (in_array($domain, $_phptal_namespaces))) {
                $attributes = PHPTAL_Parser::extendZptBlockAttributes($domain, $attributes);
                $attributes['tal:omit-tag'] = '';
            }
        }

        // separate phptal attributes from xhtml ones
        // if an attribute is not found, an error is raised.
        $split = PHPTAL_Parser::TemplateAttributes($attributes);
        if (PEAR::isError($split)) {
            return $split;
        }
        
        // no error, the returned value is a tuple 
        list($phptal, $attributes) = $split;
        
        // sort phptal attributes
        $phptal = PHPTAL_Parser::OrderTemplateAttributes($phptal);
        if (PEAR::isError($phptal)) {
            return $phptal;
        }
        
        // create the tag and add its template attributes
        $tag = new PHPTAL_Tag($this, $name, $attributes);
        foreach ($phptal as $t) { 
            $tag->appendTemplateAttribute($t); 
            unset($t); // $t is appended by reference 
        }
        
        $tag->line = $this->getLineNumber();
        $this->_push($tag);
    }

    /**
     * Extends ZPT attributes withing a *:block element so these attributes can
     * be used by phptal parser.
     *
     * @access private
     */
    function extendZptBlockAttributes($domain, $attributes)
    {
        global $_phptal_dictionary;
        $result = array();
        foreach ($attributes as $key => $value) {
            $expected = strtoupper("$domain:$key");
            if (array_key_exists($expected, $_phptal_dictionary)) {
                $result[$expected] = $value;
            }
        }
        return $result;
    }

    /**
     * xml callback
     * 
     * @access private
     */
    function onElementData($data)
    {
        // ${xxxx} variables are evaluated during code
        // generation whithin the CodeGenerator under the 
        // printString() method.
        $tag = new PHPTAL_Tag($this, "#cdata", array());
        $tag->setContent($data);
        $this->_pushChild($tag);
    }

    /**
     * xml callback
     * 
     * @access private
     */
    function onSpecific($data)
    {
        // fix xml parser '&' => '&amp;' automatic conversion
	    $data = str_replace('&amp;', '&', $data);

        if ($this->_current->name() == "#root" && !$this->_started) { 
            $this->_headers .= $data;
            return;
        }
        $tag = new PHPTAL_Tag($this, "#cdata", array());
        $tag->setContent($data);
        $this->_pushChild($tag);
    }

    /**
     * xml callback
     * 
     * @access private
     */
    function onElementClose($name)
    {
        if ($this->_current == null) {
            return $this->_raiseNoTagExpected($name);
        }
        if ($this->_current->name() != $name) {
            return $this->_raiseUnexpectedTagClosure($name);
        }
        $this->_pop();
    }

    // ----------------------------------------------------------------------
    // Static methods
    // ----------------------------------------------------------------------

    /**
     * Lookup template attributes in given hashtable.
     *
     * This method separate xml attributes from template attributes
     * and return an array composed of the array of formers and the array of
     * laters.
     * 
     * @access private 
     * @static 1
     * 
     * @param   hashtable attrs 
     *          Attributes hash
     *          
     * @return  array
     */
    function TemplateAttributes($attrs)
    {
        global $_phptal_dictionary, $_phptal_aliases, $_phptal_namespaces;
        $phptal = array();
        $att = array();
        
        foreach ($attrs as $key=>$exp) {
            
            $test_key = strtoupper($key);
            $ns = preg_replace('/(:.*?)$/', '', $test_key);
            $sns = preg_replace('/^(.*?:)/', '', $test_key);
            // dictionary lookup
            if (array_key_exists($test_key, $_phptal_dictionary)) {
                $phptal[$test_key] = $exp;
            }
            // alias lookup
            elseif (array_key_exists($test_key, $_phptal_aliases)) {
                $phptal[ $_phptal_aliases[$test_key] ] = $exp;
            }
            // the namespace is known but the the attribute is not
            elseif (in_array($ns, $_phptal_namespaces)) {
                return $this->_raiseUnknownAttribute($test_key);
            }
            // regular xml/xhtml attribute (skip namespaces declaration)
            elseif ($ns !== 'XMLNS' || $this->_keepXMLNS 
                    || !in_array($sns, $_phptal_namespaces)) {
                $att[$key] = $exp;
            }
        }
        return array($phptal, $att);
    }

    /**
     * Order phptal attributes array using $_phptal_rules_order array.
     * 
     * @static  1
     * @access  private
     * 
     * @param   array phptal 
     *          Array of phptal attributes (will be modified)
     */
    function OrderTemplateAttributes(&$phptal)
    {
        global $_phptal_rules_order, $_phptal_dictionary;
        
        // order elements by their name using the rule table       
        $result = array();
        foreach ($phptal as $akey=>$exp) {
            
            // retrieve attribute handler class
            $class = "PHPTAL_ATTRIBUTE_" . str_replace(":", "_", $akey);
            $class = str_replace("-", "_", $class);
            if (!class_exists($class)) {
                return $this->_raiseAttributeNotFound($akey, $class);
            }

            $hdl = new $class($exp);
            $hdl->name = $akey;
            $hdl->_phptal_type = $_phptal_dictionary[$akey];

            // resolve attributes conflict
            $pos = $_phptal_rules_order[$akey];
            if (array_key_exists($pos, $result)) {
                return $this->_raiseAttConflict($akey, $result[$pos]->name);
            }
            
            // order elements by their order rule
            $result[$_phptal_rules_order[$akey]] = $hdl;
            unset($hdl);
        }
        return $result;
    }

    // ----------------------------------------------------------------------
    // Errors raising methods
    // ----------------------------------------------------------------------

    function _raiseAttributeNotFound($att, $class)
    {
        $str = sprintf("Attribute '%s' exists in dictionary but class '%s' ".
                       "was not found",
                       $att, $class);
        $err = new PHPTAL_ParseError($str);
        return PEAR::raiseError($err);
    }
    
    function _raiseUnknownAttribute($att)
    {
        $str = sprintf("Unknown PHPTAL attribute '%s' in %s at line %d",
                       $att, $this->_file, $this->getLineNumber());
        $err = new PHPTAL_UnknownAttribute($str);
        return PEAR::raiseError($err);
    }

    function _raiseUnexpectedTagClosure($name)
    {
        $str = sprintf("Non matching tag '%s' error in xml file '%s' at line %d" 
                       . endl . "waiting for end of tag '%s' declared at line %d.",
                       $name, $this->_file, $this->getLineNumber(), 
                       $this->_current->name(), $this->_current->line);
        $err = new PHPTAL_ParseError($str);
        return PEAR::raiseError($err);
    }

    function _raiseNoTagExpected($name)
    {
        $str = sprintf("Bad xml error in xml file '%s' at line %d". endl
                       ."Found closing tag '%s' while no current tag is waited.",
                       $this->_file, $this->getLineNumber(), $name);
        $err = new PHPTAL_ParseError($str);
        return PEAR::raiseError($err);
    }

    function _raiseAttConflict($a2, $a1)
    {
        $str = sprintf('Template Attribute conflict in \'%s\' at line %d'. endl
                       . ' %s must not be used in the same tag as %s', 
                       $this->_file, $this->getLineNumber(), $a1, $a2);
        $err = new PHPTAL_AttributeConflict($str);
        return PEAR::raiseError($err);
    }
};

/**
 * Error raised at parse time when some bad xml is found or when some tag
 * is unknown or is badly used.
 */
class PHPTAL_ParseError extends PEAR_Error {}

/**
 * Error raised when an unknown PHPTAL tag is requested by the template.
 */
class PHPTAL_UnknownAttribute extends PHPTAL_ParseError {}

/**
 * Error raised when a template attribute conflicts with another one.
 */
class PHPTAL_AttributeConflict extends PHPTAL_ParseError {}


?>
