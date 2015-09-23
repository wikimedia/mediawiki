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

/**
 * Maintenance script that runs pending jobs.
 *
 * @ingroup Maintenance
 */
class RunJobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run pending jobs";
		$this->addOption( 'maxjobs', 'Maximum number of jobs to run', false, true );
		$this->addOption( 'maxtime', 'Maximum amount of wall-clock time', false, true );
		$this->addOption( 'type', 'Type of job to run', false, true );
		$this->addOption( 'procs', 'Number of processes to use', false, true );
		$this->addOption( 'nothrottle', 'Ignore job throttling configuration', false, false );
		$this->addOption( 'result', 'Set to JSON to print only a JSON response', false, true );
	}

	public function memoryLimit() {
		if ( $this->hasOption( 'memory-limit' ) ) {
			return parent::memoryLimit();
		}

		// Don't eat all memory on the machine if we get a bad job.
		return "150M";
	}

	public function execute() {
		global $wgCommandLineMode;

		if ( $this->hasOption( 'procs' ) ) {
			$procs = intval( $this->getOption( 'procs' ) );
			if ( $procs < 1 || $procs > 1000 ) {
				$this->error( "Invalid argument to --procs", true );
			} elseif ( $procs != 1 ) {
				$fc = new ForkController( $procs );
				if ( $fc->start() != 'child' ) {
					exit( 0 );
				}
			}
		}

		$outputJSON = ( $this->getOption( 'result' ) === 'json' );

		// Enable DBO_TRX for atomicity; JobRunner manages transactions
		// and works well in web server mode already (@TODO: this is a hack)
		$wgCommandLineMode = false;

		$runner = new JobRunner( LoggerFactory::getInstance( 'runJobs' ) );
		if ( !$outputJSON ) {
			$runner->setDebugHandler( array( $this, 'debugInternal' ) );
		}

		$response = $runner->run( array(
			'type'     => $this->getOption( 'type', false ),
			'maxJobs'  => $this->getOption( 'maxjobs', false ),
			'maxTime'  => $this->getOption( 'maxtime', false ),
			'throttle' => $this->hasOption( 'nothrottle' ) ? false : true,
		) );

		if ( $outputJSON ) {
			$this->output( FormatJson::encode( $response, true ) );
		}

		$wgCommandLineMode = true;
	}

	/**
	 * @param string $s
	 */
	public function debugInternal( $s ) {
		$this->output( $s );
	}
}

$maintClass = "RunJobs";
require_once RUN_MAINTENANCE_IF_MAIN;
