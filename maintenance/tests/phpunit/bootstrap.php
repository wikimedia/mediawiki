<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

/* Configuration */

// This file is not included in the global scope, but rather within a function, so we must global anything we need to
// have access to in the global scope explicitly
global $wgCommandLineMode, $IP, $optionsWithArgs, $wgProfiler, $wgAutoloadClasses;

// Evaluate the include path relative to this file
$IP = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );

// Set a flag which can be used to detect when other scripts have been entered through this entry point or not
define( 'MW_PHPUNIT_TEST', true );

// Start up MediaWiki in command-line mode
require_once( "$IP/maintenance/commandLine.inc" );

// Assume UTC for testing purposes
$wgLocaltimezone = 'UTC';

// To prevent tests from failing with SQLite, we need to turn database caching off
global $wgCaches;
$wgCaches[CACHE_DB] = false;

// Output a notice when running with older versions of PHPUnit
if ( !version_compare( PHPUnit_Runner_Version::id(), "3.4.1", ">" ) ) {
  echo <<<EOF
********************************************************************************

These tests run best with version PHPUnit 3.4.2 or better. Earlier versions may
show failures because earlier versions of PHPUnit do not properly implement
dependencies.

********************************************************************************

EOF;
}

/* Classes */

abstract class MediaWikiTestSetup extends PHPUnit_Framework_TestCase {
	protected $suite;
	public $regex = '';
	public $runDisabled = false;


	function __construct( PHPUnit_Framework_TestSuite $suite = null ) {
	    if ( null !== $suite ) {
			$this->suite = $suite;
		}
	}

	function __call( $func, $args ) {
		if ( method_exists( $this->suite, $func ) ) {
			return call_user_func_array( array( $tohis->suite, $func ), $args);
		} else {
			throw new MWException( "Called non-existant $func method on "
				. get_class( $this ) );
		}
		return false;
	}

    /**
     * @group utility
     */
	function getSuite() {
		return $this->suite;
	}

	/**
	 * Remove last character if it is a newline
     * @group utility
	 */
	public function chomp( $s ) {
		if ( substr( $s, -1 ) === "\n" ) {
			return substr( $s, 0, -1 );
		}
		else {
			return $s;
		}
	}

	public function addArticle( $name, $text, $line ) {
		global $wgCapitalLinks;
		$oldCapitalLinks = $wgCapitalLinks;
		$wgCapitalLinks = true; // We only need this from SetupGlobals() See r70917#c8637

		$title = Title::newFromText( $name );

		if ( is_null( $title ) ) {
			wfDie( "invalid title at line $line\n" );
		}

		$aid = $title->getArticleID( Title::GAID_FOR_UPDATE );

		if ( $aid != 0 ) {
			wfDie( "duplicate article '$name' at line $line\n" );
		}

		$art = new Article( $title );
		$art->insertNewArticle( $text, '', false, false );

		$wgCapitalLinks = $oldCapitalLinks;
	}

    /**
     * @group utility
     */
	 function buildTestDatabase( $tables ) {
		global $wgDBprefix;

		$db = wfGetDB( DB_MASTER );
		$oldTableNames = array();
		foreach ( $tables as $table )
			$oldTableNames[$table] = $db->tableName( $table );

		if ( $db->isOpen() ) {
			foreach ( $tables as $tbl ) {
				$newTableName = $db->tableName( $tbl );
				$tableName = $oldTableNames[$tbl];
				$db->query( "DROP TABLE IF EXISTS $newTableName", __METHOD__ );
				$db->duplicateTableStructure( $tableName, $newTableName, __METHOD__ );
			}
			return $db;
		} else {
			// Something amiss
			return null;
		}
	}
}
