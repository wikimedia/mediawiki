<?php

require_once( dirname(__FILE__).'/../commandLine.inc' );

global $IP;

if ( !isset( $args[0] ) ) {
	$dir = "$IP/languages/messages";
} else {
	$dir = $args[0];
}

$total = 0;
$nonZero = 0;
foreach ( glob( "$dir/*.php" ) as $file ) {
	$baseName = basename( $file );
	if( !preg_match( '/Messages([A-Z][a-z_]+)\.php$/', $baseName, $m ) ) {
		continue;
	}
	$code = str_replace( '_', '-', strtolower( $m[1] ) );
	$numMessages = wfGetNumMessages( $file );
	//print "$code: $numMessages\n";
	$total += $numMessages;
	if ( $numMessages > 0 ) {
		$nonZero ++;
	}
}
print "\nTotal: $total\n";
print "Languages: $nonZero\n";

function wfGetNumMessages( $file ) {
	// Separate function to limit scope
	require( $file );
	if ( isset( $messages ) ) {
		return count( $messages );
	} else {
		return 0;
	}
}

