<?php
/**
 * Description: This script takes $wgHiddenPrefs and removes their preference from the DB. [[bugzilla:30976]]
 * @author TyA <tya.wiki@gmail.com>
 * @ingroup Maintenance
 */
		
/* Imports the stuff from Maintenance.php so we can use it */
require_once( dirname( __FILE__ ) . '/Maintenance.php');	

/* Creates the class */ 
class cleanupPreferences extends maintenance {
	
	/* creates the execute function */
	public function execute() {
		# Gets the $wgHiddenPrefs array
		global $wgHiddenPrefs; 
		
		/* Make database object */
		$dbw = wfGetDB( DB_MASTER ); //Name $dbw per doc for db write - master because we need up to date user preferences
	
		foreach($wgHiddenPrefs as $item) { # foreach loop to cycle through HiddenPrefs and outputs current item in $item to go in the query
		
			$dbw->delete( # makes the delete query
				'user_properties', # the table
				array('up_property' => $item), # the conditions
				__METHOD__ # this thing
				);
		};
	
		$this->output('Finished!\n'); # Displays a finished message

		}
}

$maintClass = 'cleanupPreferences'; // Tells it to run the class 
if( defined('RUN_MAINTENANCE_IF_MAIN') ) {
  require_once( RUN_MAINTENANCE_IF_MAIN );
} else {
  require_once( DO_MAINTENANCE ); # Make this work on versions before 1.17
}