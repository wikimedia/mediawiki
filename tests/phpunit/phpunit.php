#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

if ( defined( 'MEDIAWIKI' ) ) {
	exit( 'Wrong entry point?' );
}

define( 'MW_ENTRY_POINT', 'cli' );

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies.
require_once __DIR__ . '/../../includes/PHPVersionCheck.php';
wfEntryPointCheck( 'text' );

// Some extensions rely on MW_INSTALL_PATH to find core files to include. Setting it here helps them
// if they're included by a core script (like DatabaseUpdater) after Maintenance.php has already
// been run.
if ( strval( getenv( 'MW_INSTALL_PATH' ) ) === '' ) {
	putenv( 'MW_INSTALL_PATH=' . realpath( __DIR__ . '/../..' ) );
}

class PHPUnitMaintClass {
	/**
	 * Accessible via getConfig()
	 *
	 * @var Config|null
	 */
	private $config;
	/**
	 * Array of desired/allowed params
	 * @var array[]
	 * @phan-var array<string,array{desc:string,require:bool,withArg:string,shortName:string,multiOccurrence:bool}>
	 */
	private $mParams = [];
	/** @var array Mapping short parameters to long ones */
	private $mShortParamsMap = [];
	/**
	 * Generic options added by addDefaultParams()
	 * @var array[]
	 * @phan-var array<string,array{desc:string,require:bool,withArg:string,shortName:string,multiOccurrence:bool}>
	 */
	private $mGenericParameters = [];
	/**
	 * Generic options which might or not be supported by the script
	 * @var array[]
	 * @phan-var array<string,array{desc:string,require:bool,withArg:string,shortName:string,multiOccurrence:bool}>
	 */
	private $mDependentParameters = [];
	/** @var array This is the list of options that were actually passed */
	private $mOptions = [];
	/** @var bool Have we already loaded our user input? */
	private $mInputLoaded = false;

	/**
	 * Used to read the options in the order they were passed.
	 * Useful for option chaining (Ex. dumpBackup.php). It will
	 * be an empty array if the options are passed in through
	 * loadParamsAndArgs( $self, $opts, $args ).
	 *
	 * This is an array of arrays where
	 * 0 => the option and 1 => parameter value.
	 *
	 * @var array
	 */
	private $orderedOptions = [];

	public function __construct() {
		$this->addOption( 'help', 'Display this help message', false, false, 'h' );
		$this->addOption( 'wiki', 'For specifying the wiki ID', false, true );
		$this->mGenericParameters = $this->mParams;
		$this->addOption( 'dbuser', 'The DB user to use for this script', false, true );
		$this->addOption( 'dbpass', 'The password to use for this script', false, true );
		$this->addOption( 'dbgroupdefault', 'The default DB group to use.', false, true );
		$this->mDependentParameters = array_diff_key( $this->mParams, $this->mGenericParameters );
		register_shutdown_function( [ $this, 'outputChanneled' ], false );
		$this->addOption( 'use-filebackend', 'Use filebackend', false, true );
		$this->addOption( 'use-bagostuff', 'Use bagostuff', false, true );
		$this->addOption( 'use-jobqueue', 'Use jobqueue', false, true );
		$this->addOption( 'use-normal-tables', 'Use normal DB tables.', false, false );
		$this->addOption(
			'reuse-db', 'Init DB only if tables are missing and keep after finish.',
			false,
			false
		);
	}

	/**
	 * Add a parameter to the script. Will be displayed on --help
	 * with the associated description
	 *
	 * @param string $name The name of the param (help, version, etc)
	 * @param string $description The description of the param to show on --help
	 * @param bool $required Is the param required?
	 * @param bool $withArg Is an argument required with this option?
	 * @param string|bool $shortName Character to use as short name
	 * @param bool $multiOccurrence Can this option be passed multiple times?
	 */
	private function addOption( $name, $description, $required = false,
		$withArg = false, $shortName = false, $multiOccurrence = false
	) {
		$this->mParams[$name] = [
			'desc' => $description,
			'require' => $required,
			'withArg' => $withArg,
			'shortName' => $shortName,
			'multiOccurrence' => $multiOccurrence
		];

		if ( $shortName !== false ) {
			$this->mShortParamsMap[$shortName] = $name;
		}
	}

	public function setup() {
		// Set a flag which can be used to detect when other scripts have been entered
		// through this entry point or not.
		define( 'MW_PHPUNIT_TEST', true );

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

		$this->loadParamsAndArgs();

		# Set the memory limit
		# Note we need to set it again later in cache LocalSettings changed it
		$this->adjustMemoryLimit();

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
	 * @return-taint none
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
	 * Normally we disable the memory_limit when running admin scripts.
	 * Some scripts may wish to actually set a limit, however, to avoid
	 * blowing up unexpectedly. We also support a --memory-limit option,
	 * to allow sysadmins to explicitly set one if they'd prefer to override
	 * defaults (or for people using Suhosin which yells at you for trying
	 * to disable the limits)
	 * @stable for overriding
	 * @return string
	 */
	private function memoryLimit() {
		$limit = $this->getOption( 'memory-limit', 'max' );
		$limit = trim( $limit, "\" '" ); // trim quotes in case someone misunderstood
		return $limit;
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
	 * @since 1.27
	 * @param array $argv
	 */
	private function loadWithArgv( $argv ) {
		$options = [];
		$this->orderedOptions = [];

		# Parse arguments
		for ( $arg = reset( $argv ); $arg !== false; $arg = next( $argv ) ) {
			if ( $arg !== '--' && substr( $arg, 0, 2 ) == '--' ) {
				# Long options
				$option = substr( $arg, 2 );
				if ( isset( $this->mParams[$option] ) && $this->mParams[$option]['withArg'] ) {
					$param = next( $argv );
					if ( $param === false ) {
						$this->error( "\nERROR: $option parameter needs a value after it\n" );
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
					if ( !isset( $this->mParams[$option] ) && isset( $this->mShortParamsMap[$option] ) ) {
						$option = $this->mShortParamsMap[$option];
					}

					if ( isset( $this->mParams[$option]['withArg'] ) && $this->mParams[$option]['withArg'] ) {
						$param = next( $argv );
						if ( $param === false ) {
							$this->error( "\nERROR: $option parameter needs a value after it\n" );
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
	private function maybeHelp( $force = false ) {
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
	 * @since 1.27
	 * @param array &$options
	 * @param string $option
	 * @param mixed $value
	 */
	private function setParam( &$options, $option, $value ) {
		$this->orderedOptions[] = [ $option, $value ];

		if ( isset( $this->mParams[$option] ) ) {
			$multi = $this->mParams[$option]['multiOccurrence'];
		} else {
			$multi = false;
		}
		$exists = array_key_exists( $option, $options );
		if ( $multi && $exists ) {
			$options[$option][] = $value;
		} elseif ( $multi ) {
			$options[$option] = [ $value ];
		} elseif ( !$exists ) {
			$options[$option] = $value;
		} else {
			$this->error( "\nERROR: $option parameter given twice\n" );
			$this->maybeHelp( true );
		}
	}

	private $mDbUser, $mDbPass;

	/**
	 * Handle the special variables that are global to all scripts
	 * @stable for overriding
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
	 * @stable for overriding
	 * @param string $msg Error message
	 * @param int $exitCode PHP exit status. Should be in range 1-254.
	 * @since 1.31
	 */
	private function fatalError( $msg, $exitCode = 1 ) {
		$this->error( $msg );
		exit( $exitCode );
	}

	/**
	 * Throw an error to the user. Doesn't respect --quiet, so don't use
	 * this for non-error output
	 * @stable for overriding
	 * @param string $err The error to display
	 */
	private function error( $err ) {
		$this->outputChanneled( false );
		print $err;
	}

	private $atLineStart = true;
	private $lastChannel = null;

	/**
	 * Clean up channeled output.  Output a newline if necessary.
	 */
	private function cleanupChanneled() {
		if ( !$this->atLineStart ) {
			print "\n";
			$this->atLineStart = true;
		}
	}

	/**
	 * Message outputter with channeled message support. Messages on the
	 * same channel are concatenated, but any intervening messages in another
	 * channel start a new line.
	 * @param string $msg The message without trailing newline
	 * @param string|null $channel Channel identifier or null for no
	 *     channel. Channel comparison uses ===.
	 */
	public function outputChanneled( $msg, $channel = null ) {
		if ( $msg === false ) {
			$this->cleanupChanneled();

			return;
		}

		// End the current line if necessary
		if ( !$this->atLineStart && $channel !== $this->lastChannel ) {
			print "\n";
		}

		print $msg;

		$this->atLineStart = false;
		if ( $channel === null ) {
			// For unchanneled messages, output trailing newline immediately
			print "\n";
			$this->atLineStart = true;
		}
		$this->lastChannel = $channel;
	}

	public function finalSetup() {
		global $wgCommandLineMode, $wgServer, $wgShowExceptionDetails, $wgShowHostnames;
		global $wgDBadminuser, $wgDBadminpassword, $wgDBDefaultGroup;
		global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

		# Turn off output buffering again, it might have been turned on in the settings files
		if ( ob_get_level() ) {
			ob_end_flush();
		}
		# Same with these
		$wgCommandLineMode = true;

		# Override $wgServer
		if ( $this->hasOption( 'server' ) ) {
			$wgServer = $this->getOption( 'server', $wgServer );
		}

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

		$this->adjustMemoryLimit();

		require_once __DIR__ . '/../common/TestsAutoLoader.php';

		TestSetup::applyInitialConfig();

		ExtensionRegistry::getInstance()->setLoadTestClassesAndNamespaces( true );
	}

	public function execute() {
		// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
		// stays in tact.
		// Has to in execute() instead of finalSetup(), because finalSetup() runs before
		// doMaintenance.php includes Setup.php, which calls MWExceptionHandler::installHandle().
		restore_error_handler();

		$this->forceFormatServerArgv();

		if ( !class_exists( 'PHPUnit\\Framework\\TestCase' ) ) {
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

	/**
	 * Get the terminal size as a two-element array where the first element
	 * is the width (number of columns) and the second element is the height
	 * (number of rows).
	 *
	 * @return array
	 */
	private static function getTermSize() {
		static $termSize = null;

		if ( $termSize !== null ) {
			return $termSize;
		}

		$default = [ 80, 50 ];

		if ( wfIsWindows() || Shell::isDisabled() ) {
			$termSize = $default;

			return $termSize;
		}

		// It's possible to get the screen size with VT-100 terminal escapes,
		// but reading the responses is not possible without setting raw mode
		// (unless you want to require the user to press enter), and that
		// requires an ioctl(), which we can't do. So we have to shell out to
		// something that can do the relevant syscalls. There are a few
		// options. Linux and Mac OS X both have "stty size" which does the
		// job directly.
		$result = Shell::command( 'stty', 'size' )->passStdin()->execute();
		if ( $result->getExitCode() !== 0 ||
			!preg_match( '/^(\d+) (\d+)$/', $result->getStdout(), $m )
		) {
			$termSize = $default;

			return $termSize;
		}

		$termSize = [ intval( $m[2] ), intval( $m[1] ) ];

		return $termSize;
	}

	/**
	 * @since 1.24
	 * @stable for overriding
	 * @return Config
	 */
	private function getConfig() {
		if ( $this->config === null ) {
			$this->config = MediaWikiServices::getInstance()->getMainConfig();
		}

		return $this->config;
	}

	/**
	 * Throw some output to the user. Scripts can call this with no fears,
	 * as we handle all --quiet stuff here
	 * @stable for overriding
	 * @param string $out The text to show to the user
	 * @param mixed|null $channel Unique identifier for the channel. See function outputChanneled.
	 */
	private function output( $out, $channel = null ) {
		// This is sometimes called very early, before Setup.php is included.
		if ( class_exists( MediaWikiServices::class ) ) {
			// Try to periodically flush buffered metrics to avoid OOMs
			$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
			if ( $stats->getDataCount() > 1000 ) {
				MediaWiki::emitBufferedStatsdData( $stats, $this->getConfig() );
			}
		}

		if ( $channel === null ) {
			$this->cleanupChanneled();
			print $out;
		} else {
			$out = preg_replace( '/\n\z/', '', $out );
			$this->outputChanneled( $out, $channel );
		}
	}

	private function showHelp() {
		$screenWidth = self::getTermSize()[0];
		$tab = "    ";
		$descWidth = $screenWidth - ( 2 * strlen( $tab ) );

		ksort( $this->mParams );

		$output = "\nUsage: php tests/phpunit.php";

		// ... append parameters ...
		if ( $this->mParams ) {
			$output .= " [--" . implode( "|--", array_keys( $this->mParams ) ) . "]";
		}

		$this->output( "$output\n\n" );

		$this->formatHelpItems(
			$this->mGenericParameters,
			'Generic maintenance parameters',
			$descWidth, $tab
		);

		$this->formatHelpItems(
			$this->mDependentParameters,
			'Script dependent parameters',
			$descWidth, $tab
		);

		// Script-specific parameters not defined on construction by
		// Maintenance::addDefaultParams()
		$scriptSpecificParams = array_diff_key(
		# all script parameters:
			$this->mParams,
			# remove the Maintenance default parameters:
			$this->mGenericParameters,
			$this->mDependentParameters
		);

		$this->formatHelpItems(
			$scriptSpecificParams,
			'Script specific parameters',
			$descWidth, $tab
		);

		$this->output( "PHPUnit options are also accepted:\n\n" );
		$command = new MediaWikiPHPUnitCommand();
		$command->publicShowHelp();
	}

	private function formatHelpItems( array $items, $heading, $descWidth, $tab ) {
		if ( $items === [] ) {
			return;
		}

		$this->output( "$heading:\n" );

		foreach ( $items as $name => $info ) {
			if ( $info['shortName'] !== false ) {
				$name .= ' (-' . $info['shortName'] . ')';
			}
			$this->output(
				wordwrap(
					"$tab--$name: " . $info['desc'],
					$descWidth,
					"\n$tab$tab"
				) . "\n"
			);
		}

		$this->output( "\n" );
	}

	/**
	 * Generic setup for most installs. Returns the location of LocalSettings
	 * @return string
	 */
	public function loadSettings() {
		global $wgCommandLineMode, $IP;

		if ( defined( "MW_CONFIG_FILE" ) ) {
			$settingsFile = MW_CONFIG_FILE;
		} else {
			$settingsFile = "$IP/LocalSettings.php";
		}
		if ( isset( $this->mOptions['wiki'] ) ) {
			$bits = explode( '-', $this->mOptions['wiki'], 2 );
			define( 'MW_DB', $bits[0] );
			define( 'MW_PREFIX', $bits[1] ?? '' );
		}

		if ( !is_readable( $settingsFile ) ) {
			$this->fatalError( "A copy of your installation's LocalSettings.php\n" .
				"must exist and be readable in the source directory.\n" .
				"Use --conf to specify it." );
		}
		$wgCommandLineMode = true;

		return $settingsFile;
	}

	/**
	 * @since 1.24
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Set triggers like when to try to run deferred updates
	 * @since 1.28
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
		self::setLBFactoryTriggers( $lbFactory, $this->getConfig() );
	}

	/**
	 * @param LBFactory $lbFactory
	 * @param Config $config
	 * @since 1.28
	 */
	private static function setLBFactoryTriggers( LBFactory $lbFactory, Config $config ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
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

	/**
	 * Run some validation checks on the params, etc
	 * @stable for overriding
	 */
	public function validateParamsAndArgs() {
		$die = false;
		# Check to make sure we've got all the required options
		foreach ( $this->mParams as $opt => $info ) {
			if ( $info['require'] && !$this->hasOption( $opt ) ) {
				$this->error( "Param $opt required!" );
				$die = true;
			}
		}

		$this->maybeHelp( $die );
	}
}

// Define the MediaWiki entrypoint
define( 'MEDIAWIKI', true );

// This environment variable is ensured present by Maintenance.php.
$IP = getenv( 'MW_INSTALL_PATH' );

// Get an object to start us off
$maintenance = new PHPUnitMaintClass();

// Basic sanity checks and such
$maintenance->setup();

// Define how settings are loaded (e.g. LocalSettings.php)
if ( !defined( 'MW_CONFIG_FILE' ) ) {
	define( 'MW_CONFIG_FILE', $maintenance->loadSettings() );
}

function wfMaintenanceSetup() {
	// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
	global $maintenance;
	$maintenance->finalSetup();
}

define( 'MW_SETUP_CALLBACK', 'wfMaintenanceSetup' );

require_once "$IP/includes/Setup.php";

$maintenance->setConfig( MediaWikiServices::getInstance()->getMainConfig() );
$maintenance->setAgentAndTriggers();
$maintenance->validateParamsAndArgs();
$maintenance->execute();
