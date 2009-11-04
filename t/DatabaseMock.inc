<?php
/**
 * Mock database class for tests, does nothing.
 * Include after LocalSettings.php
 */

$wgDBtype = 'mock';

class DatabaseMock extends DatabaseBase {
	function __construct( $server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		$this->mConn = true;
		$this->mOpened = true;
	}

	function open( $server, $user, $password, $dbName ) { return true; }
	function doQuery( $sql ) {}
	function fetchObject( $res ) {}
	function fetchRow( $res ) {}
	function numRows( $res ) {}
	function numFields( $res ) {}
	function fieldName( $res, $n ) {}
	function insertId() {}
	function dataSeek( $res, $row ) {}
	function lastErrno() { return 0; }
	function lastError() { return ''; }
	function affectedRows() {}
	function fieldInfo( $table, $field ) {}
	function strencode( $s ) {}
	function getSoftwareLink() {}
	function getServerVersion() {}
}