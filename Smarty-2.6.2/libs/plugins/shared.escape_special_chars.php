<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * escape_special_chars common function
 *
 * Function: smarty_function_escape_special_chars<br>
 * Purpose:  used by other smarty functions to escape
 *           special chars except for already escaped ones
 * @param string
 * @return string
 */
function smarty_function_escape_special_chars($string)
{
    if(!is_array($string)) {
        $string = preg_replace('!&(#?\w+);!', '%%%SMARTY_START%%%\\1%%%SMARTY_END%%%', $string);
        $string = htmlspecialchars($string);
        $string = str_replace(array('%%%SMARTY_START%%%','%%%SMARTY_END%%%'), array('&',';'), $string);
    }
    return $string;
}

/* vim: set expandtab: */

?>
