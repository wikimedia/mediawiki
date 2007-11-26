<?php
/**
 * @todo document
 * @addtogroup Maintenance
 */

/** */
$optionsWithArgs = array( 'm', 'e' );

require_once( "commandLine.inc" );
require_once( "refreshLinks.inc" );

if( isset( $options['help'] ) ) {
	echo <<<TEXT
usage: php refreshLinks.php start [-e end] [-m maxlag] [--help] [possibly other
    stuff]

    --help      : This help message
    --dfn-only  : ???
    -m <number> : Specifies max replication lag?  Does it abort or wait if this
        is exceeded?
    start       : First page id to refresh?  Doesn't work with --dfn-only set?
    -e <number> : Last page id to refresh?

This uses wfGetDB() to get the database, it seems not to accept a database ar-
gument on the command line.  So I don't know if you can use it for non-default
configuration.

Todo: Real documentation.

TEXT;
	exit(0);
}

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


