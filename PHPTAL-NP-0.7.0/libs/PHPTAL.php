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

/*
 * This file:
 * 
 * - Include PHPTAL dependencies 
 * - Define PHPTAL attributes
 * - Define PHPTAL aliases
 * - Define PHPTAL rules
 * 
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */

$__d = dirname(__FILE__);
define('PT_IP', $IP.'/PHPTAL-NP-0.7.0/libs');
require_once "PEAR.php";

if (OS_WINDOWS) {
    define('PHPTAL_PATH_SEP', '\\');
} else {
    define('PHPTAL_PATH_SEP', '/');
}

function _phptal_os_path_join()
{
    $args = func_get_args();
    return join(PHPTAL_PATH_SEP, $args);
}

require_once 'Types/Errors.php';
require_once 'Types/OString.php';

require_once _phptal_os_path_join($__d, 'PHPTAL', 'Cache.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'Context.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'Filter.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'LoopControler.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'OutputControl.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'Template.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'Macro.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'I18N.php');

require_once _phptal_os_path_join($__d, 'PHPTAL', 'SourceResolver.php');
require_once _phptal_os_path_join($__d, 'PHPTAL', 'SourceLocator.php');


define('PHPTAL_VERSION', '0.7.0');
define('PHPTAL_MARK', str_replace('.', '_', PHPTAL_VERSION) . '_');

if (strtoupper(substr(PHP_OS, 0, 3)) == "WIN") {
	$default_temp = "C:\\Windows\\Temp";
} else {
	$default_temp = "/tmp";
}
if( getenv( 'TMP' ) == "" ) {
	if( is_writable( $default_temp ) ) {
		define('PHPTAL_DEFAULT_CACHE_DIR', $default_temp.DIRECTORY_SEPARATOR);
	} else {
		global $wgUploadDirectory;
		define('PHPTAL_DEFAULT_CACHE_DIR', $wgUploadDirectory.DIRECTORY_SEPARATOR);
	}
} else {
    define('PHPTAL_DEFAULT_CACHE_DIR', getenv("TMP") . DIRECTORY_SEPARATOR);
}

if( !is_writable (PHPTAL_DEFAULT_CACHE_DIR) )
	die( htmlspecialchars(
		'Can\'t find a writable temp directory for the XHTML template. ' .
		'Check that the TMP environment variable points to a writable directory, ' .
		'or that the default temp dir (' . $default_temp . ') exists and is writable.' ) );

/**
 * This define is used to select the templates output format.
 *
 * There's few differences between XHTML and XML but they these differences can
 * break some browsers output.
 *
 * Default PHPTAL output mode is XHTML.
 */
define('PHPTAL_XHTML', 1);

/**
 * This define is used to select the templates output format.
 *
 * The XML mode does not worry about XHTML specificity and echo every entity
 * in a <entity></entity> format.
 */
define('PHPTAL_XML', 2);

/**
 * @var _phptal_namespaces
 * @type array
 *
 * This array contains the list of all known attribute namespaces, if an
 * attribute belonging to one of this namespaces is not recognized by PHPTAL,
 * an exception will be raised.
 * 
 * These namespaces will be drop from resulting xml/xhtml unless the parser 
 * is told to keep them.
 *
 * @access private
 * @static 1
 */
global $_phptal_namespaces;
$_phptal_namespaces = array('TAL', 'METAL', 'I18N', 'PHPTAL');


define('_PHPTAL_SURROUND', 1);
define('_PHPTAL_REPLACE', 2);
define('_PHPTAL_CONTENT', 3);

/**
 * @var   _phptal_dictionary
 * @type  hashtable
 * 
 * This dictionary contains ALL known PHPTAL attributes. Unknown attributes 
 * will be echoed in result as xhtml/xml ones.
 * 
 * The value define how and when the attribute handler will be called during
 * code generation.
 * 
 * @access private 
 * @static 1
 */ 
global $_phptal_dictionary;
$_phptal_dictionary = array(
    'TAL:DEFINE'         => _PHPTAL_REPLACE,  // set a context variable
    'TAL:CONDITION'      => _PHPTAL_SURROUND, // print tag content only when condition true
    'TAL:REPEAT'         => _PHPTAL_SURROUND, // repeat over an iterable
    'TAL:CONTENT'        => _PHPTAL_CONTENT,  // replace tag content
    'TAL:REPLACE'        => _PHPTAL_REPLACE,  // replace entire tag
    'TAL:ATTRIBUTES'     => _PHPTAL_REPLACE,  // dynamically set tag attributes
    'TAL:OMIT-TAG'       => _PHPTAL_SURROUND, // omit to print tag but not its content
    'TAL:COMMENT'        => _PHPTAL_SURROUND, // do nothing
    'TAL:ON-ERROR'       => _PHPTAL_SURROUND, // replace content with this if error occurs
    
    'METAL:DEFINE-MACRO' => _PHPTAL_SURROUND, // define a template macro
    'METAL:USE-MACRO'    => _PHPTAL_REPLACE,  // use a template macro
    'METAL:DEFINE-SLOT'  => _PHPTAL_SURROUND, // define a macro slot
    'METAL:FILL-SLOT'    => _PHPTAL_SURROUND, // fill a macro slot 
    
    'PHPTAL:INCLUDE'     => _PHPTAL_REPLACE,  // include an external template 
    'PHPTAL:SRC-INCLUDE' => _PHPTAL_CONTENT,  // include external file without parsing

    'I18N:TRANSLATE'     => _PHPTAL_CONTENT,  // translate some data using GetText package
    'I18N:NAME'          => _PHPTAL_SURROUND, // prepare a translation name
    'I18N:ATTRIBUTES'    => _PHPTAL_REPLACE,  // translate tag attributes values
);

/**
 * @var   _phptal_aliases
 * @type  hashtable
 *
 * Create aliases for attributes. If an alias is found during parsing, the
 * matching phptal attribute will be used.
 *
 * @access private
 * @static 1
 */
global $_phptal_aliases;
$_phptal_aliases = array(    
    'TAL:INCLUDE'    => 'PHPTAL:INCLUDE',
    'TAL:SRC-INCLUDE'=> 'PHPTAL:SRC-INCLUDE',  
);

/**
 * @var   _phptal_rules_order
 * @type  hashtable
 * 
 * This rule associative array represents both ordering and exclusion 
 * mecanism for template attributes.
 *
 * All known attributes must appear here and must be associated with 
 * an occurence priority.
 *
 * When more than one phptal attribute appear in the same tag, they 
 * will execute in following order.
 * 
 * @access private
 * @static 1
 */ 
global $_phptal_rules_order;
$_phptal_rules_order = array(
    'TAL:OMIT-TAG'       => 0,    // surround -> $tag->disableHeadFootPrint()

    'TAL:ON-ERROR'       => 1,    // surround

    'METAL:DEFINE-MACRO' => 3,    // surround
    'TAL:DEFINE'         => 3,    // replace
    'I18N:NAME'          => 3,    // replace
    'I18N:TRANSLATE'     => 3,    // content

    'TAL:CONDITION'      => 4,    // surround

    'TAL:REPEAT'         => 5,    // surround

    'I18N:ATTRIBUTES'    => 6,    // replace
    'TAL:ATTRIBUTES'     => 6,    // replace
    'TAL:REPLACE'        => 6,    // replace
    'METAL:USE-MACRO'    => 6,    // replace
    'PHPTAL:SRC-INCLUDE' => 6,    // replace
    'PHPTAL:INCLUDE'     => 6,    // replace
    'METAL:DEFINE-SLOT'  => 6,    // replace
    'METAL:FILL-SLOT'    => 6,    // replace
    
    'TAL:CONTENT'        => 7,    // content

    'TAL:COMMENT'        => 8,    // surround
);

/**
 * @var _phptal_xhtml_content_free_tags
 * @type array
 *
 * This array contains XHTML tags that must be echoed in a &lt;tag/&gt; form
 * instead of the &lt;tag&gt;&lt;/tag&gt; form.
 *
 * In fact, some browsers does not support the later form so PHPTAL 
 * ensure these tags are correctly echoed.
 */
global $_phptal_xhtml_empty_tags;
$_phptal_xhtml_empty_tags = array(
    'AREA',
    'BASE',
    'BASEFONT',
    'BR',
    'COL',
    'FRAME',
    'HR',
    'IMG',
    'INPUT',
    'ISINDEX',
    'LINK',
    'META',
    'PARAM',
);

/**
 * @var _phptal_xhtml_boolean_attributes
 * @type array
 *
 * This array contains XHTML attributes that must be echoed in a minimized
 * form. Some browsers (non HTML4 compliants are unable to interpret those
 * attributes.
 *
 * The output will definitively not be an xml document !!
 * PreFilters should be set to modify xhtml input containing these attributes.
 */
global $_phptal_xhtml_boolean_attributes;
$_phptal_xhtml_boolean_attributes = array(
    'compact',
    'nowrap',
    'ismap',
    'declare',
    'noshade',
    'checked',
    'disabled',
    'readonly',
    'multiple',
    'selected',
    'noresize',
    'defer'
);

/**
 * Shortcut to PHPTAL_Template for lazzy ones (me first).
 */
class PHPTAL extends PHPTAL_Template {}

/**
 * PEAR compliant class name.
 */
class HTML_Template_PHPTAL extends PHPTAL_Template {}

/**
 * PEAR compliant class name.
 */
class HTML_Template_PHPTAL_Filter extends PHPTAL_Filter {}

/**
 * PEAR compliant class name.
 */
class HTML_Template_PHPTAL_SourceLocator extends PHPTAL_SourceLocator {}

/**
 * PEAR compliant class name.
 */
class HTML_Template_PHPTAL_SourceResolver extends PHPTAL_SourceResolver {}


?>
