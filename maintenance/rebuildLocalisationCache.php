<?php

/**
 * Rebuild the localisation cache. Useful if you disabled automatic updates
 * using $wgLocalisationCacheConf['manualRecache'] = true;
 *
 * Usage:
 *    php rebuildLocalisationCache.php [--force] [--threads=N]
 *
 * Use --force to rebuild all files, even the ones that are not out of date.
 * Use --threads=N to fork more threads.
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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script to rebuild the localisation cache.
 *
 * @ingroup Maintenance
 */
class RebuildLocalisationCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Rebuild the localisation cache";
		$this->addOption( 'force', 'Rebuild all files, even ones not out of date' );
		$this->addOption( 'threads', 'Fork more than one thread', false, true );
		$this->addOption( 'outdir', 'Override the output directory (normally $wgCacheDirectory)',
			false, true );
	}

	public function memoryLimit() {
		if ( $this->hasOption( 'memory-limit' ) ) {
			return parent::memoryLimit();
		}
		return '1000M';
	}

	public function execute() {
		global $wgLocalisationCacheConf;

		$force = $this->hasOption( 'force' );
		$threads = $this->getOption( 'threads', 1 );
		if ( $threads < 1 || $threads != intval( $threads ) ) {
			$this->output( "Invalid thread count specified; running single-threaded.\n" );
			$threads = 1;
		}
		if ( $threads > 1 && wfIsWindows() ) {
			$this->output( "Threaded rebuild is not supported on Windows; running single-threaded.\n" );
			$threads = 1;
		}
		if ( $threads > 1 && !function_exists( 'pcntl_fork' ) ) {
			$this->output( "PHP pcntl extension is not present; running single-threaded.\n" );
			$threads = 1;
		}

		$conf = $wgLocalisationCacheConf;
		$conf['manualRecache'] = false; // Allow fallbacks to create CDB files
		if ( $force ) {
			$conf['forceRecache'] = true;
		}
		if ( $this->hasOption( 'outdir' ) ) {
			$conf['storeDirectory'] = $this->getOption( 'outdir' );
		}
		$lc = new LocalisationCache_BulkLoad( $conf );

		$codes = array_keys( Language::fetchLanguageNames( null, 'mwfile' ) );
		sort( $codes );

		// Initialise and split into chunks
		$numRebuilt = 0;
		$total = count( $codes );
		$chunks = array_chunk( $codes, ceil( count( $codes ) / $threads ) );
		$pids = array();
		foreach ( $chunks as $codes ) {
			// Do not fork for only one thread
			$pid = ( $threads > 1 ) ? pcntl_fork() : -1;

			if ( $pid === 0 ) {
				// Child, reseed because there is no bug in PHP:
				// http://bugs.php.net/bug.php?id=42465
				mt_srand( getmypid() );
				$numRebuilt = $this->doRebuild( $codes, $lc, $force );
				// Abuse the exit value for the count of rebuild languages
				exit( $numRebuilt );
			} elseif ( $pid === -1 ) {
				// Fork failed or one thread, do it serialized
				$numRebuilt += $this->doRebuild( $codes, $lc, $force );
			} else {
				// Main thread
				$pids[] = $pid;
			}
		}
		// Wait for all children
		foreach ( $pids as $pid ) {
			$status = 0;
			pcntl_waitpid( $pid, $status );
			// Fetch the count from the return value
			$numRebuilt += pcntl_wexitstatus( $status );
		}

		$this->output( "$numRebuilt languages rebuilt out of $total\n" );
		if ( $numRebuilt === 0 ) {
			$this->output( "Use --force to rebuild the caches which are still fresh.\n" );
		}
	}

	/**
	 * Helper function to rebuild list of languages codes. Prints the code
	 * for each language which is rebuilt.
	 * @param $codes array List of language codes to rebuild.
	 * @param $lc LocalisationCache Instance of LocalisationCache_BulkLoad (?)
	 * @param $force bool Rebuild up-to-date languages
	 * @return int Number of rebuilt languages
	 */
	private function doRebuild( $codes, $lc, $force ) {
		$numRebuilt = 0;
		foreach ( $codes as $code ) {
			if ( $force || $lc->isExpired( $code ) ) {
				$this->output( "Rebuilding $code...\n" );
				$lc->recache( $code );
				$numRebuilt++;
			}
		}
		return $numRebuilt;
	}

	/**
	 * Sets whether a run of this maintenance script has the force parameter set
	 *
	 * @param bool $forced
	 */
	public function setForce( $forced = true ) {
		$this->mOptions['force'] = $forced;
	}
}

$maintClass = "RebuildLocalisationCache";
require_once( RUN_MAINTENANCE_IF_MAIN );
