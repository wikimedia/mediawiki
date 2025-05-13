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

namespace MediaWiki\Maintenance;

use Closure;
use ExecutableFinder;
use Generator;
use MediaWiki;
use MediaWiki\Config\Config;
use MediaWiki\Debug\MWDebug;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Shell\Shell;
use MediaWiki\User\User;
use StatusValue;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\IReadableDatabase;

// NOTE: MaintenanceParameters is needed in the constructor, and we may not have
//       autoloading enabled at this point?
require_once __DIR__ . '/MaintenanceParameters.php';

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
 * WARNING: the constructor, MaintenanceRunner::shouldExecute(), setup(), finalSetup(),
 * and getName() are called before Setup.php is complete, which means most of the common
 * infrastructure, like logging or autoloading, is not available. Be careful when changing
 * these methods or the ones called from them. Likewise, be careful with the constructor
 * when subclassing. MediaWikiServices instance is not yet available at this point.
 *
 * @stable to extend
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
	public const STDIN_ALL = -1;

	// Help group names
	public const SCRIPT_DEPENDENT_PARAMETERS = 'Common options';
	public const GENERIC_MAINTENANCE_PARAMETERS = 'Script runner options';

	/**
	 * @var MaintenanceParameters
	 */
	protected $parameters;

	/**
	 * Empty.
	 * @deprecated since 1.39, use $this->parameters instead.
	 * @var array[]
	 * @phan-var array<string,array{desc:string,require:bool,withArg:string,shortName:string,multiOccurrence:bool}>
	 */
	protected $mParams = [];

	/**
	 * @var array This is the list of options that were actually passed
	 * @deprecated since 1.39, use {@see addOption} instead.
	 */
	protected $mOptions = [];

	/**
	 * @var array This is the list of arguments that were actually passed
	 * @deprecated since 1.39, use {@see addArg} instead.
	 */
	protected $mArgs = [];

	/** @var string|null Name of the script currently running */
	protected $mSelf;

	/** @var bool Special vars for params that are always used */
	protected $mQuiet = false;
	protected ?string $mDbUser = null;
	protected ?string $mDbPass = null;

	/**
	 * @var string A description of the script, children should change this via addDescription()
	 * @deprecated since 1.39, use {@see addDescription} instead.
	 */
	protected $mDescription = '';

	/**
	 * @var bool Have we already loaded our user input?
	 * @deprecated since 1.39, treat as private to the Maintenance base class
	 */
	protected $mInputLoaded = false;

	/**
	 * Batch size. If a script supports this, they should set
	 * a default with setBatchSize()
	 *
	 * @var int|null
	 */
	protected $mBatchSize = null;

	/**
	 * Used by getDB() / setDB()
	 * @var IMaintainableDatabase|null
	 */
	private $mDb = null;

	/** @var float UNIX timestamp */
	private $lastReplicationWait = 0.0;

	/**
	 * Used when creating separate schema files.
	 * @var resource|null
	 */
	public $fileHandle;

	/** @var HookContainer|null */
	private $hookContainer;

	/** @var HookRunner|null */
	private $hookRunner;

	/**
	 * Accessible via getConfig()
	 *
	 * @var Config|null
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
	 * @deprecated since 1.39, use $this->getParameters()->getOptionsSequence() instead.
	 * @var array
	 */
	public $orderedOptions = [];

	/**
	 * @var ILBFactory|null Injected DB connection manager (e.g. LBFactorySingle); null if none
	 */
	private ?ILBFactory $lbFactory = null;

	/**
	 * Default constructor. Children should call this *first* if implementing
	 * their own constructors
	 *
	 * @stable to call
	 */
	public function __construct() {
		$this->parameters = new MaintenanceParameters();
		$this->mOptions =& $this->parameters->getFieldReference( 'mOptions' );
		$this->orderedOptions =& $this->parameters->getFieldReference( 'optionsSequence' );
		$this->mArgs =& $this->parameters->getFieldReference( 'mArgs' );
		$this->addDefaultParams();
	}

	/**
	 * @since 1.39
	 * @return MaintenanceParameters
	 */
	public function getParameters() {
		return $this->parameters;
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
	 * Whether this script can run without LocalSettings.php. Scripts that need to be able
	 * to run when MediaWiki has not been installed should override this to return true.
	 * Scripts that return true from this method must be able to function without
	 * a storage backend. When no LocalSettings.php file is present, any attempt to access
	 * the database will fail with a fatal error.
	 *
	 * @note Subclasses that override this method to return true should also override
	 * getDbType() to return self::DB_NONE, unless they are going to use the database
	 * connection when it is available.
	 *
	 * @see getDbType()
	 * @since 1.40
	 * @stable to override
	 * @return bool
	 */
	public function canExecuteWithoutLocalSettings(): bool {
		return false;
	}

	/**
	 * Checks to see if a particular option in supported.  Normally this means it
	 * has been registered by the script via addOption.
	 * @param string $name The name of the option
	 * @return bool true if the option exists, false otherwise
	 */
	protected function supportsOption( $name ) {
		return $this->parameters->supportsOption( $name );
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
		$this->parameters->addOption(
			$name,
			$description,
			$required,
			$withArg,
			$shortName,
			$multiOccurrence
		);
	}

	/**
	 * Checks to see if a particular option was set.
	 *
	 * @param string $name The name of the option
	 * @return bool
	 */
	protected function hasOption( $name ) {
		return $this->parameters->hasOption( $name );
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
		return $this->parameters->getOption( $name, $default );
	}

	/**
	 * Add some args that are needed
	 * @param string $arg Name of the arg, like 'start'
	 * @param string $description Short description of the arg
	 * @param bool $required Is this required?
	 * @param bool $multi Does it allow multiple values? (Last arg only)
	 */
	protected function addArg( $arg, $description, $required = true, $multi = false ) {
		$this->parameters->addArg( $arg, $description, $required, $multi );
	}

	/**
	 * Remove an option.  Useful for removing options that won't be used in your script.
	 * @param string $name The option to remove.
	 */
	protected function deleteOption( $name ) {
		$this->parameters->deleteOption( $name );
	}

	/**
	 * Sets whether to allow unregistered options, which are options passed to
	 * a script that do not match an expected parameter.
	 * @param bool $allow Should we allow?
	 */
	protected function setAllowUnregisteredOptions( $allow ) {
		$this->parameters->setAllowUnregisteredOptions( $allow );
	}

	/**
	 * Set the description text.
	 * @param string $text The text of the description
	 */
	protected function addDescription( $text ) {
		$this->parameters->setDescription( $text );
	}

	/**
	 * Does a given argument exist?
	 * @param int|string $argId The index (from zero) of the argument, or
	 *                   the name declared for the argument by addArg().
	 * @return bool
	 */
	protected function hasArg( $argId = 0 ) {
		return $this->parameters->hasArg( $argId );
	}

	/**
	 * Get an argument.
	 * @param int|string $argId The index (from zero) of the argument, or
	 *                   the name declared for the argument by addArg().
	 * @param mixed|null $default The default if it doesn't exist
	 * @return mixed
	 * @return-taint none
	 */
	protected function getArg( $argId = 0, $default = null ) {
		return $this->parameters->getArg( $argId, $default );
	}

	/**
	 * Get arguments.
	 * @since 1.40
	 *
	 * @param int|string $offset The index (from zero) of the first argument, or
	 *                   the name declared for the argument by addArg().
	 * @return string[]
	 */
	protected function getArgs( $offset = 0 ) {
		return $this->parameters->getArgs( $offset );
	}

	/**
	 * Get the name of an argument.
	 * @since 1.43
	 *
	 * @param int $argId The index (from zero) of the argument.
	 *
	 * @return string|null The name of the argument, or null if the argument does not exist.
	 */
	protected function getArgName( int $argId ): ?string {
		return $this->parameters->getArgName( $argId );
	}

	/**
	 * Programmatically set the value of the given option.
	 * Useful for setting up child scripts, see runChild().
	 *
	 * @since 1.39
	 *
	 * @param string $name
	 * @param mixed|null $value
	 */
	public function setOption( string $name, $value ): void {
		$this->parameters->setOption( $name, $value );
	}

	/**
	 * Programmatically set the value of the given argument.
	 * Useful for setting up child scripts, see runChild().
	 *
	 * @since 1.39
	 *
	 * @param string|int $argId Arg index or name
	 * @param mixed|null $value
	 */
	public function setArg( $argId, $value ): void {
		$this->parameters->setArg( $argId, $value );
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
			if ( $this->supportsOption( 'batch-size' ) ) {
				// This seems a little ugly...
				$this->parameters->assignGroup( self::SCRIPT_DEPENDENT_PARAMETERS, [ 'batch-size' ] );
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
	 * @param int|null $len The number of bytes to read. If null, just
	 * return the handle. Maintenance::STDIN_ALL returns the full content
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
	 * @stable to override
	 * @param string $out The text to show to the user
	 * @param string|null $channel Unique identifier for the channel. See function outputChanneled.
	 */
	protected function output( $out, $channel = null ) {
		// This is sometimes called very early, before Setup.php is included.
		if ( defined( 'MW_SERVICE_BOOTSTRAP_COMPLETE' ) ) {
			// Flush stats periodically in long-running CLI scripts to avoid OOM (T181385)
			$statsFactory = $this->getServiceContainer()->getStatsFactory();
			MediaWiki::emitBufferedStats( $statsFactory );
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
	 * @stable to override
	 * @param string|StatusValue $err The error to display
	 * @param int $die Deprecated since 1.31, use Maintenance::fatalError() instead
	 */
	protected function error( $err, $die = 0 ) {
		if ( intval( $die ) !== 0 ) {
			wfDeprecated( __METHOD__ . '( $err, $die )', '1.31' );
			$this->fatalError( $err, intval( $die ) );
		}
		if ( $err instanceof StatusValue ) {
			foreach ( [ 'warning' => 'Warning: ', 'error' => 'Error: ' ] as $type => $prefix ) {
				foreach ( $err->getMessages( $type ) as $msg ) {
					$this->error(
						$prefix . wfMessage( $msg )
							->inLanguage( 'en' )
							->useDatabase( false )
							->text()
					);
				}
			}
			return;
		}
		$this->outputChanneled( false );
		if (
			( PHP_SAPI == 'cli' || PHP_SAPI == 'phpdbg' ) &&
			!defined( 'MW_PHPUNIT_TEST' )
		) {
			fwrite( STDERR, $err . "\n" );
		} else {
			print $err . "\n";
		}
	}

	/**
	 * Output a message and terminate the current script.
	 *
	 * @stable to override
	 * @param string|StatusValue $msg Error message
	 * @param int $exitCode PHP exit status. Should be in range 1-254.
	 * @since 1.31
	 * @return never
	 */
	protected function fatalError( $msg, $exitCode = 1 ) {
		$this->error( $msg );
		// If running PHPUnit tests we don't want to call exit, as it will end the test suite early.
		// Instead, throw an exception that will still cause the relevant test to fail if the ::fatalError
		// call was not expected.
		if ( defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MaintenanceFatalError( $exitCode );
		} else {
			exit( $exitCode );
		}
	}

	/** @var bool */
	private $atLineStart = true;
	/** @var string|null */
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
	 * @param string|false $msg The message without trailing newline
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
	 *
	 * @note Subclasses that override this method to return self::DB_NONE should
	 * also override canExecuteWithoutLocalSettings() to return true, unless they
	 * need the wiki to be set up for reasons beyond access to a database connection.
	 *
	 * @see canExecuteWithoutLocalSettings()
	 * @stable to override
	 * @return int
	 */
	public function getDbType() {
		return self::DB_STD;
	}

	/**
	 * Add the default parameters to the scripts
	 */
	protected function addDefaultParams() {
		# Generic (non-script-dependent) options:

		$this->addOption( 'help', 'Display this help message', false, false, 'h' );
		$this->addOption( 'quiet', 'Whether to suppress non-error output', false, false, 'q' );

		# Save generic options to display them separately in help
		$generic = [ 'help', 'quiet' ];
		$this->parameters->assignGroup( self::GENERIC_MAINTENANCE_PARAMETERS, $generic );

		# Script-dependent options:

		// If we support a DB, show the options
		if ( $this->getDbType() > 0 ) {
			$this->addOption( 'dbuser', 'The DB user to use for this script', false, true );
			$this->addOption( 'dbpass', 'The password to use for this script', false, true );
			$this->addOption( 'dbgroupdefault', 'The default DB group to use.', false, true );
		}

		# Save additional script-dependent options to display
		# them separately in help
		$dependent = array_diff(
			$this->parameters->getOptionNames(),
			$generic
		);
		$this->parameters->assignGroup( self::SCRIPT_DEPENDENT_PARAMETERS, $dependent );
	}

	/**
	 * @since 1.24
	 * @stable to override
	 * @return Config
	 */
	public function getConfig() {
		if ( $this->config === null ) {
			$this->config = $this->getServiceContainer()->getMainConfig();
		}

		return $this->config;
	}

	/**
	 * Returns the main service container.
	 *
	 * @since 1.40
	 * @return MediaWikiServices
	 */
	protected function getServiceContainer() {
		return MediaWikiServices::getInstance();
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
			if ( count( $missing ) === 1 ) {
				$msg = 'The "' . $missing[ 0 ] . '" extension must be installed for this script to run. '
					. 'Please enable it and then try again.';
			} else {
				$msg = 'The following extensions must be installed for this script to run: "'
					. implode( '", "', $missing ) . '". Please enable them and then try again.';
			}
			$this->fatalError( $msg );
		}
	}

	/**
	 * Returns an instance of the given maintenance script, with all of the current arguments
	 * passed to it.
	 *
	 * Callers are expected to run the returned maintenance script instance by calling {@link Maintenance::execute}
	 *
	 * @deprecated Since 1.43. Use {@link Maintenance::createChild} instead. This method is an alias to that method.
	 * @param string $maintClass A name of a child maintenance class
	 * @param string|null $classFile Full path of where the child is
	 * @return Maintenance The created instance, which the caller is expected to run by calling
	 *   {@link Maintenance::execute} on the returned object.
	 */
	public function runChild( $maintClass, $classFile = null ) {
		MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'runChild', '1.43' );
		return self::createChild( $maintClass, $classFile );
	}

	/**
	 * Returns an instance of the given maintenance script, with all of the current arguments
	 * passed to it.
	 *
	 * Callers are expected to run the returned maintenance script instance by calling {@link Maintenance::execute}
	 *
	 * @param string $maintClass A name of a child maintenance class
	 * @param string|null $classFile Full path of where the child is
	 * @stable to override
	 * @return Maintenance The created instance, which the caller is expected to run by calling
	 *   {@link Maintenance::execute} on the returned object.
	 */
	public function createChild( string $maintClass, ?string $classFile = null ): Maintenance {
		// Make sure the class is loaded first
		if ( !class_exists( $maintClass ) ) {
			if ( $classFile ) {
				require_once $classFile;
			}
			if ( !class_exists( $maintClass ) ) {
				$this->fatalError( "Cannot spawn child: $maintClass" );
			}
		}

		/**
		 * @var Maintenance $child
		 */
		$child = new $maintClass();
		$child->loadParamsAndArgs(
			$this->mSelf,
			$this->parameters->getOptions(),
			$this->parameters->getArgs()
		);
		if ( $this->mDb !== null ) {
			$child->setDB( $this->mDb );
		}
		if ( $this->lbFactory !== null ) {
			$child->setLBFactory( $this->lbFactory );
		}

		return $child;
	}

	/**
	 * Provides subclasses with an opportunity to perform initial checks.
	 * @stable to override
	 */
	public function setup() {
		// noop
	}

	/**
	 * Override memory_limit from php.ini on maintenance scripts.
	 *
	 * This defaults to max/unlimited, but some scripts may wish to set a lower limit,
	 * to avoid blowing up unexpectedly and/or taking available memory for other
	 * processes.
	 *
	 * @stable to override
	 * @return string|int Must be a shorthand string like "50M" or "max", or a number
	 * of bytes (-1 for unlimited) passed to `ini_set( 'memory_limit' )`.
	 */
	public function memoryLimit() {
		return 'max';
	}

	/**
	 * Clear all params and arguments.
	 */
	public function clearParamsAndArgs() {
		$this->parameters->clear();
		$this->mInputLoaded = false;
	}

	/**
	 * @since 1.40
	 * @internal
	 * @param string $name
	 */
	public function setName( string $name ) {
		$this->mSelf = $name;
		$this->parameters->setName( $this->mSelf );
	}

	/**
	 * Load params and arguments from a given array
	 * of command-line arguments
	 *
	 * @since 1.27
	 * @param array $argv The argument array, not including the script itself.
	 */
	public function loadWithArgv( $argv ) {
		if ( $this->mDescription ) {
			$this->parameters->setDescription( $this->mDescription );
		}

		$this->parameters->loadWithArgv( $argv );

		if ( $this->parameters->hasErrors() ) {
			$errors = "\nERROR: " . implode( "\nERROR: ", $this->parameters->getErrors() ) . "\n";
			$this->error( $errors );
			$this->maybeHelp( true );
		}

		$this->loadSpecialVars();
		$this->mInputLoaded = true;
	}

	/**
	 * Process command line arguments when running as a child script
	 *
	 * @param string|null $self The name of the script, if any
	 * @param array|null $opts An array of options, in form of key=>value
	 * @param array|null $args An array of command line arguments
	 */
	public function loadParamsAndArgs( $self = null, $opts = null, $args = null ) {
		# If we were given opts or args, set those and return early
		if ( $self !== null || $opts !== null || $args !== null ) {
			if ( $self !== null ) {
				$this->mSelf = $self;
				$this->parameters->setName( $self );
			}
			$this->parameters->setOptionsAndArgs( $opts ?? [], $args ?? [] );
			$this->mInputLoaded = true;
		}

		# If we've already loaded input (either by user values or from $argv)
		# skip on loading it again.
		if ( $this->mInputLoaded ) {
			$this->loadSpecialVars();

			return;
		}

		global $argv;
		$this->mSelf = $argv[0];
		$this->parameters->setName( $this->mSelf );
		$this->loadWithArgv( array_slice( $argv, 1 ) );
	}

	/**
	 * Run some validation checks on the params, etc
	 * @stable to override
	 */
	public function validateParamsAndArgs() {
		$valid = $this->parameters->validate();

		$this->maybeHelp( !$valid );
	}

	/**
	 * Handle the special variables that are global to all scripts
	 * @stable to override
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

		if ( $this->parameters->hasErrors() && !$this->hasOption( 'help' ) ) {
			$errors = "\nERROR: " . implode( "\nERROR: ", $this->parameters->getErrors() ) . "\n";
			$this->error( $errors );
		}

		$this->showHelp();
		$this->fatalError( '' );
	}

	/**
	 * Definitely show the help. Does not exit.
	 */
	protected function showHelp() {
		$this->mQuiet = false;
		$help = $this->parameters->getHelp();
		$this->output( $help );
	}

	/**
	 * Handle some last-minute setup here.
	 *
	 * @stable to override
	 *
	 * @param SettingsBuilder $settingsBuilder
	 */
	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		$config = $settingsBuilder->getConfig();
		$overrides = [];
		$overrides['DBadminuser'] = $config->get( MainConfigNames::DBadminuser );
		$overrides['DBadminpassword'] = $config->get( MainConfigNames::DBadminpassword );

		# Turn off output buffering again, it might have been turned on in the settings files
		if ( ob_get_level() ) {
			ob_end_flush();
		}

		# Override $wgServer
		if ( $this->hasOption( 'server' ) ) {
			$overrides['Server'] = $this->getOption( 'server', $config->get( MainConfigNames::Server ) );
		}

		# If these were passed, use them
		if ( $this->mDbUser ) {
			$overrides['DBadminuser'] = $this->mDbUser;
		}
		if ( $this->mDbPass ) {
			$overrides['DBadminpassword'] = $this->mDbPass;
		}
		if ( $this->hasOption( 'dbgroupdefault' ) ) {
			$overrides['DBDefaultGroup'] = $this->getOption( 'dbgroupdefault', null );
			// TODO: once MediaWikiServices::getInstance() starts throwing exceptions
			// and not deprecation warnings for premature access to service container,
			// we can remove this line. This method is called before Setup.php,
			// so it would be guaranteed DBLoadBalancerFactory is not yet initialized.
			if ( MediaWikiServices::hasInstance() ) {
				$service = $this->getServiceContainer()->peekService( 'DBLoadBalancerFactory' );
				if ( $service ) {
					$service->destroy();
				}
			}
		}

		if ( $this->getDbType() == self::DB_ADMIN && isset( $overrides[ 'DBadminuser' ] ) ) {
			$overrides['DBuser'] = $overrides[ 'DBadminuser' ];
			$overrides['DBpassword'] = $overrides[ 'DBadminpassword' ];

			/** @var array $dbServers */
			$dbServers = $config->get( MainConfigNames::DBservers );
			if ( $dbServers ) {
				foreach ( $dbServers as $i => $server ) {
					$dbServers[$i]['user'] = $overrides['DBuser'];
					$dbServers[$i]['password'] = $overrides['DBpassword'];
				}
				$overrides['DBservers'] = $dbServers;
			}

			$lbFactoryConf = $config->get( MainConfigNames::LBFactoryConf );
			if ( isset( $lbFactoryConf['serverTemplate'] ) ) {
				$lbFactoryConf['serverTemplate']['user'] = $overrides['DBuser'];
				$lbFactoryConf['serverTemplate']['password'] = $overrides['DBpassword'];
				$overrides['LBFactoryConf'] = $lbFactoryConf;
			}

			// TODO: once MediaWikiServices::getInstance() starts throwing exceptions
			// and not deprecation warnings for premature access to service container,
			// we can remove this line. This method is called before Setup.php,
			// so it would be guaranteed DBLoadBalancerFactory is not yet initialized.
			if ( MediaWikiServices::hasInstance() ) {
				$service = $this->getServiceContainer()->peekService( 'DBLoadBalancerFactory' );
				if ( $service ) {
					$service->destroy();
				}
			}
		}

		$this->afterFinalSetup();

		$overrides['ShowExceptionDetails'] = true;
		$overrides['ShowHostname'] = true;

		ini_set( 'max_execution_time', '0' );
		$settingsBuilder->putConfigValues( $overrides );
	}

	/**
	 * Override to perform any required operation at the end of initialisation
	 * @stable to override
	 */
	protected function afterFinalSetup() {
	}

	/**
	 * Support function for cleaning up redundant text records
	 * @param bool $delete Whether or not to actually delete the records
	 * @author Rob Church <robchur@gmail.com>
	 */
	public function purgeRedundantText( $delete = true ) {
		# Data should come off the master, wrapped in a transaction
		$dbw = $this->getPrimaryDB();
		$this->beginTransaction( $dbw, __METHOD__ );

		# Get "active" text records via the content table
		$cur = [];
		$this->output( 'Searching for active text records via contents table...' );
		$res = $dbw->newSelectQueryBuilder()
			->select( 'content_address' )
			->distinct()
			->from( 'content' )
			->caller( __METHOD__ )->fetchResultSet();
		$blobStore = $this->getServiceContainer()->getBlobStore();
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
		$textTableQueryBuilder = $dbw->newSelectQueryBuilder()
			->select( 'old_id' )
			->distinct()
			->from( 'text' );
		if ( count( $cur ) ) {
			$textTableQueryBuilder->where( $dbw->expr( 'old_id', '!=', $cur ) );
		}
		$res = $textTableQueryBuilder
			->caller( __METHOD__ )
			->fetchResultSet();
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
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'text' )
				->where( [ 'old_id' => $old ] )
				->caller( __METHOD__ )
				->execute();
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
	 * For simple cases, use ::getReplicaDB() or ::getPrimaryDB() instead.
	 *
	 * @stable to override
	 *
	 * @param int $db DB index (DB_REPLICA/DB_PRIMARY)
	 * @param string|string[] $groups default: empty array
	 * @param string|bool $dbDomain default: current wiki
	 * @return IMaintainableDatabase
	 */
	protected function getDB( $db, $groups = [], $dbDomain = false ) {
		if ( $this->mDb === null ) {
			return $this->getServiceContainer()
				->getDBLoadBalancerFactory()
				->getMainLB( $dbDomain )
				->getMaintenanceConnectionRef( $db, $groups, $dbDomain );
		}

		return $this->mDb;
	}

	/**
	 * Sets database object to be returned by getDB().
	 * @stable to override
	 *
	 * @param IMaintainableDatabase $db
	 */
	public function setDB( IMaintainableDatabase $db ) {
		$this->mDb = $db;
	}

	/**
	 * @return IReadableDatabase
	 * @since 1.42
	 */
	protected function getReplicaDB(): IReadableDatabase {
		return $this->getLBFactory()->getReplicaDatabase();
	}

	/**
	 * @return IDatabase
	 * @since 1.42
	 */
	protected function getPrimaryDB(): IDatabase {
		return $this->getLBFactory()->getPrimaryDatabase();
	}

	/**
	 * @internal
	 * @param ILBFactory $lbFactory LBFactory to inject in place of the service instance
	 * @return void
	 */
	public function setLBFactory( ILBFactory $lbFactory ) {
		$this->lbFactory = $lbFactory;
	}

	/**
	 * @return ILBFactory Injected LBFactory, if any, the service instance, otherwise
	 */
	private function getLBFactory() {
		$this->lbFactory ??= $this->getServiceContainer()->getDBLoadBalancerFactory();

		return $this->lbFactory;
	}

	/**
	 * Begin a transaction on a DB handle
	 *
	 * Maintenance scripts should call this method instead of {@link IDatabase::begin()}.
	 * Use of this method makes it clear that the caller is a maintenance script, which has
	 * the outermost transaction scope needed to explicitly begin transactions.
	 *
	 * This method makes it clear that begin() is called from a maintenance script,
	 * which has outermost scope. This is safe, unlike $dbw->begin() called in other places.
	 *
	 * Use this method for scripts with direct, straightforward, control of all writes.
	 *
	 * @param IDatabase $dbw
	 * @param string $fname Caller name
	 * @since 1.27
	 * @deprecated Since 1.44 Use {@link beginTransactionRound()} instead
	 */
	protected function beginTransaction( IDatabase $dbw, $fname ) {
		$dbw->begin( $fname );
	}

	/**
	 * Commit the transaction on a DB handle and wait for replica DB servers to catch up
	 *
	 * This method also triggers {@link DeferredUpdates::tryOpportunisticExecute()}.
	 *
	 * Maintenance scripts should call this method instead of {@link IDatabase::commit()}.
	 * Use of this method makes it clear that the caller is a maintenance script, which has
	 * the outermost transaction scope needed to explicitly commit transactions.
	 *
	 * Use this method for scripts with direct, straightforward, control of all writes.
	 *
	 * @param IDatabase $dbw
	 * @param string $fname Caller name
	 * @return bool Whether the replication wait succeeded
	 * @since 1.27
	 * @deprecated Since 1.44 Use {@link commitTransactionRound()} instead
	 */
	protected function commitTransaction( IDatabase $dbw, $fname ) {
		$dbw->commit( $fname );

		return $this->waitForReplication();
	}

	/**
	 * Rollback the transaction on a DB handle
	 *
	 * Maintenance scripts should call this method instead of {@link IDatabase::rollback()}.
	 * Use of this method makes it clear that the caller is a maintenance script, which has
	 * the outermost transaction scope needed to explicitly roll back transactions.
	 *
	 * Use this method for scripts with direct, straightforward, control of all writes.
	 *
	 * @param IDatabase $dbw
	 * @param string $fname Caller name
	 * @since 1.27
	 * @deprecated Since 1.44 Use {@link rollbackTransactionRound()} instead
	 */
	protected function rollbackTransaction( IDatabase $dbw, $fname ) {
		$dbw->rollback( $fname );
	}

	/**
	 * Wait for replica DB servers to catch up
	 *
	 * Use this method after performing a batch of autocommit writes inscripts with direct,
	 * straightforward, control of all writes.
	 *
	 * @note Since 1.39, this also calls LBFactory::autoReconfigure().
	 *
	 * @return bool Whether the replication wait succeeded
	 * @since 1.36
	 * @deprecated Since 1.44 Batch writes and use {@link commitTransactionRound()} instead
	 */
	protected function waitForReplication() {
		$lbFactory = $this->getLBFactory();

		$waitSucceeded = $lbFactory->waitForReplication(
			[ 'timeout' => 30, 'ifWritesSince' => $this->lastReplicationWait ]
		);
		$this->lastReplicationWait = microtime( true );

		// If possible, apply changes to the database configuration.
		// The primary use case for this is taking replicas out of rotation.
		// Long-running scripts may otherwise keep connections to
		// de-pooled database hosts, and may even re-connect to them.
		// If no config callback was configured, this has no effect.
		$lbFactory->autoReconfigure();

		// Periodically run any deferred updates that accumulate
		DeferredUpdates::tryOpportunisticExecute();
		// Flush stats periodically in long-running CLI scripts to avoid OOM (T181385)
		MediaWikiEntryPoint::emitBufferedStats(
			$this->getServiceContainer()->getStatsFactory()
		);

		return $waitSucceeded;
	}

	/**
	 * Start a transactional batch of DB operations
	 *
	 * Use this method for scripts that split up their work into logical transactions.
	 *
	 * This method is suitable even for scripts lacking direct, straightforward, control of
	 * all writes. Such scripts might invoke complex methods of service objects, which might
	 * easily touch multiple DB servers. This method proves the usual best-effort distributed
	 * transactions that DBO_DEFAULT provides during web requests.
	 *
	 * @see ILBfactory::beginPrimaryChanges()
	 *
	 * @param string $fname Caller name
	 * @return void
	 * @since 1.44
	 */
	protected function beginTransactionRound( $fname ) {
		$lbFactory = $this->getLBFactory();

		$lbFactory->beginPrimaryChanges( $fname );
	}

	/**
	 * Commit a transactional batch of DB operations and wait for replica DB servers to catch up
	 *
	 * Use this method for scripts that split up their work into logical transactions.
	 *
	 * This method also triggers {@link DeferredUpdates::tryOpportunisticExecute()}.
	 *
	 * @see ILBfactory::commitPrimaryChanges()
	 *
	 * @param string $fname Caller name
	 * @return bool Whether the replication wait succeeded
	 * @since 1.44
	 */
	protected function commitTransactionRound( $fname ) {
		$lbFactory = $this->getLBFactory();

		$lbFactory->commitPrimaryChanges( $fname );

		$waitSucceeded = $lbFactory->waitForReplication(
			[ 'timeout' => 30, 'ifWritesSince' => $this->lastReplicationWait ]
		);
		$this->lastReplicationWait = microtime( true );

		// Periodically run any deferred updates that accumulate
		DeferredUpdates::tryOpportunisticExecute();
		// Flush stats periodically in long-running CLI scripts to avoid OOM (T181385)
		MediaWikiEntryPoint::emitBufferedStats(
			$this->getServiceContainer()->getStatsFactory()
		);

		// If possible, apply changes to the database configuration.
		// The primary use case for this is taking replicas out of rotation.
		// Long-running scripts may otherwise keep connections to
		// de-pooled database hosts, and may even re-connect to them.
		// If no config callback was configured, this has no effect.
		$lbFactory->autoReconfigure();

		return $waitSucceeded;
	}

	/**
	 * Rollback a transactional batch of DB operations
	 *
	 * Use this method for scripts that split up their work into logical transactions.
	 * Note that this does not call {@link ILBfactory::flushPrimarySessions()}.
	 *
	 * @see ILBfactory::rollbackPrimaryChanges()
	 *
	 * @param string $fname Caller name
	 * @return void
	 * @since 1.44
	 */
	protected function rollbackTransactionRound( $fname ) {
		$lbFactory = $this->getLBFactory();

		$lbFactory->rollbackPrimaryChanges( $fname );
	}

	/**
	 * Wrap an entry iterator into a generator that returns batches of said entries
	 *
	 * The batch size is determined by {@link getBatchSize()}.
	 *
	 * @param iterable|Generator|Closure $source An iterable or a callback to get one
	 * @return iterable<array> New iterable yielding entry batches from the given iterable
	 * @since 1.44
	 */
	final protected function newBatchIterator( $source ): iterable {
		$batchSize = max( $this->getBatchSize(), 1 );
		if ( $source instanceof Closure ) {
			$iterable = $source();
		} else {
			$iterable = $source;
		}

		$entryBatch = [];
		foreach ( $iterable as $key => $entry ) {
			$entryBatch[$key] = $entry;
			if ( count( $entryBatch ) >= $batchSize ) {
				yield $entryBatch;
				$entryBatch = [];
			}
		}
		if ( $entryBatch ) {
			yield $entryBatch;
		}
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
				$this->output( str_repeat( "\x08", strlen( (string)( $i + 1 ) ) ) );
			}
			$this->output( (string)$i );
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
		}

		return posix_isatty( $fd );
	}

	/**
	 * Prompt the console for input
	 * @param string $prompt What to begin the line with, like '> '
	 * @return string|false Response
	 */
	public static function readconsole( $prompt = '> ' ) {
		static $isatty = null;
		$isatty ??= self::posix_isatty( 0 /*STDIN*/ );

		if ( $isatty && function_exists( 'readline' ) ) {
			return readline( $prompt );
		}

		if ( $isatty ) {
			$st = self::readlineEmulation( $prompt );
		} elseif ( feof( STDIN ) ) {
			$st = false;
		} else {
			$st = fgets( STDIN, 1024 );
		}
		if ( $st === false ) {
			return false;
		}

		return trim( $st );
	}

	/**
	 * Emulate readline()
	 * @param string $prompt What to begin the line with, like '> '
	 * @return string|false
	 */
	private static function readlineEmulation( $prompt ) {
		$bash = ExecutableFinder::findInDefaultPaths( 'bash' );
		if ( !wfIsWindows() && $bash ) {
			$encPrompt = Shell::escape( $prompt );
			$command = "read -er -p $encPrompt && echo \"\$REPLY\"";
			$result = Shell::command( $bash, '-c', $command )
				->passStdin()
				->forwardStderr()
				->execute();

			if ( $result->getExitCode() == 0 ) {
				return $result->getStdout();
			}

			if ( $result->getExitCode() == 127 ) {
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
	 * Call this to set up the autoloader to allow classes to be used from the
	 * tests directory.
	 *
	 * @deprecated since 1.41. Set the MW_AUTOLOAD_TEST_CLASSES in file scope instead.
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
			$this->hookContainer = $this->getServiceContainer()->getHookContainer();
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
			static function ( $id ) {
				return (int)$id;
			},
			$ids
		);
		return array_filter( $ids );
	}

	/**
	 * @param string $errorMsg Error message to be displayed if neither --user or --userid are passed.
	 *
	 * @since 1.37
	 *
	 * @return User
	 */
	protected function validateUserOption( $errorMsg ) {
		if ( $this->hasOption( "user" ) ) {
			$user = User::newFromName( $this->getOption( 'user' ) );
		} elseif ( $this->hasOption( "userid" ) ) {
			$user = User::newFromId( $this->getOption( 'userid' ) );
		} else {
			$this->fatalError( $errorMsg );
		}
		if ( !$user || !$user->isRegistered() ) {
			if ( $this->hasOption( "user" ) ) {
				$this->fatalError( "No such user: " . $this->getOption( 'user' ) );
			} elseif ( $this->hasOption( "userid" ) ) {
				$this->fatalError( "No such user id: " . $this->getOption( 'userid' ) );
			}
		}

		return $user;
	}

	/**
	 * @param string $prompt The prompt to display to the user
	 * @param string|null $default The default value to return if the user just presses enter
	 *
	 * @return string|null
	 *
	 * @since 1.43
	 */
	protected function prompt( string $prompt, ?string $default = null ): ?string {
		$defaultText = $default === null ? ' > ' : " [{$default}] > ";
		$promptWithDefault = $prompt . $defaultText;
		$line = self::readconsole( $promptWithDefault );
		if ( $line === false ) {
			return $default;
		}
		if ( $line === '' ) {
			return $default;
		}

		return $line;
	}

	/**
	 * @param string $prompt The prompt to display to the user
	 * @param bool|null $default The default value to return if the user just presses enter
	 *
	 * @return ?bool
	 *
	 * @since 1.44
	 */
	protected function promptYesNo( $prompt, $default = null ) {
		$defaultText = $default === null ? '' : ( $default ? 'Y' : 'n' );
		$line = self::readconsole( $prompt . " (Y/n) [$defaultText]" );
		if ( $line === false ) {
			return $default;
		}
		if ( $line === '' ) {
			return $default;
		}

		return strtolower( $line ) === 'y';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( Maintenance::class, 'Maintenance' );
