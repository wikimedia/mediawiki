<?php
/**
 * Shows database lag
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to show database lag.
 *
 * @ingroup Maintenance
 */
class DatabaseLag extends Maintenance {

	/** @var bool */
	protected $stopReporting = false;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Shows database lag' );
		$this->addOption( 'r', "Don't exit immediately, but show the lag every 5 seconds" );
	}

	public function execute() {
		$lb = $this->getServiceContainer()->getDBLoadBalancer();
		if ( $this->hasOption( 'r' ) ) {
			$this->output( 'time     ' );

			$serverCount = $lb->getServerCount();
			for ( $i = 1; $i < $serverCount; $i++ ) {
				$hostname = $lb->getServerName( $i );
				$this->output( sprintf( "%-12s ", $hostname ) );
			}
			$this->output( "\n" );

			do {
				$lags = $lb->getLagTimes();
				unset( $lags[0] );
				$this->output( gmdate( 'H:i:s' ) . ' ' );
				foreach ( $lags as $lag ) {
					$this->output(
						sprintf(
							"%-12s ",
							$lag === false ? 'replication stopped or errored' : $lag
						)
					);
				}
				$this->output( "\n" );
				sleep( 5 );
			} while ( !$this->stopReporting );

		} else {
			$lags = $lb->getLagTimes();
			foreach ( $lags as $i => $lag ) {
				$name = $lb->getServerName( $i );
				$this->output(
					sprintf(
						"%-20s %s\n",
						$name,
						$lag === false ? 'replication stopped or errored' : $lag
					)
				);
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = DatabaseLag::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
