<?php
/**
 * @addtogroup Maintenance
 */

/** */
$optionsWithArgs = array( 'm', 'e' );

require_once( "commandLine.inc" );
require_once( "refreshLinks.inc" );

if( isset( $options['help'] ) ) {
	echo <<<TEXT
Usage: php refreshLinks.php [<start>] [-e <end>] [-m <maxlag>] [--help] 

    --help      : This help message
    --dfn-only  : Delete links from nonexistent articles only
    -m <number> : Maximum replication lag
    <start>     : First page id to refresh
    -e <number> : Last page id to refresh

TEXT;
	exit(0);
}

error_reporting( E_ALL & (~E_NOTICE) );

if ( !$options['dfn-only'] ) {
	if ( isset( $args[0] ) ) {
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


