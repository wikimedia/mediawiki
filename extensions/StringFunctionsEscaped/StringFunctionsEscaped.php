<?php
if ( !defined( 'MEDIAWIKI' ) )
	die( 'StringFunctionsEscaped::This file is a MediaWiki extension, it is not a valid entry point' );
if ( !class_exists('ExtStringFunctions',false) && 
	 !(class_exists('ParserFunctions_HookStub',false) && isset($wgPFEnableStringFunctions) && $wgPFEnableStringFunctions))
	die( 'StringFunctionsEscaped::You must have extension StringFunctions or extension ParserFunctions with string functions enabled' );
/*

 Defines a superset of string parser functions that allow character escaping in the 'search for' and 'replace with' arguments.

 {{#pos_e:value|key|offset}}

 Returns the first position of key inside the given value, or an empty string.
 If offset is defined, this method will not search the first offset characters.
 See: http://php.net/manual/function.strpos.php

 {{#rpos_e:value|key}}

 Returns the last position of key inside the given value, or -1 if the key is
 not found. When using this to search for the last delimiter, add +1 to the
 result to retreive position after the last delimiter. This also works when
 the delimiter is not found, because "-1 + 1" is zero, which is the beginning
 of the given value.
 See: http://php.net/manual/function.strrpos.php

 {{#pad_e:value|length|with|direction}}

 Returns the value padded to the certain length with the given with string.
 If the with string is not given, spaces are used for padding. The direction
 may be specified as: 'left', 'center' or 'right'.
 See: http://php.net/manual/function.str-pad.php


 {{#replace_e:value|from|to}}

 Returns the given value with all occurences of 'from' replaced with 'to'.
 See: http://php.net/manual/function.str-replace.php


 {{#explode_e:value|delimiter|position}}

 Splits the given value into pieces by the given delimiter and returns the
 position-th piece. Empty string is returned if there are not enough pieces.
 Note: Pieces are counted from 0.
 Note: A negative value can be used to count pieces from the end, instead of
 counting from the beginning. The last piece is at position -1.
 See: http://php.net/manual/function.explode.php


 Copyright (c) 2009 Jack D. Pond
 Licensed under GNU version 2
*/

$wgExtensionCredits['parserhook'][] = array(
	'path'            => __FILE__,
	'name'            => 'StringFunctionsEscaped',
	'version'         => '1.0.0', // Sept 7, 2009
	'description'     => 'Allows escaped characters in string functions using c-like syntax',
	'descriptionmsg'  => 'pfunc_desc',
	'author'          => array('Jack D. Pond'),
	'license'         => 'GNU Version 2',
	'url'             => 'http://www.mediawiki.org/wiki/Extension:StringFunctionsEscaped',
);

$dir = dirname( __FILE__ ) . '/';
# RFU
# $wgExtensionMessagesFiles['StringFunctionsEscaped'] = $dir . 'StringFunctionsEscaped.i18n.php';

$wgExtensionFunctions[] = 'wfStringFunctionsEscaped';

$wgHooks['LanguageGetMagic'][] = 'wfStringFunctionsEscapedLanguageGetMagic';

function wfStringFunctionsEscaped ( ) {
	global $wgParser, $wgExtStringFunctionsEscaped;

	$wgExtStringFunctionsEscaped = new ExtStringFunctionsEscaped ( );

	$wgParser->setFunctionHook('pos_e',      array(&$wgExtStringFunctionsEscaped,'runPos_e'      ));
	$wgParser->setFunctionHook('rpos_e',     array(&$wgExtStringFunctionsEscaped,'runRPos_e'     ));
	$wgParser->setFunctionHook('pad_e',      array(&$wgExtStringFunctionsEscaped,'runPad_e'      ));
	$wgParser->setFunctionHook('replace_e',  array(&$wgExtStringFunctionsEscaped,'runReplace_e'  ));
	$wgParser->setFunctionHook('explode_e',  array(&$wgExtStringFunctionsEscaped,'runExplode_e'  ));
}

function wfStringFunctionsEscapedLanguageGetMagic( &$magicWords, $langCode = "en" ) {
	switch ( $langCode ) {
		default:
		$magicWords['pos_e']          = array ( 0, 'pos_e' );
		$magicWords['rpos_e']         = array ( 0, 'rpos_e' );
		$magicWords['pad_e']          = array ( 0, 'pad_e' );
		$magicWords['replace_e']      = array ( 0, 'replace_e' );
		$magicWords['explode_e']      = array ( 0, 'explode_e' );
	}
	return true;
}

class ExtStringFunctionsEscaped {

	/**
	 * {{#pos_e:value|key|offset}}
	 * Note: If the needle is an empty string, single space is used instead.
	 * Note: If the needle is not found, empty string is returned.
	 * Note: The needle is limited to specific length.
	 */
	function runPos_e ( &$parser, $inStr = '', $inNeedle = '', $inOffset = 0 ) {
		global $wgParser;
		list($callback,$flags) = $wgParser->mFunctionHooks['pos'];
		return @call_user_func_array( $callback,
			array_merge(array($parser),array($inStr,stripcslashes($inNeedle),$inOffset) ));
	}

	/**
	 * {{#rpos_e:value|key}}
	 * Note: If the needle is an empty string, single space is used instead.
	 * Note: If the needle is not found, -1 is returned.
	 * Note: The needle is limited to specific length.
	 */
	function runRPos_e( &$parser, $inStr = '', $inNeedle = '' ) {
		global $wgParser;
		list($callback,$flags) = $wgParser->mFunctionHooks['rpos'];
		return @call_user_func_array( $callback,
			array_merge(array($parser),array($inStr,stripcslashes($inNeedle)) ));
	}

	/**
	 * {{#pad_e:value|length|with|direction}}
	 * Note: Length of the resulting string is limited.
	 */
	function runPad_e( &$parser, $inStr = '', $inLen = 0, $inWith = '', $inDirection = '' ) {
		global $wgParser;
		list($callback,$flags) = $wgParser->mFunctionHooks['pad'];
		return @call_user_func_array( $callback,
			array_merge(array($parser),array($inStr, $inLen , stripcslashes($inWith), $inDirection) ));
	}

	/**
	 * {{#replace:value|from|to}}
	 * Note: If the needle is an empty string, single space is used instead.
	 * Note: The needle is limited to specific length.
	 * Note: The product is limited to specific length.
	 */
	function runReplace_e( $parser, $inStr = '', $inReplaceFrom = '', $inReplaceTo = '' ) {
		global $wgParser;
		list($callback,$flags) = $wgParser->mFunctionHooks['replace'];
		return @call_user_func_array( $callback,
			array_merge(array($parser),array($inStr, stripcslashes($inReplaceFrom), stripcslashes($inReplaceTo)) ));
	}

	/**
	 * {{#explode_e:value|delimiter|position}}
	 * Note: Negative position can be used to specify tokens from the end.
	 * Note: If the divider is an empty string, single space is used instead.
	 * Note: The divider is limited to specific length.
	 * Note: Empty string is returned, if there is not enough exploded chunks.
	 */
	function runExplode_e ( &$parser, $inStr = '', $inDiv = '', $inPos = 0 ) {
		global $wgParser;
		list($callback,$flags) = $wgParser->mFunctionHooks['explode'];
		return @call_user_func_array( $callback,
			array_merge(array($parser),array($inStr, stripcslashes($inDiv), $inPos) ));
	}

}
