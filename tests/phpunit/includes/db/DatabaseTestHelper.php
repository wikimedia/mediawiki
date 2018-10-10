<?php

use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\Database;

/**
 * Helper for testing the methods from the Database class
 * @since 1.22
 */
class DatabaseTestHelper extends Database {

	/**
	 * __CLASS__ of the test suite,
	 * used to determine, if the function name is passed every time to query()
	 */
	protected $testName = [];

	/**
	 * Array of lastSqls passed to query(),
	 * This is an array since some methods in Database can do more than one
	 * query. Cleared when calling getLastSqls().
	 */
	protected $lastSqls = [];

	/** @var array List of row arrays */
	protected $nextResult = [];

	/** @var array|null */
	protected $nextError = null;
	/** @var array|null */
	protected $lastError = null;

	/**
	 * Array of tables to be considered as existing by tableExist()
	 * Use setExistingTables() to alter.
	 */
	protected $tablesExists;

	/**
	 * Value to return from unionSupportsOrderAndLimit()
	 */
	protected $unionSupportsOrderAndLimit = true;

	public function __construct( $testName, array $opts = [] ) {
		$this->testName = $testName;

		$this->profiler = new ProfilerStub( [] );
		$this->trxProfiler = new TransactionProfiler();
		$this->cliMode = $opts['cliMode'] ?? true;
		$this->connLogger = new \Psr\Log\NullLogger();
		$this->queryLogger = new \Psr\Log\NullLogger();
		$this->errorLogger = function ( Exception $e ) {
			wfWarn( get_class( $e ) . ": {$e->getMessage()}" );
		};
		$this->deprecationLogger = function ( $msg ) {
			wfWarn( $msg );
		};
		$this->currentDomain = DatabaseDomain::newUnspecified();
		$this->open( 'localhost', 'testuser', 'password', 'testdb', null, '' );
	}

	/**
	 * Returns SQL queries grouped by '; '
	 * Clear the list of queries that have been done so far.
	 * @return string
	 */
	public function getLastSqls() {
		$lastSqls = implode( '; ', $this->lastSqls );
		$this->lastSqls = [];

		return $lastSqls;
	}

	public function setExistingTables( $tablesExists ) {
		$this->tablesExists = (array)$tablesExists;
	}

	/**
	 * @param mixed $res Use an array of row arrays to set row result
	 */
	public function forceNextResult( $res ) {
		$this->nextResult = $res;
	}

	/**
	 * @param int $errno Error number
	 * @param string $error Error text
	 * @param array $options
	 *  - wasKnownStatementRollbackError: Return value for wasKnownStatementRollbackError()
	 */
	public function forceNextQueryError( $errno, $error, $options = [] ) {
		$this->nextError = [ 'errno' => $errno, 'error' => $error ] + $options;
	}

	protected function addSql( $sql ) {
		// clean up spaces before and after some words and the whole string
		$this->lastSqls[] = trim( preg_replace(
			'/\s{2,}(?=FROM|WHERE|GROUP BY|ORDER BY|LIMIT)|(?<=SELECT|INSERT|UPDATE)\s{2,}/',
			' ', $sql
		) );
	}

	protected function checkFunctionName( $fname ) {
		if ( $fname === 'Wikimedia\\Rdbms\\Database::close' ) {
			return; // no $fname parameter
		}

		// Handle some internal calls from the Database class
		$check = $fname;
		if ( preg_match( '/^Wikimedia\\\\Rdbms\\\\Database::query \((.+)\)$/', $fname, $m ) ) {
			$check = $m[1];
		}

		if ( substr( $check, 0, strlen( $this->testName ) ) !== $this->testName ) {
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

		return parent::query( $sql, $fname, $tempIgnore );
	}

	public function tableExists( $table, $fname = __METHOD__ ) {
		$tableRaw = $this->tableName( $table, 'raw' );
		if ( isset( $this->sessionTempTables[$tableRaw] ) ) {
			return true; // already known to exist
		}

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

	function open( $server, $user, $password, $dbName, $schema, $tablePrefix ) {
		$this->conn = (object)[ 'test' ];

		return true;
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
		return $this->lastError ? $this->lastError['errno'] : -1;
	}

	function lastError() {
		return $this->lastError ? $this->lastError['error'] : 'test';
	}

	protected function wasKnownStatementRollbackError() {
		return $this->lastError['wasKnownStatementRollbackError'] ?? false;
	}

	function fieldInfo( $table, $field ) {
		return false;
	}

	function indexInfo( $table, $index, $fname = 'Database::indexInfo' ) {
		return false;
	}

	function fetchAffectedRowCount() {
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

	function isOpen() {
		return $this->conn ? true : false;
	}

	function ping( &$rtt = null ) {
		$rtt = 0.0;
		return true;
	}

	protected function closeConnection() {
		return true;
	}

	protected function doQuery( $sql ) {
		$sql = preg_replace( '< /\* .+?  \*/>', '', $sql );
		$this->addSql( $sql );

		if ( $this->nextError ) {
			$this->lastError = $this->nextError;
			$this->nextError = null;
			return false;
		}

		$res = $this->nextResult;
		$this->nextResult = [];
		$this->lastError = null;

		return new FakeResultWrapper( $res );
	}

	public function unionSupportsOrderAndLimit() {
		return $this->unionSupportsOrderAndLimit;
	}

	public function setUnionSupportsOrderAndLimit( $v ) {
		$this->unionSupportsOrderAndLimit = (bool)$v;
	}
}
