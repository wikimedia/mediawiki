<?php

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\QueryStatus;
use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\RequestTimeout\RequestTimeout;

/**
 * Helper for testing the methods from the Database class
 * @since 1.22
 */
class DatabaseTestHelper extends Database {

	/**
	 * @var string[] __CLASS__ of the test suite,
	 * used to determine, if the function name is passed every time to query()
	 */
	protected $testName = [];

	/**
	 * @var string[] Array of lastSqls passed to query(),
	 * This is an array since some methods in Database can do more than one
	 * query. Cleared when calling getLastSqls().
	 */
	protected $lastSqls = [];

	/** @var array Stack of result maps */
	protected $nextResMapQueue = [];

	/** @var array|null */
	protected $lastResMap = null;

	/**
	 * @var string[] Array of tables to be considered as existing by tableExist()
	 * Use setExistingTables() to alter.
	 */
	protected $tablesExists;

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
			'serverName' => null,
			'topologyRole' => null,
			'srvCache' => new HashBagOStuff(),
			'profiler' => null,
			'trxProfiler' => new TransactionProfiler(),
			'connLogger' => new NullLogger(),
			'queryLogger' => new NullLogger(),
			'replLogger' => new NullLogger(),
			'errorLogger' => static function ( Exception $e ) {
				wfWarn( get_class( $e ) . ': ' . $e->getMessage() );
			},
			'deprecationLogger' => static function ( $msg ) {
				wfWarn( $msg );
			},
			'criticalSectionProvider' =>
				RequestTimeout::singleton()->createCriticalSectionProvider( 120 )
		] );

		$this->testName = $testName;
		$this->platform = new SQLPlatformTestHelper( new AddQuoterMock() );

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
	 * @param int $errno Error number
	 * @param string $error Error text
	 * @param array $options
	 *  - isKnownStatementRollbackError: Return value for isKnownStatementRollbackError()
	 */
	public function forceNextResult( $res, $errno = 0, $error = '', $options = [] ) {
		$this->nextResMapQueue[] = [
			'res' => $res,
			'errno' => $errno,
			'error' => $error
		] + $options;
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

	public function open( $server, $user, $password, $db, $schema, $tablePrefix ) {
		$this->conn = (object)[ 'test' ];

		return true;
	}

	public function insertId() {
		return -1;
	}

	public function lastErrno() {
		return $this->lastResMap ? $this->lastResMap['errno'] : -1;
	}

	public function lastError() {
		return $this->lastResMap ? $this->lastResMap['error'] : 'test';
	}

	protected function isKnownStatementRollbackError( $errno ) {
		return ( $this->lastResMap['errno'] ?? 0 ) === $errno
			? ( $this->lastResMap['isKnownStatementRollbackError'] ?? false )
			: false;
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

	protected function doSingleStatementQuery( string $sql ): QueryStatus {
		$sql = preg_replace( '< /\* .+?  \*/>', '', $sql );
		$this->addSql( $sql );

		if ( $this->nextResMapQueue ) {
			$this->lastResMap = array_shift( $this->nextResMapQueue );
			if ( !$this->lastResMap['errno'] && $this->forcedAffectedCountQueue ) {
				$this->affectedRowCount = array_shift( $this->forcedAffectedCountQueue );
			}
		} else {
			$this->lastResMap = [ 'res' => [], 'errno' => 0, 'error' => '' ];
		}
		$res = $this->lastResMap['res'];

		return new QueryStatus(
			is_bool( $res ) ? $res : new FakeResultWrapper( $res ),
			$this->affectedRows(),
			$this->lastError(),
			$this->lastErrno()
		);
	}
}
