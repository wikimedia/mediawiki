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

class PHPTAL_Attribute_I18N_Attributes extends PHPTAL_Attribute
{
    var $_overwritten = array();
    
    function activate(&$g, &$tag)
    {
        global $_phptal_xhtml_boolean_attributes;
        $g->requireGettext();

        $g->doPrintString('<', $tag->name());

        $attributes = $tag->attributes();
        
        // prepare expressions for attributes to translate
        $exp = new PHPTAL_Expression($g, $tag, $this->expression);
        $exp->setPolicy(_PHPTAL_ES_RECEIVER_IS_TEMP);
        $err = $exp->prepare();
        if (PEAR::isError($err)) { return $err; }

        // more than one attribute to translate
        if ($exp->countSubs() > 0) {
            foreach ($exp->subs() as $subExpression) {
                $err = $this->_attributeExpression($g, $tag, $subExpression);
                if (PEAR::isError($err)) { return $err; }
            }
        } else {
            $err = $this->_attributeExpression($g, $tag, $exp);
            if (PEAR::isError($err)) { return $err; }
        }
    
        // echo non overwritten attributes
        foreach ($attributes as $key=>$value) {
            $test_key = strtolower($key);
            if (!in_array($test_key, $this->_overwritten)) {
                // beware of xhtml boolean attributes
                if ($tag->_parser->_outputMode() == PHPTAL_XHTML
                    && in_array($test_key, $_phptal_xhtml_boolean_attributes)) {
                    $g->doPrintString(' '.$key);
                } else {
                    $g->doPrintString(' '.$key.'="');
                    $g->doPrintString($value);
                    $g->doPrintString('"');
                }
            }
        }

        if ($tag->_isXHTMLEmptyElement()) { 
            $g->doPrintString(' />'); 
            return; 
        }
        
        if ($tag->hasContent()) {
            $g->doPrintString('>');
        } else {
            $g->doPrintString('/>');
        }

        $err = $tag->generateContent($g);
        if (PEAR::isError($err)) { return $err; }

        if ($tag->hasContent()) {
            $g->doPrintString('</', $tag->name(), '>');
        }
    }

    function _attributeExpression(&$g, &$tag, &$subExpression)
    {
        $attributes = $tag->attributes();
        $default    = false;
        $name       = strtolower($subExpression->getReceiver());

        // set default attribute value in $default variable
        if (preg_match('/\bdefault\b/sm', $subExpression->_src)) {
            if (!array_key_exists($name, $attributes)) {
                $g->doAffectResult('$__default__', 'false');
            } else {
                $value = $attributes[$name];
                $value = str_replace('\'', '\\\'', $value);
                $g->doAffectResult('$__default__', '\''. $value . '\'');
            }
            $default = true;
        }

        $real = str_replace('__phptales_dd__', ':', $name);
        $this->_overwritten[] = $real;
        $err  = $subExpression->generate();
        if (PEAR::isError($err)) { return $err; }

        $this->_printAttribute($g, $tag, $real, $name, $default);
    }

    function _printAttribute(&$g, &$tag, $realName, $varName, $default=false)
    {
        global $_phptal_xhtml_boolean_attributes;
        
        // watch boolean attributes on XHTML mode
        if ($tag->_parser->_outputMode() == PHPTAL_XHTML 
            && in_array($realName, $_phptal_xhtml_boolean_attributes)) {
            $g->doIf('!PEAR::isError($'.$varName.') && $'.$varName);
            $g->doPrintString(' '.$realName);
            $g->endBlock();
        } elseif ($default) {
            $g->doIf('!PEAR::isError($'.$varName.') && false !== $'.$varName.' && null !== $'.$varName);
            $g->doPrintString(' '. $realName . '="');
            $code = sprintf('$__tpl__->_translate(trim($%s))', $varName);
            $g->doPrintRes($code, true);
            // $g->doPrintVar($varName);
            $g->doPrintString('"');
            $g->endBlock();
        } else {
            $g->doPrintString(' '. $realName . '="');
            $code = sprintf('$__tpl__->_translate(trim($%s))', $varName);
            $g->doPrintRes($code, true);
            // $g->doPrintVar($varName);
            $g->doPrintString('"');
        }
    }
}

?>
