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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to rebuild the localisation cache.
 *
 * @ingroup Maintenance
 */
class RebuildLocalisationCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Rebuild the localisation cache' );
		$this->addOption( 'force', 'Rebuild all files, even ones not out of date' );
		$this->addOption( 'threads', 'Fork more than one thread', false, true );
		$this->addOption( 'outdir', 'Override the output directory (normally $wgCacheDirectory)',
			false, true );
		$this->addOption( 'lang', 'Only rebuild these languages, comma separated.',
			false, true );
		$this->addOption(
			'store-class',
			'Override the LC store class (normally $wgLocalisationCacheConf[\'storeClass\'])',
			false,
			true
		);
		$this->addOption(
			'no-database',
			'EXPERIMENTAL: Disable the database backend. Setting this option will result in an error ' .
			'if you have extensions or use site configuration that need the database. This is an ' .
			'experimental feature to allow offline building of the localisation cache. Known limitations:' .
			"\n" .
			'* Incompatible with LCStoreDB, which always requires a database. ' . "\n" .
			'* The message purge may require a database. See --skip-message-purge.'
		);
		// T237148: The Gadget extension (bundled with MediaWiki by default) requires a database`
		// connection to register its modules for MessageBlobStore.
		$this->addOption(
			'skip-message-purge',
			'Skip purging of MessageBlobStore. The purge operation may require a database, depending ' .
			'on the configuration and extensions on this wiki. If skipping the purge now, you need to ' .
			'run purgeMessageBlobStore.php shortly after deployment.'
		);
	}

	public function finalSetup() {
		# This script needs to be run to build the inital l10n cache. But if
		# $wgLanguageCode is not 'en', it won't be able to run because there is
		# no l10n cache. Break the cycle by forcing $wgLanguageCode = 'en'.
		global $wgLanguageCode;
		$wgLanguageCode = 'en';
		parent::finalSetup();
	}

	public function execute() {
		global $wgLocalisationCacheConf, $wgCacheDirectory;

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
		// Allow fallbacks to create CDB files
		$conf['manualRecache'] = false;
		$conf['forceRecache'] = $force || !empty( $conf['forceRecache'] );
		if ( $this->hasOption( 'outdir' ) ) {
			$conf['storeDirectory'] = $this->getOption( 'outdir' );
		}

		if ( $this->hasOption( 'store-class' ) ) {
			$conf['storeClass'] = $this->getOption( 'store-class' );
		}

		// XXX Copy-pasted from ServiceWiring.php. Do we need a factory for this one caller?
		$services = MediaWikiServices::getInstance();
		$lc = new LocalisationCacheBulkLoad(
			new ServiceOptions(
				LocalisationCache::CONSTRUCTOR_OPTIONS,
				$conf,
				$services->getMainConfig()
			),
			LocalisationCache::getStoreFromConf( $conf, $wgCacheDirectory ),
			LoggerFactory::getInstance( 'localisation' ),
			$this->hasOption( 'skip-message-purge' ) ? [] :
				[ static function () use ( $services ) {
					MessageBlobStore::clearGlobalCacheEntry( $services->getMainWANObjectCache() );
				} ],
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);

		$allCodes = array_keys( $services
			->getLanguageNameUtils()
			->getLanguageNames( null, 'mwfile' ) );
		if ( $this->hasOption( 'lang' ) ) {
			# Validate requested languages
			$codes = array_intersect( $allCodes,
				explode( ',', $this->getOption( 'lang' ) ) );
			# Bailed out if nothing is left
			if ( count( $codes ) == 0 ) {
				$this->fatalError( 'None of the languages specified exists.' );
			}
		} else {
			# By default get all languages
			$codes = $allCodes;
		}
		sort( $codes );

		// Initialise and split into chunks
		$numRebuilt = 0;
		$total = count( $codes );
		$chunks = array_chunk( $codes, ceil( count( $codes ) / $threads ) );
		$pids = [];
		$parentStatus = 0;
		foreach ( $chunks as $codes ) {
			// Do not fork for only one thread
			$pid = ( $threads > 1 ) ? pcntl_fork() : -1;

			if ( $pid === 0 ) {
				// Child, reseed because there is no bug in PHP:
				// https://bugs.php.net/bug.php?id=42465
				mt_srand( getmypid() );

				$this->doRebuild( $codes, $lc, $force );
				exit( 0 );
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

			if ( pcntl_wifexited( $status ) ) {
			$code = pcntl_wexitstatus( $status );
				if ( $code ) {
					$this->output( "Pid $pid exited with status $code !!\n" );
				}
				// Mush all child statuses into a single value in the parent.
				$parentStatus |= $code;
			} elseif ( pcntl_wifsignaled( $status ) ) {
				$signum = pcntl_wtermsig( $status );
				$this->output( "Pid $pid terminated by signal $signum !!\n" );
				$parentStatus |= 1;
			}
		}

		if ( !$pids ) {
			$this->output( "$numRebuilt languages rebuilt out of $total\n" );
			if ( $numRebuilt === 0 ) {
				$this->output( "Use --force to rebuild the caches which are still fresh.\n" );
			}
		}
		if ( $parentStatus ) {
			exit( $parentStatus );
		}
	}

	/**
	 * Helper function to rebuild list of languages codes. Prints the code
	 * for each language which is rebuilt.
	 * @param string[] $codes List of language codes to rebuild.
	 * @param LocalisationCache $lc
	 * @param bool $force Rebuild up-to-date languages
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

	/** @inheritDoc */
	public function getDbType() {
		if ( $this->hasOption( 'no-database' ) ) {
			return Maintenance::DB_NONE;
		}

		return parent::getDbType();
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

$maintClass = RebuildLocalisationCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
