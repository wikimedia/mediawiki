<?php

abstract class MediaWiki_Setup extends PHPUnit_Framework_TestCase {

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

