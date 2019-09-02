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
	public function __construct() {
		parent::__construct();
		$this->setAllowUnregisteredOptions( true );
		$this->addOption(
			'debug-tests',
			'Log testing activity to the PHPUnitCommand log channel (deprecated, always on).',
			false, # not required
			false # no arg needed
		);
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

	public function setup() {
		parent::setup();

		require_once __DIR__ . '/../common/TestSetup.php';
		TestSetup::snapshotGlobals();
	}

	public function finalSetup() {
		parent::finalSetup();

		// Inject test autoloader
		self::requireTestsAutoloader();

		TestSetup::applyInitialConfig();
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

		fwrite( STDERR, defined( 'HHVM_VERSION' ) ?
			'Using HHVM ' . HHVM_VERSION . ' (' . PHP_VERSION . ")\n" :
			'Using PHP ' . PHP_VERSION . "\n" );

		// Tell PHPUnit to ignore options meant for MediaWiki
		$ignore = [];
		foreach ( $this->mParams as $name => $param ) {
			if ( empty( $param['withArg'] ) ) {
				$ignore[] = $name;
			} else {
				$ignore[] = "$name=";
			}
		}

		// Pass through certain options to MediaWikiTestCase
		$cliArgs = [];
		foreach (
			[
				'use-filebackend',
				'use-bagostuff',
				'use-jobqueue',
				'use-normal-tables',
				'reuse-db'
			] as $name
		) {
			$cliArgs[$name] = $this->getOption( $name );
		}

		$command = new MediaWikiPHPUnitCommand( $ignore, $cliArgs );
		$command->run( $_SERVER['argv'], true );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	protected function addOption( $name, $description, $required = false,
		$withArg = false, $shortName = false, $multiOccurrence = false
	) {
		// ignore --quiet which does not really make sense for unit tests
		if ( $name !== 'quiet' ) {
			parent::addOption( $name, $description, $required, $withArg, $shortName, $multiOccurrence );
		}
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
