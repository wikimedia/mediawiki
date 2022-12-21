<?php

namespace MediaWiki\Maintenance;

use Config;
use Exception;
use LCStoreNull;
use Maintenance;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Settings\SettingsBuilder;
use MWException;
use Profiler;

/**
 * A runner for maintenance scripts.
 *
 * @since 1.39
 * @unstable
 */
class MaintenanceRunner {

	/**
	 * Identifies the script to execute in a way that setup() understands.
	 *
	 * @var ?string
	 */
	private $script = null;

	/** @var string[]|null */
	private $scriptArgv = null;

	/** @var Maintenance|null */
	private $scriptObject = null;

	/** @var MaintenanceParameters */
	private $parameters;

	/** @var bool */
	private $runFromWrapper = false;

	/**
	 * Default constructor. Children should call this *first* if implementing
	 * their own constructors
	 *
	 * @stable to call
	 */
	public function __construct() {
		$this->parameters = new MaintenanceParameters();
		$this->addDefaultParams();
	}

	/**
	 * Add the default parameters to the scripts
	 */
	protected function addDefaultParams() {
		// Generic (non-script-dependent) options:

		$this->parameters->addOption( 'conf', 'Location of LocalSettings.php, if not default', false, true );
		$this->parameters->addOption( 'wiki', 'For specifying the wiki ID', false, true );
		$this->parameters->addOption( 'globals', 'Output globals at the end of processing for debugging' );
		$this->parameters->addOption(
			'memory-limit',
			'Set a specific memory limit for the script, '
			. '"max" for no limit or "default" to avoid changing it',
			false,
			true
		);
		$this->parameters->addOption( 'server', "The protocol and server name to use in URLs, e.g. " .
			"https://en.wikipedia.org. This is sometimes necessary because " .
			"server name detection may fail in command line scripts.", false, true );
		$this->parameters->addOption( 'profiler', 'Profiler output format (usually "text")', false, true );

		// Save generic options to display them separately in help
		$generic = $this->parameters->getOptionNames();
		$this->parameters->assignGroup( Maintenance::GENERIC_MAINTENANCE_PARAMETERS, $generic );
	}

	/**
	 * @param int $code
	 *
	 * @return never
	 */
	private function showHelpAndExit( $code = 0 ) {
		foreach ( $this->parameters->getErrors() as $error ) {
			$this->error( "$error\n" );
			$code = 1;
		}

		$this->parameters->setDescription( 'Runner for maintenance scripts' );

		$help = $this->parameters->getHelp();
		echo $help;
		exit( $code );
	}

	/**
	 * Initialize the runner from the given command line arguments
	 * as passed to a wrapper script.
	 *
	 * @note Called before Setup.php
	 *
	 * @param string[] $argv The arguments passed from the command line,
	 *        including the wrapper script at index 0, and usually
	 *        the script to run at index 1.
	 */
	public function initFromWrapper( array $argv ) {
		$scriptClass = null;

		$this->parameters->setName( $argv[0] );
		$this->parameters->setAllowUnregisteredOptions( true );
		$this->parameters->addArg(
			'script',
			'The class name of the maintenance script to run. ' .
				'Dots (.) are supported as namespace separator. ' .
				'"MyExt:SomeScript" expands to "MediaWiki\Extension\MyExt\Maintenance\SomeScript".',
			true
		);

		$this->runFromWrapper = true;
		$this->parameters->loadWithArgv( $argv, 1 );

		// script params
		$argv = array_slice( $argv, 2 );

		if ( $this->parameters->validate() ) {
			$scriptClass = $this->parameters->getArg( 0 );

			// Special handling for the 'help' command
			if ( $scriptClass === 'help' ) {
				if ( $this->parameters->hasArg( 1 ) ) {
					$scriptClass = $this->parameters->getArg( 1 );

					// turn <help> <command> into <command> --help
					$this->parameters->loadWithArgv( [ $scriptClass ] );
					$argv = [ '--help' ];
				} else {
					// same as no command
					$scriptClass = null;
				}
			}
		}

		if ( $scriptClass ) {
			// Support "$ext:$script" format
			if ( preg_match( '/^([\w.\\\\]+):([\w.\\\\]+)$/', $scriptClass, $m ) ) {
				$scriptClass = "MediaWiki\\Extension\\{$m[1]}\\Maintenance\\{$m[2]}";
			}

			// Accept dot (.) as namespace separators as well.
			// Backslashes are just annoying on the command line.
			$scriptClass = strtr( $scriptClass, '.', '\\' );

			// Strip another argument from $argv!
			$this->initInternal( $scriptClass, $argv );
		} else {
			$this->showHelpAndExit();
		}
	}

	/**
	 * Initialize the runner for the given class.
	 * This is used when running scripts directly, without a wrapper.
	 *
	 * @note Called before Setup.php
	 *
	 * @param string $scriptClass The script class to run
	 * @param string[] $argv The arguments to passed to the script, including
	 *        the script itself at index 0.
	 */
	public function initForClass( string $scriptClass, $argv ) {
		$this->runFromWrapper = false;
		$this->script = $scriptClass;
		$this->parameters->setName( $argv[0] );
		$this->parameters->loadWithArgv( $argv );
		$this->initInternal( $scriptClass, array_slice( $argv, 1 ) );
	}

	/**
	 * Initialize the runner.
	 * @note Called before Setup.php
	 *
	 * @param string $scriptClass The script to run
	 * @param string[] $scriptArgv The arguments to pass to the maintenance script,
	 *        not including the script itself.
	 */
	private function initInternal( string $scriptClass, array $scriptArgv ) {
		global $IP, $wgCommandLineMode;

		$this->script = $scriptClass;
		$this->scriptArgv = $scriptArgv;

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

		// make sure we clean up after ourselves.
		register_shutdown_function( [ $this, 'cleanup' ] );

		$wgCommandLineMode = true;

		// Turn off output buffering if it's on
		while ( ob_get_level() > 0 ) {
			ob_end_flush();
		}
	}

	/**
	 * MW_FINAL_SETUP_CALLBACK handler, for setting up the Maintenance object.
	 *
	 * @param SettingsBuilder $settings
	 */
	public function setup( SettingsBuilder $settings ) {
		// NOTE: we can't check this earlier, since we need the autoloader to be initialized.
		if ( !class_exists( $this->script ) ) {
			$this->fatalError( "{$this->script} not found.\n" );
		}

		// Initialize the actual Maintenance object
		// TODO: allow the script to be specified by an alias name
		$this->scriptObject = new $this->script();
		$this->scriptObject->setName( $this->getName() );

		if ( !$this->scriptObject instanceof Maintenance ) {
			$this->fatalError( "{$this->script} is not a maintenance script.\n" );
		}

		// Inject runner stuff into the script's parameter definitions.
		// This is mainly used when printing help.
		$scriptParameters = $this->scriptObject->getParameters();

		if ( $this->runFromWrapper ) {
			$scriptParameters->setUsagePrefix( 'php ' . $this->parameters->getName() );
		}

		$scriptParameters->mergeOptions( $this->parameters );
		$this->parameters = $scriptParameters;

		// Ingest argv
		$this->scriptObject->loadWithArgv( $this->scriptArgv );

		// Basic checks and such
		$this->scriptObject->setup();

		// Set the memory limit
		$this->adjustMemoryLimit();

		// Override any config settings
		$this->overrideConfig( $settings );
	}

	/**
	 * Returns the maintenance script name to show in the help message.
	 *
	 * @return string
	 */
	public function getName(): string {
		// Once one of the init methods was called, getArg( 0 ) should always
		// return something.
		return $this->parameters->getArg( 0 ) ?? 'UNKNOWN';
	}

	/**
	 * Normally we disable the memory_limit when running admin scripts.
	 * Some scripts may wish to actually set a limit, however, to avoid
	 * blowing up unexpectedly.
	 * @see Maintenance::memoryLimit()
	 * @return string
	 */
	private function memoryLimit() {
		if ( $this->parameters->hasOption( 'memory-limit' ) ) {
			$limit = $this->parameters->getOption( 'memory-limit', 'max' );
			$limit = trim( $limit, "\" '" ); // trim quotes in case someone misunderstood
			return $limit;
		}

		$limit = $this->scriptObject->memoryLimit();
		return $limit ?: 'max';
	}

	/**
	 * Adjusts PHP's memory limit to better suit our needs, if needed.
	 */
	private function adjustMemoryLimit() {
		$limit = $this->memoryLimit();
		if ( $limit == 'max' ) {
			$limit = -1; // no memory limit
		}
		if ( $limit != 'default' ) {
			ini_set( 'memory_limit', $limit );
		}
	}

	/**
	 * Define how settings are loaded (e.g. LocalSettings.php)
	 * @note Called before Setup.php
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
	 * @param SettingsBuilder $settingsBuilder
	 *
	 * @return void
	 */
	private function overrideConfig( SettingsBuilder $settingsBuilder ) {
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

		$output = $this->parameters->getOption( 'profiler' );
		if ( $output ) {
			// Per-script profiling; useful for debugging
			$this->activateProfiler( $output, $config );
		}

		$this->scriptObject->finalSetup( $settingsBuilder );
	}

	/**
	 * Activate the profiler (assuming $wgProfiler is set)
	 *
	 * @param string $output
	 * @param Config $config
	 *
	 * @throws MWException
	 */
	private function activateProfiler( string $output, Config $config ) {
		$profiler = $config->get( MainConfigNames::Profiler );
		$limits = $config->get( MainConfigNames::TrxProfilerLimits );

		if ( isset( $profiler['class'] ) ) {
			$class = $profiler['class'];
			/** @var Profiler $profiler */
			$profiler = new $class(
				[ 'sampling' => 1, 'output' => [ $output ] ]
				+ $profiler
				+ [ 'threshold' => 0.0 ]
			);
			$profiler->setAllowOutput();
			Profiler::replaceStubInstance( $profiler );
		}

		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->setLogger( LoggerFactory::getInstance( 'DBPerformance' ) );
		$trxProfiler->setExpectations( $limits['Maintenance'], __METHOD__ );
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
			if ( $this->parameters->hasOption( 'globals' ) ) {
				print_r( $GLOBALS );
			}

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
		if ( $this->scriptObject ) {
			$this->scriptObject->cleanupChanneled();
		}
	}

}
