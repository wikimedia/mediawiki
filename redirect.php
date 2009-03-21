<?php

/**
 * Script that redirects to the article passed in the "wpDropdown" parameter.
 * This is used by the nostalgia skin for the special pages drop-down
 *
 * @file
 */

require_once( './includes/WebStart.php' );
global $wgArticlePath;

$page = $wgRequest->getVal( 'wpDropdown' );

$url = str_replace( "$1", urlencode( $page ), $wgArticlePath );

header( "Location: {$url}" );
