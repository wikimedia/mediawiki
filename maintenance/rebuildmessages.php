<?php

require_once( 'commandLine.inc' );

if( $wgLocalDatabases ) {
	$databases = $wgLocalDatabases;
} else {
	$databases = array( $wgDBname );
}

foreach( $databases as $db ) {
	echo "Deleting message cache for {$db}... ";
	$wgMessageCache->mMemc->delete( "{$db}:messages" );
	echo "Deleted";
}