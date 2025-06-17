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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Json\FormatJson;
use MediaWiki\Maintenance\ForkController;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Settings\SettingsBuilder;

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

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		// So extensions (and other code) can check whether they're running in job mode.
		// This is not defined if this script is included from installer/updater or phpunit.
		define( 'MEDIAWIKI_JOB_RUNNER', true );
		parent::finalSetup( $settingsBuilder );
	}

	/** @inheritDoc */
	public function memoryLimit() {
		if ( $this->hasOption( 'memory-limit' ) ) {
			return parent::memoryLimit();
		}

		// Don't eat all memory on the machine if we get a bad job.
		//
		// The default memory_limit for PHP-CLI is -1 (unlimited).
		// This is fine for most maintenance scripts, but runJobs.php is unusually likely
		// to leak memory (e.g. some badly-managed in-process cache array in some class)
		// because it can run for long periods doing different tasks.
		// Let's use 3x the limit for a web request.
		global $wgMemoryLimit;
		$limit = wfShorthandToInteger( (string)$wgMemoryLimit );
		return $limit === -1 ? $limit : ( $limit * 3 );
	}

	public function execute() {
		if ( $this->hasOption( 'procs' ) ) {
			$procs = intval( $this->getOption( 'procs' ) );
			if ( $procs < 1 || $procs > 1000 ) {
				$this->fatalError( "Invalid argument to --procs" );
			} elseif ( $procs != 1 ) {
				try {
					$fc = new ForkController( $procs );
				} catch ( Throwable $e ) {
					$this->fatalError( $e->getMessage() );
				}
				if ( $fc->start() != 'child' ) {
					return;
				}
			}
		}

		$outputJSON = ( $this->getOption( 'result' ) === 'json' );
		$wait = $this->hasOption( 'wait' );

		$runner = $this->getServiceContainer()->getJobRunner();
		if ( !$outputJSON ) {
			$runner->setDebugHandler( $this->debugInternal( ... ) );
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
				$response['reached'] === 'memory-limit' ||
				$response['reached'] === 'exception'
			) {
				// If job queue is empty, output it
				if ( !$outputJSON && $response['jobs'] === [] ) {
					$this->output( "Job queue is empty.\n" );
				}
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
	private function debugInternal( $s ) {
		$this->output( $s );
	}
}

// @codeCoverageIgnoreStart
$maintClass = RunJobs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
