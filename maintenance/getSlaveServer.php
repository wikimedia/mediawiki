<?php

require_once( dirname(__FILE__).'/commandLine.inc' );

if( isset( $options['group'] ) ) {
	$db = wfGetDB( DB_SLAVE, $options['group'] );
	$host = $db->getServer();
} else {
	$lb = wfGetLB();
	$i = $lb->getReaderIndex();
	$host = $lb->getServerName( $i );
}

print "$host\n";


