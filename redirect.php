<?php

/**
 * Script that redirects to the article passed in the "wpDropdown" parameter.
 * This is used by the nostalgia skin for the special pages drop-down
 *
 * @file
 */
if ( isset( $_SERVER['MW_COMPILED'] ) ) {
	require ( 'phase3/includes/WebStart.php' );
} else {
	require ( dirname( __FILE__ ) . '/includes/WebStart.php' );
}

global $wgArticlePath;

$page = $wgRequest->getVal( 'wpDropdown' );

$url = str_replace( "$1", urlencode( $page ), $wgArticlePath );

header( "Location: {$url}" );
