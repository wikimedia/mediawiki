<?php
/**
 * Based on runJobs.php
 *
 * Report number of jobs currently waiting in master database.
 *
 * @file
 * @ingroup Maintenance
 * @author Tim Starling
 * @author Ashar Voultoiz
 */
 
require_once( "Maintenance.php" );

class ShowJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Show number of jobs waiting in master database";
	}
	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		$this->output( $dbw->selectField( 'job', 'count(*)', '', 'runJobs.php' ) . "\n" );
	}
}

$maintClass = "ShowJobs";
require_once( DO_MAINTENANCE );
