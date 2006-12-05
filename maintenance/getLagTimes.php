<?php

require 'commandLine.inc';

if( empty( $wgDBservers ) ) {
	echo "This script dumps replication lag times, but you don't seem to have\n";
	echo "a multi-host db server configuration.\n";
} else {
	$lags = $wgLoadBalancer->getLagTimes();
	foreach( $lags as $n => $lag ) {
		$host = $wgDBservers[$n]["host"];
		if( IP::isValid( $host ) ) {
			$ip = $host;
			$host = gethostbyaddr( $host );
		} else {
			$ip = gethostbyname( $host );
		}
		$stars = str_repeat( '*', intval( $lag ) );
		printf( "%10s %20s %3d %s\n", $ip, $host, $lag, $stars );
	}
}

?>