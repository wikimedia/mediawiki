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

class PHPTAL_Generator
{
    var $_temp_id   = 0;
    var $_str_buffer = "";
    var $_tab = "";
    var $_tab_save = "";
    var $_code = "";
    var $_closed = false;
    var $_fname;
    var $_funcName;

    var $_macros = array();
    var $_stacks = array();
    var $_current_macro = false;

    var $_gettext_required = false;

    var $_headers          = false;

    function PHPTAL_Generator($fname, $func_name)
    {
        $this->_fname = $fname;
        $this->_funcName = $func_name;
        $this->appendln('<?php');
        $this->doComment(str_pad("", 78, "-"));
        $this->doComment("This code was generate by PHPTAL based on the template source file :");
        $this->doComment("$fname");
        $this->doComment(str_pad("", 78, "-"));
        $this->appendln();
        $this->appendln('function ', $func_name, '(&$__tpl__)');
        $this->appendln('{');
        $this->tabInc();
        $this->appendln('$__ctx__ =& $__tpl__->getContext();');
        $this->appendln('$__out__ = new PHPTAL_OutputControl($__ctx__, $__tpl__->getEncoding());');
        $this->appendln('$__ctx__->set("repeat", array());');
    }

    function setHeaders($h)
    {
        $this->_headers = $h;
        if ($this->_headers) {
            $this->doHeadersPrint();
        }
    }

    function requireGettext()
    {
        $this->_gettext_required = true;
    }
    
    function getCode()
    {
        if (!$this->_closed) {
            $this->_flushOutput();
            $this->appendln('return $__out__->toString();');
            $this->endBlock();

            foreach ($this->_macros as $name=>$code) {
                $this->_code .= $code;
            }
            if ($this->_gettext_required) {
            	# Protect against the string and the inclusion.
            	# Note: some really obscure characters might still break.
            	$path = addslashes( PT_IP );
            	$path = str_replace( array( '$', '\\' ), array( '\\$', '\\\\' ), $path );
                $this->_code = preg_replace('/^<\?php/sm', 
                                            '<?php require_once "' . $path . '/GetText.php";', 
                                           $this->_code, 
                                           1);
            }
            $this->append('?>');
            $this->_closed = true;
        }
        return $this->_code;
    }
    
    function execute($code)
    {
        $this->_flushOutput();
        $this->appendln(trim($code), ";");
    }

    function doComment($str)
    {
        $this->_flushOutput();
        $this->appendln('// ',$str);
    }

    function setSource($tagName, $line)
    {
        $this->doComment('TAG ' . $tagName . ' AT LINE ' . $line);
        $this->appendln('$_src_tag = "'. $tagName. '"; ', '$_src_line = '. $line. ';');
    }
    
    // loop
    function doDo()
    {
        $this->_flushOutput();
        $this->appendln("do {");
        $this->tabInc();        
    }
    
    function doEndDoWhile($condition)
    {
        $this->tabDec();
        $this->appendln("} while($condition);");
    }

    function doWhile($condition)
    {
        $this->_flushOutput();
        $this->appendln("while ($condition) {");
        $this->tabInc();
    }

    // conditionals
    function doIf($condition)
    {
        $this->_flushOutput();
        $this->appendln("if ($condition) {");
        $this->tabInc();
    }
    
    function doElseIf($condition)
    {
        $this->_str_buffer = "";
        $this->endBlock();
        $this->appendln("else if ($condition) {");
        $this->tabInc();
    }
    
    function doElse()
    {
        $this->endBlock();
        $this->appendln("else {");
        $this->tabInc();
    }

    // 
    function doMacroDeclare($name)
    {
        $this->_flushOutput();
        $this->_stacks[] = $this->_code;
        $this->_code = "";
        $this->_current_macro = $name;
        $this->_tab_save = $this->_tab;
        $this->_tab = "";
        $this->appendln('function ', $this->_funcName,'_',$name, '(&$__tpl__)');
        $this->appendln('{');
        $this->tabInc();
        $this->appendln('$__ctx__ =& $__tpl__->getContext();');
        $this->appendln('$__out__ = new PHPTAL_OutputControl($__ctx__, $__tpl__->getEncoding());');
        if ($this->_headers) {
            $this->doHeadersPrint();
        }
    }

    function doHeadersPrint()
    {
        $this->_flushOutput();
        $this->doIf('! $__tpl__->_headers');
        $str = str_replace("'", "\\'", $this->_headers);
        $str = "'" . $str . "'";
        $str = PHPTAL_ES_path_in_string($str, "'");
        $this->appendln('$__tpl__->_headers = ' . $str .';');
        $this->endBlock();
    }
    
    function doMacroEnd()
    {
        $this->_flushOutput();
        $this->appendln('return $__out__->toString();');
        $this->endBlock();
        $this->_macros[$this->_current_macro] = $this->_code;
        $this->_code = array_pop($this->_stacks);
        $this->_current_macro = false;
        $this->_tab = $this->_tab_save;
    }

    function doAffectResult($dest, $code)
    {
        if ($dest[0] != '$') { $dest = '$'.$dest; }
        // test &
        $this->appendln("$dest = $code;");
    }
    
    //
    function doPrintString()
    {
        $args = func_get_args();
        $this->_str_buffer .= join("", $args);
        // $this->appendln('$__out__->write(', $str, ');');
    }
    
    function doPrintVar($var, $structure=false)
    {
        $this->_flushOutput();
        if ($var[0] != '$') { 
            $var = '$' . $var; 
        }
        if ($structure) {
            $this->appendln('$__out__->writeStructure(', $var, ');');
        } else {
            $this->appendln('$__out__->write(', $var, ');');
        }
    }
    
    function doPrintRes($code, $structure=false)
    {
        $this->_flushOutput();
        if ($structure) {
            $this->appendln('$__out__->writeStructure(', $code, ');');
        } else {
            $this->appendln('$__out__->write(', $code, ');');
        }
    }

    function doPrintContext($path, $structure=false)
    {
        $code = sprintf('$__ctx__->get(\'%s\')', $path);
        $this->doPrintRes($code, $structure);
    }

    // output buffering control
    function doOBStart()
    {
        $this->_flushOutput();
        $this->appendln('$__out__->pushBuffer();');
    }
    
    function doOBEnd($dest)
    {
        $this->_flushOutput();
        // test &
        $this->appendln($dest, ' =& $__out__->popBuffer();');
    }

    function doOBClean()
    {
        $this->_flushOutput();
        $this->appendln('$__out__->popBuffer();');
    }

    function doOBPrint()
    {
        $this->_flushOutput();
        $this->appendln('$__out__->writeStructure($__out__->popBuffer());');
    }

    function doOBEndInContext($dest)
    {
        $this->doContextSet($dest, '$__out__->popBuffer()');
    }

    // 
    function doReference($dest, $source)
    {
        $this->_flushOutput();
        if ($dest[0] != '$') { $dest = '$'.$dest; }
        if ($source[0] != '$') { $source = '$'.$source; }
        // test &
        $this->appendln("$dest =& $source;");
    }
    
    function doUnset($var)
    {
        $this->appendln("unset($var);");
    }
    
    // create a new temporary variable (non context) and return its name
    function newTemporaryVar()
    {
        return '$temp_' . $this->_temp_id++;
    }
    
    function releaseTemporaryVar($name)
    {
        $this->doUnset($name);
    }

    // context methods
    
    function doContextSet($out, $code)
    {
        $this->_flushOutput();
        if ($out[0] != '$') 
        { $out = '"'.$out.'"'; }
        // test & (Ref)
        $this->appendln("\$__ctx__->setRef($out, $code);");
    }
    
    function doContextGet($out, $path)
    {
        $this->_flushOutput();
        if ($out[0] != '$') 
        { $out = '$' . $out; }
        // test &
        $this->appendln("$out =& \$__ctx__->get(\"$path\");");
    }
    
    function endBlock()
    {
        $this->tabDec();
        $this->appendln('}');
    }

    function tabInc()
    {
        $this->_flushOutput();
        $this->_tab .= "    ";
    }
    
    function tabDec()
    {
        $this->_flushOutput();
        $this->_tab = substr($this->_tab, 4);
    }
    
    function appendln()
    {
        $args = func_get_args();
        $str  = join("", $args);
        $this->_code .= $this->_tab . $str . "\n";
    }

    function append()
    {
        $args = func_get_args();
        $str  = join("", $args);
        $this->_code .= $this->_tab . $str;
    }
    
    function _flushOutput()
    {
        if ($this->_str_buffer == "") return;
        $this->_str_buffer = str_replace("'", "\\'", $this->_str_buffer);
        $this->_str_buffer = "'" . $this->_str_buffer . "'";
        $this->_str_buffer = PHPTAL_ES_path_in_string($this->_str_buffer, "'");
        $this->appendln('$__out__->writeStructure(', $this->_str_buffer, ');');
        $this->_str_buffer = "";
    }
}

?>
