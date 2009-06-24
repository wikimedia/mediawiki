<?php
/**
 * @see wfWaitForSlaves()
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class WaitForSlave extends Maintenance {
	public function __construct() {
		$this->addArgs( array( 'maxlag' ) );
	}
	public function execute() {
		wfWaitForSlaves( $this->getArg( 0, 10 ) );
	}
}

$maintClass = "WaitForSlave";
require_once( DO_MAINTENANCE );
