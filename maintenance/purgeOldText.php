<?php
/**
 * Purge old text records from the database
 *
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( "Maintenance.php" );

class PurgeOldText extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Purge old text records from the database";
		$this->addOption( 'purge', 'Performs the deletion' );
	}
	
	public function execute() {
		$this->purgeRedundantText( $this->hasOption('purge') );
	}
}

$maintClass = "PurgeOldText";
require_once( DO_MAINTENANCE );
