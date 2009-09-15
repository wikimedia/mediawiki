<?php

$wgUseNormalUser = true;
require_once('commandLine.inc');

if ( isset( $options['r'] ) ) {
	$lb = wfGetLB();
	print 'time     ';
	for( $i = 0; $i < $lb->getServerCount(); $i++ ) {
		$hostname = $lb->getServerName( $i );
		printf("%-12s ", $hostname );
	}
	print("\n");
	
	while( 1 ) {
		$lags = $lb->getLagTimes();
		unset( $lags[0] );
		print( gmdate( 'H:i:s' ) . ' ' );
		foreach( $lags as $i => $lag ) {
			printf("%-12s " , $lag === false ? 'false' : $lag );
		}
		print("\n");
		sleep(5);
	}
} else {
	$lb = wfGetLB();
	$lags = $lb->getLagTimes();
	foreach( $lags as $i => $lag ) {
		$name = $lb->getServerName( $i );
		printf("%-20s %s\n" , $name, $lag === false ? 'false' : $lag );
	}
}
?>
