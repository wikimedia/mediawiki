<?php
require_once( "commandLine.inc" );

$messages = array();
foreach ( $wgAllMessagesEn as $key => $englishValue )
{
	$messages[$key] = wfMsg( $key );
}

if ( count( $argv ) >= 2 ) {
	$res = fopen( $argv[2] );
	fwrite( $res, serialize( $messages ) );
} else {
	print serialize( $messages );
}

?>
