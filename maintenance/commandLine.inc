<?php
$wgCommandLineMode = true;

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
if ( $argv[1] ) {
	$lang = $argv[1];
	putenv( "wikilang=$lang");
	$settingsFile = "/apache/htdocs/{$argv[1]}/w/LocalSettings.php";
	$newpath = "/apache/common/php$sep";
} else {
	$settingsFile = "../LocalSettings.php";
	$newpath = "";
}

if ( ! is_readable( $settingsFile ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "must exist in the source directory.\n";
	exit();
}

ini_set( "include_path", "../includes$sep../languages$sep$newpath$IP$sep$include_path" );

$wgCommandLineMode = true;
$DP = "../includes";
include_once( $settingsFile );

include_once( "Setup.php" );
include_once( "./InitialiseMessages.inc" );
include_once( "../install-utils.inc" );
$wgTitle = Title::newFromText( "Rebuild messages script" );
$wgCommandLineMode = true;
set_time_limit(0);
?>
