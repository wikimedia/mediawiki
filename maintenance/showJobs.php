<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\JobQueue\Job;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Report number of jobs currently waiting in primary database.
 *
 * Based on runJobs.php. Note that this only works for JobQueue backends
 * that implement JobQueue::doGetSize. Implementations based on Kafka,
 * for example, might not have a way to obtain this. In that case,
 * telemetry should be provided externally, e.g. with Grafana/Prometheus.
 *
 * @ingroup Maintenance
 * @author Tim Starling
 * @author Antoine Musso
 */
class ShowJobs extends Maintenance {
	private const STATE_METHODS = [
		'unclaimed' => 'getAllQueuedJobs',
		'delayed'   => 'getAllDelayedJobs',
		'claimed'   => 'getAllAcquiredJobs',
		'abandoned' => 'getAllAbandonedJobs',
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Show number of jobs waiting in primary database' );
		$this->addOption( 'group', 'Show number of jobs per job type' );
		$this->addOption( 'list', 'Show a list of all jobs instead of counts' );
		$this->addOption( 'type', 'Only show/count jobs of a given type', false, true );
		$this->addOption( 'status', 'Filter list by state (unclaimed,delayed,claimed,abandoned)' );
		$this->addOption( 'limit', 'Limit of jobs listed' );
	}

	public function execute() {
		$typeFilter = $this->getOption( 'type', '' );
		$stateFilter = $this->getOption( 'status', '' );
		$stateLimit = (float)$this->getOption( 'limit', INF );

		$group = $this->getServiceContainer()->getJobQueueGroup();

		$filteredTypes = $typeFilter
			? [ $typeFilter ]
			: $group->getQueueTypes();
		$filteredStates = $stateFilter
			? array_intersect_key( self::STATE_METHODS, [ $stateFilter => 1 ] )
			: self::STATE_METHODS;

		if ( $this->hasOption( 'list' ) ) {
			$count = 0;
			foreach ( $filteredTypes as $type ) {
				$queue = $group->get( $type );
				foreach ( $filteredStates as $state => $method ) {
					foreach ( $queue->$method() as $job ) {
						/** @var Job $job */
						$this->output( $job->toString() . " status=$state\n" );
						if ( ++$count >= $stateLimit ) {
							return;
						}
					}
				}
			}
		} elseif ( $this->hasOption( 'group' ) ) {
			foreach ( $filteredTypes as $type ) {
				$queue = $group->get( $type );
				$delayed = $queue->getDelayedCount();
				$pending = $queue->getSize();
				$claimed = $queue->getAcquiredCount();
				$abandoned = $queue->getAbandonedCount();
				$active = max( 0, $claimed - $abandoned );
				if ( ( $pending + $claimed + $delayed + $abandoned ) > 0 ) {
					$this->output(
						"{$type}: $pending queued; " .
						"$claimed claimed ($active active, $abandoned abandoned); " .
						"$delayed delayed\n"
					);
				}
			}
		} else {
			$count = 0;
			foreach ( $filteredTypes as $type ) {
				$count += $group->get( $type )->getSize();
			}
			$this->output( "$count\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ShowJobs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
