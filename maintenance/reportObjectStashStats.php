<?php
/**
 * Report a read-only census of resident keys in a DB-backed object stash.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\ObjectCache\SqlBagOStuff;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Maintenance script that reports a census of resident (live, non-expired) keys in a
 * DB-backed object stash, grouped by key group and server/cluster.
 *
 * @ingroup Maintenance
 */
class ReportObjectStashStats extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Report a read-only census of resident keys in a DB-backed object stash, ' .
			'grouped by key group and cluster. Scans a live primary gently in batches.'
		);
		$this->addOption(
			'cache',
			'Object cache ID from $wgObjectCaches to census (default: the main object stash)',
			false,
			true
		);
		$this->addOption( 'report', 'Emit StatsFactory gauges in addition to human-readable output' );
		$this->addOption( 'tag', 'Restrict the census to a single cluster/server tag', false, true );
		$this->addOption( 'sleep', 'Milliseconds to sleep between batches (default 50)', false, true );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$cacheId = $this->getOption( 'cache' );
		if ( $cacheId !== null ) {
			try {
				$cache = $this->getServiceContainer()->getObjectCacheFactory()->getInstance( $cacheId );
			} catch ( InvalidArgumentException $e ) {
				$this->fatalError( $e->getMessage() );
			}
		} else {
			$cache = $this->getServiceContainer()->getMainObjectStash();
		}

		if ( !$cache instanceof SqlBagOStuff ) {
			$this->fatalError(
				'The selected object cache is not backed by SqlBagOStuff; nothing to report.'
			);
		}

		$sleepMs = (int)$this->getOption( 'sleep', 50 );
		$progress = static function ( $rowsScanned ) use ( $sleepMs ) {
			if ( $sleepMs > 0 ) {
				usleep( 1000 * $sleepMs );
			}
		};

		$tag = $this->getOption( 'tag' );
		try {
			$stats = $cache->getKeyGroupStats( $tag, $this->getBatchSize(), $progress );
		} catch ( InvalidArgumentException $e ) {
			$this->fatalError( $e->getMessage() );
		}

		$this->printHuman( $stats );

		if ( $this->hasOption( 'report' ) ) {
			$this->emitGauges( $stats );
		}

		// A depooled or unreachable cluster is skipped rather than fatal; note the shortfall.
		if ( $tag !== null && !$stats ) {
			$this->output( "\nRequested cluster '$tag' returned no data (depooled or unreachable).\n" );
		} else {
			$this->output( "\nScanned " . count( $stats ) . " cluster(s).\n" );
		}
	}

	/**
	 * @param array<string,array{groups:array<string,array{keys:int,bytes:int}>,durationSeconds:float}> $stats
	 */
	private function printHuman( array $stats ) {
		$totals = [];
		foreach ( $stats as $cluster => $report ) {
			$this->output( sprintf(
				"Cluster: %s (scanned in %.1fs)\n",
				$cluster,
				$report['durationSeconds']
			) );
			$this->output( sprintf( "  %-40s %12s %16s\n", 'keygroup', 'keys', 'bytes' ) );
			$byGroup = $report['groups'];
			ksort( $byGroup );
			foreach ( $byGroup as $keygroup => $counts ) {
				$this->output( sprintf(
					"  %-40s %12d %16d\n",
					$keygroup,
					$counts['keys'],
					$counts['bytes']
				) );
				$totals[$keygroup]['keys'] = ( $totals[$keygroup]['keys'] ?? 0 ) + $counts['keys'];
				$totals[$keygroup]['bytes'] = ( $totals[$keygroup]['bytes'] ?? 0 ) + $counts['bytes'];
			}
			$this->output( "\n" );
		}

		if ( $totals ) {
			ksort( $totals );
			$this->output( "Totals across clusters:\n" );
			$this->output( sprintf( "  %-40s %12s %16s\n", 'keygroup', 'keys', 'bytes' ) );
			foreach ( $totals as $keygroup => $counts ) {
				$this->output( sprintf(
					"  %-40s %12d %16d\n",
					$keygroup,
					$counts['keys'],
					$counts['bytes']
				) );
			}
			$this->output(
				"\nNote: with dataRedundancy each key is resident on more than one cluster and is\n" .
				"counted once per cluster it lives on, so the logical footprint is roughly the\n" .
				"total divided by dataRedundancy.\n"
			);
		}
	}

	/**
	 * @param array<string,array{groups:array<string,array{keys:int,bytes:int}>,durationSeconds:float}> $stats
	 */
	private function emitGauges( array $stats ) {
		$statsFactory = $this->getServiceContainer()->getStatsFactory();
		$censusTime = (int)ConvertibleTimestamp::time();
		foreach ( $stats as $cluster => $report ) {
			$clusterKeys = 0;
			$clusterBytes = 0;
			foreach ( $report['groups'] as $keygroup => $counts ) {
				$statsFactory->getGauge( 'bagostuff_resident_keys' )
					->setLabel( 'keygroup', $keygroup )
					->setLabel( 'cluster', $cluster )
					->set( $counts['keys'] );

				$statsFactory->getGauge( 'bagostuff_resident_bytes' )
					->setLabel( 'keygroup', $keygroup )
					->setLabel( 'cluster', $cluster )
					->set( $counts['bytes'] );

				$clusterKeys += $counts['keys'];
				$clusterBytes += $counts['bytes'];
			}

			// Report one total for each cluster. The scan finds the key groups. If a group
			// becomes empty, the script does not report it again, and the gauge keeps its
			// last value. Compare the sum of the groups against this total to find a gauge
			// that is not current.
			$statsFactory->getGauge( 'bagostuff_resident_cluster_keys' )
				->setLabel( 'cluster', $cluster )
				->set( $clusterKeys );

			$statsFactory->getGauge( 'bagostuff_resident_cluster_bytes' )
				->setLabel( 'cluster', $cluster )
				->set( $clusterBytes );

			// Report the age of the data, so a failed census is visible
			$statsFactory->getGauge( 'bagostuff_resident_census_timestamp_seconds' )
				->setLabel( 'cluster', $cluster )
				->set( $censusTime );

			// Report the time scan cost, so we can judge if we can run this more often
			$statsFactory->getGauge( 'bagostuff_resident_census_duration_seconds' )
				->setLabel( 'cluster', $cluster )
				->set( $report['durationSeconds'] );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ReportObjectStashStats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
