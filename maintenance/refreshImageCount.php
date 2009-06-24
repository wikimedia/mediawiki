<?php
/**
 * Quickie hack; patch-ss_images.sql uses variables which don't
 * replicate properly.
 *
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class RefreshImageCount extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Resets ss_image count, forcing slaves to pick it up.";
	}
	
	public function execute() {
		$dbw = wfGetDB( DB_MASTER );

		// Load the current value from the master
		$count = $dbw->selectField( 'site_stats', 'ss_images' );

		$this->output( wfWikiID() . ": forcing ss_images to $count\n" );

		// First set to NULL so that it changes on the master
		$dbw->update( 'site_stats',
			array( 'ss_images' => null ),
			array( 'ss_row_id' => 1 ) );
	
		// Now this update will be forced to go out
		$dbw->update( 'site_stats',
			array( 'ss_images' => $count ),
			array( 'ss_row_id' => 1 ) );
			}
}

$maintClass = "RefreshImageCount";
require_once( DO_MAINTENANCE );

