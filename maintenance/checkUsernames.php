<?php
/**
 * This script verifies that database usernames are actually valid.
 * An existing usernames can become invalid if User::isValidUserName()
 * is altered or if we change the $wgMaxNameChars
 * @file
 * @ingroup Maintenance
 */


require_once( "Maintenance.php" );

class CheckUsernames extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Verify that database usernames are actually valid";
	}

	function execute() {
		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select( 'user',
			array( 'user_id', 'user_name' ),
			null,
			__METHOD__
		);

		while ( $row = $dbr->fetchObject( $res ) ) {
			if ( ! User::isValidUserName( $row->user_name ) ) {
				$this->error( sprintf( "%s: %6d: '%s'\n", wfWikiID(), $row->user_id, $row->user_name ) );
				wfDebugLog( 'checkUsernames', $out );
			}
		}
	}
}

$maintClass = "CheckUsernames";
require_once( "doMaintenance.php" );
