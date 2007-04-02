<?php

require_once( dirname(__FILE__).'/commandLine.inc' );

if( isset( $options['group'] ) ) {
	$db = wfGetDB( DB_SLAVE, $options['group'] );
	$host = $db->getProperty( 'mServer' );
} else {
	$i = $wgLoadBalancer->getReaderIndex();
	$host = $wgDBservers[$i]['host'];
}

print "$host\n";

?>
