<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace Wikimedia\Rdbms;

use mysqli;
use mysqli_result;
use RuntimeException;
use Wikimedia\AtEase\AtEase;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\Platform\MySQLPlatform;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\Replication\MysqlReplicationReporter;

/**
 * MySQL database abstraction layer.
 *
 * Defines methods independent of the used MySQL extension.
 *
 * @property mysqli|null $conn
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 */
class DatabaseMySQL extends Database {
	/** @var string|null */
	private $sslKeyPath;
	/** @var string|null */
	private $sslCertPath;
	/** @var string|null */
	private $sslCAFile;
	/** @var string|null */
	private $sslCAPath;
	/**
	 * Open SSL cipher list string
	 * @see https://docs.openssl.org/3.3/man1/openssl-ciphers/
	 * @var string|null
	 */
	private $sslCiphers;
	/** @var bool Use experimental UTF-8 transmission encoding */
	private $utf8Mode;

	/** @var SQLPlatform */
	protected $platform;

	/** @var MysqlReplicationReporter */
	protected $replicationReporter;
	/** @var int Last implicit row ID for the session (0 if none) */
	private $sessionLastAutoRowId;

	/**
	 * Additional $params include:
	 *   - lagDetectionMethod : set to one of (Seconds_Behind_Master,pt-heartbeat).
	 *       pt-heartbeat assumes the table is at heartbeat.heartbeat
	 *       and uses UTC timestamps in the heartbeat.ts column.
	 *       (https://www.percona.com/doc/percona-toolkit/2.2/pt-heartbeat.html)
	 *   - lagDetectionOptions : if using pt-heartbeat, this can be set to an array map.
	 *       The "conds" key overrides the WHERE clause used to find the relevant row in the
	 *       `heartbeat` table, e.g. ['shard' => 's1']. By default, the row used is the newest
	 *       row having a server_id matching that of the immediate replication source server
	 *       for the given replica.
	 *   - useGTIDs : use GTID methods like MASTER_GTID_WAIT() when possible.
	 *   - sslKeyPath : path to key file [default: null]
	 *   - sslCertPath : path to certificate file [default: null]
	 *   - sslCAFile: path to a single certificate authority PEM file [default: null]
	 *   - sslCAPath : parth to certificate authority PEM directory [default: null]
	 *   - sslCiphers : array list of allowable ciphers [default: null]
	 * @param array $params
	 */
	public function __construct( array $params ) {
		foreach ( [ 'KeyPath', 'CertPath', 'CAFile', 'CAPath', 'Ciphers' ] as $name ) {
			$var = "ssl{$name}";
			if ( isset( $params[$var] ) ) {
				$this->$var = $params[$var];
			}
		}
		$this->utf8Mode = !empty( $params['utf8Mode'] );
		parent::__construct( $params );
		$this->platform = new MySQLPlatform(
			$this,
			$this->logger,
			$this->currentDomain,
			$this->errorLogger
		);
		$this->replicationReporter = new MysqlReplicationReporter(
			$params['topologyRole'],
			$this->logger,
			$params['srvCache'],
			$params['lagDetectionMethod'] ?? 'Seconds_Behind_Master',
			$params['lagDetectionOptions'] ?? [],
			!empty( $params['useGTIDs' ] )
		);
	}

	/**
	 * @return string
	 */
	public function getType() {
		return 'mysql';
	}

	/** @inheritDoc */
	protected function open( $server, $user, $password, $db, $schema, $tablePrefix ) {
		$this->close( __METHOD__ );

		if ( $schema !== null ) {
			throw $this->newExceptionAfterConnectError( "Got schema '$schema'; not supported." );
		}

		$this->installErrorHandler();
		try {
			$this->conn = $this->mysqlConnect( $server, $user, $password, $db );
		} catch ( RuntimeException $e ) {
			$this->restoreErrorHandler();
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
		$error = $this->restoreErrorHandler();

		if ( !$this->conn ) {
			throw $this->newExceptionAfterConnectError( $error ?: $this->lastError() );
		}

		try {
			$this->currentDomain = new DatabaseDomain(
				( $db !== '' ) ? $db : null,
				null,
				$tablePrefix
			);
			$this->platform->setCurrentDomain( $this->currentDomain );

			$set = [];
			if ( !$this->flagsHolder->getFlag( self::DBO_GAUGE ) ) {
				// Abstract over any excessive MySQL defaults
				$set[] = 'group_concat_max_len = 262144';
				// Set any custom settings defined by site config
				// https://dev.mysql.com/doc/refman/5.7/en/innodb-parameters.html
				foreach ( $this->connectionVariables as $var => $val ) {
					// Escape strings but not numbers to avoid MySQL complaining
					if ( !is_int( $val ) && !is_float( $val ) ) {
						$val = $this->addQuotes( $val );
					}
					$set[] = $this->platform->addIdentifierQuotes( $var ) . ' = ' . $val;
				}
			}

			if ( $set ) {
				$sql = 'SET ' . implode( ', ', $set );
				$flags = self::QUERY_NO_RETRY | self::QUERY_CHANGE_TRX;
				$query = new Query( $sql, $flags, 'SET' );
				// Avoid using query() so that replaceLostConnection() does not throw
				// errors if the transaction status is STATUS_TRX_ERROR
				$qs = $this->executeQuery( $query, __METHOD__, $flags );
				if ( $qs->res === false ) {
					$this->reportQueryError( $qs->message, $qs->code, $sql, __METHOD__ );
				}
			}
		} catch ( RuntimeException $e ) {
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
	}

	/** @inheritDoc */
	protected function doSelectDomain( DatabaseDomain $domain ) {
		if ( $domain->getSchema() !== null ) {
			throw new DBExpectedError(
				$this,
				__CLASS__ . ": domain '{$domain->getId()}' has a schema component"
			);
		}

		$database = $domain->getDatabase();
		// A null database means "don't care" so leave it as is and update the table prefix
		if ( $database === null ) {
			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				null,
				$domain->getTablePrefix()
			);
			$this->platform->setCurrentDomain( $this->currentDomain );

			return true;
		}

		if ( $database !== $this->getDBname() ) {
			$sql = 'USE ' . $this->addIdentifierQuotes( $database );
			$query = new Query( $sql, self::QUERY_CHANGE_TRX, 'USE' );
			$qs = $this->executeQuery( $query, __METHOD__, self::QUERY_CHANGE_TRX );
			if ( $qs->res === false ) {
				$this->reportQueryError( $qs->message, $qs->code, $sql, __METHOD__ );
				return false; // unreachable
			}
		}

		// Update that domain fields on success (no exception thrown)
		$this->currentDomain = $domain;
		$this->platform->setCurrentDomain( $domain );

		return true;
	}

	/**
	 * @return string
	 */
	public function lastError() {
		if ( $this->conn ) {
			// Even if it's non-zero, it can still be invalid
			$error = $this->mysqlError( $this->conn );
			if ( !$error ) {
				$error = $this->mysqlError();
			}
		} else {
			$error = $this->mysqlError() ?: $this->lastConnectError;
		}

		return $error;
	}

	/** @inheritDoc */
	protected function isInsertSelectSafe( array $insertOptions, array $selectOptions, $fname ) {
		$row = $this->replicationReporter->getReplicationSafetyInfo( $this, $fname );
		// For row-based-replication, the resulting changes will be relayed, not the query
		if ( $row->binlog_format === 'ROW' ) {
			return true;
		}
		// LIMIT requires ORDER BY on a unique key or it is non-deterministic
		if ( isset( $selectOptions['LIMIT'] ) ) {
			return false;
		}
		// In MySQL, an INSERT SELECT is only replication safe with row-based
		// replication or if innodb_autoinc_lock_mode is 0. When those
		// conditions aren't met, use non-native mode.
		// While we could try to determine if the insert is safe anyway by
		// checking if the target table has an auto-increment column that
		// isn't set in $varMap, that seems unlikely to be worth the extra
		// complexity.
		return (
			in_array( 'NO_AUTO_COLUMNS', $insertOptions ) ||
			(int)$row->innodb_autoinc_lock_mode === 0
		);
	}

	/** @inheritDoc */
	protected function checkInsertWarnings( Query $query, $fname ) {
		if ( $this->conn && $this->conn->warning_count ) {
			// Yeah it's weird. It's not iterable.
			$warnings = $this->conn->get_warnings();
			$done = $warnings === false;
			while ( !$done ) {
				if ( in_array( $warnings->errno, [
					// List based on https://dev.mysql.com/doc/refman/8.0/en/sql-mode.html#ignore-effect-on-execution
					1048, /* ER_BAD_NULL_ERROR */
					1526, /* ER_NO_PARTITION_FOR_GIVEN_VALUE */
					1748, /* ER_ROW_DOES_NOT_MATCH_GIVEN_PARTITION_SET */
					1242, /* ER_SUBQUERY_NO_1_ROW */
					1369, /* ER_VIEW_CHECK_FAILED */
					// Truncation and overflow per T108255
					1264, /* ER_WARN_DATA_OUT_OF_RANGE */
					1265, /* WARN_DATA_TRUNCATED */
				] ) ) {
					$this->reportQueryError(
						'Insert returned unacceptable warning: ' . $warnings->message,
						$warnings->errno,
						$query->getSQL(),
						$fname
					);
				}
				$done = !$warnings->next();
			}
		}
	}

	/** @inheritDoc */
	public function estimateRowCount(
		$tables,
		$var = '*',
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	): int {
		$conds = $this->platform->normalizeConditions( $conds, $fname );
		$column = $this->platform->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$options['EXPLAIN'] = true;
		$res = $this->select( $tables, $var, $conds, $fname, $options, $join_conds );
		if ( $res === false ) {
			return -1;
		}
		if ( !$res->numRows() ) {
			return 0;
		}

		$rows = 1;
		foreach ( $res as $plan ) {
			$rows *= $plan->rows > 0 ? $plan->rows : 1; // avoid resetting to zero
		}

		return (int)$rows;
	}

	/** @inheritDoc */
	public function tableExists( $table, $fname = __METHOD__ ) {
		[ $db, $pt ] = $this->platform->getDatabaseAndTableIdentifier( $table );
		if ( isset( $this->sessionTempTables[$db][$pt] ) ) {
			return true; // already known to exist and won't be found in the query anyway
		}

		return (bool)$this->newSelectQueryBuilder()
			->select( '1' )
			->from( 'information_schema.tables' )
			->where( [
				'table_schema' => $db,
				'table_name' => $pt,
			] )
			->caller( $fname )
			->fetchField();
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @return MySQLField|false
	 */
	public function fieldInfo( $table, $field ) {
		$query = new Query(
			"SELECT * FROM " . $this->tableName( $table ) . " LIMIT 1",
			self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$res = $this->query( $query, __METHOD__ );
		if ( !$res ) {
			return false;
		}
		/** @var MysqliResultWrapper $res */
		'@phan-var MysqliResultWrapper $res';
		return $res->getInternalFieldInfo( $field );
	}

	/** @inheritDoc */
	public function indexInfo( $table, $index, $fname = __METHOD__ ) {
		# https://dev.mysql.com/doc/mysql/en/SHOW_INDEX.html
		$query = new Query(
			'SHOW INDEX FROM ' . $this->tableName( $table ),
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SHOW'
		);
		$res = $this->query( $query, $fname );

		foreach ( $res as $row ) {
			if ( $row->Key_name === $index ) {
				return [ 'unique' => !$row->Non_unique ];
			}
		}

		return false;
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public function strencode( $s ) {
		return $this->mysqlRealEscapeString( $s );
	}

	/** @inheritDoc */
	public function serverIsReadOnly() {
		// Avoid SHOW to avoid internal temporary tables
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$query = new Query( "SELECT @@GLOBAL.read_only AS Value", $flags, 'SELECT' );
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchObject();

		return $row && $row->Value && $row->Value !== 'OFF';
	}

	/**
	 * @return string
	 */
	public function getSoftwareLink() {
		[ $variant ] = $this->getMySqlServerVariant();
		if ( $variant === 'MariaDB' ) {
			return '[{{int:version-db-mariadb-url}} MariaDB]';
		}

		return '[{{int:version-db-mysql-url}} MySQL]';
	}

	/**
	 * @return string[] (one of ("MariaDB","MySQL"), x.y.z version string)
	 */
	private function getMySqlServerVariant() {
		$version = $this->getServerVersion();

		// MariaDB includes its name in its version string; this is how MariaDB's version of
		// the mysql command-line client identifies MariaDB servers.
		// https://dev.mysql.com/doc/refman/8.0/en/information-functions.html#function_version
		// https://mariadb.com/kb/en/version/
		$parts = explode( '-', $version, 2 );
		$number = $parts[0];
		$suffix = $parts[1] ?? '';
		if ( str_contains( $suffix, 'MariaDB' ) || str_contains( $suffix, '-maria-' ) ) {
			$vendor = 'MariaDB';
		} else {
			$vendor = 'MySQL';
		}

		return [ $vendor, $number ];
	}

	/**
	 * @return string
	 */
	public function getServerVersion() {
		// MariaDB 10 adds the prefix "5.5.5-", and only some newer client libraries strip
		// it off (see RPL_VERSION_HACK in include/mysql_com.h).
		$version = $this->conn->server_info;
		if (
			str_starts_with( $version, '5.5.5-' ) &&
			( str_contains( $version, 'MariaDB' ) || str_contains( $version, '-maria-' ) )
		) {
			$version = substr( $version, strlen( '5.5.5-' ) );
		}
		return $version;
	}

	public function setSessionOptions( array $options ) {
		$sqlAssignments = [];

		if ( isset( $options['connTimeout'] ) ) {
			$encTimeout = (int)$options['connTimeout'];
			$sqlAssignments[] = "net_read_timeout=$encTimeout";
			$sqlAssignments[] = "net_write_timeout=$encTimeout";
		}
		if ( isset( $options['groupConcatMaxLen'] ) ) {
			$maxLength = (int)$options['groupConcatMaxLen'];
			$sqlAssignments[] = "group_concat_max_len=$maxLength";
		}

		if ( $sqlAssignments ) {
			$query = new Query(
				'SET ' . implode( ', ', $sqlAssignments ),
				self::QUERY_CHANGE_TRX | self::QUERY_CHANGE_NONE,
				'SET'
			);
			$this->query( $query, __METHOD__ );
		}
	}

	/**
	 * @param string &$sql
	 * @param string &$newLine
	 * @return bool
	 */
	public function streamStatementEnd( &$sql, &$newLine ) {
		if ( preg_match( '/^DELIMITER\s+(\S+)/i', $newLine, $m ) ) {
			$this->delimiter = $m[1];
			$newLine = '';
		}

		return parent::streamStatementEnd( $sql, $newLine );
	}

	/** @inheritDoc */
	public function doLockIsFree( string $lockName, string $method ) {
		$query = new Query( $this->platform->lockIsFreeSQLText( $lockName ), self::QUERY_CHANGE_LOCKS, 'SELECT' );
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return ( $row->unlocked == 1 );
	}

	/** @inheritDoc */
	public function doLock( string $lockName, string $method, int $timeout ) {
		$query = new Query( $this->platform->lockSQLText( $lockName, $timeout ), self::QUERY_CHANGE_LOCKS, 'SELECT' );
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return ( $row->acquired !== null ) ? (float)$row->acquired : null;
	}

	/** @inheritDoc */
	public function doUnlock( string $lockName, string $method ) {
		$query = new Query( $this->platform->unlockSQLText( $lockName ), self::QUERY_CHANGE_LOCKS, 'SELECT' );
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return ( $row->released == 1 );
	}

	/** @inheritDoc */
	protected function doFlushSession( $fname ) {
		// Note that RELEASE_ALL_LOCKS() is not supported well enough to use here.
		// https://mariadb.com/kb/en/release_all_locks/
		$releaseLockFields = [];
		foreach ( $this->sessionNamedLocks as $name => $info ) {
			$encName = $this->addQuotes( $this->platform->makeLockName( $name ) );
			$releaseLockFields[] = "RELEASE_LOCK($encName)";
		}
		if ( $releaseLockFields ) {
			$sql = 'SELECT ' . implode( ',', $releaseLockFields );
			$flags = self::QUERY_CHANGE_LOCKS | self::QUERY_NO_RETRY;
			$query = new Query( $sql, $flags, 'SELECT' );
			$qs = $this->executeQuery( $query, __METHOD__, $flags );
			if ( $qs->res === false ) {
				$this->reportQueryError( $qs->message, $qs->code, $sql, $fname, true );
			}
		}
	}

	/** @inheritDoc */
	public function upsert( $table, array $rows, $uniqueKeys, array $set, $fname = __METHOD__ ) {
		$identityKey = $this->platform->normalizeUpsertParams( $uniqueKeys, $rows );
		if ( !$rows ) {
			return;
		}
		$this->platform->assertValidUpsertSetArray( $set, $identityKey, $rows );

		$encTable = $this->tableName( $table );
		[ $sqlColumns, $sqlTuples ] = $this->platform->makeInsertLists( $rows );
		$sqlColumnAssignments = $this->makeList( $set, self::LIST_SET );
		// No need to expose __NEW.* since buildExcludedValue() uses VALUES(column)

		// https://mariadb.com/kb/en/insert-on-duplicate-key-update/
		// https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
		$sql =
			"INSERT INTO $encTable " .
			"($sqlColumns) VALUES $sqlTuples " .
			"ON DUPLICATE KEY UPDATE $sqlColumnAssignments";
		$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', $table );
		$this->query( $query, $fname );
		// Count updates of conflicting rows and row inserts equally toward the change count
		$this->lastQueryAffectedRows = min( $this->lastQueryAffectedRows, count( $rows ) );
	}

	/** @inheritDoc */
	public function replace( $table, $uniqueKeys, $rows, $fname = __METHOD__ ) {
		$this->platform->normalizeUpsertParams( $uniqueKeys, $rows );
		if ( !$rows ) {
			return;
		}
		$encTable = $this->tableName( $table );
		[ $sqlColumns, $sqlTuples ] = $this->platform->makeInsertLists( $rows );
		// https://dev.mysql.com/doc/refman/8.0/en/replace.html
		$sql = "REPLACE INTO $encTable ($sqlColumns) VALUES $sqlTuples";
		// Note that any auto-increment columns on conflicting rows will be reassigned
		// due to combined DELETE+INSERT semantics. This will be reflected in insertId().
		$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'REPLACE', $table );
		$this->query( $query, $fname );
		// Do not count deletions of conflicting rows toward the change count
		$this->lastQueryAffectedRows = min( $this->lastQueryAffectedRows, count( $rows ) );
	}

	/** @inheritDoc */
	protected function isConnectionError( $errno ) {
		// https://mariadb.com/kb/en/mariadb-error-codes/
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/client-error-reference.html
		return in_array( $errno, [ 2013, 2006, 2003, 1927, 1053 ], true );
	}

	/** @inheritDoc */
	protected function isQueryTimeoutError( $errno ) {
		// https://mariadb.com/kb/en/mariadb-error-codes/
		// https://dev.mysql.com/doc/refman/8.0/en/client-error-reference.html
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
		return in_array( $errno, [ 3024, 2062, 1969, 1028 ], true );
	}

	/** @inheritDoc */
	protected function isKnownStatementRollbackError( $errno ) {
		// https://mariadb.com/kb/en/mariadb-error-codes/
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
		return in_array(
			$errno,
			[ 3024, 1969, 1022, 1062, 1216, 1217, 1137, 1146, 1051, 1054 ],
			true
		);
	}

	/**
	 * @param string $oldName
	 * @param string $newName
	 * @param bool $temporary
	 * @param string $fname
	 * @return bool
	 */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$tmp = $temporary ? 'TEMPORARY ' : '';
		$newNameQuoted = $this->addIdentifierQuotes( $newName );
		$oldNameQuoted = $this->addIdentifierQuotes( $oldName );

		$query = new Query(
			"CREATE $tmp TABLE $newNameQuoted (LIKE $oldNameQuoted)",
			self::QUERY_PSEUDO_PERMANENT | self::QUERY_CHANGE_SCHEMA,
			$temporary ? 'CREATE TEMPORARY' : 'CREATE',
			// Use a dot to avoid double-prefixing in Database::getTempTableWrites()
			'.' . $newName
		);
		return $this->query( $query, $fname );
	}

	/**
	 * List all tables on the database
	 *
	 * @param string|null $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @return array
	 */
	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		$qb = $this->newSelectQueryBuilder()
			->select( 'table_name' )
			->from( 'information_schema.tables' )
			->where( [
				'table_schema' => $this->currentDomain->getDatabase(),
				'table_type' => 'BASE TABLE'
			] )
			->caller( $fname );
		if ( $prefix !== null && $prefix !== '' ) {
			$qb->andWhere( $this->expr(
				'table_name', IExpression::LIKE, new LikeValue( $prefix, $this->anyString() )
			) );
		}
		return $qb->fetchFieldValues();
	}

	/** @inheritDoc */
	public function selectSQLText(
		$tables,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	) {
		$sql = parent::selectSQLText( $tables, $vars, $conds, $fname, $options, $join_conds );
		// https://dev.mysql.com/doc/refman/5.7/en/optimizer-hints.html
		// https://mariadb.com/kb/en/library/aborting-statements/
		$timeoutMsec = intval( $options['MAX_EXECUTION_TIME'] ?? 0 );
		if ( $timeoutMsec > 0 ) {
			[ $vendor, $number ] = $this->getMySqlServerVariant();
			if ( $vendor === 'MariaDB' && version_compare( $number, '10.1.2', '>=' ) ) {
				$timeoutSec = $timeoutMsec / 1000;
				$sql = "SET STATEMENT max_statement_time=$timeoutSec FOR $sql";
			} elseif ( $vendor === 'MySQL' && version_compare( $number, '5.7.0', '>=' ) ) {
				$sql = preg_replace(
					'/^SELECT(?=\s)/',
					"SELECT /*+ MAX_EXECUTION_TIME($timeoutMsec)*/",
					$sql
				);
			}
		}

		return $sql;
	}

	protected function doSingleStatementQuery( string $sql ): QueryStatus {
		$conn = $this->getBindingHandle();

		// Hide packet warnings caused by things like dropped connections
		AtEase::suppressWarnings();
		$res = $conn->query( $sql );
		AtEase::restoreWarnings();
		// Note that mysqli::insert_id only reflects the last query statement
		$insertId = (int)$conn->insert_id;
		$this->lastQueryInsertId = $insertId;
		$this->sessionLastAutoRowId = $insertId ?: $this->sessionLastAutoRowId;

		return new QueryStatus(
			$res instanceof mysqli_result ? new MysqliResultWrapper( $this, $res ) : $res,
			$conn->affected_rows,
			$conn->error,
			$conn->errno
		);
	}

	/**
	 * @param string|null $server
	 * @param string|null $user
	 * @param string|null $password
	 * @param string|null $db
	 * @return mysqli|null
	 * @throws DBConnectionError
	 */
	private function mysqlConnect( $server, $user, $password, $db ) {
		if ( !function_exists( 'mysqli_init' ) ) {
			throw $this->newExceptionAfterConnectError(
				"MySQLi functions missing, have you compiled PHP with the --with-mysqli option?"
			);
		}

		// PHP 8.1.0+ throws exceptions by default. Turn that off for consistency.
		mysqli_report( MYSQLI_REPORT_OFF );

		// Other than mysql_connect, mysqli_real_connect expects an explicit port number
		// e.g. "localhost:1234" or "127.0.0.1:1234"
		// or Unix domain socket path
		// e.g. "localhost:/socket_path" or "localhost:/foo/bar:bar:bar"
		// colons are known to be used by Google AppEngine,
		// see <https://cloud.google.com/sql/docs/mysql/connect-app-engine>
		//
		// We need to parse the port or socket path out of $realServer
		$port = null;
		$socket = null;
		$hostAndPort = IPUtils::splitHostAndPort( $server );
		if ( $hostAndPort ) {
			$realServer = $hostAndPort[0];
			if ( $hostAndPort[1] ) {
				$port = $hostAndPort[1];
			}
		} elseif ( substr_count( $server, ':/' ) == 1 ) {
			// If we have a colon slash instead of a colon and a port number
			// after the ip or hostname, assume it's the Unix domain socket path
			[ $realServer, $socket ] = explode( ':', $server, 2 );
		} else {
			$realServer = $server;
		}

		$mysqli = mysqli_init();
		// Make affectedRows() for UPDATE reflect the number of matching rows, regardless
		// of whether any column values changed. This is what callers want to know and is
		// consistent with what Postgres and SQLite return.
		$flags = MYSQLI_CLIENT_FOUND_ROWS;
		if ( $this->ssl ) {
			$flags |= MYSQLI_CLIENT_SSL;
			$mysqli->ssl_set(
				$this->sslKeyPath,
				$this->sslCertPath,
				$this->sslCAFile,
				$this->sslCAPath,
				$this->sslCiphers
			);
		}
		if ( $this->getFlag( self::DBO_COMPRESS ) ) {
			$flags |= MYSQLI_CLIENT_COMPRESS;
		}
		if ( $this->getFlag( self::DBO_PERSISTENT ) ) {
			$realServer = 'p:' . $realServer;
		}

		if ( $this->utf8Mode ) {
			// Tell the server we're communicating with it in UTF-8.
			// This may engage various charset conversions.
			$mysqli->options( MYSQLI_SET_CHARSET_NAME, 'utf8' );
		} else {
			$mysqli->options( MYSQLI_SET_CHARSET_NAME, 'binary' );
		}

		$mysqli->options( MYSQLI_OPT_CONNECT_TIMEOUT, $this->connectTimeout ?: 3 );
		if ( $this->receiveTimeout ) {
			$mysqli->options( MYSQLI_OPT_READ_TIMEOUT, $this->receiveTimeout );
		}

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal socket seems set when used
		$ok = $mysqli->real_connect( $realServer, $user, $password, $db, $port, $socket, $flags );

		return $ok ? $mysqli : null;
	}

	/** @inheritDoc */
	protected function closeConnection() {
		return ( $this->conn instanceof mysqli ) ? mysqli_close( $this->conn ) : true;
	}

	/** @inheritDoc */
	protected function lastInsertId() {
		return $this->sessionLastAutoRowId;
	}

	protected function doHandleSessionLossPreconnect() {
		// https://mariadb.com/kb/en/last_insert_id/
		$this->sessionLastAutoRowId = 0;
	}

	/** @inheritDoc */
	public function insertId() {
		if ( $this->lastEmulatedInsertId === null ) {
			$conn = $this->getBindingHandle();
			// Note that mysqli::insert_id only reflects the last query statement
			$this->lastEmulatedInsertId = (int)$conn->insert_id;
		}

		return $this->lastEmulatedInsertId;
	}

	/**
	 * @return int
	 */
	public function lastErrno() {
		if ( $this->conn instanceof mysqli ) {
			return $this->conn->errno;
		} else {
			return mysqli_connect_errno();
		}
	}

	/**
	 * @param mysqli|null $conn Optional connection object
	 * @return string
	 */
	private function mysqlError( $conn = null ) {
		if ( $conn === null ) {
			return (string)mysqli_connect_error();
		} else {
			return $conn->error;
		}
	}

	/**
	 * @param mixed $s
	 */
	private function mysqlRealEscapeString( $s ): string {
		$conn = $this->getBindingHandle();

		return $conn->real_escape_string( (string)$s );
	}
}
