<?php

/**
 * Shows database lag
 *
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DatabaseLag extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Shows database lag";
		$this->addOption( 'r', "Don't exit immediately, but show the lag every 5 seconds" );
	}

	public function execute() {
		if ( $this->hasOption( 'r' ) ) {
			$lb = wfGetLB();
			echo 'time     ';
			for ( $i = 1; $i < $lb->getServerCount(); $i++ ) {
				$hostname = $lb->getServerName( $i );
				printf( "%-12s ", $hostname );
			}
			echo "\n";

			while ( 1 ) {
				$lb->clearLagTimeCache();
				$lags = $lb->getLagTimes();
				unset( $lags[0] );
				echo gmdate( 'H:i:s' ) . ' ';
				foreach ( $lags as $i => $lag ) {
					printf( "%-12s " , $lag === false ? 'false' : $lag );
				}
				echo "\n";
				sleep( 5 );
			}
		} else {
			$lb = wfGetLB();
			$lags = $lb->getLagTimes();
			foreach ( $lags as $i => $lag ) {
				$name = $lb->getServerName( $i );
				$this->output( sprintf( "%-20s %s\n" , $name, $lag === false ? 'false' : $lag ) );
			}
		}
	}
}

$maintClass = "DatabaseLag";
require_once( DO_MAINTENANCE );
