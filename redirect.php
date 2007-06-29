<?php
require_once( './includes/WebStart.php' );
global $wgArticlePath;

require_once( 'includes/WebRequest.php' );
$wgRequest = new WebRequest();

$page = $wgRequest->getVal( 'wpDropdown' );

$url = str_replace( "$1", urlencode( $page ), $wgArticlePath );

header( "Location: {$url}" );

