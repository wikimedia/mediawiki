<?php

define('PHPTAL_PRE_FILTER', 1);
define('PHPTAL_POST_FILTER', 2);

/**
 * Interface for source filter.
 *
 * This interface may be used to implement input / output filters.
 *
 * If the template intermediate php code is up to date, pre filters won't be
 * used on it.
 *
 * Output filters are only called on main template result.
 * 
 *
 * <?
 * class MyFilter extends PHPTAL_Filter
 * {
 *     function filter(&$tpl, $data, $mode) 
 *     {
 *         // just to present $mode usage for input/output filters
 *         if ($mode == PHPTAL_POST_FILTER) {
 *             return PEAR::raiseError("MyFilter mustn't be used as a pre-filter');
 *         }
 *         
 *         // remove html comments from template source
 *         return preg_replace('/(<\!--.*?-->)/sm', '', $data);
 *     }
 * }
 *
 * $tpl = PHPTAL('mytemplate.html');
 * $tpl->addInputFilter( new MyFilter() );
 * echo $tpl->execute();
 * 
 * ?>
 *
 * @author    Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_Filter
{
    /**
     * Filter some template source string.
     *
     * @param PHPTAL_Template $tpl 
     *        The template which invoked this filter.
     *        
     * @param string $data         
     *        Data to filter.
     *
     * @param int $mode
     *        PHPTAL_PRE_FILTER | PHPTAL_POST_FILTER depending if this filter
     *        is registered as a pre-filter or as a post-filter.
     *        
     */
    function filter(&$tpl, $data, $mode)
    {
        return $data;
    }
}

?>
