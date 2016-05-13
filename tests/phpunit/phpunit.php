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

$wgPhpUnitClass = 'PHPUnit_TextUI_Command';

// Start up MediaWiki in command-line mode
require_once dirname( dirname( __DIR__ ) ) . "/maintenance/Maintenance.php";

class PHPUnitMaintClass extends Maintenance {

	public static $additionalOptions = [
		'regex' => false,
		'file' => false,
		'use-filebackend' => false,
		'use-bagostuff' => false,
		'use-jobqueue' => false,
		'keep-uploads' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
		'wiki' => false,
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
		$this->addOption(
			'regex',
			'Only run parser tests that match the given regex.',
			false,
			true
		);
		$this->addOption( 'file', 'File describing parser tests.', false, true );
		$this->addOption( 'use-filebackend', 'Use filebackend', false, true );
		$this->addOption( 'use-bagostuff', 'Use bagostuff', false, true );
		$this->addOption( 'use-jobqueue', 'Use jobqueue', false, true );
		$this->addOption(
			'keep-uploads',
			'Re-use the same upload directory for each test, don\'t delete it.',
			false,
			false
		);
		$this->addOption( 'use-normal-tables', 'Use normal DB tables.', false, false );
		$this->addOption(
			'reuse-db', 'Init DB only if tables are missing and keep after finish.',
			false,
			false
		);
	}

	public function finalSetup() {
		parent::finalSetup();

		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType, $wgMainWANCache;
		global $wgMainStash;
		global $wgLanguageConverterCacheType, $wgUseDatabaseMessages;
		global $wgLocaltimezone, $wgLocalisationCacheConf;
		global $wgDevelopmentWarnings;
		global $wgSessionProviders;
		global $wgJobTypeConf;
		global $wgAuthManagerConfig, $wgAuth, $wgDisableAuthManager;

		// Inject test autoloader
		require_once __DIR__ . '/../TestsAutoLoader.php';

		// wfWarn should cause tests to fail
		$wgDevelopmentWarnings = true;

		// Make sure all caches and stashes are either disabled or use
		// in-process cache only to prevent tests from using any preconfigured
		// cache meant for the local wiki from outside the test run.
		// See also MediaWikiTestCase::run() which mocks CACHE_DB and APC.

		// Disabled in DefaultSettings, override local settings
		$wgMainWANCache =
		$wgMainCacheType = CACHE_NONE;
		// Uses CACHE_ANYTHING in DefaultSettings, use hash instead of db
		$wgMessageCacheType =
		$wgParserCacheType =
		$wgSessionCacheType =
		$wgLanguageConverterCacheType = 'hash';
		// Uses db-replicated in DefaultSettings
		$wgMainStash = 'hash';
		// Use memory job queue
		$wgJobTypeConf = [
			'default' => [ 'class' => 'JobQueueMemory', 'order' => 'fifo' ],
		];

		$wgUseDatabaseMessages = false; # Set for future resets

		// Assume UTC for testing purposes
		$wgLocaltimezone = 'UTC';

		$wgLocalisationCacheConf['storeClass'] = 'LCStoreNull';

		// Generic MediaWiki\Session\SessionManager configuration for tests
		// We use CookieSessionProvider because things might be expecting
		// cookies to show up in a FauxRequest somewhere.
		$wgSessionProviders = [
			[
				'class' => MediaWiki\Session\CookieSessionProvider::class,
				'args' => [ [
					'priority' => 30,
					'callUserSetCookiesHook' => true,
				] ],
			],
		];

		// Generic AuthManager configuration for testing
		$wgAuthManagerConfig = [
			'preauth' => [],
			'primaryauth' => [
				[
					'class' => MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider::class,
					'args' => [ [
						'authoritative' => false,
					] ],
				],
				[
					'class' => MediaWiki\Auth\LocalPasswordPrimaryAuthenticationProvider::class,
					'args' => [ [
						'authoritative' => true,
					] ],
				],
			],
			'secondaryauth' => [],
		];
		$wgAuth = $wgDisableAuthManager ? new AuthPlugin : new MediaWiki\Auth\AuthManagerAuthPlugin();

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

		// Bug T116683 serialize_precision of 100
		// may break testing against floating point values
		// treated with PHP's serialize()
		ini_set( 'serialize_precision', 17 );

		// TODO: we should call MediaWikiTestCase::prepareServices( new GlobalVarConfig() ) here.
		// But PHPUnit may not be loaded yet, so we have to wait until just
		// before PHPUnit_TextUI_Command::main() is executed at the end of this file.
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

		if ( $this->hasOption( 'with-phpunitclass' ) ) {
			global $wgPhpUnitClass;
			$wgPhpUnitClass = $this->getOption( 'with-phpunitclass' );

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

if ( !class_exists( 'PHPUnit_Framework_TestCase' ) ) {
	echo "PHPUnit not found. Please install it and other dev dependencies by
running `composer install` in MediaWiki root directory.\n";
	exit( 1 );
}
if ( !class_exists( $wgPhpUnitClass ) ) {
	echo "PHPUnit entry point '" . $wgPhpUnitClass . "' not found. Please make sure you installed
the containing component and check the spelling of the class name.\n";
	exit( 1 );
}

echo defined( 'HHVM_VERSION' ) ?
	'Using HHVM ' . HHVM_VERSION . ' (' . PHP_VERSION . ")\n" :
	'Using PHP ' . PHP_VERSION . "\n";

// Prepare global services for unit tests.
// FIXME: this should be done in the finalSetup() method,
// but PHPUnit may not have been loaded at that point.
MediaWikiTestCase::prepareServices( new GlobalVarConfig() );

$wgPhpUnitClass::main();
