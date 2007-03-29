<?php
require_once( "commandLine.inc" );

# Don't wait for benet
foreach ( $wgLoadBalancer->mServers as $i => $server ) {
	if ( $server['host'] == '10.0.0.29' ) {
		unset($wgLoadBalancer->mServers[$i]);
	}
}
if ( isset( $args[0] ) ) {
	wfWaitForSlaves($args[0]);
} else {
	wfWaitForSlaves(10);
}

?>
