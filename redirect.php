<?php
unset( $DP );
unset( $IP );
$wgCommandLineMode = false;

require_once( "./LocalSettings.php" );
global $wgArticlePath;

require_once( "WebRequest.php" );
$wgRequest = new WebRequest();

$page = $wgRequest->getVal( "wpDropdown" );

$url = str_replace( "$1", urlencode( $page ), $wgArticlePath );

header( "Location: {$url}" );
?>
