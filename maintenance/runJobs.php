<?php
/**
 * Run pending jobs.
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

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\Logger\LoggerFactory;

// So extensions (and other code) can check whether they're running in job mode
define( 'MEDIAWIKI_JOB_RUNNER', true );

/**
 * Maintenance script that runs pending jobs.
 *
 * @ingroup Maintenance
 */
class RunJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Run pending jobs' );
		$this->addOption( 'maxjobs', 'Maximum number of jobs to run', false, true );
		$this->addOption( 'maxtime', 'Maximum amount of wall-clock time', false, true );
		$this->addOption( 'type', 'Type of job to run', false, true );
		$this->addOption( 'procs', 'Number of processes to use', false, true );
		$this->addOption( 'nothrottle', 'Ignore job throttling configuration', false, false );
		$this->addOption( 'result', 'Set to "json" to print only a JSON response', false, true );
		$this->addOption( 'wait', 'Wait for new jobs instead of exiting', false, false );
	}

	public function memoryLimit() {
		if ( $this->hasOption( 'memory-limit' ) ) {
			return parent::memoryLimit();
		}

		// Don't eat all memory on the machine if we get a bad job.
		return "150M";
	}

	public function execute() {
		if ( $this->hasOption( 'procs' ) ) {
			$procs = intval( $this->getOption( 'procs' ) );
			if ( $procs < 1 || $procs > 1000 ) {
				$this->fatalError( "Invalid argument to --procs" );
			} elseif ( $procs != 1 ) {
				$fc = new ForkController( $procs );
				if ( $fc->start() != 'child' ) {
					exit( 0 );
				}
			}
		}

		$outputJSON = ( $this->getOption( 'result' ) === 'json' );
		$wait = $this->hasOption( 'wait' );

		$runner = new JobRunner( LoggerFactory::getInstance( 'runJobs' ) );
		if ( !$outputJSON ) {
			$runner->setDebugHandler( [ $this, 'debugInternal' ] );
		}

		$type = $this->getOption( 'type', false );
		$maxJobs = $this->getOption( 'maxjobs', false );
		$maxTime = $this->getOption( 'maxtime', false );
		$throttle = !$this->hasOption( 'nothrottle' );

		while ( true ) {
			$response = $runner->run( [
				'type'     => $type,
				'maxJobs'  => $maxJobs,
				'maxTime'  => $maxTime,
				'throttle' => $throttle,
			] );

			if ( $outputJSON ) {
				$this->output( FormatJson::encode( $response, true ) );
			}

			if (
				!$wait ||
				$response['reached'] === 'time-limit' ||
				$response['reached'] === 'job-limit' ||
				$response['reached'] === 'memory-limit'
			) {
				break;
			}

			if ( $maxJobs !== false ) {
				$maxJobs -= count( $response['jobs'] );
			}

			sleep( 1 );
		}
	}

	/**
	 * @param string $s
	 */
	public function debugInternal( $s ) {
		$this->output( $s );
	}
}

$maintClass = RunJobs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
