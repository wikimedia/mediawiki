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

class PHPTAL_Attribute_I18N_Translate extends PHPTAL_Attribute
{
    function activate(&$g, &$tag)
    {
        $g->requireGettext();

        if (strlen($this->expression) == 0) {
            $key = "'". $this->_preparseGetTextKey($tag) ."'";
        } else {
            $exp = new PHPTAL_Expression($g, $tag, $this->expression);
            $exp->setPolicy(_PHPTAL_ES_RECEIVER_IS_TEMP);
            $key = $g->newTemporaryVar();
            $exp->setReceiver($key);
            $err = $exp->generate();
            if (PEAR::isError($err)) { return $err; }
        }
        
        // children may contains i18n:name attributes,
        // we ignore output but parse content before calling translation.
        $g->doOBStart();
        foreach ($tag->_children as $child) {
            $child->generateCode($g);
        }
        $g->doOBClean();
        // $code = sprintf('$__tpl__->_translate(\'%s\')', $key);
        $code = sprintf('$__tpl__->_translate(%s)', $key);
        $g->doPrintRes($code, true);
    }

    function _preparseGetTextKey(&$tag)
    {
        $key = "";
        foreach ($tag->_children as $child) {
            if ($child->isData()) {
                $str = preg_replace('/\s+/sm', ' ', $child->_content);
                $key .= trim($str) . ' ';
            } else {
                $is_i18n_name = false;
                // look for i18n:name
                foreach ($child->_surround_attributes as $att) {
                    if (get_class($att) == strtolower("PHPTAL_Attribute_I18N_Name")) {
                        $key .= '${' . $att->expression . '}';
                        $is_i18n_name = true;
                    }
                }
                // recursive call to preparse key for non i18n:name tags
                if (!$is_i18n_name) {
                    $key .= PHPTAL_Attribute_I18N_Translate::_preparseGetTextKey($child) . ' ';
                }
            }
        }

        // remove every thing that has more than 1 space
        $key = preg_replace('/\s+/sm', ' ', $key);
        $key = trim($key);
        return $key;
    }
}

?>
