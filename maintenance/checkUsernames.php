<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'commandLine.inc';

class checkUsernames {
	var $stderr;

	function checkUsernames() {
		$this->stderr = fopen( 'php://stderr', 'wt' );
	}
	function main() {
		global $wgDBname;
		$fname = 'checkUsernames::main';
		
		$dbr =& wfGetDB( DB_SLAVE );
		
		$res = $dbr->select( 'user',
			array( 'user_id', 'user_name' ),
			null,
			$fname
		);

		fwrite( $this->stderr, "Checking $wgDBname\n" );
		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( ! User::isValidUserName( $row->user_name ) )
				printf( "%s: %6d: %s\n", $wgDBname, $row->user_id, $row->user_name );
		}
	}
}

$cun = new checkUsernames;
$cun->main();
