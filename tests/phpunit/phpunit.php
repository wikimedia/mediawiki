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

	public static $additionalOptions = [
		'file' => false,
		'use-filebackend' => false,
		'use-bagostuff' => false,
		'use-jobqueue' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
		'wiki' => false,
		'profiler' => false,
	];

	public function __construct() {
		parent::__construct();
		$this->addOption(
			'with-phpunitclass',
			'Class name of the PHPUnit entry point to use',
			false,
			true
		);
		$this->addOption(
			'debug-tests',
			'Log testing activity to the PHPUnitCommand log channel.',
			false, # not required
			false # no arg needed
		);
		$this->addOption( 'file', 'File describing parser tests.', false, true );
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

	public function finalSetup() {
		parent::finalSetup();

		// Inject test autoloader
		self::requireTestsAutoloader();

		TestSetup::applyInitialConfig();
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
			// Hack to eliminate the need to use the Makefile (which sucks ATM)
			array_splice( $_SERVER['argv'], 1, 0,
				[ '--configuration', $IP . '/tests/phpunit/suite.xml' ] );
		}

		$phpUnitClass = 'PHPUnit_TextUI_Command';

		if ( $this->hasOption( 'with-phpunitclass' ) ) {
			$phpUnitClass = $this->getOption( 'with-phpunitclass' );

			# Cleanup $args array so the option and its value do not
			# pollute PHPUnit
			$key = array_search( '--with-phpunitclass', $_SERVER['argv'] );
			unset( $_SERVER['argv'][$key] ); // the option
			unset( $_SERVER['argv'][$key + 1] ); // its value
			$_SERVER['argv'] = array_values( $_SERVER['argv'] );
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

		if ( !class_exists( 'PHPUnit_Framework_TestCase' ) ) {
			echo "PHPUnit not found. Please install it and other dev dependencies by
		running `composer install` in MediaWiki root directory.\n";
			exit( 1 );
		}
		if ( !class_exists( $phpUnitClass ) ) {
			echo "PHPUnit entry point '" . $phpUnitClass . "' not found. Please make sure you installed
		the containing component and check the spelling of the class name.\n";
			exit( 1 );
		}

		echo defined( 'HHVM_VERSION' ) ?
			'Using HHVM ' . HHVM_VERSION . ' (' . PHP_VERSION . ")\n" :
			'Using PHP ' . PHP_VERSION . "\n";

		// Prepare global services for unit tests.
		MediaWikiTestCase::prepareServices( new GlobalVarConfig() );

		$phpUnitClass::main();
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	/**
	 * Force the format of elements in $_SERVER['argv']
	 *  - Split args such as "wiki=enwiki" into two separate arg elements "wiki" and "enwiki"
	 */
	private function forceFormatServerArgv() {
		$argv = [];
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
