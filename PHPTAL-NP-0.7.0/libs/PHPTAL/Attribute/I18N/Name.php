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

class PHPTAL_Attribute_I18N_Name extends PHPTAL_Attribute
{
    function start(&$g, &$tag)
    {
        $g->requireGettext();
        $g->doOBStart();
    }
    
    function end(&$g, &$tag)
    {
        $temp = $g->newTemporaryVar();
        $g->doOBEnd($temp);
        $code = sprintf('$__tpl__->_setTranslateVar(\'%s\', %s)', 
                        $this->expression, 
                        $temp);
        $g->execute($code);
    }
}

?>
