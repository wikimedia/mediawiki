<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( "commandLine.inc" );
require_once( "refreshLinks.inc" );

error_reporting( E_ALL & (~E_NOTICE) );


if ($argv[2]) {
	$start = (int)$argv[2];
} else {
	$start = 1;
}

refreshLinks( $start );

exit();

?>
