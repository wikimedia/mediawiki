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


class PHPTAL_Attribute_TAL_Condition extends PHPTAL_Attribute
{
    function start(&$g, &$tag)
    {
        $exp = new PHPTAL_Expression($g, $tag, $this->expression);
        $exp->setPolicy(_PHPTAL_ES_RECEIVER_IS_TEMP);
        $test_var = $g->newTemporaryVar();
        $exp->setReceiver($test_var);
        $err = $exp->generate();
        if (PEAR::isError($err)) { return $err; }
        $g->doIf("!PEAR::isError($test_var) && $test_var");
    }

    function end(&$g, &$tag)
    {
        $g->endBlock();
    }
};

?>
