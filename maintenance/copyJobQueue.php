<?php
/**
 * Copy all jobs from one job queue system to another.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\JobQueue\JobQueue;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\WikiMap\WikiMap;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Copy all jobs from one job queue system to another.
 * This uses an ad-hoc $wgJobQueueMigrationConfig setting,
 * which is a map of queue system names to JobQueue::factory() parameters.
 * The parameters should not have wiki or type settings and thus partial.
 *
 * @ingroup Maintenance
 */
class CopyJobQueue extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Copy jobs from one queue system to another.' );
		$this->addOption( 'src', 'Key to $wgJobQueueMigrationConfig for source', true, true );
		$this->addOption( 'dst', 'Key to $wgJobQueueMigrationConfig for destination', true, true );
		$this->addOption( 'type', 'Types of jobs to copy (use "all" for all)', true, true );
		$this->setBatchSize( 500 );
	}

	public function execute() {
		global $wgJobQueueMigrationConfig;

		$srcKey = $this->getOption( 'src' );
		$dstKey = $this->getOption( 'dst' );

		if ( !isset( $wgJobQueueMigrationConfig[$srcKey] ) ) {
			$this->fatalError( "\$wgJobQueueMigrationConfig not set for '$srcKey'." );
		} elseif ( !isset( $wgJobQueueMigrationConfig[$dstKey] ) ) {
			$this->fatalError( "\$wgJobQueueMigrationConfig not set for '$dstKey'." );
		}

		$types = ( $this->getOption( 'type' ) === 'all' )
			? $this->getServiceContainer()->getJobQueueGroup()->getQueueTypes()
			: [ $this->getOption( 'type' ) ];

		$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
		foreach ( $types as $type ) {
			$baseConfig = [ 'type' => $type, 'domain' => $dbDomain ];
			$src = JobQueue::factory( $baseConfig + $wgJobQueueMigrationConfig[$srcKey] );
			$dst = JobQueue::factory( $baseConfig + $wgJobQueueMigrationConfig[$dstKey] );

			[ $total, $totalOK ] = $this->copyJobs( $src, $dst, $src->getAllQueuedJobs() );
			$this->output( "Copied $totalOK/$total queued $type jobs.\n" );

			[ $total, $totalOK ] = $this->copyJobs( $src, $dst, $src->getAllDelayedJobs() );
			$this->output( "Copied $totalOK/$total delayed $type jobs.\n" );
		}
	}

	protected function copyJobs( JobQueue $src, JobQueue $dst, iterable $jobs ): array {
		$total = 0;
		$totalOK = 0;
		$batch = [];
		foreach ( $jobs as $job ) {
			++$total;
			$batch[] = $job;
			if ( count( $batch ) >= $this->getBatchSize() ) {
				$dst->push( $batch );
				$totalOK += count( $batch );
				$batch = [];
				$dst->waitForBackups();
			}
		}
		if ( count( $batch ) ) {
			$dst->push( $batch );
			$totalOK += count( $batch );
			$dst->waitForBackups();
		}

		return [ $total, $totalOK ];
	}
}

// @codeCoverageIgnoreStart
$maintClass = CopyJobQueue::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
