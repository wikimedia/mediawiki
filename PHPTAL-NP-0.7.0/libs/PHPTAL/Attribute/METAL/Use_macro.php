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

class PHPTAL_Attribute_METAL_Use_Macro extends PHPTAL_Attribute
{
    function activate(&$g, &$tag)
    {
        $g->doOBStart();
        $err = $tag->generateContent($g);
        if (PEAR::isError($err)) { return $err; }
        $g->doOBClean();
        
        $path = $g->newTemporaryVar();
        
        $g->doAffectResult($path, "'". PHPTAL_ES_path_in_string($this->expression,"'") . "'");
        
        $temp = $g->newTemporaryVar();
        
        // push error
        $g->execute('$__old_error = $__ctx__->_errorRaised');
        $g->execute('$__ctx__->_errorRaised = false');
        
        $g->doAffectResult($temp, 'new PHPTAL_Macro($__tpl__, '. $path .')');
        $g->doAffectResult($temp, $temp.'->execute($__tpl__)');
        
        $g->doIf('PEAR::isError('.$temp.')');
        $g->execute('$__ctx__->_errorRaised = '.$temp );
        $g->endBlock();
        
        $g->doPrintVar($temp, true);

        // restore error
        $g->doIf('!$__ctx__->_errorRaised');
        $g->execute('$__ctx__->_errorRaised = $__old_error');
        $g->endBlock();
    }
}

?>
