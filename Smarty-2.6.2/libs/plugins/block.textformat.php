<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {textformat}{/textformat} block plugin
 *
 * Type:     block function<br>
 * Name:     textformat<br>
 * Purpose:  format text a certain way with preset styles
 *           or custom wrap/indent settings<br>
 * @link http://smarty.php.net/manual/en/language.function.textformat.php {textformat}
 *       (Smarty online manual)
 * @param array
 * <pre>
 * Params:   style: string (email)
 *           indent: integer (0)
 *           wrap: integer (80)
 *           wrap_char string ("\n")
 *           indent_char: string (" ")
 *           wrap_boundary: boolean (true)
 * </pre>
 * @param string contents of the block
 * @param Smarty clever simulation of a method
 * @return string string $content re-formatted
 */
function smarty_block_textformat($params, $content, &$smarty)
{
    if (is_null($content)) {
        return;
    }

    $style = null;
    $indent = 0;
    $indent_first = 0;
    $indent_char = ' ';
    $wrap = 80;
    $wrap_char = "\n";
    $wrap_cut = false;
    $assign = null;
    
    foreach ($params as $_key => $_val) {
        switch ($_key) {
            case 'style':
            case 'indent_char':
            case 'wrap_char':
            case 'assign':
                $$_key = (string)$_val;
                break;

            case 'indent':
            case 'indent_first':
            case 'wrap':
                $$_key = (int)$_val;
                break;

            case 'wrap_cut':
                $$_key = (bool)$_val;
                break;

            default:
                $smarty->trigger_error("textformat: unknown attribute '$_key'");
        }
    }

    if ($style == 'email') {
        $wrap = 72;
    }

    // split into paragraphs
    $paragraphs = preg_split('![\r\n][\r\n]!',$content);
    $output = '';

    foreach ($paragraphs as $paragraph) {
        if ($paragraph == '') {
            continue;
        }
        // convert mult. spaces & special chars to single space
        $paragraph = preg_replace(array('!\s+!','!(^\s+)|(\s+$)!'),array(' ',''),$paragraph);
        // indent first line
        if($indent_first > 0) {
            $paragraph = str_repeat($indent_char,$indent_first) . $paragraph;
        }
        // wordwrap sentences
        $paragraph = wordwrap($paragraph, $wrap - $indent, $wrap_char, $wrap_cut);
        // indent lines
        if($indent > 0) {
            $paragraph = preg_replace('!^!m',str_repeat($indent_char,$indent),$paragraph);
        }
        $output .= $paragraph . $wrap_char . $wrap_char;
    }

    if ($assign) {
        $smarty->assign($assign,$output);
    } else {
        return $output;
    }
}

/* vim: set expandtab: */

?>
