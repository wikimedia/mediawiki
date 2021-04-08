#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

class PHPUnitMaintClass {
	/**
	 * Array of desired/allowed params
	 * @var array[]
	 */
	private $mParams = [];
	/** @var array This is the list of options that were actually passed */
	private $mOptions = [];
	/** @var bool Have we already loaded our user input? */
	private $mInputLoaded = false;

	public function __construct() {
		$this->addOption( 'help', 'Display this help message' );
		$this->addOption( 'wiki', 'For specifying the wiki ID', true );
		$this->addOption( 'dbuser', 'The DB user to use for this script', true );
		$this->addOption( 'dbpass', 'The password to use for this script', true );
		$this->addOption( 'dbgroupdefault', 'The default DB group to use.', true );
		$this->addOption( 'use-filebackend', 'Use filebackend', true );
		$this->addOption( 'use-bagostuff', 'Use bagostuff', true );
		$this->addOption( 'use-jobqueue', 'Use jobqueue', true );
		$this->addOption( 'use-normal-tables', 'Use normal DB tables.' );
		$this->addOption(
			'reuse-db', 'Init DB only if tables are missing and keep after finish.'
		);
	}

	/**
	 * Add a parameter to the script. Will be displayed on --help
	 * with the associated description
	 *
	 * @param string $name The name of the param (help, version, etc)
	 * @param string $description The description of the param to show on --help
	 * @param bool $withArg Is an argument required with this option?
	 */
	private function addOption( $name, $description, $withArg = false ) {
		$this->mParams[$name] = [
			'desc' => $description,
			'withArg' => $withArg
		];
	}

	public function setup() {
		global $wgCommandLineMode;

		// Set a flag which can be used to detect when other scripts have been entered
		// through this entry point or not.
		define( 'MW_PHPUNIT_TEST', true );

		# Abort if called from a web server
		# wfIsCLI() is not available yet
		if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
			$this->fatalError( 'This script must be run from the command line' );
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

		$this->loadParamsAndArgs();

		# Disable the memory limit as it's not needed for tests.
		# Note we need to set it again later in cache LocalSettings changed it
		ini_set( 'memory_limit', -1 );

		# Set max execution time to 0 (no limit). PHP.net says that
		# "When running PHP from the command line the default setting is 0."
		# But sometimes this doesn't seem to be the case.
		ini_set( 'max_execution_time', 0 );

		$wgCommandLineMode = true;

		# Turn off output buffering if it's on
		while ( ob_get_level() > 0 ) {
			ob_end_flush();
		}

		require_once __DIR__ . '/../common/TestSetup.php';
		TestSetup::snapshotGlobals();
	}

	/**
	 * Checks to see if a particular option exists.
	 * @param string $name The name of the option
	 * @return bool
	 */
	private function hasOption( $name ) {
		return isset( $this->mOptions[$name] );
	}

	/**
	 * Get an option, or return the default.
	 *
	 * If the option was added to support multiple occurrences,
	 * this will return an array.
	 *
	 * @param string $name The name of the param
	 * @param mixed|null $default Anything you want, default null
	 * @return mixed
	 */
	private function getOption( $name, $default = null ) {
		if ( $this->hasOption( $name ) ) {
			return $this->mOptions[$name];
		} else {
			// Set it so we don't have to provide the default again
			$this->mOptions[$name] = $default;

			return $this->mOptions[$name];
		}
	}

	/**
	 * Process command line arguments
	 * $mOptions becomes an array with keys set to the option names
	 * $mArgs becomes a zero-based array containing the non-option arguments
	 */
	private function loadParamsAndArgs() {
		# If we've already loaded input (either by user values or from $argv)
		# skip on loading it again. The array_shift() will corrupt values if
		# it's run again and again
		if ( $this->mInputLoaded ) {
			$this->loadSpecialVars();
			return;
		}

		global $argv;
		$this->loadWithArgv( array_slice( $argv, 1 ) );
	}

	/**
	 * Load params and arguments from a given array
	 * of command-line arguments
	 *
	 * @param array $argv
	 */
	private function loadWithArgv( $argv ) {
		$options = [];

		# Parse arguments
		for ( $arg = reset( $argv ); $arg !== false; $arg = next( $argv ) ) {
			if ( $arg !== '--' && substr( $arg, 0, 2 ) == '--' ) {
				# Long options
				$option = substr( $arg, 2 );
				if ( isset( $this->mParams[$option] ) && $this->mParams[$option]['withArg'] ) {
					$param = next( $argv );
					if ( $param === false ) {
						echo "\nERROR: $option parameter needs a value after it\n";
						$this->maybeHelp( true );
					}

					$this->setParam( $options, $option, $param );
				} else {
					$bits = explode( '=', $option, 2 );
					$this->setParam( $options, $bits[0], $bits[1] ?? 1 );
				}
			} elseif ( $arg !== '-' && substr( $arg, 0, 1 ) == '-' ) {
				# Short options
				$argLength = strlen( $arg );
				for ( $p = 1; $p < $argLength; $p++ ) {
					$option = $arg[$p];
					if ( isset( $this->mParams[$option]['withArg'] ) && $this->mParams[$option]['withArg'] ) {
						$param = next( $argv );
						if ( $param === false ) {
							echo "\nERROR: $option parameter needs a value after it\n";
							$this->maybeHelp( true );
						}
						$this->setParam( $options, $option, $param );
					} else {
						$this->setParam( $options, $option, 1 );
					}
				}
			}
		}

		$this->mOptions = $options;
		$this->loadSpecialVars();
		$this->mInputLoaded = true;
	}

	/**
	 * Maybe show the help. If the help is shown, exit.
	 *
	 * @param bool $force Whether to force the help to show, default false
	 */
	public function maybeHelp( $force = false ) {
		if ( !$force && !$this->hasOption( 'help' ) ) {
			return;
		}
		$this->showHelp();
		die( 1 );
	}

	/**
	 * Helper function used solely by loadParamsAndArgs
	 * to prevent code duplication
	 *
	 * This sets the param in the options array based on
	 * whether or not it can be specified multiple times.
	 *
	 * @param array &$options
	 * @param string $option
	 * @param mixed $value
	 */
	private function setParam( &$options, $option, $value ) {
		$exists = array_key_exists( $option, $options );
		if ( !$exists ) {
			$options[$option] = $value;
		} else {
			echo "\nERROR: $option parameter given twice\n";
			$this->maybeHelp( true );
		}
	}

	private $mDbUser, $mDbPass;

	/**
	 * Handle the special variables that are global to all scripts
	 */
	private function loadSpecialVars() {
		if ( $this->hasOption( 'dbuser' ) ) {
			$this->mDbUser = $this->getOption( 'dbuser' );
		}
		if ( $this->hasOption( 'dbpass' ) ) {
			$this->mDbPass = $this->getOption( 'dbpass' );
		}
	}

	/**
	 * Output a message and terminate the current script.
	 *
	 * @param string $msg Error message
	 * @param int $exitCode PHP exit status. Should be in range 1-254.
	 */
	private function fatalError( $msg, $exitCode = 1 ) {
		echo $msg;
		exit( $exitCode );
	}

	public function finalSetup() {
		global $wgCommandLineMode, $wgShowExceptionDetails, $wgShowHostnames;
		global $wgDBadminuser, $wgDBadminpassword, $wgDBDefaultGroup;
		global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

		# Turn off output buffering again, it might have been turned on in the settings files
		if ( ob_get_level() ) {
			ob_end_flush();
		}
		# Same with these
		$wgCommandLineMode = true;

		# If these were passed, use them
		if ( $this->mDbUser ) {
			$wgDBadminuser = $this->mDbUser;
		}
		if ( $this->mDbPass ) {
			$wgDBadminpassword = $this->mDbPass;
		}
		if ( $this->hasOption( 'dbgroupdefault' ) ) {
			$wgDBDefaultGroup = $this->getOption( 'dbgroupdefault', null );

			$service = MediaWikiServices::getInstance()->peekService( 'DBLoadBalancerFactory' );
			if ( $service ) {
				$service->destroy();
			}
		}

		if ( isset( $wgDBadminuser ) ) {
			$wgDBuser = $wgDBadminuser;
			$wgDBpassword = $wgDBadminpassword;

			if ( $wgDBservers ) {
				/**
				 * @var array $wgDBservers
				 */
				foreach ( $wgDBservers as $i => $server ) {
					$wgDBservers[$i]['user'] = $wgDBuser;
					$wgDBservers[$i]['password'] = $wgDBpassword;
				}
			}
			if ( isset( $wgLBFactoryConf['serverTemplate'] ) ) {
				$wgLBFactoryConf['serverTemplate']['user'] = $wgDBuser;
				$wgLBFactoryConf['serverTemplate']['password'] = $wgDBpassword;
			}
			$service = MediaWikiServices::getInstance()->peekService( 'DBLoadBalancerFactory' );
			if ( $service ) {
				$service->destroy();
			}
		}

		$wgShowExceptionDetails = true;
		$wgShowHostnames = true;

		Wikimedia\suppressWarnings();
		set_time_limit( 0 );
		Wikimedia\restoreWarnings();

		ini_set( 'memory_limit', -1 );

		require_once __DIR__ . '/../common/TestsAutoLoader.php';

		TestSetup::applyInitialConfig();

		ExtensionRegistry::getInstance()->setLoadTestClassesAndNamespaces( true );
	}

	public function execute() {
		// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
		// stays in tact.
		// Has to in execute() instead of finalSetup(), because finalSetup() runs before
		// Setup.php is included, which calls MWExceptionHandler::installHandle().
		restore_error_handler();

		$this->forceFormatServerArgv();

		if ( !class_exists( PHPUnit\Framework\TestCase::class ) ) {
			echo "PHPUnit not found. Please install it and other dev dependencies by
		running `composer install` in MediaWiki root directory.\n";
			exit( 1 );
		}

		// Start an output buffer to avoid headers being sent by constructors,
		// data providers, etc. (T206476)
		ob_start();

		fwrite( STDERR, 'Using PHP ' . PHP_VERSION . "\n" );

		foreach ( MediaWikiCliOptions::$additionalOptions as $option => $default ) {
			MediaWikiCliOptions::$additionalOptions[$option] = $this->getOption( $option );
		}

		$command = new MediaWikiPHPUnitCommand();
		$command->run( $_SERVER['argv'], true );
	}

	/**
	 * Force the format of elements in $_SERVER['argv']
	 *  - Split args such as "wiki=enwiki" into two separate arg elements "wiki" and "enwiki"
	 */
	private function forceFormatServerArgv() {
		$argv = [];
		for ( $key = 0; $key < count( $_SERVER['argv'] ); $key++ ) {
			$arg = $_SERVER['argv'][$key];

			if ( $key === 0 ) {
				$argv[0] = $arg;
				continue;
			}

			if ( preg_match( '/^--(.*)$/', $arg, $match ) ) {
				$opt = $match[1];
				$parts = explode( '=', $opt, 2 );
				$opt = $parts[0];

				// Avoid confusing PHPUnit with MediaWiki-specific parameters
				if ( isset( $this->mParams[$opt] ) ) {
					if ( $this->mParams[$opt]['withArg'] && !isset( $parts[1] ) ) {
						// skip the value after the option name as well
						$key++;
					}
					continue;
				}
			}

			$argv[] = $arg;
		}
		$_SERVER['argv'] = $argv;
	}

	private function showHelp() {
		$tab = "    ";
		$descWidth = 80 - ( 2 * strlen( $tab ) );

		ksort( $this->mParams );

		$output = "\nUsage: php tests/phpunit.php";

		$output .= " [--" . implode( "|--", array_keys( $this->mParams ) ) . "]";

		echo "$output\n\n";
		echo "MediaWiki specific parameters:\n";

		foreach ( $this->mParams as $name => $info ) {
			echo wordwrap(
					"$tab--$name: " . $info['desc'],
					$descWidth,
					"\n$tab$tab"
				) . "\n";
		}

		echo "\n";

		echo "PHPUnit options:\n\n";
		$command = new MediaWikiPHPUnitCommand();
		$command->publicShowHelp();
	}

	/**
	 * Generic setup for most installs. Returns the location of LocalSettings
	 * @return string
	 */
	public function loadSettings() {
		global $wgCommandLineMode, $IP;

		$settingsFile = "$IP/LocalSettings.php";
		if ( isset( $this->mOptions['wiki'] ) ) {
			$bits = explode( '-', $this->mOptions['wiki'], 2 );
			define( 'MW_DB', $bits[0] );
			define( 'MW_PREFIX', $bits[1] ?? '' );
		}

		if ( !is_readable( $settingsFile ) ) {
			$this->fatalError( "A copy of your installation's LocalSettings.php\n" .
				"must exist and be readable in the source directory." );
		}
		$wgCommandLineMode = true;

		return $settingsFile;
	}

	/**
	 * Set triggers like when to try to run deferred updates
	 */
	public function setAgentAndTriggers() {
		if ( function_exists( 'posix_getpwuid' ) ) {
			$agent = posix_getpwuid( posix_geteuid() )['name'];
		} else {
			$agent = 'sysadmin';
		}
		$agent .= '@' . wfHostname();

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		// Add a comment for easy SHOW PROCESSLIST interpretation
		$lbFactory->setAgentName(
			mb_strlen( $agent ) > 15 ? mb_substr( $agent, 0, 15 ) . '...' : $agent
		);
		self::setLBFactoryTriggers( $lbFactory );
	}

	/**
	 * @param LBFactory $lbFactory
	 */
	private static function setLBFactoryTriggers( LBFactory $lbFactory ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		$config = $services->getMainConfig();
		// Hook into period lag checks which often happen in long-running scripts
		$lbFactory->setWaitForReplicationListener(
			__METHOD__,
			static function () use ( $stats, $config ) {
				// Check config in case of JobRunner and unit tests
				if ( $config->get( 'CommandLineMode' ) ) {
					DeferredUpdates::tryOpportunisticExecute( 'run' );
				}
				// Try to periodically flush buffered metrics to avoid OOMs
				MediaWiki::emitBufferedStatsdData( $stats, $config );
			}
		);
		// Check for other windows to run them. A script may read or do a few writes
		// to the master but mostly be writing to something else, like a file store.
		$lbFactory->getMainLB()->setTransactionListener(
			__METHOD__,
			static function ( $trigger ) use ( $stats, $config ) {
				// Check config in case of JobRunner and unit tests
				if ( $config->get( 'CommandLineMode' ) && $trigger === IDatabase::TRIGGER_COMMIT ) {
					DeferredUpdates::tryOpportunisticExecute( 'run' );
				}
				// Try to periodically flush buffered metrics to avoid OOMs
				MediaWiki::emitBufferedStatsdData( $stats, $config );
			}
		);
	}
}

if ( defined( 'MEDIAWIKI' ) ) {
	exit( 'Wrong entry point?' );
}

define( 'MW_ENTRY_POINT', 'cli' );

if ( strval( getenv( 'MW_INSTALL_PATH' ) ) === '' ) {
	putenv( 'MW_INSTALL_PATH=' . realpath( __DIR__ . '/../..' ) );
}

// Define the MediaWiki entrypoint
define( 'MEDIAWIKI', true );

$IP = getenv( 'MW_INSTALL_PATH' );

$wrapper = new PHPUnitMaintClass();
$wrapper->setup();

// Define how settings are loaded (e.g. LocalSettings.php)
define( 'MW_CONFIG_FILE', $wrapper->loadSettings() );

function wfPHPUnitSetup() {
	// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
	global $wrapper;
	$wrapper->finalSetup();
}

define( 'MW_SETUP_CALLBACK', 'wfPHPUnitSetup' );

require_once "$IP/includes/Setup.php";

$wrapper->setAgentAndTriggers();
$wrapper->maybeHelp( false );
$wrapper->execute();
