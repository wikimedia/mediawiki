<?php

/**
 * Rebuild the localisation cache. Useful if you disabled automatic updates
 * using $wgLocalisationCacheConf['manualRecache'] = true;
 *
 * Usage:
 *    php rebuildLocalisationCache.php [--force]
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
 * @ingroup Maintenance
 */

require_once( "Maintenance.php" );

class RebuildLocalisationCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Rebuild the localisation cache";
		$this->addOption( 'force', 'Rebuild all files, even ones not out of date' );
		$this->addOption( 'threads', 'Fork more than one thread', false, true );
	}

	public function execute() {
		global $wgLocalisationCacheConf;

		ini_set( 'memory_limit', '200M' );

		$force = $this->hasOption('force');
		$threads = $this->getOption( 'threads', 1 );

		$conf = $wgLocalisationCacheConf;
		$conf['manualRecache'] = false; // Allow fallbacks to create CDB files
		if ( $force ) {
			$conf['forceRecache'] = true;
		}
		$lc = new LocalisationCache_BulkLoad( $conf );

		$codes = array_keys( Language::getLanguageNames( true ) );
		sort( $codes );

		// Initialise and split into chunks
		$numRebuilt = 0;
		$total = count($codes);
		$chunks = array_chunk( $codes, ceil(count($codes)/$threads) );
		$pids = array();
		foreach ( $chunks as $codes ) {
			// Do not fork for only one thread
			$pid = ( $threads > 1 ) ? pcntl_fork() : -1;

			if ( $pid === 0 ) {
				// Child
				doRebuild( $codes, $numRebuilt, $lc, $force );
				exit();
			} elseif ($pid === -1) {
				// Fork failed or one thread, do it serialized
				doRebuild( $codes, $numRebuilt, $lc, $force );
			} else {
				// Main thread
				$pids[] = $pid;
			}
		}
		// Wait for all children
		foreach ( $pids as $pid ) pcntl_waitpid($pid, $status);

		$this->output( "$numRebuilt languages rebuilt out of $total\n" );
		if ( $numRebuilt == 0 ) {
			$this->output( "Use --force to rebuild the caches which are still fresh.\n" );
		}
	}
}

$maintClass = "RebuildLocalisationCache";
require_once( DO_MAINTENANCE );
