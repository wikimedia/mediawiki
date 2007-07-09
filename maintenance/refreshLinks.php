<?php
/**
 * @todo document
 * @addtogroup Maintenance
 */

/** */
$optionsWithArgs = array( 'm', 'e' );
require_once( "commandLine.inc" );
require_once( "refreshLinks.inc" );

error_reporting( E_ALL & (~E_NOTICE) );

if ( !$options['dfn-only'] ) {
	if ($args[0]) {
		$start = (int)$args[0];
	} else {
		$start = 1;
	}

	refreshLinks( $start, $options['new-only'], $options['m'], $options['e'], $options['redirects-only'] );
}
// this bit's bad for replication: disabling temporarily
// --brion 2005-07-16
//deleteLinksFromNonexistent();

if ( $options['globals'] ) {
	print_r( $GLOBALS );
}


