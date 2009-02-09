<?php

/**
 * Proof of principle script
 */

require( dirname( __FILE__ ) . '/../commandLine.inc' );

$obj = new MakeMessagesDB;
$obj->run();

class MakeMessagesDB {

	function run() {
		global $wgExtensionMessagesFiles, $wgMessageCache, $IP;

		$nameHash = md5( implode( "\n", array_keys( $wgExtensionMessagesFiles ) ) );
		$dir = "$IP/cache/ext-msgs";
		wfMkdirParents( $dir );
		$db = dba_open( "$dir/$nameHash.cdb", 'n', 'cdb' );
		if ( !$db ) {
			echo "Cannot open DB file\n";
			exit( 1 );
		}

		# Load extension messages
		foreach ( $wgExtensionMessagesFiles as $file ) {
			$messages = $magicWords = array();
			require( $file );
			foreach ( $messages as $lang => $unused ) {
				$wgMessageCache->processMessagesArray( $messages, $lang );
			}
		}

		# Write them to the file
		foreach ( $wgMessageCache->mExtensionMessages as $lang => $messages ) {
			foreach ( $messages as $key => $text ) {
				dba_insert( "$lang:$key", $text, $db );
			}
		}

		dba_close( $db );
	}
}

