<?php
unset( $DP );
unset( $IP );
$wgCommandLineMode = false;

include_once( "./LocalSettings.php" );
global $wgArticlePath;

$wpDropdown = $_REQUEST['wpDropdown'];
if( get_magic_quotes_gpc() ) {
	$wpDropdown = stripslashes( $wpDropdown );
}

$url = str_replace( "$1", $wpDropdown, $wgArticlePath );
header( "Location: {$url}" );
?>
