<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	echo <<<EOF
You are running these tests directly from phpunit. You may not have all globals correctly set.
Running phpunit.php instead is recommended.
EOF;
	require_once ( dirname( __FILE__ ) . "/phpunit.php" );
}

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

	protected function buildTestDatabase( $tables ) {
		global $wgDBprefix;

		$db = wfGetDB( DB_MASTER );
		$oldTableNames = array();
		foreach ( $tables as $table )
			$oldTableNames[$table] = $db->tableName( $table );
		if ( $db->getType() == 'oracle' ) {
			$wgDBprefix = 'pt_';
		} else {
			$wgDBprefix = 'parsertest_';
		}

		$db->tablePrefix( $wgDBprefix );

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

