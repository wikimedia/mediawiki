<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */

$optionsWithArgs = array( 's', 'd' );

require_once( "commandLine.inc" );
require_once( "dumpHTML.inc" );

error_reporting( E_ALL & (~E_NOTICE) );


if ( !empty( $options['s'] ) ) {
	$start = $options['s'];
} else {
	$start = 1;
}

if ( !empty( $options['d'] ) ) {
	$dest = $options['d'];
} else {
	$dest = 'static';
}

dumpHTML( $dest, $start );

exit();

?>
