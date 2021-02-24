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
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Shell\Shell;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;

/**
 * Abstract maintenance class for quickly writing and churning out
 * maintenance scripts with minimal effort. All that _must_ be defined
 * is the execute() method. See docs/maintenance.txt for more info
 * and a quick demo of how to use it.
 *
 * Terminology:
 *   params: registry of named values that may be passed to the script
 *   arg list: registry of positional values that may be passed to the script
 *   options: passed param values
 *   args: passed positional values
 *
 * In the command:
 *   mwscript somescript.php --foo=bar baz
 * foo is a param
 * bar is the option value of the option for param foo
 * baz is the arg value at index 0 in the arg list
 *
 * @stable for subclassing
 *
 * @since 1.16
 * @ingroup Maintenance
 */
abstract class Maintenance {
	/**
	 * Constants for DB access type
	 * @see Maintenance::getDbType()
	 */
	public const DB_NONE = 0;
	public const DB_STD = 1;
	public const DB_ADMIN = 2;

	// Const for getStdin()
	public const STDIN_ALL = 'all';

	/**
	 * Array of desired/allowed params
	 * @var array[]
	 * @phan-var array<string,array{desc:string,require:bool,withArg:string,shortName:string,multiOccurrence:bool}>
	 */
	protected $mParams = [];

	// Array of mapping short parameters to long ones
	protected $mShortParamsMap = [];

	// Array of desired/allowed args
	protected $mArgList = [];

	// This is the list of options that were actually passed
	protected $mOptions = [];

	// This is the list of arguments that were actually passed
	protected $mArgs = [];

	// Allow arbitrary options to be passed, or only specified ones?
	protected $mAllowUnregisteredOptions = false;

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
	private $mDependantParameters = [];

	/**
	 * Used by getDB() / setDB()
	 * @var IMaintainableDatabase
	 */
	private $mDb = null;

	/** @var float UNIX timestamp */
	private $lastReplicationWait = 0.0;

	/**
	 * Used when creating separate schema files.
	 * @var resource
	 */
	public $fileHandle;

	/** @var HookContainer|null */
	private $hookContainer;

	/** @var HookRunner|null */
	private $hookRunner;

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
	 *
	 * @stable for calling
	 */
	public function __construct() {
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
	 * Do the actual work. All child classes will need to implement this
	 *
	 * @return bool|null|void True for success, false for failure. Not returning
	 *   a value, or returning null, is also interpreted as success. Returning
	 *   false for failure will cause doMaintenance.php to exit the process
	 *   with a non-zero exit status.
	 */
	abstract public function execute();

	/**
	 * Checks to see if a particular option in supported.  Normally this means it
	 * has been registered by the script via addOption.
	 * @param string $name The name of the option
	 * @return bool true if the option exists, false otherwise
	 */
	protected function supportsOption( $name ) {
		return isset( $this->mParams[$name] );
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
	 * Checks to see if a particular option was set.
	 *
	 * @param string $name The name of the option
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
	 * @param mixed|null $default Anything you want, default null
	 * @return mixed
	 * @return-taint none
	 */
	protected function getOption( $name, $default = null ) {
		if ( $this->hasOption( $name ) ) {
			return $this->mOptions[$name];
		} else {
			return $default;
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
	 * Sets whether to allow unregistered options, which are options passed to
	 * a script that do not match an expected parameter.
	 * @param bool $allow Should we allow?
	 */
	protected function setAllowUnregisteredOptions( $allow ) {
		$this->mAllowUnregisteredOptions = $allow;
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
		if ( func_num_args() === 0 ) {
			wfDeprecated( __METHOD__ . ' without an $argId', '1.33' );
		}

		return isset( $this->mArgs[$argId] );
	}

	/**
	 * Get an argument.
	 * @param int $argId The integer value (from zero) for the arg
	 * @param mixed|null $default The default if it doesn't exist
	 * @return mixed
	 * @return-taint none
	 */
	protected function getArg( $argId = 0, $default = null ) {
		if ( func_num_args() === 0 ) {
			wfDeprecated( __METHOD__ . ' without an $argId', '1.33' );
		}

		return $this->mArgs[$argId] ?? $default;
	}

	/**
	 * Returns batch size
	 *
	 * @since 1.31
	 *
	 * @return int|null
	 */
	protected function getBatchSize() {
		return $this->mBatchSize;
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
	 * @param int|null $len The number of bytes to read. If null, just return the handle.
	 *   Maintenance::STDIN_ALL returns the full length
	 * @return mixed
	 */
	protected function getStdin( $len = null ) {
		if ( $len == self::STDIN_ALL ) {
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
	 * @stable for overriding
	 * @param string $out The text to show to the user
	 * @param mixed|null $channel Unique identifier for the channel. See function outputChanneled.
	 */
	protected function output( $out, $channel = null ) {
		// This is sometimes called very early, before Setup.php is included.
		if ( class_exists( MediaWikiServices::class ) ) {
			// Try to periodically flush buffered metrics to avoid OOMs
			$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
			if ( $stats->getDataCount() > 1000 ) {
				MediaWiki::emitBufferedStatsdData( $stats, $this->getConfig() );
			}
		}

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
	 * @stable for overriding
	 * @param string $err The error to display
	 * @param int $die Deprecated since 1.31, use Maintenance::fatalError() instead
	 */
	protected function error( $err, $die = 0 ) {
		if ( intval( $die ) !== 0 ) {
			wfDeprecated( __METHOD__ . '( $err, $die )', '1.31' );
			$this->fatalError( $err, intval( $die ) );
		}
		$this->outputChanneled( false );
		if (
			( PHP_SAPI == 'cli' || PHP_SAPI == 'phpdbg' ) &&
			!defined( 'MW_PHPUNIT_TEST' )
		) {
			fwrite( STDERR, $err . "\n" );
		} else {
			print $err;
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
	protected function fatalError( $msg, $exitCode = 1 ) {
		$this->error( $msg );
		exit( $exitCode );
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

	/**
	 * Does the script need different DB access? By default, we give Maintenance
	 * scripts normal rights to the DB. Sometimes, a script needs admin rights
	 * access for a reason and sometimes they want no access. Subclasses should
	 * override and return one of the following values, as needed:
	 *    Maintenance::DB_NONE  -  For no DB access at all
	 *    Maintenance::DB_STD   -  For normal DB access, default
	 *    Maintenance::DB_ADMIN -  For admin DB access
	 * @stable for overriding
	 * @return int
	 */
	public function getDbType() {
		return self::DB_STD;
	}

	/**
	 * Add the default parameters to the scripts
	 */
	protected function addDefaultParams() {
		# Generic (non script dependant) options:

		$this->addOption( 'help', 'Display this help message', false, false, 'h' );
		$this->addOption( 'quiet', 'Whether to suppress non-error output', false, false, 'q' );
		$this->addOption( 'conf', 'Location of LocalSettings.php, if not default', false, true );
		$this->addOption( 'wiki', 'For specifying the wiki ID', false, true );
		$this->addOption( 'globals', 'Output globals at the end of processing for debugging' );
		$this->addOption(
			'memory-limit',
			'Set a specific memory limit for the script, '
				. '"max" for no limit or "default" to avoid changing it',
			false,
			true
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
			$this->addOption( 'dbgroupdefault', 'The default DB group to use.', false, true );
		}

		# Save additional script dependant options to display
		#  them separately in help
		$this->mDependantParameters = array_diff_key( $this->mParams, $this->mGenericParameters );
	}

	/**
	 * @since 1.24
	 * @stable for overriding
	 * @return Config
	 */
	public function getConfig() {
		if ( $this->config === null ) {
			$this->config = MediaWikiServices::getInstance()->getMainConfig();
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
			$this->fatalError( $msg );
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
		self::setLBFactoryTriggers( $lbFactory, $this->getConfig() );
	}

	/**
	 * @param LBFactory $LBFactory
	 * @param Config $config
	 * @since 1.28
	 */
	public static function setLBFactoryTriggers( LBFactory $LBFactory, Config $config ) {
		$services = MediaWikiServices::getInstance();
		$stats = $services->getStatsdDataFactory();
		// Hook into period lag checks which often happen in long-running scripts
		$lbFactory = $services->getDBLoadBalancerFactory();
		$lbFactory->setWaitForReplicationListener(
			__METHOD__,
			function () use ( $stats, $config ) {
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
			function ( $trigger ) use ( $stats, $config ) {
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
	 * Run a child maintenance script. Pass all of the current arguments
	 * to it.
	 * @param string $maintClass A name of a child maintenance class
	 * @param string|null $classFile Full path of where the child is
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
		 * @var Maintenance $child
		 */
		$child = new $maintClass();
		$child->loadParamsAndArgs( $this->mSelf, $this->mOptions, $this->mArgs );
		if ( $this->mDb !== null ) {
			$child->setDB( $this->mDb );
		}

		return $child;
	}

	/**
	 * Do some sanity checking and basic setup
	 */
	public function setup() {
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
		if ( !defined( 'HPHP_VERSION' ) && !ini_get( 'register_argc_argv' ) ) {
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
		global $wgProfiler, $wgTrxProfilerLimits;

		$output = $this->getOption( 'profiler' );
		if ( !$output ) {
			return;
		}

		if ( isset( $wgProfiler['class'] ) ) {
			$class = $wgProfiler['class'];
			/** @var Profiler $profiler */
			$profiler = new $class(
				[ 'sampling' => 1, 'output' => [ $output ] ]
					+ $wgProfiler
					+ [ 'threshold' => 0.0 ]
			);
			$profiler->setAllowOutput();
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
					$this->setParam( $options, $bits[0], $bits[1] ?? 1 );
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

	/**
	 * Process command line arguments
	 * $mOptions becomes an array with keys set to the option names
	 * $mArgs becomes a zero-based array containing the non-option arguments
	 *
	 * @param string|null $self The name of the script, if any
	 * @param array|null $opts An array of options, in form of key=>value
	 * @param array|null $args An array of command line arguments
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
		# Check arg list too
		foreach ( $this->mArgList as $k => $info ) {
			if ( $info['require'] && !$this->hasArg( $k ) ) {
				$this->error( 'Argument <' . $info['name'] . '> required!' );
				$die = true;
			}
		}
		if ( !$this->mAllowUnregisteredOptions ) {
			# Check for unexpected options
			foreach ( $this->mOptions as $opt => $val ) {
				if ( !$this->supportsOption( $opt ) ) {
					$this->error( "Unexpected option $opt!" );
					$die = true;
				}
			}
		}

		$this->maybeHelp( $die );
	}

	/**
	 * Handle the special variables that are global to all scripts
	 * @stable for overriding
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
	 * Maybe show the help. If the help is shown, exit.
	 *
	 * @param bool $force Whether to force the help to show, default false
	 */
	protected function maybeHelp( $force = false ) {
		if ( !$force && !$this->hasOption( 'help' ) ) {
			return;
		}
		$this->showHelp();
		die( 1 );
	}

	/**
	 * Definitely show the help. Does not exit.
	 */
	protected function showHelp() {
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
			$output .= " [--" . implode( "|--", array_keys( $this->mParams ) ) . "]";
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
		'@phan-var array[] $scriptSpecificParams';
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
	}

	/**
	 * Handle some last-minute setup here.
	 * @stable for overriding
	 */
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

		if ( $this->getDbType() == self::DB_ADMIN && isset( $wgDBadminuser ) ) {
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

		// Per-script profiling; useful for debugging
		$this->activateProfiler();

		$this->afterFinalSetup();

		$wgShowExceptionDetails = true;
		$wgShowHostnames = true;

		Wikimedia\suppressWarnings();
		set_time_limit( 0 );
		Wikimedia\restoreWarnings();

		$this->adjustMemoryLimit();
	}

	/**
	 * Execute a callback function at the end of initialisation
	 * @stable for overriding
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
			$bits = explode( '-', $this->mOptions['wiki'], 2 );
			define( 'MW_DB', $bits[0] );
			define( 'MW_PREFIX', $bits[1] ?? '' );
		} elseif ( isset( $this->mOptions['server'] ) ) {
			// Provide the option for site admins to detect and configure
			// multiple wikis based on server names. This offers --server
			// as alternative to --wiki.
			// See https://www.mediawiki.org/wiki/Manual:Wiki_family
			$_SERVER['SERVER_NAME'] = $this->mOptions['server'];
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
	 * Support function for cleaning up redundant text records
	 * @param bool $delete Whether or not to actually delete the records
	 * @author Rob Church <robchur@gmail.com>
	 */
	public function purgeRedundantText( $delete = true ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = $this->getDB( DB_MASTER );
		$this->beginTransaction( $dbw, __METHOD__ );

		# Get "active" text records via the content table
		$cur = [];
		$this->output( 'Searching for active text records via contents table...' );
		$res = $dbw->select( 'content', 'content_address', [], __METHOD__, [ 'DISTINCT' ] );
		$blobStore = MediaWikiServices::getInstance()->getBlobStore();
		foreach ( $res as $row ) {
			// @phan-suppress-next-line PhanUndeclaredMethod
			$textId = $blobStore->getTextIdFromAddress( $row->content_address );
			if ( $textId ) {
				$cur[] = $textId;
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

		$this->commitTransaction( $dbw, __METHOD__ );
	}

	/**
	 * Get the maintenance directory.
	 * @return string
	 */
	protected function getDir() {
		return __DIR__ . '/../';
	}

	/**
	 * Returns a database to be used by current maintenance script.
	 *
	 * This uses the main LBFactory instance by default unless overriden via setDB().
	 *
	 * This function has the same parameters as LoadBalancer::getConnection().
	 *
	 * @stable for overriding
	 *
	 * @param int $db DB index (DB_REPLICA/DB_MASTER)
	 * @param string|string[] $groups default: empty array
	 * @param string|bool $dbDomain default: current wiki
	 * @return IMaintainableDatabase
	 */
	protected function getDB( $db, $groups = [], $dbDomain = false ) {
		if ( $this->mDb === null ) {
			return MediaWikiServices::getInstance()
				->getDBLoadBalancerFactory()
				->getMainLB( $dbDomain )
				->getMaintenanceConnectionRef( $db, $groups, $dbDomain );
		}

		return $this->mDb;
	}

	/**
	 * Sets database object to be returned by getDB().
	 * @stable for overriding
	 *
	 * @param IMaintainableDatabase $db
	 */
	public function setDB( IMaintainableDatabase $db ) {
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
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$waitSucceeded = $lbFactory->waitForReplication(
			[ 'timeout' => 30, 'ifWritesSince' => $this->lastReplicationWait ]
		);
		$this->lastReplicationWait = microtime( true );
		return $waitSucceeded;
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
	 * @param IMaintainableDatabase $db
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
		$db->lockTables( $read, $write, __CLASS__ . '-searchIndexLock' );
	}

	/**
	 * Unlock the tables
	 * @param IMaintainableDatabase $db
	 */
	private function unlockSearchindex( $db ) {
		$db->unlockTables( __CLASS__ . '-searchIndexLock' );
	}

	/**
	 * Unlock and lock again
	 * Since the lock is low-priority, queued reads will be able to complete
	 * @param IMaintainableDatabase $db
	 */
	private function relockSearchindex( $db ) {
		$this->unlockSearchindex( $db );
		$this->lockSearchindex( $db );
	}

	/**
	 * Perform a search index update with locking
	 * @param int $maxLockTime The maximum time to keep the search index locked.
	 * @param callable $callback The function that will update the function.
	 * @param IMaintainableDatabase $dbw
	 * @param array|IResultWrapper $results
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
			call_user_func( $callback, $row );
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
	 * @param int $pageId The page ID to update.
	 * @return null|string
	 */
	public function updateSearchIndexForPage( int $pageId ) {
		// Get current revision
		$rev = MediaWikiServices::getInstance()
			->getRevisionLookup()
			->getRevisionByPageId( $pageId, 0, IDBAccessObject::READ_LATEST );
		$title = null;
		if ( $rev ) {
			$titleObj = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );
			$title = $titleObj->getPrefixedDBkey();
			$this->output( "$title..." );
			# Update searchindex
			$u = new SearchUpdate( $pageId, $titleObj, $rev->getContent( SlotRecord::MAIN ) );
			$u->doUpdate();
			$this->output( "\n" );
		}

		return $title;
	}

	/**
	 * Count down from $seconds to zero on the terminal, with a one-second pause
	 * between showing each number. If the maintenance script is in quiet mode,
	 * this function does nothing.
	 *
	 * @since 1.31
	 *
	 * @codeCoverageIgnore
	 * @param int $seconds
	 */
	protected function countDown( $seconds ) {
		if ( $this->isQuiet() ) {
			return;
		}
		for ( $i = $seconds; $i >= 0; $i-- ) {
			if ( $i != $seconds ) {
				$this->output( str_repeat( "\x08", strlen( $i + 1 ) ) );
			}
			$this->output( $i );
			if ( $i ) {
				sleep( 1 );
			}
		}
		$this->output( "\n" );
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
		if ( $isatty === null ) {
			$isatty = self::posix_isatty( 0 /*STDIN*/ );
		}

		if ( $isatty && function_exists( 'readline' ) ) {
			return readline( $prompt );
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
		$bash = ExecutableFinder::findInDefaultPaths( 'bash' );
		if ( !wfIsWindows() && $bash ) {
			$retval = false;
			$encPrompt = Shell::escape( $prompt );
			$command = "read -er -p $encPrompt && echo \"\$REPLY\"";
			$encCommand = Shell::escape( $command );
			$line = Shell::escape( "$bash -c $encCommand", $retval, [], [ 'walltime' => 0 ] );

			// @phan-suppress-next-line PhanImpossibleCondition,PhanSuspiciousValueComparison
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
	 * Get the terminal size as a two-element array where the first element
	 * is the width (number of columns) and the second element is the height
	 * (number of rows).
	 *
	 * @return array
	 */
	public static function getTermSize() {
		$default = [ 80, 50 ];
		if ( wfIsWindows() ) {
			return $default;
		}
		if ( Shell::isDisabled() ) {
			return $default;
		}
		// It's possible to get the screen size with VT-100 terminal escapes,
		// but reading the responses is not possible without setting raw mode
		// (unless you want to require the user to press enter), and that
		// requires an ioctl(), which we can't do. So we have to shell out to
		// something that can do the relevant syscalls. There are a few
		// options. Linux and Mac OS X both have "stty size" which does the
		// job directly.
		$result = Shell::command( 'stty', 'size' )
			->execute();
		if ( $result->getExitCode() !== 0 ) {
			return $default;
		}
		if ( !preg_match( '/^(\d+) (\d+)$/', $result->getStdout(), $m ) ) {
			return $default;
		}
		return [ intval( $m[2] ), intval( $m[1] ) ];
	}

	/**
	 * Call this to set up the autoloader to allow classes to be used from the
	 * tests directory.
	 */
	public static function requireTestsAutoloader() {
		require_once __DIR__ . '/../../tests/common/TestsAutoLoader.php';
	}

	/**
	 * Get a HookContainer, for running extension hooks or for hook metadata.
	 *
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer() {
		if ( !$this->hookContainer ) {
			$this->hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		}
		return $this->hookContainer;
	}

	/**
	 * Get a HookRunner for running core hooks.
	 *
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner() {
		if ( !$this->hookRunner ) {
			$this->hookRunner = new HookRunner( $this->getHookContainer() );
		}
		return $this->hookRunner;
	}

	/**
	 * Utility function to parse a string (perhaps from a command line option)
	 * into a list of integers (perhaps some kind of numeric IDs).
	 *
	 * @since 1.35
	 *
	 * @param string $text
	 *
	 * @return int[]
	 */
	protected function parseIntList( $text ) {
		$ids = preg_split( '/[\s,;:|]+/', $text );
		$ids = array_map(
			function ( $id ) {
				return (int)$id;
			},
			$ids
		);
		return array_filter( $ids );
	}
}
