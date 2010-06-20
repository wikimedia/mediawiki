<?php
/* 
 * This script is designed to destroy your entire wiki so you can start over.
 * THIS IS NOT RECOVERABLE IN ANY WAY SHAPE OR FORM. You have been warned.
 *
 * @ingroup Maintenance
 */

require_once( 'Maintenance.php' );

class NukeEntireWiki extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Truncate all tables in your wiki. Skips user-related tables by default";
		$this->addOption( 'users', 'Include the user-related tables' );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		$this->output( "This will truncate all tables in your MediaWiki installation. Press Ctrl+C to abort\n" );
		wfCountDown( 5 );

		$dbw = wfGetDB( DB_MASTER );

		// Skip these tables unless the --users switch was given
		if( !$this->hasOption( 'users' ) ) {
			$userTables = $dbw->tableNamesN( 'user', 'user_groups', 'user_properties' );
		} else {
			$userTables = array();
		}

		$res = $dbw->query( "SHOW TABLES" );
		while( $tbl = $dbw->fetchRow( $res ) ) {
			if( in_array( "`{$tbl[0]}`", $userTables ) )
				continue;
			$this->output( "Truncating table {$tbl[0]}..." );
			$dbw->query( "TRUNCATE TABLE {$tbl[0]}" );
			$this->output( "done\n" );
		}
	}
}

$maintClass = 'NukeEntireWiki';
require_once( DO_MAINTENANCE );
