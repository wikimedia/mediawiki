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
 * Represents an xhtml entity.
 *
 * To obtain a fine granularity of code generation the Tag code generation 
 * process is divided into logical sub sequences.
 * 
 * surround head                // call surround attributes
 *    replace                   // call replace and skip head -> foo sequence
 *    || head                   // echo tag start (see disableHeadFoot())
 *            content           // call content replace and skip cdata seq
 *            || cdata && child // echo tag content and generate children code
 *       foot                   // echo tag end   (see disableHeadFoot())
 * surround foot                // call surround attributes
 *  
 *
 * Some attributes like 'omit' requires to disable the head / foot sequences 
 * whithout touching content sequences, the disableHeadFoot() method must be
 * used in that aim.
 * 
 * @version 0.1
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_Tag 
{
    /**
     * @var int $line The template line which produced this tag.
     */
    var $line = 0;
    
    // tree properties
    
    var $_name = "#cdata";
    var $_attrs = array();
    var $_children = array();
    var $_content = "";
    var $_parent = null;

    // template system properties
    
    var $_parser;
    var $_replace_attributes = array();
    var $_content_attributes = array();
    var $_surround_attributes = array();
    var $_head_foot_disabled = false;


    /**
     * Tag constructor.
     *
     * @param string $name
     *        The tag name.
     *
     * @param hashtable $attrs
     *        Tag xhtml attributes.
     */
    function PHPTAL_Tag(&$parser, $name, $attrs)
    {
        $this->_parser =& $parser;
        $this->_name   = $name;
        $this->_attrs  = $attrs;
    }

    function appendTemplateAttribute(&$hdlr)
    {
        switch ($hdlr->_phptal_type)
        {
            case _PHPTAL_REPLACE:
                $this->_replace_attributes[] =& $hdlr;
                break;
            case _PHPTAL_CONTENT:
                $this->_content_attributes[] =& $hdlr;
                break;
            case _PHPTAL_SURROUND:
                $this->_surround_attributes[] =& $hdlr;
                break;
            default:
                return PEAR::raiseError(
                "PHPTAL internal error : bad attribute type : "
                . get_class($hdlr));
        }
    }

    function name()
    {
        return $this->_name;
    }

    function isData()
    {
        return $this->_name == "#cdata";
    }

    function attributes()
    {
        return $this->_attrs;
    }

    function setParent(&$node)
    {
        $this->_parent =& $node;
    }

    function &getParent()
    {
        return $this->_parent;
    }
    
    function addChild(&$node)
    {
        $node->setParent($this);
        $this->_children[] = &$node;
    }
    
    function setContent($str)
    {
        $this->_content = $str;
    }

    function hasContent()
    {
        return (count($this->_content_attributes) == 0 
            ||  count($this->_children) == 0 
            ||  strlen($this->_content) == 0);
    }

    function toString($tab="")
    {
        $buf = new StringBuffer();
        $buf->appendln($tab, '+ node ', $this->name());
        for ($i=0; $i < count($this->_children); $i++) {
            $child =& $this->_children[$i];
            $buf->append($child->toString($tab . "  "));
            unset($child);
        }
        return $buf->toString();
    }    

    function generateCode(&$g)
    {
        // if text node, just print the content and return
        if ($this->_name == "#cdata") {
            $g->doPrintString($this->_content);
            return;
        }

        if ($this->_name == "#root") {
            return $this->generateContent($g);
        }
        
        // if replace attributes exists, they will handle
        // this tag rendering
        if (count($this->_replace_attributes) > 0) {
            $err = $this->surroundHead($g);
            if (PEAR::isError($err)) { return $err; }
            
            for ($i=0; $i<count($this->_replace_attributes); $i++) {
                $h =& $this->_replace_attributes[$i];
                $err = $h->activate($g, $this);
                if (PEAR::isError($err)) { return $err; }
                unset($h);
            }
            return $this->surroundFoot($g);
        }

        $err = $this->surroundHead($g);
        if (PEAR::isError($err)) { return $err; }

        $this->printHead($g);
        
        $err = $this->generateContent($g);
        if (PEAR::isError($err)) { return $err; }
  
        $this->printFoot($g);

        $err = $this->surroundFoot($g); 
        if (PEAR::isError($err)) { return $err; }
    }

    function printHead(&$g)
    {
        if ($this->headFootDisabled()) return;

        $g->doPrintString('<', $this->_name);

        $this->printAttributes($g);
        
        if ($this->hasContent() && !$this->_isXHTMLEmptyElement()) {
            $g->doPrintString('>');
        } else {
            $g->doPrintString('/>');
        }
    }

    function printFoot(&$g)
    { 
        if ($this->headFootDisabled()) return;
        
        if ($this->hasContent() && !$this->_isXHTMLEmptyElement()) {
            $g->doPrintString('</', $this->_name, '>');
        }       
    }
    
    function printAttributes(&$g)
    {
        global $_phptal_xhtml_boolean_attributes;
        foreach ($this->_attrs as $key=>$value) {
            if ($this->_parser->_outputMode() == PHPTAL_XHTML 
                && in_array(strtolower($key), $_phptal_xhtml_boolean_attributes)) {
                $g->doPrintString(" $key");
            } else {
                $g->doPrintString(" $key=\"$value\"");
            }
        }
    }

    function surroundHead(&$g)
    {
        // if some surround attributes, we activate
        // their header method
        for ($i=0; $i<count($this->_surround_attributes); $i++) {
            $h =& $this->_surround_attributes[$i];
            $err = $h->start($g, $this);
            if (PEAR::isError($err)) { return $err; }
            unset($h);
        }
    }

    function surroundFoot(&$g)
    {
        // close surrounders in reverse order of course
        for ($i = (count($this->_surround_attributes)-1); $i >= 0; $i--) {
            $err = $this->_surround_attributes[$i]->end($g, $this);
            if (PEAR::isError($err)) { return $err; }
        }
    }
    
    function generateContent(&$g)
    {
        if (count($this->_content_attributes) > 0) {
            // time for content attributes, 
            foreach ($this->_content_attributes as $h) {
                $err = $h->activate($g, $this);
                if (PEAR::isError($err)) { return $err; }
            }
        } else {
            // if none, we just ask children to generate their code
            foreach ($this->_children as $child) {
                $err = $child->generateCode($g);
                if (PEAR::isError($err)) { return $err; }
            }
        }
    }

    function disableHeadFoot()
    {
        $this->_head_foot_disabled = true;
    }

    function headFootDisabled()
    {
        return $this->_head_foot_disabled;print_r($this->_root);
    }

    function _isXHTMLEmptyElement()
    {
        global $_phptal_xhtml_empty_tags;
        if ($this->_parser->_outputMode() != PHPTAL_XHTML) {
            return false;
        }
        return in_array(strtoupper($this->name()), $_phptal_xhtml_empty_tags);
    }
}

?>
