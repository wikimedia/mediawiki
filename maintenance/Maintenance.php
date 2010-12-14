<?php
/**
 * @file
 * @ingroup Maintenance
 * @defgroup Maintenance Maintenance
 */

// Define this so scripts can easily find doMaintenance.php
define( 'DO_MAINTENANCE', dirname( __FILE__ ) . '/doMaintenance.php' );
$maintClass = false;

// Make sure we're on PHP5 or better
if ( version_compare( PHP_VERSION, '5.1.0' ) < 0 ) {
	die ( "Sorry! This version of MediaWiki requires PHP 5.1.x; you are running " .
		PHP_VERSION . ".\n\n" .
		"If you are sure you already have PHP 5.1.x or higher installed, it may be\n" .
		"installed in a different path from PHP " . PHP_VERSION . ". Check with your system\n" .
		"administrator.\n" );
}

// Wrapper for posix_isatty()
if ( !function_exists( 'posix_isatty' ) ) {
	# We default as considering stdin a tty (for nice readline methods)
	# but treating stout as not a tty to avoid color codes
	function posix_isatty( $fd ) {
		return !$fd;
	}
}

/**
 * Abstract maintenance class for quickly writing and churning out
 * maintenance scripts with minimal effort. All that _must_ be defined
 * is the execute() method. See docs/maintenance.txt for more info
 * and a quick demo of how to use it.
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
 * @author Chad Horohoe <chad@anyonecanedit.org>
 * @since 1.16
 * @ingroup Maintenance
 */
abstract class Maintenance {

	/**
	 * Constants for DB access type
	 * @see Maintenance::getDbType()
	 */
	const DB_NONE  = 0;
	const DB_STD   = 1;
	const DB_ADMIN = 2;

	// Const for getStdin()
	const STDIN_ALL = 'all';

	// This is the desired params
	protected $mParams = array();

	// Array of desired args
	protected $mArgList = array();

	// This is the list of options that were actually passed
	protected $mOptions = array();

	// This is the list of arguments that were actually passed
	protected $mArgs = array();

	// Name of the script currently running
	protected $mSelf;

	// Special vars for params that are always used
	protected $mQuiet = false;
	protected $mDbUser, $mDbPass;

	// A description of the script, children should change this
	protected $mDescription = '';

	// Have we already loaded our user input?
	protected $mInputLoaded = false;

	// Batch size. If a script supports this, they should set
	// a default with setBatchSize()
	protected $mBatchSize = null;

	/**
	 * List of all the core maintenance scripts. This is added
	 * to scripts added by extensions in $wgMaintenanceScripts
	 * and returned by getMaintenanceScripts()
	 */
	protected static $mCoreScripts = null;

	/**
	 * Default constructor. Children should call this *first* if implementing
	 * their own constructors
	 */
	public function __construct() {
		// Setup $IP, using MW_INSTALL_PATH if it exists
		global $IP;
		$IP = strval( getenv( 'MW_INSTALL_PATH' ) ) !== ''
			? getenv( 'MW_INSTALL_PATH' )
			: realpath( dirname( __FILE__ ) . '/..' );

		$this->addDefaultParams();
		register_shutdown_function( array( $this, 'outputChanneled' ), false );
	}

	/**
	 * Do the actual work. All child classes will need to implement this
	 */
	abstract public function execute();

	/**
	 * Add a parameter to the script. Will be displayed on --help
	 * with the associated description
	 *
	 * @param $name String: the name of the param (help, version, etc)
	 * @param $description String: the description of the param to show on --help
	 * @param $required Boolean: is the param required?
	 * @param $withArg Boolean: is an argument required with this option?
	 */
	protected function addOption( $name, $description, $required = false, $withArg = false ) {
		$this->mParams[$name] = array( 'desc' => $description, 'require' => $required, 'withArg' => $withArg );
	}

	/**
	 * Checks to see if a particular param exists.
	 * @param $name String: the name of the param
	 * @return Boolean
	 */
	protected function hasOption( $name ) {
		return isset( $this->mOptions[$name] );
	}

	/**
	 * Get an option, or return the default
	 * @param $name String: the name of the param
	 * @param $default Mixed: anything you want, default null
	 * @return Mixed
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
	 * @param $arg String: name of the arg, like 'start'
	 * @param $description String: short description of the arg
	 * @param $required Boolean: is this required?
	 */
	protected function addArg( $arg, $description, $required = true ) {
		$this->mArgList[] = array(
			'name' => $arg,
			'desc' => $description,
			'require' => $required
		);
	}

	/**
	 * Remove an option.  Useful for removing options that won't be used in your script.
	 * @param $name String: the option to remove.
	 */
	protected function deleteOption( $name ) {
		unset( $this->mParams[$name] );
	}

	/**
	 * Set the description text.
	 * @param $text String: the text of the description
	 */
	protected function addDescription( $text ) {
		$this->mDescription = $text;
	}

	/**
	 * Does a given argument exist?
	 * @param $argId Integer: the integer value (from zero) for the arg
	 * @return Boolean
	 */
	protected function hasArg( $argId = 0 ) {
		return isset( $this->mArgs[$argId] );
	}

	/**
	 * Get an argument.
	 * @param $argId Integer: the integer value (from zero) for the arg
	 * @param $default Mixed: the default if it doesn't exist
	 * @return mixed
	 */
	protected function getArg( $argId = 0, $default = null ) {
		return $this->hasArg( $argId ) ? $this->mArgs[$argId] : $default;
	}

	/**
	 * Set the batch size.
	 * @param $s Integer: the number of operations to do in a batch
	 */
	protected function setBatchSize( $s = 0 ) {
		$this->mBatchSize = $s;
	}

	/**
	 * Get the script's name
	 * @return String
	 */
	public function getName() {
		return $this->mSelf;
	}

	/**
	 * Return input from stdin.
	 * @param $len Integer: the number of bytes to read. If null,
	 *        just return the handle. Maintenance::STDIN_ALL returns
	 *        the full length
	 * @return Mixed
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

	public function isQuiet() {
		return $this->mQuiet;
	}

	/**
	 * Throw some output to the user. Scripts can call this with no fears,
	 * as we handle all --quiet stuff here
	 * @param $out String: the text to show to the user
	 * @param $channel Mixed: unique identifier for the channel. See
	 *     function outputChanneled.
	 */
	protected function output( $out, $channel = null ) {
		if ( $this->mQuiet ) {
			return;
		}
		if ( $channel === null ) {
			$this->cleanupChanneled();

			$f = fopen( 'php://stdout', 'w' );
			fwrite( $f, $out );
			fclose( $f );
		}
		else {
			$out = preg_replace( '/\n\z/', '', $out );
			$this->outputChanneled( $out, $channel );
		}
	}

	/**
	 * Throw an error to the user. Doesn't respect --quiet, so don't use
	 * this for non-error output
	 * @param $err String: the error to display
	 * @param $die Boolean: If true, go ahead and die out.
	 */
	protected function error( $err, $die = false ) {
		$this->outputChanneled( false );
		if ( php_sapi_name() == 'cli' ) {
			fwrite( STDERR, $err . "\n" );
		} else {
			$f = fopen( 'php://stderr', 'w' );
			fwrite( $f, $err . "\n" );
			fclose( $f );
		}
		if ( $die ) {
			die();
		}
	}

	private $atLineStart = true;
	private $lastChannel = null;

	/**
	 * Clean up channeled output.  Output a newline if necessary.
	 */
	public function cleanupChanneled() {
		if ( !$this->atLineStart ) {
			$handle = fopen( 'php://stdout', 'w' );
			fwrite( $handle, "\n" );
			fclose( $handle );
			$this->atLineStart = true;
		}
	}

	/**
	 * Message outputter with channeled message support. Messages on the
	 * same channel are concatenated, but any intervening messages in another
	 * channel start a new line.
	 * @param $msg String: the message without trailing newline
	 * @param $channel Channel identifier or null for no
	 *     channel. Channel comparison uses ===.
	 */
	public function outputChanneled( $msg, $channel = null ) {
		if ( $msg === false ) {
			$this->cleanupChanneled();
			return;
		}

		$handle = fopen( 'php://stdout', 'w' );

		// End the current line if necessary
		if ( !$this->atLineStart && $channel !== $this->lastChannel ) {
			fwrite( $handle, "\n" );
		}

		fwrite( $handle, $msg );

		$this->atLineStart = false;
		if ( $channel === null ) {
			// For unchanneled messages, output trailing newline immediately
			fwrite( $handle, "\n" );
			$this->atLineStart = true;
		}
		$this->lastChannel = $channel;

		// Cleanup handle
		fclose( $handle );
	}

	/**
	 * Does the script need different DB access? By default, we give Maintenance
	 * scripts normal rights to the DB. Sometimes, a script needs admin rights
	 * access for a reason and sometimes they want no access. Subclasses should
	 * override and return one of the following values, as needed:
	 *    Maintenance::DB_NONE  -  For no DB access at all
	 *    Maintenance::DB_STD   -  For normal DB access, default
	 *    Maintenance::DB_ADMIN -  For admin DB access
	 * @return Integer
	 */
	public function getDbType() {
		return Maintenance::DB_STD;
	}

	/**
	 * Add the default parameters to the scripts
	 */
	protected function addDefaultParams() {
		$this->addOption( 'help', 'Display this help message' );
		$this->addOption( 'quiet', 'Whether to supress non-error output' );
		$this->addOption( 'conf', 'Location of LocalSettings.php, if not default', false, true );
		$this->addOption( 'wiki', 'For specifying the wiki ID', false, true );
		$this->addOption( 'globals', 'Output globals at the end of processing for debugging' );
		$this->addOption( 'memory-limit', 'Set a specific memory limit for the script, "max" for no limit or "default" to avoid changing it' );
		$this->addOption( 'server', "The protocol and server name to use in URLs, e.g. " .
				"http://en.wikipedia.org. This is sometimes necessary because " .
				"server name detection may fail in command line scripts.", false, true );
		// If we support a DB, show the options
		if ( $this->getDbType() > 0 ) {
			$this->addOption( 'dbuser', 'The DB user to use for this script', false, true );
			$this->addOption( 'dbpass', 'The password to use for this script', false, true );
		}
		// If we support $mBatchSize, show the option
		if ( $this->mBatchSize ) {
			$this->addOption( 'batch-size', 'Run this many operations ' .
				'per batch, default: ' . $this->mBatchSize, false, true );
		}
	}

	/**
	 * Run a child maintenance script. Pass all of the current arguments
	 * to it.
	 * @param $maintClass String: a name of a child maintenance class
	 * @param $classFile String: full path of where the child is
	 * @return Maintenance child
	 */
	public function runChild( $maintClass, $classFile = null ) {
		// If we haven't already specified, kill setup procedures
		// for child scripts, we've already got a sane environment
		self::disableSetup();

		// Make sure the class is loaded first
		if ( !class_exists( $maintClass ) ) {
			if ( $classFile ) {
				require_once( $classFile );
			}
			if ( !class_exists( $maintClass ) ) {
				$this->error( "Cannot spawn child: $maintClass" );
			}
		}

		$child = new $maintClass();
		$child->loadParamsAndArgs( $this->mSelf, $this->mOptions, $this->mArgs );
		return $child;
	}

	/**
	 * Disable Setup.php mostly
	 */
	protected static function disableSetup() {
		if ( !defined( 'MW_NO_SETUP' ) ) {
			define( 'MW_NO_SETUP', true );
		}
	}

	/**
	 * Do some sanity checking and basic setup
	 */
	public function setup() {
		global $wgCommandLineMode, $wgRequestTime;

		# Abort if called from a web server
		if ( isset( $_SERVER ) && isset( $_SERVER['REQUEST_METHOD'] ) ) {
			$this->error( 'This script must be run from the command line', true );
		}

		# Make sure we can handle script parameters
		if ( !ini_get( 'register_argc_argv' ) ) {
			$this->error( 'Cannot get command line arguments, register_argc_argv is set to false', true );
		}

		if ( version_compare( phpversion(), '5.2.4' ) >= 0 ) {
			// Send PHP warnings and errors to stderr instead of stdout.
			// This aids in diagnosing problems, while keeping messages
			// out of redirected output.
			if ( ini_get( 'display_errors' ) ) {
				ini_set( 'display_errors', 'stderr' );
			}

			// Don't touch the setting on earlier versions of PHP,
			// as setting it would disable output if you'd wanted it.

			// Note that exceptions are also sent to stderr when
			// command-line mode is on, regardless of PHP version.
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
		@ob_end_flush();

		$this->validateParamsAndArgs();
	}

	/**
	 * Normally we disable the memory_limit when running admin scripts.
	 * Some scripts may wish to actually set a limit, however, to avoid
	 * blowing up unexpectedly. We also support a --memory-limit option,
	 * to allow sysadmins to explicitly set one if they'd prefer to override
	 * defaults (or for people using Suhosin which yells at you for trying
	 * to disable the limits)
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
	 * Clear all params and arguments.
	 */
	public function clearParamsAndArgs() {
		$this->mOptions = array();
		$this->mArgs = array();
		$this->mInputLoaded = false;
	}

	/**
	 * Process command line arguments
	 * $mOptions becomes an array with keys set to the option names
	 * $mArgs becomes a zero-based array containing the non-option arguments
	 *
	 * @param $self String The name of the script, if any
	 * @param $opts Array An array of options, in form of key=>value
	 * @param $args Array An array of command line arguments
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
		$this->mSelf = array_shift( $argv );

		$options = array();
		$args = array();

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
						$this->error( "\nERROR: $option needs a value after it\n" );
						$this->maybeHelp( true );
					}
					$options[$option] = $param;
				} else {
					$bits = explode( '=', $option, 2 );
					if ( count( $bits ) > 1 ) {
						$option = $bits[0];
						$param = $bits[1];
					} else {
						$param = 1;
					}
					$options[$option] = $param;
				}
			} elseif ( substr( $arg, 0, 1 ) == '-' ) {
				# Short options
				for ( $p = 1; $p < strlen( $arg ); $p++ ) {
					$option = $arg { $p } ;
					if ( isset( $this->mParams[$option]['withArg'] ) && $this->mParams[$option]['withArg'] ) {
						$param = next( $argv );
						if ( $param === false ) {
							$this->error( "\nERROR: $option needs a value after it\n" );
							$this->maybeHelp( true );
						}
						$options[$option] = $param;
					} else {
						$options[$option] = 1;
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
			$this->mBatchSize = $this->getOption( 'batch-size' );
		}
	}

	/**
	 * Maybe show the help.
	 * @param $force boolean Whether to force the help to show, default false
	 */
	protected function maybeHelp( $force = false ) {
		if( !$force && !$this->hasOption( 'help' ) ) {
			return;
		}

		$screenWidth = 80; // TODO: Caculate this!
		$tab = "    ";
		$descWidth = $screenWidth - ( 2 * strlen( $tab ) );

		ksort( $this->mParams );
		$this->mQuiet = false;

		// Description ...
		if ( $this->mDescription ) {
			$this->output( "\n" . $this->mDescription . "\n" );
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
				if ( $k < count( $this->mArgList ) - 1 )
					$output .= ' ';
			}
		}
		$this->output( "$output\n\n" );

		// Parameters description
		foreach ( $this->mParams as $par => $info ) {
			$this->output(
				wordwrap( "$tab--$par: " . $info['desc'], $descWidth,
						"\n$tab$tab" ) . "\n"
			);
		}

		// Arguments description
		foreach ( $this->mArgList as $info ) {
			$openChar = $info['require'] ? '<' : '[';
			$closeChar = $info['require'] ? '>' : ']';
			$this->output(
				wordwrap( "$tab$openChar" . $info['name'] . "$closeChar: " .
					$info['desc'], $descWidth, "\n$tab$tab" ) . "\n"
			);
		}

		die( 1 );
	}

	/**
	 * Handle some last-minute setup here.
	 */
	public function finalSetup() {
		global $wgCommandLineMode, $wgShowSQLErrors, $wgServer;
		global $wgProfiling, $wgDBadminuser, $wgDBadminpassword;
		global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

		# Turn off output buffering again, it might have been turned on in the settings files
		if ( ob_get_level() ) {
			ob_end_flush();
		}
		# Same with these
		$wgCommandLineMode = true;

		# Override $wgServer
		if( $this->hasOption( 'server') ) {
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
				foreach ( $wgDBservers as $i => $server ) {
					$wgDBservers[$i]['user'] = $wgDBuser;
					$wgDBservers[$i]['password'] = $wgDBpassword;
				}
			}
			if ( isset( $wgLBFactoryConf['serverTemplate'] ) ) {
				$wgLBFactoryConf['serverTemplate']['user'] = $wgDBuser;
				$wgLBFactoryConf['serverTemplate']['password'] = $wgDBpassword;
			}
			LBFactory::destroyInstance();
		}

		$this->afterFinalSetup();

		$wgShowSQLErrors = true;
		@set_time_limit( 0 );
		$this->adjustMemoryLimit();

		$wgProfiling = false; // only for Profiler.php mode; avoids OOM errors
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
	 * Do setup specific to WMF
	 */
	public function loadWikimediaSettings() {
		global $IP, $wgNoDBParam, $wgUseNormalUser, $wgConf, $site, $lang;

		if ( empty( $wgNoDBParam ) ) {
			# Check if we were passed a db name
			if ( isset( $this->mOptions['wiki'] ) ) {
				$db = $this->mOptions['wiki'];
			} else {
				$db = array_shift( $this->mArgs );
			}
			list( $site, $lang ) = $wgConf->siteFromDB( $db );

			# If not, work out the language and site the old way
			if ( is_null( $site ) || is_null( $lang ) ) {
				if ( !$db ) {
					$lang = 'aa';
				} else {
					$lang = $db;
				}
				if ( isset( $this->mArgs[0] ) ) {
					$site = array_shift( $this->mArgs );
				} else {
					$site = 'wikipedia';
				}
			}
		} else {
			$lang = 'aa';
			$site = 'wikipedia';
		}

		# This is for the IRC scripts, which now run as the apache user
		# The apache user doesn't have access to the wikiadmin_pass command
		if ( $_ENV['USER'] == 'apache' ) {
		# if ( posix_geteuid() == 48 ) {
			$wgUseNormalUser = true;
		}

		putenv( 'wikilang=' . $lang );

		ini_set( 'include_path', ".:$IP:$IP/includes:$IP/languages:$IP/maintenance" );

		if ( $lang == 'test' && $site == 'wikipedia' ) {
			define( 'TESTWIKI', 1 );
		}
	}

	/**
	 * Generic setup for most installs. Returns the location of LocalSettings
	 * @return String
	 */
	public function loadSettings() {
		global $wgWikiFarm, $wgCommandLineMode, $IP;

		$wgWikiFarm = false;
		if ( isset( $this->mOptions['conf'] ) ) {
			$settingsFile = $this->mOptions['conf'];
		} else if ( defined("MW_CONFIG_FILE") ) {
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
						"Use --conf to specify it." , true );
		}
		$wgCommandLineMode = true;
		return $settingsFile;
	}

	/**
	 * Support function for cleaning up redundant text records
	 * @param $delete Boolean: whether or not to actually delete the records
	 * @author Rob Church <robchur@gmail.com>
	 */
	public function purgeRedundantText( $delete = true ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		$tbl_arc = $dbw->tableName( 'archive' );
		$tbl_rev = $dbw->tableName( 'revision' );
		$tbl_txt = $dbw->tableName( 'text' );

		# Get "active" text records from the revisions table
		$this->output( 'Searching for active text records in revisions table...' );
		$res = $dbw->query( "SELECT DISTINCT rev_text_id FROM $tbl_rev" );
		foreach ( $res as $row ) {
			$cur[] = $row->rev_text_id;
		}
		$this->output( "done.\n" );

		# Get "active" text records from the archive table
		$this->output( 'Searching for active text records in archive table...' );
		$res = $dbw->query( "SELECT DISTINCT ar_text_id FROM $tbl_arc" );
		foreach ( $res as $row ) {
			$cur[] = $row->ar_text_id;
		}
		$this->output( "done.\n" );

		# Get the IDs of all text records not in these sets
		$this->output( 'Searching for inactive text records...' );
		$set = implode( ', ', $cur );
		$res = $dbw->query( "SELECT old_id FROM $tbl_txt WHERE old_id NOT IN ( $set )" );
		$old = array();
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
			$set = implode( ', ', $old );
			$dbw->query( "DELETE FROM $tbl_txt WHERE old_id IN ( $set )" );
			$this->output( "done.\n" );
		}

		# Done
		$dbw->commit();
	}

	/**
	 * Get the maintenance directory.
	 */
	protected function getDir() {
		return dirname( __FILE__ );
	}

	/**
	 * Get the list of available maintenance scripts. Note
	 * that if you call this _before_ calling doMaintenance
	 * you won't have any extensions in it yet
	 * @return Array
	 */
	public static function getMaintenanceScripts() {
		global $wgMaintenanceScripts;
		return $wgMaintenanceScripts + self::getCoreScripts();
	}

	/**
	 * Return all of the core maintenance scripts
	 * @return array
	 */
	protected static function getCoreScripts() {
		if ( !self::$mCoreScripts ) {
			self::disableSetup();
			$paths = array(
				dirname( __FILE__ ),
				dirname( __FILE__ ) . '/gearman',
				dirname( __FILE__ ) . '/language',
				dirname( __FILE__ ) . '/storage',
			);
			self::$mCoreScripts = array();
			foreach ( $paths as $p ) {
				$handle = opendir( $p );
				while ( ( $file = readdir( $handle ) ) !== false ) {
					if ( $file == 'Maintenance.php' ) {
						continue;
					}
					$file = $p . '/' . $file;
					if ( is_dir( $file ) || !strpos( $file, '.php' ) ||
						( strpos( file_get_contents( $file ), '$maintClass' ) === false ) ) {
						continue;
					}
					require( $file );
					$vars = get_defined_vars();
					if ( array_key_exists( 'maintClass', $vars ) ) {
						self::$mCoreScripts[$vars['maintClass']] = $file;
					}
				}
				closedir( $handle );
			}
		}
		return self::$mCoreScripts;
	}

	/**
	 * Lock the search index
	 * @param &$db Database object
	 */
	private function lockSearchindex( &$db ) {
		$write = array( 'searchindex' );
		$read = array( 'page', 'revision', 'text', 'interwiki', 'l10n_cache' );
		$db->lockTables( $read, $write, __CLASS__ . '::' . __METHOD__ );
	}

	/**
	 * Unlock the tables
	 * @param &$db Database object
	 */
	private function unlockSearchindex( &$db ) {
		$db->unlockTables(  __CLASS__ . '::' . __METHOD__ );
	}

	/**
	 * Unlock and lock again
	 * Since the lock is low-priority, queued reads will be able to complete
	 * @param &$db Database object
	 */
	private function relockSearchindex( &$db ) {
		$this->unlockSearchindex( $db );
		$this->lockSearchindex( $db );
	}

	/**
	 * Perform a search index update with locking
	 * @param $maxLockTime Integer: the maximum time to keep the search index locked.
	 * @param $callback callback String: the function that will update the function.
	 * @param $dbw Database object
	 * @param $results
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
	 * @param $dbw Database: a database write handle
	 * @param $pageId Integer: the page ID to update.
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
			$u = new SearchUpdate( $pageId, $titleObj->getText(), $rev->getText() );
			$u->doUpdate();
			$this->output( "\n" );
		}
		return $title;
	}

	/**
	 * Prompt the console for input
	 * @param $prompt String what to begin the line with, like '> '
	 * @return String response
	 */
	public static function readconsole( $prompt = '> ' ) {
		static $isatty = null;
		if ( is_null( $isatty ) ) {
			$isatty = posix_isatty( 0 /*STDIN*/ );
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
			if ( $st === false ) return false;
			$resp = trim( $st );
			return $resp;
		}
	}

	/**
	 * Emulate readline()
	 * @param $prompt String what to begin the line with, like '> '
	 * @return String
	 */
	private static function readlineEmulation( $prompt ) {
		$bash = Installer::locateExecutableInDefaultPaths( array( 'bash' ) );
		if ( !wfIsWindows() && $bash ) {
			$retval = false;
			$encPrompt = wfEscapeShellArg( $prompt );
			$command = "read -er -p $encPrompt && echo \"\$REPLY\"";
			$encCommand = wfEscapeShellArg( $command );
			$line = wfShellExec( "$bash -c $encCommand", $retval );

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
}

class FakeMaintenance extends Maintenance {
	protected $mSelf = "FakeMaintenanceScript";
	public function execute() {
		return;
	}
}

