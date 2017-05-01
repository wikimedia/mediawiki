<?php
/**
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
 * @defgroup Maintenance Maintenance
 */

// Bail on old versions of PHP, or if composer has not been run yet to install
// dependencies.
require_once __DIR__ . '/../includes/PHPVersionCheck.php';
wfEntryPointCheck( 'cli' );

/**
 * @defgroup MaintenanceArchive Maintenance archives
 * @ingroup Maintenance
 */

// Define this so scripts can easily find doMaintenance.php
define( 'RUN_MAINTENANCE_IF_MAIN', __DIR__ . '/doMaintenance.php' );
define( 'DO_MAINTENANCE', RUN_MAINTENANCE_IF_MAIN ); // original name, harmless

$maintClass = false;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Abstract maintenance class for quickly writing and churning out
 * maintenance scripts with minimal effort. All that _must_ be defined
 * is the execute() method. See docs/maintenance.txt for more info
 * and a quick demo of how to use it.
 *
 * @author Chad Horohoe <chad@anyonecanedit.org>
 * @since 1.16
 * @ingroup Maintenance
 */
abstract class Maintenance {
	/**
	 * Constants for DB access type
	 * @see Maintenance::getDbType()
	 */
	const DB_NONE = 0;
	const DB_STD = 1;
	const DB_ADMIN = 2;

	// Const for getStdin()
	const STDIN_ALL = 'all';

	// This is the desired params
	protected $mParams = [];

	// Array of mapping short parameters to long ones
	protected $mShortParamsMap = [];

	// Array of desired args
	protected $mArgList = [];

	// This is the list of options that were actually passed
	protected $mOptions = [];

	// This is the list of arguments that were actually passed
	protected $mArgs = [];

	// Name of the script currently running
	protected $mSelf;

	// Special vars for params that are always used
	protected $mQuiet = false;
	protected $mDbUser, $mDbPass;

	// A description of the script, children should change this via addDescription()
	protected $mDescription = '';

	// Have we already loaded our user input?
	protected $mInputLoaded = false;

	/**
	 * Batch size. If a script supports this, they should set
	 * a default with setBatchSize()
	 *
	 * @var int
	 */
	protected $mBatchSize = null;

	// Generic options added by addDefaultParams()
	private $mGenericParameters = [];
	// Generic options which might or not be supported by the script
	private $mDependantParameters = [];

	/**
	 * Used by getDB() / setDB()
	 * @var Database
	 */
	private $mDb = null;

	/** @var float UNIX timestamp */
	private $lastReplicationWait = 0.0;

	/**
	 * Used when creating separate schema files.
	 * @var resource
	 */
	public $fileHandle;

	/**
	 * Accessible via getConfig()
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * @see Maintenance::requireExtension
	 * @var array
	 */
	private $requiredExtensions = [];

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
	public $orderedOptions = [];

	/**
	 * Default constructor. Children should call this *first* if implementing
	 * their own constructors
	 */
	public function __construct() {
		// Setup $IP, using MW_INSTALL_PATH if it exists
		global $IP;
		$IP = strval( getenv( 'MW_INSTALL_PATH' ) ) !== ''
			? getenv( 'MW_INSTALL_PATH' )
			: realpath( __DIR__ . '/..' );

		$this->addDefaultParams();
		register_shutdown_function( [ $this, 'outputChanneled' ], false );
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
			return false; // sanity
		}
		if ( $bt[0]['class'] !== 'Maintenance' || $bt[0]['function'] !== 'shouldExecute' ) {
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
	 * Do the actual work. All child classes will need to implement this
	 */
	abstract public function execute();

	/**
	 * Add a parameter to the script. Will be displayed on --help
	 * with the associated description
	 *
	 * @param string $name The name of the param (help, version, etc)
	 * @param string $description The description of the param to show on --help
	 * @param bool $required Is the param required?
	 * @param bool $withArg Is an argument required with this option?
	 * @param string $shortName Character to use as short name
	 * @param bool $multiOccurrence Can this option be passed multiple times?
	 */
	protected function addOption( $name, $description, $required = false,
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

	/**
	 * Checks to see if a particular param exists.
	 * @param string $name The name of the param
	 * @return bool
	 */
	protected function hasOption( $name ) {
		return isset( $this->mOptions[$name] );
	}

	/**
	 * Get an option, or return the default.
	 *
	 * If the option was added to support multiple occurrences,
	 * this will return an array.
	 *
	 * @param string $name The name of the param
	 * @param mixed $default Anything you want, default null
	 * @return mixed
	 */
	protected function getOption( $name, $default = null ) {
		if ( $this->hasOption( $name ) ) {
			return $this->mOptions[$name];
		} else {
			// Set it so we don't have to provide the default again
			$this->mOptions[$name] = $default;

			return $this->mOptions[$name];
		}
	}

	/**
	 * Add some args that are needed
	 * @param string $arg Name of the arg, like 'start'
	 * @param string $description Short description of the arg
	 * @param bool $required Is this required?
	 */
	protected function addArg( $arg, $description, $required = true ) {
		$this->mArgList[] = [
			'name' => $arg,
			'desc' => $description,
			'require' => $required
		];
	}

	/**
	 * Remove an option.  Useful for removing options that won't be used in your script.
	 * @param string $name The option to remove.
	 */
	protected function deleteOption( $name ) {
		unset( $this->mParams[$name] );
	}

	/**
	 * Set the description text.
	 * @param string $text The text of the description
	 */
	protected function addDescription( $text ) {
		$this->mDescription = $text;
	}

	/**
	 * Does a given argument exist?
	 * @param int $argId The integer value (from zero) for the arg
	 * @return bool
	 */
	protected function hasArg( $argId = 0 ) {
		return isset( $this->mArgs[$argId] );
	}

	/**
	 * Get an argument.
	 * @param int $argId The integer value (from zero) for the arg
	 * @param mixed $default The default if it doesn't exist
	 * @return mixed
	 */
	protected function getArg( $argId = 0, $default = null ) {
		return $this->hasArg( $argId ) ? $this->mArgs[$argId] : $default;
	}

	/**
	 * Set the batch size.
	 * @param int $s The number of operations to do in a batch
	 */
	protected function setBatchSize( $s = 0 ) {
		$this->mBatchSize = $s;

		// If we support $mBatchSize, show the option.
		// Used to be in addDefaultParams, but in order for that to
		// work, subclasses would have to call this function in the constructor
		// before they called parent::__construct which is just weird
		// (and really wasn't done).
		if ( $this->mBatchSize ) {
			$this->addOption( 'batch-size', 'Run this many operations ' .
				'per batch, default: ' . $this->mBatchSize, false, true );
			if ( isset( $this->mParams['batch-size'] ) ) {
				// This seems a little ugly...
				$this->mDependantParameters['batch-size'] = $this->mParams['batch-size'];
			}
		}
	}

	/**
	 * Get the script's name
	 * @return string
	 */
	public function getName() {
		return $this->mSelf;
	}

	/**
	 * Return input from stdin.
	 * @param int $len The number of bytes to read. If null, just return the handle.
	 *   Maintenance::STDIN_ALL returns the full length
	 * @return mixed
	 */
	protected function getStdin( $len = null ) {
		if ( $len == Maintenance::STDIN_ALL ) {
			return file_get_contents( 'php://stdin' );
		}
		$f = fopen( 'php://stdin', 'rt' );
		if ( !$len ) {
			return $f;
		}
		$input = fgets( $f, $len );
		fclose( $f );

		return rtrim( $input );
	}

	/**
	 * @return bool
	 */
	public function isQuiet() {
		return $this->mQuiet;
	}

	/**
	 * Throw some output to the user. Scripts can call this with no fears,
	 * as we handle all --quiet stuff here
	 * @param string $out The text to show to the user
	 * @param mixed $channel Unique identifier for the channel. See function outputChanneled.
	 */
	protected function output( $out, $channel = null ) {
		if ( $this->mQuiet ) {
			return;
		}
		if ( $channel === null ) {
			$this->cleanupChanneled();
			print $out;
		} else {
			$out = preg_replace( '/\n\z/', '', $out );
			$this->outputChanneled( $out, $channel );
		}
	}

	/**
	 * Throw an error to the user. Doesn't respect --quiet, so don't use
	 * this for non-error output
	 * @param string $err The error to display
	 * @param int $die If > 0, go ahead and die out using this int as the code
	 */
	protected function error( $err, $die = 0 ) {
		$this->outputChanneled( false );
		if ( PHP_SAPI == 'cli' ) {
			fwrite( STDERR, $err . "\n" );
		} else {
			print $err;
		}
		$die = intval( $die );
		if ( $die > 0 ) {
			die( $die );
		}
	}

	private $atLineStart = true;
	private $lastChannel = null;

	/**
	 * Clean up channeled output.  Output a newline if necessary.
	 */
	public function cleanupChanneled() {
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
	 * @param string $channel Channel identifier or null for no
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

	/**
	 * Does the script need different DB access? By default, we give Maintenance
	 * scripts normal rights to the DB. Sometimes, a script needs admin rights
	 * access for a reason and sometimes they want no access. Subclasses should
	 * override and return one of the following values, as needed:
	 *    Maintenance::DB_NONE  -  For no DB access at all
	 *    Maintenance::DB_STD   -  For normal DB access, default
	 *    Maintenance::DB_ADMIN -  For admin DB access
	 * @return int
	 */
	public function getDbType() {
		return Maintenance::DB_STD;
	}

	/**
	 * Add the default parameters to the scripts
	 */
	protected function addDefaultParams() {

		# Generic (non script dependant) options:

		$this->addOption( 'help', 'Display this help message', false, false, 'h' );
		$this->addOption( 'quiet', 'Whether to supress non-error output', false, false, 'q' );
		$this->addOption( 'conf', 'Location of LocalSettings.php, if not default', false, true );
		$this->addOption( 'wiki', 'For specifying the wiki ID', false, true );
		$this->addOption( 'globals', 'Output globals at the end of processing for debugging' );
		$this->addOption(
			'memory-limit',
			'Set a specific memory limit for the script, '
				. '"max" for no limit or "default" to avoid changing it'
		);
		$this->addOption( 'server', "The protocol and server name to use in URLs, e.g. " .
			"http://en.wikipedia.org. This is sometimes necessary because " .
			"server name detection may fail in command line scripts.", false, true );
		$this->addOption( 'profiler', 'Profiler output format (usually "text")', false, true );

		# Save generic options to display them separately in help
		$this->mGenericParameters = $this->mParams;

		# Script dependant options:

		// If we support a DB, show the options
		if ( $this->getDbType() > 0 ) {
			$this->addOption( 'dbuser', 'The DB user to use for this script', false, true );
			$this->addOption( 'dbpass', 'The password to use for this script', false, true );
		}

		# Save additional script dependant options to display
		#  them separately in help
		$this->mDependantParameters = array_diff_key( $this->mParams, $this->mGenericParameters );
	}

	/**
	 * @since 1.24
	 * @return Config
	 */
	public function getConfig() {
		if ( $this->config === null ) {
			$this->config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}

		return $this->config;
	}

	/**
	 * @since 1.24
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		$this->config = $config;
	}

	/**
	 * Indicate that the specified extension must be
	 * loaded before the script can run.
	 *
	 * This *must* be called in the constructor.
	 *
	 * @since 1.28
	 * @param string $name
	 */
	protected function requireExtension( $name ) {
		$this->requiredExtensions[] = $name;
	}

	/**
	 * Verify that the required extensions are installed
	 *
	 * @since 1.28
	 */
	public function checkRequiredExtensions() {
		$registry = ExtensionRegistry::getInstance();
		$missing = [];
		foreach ( $this->requiredExtensions as $name ) {
			if ( !$registry->isLoaded( $name ) ) {
				$missing[] = $name;
			}
		}

		if ( $missing ) {
			$joined = implode( ', ', $missing );
			$msg = "The following extensions are required to be installed "
				. "for this script to run: $joined. Please enable them and then try again.";
			$this->error( $msg, 1 );
		}

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
		self::setLBFactoryTriggers( $lbFactory );
	}

	/**
	 * @param LBFactory $LBFactory
	 * @since 1.28
	 */
	public static function setLBFactoryTriggers( LBFactory $LBFactory ) {
		// Hook into period lag checks which often happen in long-running scripts
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->setWaitForReplicationListener(
			__METHOD__,
			function () {
				global $wgCommandLineMode;
				// Check config in case of JobRunner and unit tests
				if ( $wgCommandLineMode ) {
					DeferredUpdates::tryOpportunisticExecute( 'run' );
				}
			}
		);
		// Check for other windows to run them. A script may read or do a few writes
		// to the master but mostly be writing to something else, like a file store.
		$lbFactory->getMainLB()->setTransactionListener(
			__METHOD__,
			function ( $trigger ) {
				global $wgCommandLineMode;
				// Check config in case of JobRunner and unit tests
				if ( $wgCommandLineMode && $trigger === IDatabase::TRIGGER_COMMIT ) {
					DeferredUpdates::tryOpportunisticExecute( 'run' );
				}
			}
		);
	}

	/**
	 * Run a child maintenance script. Pass all of the current arguments
	 * to it.
	 * @param string $maintClass A name of a child maintenance class
	 * @param string $classFile Full path of where the child is
	 * @return Maintenance
	 */
	public function runChild( $maintClass, $classFile = null ) {
		// Make sure the class is loaded first
		if ( !class_exists( $maintClass ) ) {
			if ( $classFile ) {
				require_once $classFile;
			}
			if ( !class_exists( $maintClass ) ) {
				$this->error( "Cannot spawn child: $maintClass" );
			}
		}

		/**
		 * @var $child Maintenance
		 */
		$child = new $maintClass();
		$child->loadParamsAndArgs( $this->mSelf, $this->mOptions, $this->mArgs );
		if ( !is_null( $this->mDb ) ) {
			$child->setDB( $this->mDb );
		}

		return $child;
	}

	/**
	 * Do some sanity checking and basic setup
	 */
	public function setup() {
		global $IP, $wgCommandLineMode, $wgRequestTime;

		# Abort if called from a web server
		if ( isset( $_SERVER ) && isset( $_SERVER['REQUEST_METHOD'] ) ) {
			$this->error( 'This script must be run from the command line', true );
		}

		if ( $IP === null ) {
			$this->error( "\$IP not set, aborting!\n" .
				'(Did you forget to call parent::__construct() in your maintenance script?)', 1 );
		}

		# Make sure we can handle script parameters
		if ( !defined( 'HPHP_VERSION' ) && !ini_get( 'register_argc_argv' ) ) {
			$this->error( 'Cannot get command line arguments, register_argc_argv is set to false', true );
		}

		// Send PHP warnings and errors to stderr instead of stdout.
		// This aids in diagnosing problems, while keeping messages
		// out of redirected output.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		$this->loadParamsAndArgs();
		$this->maybeHelp();

		# Set the memory limit
		# Note we need to set it again later in cache LocalSettings changed it
		$this->adjustMemoryLimit();

		# Set max execution time to 0 (no limit). PHP.net says that
		# "When running PHP from the command line the default setting is 0."
		# But sometimes this doesn't seem to be the case.
		ini_set( 'max_execution_time', 0 );

		$wgRequestTime = microtime( true );

		# Define us as being in MediaWiki
		define( 'MEDIAWIKI', true );

		$wgCommandLineMode = true;

		# Turn off output buffering if it's on
		while ( ob_get_level() > 0 ) {
			ob_end_flush();
		}

		$this->validateParamsAndArgs();
	}

	/**
	 * Normally we disable the memory_limit when running admin scripts.
	 * Some scripts may wish to actually set a limit, however, to avoid
	 * blowing up unexpectedly. We also support a --memory-limit option,
	 * to allow sysadmins to explicitly set one if they'd prefer to override
	 * defaults (or for people using Suhosin which yells at you for trying
	 * to disable the limits)
	 * @return string
	 */
	public function memoryLimit() {
		$limit = $this->getOption( 'memory-limit', 'max' );
		$limit = trim( $limit, "\" '" ); // trim quotes in case someone misunderstood
		return $limit;
	}

	/**
	 * Adjusts PHP's memory limit to better suit our needs, if needed.
	 */
	protected function adjustMemoryLimit() {
		$limit = $this->memoryLimit();
		if ( $limit == 'max' ) {
			$limit = -1; // no memory limit
		}
		if ( $limit != 'default' ) {
			ini_set( 'memory_limit', $limit );
		}
	}

	/**
	 * Activate the profiler (assuming $wgProfiler is set)
	 */
	protected function activateProfiler() {
		global $wgProfiler, $wgProfileLimit, $wgTrxProfilerLimits;

		$output = $this->getOption( 'profiler' );
		if ( !$output ) {
			return;
		}

		if ( is_array( $wgProfiler ) && isset( $wgProfiler['class'] ) ) {
			$class = $wgProfiler['class'];
			/** @var Profiler $profiler */
			$profiler = new $class(
				[ 'sampling' => 1, 'output' => [ $output ] ]
					+ $wgProfiler
					+ [ 'threshold' => $wgProfileLimit ]
			);
			$profiler->setTemplated( true );
			Profiler::replaceStubInstance( $profiler );
		}

		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->setLogger( LoggerFactory::getInstance( 'DBPerformance' ) );
		$trxProfiler->setExpectations( $wgTrxProfilerLimits['Maintenance'], __METHOD__ );
	}

	/**
	 * Clear all params and arguments.
	 */
	public function clearParamsAndArgs() {
		$this->mOptions = [];
		$this->mArgs = [];
		$this->mInputLoaded = false;
	}

	/**
	 * Load params and arguments from a given array
	 * of command-line arguments
	 *
	 * @since 1.27
	 * @param array $argv
	 */
	public function loadWithArgv( $argv ) {
		$options = [];
		$args = [];
		$this->orderedOptions = [];

		# Parse arguments
		for ( $arg = reset( $argv ); $arg !== false; $arg = next( $argv ) ) {
			if ( $arg == '--' ) {
				# End of options, remainder should be considered arguments
				$arg = next( $argv );
				while ( $arg !== false ) {
					$args[] = $arg;
					$arg = next( $argv );
				}
				break;
			} elseif ( substr( $arg, 0, 2 ) == '--' ) {
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
					if ( count( $bits ) > 1 ) {
						$option = $bits[0];
						$param = $bits[1];
					} else {
						$param = 1;
					}

					$this->setParam( $options, $option, $param );
				}
			} elseif ( $arg == '-' ) {
				# Lonely "-", often used to indicate stdin or stdout.
				$args[] = $arg;
			} elseif ( substr( $arg, 0, 1 ) == '-' ) {
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
			} else {
				$args[] = $arg;
			}
		}

		$this->mOptions = $options;
		$this->mArgs = $args;
		$this->loadSpecialVars();
		$this->mInputLoaded = true;
	}

	/**
	 * Helper function used solely by loadParamsAndArgs
	 * to prevent code duplication
	 *
	 * This sets the param in the options array based on
	 * whether or not it can be specified multiple times.
	 *
	 * @since 1.27
	 * @param array $options
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

	/**
	 * Process command line arguments
	 * $mOptions becomes an array with keys set to the option names
	 * $mArgs becomes a zero-based array containing the non-option arguments
	 *
	 * @param string $self The name of the script, if any
	 * @param array $opts An array of options, in form of key=>value
	 * @param array $args An array of command line arguments
	 */
	public function loadParamsAndArgs( $self = null, $opts = null, $args = null ) {
		# If we were given opts or args, set those and return early
		if ( $self ) {
			$this->mSelf = $self;
			$this->mInputLoaded = true;
		}
		if ( $opts ) {
			$this->mOptions = $opts;
			$this->mInputLoaded = true;
		}
		if ( $args ) {
			$this->mArgs = $args;
			$this->mInputLoaded = true;
		}

		# If we've already loaded input (either by user values or from $argv)
		# skip on loading it again. The array_shift() will corrupt values if
		# it's run again and again
		if ( $this->mInputLoaded ) {
			$this->loadSpecialVars();

			return;
		}

		global $argv;
		$this->mSelf = $argv[0];
		$this->loadWithArgv( array_slice( $argv, 1 ) );
	}

	/**
	 * Run some validation checks on the params, etc
	 */
	protected function validateParamsAndArgs() {
		$die = false;
		# Check to make sure we've got all the required options
		foreach ( $this->mParams as $opt => $info ) {
			if ( $info['require'] && !$this->hasOption( $opt ) ) {
				$this->error( "Param $opt required!" );
				$die = true;
			}
		}
		# Check arg list too
		foreach ( $this->mArgList as $k => $info ) {
			if ( $info['require'] && !$this->hasArg( $k ) ) {
				$this->error( 'Argument <' . $info['name'] . '> required!' );
				$die = true;
			}
		}

		if ( $die ) {
			$this->maybeHelp( true );
		}
	}

	/**
	 * Handle the special variables that are global to all scripts
	 */
	protected function loadSpecialVars() {
		if ( $this->hasOption( 'dbuser' ) ) {
			$this->mDbUser = $this->getOption( 'dbuser' );
		}
		if ( $this->hasOption( 'dbpass' ) ) {
			$this->mDbPass = $this->getOption( 'dbpass' );
		}
		if ( $this->hasOption( 'quiet' ) ) {
			$this->mQuiet = true;
		}
		if ( $this->hasOption( 'batch-size' ) ) {
			$this->mBatchSize = intval( $this->getOption( 'batch-size' ) );
		}
	}

	/**
	 * Maybe show the help.
	 * @param bool $force Whether to force the help to show, default false
	 */
	protected function maybeHelp( $force = false ) {
		if ( !$force && !$this->hasOption( 'help' ) ) {
			return;
		}

		$screenWidth = 80; // TODO: Calculate this!
		$tab = "    ";
		$descWidth = $screenWidth - ( 2 * strlen( $tab ) );

		ksort( $this->mParams );
		$this->mQuiet = false;

		// Description ...
		if ( $this->mDescription ) {
			$this->output( "\n" . wordwrap( $this->mDescription, $screenWidth ) . "\n" );
		}
		$output = "\nUsage: php " . basename( $this->mSelf );

		// ... append parameters ...
		if ( $this->mParams ) {
			$output .= " [--" . implode( array_keys( $this->mParams ), "|--" ) . "]";
		}

		// ... and append arguments.
		if ( $this->mArgList ) {
			$output .= ' ';
			foreach ( $this->mArgList as $k => $arg ) {
				if ( $arg['require'] ) {
					$output .= '<' . $arg['name'] . '>';
				} else {
					$output .= '[' . $arg['name'] . ']';
				}
				if ( $k < count( $this->mArgList ) - 1 ) {
					$output .= ' ';
				}
			}
		}
		$this->output( "$output\n\n" );

		# TODO abstract some repetitive code below

		// Generic parameters
		$this->output( "Generic maintenance parameters:\n" );
		foreach ( $this->mGenericParameters as $par => $info ) {
			if ( $info['shortName'] !== false ) {
				$par .= " (-{$info['shortName']})";
			}
			$this->output(
				wordwrap( "$tab--$par: " . $info['desc'], $descWidth,
					"\n$tab$tab" ) . "\n"
			);
		}
		$this->output( "\n" );

		$scriptDependantParams = $this->mDependantParameters;
		if ( count( $scriptDependantParams ) > 0 ) {
			$this->output( "Script dependant parameters:\n" );
			// Parameters description
			foreach ( $scriptDependantParams as $par => $info ) {
				if ( $info['shortName'] !== false ) {
					$par .= " (-{$info['shortName']})";
				}
				$this->output(
					wordwrap( "$tab--$par: " . $info['desc'], $descWidth,
						"\n$tab$tab" ) . "\n"
				);
			}
			$this->output( "\n" );
		}

		// Script specific parameters not defined on construction by
		// Maintenance::addDefaultParams()
		$scriptSpecificParams = array_diff_key(
			# all script parameters:
			$this->mParams,
			# remove the Maintenance default parameters:
			$this->mGenericParameters,
			$this->mDependantParameters
		);
		if ( count( $scriptSpecificParams ) > 0 ) {
			$this->output( "Script specific parameters:\n" );
			// Parameters description
			foreach ( $scriptSpecificParams as $par => $info ) {
				if ( $info['shortName'] !== false ) {
					$par .= " (-{$info['shortName']})";
				}
				$this->output(
					wordwrap( "$tab--$par: " . $info['desc'], $descWidth,
						"\n$tab$tab" ) . "\n"
				);
			}
			$this->output( "\n" );
		}

		// Print arguments
		if ( count( $this->mArgList ) > 0 ) {
			$this->output( "Arguments:\n" );
			// Arguments description
			foreach ( $this->mArgList as $info ) {
				$openChar = $info['require'] ? '<' : '[';
				$closeChar = $info['require'] ? '>' : ']';
				$this->output(
					wordwrap( "$tab$openChar" . $info['name'] . "$closeChar: " .
						$info['desc'], $descWidth, "\n$tab$tab" ) . "\n"
				);
			}
			$this->output( "\n" );
		}

		die( 1 );
	}

	/**
	 * Handle some last-minute setup here.
	 */
	public function finalSetup() {
		global $wgCommandLineMode, $wgShowSQLErrors, $wgServer;
		global $wgDBadminuser, $wgDBadminpassword;
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

		if ( $this->getDbType() == self::DB_ADMIN && isset( $wgDBadminuser ) ) {
			$wgDBuser = $wgDBadminuser;
			$wgDBpassword = $wgDBadminpassword;

			if ( $wgDBservers ) {
				/**
				 * @var $wgDBservers array
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
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->destroy();
		}

		// Per-script profiling; useful for debugging
		$this->activateProfiler();

		$this->afterFinalSetup();

		$wgShowSQLErrors = true;

		MediaWiki\suppressWarnings();
		set_time_limit( 0 );
		MediaWiki\restoreWarnings();

		$this->adjustMemoryLimit();
	}

	/**
	 * Execute a callback function at the end of initialisation
	 */
	protected function afterFinalSetup() {
		if ( defined( 'MW_CMDLINE_CALLBACK' ) ) {
			call_user_func( MW_CMDLINE_CALLBACK );
		}
	}

	/**
	 * Potentially debug globals. Originally a feature only
	 * for refreshLinks
	 */
	public function globals() {
		if ( $this->hasOption( 'globals' ) ) {
			print_r( $GLOBALS );
		}
	}

	/**
	 * Generic setup for most installs. Returns the location of LocalSettings
	 * @return string
	 */
	public function loadSettings() {
		global $wgCommandLineMode, $IP;

		if ( isset( $this->mOptions['conf'] ) ) {
			$settingsFile = $this->mOptions['conf'];
		} elseif ( defined( "MW_CONFIG_FILE" ) ) {
			$settingsFile = MW_CONFIG_FILE;
		} else {
			$settingsFile = "$IP/LocalSettings.php";
		}
		if ( isset( $this->mOptions['wiki'] ) ) {
			$bits = explode( '-', $this->mOptions['wiki'] );
			if ( count( $bits ) == 1 ) {
				$bits[] = '';
			}
			define( 'MW_DB', $bits[0] );
			define( 'MW_PREFIX', $bits[1] );
		}

		if ( !is_readable( $settingsFile ) ) {
			$this->error( "A copy of your installation's LocalSettings.php\n" .
				"must exist and be readable in the source directory.\n" .
				"Use --conf to specify it.", true );
		}
		$wgCommandLineMode = true;

		return $settingsFile;
	}

	/**
	 * Support function for cleaning up redundant text records
	 * @param bool $delete Whether or not to actually delete the records
	 * @author Rob Church <robchur@gmail.com>
	 */
	public function purgeRedundantText( $delete = true ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = $this->getDB( DB_MASTER );
		$this->beginTransaction( $dbw, __METHOD__ );

		# Get "active" text records from the revisions table
		$cur = [];
		$this->output( 'Searching for active text records in revisions table...' );
		$res = $dbw->select( 'revision', 'rev_text_id', [], __METHOD__, [ 'DISTINCT' ] );
		foreach ( $res as $row ) {
			$cur[] = $row->rev_text_id;
		}
		$this->output( "done.\n" );

		# Get "active" text records from the archive table
		$this->output( 'Searching for active text records in archive table...' );
		$res = $dbw->select( 'archive', 'ar_text_id', [], __METHOD__, [ 'DISTINCT' ] );
		foreach ( $res as $row ) {
			# old pre-MW 1.5 records can have null ar_text_id's.
			if ( $row->ar_text_id !== null ) {
				$cur[] = $row->ar_text_id;
			}
		}
		$this->output( "done.\n" );

		# Get the IDs of all text records not in these sets
		$this->output( 'Searching for inactive text records...' );
		$cond = 'old_id NOT IN ( ' . $dbw->makeList( $cur ) . ' )';
		$res = $dbw->select( 'text', 'old_id', [ $cond ], __METHOD__, [ 'DISTINCT' ] );
		$old = [];
		foreach ( $res as $row ) {
			$old[] = $row->old_id;
		}
		$this->output( "done.\n" );

		# Inform the user of what we're going to do
		$count = count( $old );
		$this->output( "$count inactive items found.\n" );

		# Delete as appropriate
		if ( $delete && $count ) {
			$this->output( 'Deleting...' );
			$dbw->delete( 'text', [ 'old_id' => $old ], __METHOD__ );
			$this->output( "done.\n" );
		}

		# Done
		$this->commitTransaction( $dbw, __METHOD__ );
	}

	/**
	 * Get the maintenance directory.
	 * @return string
	 */
	protected function getDir() {
		return __DIR__;
	}

	/**
	 * Returns a database to be used by current maintenance script. It can be set by setDB().
	 * If not set, wfGetDB() will be used.
	 * This function has the same parameters as wfGetDB()
	 *
	 * @param integer $db DB index (DB_REPLICA/DB_MASTER)
	 * @param array $groups; default: empty array
	 * @param string|bool $wiki; default: current wiki
	 * @return Database
	 */
	protected function getDB( $db, $groups = [], $wiki = false ) {
		if ( is_null( $this->mDb ) ) {
			return wfGetDB( $db, $groups, $wiki );
		} else {
			return $this->mDb;
		}
	}

	/**
	 * Sets database object to be returned by getDB().
	 *
	 * @param IDatabase $db Database object to be used
	 */
	public function setDB( IDatabase $db ) {
		$this->mDb = $db;
	}

	/**
	 * Begin a transcation on a DB
	 *
	 * This method makes it clear that begin() is called from a maintenance script,
	 * which has outermost scope. This is safe, unlike $dbw->begin() called in other places.
	 *
	 * @param IDatabase $dbw
	 * @param string $fname Caller name
	 * @since 1.27
	 */
	protected function beginTransaction( IDatabase $dbw, $fname ) {
		$dbw->begin( $fname );
	}

	/**
	 * Commit the transcation on a DB handle and wait for replica DBs to catch up
	 *
	 * This method makes it clear that commit() is called from a maintenance script,
	 * which has outermost scope. This is safe, unlike $dbw->commit() called in other places.
	 *
	 * @param IDatabase $dbw
	 * @param string $fname Caller name
	 * @return bool Whether the replica DB wait succeeded
	 * @since 1.27
	 */
	protected function commitTransaction( IDatabase $dbw, $fname ) {
		$dbw->commit( $fname );
		try {
			$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
			$lbFactory->waitForReplication(
				[ 'timeout' => 30, 'ifWritesSince' => $this->lastReplicationWait ]
			);
			$this->lastReplicationWait = microtime( true );

			return true;
		} catch ( DBReplicationWaitError $e ) {
			return false;
		}
	}

	/**
	 * Rollback the transcation on a DB handle
	 *
	 * This method makes it clear that rollback() is called from a maintenance script,
	 * which has outermost scope. This is safe, unlike $dbw->rollback() called in other places.
	 *
	 * @param IDatabase $dbw
	 * @param string $fname Caller name
	 * @since 1.27
	 */
	protected function rollbackTransaction( IDatabase $dbw, $fname ) {
		$dbw->rollback( $fname );
	}

	/**
	 * Lock the search index
	 * @param Database &$db
	 */
	private function lockSearchindex( $db ) {
		$write = [ 'searchindex' ];
		$read = [
			'page',
			'revision',
			'text',
			'interwiki',
			'l10n_cache',
			'user',
			'page_restrictions'
		];
		$db->lockTables( $read, $write, __CLASS__ . '::' . __METHOD__ );
	}

	/**
	 * Unlock the tables
	 * @param Database &$db
	 */
	private function unlockSearchindex( $db ) {
		$db->unlockTables( __CLASS__ . '::' . __METHOD__ );
	}

	/**
	 * Unlock and lock again
	 * Since the lock is low-priority, queued reads will be able to complete
	 * @param Database &$db
	 */
	private function relockSearchindex( $db ) {
		$this->unlockSearchindex( $db );
		$this->lockSearchindex( $db );
	}

	/**
	 * Perform a search index update with locking
	 * @param int $maxLockTime The maximum time to keep the search index locked.
	 * @param string $callback The function that will update the function.
	 * @param Database $dbw
	 * @param array $results
	 */
	public function updateSearchIndex( $maxLockTime, $callback, $dbw, $results ) {
		$lockTime = time();

		# Lock searchindex
		if ( $maxLockTime ) {
			$this->output( "   --- Waiting for lock ---" );
			$this->lockSearchindex( $dbw );
			$lockTime = time();
			$this->output( "\n" );
		}

		# Loop through the results and do a search update
		foreach ( $results as $row ) {
			# Allow reads to be processed
			if ( $maxLockTime && time() > $lockTime + $maxLockTime ) {
				$this->output( "    --- Relocking ---" );
				$this->relockSearchindex( $dbw );
				$lockTime = time();
				$this->output( "\n" );
			}
			call_user_func( $callback, $dbw, $row );
		}

		# Unlock searchindex
		if ( $maxLockTime ) {
			$this->output( "    --- Unlocking --" );
			$this->unlockSearchindex( $dbw );
			$this->output( "\n" );
		}
	}

	/**
	 * Update the searchindex table for a given pageid
	 * @param Database $dbw A database write handle
	 * @param int $pageId The page ID to update.
	 * @return null|string
	 */
	public function updateSearchIndexForPage( $dbw, $pageId ) {
		// Get current revision
		$rev = Revision::loadFromPageId( $dbw, $pageId );
		$title = null;
		if ( $rev ) {
			$titleObj = $rev->getTitle();
			$title = $titleObj->getPrefixedDBkey();
			$this->output( "$title..." );
			# Update searchindex
			$u = new SearchUpdate( $pageId, $titleObj->getText(), $rev->getContent() );
			$u->doUpdate();
			$this->output( "\n" );
		}

		return $title;
	}

	/**
	 * Wrapper for posix_isatty()
	 * We default as considering stdin a tty (for nice readline methods)
	 * but treating stout as not a tty to avoid color codes
	 *
	 * @param mixed $fd File descriptor
	 * @return bool
	 */
	public static function posix_isatty( $fd ) {
		if ( !function_exists( 'posix_isatty' ) ) {
			return !$fd;
		} else {
			return posix_isatty( $fd );
		}
	}

	/**
	 * Prompt the console for input
	 * @param string $prompt What to begin the line with, like '> '
	 * @return string Response
	 */
	public static function readconsole( $prompt = '> ' ) {
		static $isatty = null;
		if ( is_null( $isatty ) ) {
			$isatty = self::posix_isatty( 0 /*STDIN*/ );
		}

		if ( $isatty && function_exists( 'readline' ) ) {
			$resp = readline( $prompt );
			if ( $resp === null ) {
				// Workaround for https://github.com/facebook/hhvm/issues/4776
				return false;
			} else {
				return $resp;
			}
		} else {
			if ( $isatty ) {
				$st = self::readlineEmulation( $prompt );
			} else {
				if ( feof( STDIN ) ) {
					$st = false;
				} else {
					$st = fgets( STDIN, 1024 );
				}
			}
			if ( $st === false ) {
				return false;
			}
			$resp = trim( $st );

			return $resp;
		}
	}

	/**
	 * Emulate readline()
	 * @param string $prompt What to begin the line with, like '> '
	 * @return string
	 */
	private static function readlineEmulation( $prompt ) {
		$bash = Installer::locateExecutableInDefaultPaths( [ 'bash' ] );
		if ( !wfIsWindows() && $bash ) {
			$retval = false;
			$encPrompt = wfEscapeShellArg( $prompt );
			$command = "read -er -p $encPrompt && echo \"\$REPLY\"";
			$encCommand = wfEscapeShellArg( $command );
			$line = wfShellExec( "$bash -c $encCommand", $retval, [], [ 'walltime' => 0 ] );

			if ( $retval == 0 ) {
				return $line;
			} elseif ( $retval == 127 ) {
				// Couldn't execute bash even though we thought we saw it.
				// Shell probably spit out an error message, sorry :(
				// Fall through to fgets()...
			} else {
				// EOF/ctrl+D
				return false;
			}
		}

		// Fallback... we'll have no editing controls, EWWW
		if ( feof( STDIN ) ) {
			return false;
		}
		print $prompt;

		return fgets( STDIN, 1024 );
	}

	/**
	 * Call this to set up the autoloader to allow classes to be used from the
	 * tests directory.
	 */
	public static function requireTestsAutoloader() {
		require_once __DIR__ . '/../tests/common/TestsAutoLoader.php';
	}
}

/**
 * Fake maintenance wrapper, mostly used for the web installer/updater
 */
class FakeMaintenance extends Maintenance {
	protected $mSelf = "FakeMaintenanceScript";

	public function execute() {
		return;
	}
}

/**
 * Class for scripts that perform database maintenance and want to log the
 * update in `updatelog` so we can later skip it
 */
abstract class LoggedUpdateMaintenance extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'force', 'Run the update even if it was completed already' );
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$db = $this->getDB( DB_MASTER );
		$key = $this->getUpdateKey();

		if ( !$this->hasOption( 'force' )
			&& $db->selectRow( 'updatelog', '1', [ 'ul_key' => $key ], __METHOD__ )
		) {
			$this->output( "..." . $this->updateSkippedMessage() . "\n" );

			return true;
		}

		if ( !$this->doDBUpdates() ) {
			return false;
		}

		if ( $db->insert( 'updatelog', [ 'ul_key' => $key ], __METHOD__, 'IGNORE' ) ) {
			return true;
		} else {
			$this->output( $this->updatelogFailedMessage() . "\n" );

			return false;
		}
	}

	/**
	 * Message to show that the update was done already and was just skipped
	 * @return string
	 */
	protected function updateSkippedMessage() {
		$key = $this->getUpdateKey();

		return "Update '{$key}' already logged as completed.";
	}

	/**
	 * Message to show that the update log was unable to log the completion of this update
	 * @return string
	 */
	protected function updatelogFailedMessage() {
		$key = $this->getUpdateKey();

		return "Unable to log update '{$key}' as completed.";
	}

	/**
	 * Do the actual work. All child classes will need to implement this.
	 * Return true to log the update as done or false (usually on failure).
	 * @return bool
	 */
	abstract protected function doDBUpdates();

	/**
	 * Get the update key name to go in the update log table
	 * @return string
	 */
	abstract protected function getUpdateKey();
}
