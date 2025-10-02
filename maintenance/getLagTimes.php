<?php
/**
 * Display replication lag times.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use Wikimedia\IPUtils;

/**
 * Maintenance script that displays replication lag times.
 *
 * @ingroup Maintenance
 */
class GetLagTimes extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump replication lag times' );
		$this->addOption( 'report', "Report the lag values to StatsD" );
	}

	public function execute() {
		$services = $this->getServiceContainer();
		$lbFactory = $services->getDBLoadBalancerFactory();
		$stats = $services->getStatsFactory();
		$lbsByType = [
			'main' => $lbFactory->getAllMainLBs(),
			'external' => $lbFactory->getAllExternalLBs()
		];

		foreach ( $lbsByType as $type => $lbs ) {
			foreach ( $lbs as $cluster => $lb ) {
				if ( $lb->getServerCount() <= 1 ) {
					continue;
				}
				$lags = $lb->getLagTimes();
				foreach ( $lags as $serverIndex => $lag ) {
					$host = $lb->getServerName( $serverIndex );
					if ( IPUtils::isValid( $host ) ) {
						$ip = $host;
						$host = gethostbyaddr( $host );
					} else {
						$ip = gethostbyname( $host );
					}

					if ( $lag === false ) {
						$stars = 'replication stopped or errored';
					} else {
						$starLen = min( intval( $lag ), 40 );
						$stars = str_repeat( '*', $starLen );
					}
					$this->output( sprintf( "%10s %20s %3d %s\n", $ip, $host, $lag, $stars ) );

					if ( $this->hasOption( 'report' ) ) {
						$group = ( $type === 'external' ) ? 'external' : $cluster;

						// $lag is the lag duration in seconds
						// emit milliseconds for backwards-compatibility
						$stats->getGauge( 'loadbalancer_lag_milliseconds' )
							->setLabel( 'group', $group )
							->setLabel( 'host', $host )
							->copyToStatsdAt( "loadbalancer.lag.$group.$host" )
							->set( (int)( $lag * 1e3 ) );

						// emit seconds also to align with Prometheus' recommendations
						$stats->getGauge( 'loadbalancer_lag_seconds' )
							->setLabel( 'group', $group )
							->setLabel( 'host', $host )
							->set( (int)$lag );
					}
				}
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = GetLagTimes::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
