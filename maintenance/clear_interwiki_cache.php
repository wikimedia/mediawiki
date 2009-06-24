<?php
/**
 * This script is used to clear the interwiki links for ALL languages in
 * memcached.
 *
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class ClearInterwikiCache extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Clear all interwiki links for all languages from the cache";
	}

	public function execute() {
		global $wgLocalDatabases;
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'interwiki', array( 'iw_prefix' ), false );
		$prefixes = array();
		while ( $row = $dbr->fetchObject( $res ) ) {
			$prefixes[] = $row->iw_prefix;
		}

		foreach ( $wgLocalDatabases as $db ) {
			$this->output( "$db..." );
			foreach ( $prefixes as $prefix ) {
				$wgMemc->delete("$db:interwiki:$prefix");
			}
			$this->output( "done\n" );
		}
	}
}

$maintClass = "ClearInterwikiCache";
require_once( DO_MAINTENANCE );
