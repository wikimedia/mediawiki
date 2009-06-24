<?php
/**
 * This script reports the hostname of a slave server.
 *
 * @file
 * @ingroup Maintenance
 */
 
require_once( "Maintenance.php" );

class GetSlaveServer extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addParam( "group", "Query group to check specifically" );
		$this->mDescription = "Report the hostname of a slave server";
	}
	public function execute() {
		global $wgAllDBsAreLocalhost;
		if( $wgAllDBsAreLocalhost ) {
			$host = 'localhost';
		} else {
			if( $this->hasOption('group') ) {
				$db = wfGetDB( DB_SLAVE, $this->getOption('group') );
				$host = $db->getServer();
			} else {
				$lb = wfGetLB();
				$i = $lb->getReaderIndex();
				$host = $lb->getServerName( $i );
			}
		}
		$this->output( "$host\n" );
	}
}

$maintClass = "GetSlaveServer";
require_once( DO_MAINTENANCE );
