<?php

# Script to kill a MySQL thread after a specified timeout

if( php_sapi_name() != 'cli' ) {
	die('');
}

define( 'MEDIAWIKI', 1 );
$wgCommandLineMode = true;

unset( $IP );
ini_set( "allow_url_fopen", 0 ); # For security...
require_once( "../LocalSettings.php" );

if( !$wgAllowSysopQueries ) {
	die( "Queries disabled.\n" );
}

require_once( "Setup.php" );

$wgTitle = Title::newFromText( wfMsg( "badtitle" ) );
$wgArticle = new Article($wgTitle);

if ( !$argv[1] || !$argv[2] ) {
	exit();
}

$tid = (int)$argv[2];

# Wait for timeout (this process may be killed during this time)
$us = floor( $argv[1] * 1000000 ) % 1000000;
$s = floor( $argv[1] );
usleep( $us );
sleep( $s );

# Kill DB thread
$conn = Database::newFromParams( $wgDBserver, $wgDBsqluser, $wgDBsqlpassword, $wgDBname );
$conn->query( "KILL $tid" );

?>
