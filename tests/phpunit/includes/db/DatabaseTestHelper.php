<?php

/**
 * Helper for testing the methods from the DatabaseBase class
 * @since 1.22
 */
class DatabaseTestHelper extends DatabaseBase {

	/**
	 * __CLASS__ of the test suite,
	 * used to determine, if the function name is passed every time to query()
	 */
	protected $testName = array();

	/**
	 * Array of lastSqls passed to query(),
	 * This is an array since some methods in DatabaseBase can do more than one
	 * query. Cleared when calling getLastSqls().
	 */
	protected $lastSqls = array();

	/**
	 * Array of tables to be considered as existing by tableExist()
	 * Use setExistingTables() to alter.
	 */
	protected $tablesExists;

	public function __construct( $testName ) {
		$this->testName = $testName;
	}

	/**
	 * Returns SQL queries grouped by '; '
	 * Clear the list of queries that have been done so far.
	 */
	public function getLastSqls() {
		$lastSqls = implode( '; ', $this->lastSqls );
		$this->lastSqls = array();

		return $lastSqls;
	}

	public function setExistingTables( $tablesExists ) {
		$this->tablesExists = (array)$tablesExists;
	}

	protected function addSql( $sql ) {
		// clean up spaces before and after some words and the whole string
		$this->lastSqls[] = trim( preg_replace(
			'/\s{2,}(?=FROM|WHERE|GROUP BY|ORDER BY|LIMIT)|(?<=SELECT|INSERT|UPDATE)\s{2,}/',
			' ', $sql
		) );
	}

	protected function checkFunctionName( $fname ) {
		if ( substr( $fname, 0, strlen( $this->testName ) ) !== $this->testName ) {
			throw new MWException( 'function name does not start with test class. ' .
				$fname . ' vs. ' . $this->testName . '. ' .
				'Please provide __METHOD__ to database methods.' );
		}
	}

	function strencode( $s ) {
		// Choose apos to avoid handling of escaping double quotes in quoted text
		return str_replace( "'", "\'", $s );
	}

	public function addIdentifierQuotes( $s ) {
		// no escaping to avoid handling of double quotes in quoted text
		return $s;
	}

	public function query( $sql, $fname = '', $tempIgnore = false ) {
		$this->checkFunctionName( $fname );
		$this->addSql( $sql );

		return parent::query( $sql, $fname, $tempIgnore );
	}

	public function tableExists( $table, $fname = __METHOD__ ) {
		$this->checkFunctionName( $fname );

		return in_array( $table, (array)$this->tablesExists );
	}

	// Redeclare parent method to make it public
	public function nativeReplace( $table, $rows, $fname ) {
		return parent::nativeReplace( $table, $rows, $fname );
	}

	function getType() {
		return 'test';
	}

	function open( $server, $user, $password, $dbName ) {
		return false;
	}

	function fetchObject( $res ) {
		return false;
	}

	function fetchRow( $res ) {
		return false;
	}

	function numRows( $res ) {
		return -1;
	}

	function numFields( $res ) {
		return -1;
	}

	function fieldName( $res, $n ) {
		return 'test';
	}

	function insertId() {
		return -1;
	}

	function dataSeek( $res, $row ) {
		/* nop */
	}

	function lastErrno() {
		return -1;
	}

	function lastError() {
		return 'test';
	}

	function fieldInfo( $table, $field ) {
		return false;
	}

	function indexInfo( $table, $index, $fname = 'Database::indexInfo' ) {
		return false;
	}

	function affectedRows() {
		return -1;
	}

	function getSoftwareLink() {
		return 'test';
	}

	function getServerVersion() {
		return 'test';
	}

	function getServerInfo() {
		return 'test';
	}

	protected function closeConnection() {
		return false;
	}

	protected function doQuery( $sql ) {
		return array();
	}
}
