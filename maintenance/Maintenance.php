<?php
// Define this so scripts can easily find doMaintenance.php
define( 'DO_MAINTENANCE', dirname(__FILE__) . '/doMaintenance.php' );

// Make sure we're on PHP5 or better
if( version_compare( PHP_VERSION, '5.0.0' ) < 0 ) {
	echo( "Sorry! This version of MediaWiki requires PHP 5; you are running " .
		PHP_VERSION . ".\n\n" .
		"If you are sure you already have PHP 5 installed, it may be installed\n" .
		"in a different path from PHP 4. Check with your system administrator.\n" );
	die();
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
	private $mParams = array();
	
	// Array of desired args
	private $mArgList = array();

	// This is the list of options that were actually passed
	private $mOptions = array();

	// This is the list of arguments that were actually passed
	protected $mArgs = array();
	
	// Name of the script currently running
	protected $mSelf;

	// Special vars for params that are always used
	private $mQuiet = false;
	private $mDbUser, $mDbPass;

	// A description of the script, children should change this
	protected $mDescription = '';
	
	// Have we already loaded our user input?
	private $mInputLoaded = false;
	
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
	 * Default constructor. Children should call this if implementing
	 * their own constructors
	 */
	public function __construct() {
		$this->addDefaultParams();
	}

	/**
	 * Do the actual work. All child classes will need to implement this
	 */
	abstract public function execute();

	/**
	 * Add a parameter to the script. Will be displayed on --help
	 * with the associated description
	 *
	 * @param $name String The name of the param (help, version, etc)
	 * @param $description String The description of the param to show on --help
	 * @param $required boolean Is the param required?
	 * @param $withArg Boolean Is an argument required with this option?
	 */
	protected function addOption( $name, $description, $required = false, $withArg = false ) {
		$this->mParams[ $name ] = array( 'desc' => $description, 'require' => $required, 'withArg' => $withArg );
	}
	
	/**
	 * Checks to see if a particular param exists.
	 * @param $name String The name of the param
	 * @return boolean
	 */
	protected function hasOption( $name ) {
		return isset( $this->mOptions[ $name ] );
	}
	
	/**
	 * Get an option, or return the default
	 * @param $name String The name of the param
	 * @param $default mixed Anything you want, default null
	 * @return mixed
	 */
	protected function getOption( $name, $default = null ) {
		if( $this->hasOption($name) ) {
			return $this->mOptions[$name];
		} else {
			// Set it so we don't have to provide the default again
			$this->mOptions[$name] = $default;
			return $this->mOptions[$name];
		}
	}
	
	/**
	 * Add some args that are needed. Used in formatting help
	 */
	protected function addArgs( $args ) {
		$this->mArgList = array_merge( $this->mArgList, $args );
	}
	
	/**
	 * Does a given argument exist?
	 * @param $argId int The integer value (from zero) for the arg
	 * @return boolean
	 */
	protected function hasArg( $argId = 0 ) {
		return isset( $this->mArgs[ $argId ] ) ;
	}

	/**
	 * Get an argument.
	 * @param $argId int The integer value (from zero) for the arg
	 * @param $default mixed The default if it doesn't exist
	 * @return mixed
	 */
	protected function getArg( $argId = 0, $default = null ) {
		return $this->hasArg($argId) ? $this->mArgs[$argId] : $default;
	}

	/**
	 * Set the batch size.
	 * @param $s int The number of operations to do in a batch
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
	 * @param $length int The number of bytes to read. If null,
	 *        just return the handle. Maintenance::STDIN_ALL returns
	 *        the full length
	 * @return mixed
	 */
	protected function getStdin( $len = null ) {
		if ( $len == Maintenance::STDIN_ALL )
			return file_get_contents( 'php://stdin' );
		$f = fopen( 'php://stdin', 'rt' );
		if( !$len )
			return $f;
		$input = fgets( $f, $len );
		fclose ( $f );
		return rtrim( $input );
	}

	/**
	 * Throw some output to the user. Scripts can call this with no fears,
	 * as we handle all --quiet stuff here
	 * @param $out String The text to show to the user
	 */
	protected function output( $out ) {
		if( $this->mQuiet ) {
			return;
		}
		$f = fopen( 'php://stdout', 'w' );
		fwrite( $f, $out );
		fclose( $f );
	}

	/**
	 * Throw an error to the user. Doesn't respect --quiet, so don't use
	 * this for non-error output
	 * @param $err String The error to display
	 * @param $die boolean If true, go ahead and die out.
	 */
	protected function error( $err, $die = false ) {
		$f = fopen( 'php://stderr', 'w' ); 
		fwrite( $f, $err . "\n" );
		fclose( $f );
		if( $die ) die();
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
	protected function getDbType() {
		return Maintenance::DB_STD;
	}

	/**
	 * Add the default parameters to the scripts
	 */
	private function addDefaultParams() {
		$this->addOption( 'help', "Display this help message" );
		$this->addOption( 'quiet', "Whether to supress non-error output" );
		$this->addOption( 'conf', "Location of LocalSettings.php, if not default", false, true );
		$this->addOption( 'wiki', "For specifying the wiki ID", false, true );
		$this->addOption( 'globals', "Output globals at the end of processing for debugging" );
		// If we support a DB, show the options
		if( $this->getDbType() > 0 ) {
			$this->addOption( 'dbuser', "The DB user to use for this script", false, true );
			$this->addOption( 'dbpass', "The password to use for this script", false, true );
		}
		// If we support $mBatchSize, show the option
		if( $this->mBatchSize ) {
			$this->addOption( 'batch-size', 'Run this many operations ' .
				'per batch, default: ' . $this->mBatchSize , false, true );
		}
	}

	/**
	 * Spawn a child maintenance script. Pass all of the current arguments
	 * to it.
	 * @param $maintClass String A name of a child maintenance class
	 * @param $classFile String Full path of where the child is
	 * @return Maintenance child
	 */
	protected function spawnChild( $maintClass, $classFile = null ) {
		// If we haven't already specified, kill setup procedures
		// for child scripts, we've already got a sane environment
		self::disableSetup();
		
		// Make sure the class is loaded first
		if( !class_exists( $maintClass ) ) {
			if( $classFile ) {
				require_once( $classFile );
			}
			if( !class_exists( $maintClass ) ) {
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
		if( !defined( 'MW_NO_SETUP' ) )
			define( 'MW_NO_SETUP', true );
	}

	/**
	 * Do some sanity checking and basic setup
	 */
	public function setup() {
		global $IP, $wgCommandLineMode, $wgUseNormalUser, $wgRequestTime;

		# Abort if called from a web server
		if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
			$this->error( "This script must be run from the command line", true );
		}

		# Make sure we can handle script parameters
		if( !ini_get( 'register_argc_argv' ) ) {
			$this->error( "Cannot get command line arguments, register_argc_argv is set to false", true );
		}

		if( version_compare( phpversion(), '5.2.4' ) >= 0 ) {
			// Send PHP warnings and errors to stderr instead of stdout.
			// This aids in diagnosing problems, while keeping messages
			// out of redirected output.
			if( ini_get( 'display_errors' ) ) {
				ini_set( 'display_errors', 'stderr' );
			}

			// Don't touch the setting on earlier versions of PHP,
			// as setting it would disable output if you'd wanted it.

			// Note that exceptions are also sent to stderr when
			// command-line mode is on, regardless of PHP version.
		}

		# Set the memory limit
		ini_set( 'memory_limit', -1 );

		$wgRequestTime = microtime(true);

		# Define us as being in Mediawiki
		define( 'MEDIAWIKI', true );

		# Setup $IP, using MW_INSTALL_PATH if it exists
		$IP = strval( getenv('MW_INSTALL_PATH') ) !== ''
			? getenv('MW_INSTALL_PATH')
			: realpath( dirname( __FILE__ ) . '/..' );
		
		$wgCommandLineMode = true;
		# Turn off output buffering if it's on
		@ob_end_flush();

		if (!isset( $wgUseNormalUser ) ) {
			$wgUseNormalUser = false;
		}

		$this->loadParamsAndArgs();
		$this->maybeHelp();
		$this->validateParamsAndArgs();
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
		if( $self ) {
			$this->mSelf = $self;
			$this->mInputLoaded = true;
		}
		if( $opts ) {
			$this->mOptions = $opts;
			$this->mInputLoaded = true;
		}
		if( $args ) {
			$this->mArgs = $args;
			$this->mInputLoaded = true;
		}

		# If we've already loaded input (either by user values or from $argv)
		# skip on loading it again. The array_shift() will corrupt values if
		# it's run again and again
		if( $this->mInputLoaded ) {
			$this->loadSpecialVars();
			return;
		}

		global $argv;
		$this->mSelf = array_shift( $argv );

		$options = array();
		$args = array();

		# Parse arguments
		for( $arg = reset( $argv ); $arg !== false; $arg = next( $argv ) ) {
			if ( $arg == '--' ) {
				# End of options, remainder should be considered arguments
				$arg = next( $argv );
				while( $arg !== false ) {
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
					if( count( $bits ) > 1 ) {
						$option = $bits[0];
						$param = $bits[1];
					} else {
						$param = 1;
					}
					$options[$option] = $param;
				}
			} elseif ( substr( $arg, 0, 1 ) == '-' ) {
				# Short options
				for ( $p=1; $p<strlen( $arg ); $p++ ) {
					$option = $arg{$p};
					if ( $this->mParams[$option]['withArg'] ) {
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
	private function validateParamsAndArgs() {
		# Check to make sure we've got all the required ones
		foreach( $this->mParams as $opt => $info ) {
			if( $info['require'] && !$this->hasOption($opt) ) {
				$this->error( "Param $opt required.", true );
			}
		}

		# Also make sure we've got enough arguments
		if ( count( $this->mArgs ) < count( $this->mArgList ) ) {
			$this->error( "Not enough arguments passed", true );
		}
	}
	
	/**
	 * Handle the special variables that are global to all scripts
	 */
	private function loadSpecialVars() {
		if( $this->hasOption( 'dbuser' ) )
			$this->mDbUser = $this->getOption( 'dbuser' );
		if( $this->hasOption( 'dbpass' ) )
			$this->mDbPass = $this->getOption( 'dbpass' );
		if( $this->hasOption( 'quiet' ) )
			$this->mQuiet = true;
		if( $this->hasOption( 'batch-size' ) )
			$this->mBatchSize = $this->getOption( 'batch-size' );
	}

	/**
	 * Maybe show the help.
	 * @param $force boolean Whether to force the help to show, default false
	 */
	private function maybeHelp( $force = false ) {
		ksort( $this->mParams );
		if( $this->hasOption('help') || in_array( 'help', $this->mArgs ) || $force ) {
			$this->mQuiet = false;
			if( $this->mDescription ) {
				$this->output( "\n" . $this->mDescription . "\n" );
			}
			$this->output( "\nUsage: php " . $this->mSelf );
			if( $this->mParams ) {
				$this->output( " [--" . implode( array_keys( $this->mParams ), "|--" ) . "]" );
			}
			if( $this->mArgList ) {
				$this->output( " <" . implode( $this->mArgList, "> <" ) . ">" );
			}
			$this->output( "\n" );
			foreach( $this->mParams as $par => $info ) {
				$this->output( "\t$par : " . $info['desc'] . "\n" );
			}
			die( 1 );
		}
	}
	
	/**
	 * Handle some last-minute setup here.
	 */
	public function finalSetup() {
		global $wgCommandLineMode, $wgUseNormalUser, $wgShowSQLErrors;
		global $wgTitle, $wgProfiling, $IP, $wgDBadminuser, $wgDBadminpassword;
		global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

		# Turn off output buffering again, it might have been turned on in the settings files
		if( ob_get_level() ) {
			ob_end_flush();
		}
		# Same with these
		$wgCommandLineMode = true;

		# If these were passed, use them
		if( $this->mDbUser )
			$wgDBadminuser = $this->mDbUser;
		if( $this->mDbPass )
			$wgDBadminpassword = $this->mDbPass;

		if ( empty( $wgUseNormalUser ) && isset( $wgDBadminuser ) ) {
			$wgDBuser = $wgDBadminuser;
			$wgDBpassword = $wgDBadminpassword;

			if( $wgDBservers ) {
				foreach ( $wgDBservers as $i => $server ) {
					$wgDBservers[$i]['user'] = $wgDBuser;
					$wgDBservers[$i]['password'] = $wgDBpassword;
				}
			}
			if( isset( $wgLBFactoryConf['serverTemplate'] ) ) {
				$wgLBFactoryConf['serverTemplate']['user'] = $wgDBuser;
				$wgLBFactoryConf['serverTemplate']['password'] = $wgDBpassword;
			}
		}

		if ( defined( 'MW_CMDLINE_CALLBACK' ) ) {
			$fn = MW_CMDLINE_CALLBACK;
			$fn();
		}
	
		$wgShowSQLErrors = true;
		@set_time_limit( 0 );
	
		$wgProfiling = false; // only for Profiler.php mode; avoids OOM errors
	}

	/**
	 * Potentially debug globals. Originally a feature only
	 * for refreshLinks
	 */
	public function globals() {
		if( $this->hasOption( 'globals' ) ) {
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
		#if ( posix_geteuid() == 48 ) {
			$wgUseNormalUser = true;
		}
	
		putenv( 'wikilang=' . $lang );
	
		$DP = $IP;
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
		global $wgWikiFarm, $wgCommandLineMode, $IP, $DP;

		$wgWikiFarm = false;
		if ( isset( $this->mOptions['conf'] ) ) {
			$settingsFile = $this->mOptions['conf'];
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
	
		if ( ! is_readable( $settingsFile ) ) {
			$this->error( "A copy of your installation's LocalSettings.php\n" .
			  			"must exist and be readable in the source directory.", true );
		}
		$wgCommandLineMode = true;
		$DP = $IP;
		return $settingsFile;
	}
	
	/**
	 * Support function for cleaning up redundant text records
	 * @param $delete boolean Whether or not to actually delete the records
	 * @author Rob Church <robchur@gmail.com>
	 */
	protected function purgeRedundantText( $delete = true ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		$tbl_arc = $dbw->tableName( 'archive' );
		$tbl_rev = $dbw->tableName( 'revision' );
		$tbl_txt = $dbw->tableName( 'text' );

		# Get "active" text records from the revisions table
		$this->output( "Searching for active text records in revisions table..." );
		$res = $dbw->query( "SELECT DISTINCT rev_text_id FROM $tbl_rev" );
		while( $row = $dbw->fetchObject( $res ) ) {
			$cur[] = $row->rev_text_id;
		}
		$this->output( "done.\n" );

		# Get "active" text records from the archive table
		$this->output( "Searching for active text records in archive table..." );
		$res = $dbw->query( "SELECT DISTINCT ar_text_id FROM $tbl_arc" );
		while( $row = $dbw->fetchObject( $res ) ) {
			$cur[] = $row->ar_text_id;
		}
		$this->output( "done.\n" );

		# Get the IDs of all text records not in these sets
		$this->output( "Searching for inactive text records..." );
		$set = implode( ', ', $cur );
		$res = $dbw->query( "SELECT old_id FROM $tbl_txt WHERE old_id NOT IN ( $set )" );
		$old = array();
		while( $row = $dbw->fetchObject( $res ) ) {
			$old[] = $row->old_id;
		}
		$this->output( "done.\n" );

		# Inform the user of what we're going to do
		$count = count( $old );
		$this->output( "$count inactive items found.\n" );

		# Delete as appropriate
		if( $delete && $count ) {
			$this->output( "Deleting..." );
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
	 * @return array
	 */
	public static function getMaintenanceScripts() {
		global $wgMaintenanceScripts;
		return $wgMaintenanceScripts + self::getCoreScripts();
	}
	
	/**
	 * Return all of the core maintenance scripts
	 * @return array
	 */
	private static function getCoreScripts() {
		if( !self::$mCoreScripts ) {
			self::disableSetup();
			$paths = array(
				dirname( __FILE__ ),
				dirname( __FILE__ ) . '/gearman',
				dirname( __FILE__ ) . '/language',
				dirname( __FILE__ ) . '/storage',
			);
			self::$mCoreScripts = array();
			foreach( $paths as $p ) {
				$handle = opendir( $p );
				while( ( $file = readdir( $handle ) ) !== false ) {
					if( $file == 'Maintenance.php' )
						continue;
					$file = $p . '/' . $file;
					if( is_dir( $file ) || !strpos( $file, '.php' ) || 
						( strpos( file_get_contents( $file ), '$maintClass' ) === false ) ) {
						continue;
					}
					require( $file );
					$vars = get_defined_vars();
					if( array_key_exists( 'maintClass', $vars ) ) {
						self::$mCoreScripts[$vars['maintClass']] = $file;
					}
				}
				closedir( $handle );
			}
		}
		return self::$mCoreScripts;
	}
}
