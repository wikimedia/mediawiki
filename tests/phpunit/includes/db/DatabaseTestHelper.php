<?php

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\Database\DatabaseFlags;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\QueryStatus;
use Wikimedia\Rdbms\Replication\ReplicationReporter;
use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\RequestTimeout\RequestTimeout;

/**
 * Helper for testing the methods from the Database class
 * @since 1.22
 */
class DatabaseTestHelper extends Database {

	/**
	 * @var string __CLASS__ of the test suite,
	 * used to determine, if the function name is passed every time to query()
	 */
	protected string $testName;

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

	public function __construct( string $testName, array $opts = [] ) {
		$params = $opts + [
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
			'logger' => new NullLogger(),
			'errorLogger' => static function ( Exception $e ) {
				wfWarn( get_class( $e ) . ': ' . $e->getMessage() );
			},
			'deprecationLogger' => static function ( $msg ) {
				wfWarn( $msg );
			},
			'criticalSectionProvider' =>
				RequestTimeout::singleton()->createCriticalSectionProvider( 120 )
		];
		parent::__construct( $params );

		$this->testName = $testName;
		$this->platform = new SQLPlatformTestHelper( new AddQuoterMock() );
		$this->flagsHolder = new DatabaseFlags( 0 );
		$this->replicationReporter = new ReplicationReporter(
			$params['topologyRole'],
			$params['logger'],
			$params['srvCache']
		);

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

	public function setExistingTables( string|array $tablesExists ) {
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

	protected function addSql( string $sql ) {
		// clean up spaces before and after some words and the whole string
		$this->lastSqls[] = trim( preg_replace(
			'/\s{2,}(?=FROM|WHERE|GROUP BY|ORDER BY|LIMIT)|(?<=SELECT|INSERT|UPDATE)\s{2,}/',
			' ', $sql
		) );
	}

	protected function checkFunctionName( string $fname ) {
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

		if ( !str_starts_with( $check, $this->testName ) ) {
			throw new LogicException( 'function name does not start with test class. ' .
				$fname . ' vs. ' . $this->testName . '. ' .
				'Please provide __METHOD__ to database methods.' );
		}
	}

	/** @inheritDoc */
	public function strencode( $s ) {
		// Choose apos to avoid handling of escaping double quotes in quoted text
		return str_replace( "'", "\'", $s );
	}

	/** @inheritDoc */
	public function query( $sql, $fname = '', $flags = 0 ) {
		$this->checkFunctionName( $fname );

		return parent::query( $sql, $fname, $flags );
	}

	/** @inheritDoc */
	public function tableExists( $table, $fname = __METHOD__ ) {
		[ $db, $pt ] = $this->platform->getDatabaseAndTableIdentifier( $table );
		if ( isset( $this->sessionTempTables[$db][$pt] ) ) {
			return true; // already known to exist
		}

		$this->checkFunctionName( $fname );

		return in_array( $table, (array)$this->tablesExists );
	}

	/** @inheritDoc */
	public function getType() {
		return 'test';
	}

	/** @inheritDoc */
	public function open( $server, $user, $password, $db, $schema, $tablePrefix ) {
		$this->conn = (object)[ 'test' ];

		return true;
	}

	/** @inheritDoc */
	protected function lastInsertId() {
		return -1;
	}

	/** @inheritDoc */
	public function lastErrno() {
		return $this->lastResMap ? $this->lastResMap['errno'] : -1;
	}

	/** @inheritDoc */
	public function lastError() {
		return $this->lastResMap ? $this->lastResMap['error'] : 'test';
	}

	/** @inheritDoc */
	protected function isKnownStatementRollbackError( $errno ) {
		return ( $this->lastResMap['errno'] ?? 0 ) === $errno
			? ( $this->lastResMap['isKnownStatementRollbackError'] ?? false )
			: false;
	}

	/** @inheritDoc */
	public function fieldInfo( $table, $field ) {
		return false;
	}

	/** @inheritDoc */
	public function indexInfo( $table, $index, $fname = 'Database::indexInfo' ) {
		return false;
	}

	/** @inheritDoc */
	public function getSoftwareLink() {
		return 'test';
	}

	/** @inheritDoc */
	public function getServerVersion() {
		return 'test';
	}

	/** @inheritDoc */
	public function getServerInfo() {
		return 'test';
	}

	/** @inheritDoc */
	public function ping( &$rtt = null ) {
		$rtt = 0.0;
		return true;
	}

	/** @inheritDoc */
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
				$count = array_shift( $this->forcedAffectedCountQueue );
				$this->lastQueryAffectedRows = $count;
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
