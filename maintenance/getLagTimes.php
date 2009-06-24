<?php
/**
 * @file
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class GetLagTimes extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Dump replication lag times";
	}

	public function execute() {
		$lb = wfGetLB();

		if( $lb->getServerCount() == 1 ) {
			$this->error( "This script dumps replication lag times, but you don't seem to have\n"
		 				  . "a multi-host db server configuration.\n" );
		} else {
			$lags = $lb->getLagTimes();
			foreach( $lags as $n => $lag ) {
				$host = $lb->getServerName( $n );
				if( IP::isValid( $host ) ) {
					$ip = $host;
					$host = gethostbyaddr( $host );
				} else {
					$ip = gethostbyname( $host );
				}
				$starLen = min( intval( $lag ), 40 );
				$stars = str_repeat( '*', $starLen );
				$this->output( sprintf( "%10s %20s %3d %s\n", $ip, $host, $lag, $stars ) );
			}
		}
	}
}

$maintClass = "GetLagTimes";
require_once( DO_MAINTENANCE );
