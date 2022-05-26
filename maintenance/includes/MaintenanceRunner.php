<?php

namespace MediaWiki\Maintenance;

use Exception;
use LCStoreNull;
use Maintenance;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Settings\SettingsBuilder;

/**
 * A runner for maintenance scripts.
 *
 * @since 1.39
 * @unstable
 */
class MaintenanceRunner {

	/** @var ?Maintenance */
	private $scriptObject = null;

	/** @var MaintenanceParameters */
	private $parameters;

	/**
	 * Initialize the runner
	 *
	 * @param string $scriptClass
	 */
	public function init( string $scriptClass ) {
		global $IP, $wgCommandLineMode;

		# Abort if called from a web server
		# wfIsCLI() is not available yet
		if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
			$this->fatalError( 'This script must be run from the command line' );
		}

		if ( $IP === null ) {
			$this->fatalError( "\$IP not set, aborting!\n" .
				'(Did you forget to call parent::__construct() in your maintenance script?)' );
		}

		# Make sure we can handle script parameters
		if ( !ini_get( 'register_argc_argv' ) ) {
			$this->fatalError( 'Cannot get command line arguments, register_argc_argv is set to false' );
		}

		// Send PHP warnings and errors to stderr instead of stdout.
		// This aids in diagnosing problems, while keeping messages
		// out of redirected output.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		// Get an object to start us off
		$this->scriptObject = new $scriptClass();

		// make sure we clean up after ourselves.
		register_shutdown_function( [ $this, 'cleanup' ] );

		$this->parameters = $this->scriptObject->getParameters();

		// Basic checks and such
		$this->scriptObject->setup();

		# Set max execution time to 0 (no limit). PHP.net says that
		# "When running PHP from the command line the default setting is 0."
		# But sometimes this doesn't seem to be the case.
		// @phan-suppress-next-line PhanTypeMismatchArgumentInternal Scalar okay with php8.1
		ini_set( 'max_execution_time', 0 );

		$wgCommandLineMode = true;

		# Turn off output buffering if it's on
		while ( ob_get_level() > 0 ) {
			ob_end_flush();
		}
	}

	/**
	 * Returns the maintenance script name.
	 *
	 * Safe to call after init().
	 *
	 * @return string
	 */
	public function getName(): string {
		// The name has been initialized by Maintenance::loadParamsAndArgs(),
		// which has been called by Maintenance::setup(), which was called
		// by $this->init().
		return $this->scriptObject->getName();
	}

	/**
	 * Define how settings are loaded (e.g. LocalSettings.php)
	 *
	 * @internal
	 * @return void
	 */
	public function defineSettings() {
		global $wgCommandLineMode, $IP;

		// NOTE: This doesn't actually load anything, that will be done later
		//       by Setup.php. But it defines MW_CONFIG_CALLBACK and possibly
		//       other constants that control initialization.
		if ( !defined( 'MW_CONFIG_CALLBACK' ) ) {
			if ( $this->parameters->hasOption( 'conf' ) ) {
				// Define the constant instead of directly setting $settingsFile
				// to ensure consistency. wfDetectLocalSettingsFile() will return
				// MW_CONFIG_FILE if it is defined.
				define( 'MW_CONFIG_FILE', $this->parameters->getOption( 'conf' ) );
			}
			$settingsFile = wfDetectLocalSettingsFile( $IP );

			if ( $this->parameters->hasOption( 'wiki' ) ) {
				$wikiName = $this->parameters->getOption( 'wiki' );
				$bits = explode( '-', $wikiName, 2 );
				define( 'MW_DB', $bits[0] );
				define( 'MW_PREFIX', $bits[1] ?? '' );
				define( 'MW_WIKI_NAME', $wikiName );
			} elseif ( $this->parameters->hasOption( 'server' ) ) {
				// Provide the option for site admins to detect and configure
				// multiple wikis based on server names. This offers --server
				// as alternative to --wiki.
				// See https://www.mediawiki.org/wiki/Manual:Wiki_family
				$_SERVER['SERVER_NAME'] = $this->parameters->getOption( 'server' );
			}

			if ( !is_readable( $settingsFile ) ) {
				$this->fatalError( "The file $settingsFile must exist and be readable.\n" .
					"Use --conf to specify it." );
			}
			$wgCommandLineMode = true;
		}
	}

	/**
	 * MW_SETUP_CALLBACK handler, for overriding config.
	 *
	 * @param SettingsBuilder $settingsBuilder
	 *
	 * @return void
	 */
	public function overrideConfig( SettingsBuilder $settingsBuilder ) {
		$config = $settingsBuilder->getConfig();

		if ( $this->scriptObject->getDbType() === Maintenance::DB_NONE ) {
			$cacheConf = $config->get( MainConfigNames::LocalisationCacheConf );
			if ( $cacheConf['storeClass'] === false
				&& ( $cacheConf['store'] == 'db'
					|| ( $cacheConf['store'] == 'detect'
						&& !$config->get( MainConfigNames::CacheDirectory ) ) )
			) {
				$cacheConf['storeClass'] = LCStoreNull::class;
				$settingsBuilder->putConfigValue( MainConfigNames::LocalisationCacheConf, $cacheConf );
			}
		}

		$this->scriptObject->finalSetup( $settingsBuilder );
	}

	/**
	 * Run the maintenance script.
	 *
	 * @note The process should exit immediately after this method returns.
	 * At that point, MediaWiki will already have been shut down.
	 * It is no longer safe to perform any write operations on the database.
	 *
	 * @note Any exceptions thrown by the maintenance script will cause this
	 * method to terminate the process after reporting the error to the user,
	 * without shutdown and cleanup.
	 *
	 * @return bool true on success, false on failure,
	 *         passed through from Maintenance::execute().
	 */
	public function run(): bool {
		// Initialize main config instance
		$this->scriptObject->setConfig( MediaWikiServices::getInstance()->getMainConfig() );

		// Double check required extensions are installed
		$this->scriptObject->checkRequiredExtensions();

		if ( $this->scriptObject->getDbType() == Maintenance::DB_NONE ) {
			// Be strict with maintenance tasks that claim to not need a database by
			// disabling the storage backend.
			MediaWikiServices::disableStorageBackend();
		}

		$this->scriptObject->validateParamsAndArgs();

		// Do the work
		try {
			$success = $this->scriptObject->execute() !== false;

			// Potentially debug globals
			$this->scriptObject->globals();

			$this->scriptObject->shutdown();

			return $success;
		} catch ( Exception $ex ) {
			$exReportMessage = '';
			while ( $ex ) {
				$cls = get_class( $ex );
				$exReportMessage .= "$cls from line {$ex->getLine()} of {$ex->getFile()}: {$ex->getMessage()}\n";
				$exReportMessage .= $ex->getTraceAsString() . "\n";
				$ex = $ex->getPrevious();
			}
			$this->error( $exReportMessage );

			// Exit now because process is in an unsafe state.
			// Also to avoid DBTransactionError (T305730).
			// Do not commit database writes, do not run deferreds, do not pass Go.
			exit( 1 );
		}
	}

	/**
	 * Output a message and terminate the current script.
	 *
	 * @param string $msg Error message
	 * @param int $exitCode PHP exit status. Should be in range 1-254.
	 * @return never
	 */
	private function fatalError( $msg, $exitCode = 1 ) {
		$this->error( $msg );
		exit( $exitCode );
	}

	/**
	 * @param string $msg
	 */
	private function error( string $msg ) {
		// Print to stderr if possible, don't mix it in with stdout output.
		if ( defined( 'STDERR' ) ) {
			fwrite( STDERR, $msg );
		} else {
			echo $msg;
		}
	}

	/**
	 * Should we execute the maintenance script, or just allow it to be included
	 * as a standalone class? It checks that the call stack only includes this
	 * function and "requires" (meaning was called from the file scope)
	 *
	 * @return bool
	 */
	public static function shouldExecute() {
		global $wgCommandLineMode;

		if ( !function_exists( 'debug_backtrace' ) ) {
			// If someone has a better idea...
			return $wgCommandLineMode;
		}

		$bt = debug_backtrace();
		$count = count( $bt );
		if ( $count < 2 ) {
			return false;
		}
		if ( $bt[0]['class'] !== self::class || $bt[0]['function'] !== 'shouldExecute' ) {
			return false; // last call should be to this function
		}
		$includeFuncs = [ 'require_once', 'require', 'include', 'include_once' ];
		for ( $i = 1; $i < $count; $i++ ) {
			if ( !in_array( $bt[$i]['function'], $includeFuncs ) ) {
				return false; // previous calls should all be "requires"
			}
		}

		return true;
	}

	/**
	 * Handler for register_shutdown_function
	 * @internal
	 * @return void
	 */
	public function cleanup() {
		$this->scriptObject->cleanupChanneled();
	}

}
