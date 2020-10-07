<?php

use Psr\Log\NullLogger;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\TransactionProfiler;

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

	/** @var int[] */
	protected $forcedAffectedCountQueue = [];

	public function __construct( $testName, array $opts = [] ) {
		parent::__construct( $opts + [
			'host' => null,
			'user' => null,
			'password' => null,
			'dbname' => null,
			'schema' => null,
			'tablePrefix' => '',
			'flags' => 0,
			'cliMode' => true,
			'agent' => '',
			'topologyRole' => null,
			'topologicalMaster' => null,
			'srvCache' => new HashBagOStuff(),
			'profiler' => null,
			'trxProfiler' => new TransactionProfiler(),
			'connLogger' => new NullLogger(),
			'queryLogger' => new NullLogger(),
			'replLogger' => new NullLogger(),
			'errorLogger' => function ( Exception $e ) {
				wfWarn( get_class( $e ) . ": {$e->getMessage()}" );
			},
			'deprecationLogger' => function ( $msg ) {
				wfWarn( $msg );
			}
		] );

		$this->testName = $testName;

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
		if ( preg_match(
			'/^Wikimedia\\\\Rdbms\\\\Database::(?:query|beginIfImplied) \((.+)\)$/',
			$fname,
			$m
		) ) {
			$check = $m[1];
		}

		if ( substr( $check, 0, strlen( $this->testName ) ) !== $this->testName ) {
			throw new MWException( 'function name does not start with test class. ' .
				$fname . ' vs. ' . $this->testName . '. ' .
				'Please provide __METHOD__ to database methods.' );
		}
	}

	public function strencode( $s ) {
		// Choose apos to avoid handling of escaping double quotes in quoted text
		return str_replace( "'", "\'", $s );
	}

	public function addIdentifierQuotes( $s ) {
		// no escaping to avoid handling of double quotes in quoted text
		return $s;
	}

	public function query( $sql, $fname = '', $flags = 0 ) {
		$this->checkFunctionName( $fname );

		return parent::query( $sql, $fname, $flags );
	}

	public function tableExists( $table, $fname = __METHOD__ ) {
		$tableRaw = $this->tableName( $table, 'raw' );
		if ( isset( $this->sessionTempTables[$tableRaw] ) ) {
			return true; // already known to exist
		}

		$this->checkFunctionName( $fname );

		return in_array( $table, (array)$this->tablesExists );
	}

	public function getType() {
		return 'test';
	}

	public function open( $server, $user, $password, $dbName, $schema, $tablePrefix ) {
		$this->conn = (object)[ 'test' ];

		return true;
	}

	public function fetchObject( $res ) {
		return false;
	}

	public function fetchRow( $res ) {
		return false;
	}

	public function numRows( $res ) {
		return -1;
	}

	public function numFields( $res ) {
		return -1;
	}

	public function fieldName( $res, $n ) {
		return 'test';
	}

	public function insertId() {
		return -1;
	}

	public function dataSeek( $res, $row ) {
		/* nop */
	}

	public function lastErrno() {
		return $this->lastError ? $this->lastError['errno'] : -1;
	}

	public function lastError() {
		return $this->lastError ? $this->lastError['error'] : 'test';
	}

	protected function wasKnownStatementRollbackError() {
		return $this->lastError['wasKnownStatementRollbackError'] ?? false;
	}

	public function fieldInfo( $table, $field ) {
		return false;
	}

	public function indexInfo( $table, $index, $fname = 'Database::indexInfo' ) {
		return false;
	}

	public function fetchAffectedRowCount() {
		return -1;
	}

	public function getSoftwareLink() {
		return 'test';
	}

	public function getServerVersion() {
		return 'test';
	}

	public function getServerInfo() {
		return 'test';
	}

	public function ping( &$rtt = null ) {
		$rtt = 0.0;
		return true;
	}

	protected function closeConnection() {
		return true;
	}

	public function setNextQueryAffectedRowCounts( array $counts ) {
		$this->forcedAffectedCountQueue = $counts;
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

		if ( $this->forcedAffectedCountQueue ) {
			$this->affectedRowCount = array_shift( $this->forcedAffectedCountQueue );
		}

		return new FakeResultWrapper( $res );
	}

	public function unionSupportsOrderAndLimit() {
		return $this->unionSupportsOrderAndLimit;
	}

	public function setUnionSupportsOrderAndLimit( $v ) {
		$this->unionSupportsOrderAndLimit = (bool)$v;
	}

	public function useIndexClause( $index ) {
		return "FORCE INDEX (" . $this->indexName( $index ) . ")";
	}

	public function ignoreIndexClause( $index ) {
		return "IGNORE INDEX (" . $this->indexName( $index ) . ")";
	}
}
