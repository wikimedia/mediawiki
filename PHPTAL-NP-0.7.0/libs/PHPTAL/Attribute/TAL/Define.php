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

class PHPTAL_Attribute_TAL_Define extends PHPTAL_Attribute
{
    function activate(&$g, &$tag)
    {
        if (preg_match('/\|\s*?\bdefault\b/sm', $this->expression)) {
            $g->doOBStart();
            $err = $tag->generateContent($g);
            if (PEAR::isError($err)) { return $err; }
            $g->doOBEnd('$__default__');
            $default = true;
        }
        
        // use node content to set variable
        if (preg_match('/^[a-z0-9_]+$/i', $this->expression)) {
            $g->doOBStart();
            $err = $tag->generateContent($g);
            if (PEAR::isError($err)) { return $err; }
            $g->doOBEndInContext($this->expression);
        } else {
            $exp = new PHPTAL_Expression($g, $tag, $this->expression);
            $exp->setPolicy(_PHPTAL_ES_RECEIVER_IS_CONTEXT);
            $err = $exp->generate();
            if (PEAR::isError($err)) { return $err; }
        }
        if (isset($default)) { $g->execute('unset($__default__)'); }
    }
}

?>
