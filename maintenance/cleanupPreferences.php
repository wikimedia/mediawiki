<?php
/**
 * Description: This script takes $wgHiddenPrefs and removes their preference from the DB. [[bugzilla:30976]]
 * @author TyA <tya.wiki@gmail.com>
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class CleanupPreferences extends Maintenance {
	public function execute() {
		global $wgHiddenPrefs;

		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		foreach( $wgHiddenPrefs as $item ) {
			$dbw->delete(
				'user_properties',
				array( 'up_property' => $item ),
				__METHOD__
			);
		};
		$dbw->commit();
		$this->output( "Finished!\n" );
	}
}

$maintClass = 'CleanupPreferences'; // Tells it to run the class
require_once( RUN_MAINTENANCE_IF_MAIN );
