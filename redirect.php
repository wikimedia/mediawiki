<?php
unset( $DP );
unset( $IP );
$wgCommandLineMode = false;

include_once( "./LocalSettings.php" );
global $wgArticlePath;

include_once( "WebRequest.php" );
$wgRequest = new WebRequest();

$page = $wgRequest->getVal( "wpDropdown" );

$url = str_replace( "$1", $page, $wgArticlePath );

header( "Location: {$url}" );
?>
