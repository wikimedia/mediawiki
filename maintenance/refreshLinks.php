<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$optionsWithArgs = array( 'm' );
require_once( "commandLine.inc" );
require_once( "refreshLinks.inc" );

error_reporting( E_ALL & (~E_NOTICE) );

if ( !$options['dfn-only'] ) {
	if ($args[0]) {
		$start = (int)$args[0];
	} else {
		$start = 1;
	}

	refreshLinks( $start, $options['new-only'], $options['m'] );
}
deleteLinksFromNonexistent();

?>
