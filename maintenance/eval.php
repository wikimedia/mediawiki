<?php
/*require_once( "../includes/DefaultSettings.php" );
require_once( "../LocalSettings.php" );
require_once( "../includes/MemCachedClient.inc.php" );*/


require_once( "liveCmdLine.inc" );

do {
	$line = readconsole( "> " );
	eval( $line );
	if ( function_exists( "readline_add_history" ) ) {
		readline_add_history( $line );
	}
} while ( 1 );

function readconsole( $prompt = "" ) {
	if ( function_exists( "readline" ) ) {
		return readline( $prompt );
	} else {
		print $prompt;
		$fp = fopen( "php://stdin", "r" );
		$resp = trim( fgets( $fp, 1024 ) );
		fclose( $fp );
		return $resp;
	}
}



?>

