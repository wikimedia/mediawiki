<?php
/**
 * Script to kill a MySQL thread after a specified timeout
 * @package MediaWiki
 */

/**
 *
 */
$wgCommandLineMode = true;

unset( $IP );
ini_set( 'allow_url_fopen', 0 ); # For security...
require_once( './LocalSettings.php' );

# Windows requires ';' as separator, ':' for Unix
$sep = strchr( $include_path = ini_get( 'include_path' ), ';' ) ? ';' : ':';
ini_set( 'include_path', "$IP$sep$include_path" );

require_once( 'Setup.php' );

$wgTitle = Title::newFromText( wfMsg( 'badtitle' ) );
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
$conn->query( 'KILL '.$tid );

?>
