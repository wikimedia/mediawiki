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
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\MessageBlobStore;
use MediaWiki\Settings\SettingsBuilder;

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
		$this->addOption( 'dry-run', 'Determine what languages need to be rebuilt without changing anything' );
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
		$this->addOption(
			'no-progress',
			"Don't print a message for each rebuilt language file.  Use this instead of " .
			"--quiet to get a brief summary of the operation."
		);
	}

	public function finalSetup( SettingsBuilder $settingsBuilder = null ) {
		# This script needs to be run to build the initial l10n cache. But if
		# LanguageCode is not 'en', it won't be able to run because there is
		# no l10n cache. Break the cycle by forcing the LanguageCode setting to 'en'.
		$settingsBuilder->putConfigValue( MainConfigNames::LanguageCode, 'en' );
		parent::finalSetup( $settingsBuilder );
	}

	public function execute() {
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
		if ( $threads > 1 && ( !extension_loaded( 'sockets' ) || !function_exists( 'pcntl_fork' ) ) ) {
			$this->output( "Threaded rebuild requires ext-pcntl and ext-sockets; running single-threaded.\n" );
			$threads = 1;
		}

		$conf = $this->getConfig()->get( MainConfigNames::LocalisationCacheConf );
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
			LocalisationCache::getStoreFromConf( $conf, $this->getConfig()->get( MainConfigNames::CacheDirectory ) ),
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
			->getLanguageNames( LanguageNameUtils::AUTONYMS, LanguageNameUtils::SUPPORTED ) );
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

		$numRebuilt = 0;
		$total = count( $codes );
		$parentStatus = 0;

		if ( $threads <= 1 ) {
			// Single-threaded implementation
			$numRebuilt += $this->doRebuild( $codes, $lc, $force );
		} else {
			// Multi-threaded implementation
			$chunks = array_chunk( $codes, ceil( count( $codes ) / $threads ) );
			// Map from PID to readable socket
			$sockets = [];

			foreach ( $chunks as $codes ) {
				$socketpair = [];
				// Create a pair of sockets so that the child can communicate
				// the number of rebuilt langs to the parent.
				if ( !socket_create_pair( AF_UNIX, SOCK_STREAM, 0, $socketpair ) ) {
					$this->fatalError( 'socket_create_pair failed' );
				}

				$pid = pcntl_fork();

				if ( $pid === -1 ) {
					$this->fatalError( ' pcntl_fork failed' );
				} elseif ( $pid === 0 ) {
					// Child, reseed because there is no bug in PHP:
					// https://bugs.php.net/bug.php?id=42465
					mt_srand( getmypid() );

					$numRebuilt = $this->doRebuild( $codes, $lc, $force );
					// Report the number of rebuilt langs to the parent.
					$msg = strval( $numRebuilt ) . "\n";
					socket_write( $socketpair[1], $msg, strlen( $msg ) );
					// Child exits.
					return;
				} else {
					// Main thread
					$sockets[$pid] = $socketpair[0];
				}
			}

			// Wait for all children
			foreach ( $sockets as $pid => $socket ) {
				$status = 0;
				pcntl_waitpid( $pid, $status );

				if ( pcntl_wifexited( $status ) ) {
					$code = pcntl_wexitstatus( $status );
					if ( $code ) {
						$this->output( "Pid $pid exited with status $code !!\n" );
					} else {
						// Good exit status from child.  Read the number of rebuilt langs from it.
						$res = socket_read( $socket, 512, PHP_NORMAL_READ );
						if ( $res === false ) {
							$this->output( "socket_read failed in parent\n" );
						} else {
							$numRebuilt += intval( $res );
						}
					}

					// Mush all child statuses into a single value in the parent.
					$parentStatus |= $code;
				} elseif ( pcntl_wifsignaled( $status ) ) {
					$signum = pcntl_wtermsig( $status );
					$this->output( "Pid $pid terminated by signal $signum !!\n" );
					$parentStatus |= 1;
				}
			}
		}

		$this->output( "$numRebuilt languages rebuilt out of $total\n" );
		if ( $numRebuilt === 0 ) {
			$this->output( "Use --force to rebuild the caches which are still fresh.\n" );
		}
		if ( $parentStatus ) {
			$this->fatalError( 'Failed.', $parentStatus );
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
		$operation = $this->hasOption( 'dry-run' ) ? "Would rebuild" : "Rebuilding";

		foreach ( $codes as $code ) {
			if ( $force || $lc->isExpired( $code ) ) {
				if ( !$this->hasOption( 'no-progress' ) ) {
					$this->output( "$operation $code...\n" );
				}
				if ( !$this->hasOption( 'dry-run' ) ) {
					$lc->recache( $code );
				}
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
