<?php

class DatabaseUpdaterTest extends MediaWikiTestCase {

	public function testSetAppliedUpdates() {
		$db = new FakeDatabase();
		$dbu = new FakeDatabaseUpdater( $db );
		$dbu->setAppliedUpdates( "test", array() );
		$expected = "updatelist-test-" . time() . "0";
		$actual = $db->lastInsertData['ul_key'];
		$this->assertEquals( $expected, $actual, var_export( $db->lastInsertData, true ) );
		$dbu->setAppliedUpdates( "test", array() );
		$expected = "updatelist-test-" . time() . "1";
		$actual = $db->lastInsertData['ul_key'];
		$this->assertEquals( $expected, $actual, var_export( $db->lastInsertData, true ) );
	}
}

class FakeDatabase extends DatabaseBase {
	public $lastInsertTable;
	public $lastInsertData;

	function __construct() {
	}

	function clearFlag( $arg ) {
	}

	function setFlag( $arg ) {
	}

	public function insert( $table, $a, $fname = __METHOD__, $options = array() ) {
		$this->lastInsertTable = $table;
		$this->lastInsertData = $a;
	}

	/**
	 * Get the type of the DBMS, as it appears in $wgDBtype.
	 *
	 * @return string
	 */
	function getType() {
		// TODO: Implement getType() method.
	}

	/**
	 * Open a connection to the database. Usually aborts on failure
	 *
	 * @param string $server Database server host
	 * @param string $user Database user name
	 * @param string $password Database user password
	 * @param string $dbName Database name
	 * @return bool
	 * @throws DBConnectionError
	 */
	function open( $server, $user, $password, $dbName ) {
		// TODO: Implement open() method.
	}

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 * If no more rows are available, false is returned.
	 *
	 * @param ResultWrapper|stdClass $res Object as returned from DatabaseBase::query(), etc.
	 * @return stdClass|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchObject( $res ) {
		// TODO: Implement fetchObject() method.
	}

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form. Fields are retrieved with $row['fieldname'].
	 * If no more rows are available, false is returned.
	 *
	 * @param ResultWrapper $res Result object as returned from DatabaseBase::query(), etc.
	 * @return array|bool
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	function fetchRow( $res ) {
		// TODO: Implement fetchRow() method.
	}

	/**
	 * Get the number of rows in a result object
	 *
	 * @param mixed $res A SQL result
	 * @return int
	 */
	function numRows( $res ) {
		// TODO: Implement numRows() method.
	}

	/**
	 * Get the number of fields in a result object
	 * @see http://www.php.net/mysql_num_fields
	 *
	 * @param mixed $res A SQL result
	 * @return int
	 */
	function numFields( $res ) {
		// TODO: Implement numFields() method.
	}

	/**
	 * Get a field name in a result object
	 * @see http://www.php.net/mysql_field_name
	 *
	 * @param mixed $res A SQL result
	 * @param int $n
	 * @return string
	 */
	function fieldName( $res, $n ) {
		// TODO: Implement fieldName() method.
	}

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * The value inserted should be fetched from nextSequenceValue()
	 *
	 * Example:
	 * $id = $dbw->nextSequenceValue( 'page_page_id_seq' );
	 * $dbw->insert( 'page', array( 'page_id' => $id ) );
	 * $id = $dbw->insertId();
	 *
	 * @return int
	 */
	function insertId() {
		// TODO: Implement insertId() method.
	}

	/**
	 * Change the position of the cursor in a result object
	 * @see http://www.php.net/mysql_data_seek
	 *
	 * @param mixed $res A SQL result
	 * @param int $row
	 */
	function dataSeek( $res, $row ) {
		// TODO: Implement dataSeek() method.
	}

	/**
	 * Get the last error number
	 * @see http://www.php.net/mysql_errno
	 *
	 * @return int
	 */
	function lastErrno() {
		// TODO: Implement lastErrno() method.
	}

	/**
	 * Get a description of the last error
	 * @see http://www.php.net/mysql_error
	 *
	 * @return string
	 */
	function lastError() {
		// TODO: Implement lastError() method.
	}

	/**
	 * mysql_fetch_field() wrapper
	 * Returns false if the field doesn't exist
	 *
	 * @param string $table Table name
	 * @param string $field Field name
	 *
	 * @return Field
	 */
	function fieldInfo( $table, $field ) {
		// TODO: Implement fieldInfo() method.
	}

	/**
	 * Get information about an index into an object
	 * @param string $table Table name
	 * @param string $index Index name
	 * @param string $fname Calling function name
	 * @return mixed Database-specific index description class or false if the index does not exist
	 */
	function indexInfo( $table, $index, $fname = __METHOD__ ) {
		// TODO: Implement indexInfo() method.
	}

	/**
	 * Get the number of rows affected by the last write query
	 * @see http://www.php.net/mysql_affected_rows
	 *
	 * @return int
	 */
	function affectedRows() {
		// TODO: Implement affectedRows() method.
	}

	/**
	 * Wrapper for addslashes()
	 *
	 * @param string $s String to be slashed.
	 * @return string Slashed string.
	 */
	function strencode( $s ) {
		// TODO: Implement strencode() method.
	}

	/**
	 * Returns a wikitext link to the DB's website, e.g.,
	 *   return "[http://www.mysql.com/ MySQL]";
	 * Should at least contain plain text, if for some reason
	 * your database has no website.
	 *
	 * @return string Wikitext of a link to the server software's web site
	 */
	function getSoftwareLink() {
		// TODO: Implement getSoftwareLink() method.
	}

	/**
	 * A string describing the current software version, like from
	 * mysql_get_server_info().
	 *
	 * @return string Version information from the database server.
	 */
	function getServerVersion() {
		// TODO: Implement getServerVersion() method.
	}

	/**
	 * Closes underlying database connection
	 * @since 1.20
	 * @return bool Whether connection was closed successfully
	 */
	protected function closeConnection() {
		// TODO: Implement closeConnection() method.
	}

	/**
	 * The DBMS-dependent part of query()
	 *
	 * @param string $sql SQL query.
	 * @return ResultWrapper|bool Result object to feed to fetchObject,
	 *   fetchRow, ...; or false on failure
	 */
	protected function doQuery( $sql ) {
		// TODO: Implement doQuery() method.
	}
}

class FakeDatabaseUpdater extends DatabaseUpdater {
	function __construct( $db ) {
		$this->db = $db;
		self::$updateCounter = 0;
	}

	/**
	 * Get an array of updates to perform on the database. Should return a
	 * multi-dimensional array. The main key is the MediaWiki version (1.12,
	 * 1.13...) with the values being arrays of updates, identical to how
	 * updaters.inc did it (for now)
	 *
	 * @return array
	 */
	protected function getCoreUpdateList() {
		return array();
	}

	public function canUseNewUpdatelog() {
		return true;
	}

	public function setAppliedUpdates( $version, $updates = array() ) {
		parent::setAppliedUpdates( $version, $updates );
	}
}
