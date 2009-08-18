<?php
/**
* Merges in JavaScript with mwEmbed.i18n.php
*
* @file
* @ingroup Maintenance
*/

# Abort if called from a web server
if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
	print "This script must be run from the command line\n";
	exit();
}
define( 'MEDIAWIKI', true );
// get the scriptLoader globals:
require_once( '../../jsScriptLoader.php' );

$mwSTART_MSG_KEY = '$messages[\'en\'] = array(';
$mwEND_MSG_KEY = ',
);';
$mwLangFilePath = '../languages/mwEmbed.i18n.php';
// get options (like override JS or override PHP)

// read in mwEmbed.i18n.php
$rawLangFile = file_get_contents( $mwLangFilePath );

$startInx = strpos( $rawLangFile, $mwSTART_MSG_KEY ) + strlen( $mwSTART_MSG_KEY );
$endInx = strpos( $rawLangFile, $mwEND_MSG_KEY ) + 1;
if ( $startInx === false || $endInx === false ) {
	print "Could not find $mwSTART_MSG_KEY or $mwEND_MSG_KEY in mwEmbed.i18n.php \n";
	exit();
}

$preFile = substr( $rawLangFile, 0, $startInx );
$msgSet = substr( $rawLangFile, $startInx, $endInx - $startInx );
$postFile = substr( $rawLangFile, $endInx );

// build replacement from all javascript in mwEmbed
$path = realpath( '../../' );

$curFileName = '';
// @@todo existing msgSet should be parsed (or we just "include" the file first)
$msgSet = "";

$objects = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ), RecursiveIteratorIterator::SELF_FIRST );
foreach ( $objects as $fname => $object ) {
	if ( substr( $fname, - 3 ) == '.js' ) {
		$jsFileText = file_get_contents( $fname );
		$mwPos = strpos( $fname, 'mwEmbed' ) + 7;
		$curFileName = substr( $fname, $mwPos );
		if ( preg_match( '/loadGM\s*\(\s*{(.*)}\s*\)\s*/siU',	// @@todo fix: will break down if someone does }) in their msg text
		$jsFileText,
		$matches ) ) {
			$msgSet .= doJsonMerge( $matches[1] );
		}
	}
}

// rebuild and output to file
if ( file_put_contents( $mwLangFilePath, trim( $preFile ) . "\n\t" . trim( $msgSet ) . "\n" . trim( $postFile ) ) ) {
	print "Updated $mwLangFilePath file\n";
	exit();
}

function doJsonMerge( $json_txt ) {
	global $curFileName;

	$out = "\n\t/*
	\t * js file: {$curFileName}
	\t */\n";
	$jmsg = json_decode( '{' . $json_txt . '}', true );
	if ( count( $jmsg ) != 0 ) {
		foreach ( $jmsg as $k => $v ) {
			$out .= "\t'{$k}' => '" . str_replace( '\'', '\\\'', $v ) . "',\n";
		}
		return $out;
	} else {
		print "Could not get any json vars from $curFileName\n";
		return '';
	}
}
