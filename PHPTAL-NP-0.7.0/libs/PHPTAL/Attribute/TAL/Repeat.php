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

class PHPTAL_Attribute_TAL_Repeat extends PHPTAL_Attribute
{
    var $in = "__in__";
    var $out = "__out__";
    
    function start(&$g, &$tag)
    {
        $g->setSource($tag->name(), $tag->line);
        
        $g->doComment('new loop');
        $exp = new PHPTAL_Expression($g, $tag, $this->expression);
        $exp->setPolicy(_PHPTAL_ES_RECEIVER_IS_TEMP);
        $err = $exp->prepare();
        if (PEAR::isError($err)) { return $err; }
        
        $this->out = $exp->getReceiver();
        $temp = $g->newTemporaryVar();
        $exp->setReceiver($temp);
        $err = $exp->generate();   // now $temp points to the loop data
        if (PEAR::isError($err)) { return $err; }

        $loop = $g->newTemporaryVar();
        $this->loop = $loop;
        $g->doAffectResult($loop, 
                           '& new PHPTAL_LoopControler($__ctx__, "'. $this->out . '", '
                                                  . $temp .');');
        $g->doIf('PEAR::isError('.$loop.'->_error)');
        $g->doPrintVar($loop.'->_error');
        $g->doElse();
        // $g->execute($loop.'->prepare()');
        $g->doWhile($loop.'->isValid()');
        
    }

    function end(&$g, &$tag)
    {
        $g->execute($this->loop.'->next()');
        $g->endBlock();
        $g->endBlock();
        $g->doComment('end loop');
    }
}
