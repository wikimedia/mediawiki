<?php
require_once( "commandLine.inc" );

$messages = array();
foreach ( $wgAllMessagesEn as $key => $englishValue )
{
	$messages[$key] = wfMsg( $key );
}
print "MediaWiki $wgVersion language file\n";
print serialize( $messages );

?>
