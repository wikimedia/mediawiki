#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

/* Configuration */

// Evaluate the include path relative to this file
$IP = dirname( dirname( __DIR__ ) );

// Set a flag which can be used to detect when other scripts have been entered through this entry point or not
define( 'MW_PHPUNIT_TEST', true );

// Start up MediaWiki in command-line mode
require_once( "$IP/maintenance/Maintenance.php" );

class PHPUnitMaintClass extends Maintenance {

	function __construct() {
		parent::__construct();
		$this->addOption( 'with-phpunitdir'
			, 'Directory to include PHPUnit from, for example when using a git fetchout from upstream. Path will be prepended to PHP `include_path`.'
			, false # not required
			, true  # need arg
		);
	}

	public function finalSetup() {
		parent::finalSetup();

		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
		global $wgLanguageConverterCacheType, $wgUseDatabaseMessages;
		global $wgLocaltimezone, $wgLocalisationCacheConf;
		global $wgDevelopmentWarnings;

		// wfWarn should cause tests to fail
		$wgDevelopmentWarnings = true;

		$wgMainCacheType = CACHE_NONE;
		$wgMessageCacheType = CACHE_NONE;
		$wgParserCacheType = CACHE_NONE;
		$wgLanguageConverterCacheType = CACHE_NONE;

		$wgUseDatabaseMessages = false; # Set for future resets

		// Assume UTC for testing purposes
		$wgLocaltimezone = 'UTC';

		$wgLocalisationCacheConf['storeClass'] =  'LCStore_Null';
	}

	public function execute() {
		global $IP;

		# Make sure we have --configuration or PHPUnit might complain
		if( !in_array( '--configuration', $_SERVER['argv'] ) ) {
			//Hack to eliminate the need to use the Makefile (which sucks ATM)
			array_splice( $_SERVER['argv'], 1, 0,
				array( '--configuration', $IP . '/tests/phpunit/suite.xml' ) );
		}

		# --with-phpunitdir let us override the default PHPUnit version
		if( $phpunitDir = $this->getOption( 'with-phpunitdir' ) ) {
			# Sanity checks
			if( !is_dir($phpunitDir) ) {
				$this->error( "--with-phpunitdir should be set to an existing directory", 1 );
			}
			if( !is_readable( $phpunitDir."/PHPUnit/Runner/Version.php" ) ) {
				$this->error( "No usable PHPUnit installation in $phpunitDir.\nAborting.\n", 1 );
			}

			# Now prepends provided PHPUnit directory
			$this->output( "Will attempt loading PHPUnit from `$phpunitDir`\n" );
			set_include_path( $phpunitDir
				. PATH_SEPARATOR . get_include_path() );

			# Cleanup $args array so the option and its value do not
			# pollute PHPUnit
			$key = array_search( '--with-phpunitdir', $_SERVER['argv'] );
			unset( $_SERVER['argv'][$key]   ); // the option
			unset( $_SERVER['argv'][$key+1] ); // its value
			$_SERVER['argv'] = array_values( $_SERVER['argv'] );

		}
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

$maintClass = 'PHPUnitMaintClass';
require( RUN_MAINTENANCE_IF_MAIN );

require_once( 'PHPUnit/Runner/Version.php' );

if( PHPUnit_Runner_Version::id() !== '@package_version@'
   && version_compare( PHPUnit_Runner_Version::id(), '3.6.7', '<' ) ) {
	die( 'PHPUnit 3.6.7 or later required, you have ' . PHPUnit_Runner_Version::id() . ".\n" );
}
require_once( 'PHPUnit/Autoload.php' );

require_once( "$IP/tests/TestsAutoLoader.php" );
MediaWikiPHPUnitCommand::main();
