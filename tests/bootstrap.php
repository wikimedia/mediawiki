<?php

/**
 * Set up the MediaWiki environment when running tests with "phpunit" command
 *
 * Warning: this file is not included from global scope!
 * @file
 */

global $wgCommandLineMode, $IP;
$wgCommandLineMode = true;
$IP = dirname( dirname( __FILE__ ) );

define( 'MEDIAWIKI', true );
define( 'MW_PHPUNIT_TEST', true );

require_once "$IP/includes/Defines.php";
require_once "$IP/includes/AutoLoader.php";
require_once "$IP/LocalSettings.php";
require_once "$IP/includes/ProfilerStub.php";
require_once "$IP/includes/GlobalFunctions.php";
require_once "$IP/includes/Hooks.php";

// for php versions before 5.2.1
if ( !function_exists('sys_get_temp_dir')) {
  function sys_get_temp_dir() {
	  if( $temp=getenv('TMP') )		   return $temp;
	  if( $temp=getenv('TEMP') )		return $temp;
	  if( $temp=getenv('TMPDIR') )	  return $temp;
	  $temp=tempnam(__FILE__,'');
	  if (file_exists($temp)) {
		  unlink($temp);
		  return dirname($temp);
	  }
	  return null;
  }
 }

