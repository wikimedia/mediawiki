#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

// Set a flag which can be used to detect when other scripts have been entered
// through this entry point or not.
define( 'MW_PHPUNIT_TEST', true );

// Start up MediaWiki in command-line mode
require_once dirname( dirname( __DIR__ ) ) . "/maintenance/Maintenance.php";

class PHPUnitMaintClass extends Maintenance {

	public static $additionalOptions = array(
		'regex' => false,
		'file' => false,
		'use-filebackend' => false,
		'use-bagostuff' => false,
		'use-jobqueue' => false,
		'keep-uploads' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
		'wiki' => false,
	);

	public function __construct() {
		parent::__construct();
		$this->addOption(
			'with-phpunitdir',
			'Directory to include PHPUnit from, for example when using a git '
				. 'fetchout from upstream. Path will be prepended to PHP `include_path`.',
			false, # not required
			true # need arg
		);
		$this->addOption(
			'debug-tests',
			'Log testing activity to the PHPUnitCommand log channel.',
			false, # not required
			false # no arg needed
		);
		$this->addOption( 'regex', 'Only run parser tests that match the given regex.', false, true );
		$this->addOption( 'file', 'File describing parser tests.', false, true );
		$this->addOption( 'use-filebackend', 'Use filebackend', false, true );
		$this->addOption( 'use-bagostuff', 'Use bagostuff', false, true );
		$this->addOption( 'use-jobqueue', 'Use jobqueue', false, true );
		$this->addOption( 'keep-uploads', 'Re-use the same upload directory for each test, don\'t delete it.', false, false );
		$this->addOption( 'use-normal-tables', 'Use normal DB tables.', false, false );
		$this->addOption( 'reuse-db', 'Init DB only if tables are missing and keep after finish.', false, false );
	}

	public function finalSetup() {
		parent::finalSetup();

		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
		global $wgLanguageConverterCacheType, $wgUseDatabaseMessages;
		global $wgLocaltimezone, $wgLocalisationCacheConf;
		global $wgDevelopmentWarnings;

		// Inject test autoloader
		require_once __DIR__ . '/../TestsAutoLoader.php';

		// wfWarn should cause tests to fail
		$wgDevelopmentWarnings = true;

		$wgMainCacheType = CACHE_NONE;
		$wgMessageCacheType = CACHE_NONE;
		$wgParserCacheType = CACHE_NONE;
		$wgLanguageConverterCacheType = CACHE_NONE;

		$wgUseDatabaseMessages = false; # Set for future resets

		// Assume UTC for testing purposes
		$wgLocaltimezone = 'UTC';

		$wgLocalisationCacheConf['storeClass'] = 'LCStoreNull';

		// Bug 44192 Do not attempt to send a real e-mail
		Hooks::clear( 'AlternateUserMailer' );
		Hooks::register(
			'AlternateUserMailer',
			function () {
				return false;
			}
		);
		// xdebug's default of 100 is too low for MediaWiki
		ini_set( 'xdebug.max_nesting_level', 1000 );
	}

	public function execute() {
		global $IP;

		// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
		// stays in tact.
		// Has to in execute() instead of finalSetup(), because finalSetup() runs before
		// doMaintenance.php includes Setup.php, which calls MWExceptionHandler::installHandle().
		restore_error_handler();

		$this->forceFormatServerArgv();

		# Make sure we have --configuration or PHPUnit might complain
		if ( !in_array( '--configuration', $_SERVER['argv'] ) ) {
			//Hack to eliminate the need to use the Makefile (which sucks ATM)
			array_splice( $_SERVER['argv'], 1, 0,
				array( '--configuration', $IP . '/tests/phpunit/suite.xml' ) );
		}

		# --with-phpunitdir let us override the default PHPUnit version
		# Can use with either or phpunit.phar in the directory or the
		# full PHPUnit code base.
		if ( $this->hasOption( 'with-phpunitdir' ) ) {
			$phpunitDir = $this->getOption( 'with-phpunitdir' );

			# prepends provided PHPUnit directory or phar
			$this->output( "Will attempt loading PHPUnit from `$phpunitDir`\n" );
			set_include_path( $phpunitDir . PATH_SEPARATOR . get_include_path() );

			# Cleanup $args array so the option and its value do not
			# pollute PHPUnit
			$key = array_search( '--with-phpunitdir', $_SERVER['argv'] );
			unset( $_SERVER['argv'][$key] ); // the option
			unset( $_SERVER['argv'][$key + 1] ); // its value
			$_SERVER['argv'] = array_values( $_SERVER['argv'] );
		}

		if ( !wfIsWindows() ) {
			# If we are not running on windows then we can enable phpunit colors
			# Windows does not come anymore with ANSI.SYS loaded by default
			# PHPUnit uses the suite.xml parameters to enable/disable colors
			# which can be then forced to be enabled with --colors.
			# The below code injects a parameter just like if the user called
			# Probably fix bug 29226
			$key = array_search( '--colors', $_SERVER['argv'] );
			if ( $key === false ) {
				array_splice( $_SERVER['argv'], 1, 0, '--colors' );
			}
		}

		# Makes MediaWiki PHPUnit directory includable so the PHPUnit will
		# be able to resolve relative files inclusion such as suites/*
		# PHPUnit uses stream_resolve_include_path() internally
		# See bug 32022
		$key = array_search( '--include-path', $_SERVER['argv'] );
		if ( $key === false ) {
			array_splice( $_SERVER['argv'], 1, 0,
				__DIR__
				. PATH_SEPARATOR
				. get_include_path()
			);
			array_splice( $_SERVER['argv'], 1, 0, '--include-path' );
		}

		$key = array_search( '--debug-tests', $_SERVER['argv'] );
		if ( $key !== false && array_search( '--printer', $_SERVER['argv'] ) === false ) {
			unset( $_SERVER['argv'][$key] );
			array_splice( $_SERVER['argv'], 1, 0, 'MediaWikiPHPUnitTestListener' );
			array_splice( $_SERVER['argv'], 1, 0, '--printer' );
		}

		foreach ( self::$additionalOptions as $option => $default ) {
			$key = array_search( '--' . $option, $_SERVER['argv'] );
			if ( $key !== false ) {
				unset( $_SERVER['argv'][$key] );
				if ( $this->mParams[$option]['withArg'] ) {
					self::$additionalOptions[$option] = $_SERVER['argv'][$key + 1];
					unset( $_SERVER['argv'][$key + 1] );
				} else {
					self::$additionalOptions[$option] = true;
				}
			}
		}

	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	/**
	 * Force the format of elements in $_SERVER['argv']
	 *  - Split args such as "wiki=enwiki" into two separate arg elements "wiki" and "enwiki"
	 */
	private function forceFormatServerArgv() {
		$argv = array();
		foreach ( $_SERVER['argv'] as $key => $arg ) {
			if ( $key === 0 ) {
				$argv[0] = $arg;
			} elseif ( strstr( $arg, '=' ) ) {
				foreach ( explode( '=', $arg, 2 ) as $argPart ) {
					$argv[] = $argPart;
				}
			} else {
				$argv[] = $arg;
			}
		}
		$_SERVER['argv'] = $argv;
	}

}

$maintClass = 'PHPUnitMaintClass';
require RUN_MAINTENANCE_IF_MAIN;

// Prevent segfault when we have lots of unit tests (bug 62623)
if ( version_compare( PHP_VERSION, '5.4.0', '<' ) ) {
	register_shutdown_function( function () {
		gc_collect_cycles();
		gc_disable();
	} );
}


$ok = false;

foreach ( array(
	stream_resolve_include_path( 'phpunit.phar' ),
	'PHPUnit/Runner/Version.php',
	'PHPUnit/Autoload.php'
) as $includePath ) {
	@include_once $includePath;
	if ( class_exists( 'PHPUnit_TextUI_Command' ) ) {
		$ok = true;
		break;
	}
}

if ( !$ok ) {
	die( "Couldn't find a usable PHPUnit.\n" );
}

$puVersion = PHPUnit_Runner_Version::id();
if ( $puVersion !== '@package_version@' && version_compare( $puVersion, '3.7.0', '<' ) ) {
	die( "PHPUnit 3.7.0 or later required; you have {$puVersion}.\n" );
}

PHPUnit_TextUI_Command::main();
