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

use RuntimeException;
use Wikimedia\Rdbms\Platform\MySQLPlatform;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\Replication\MysqlReplicationReporter;

/**
 * MySQL database abstraction layer.
 *
 * Defines methods independent of the used MySQL extension.
 *
 * TODO: This could probably be merged with DatabaseMysqli.
 * The classees were split to support a transition from the old "mysql" extension
 * to mysqli, and there may be an argument for retaining it in order to support
 * some future transition to something else, but it's complexity and YAGNI.
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 */
abstract class DatabaseMysqlBase extends Database {
	/** @var string|null */
	protected $sslKeyPath;
	/** @var string|null */
	protected $sslCertPath;
	/** @var string|null */
	protected $sslCAFile;
	/** @var string|null */
	protected $sslCAPath;
	/**
	 * Open SSL cipher list string
	 * @see https://www.openssl.org/docs/man1.0.2/man1/ciphers.html
	 * @var string|null
	 */
	protected $sslCiphers;
	/** @var bool Use experimental UTF-8 transmission encoding */
	protected $utf8Mode;

	/** @var SQLPlatform */
	protected $platform;

	/** @var MysqlReplicationReporter */
	protected $replicationReporter;

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
				$db && strlen( $db ) ? $db : null,
				null,
				$tablePrefix
			);
			$this->platform->setPrefix( $tablePrefix );

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
			$this->platform->setPrefix( $domain->getTablePrefix() );

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
		$this->platform->setPrefix( $domain->getTablePrefix() );

		return true;
	}

	/**
	 * Open a connection to a MySQL server
	 *
	 * @param string|null $server
	 * @param string|null $user
	 * @param string|null $password
	 * @param string|null $db
	 * @return mixed|null Driver connection handle
	 * @throws DBConnectionError
	 */
	abstract protected function mysqlConnect( $server, $user, $password, $db );

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

	/**
	 * Returns the text of the error message from previous MySQL operation
	 *
	 * @param resource|null $conn Raw connection
	 * @return string
	 */
	abstract protected function mysqlError( $conn = null );

	protected function isInsertSelectSafe( array $insertOptions, array $selectOptions ) {
		$row = $this->replicationReporter->getReplicationSafetyInfo( $this );
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

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * Takes same arguments as Database::select()
	 *
	 * @param string|array $tables
	 * @param string|array $var
	 * @param string|array $conds
	 * @param string $fname
	 * @param string|array $options
	 * @param array $join_conds
	 * @return int|false
	 */
	public function estimateRowCount(
		$tables,
		$var = '*',
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	) {
		$conds = $this->platform->normalizeConditions( $conds, $fname );
		$column = $this->platform->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$options['EXPLAIN'] = true;
		$res = $this->select( $tables, $var, $conds, $fname, $options, $join_conds );
		if ( $res === false ) {
			return false;
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

	public function tableExists( $table, $fname = __METHOD__ ) {
		// Split database and table into proper variables as Database::tableName() returns
		// shared tables prefixed with their database, which do not work in SHOW TABLES statements
		[ $database, , $prefix, $table ] = $this->platform->qualifiedTableComponents( $table );
		$tableName = "{$prefix}{$table}";

		if ( isset( $this->sessionTempTables[$tableName] ) ) {
			return true; // already known to exist and won't show in SHOW TABLES anyway
		}

		// We can't use buildLike() here, because it specifies an escape character
		// other than the backslash, which is the only one supported by SHOW TABLES
		// TODO: Avoid using platform's internal methods
		$encLike = $this->platform->escapeLikeInternal( $tableName, '\\' );

		// If the database has been specified (such as for shared tables), use "FROM"
		if ( $database !== '' ) {
			$encDatabase = $this->platform->addIdentifierQuotes( $database );
			$sql = "SHOW TABLES FROM $encDatabase LIKE '$encLike'";
		} else {
			$sql = "SHOW TABLES LIKE '$encLike'";
		}

		$query = new Query( $sql, self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE, 'SHOW', $table );
		$res = $this->query( $query, $fname );

		return $res->numRows() > 0;
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
			'SELECT',
			$table
		);
		$res = $this->query( $query, __METHOD__ );
		if ( !$res ) {
			return false;
		}
		/** @var MysqliResultWrapper $res */
		'@phan-var MysqliResultWrapper $res';
		return $res->getInternalFieldInfo( $field );
	}

	/**
	 * Get information about an index into an object
	 * Returns false if the index does not exist
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|array|null False or null on failure
	 */
	public function indexInfo( $table, $index, $fname = __METHOD__ ) {
		# https://dev.mysql.com/doc/mysql/en/SHOW_INDEX.html
		$index = $this->platform->indexName( $index );
		$query = new Query(
			'SHOW INDEX FROM ' . $this->tableName( $table ),
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SHOW',
			$table
		);
		$res = $this->query( $query, $fname );

		if ( !$res ) {
			return null;
		}

		$result = [];

		foreach ( $res as $row ) {
			if ( $row->Key_name == $index ) {
				$result[] = $row;
			}
		}

		return $result ?: false;
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public function strencode( $s ) {
		return $this->mysqlRealEscapeString( $s );
	}

	/**
	 * Escape special characters in a string for use in an SQL statement
	 *
	 * @param string $s
	 * @return mixed
	 */
	abstract protected function mysqlRealEscapeString( $s );

	public function serverIsReadOnly() {
		// Avoid SHOW to avoid internal temporary tables
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$query = new Query( "SELECT @@GLOBAL.read_only AS Value", $flags, 'SELECT' );
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchObject();

		return $row && (bool)$row->Value;
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
	protected function getMySqlServerVariant() {
		$version = $this->getServerVersion();

		// MariaDB includes its name in its version string; this is how MariaDB's version of
		// the mysql command-line client identifies MariaDB servers.
		// https://dev.mysql.com/doc/refman/8.0/en/information-functions.html#function_version
		// https://mariadb.com/kb/en/version/
		$parts = explode( '-', $version, 2 );
		$number = $parts[0];
		$suffix = $parts[1] ?? '';
		if ( strpos( $suffix, 'MariaDB' ) !== false || strpos( $suffix, '-maria-' ) !== false ) {
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
		$cache = $this->srvCache;
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$cache->makeGlobalKey( 'mysql-server-version', $this->getServerName() ),
			$cache::TTL_HOUR,
			function () use ( $fname ) {
				// Not using mysql_get_server_info() or similar for consistency: in the handshake,
				// MariaDB 10 adds the prefix "5.5.5-", and only some newer client libraries strip
				// it off (see RPL_VERSION_HACK in include/mysql_com.h).
				return $this->selectField( '', 'VERSION()', '', $fname );
			}
		);
	}

	/**
	 * @param array $options
	 */
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

	public function doLockIsFree( string $lockName, string $method ) {
		$query = new Query( $this->platform->lockIsFreeSQLText( $lockName ), self::QUERY_CHANGE_LOCKS, 'SELECT' );
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return ( $row->unlocked == 1 );
	}

	public function doLock( string $lockName, string $method, int $timeout ) {
		$query = new Query( $this->platform->lockSQLText( $lockName, $timeout ), self::QUERY_CHANGE_LOCKS, 'SELECT' );
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return ( $row->acquired !== null ) ? (float)$row->acquired : null;
	}

	public function doUnlock( string $lockName, string $method ) {
		$query = new Query( $this->platform->unlockSQLText( $lockName ), self::QUERY_CHANGE_LOCKS, 'SELECT' );
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return ( $row->released == 1 );
	}

	public function namedLocksEnqueue() {
		return true;
	}

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

	protected function doUpsert(
		string $table,
		array $rows,
		array $identityKey,
		array $set,
		string $fname
	) {
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

	protected function doReplace( $table, array $identityKey, array $rows, $fname ) {
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

	/**
	 * Determines if the last failure was due to a deadlock
	 *
	 * @return bool
	 */
	public function wasDeadlock() {
		return $this->lastErrno() == 1213;
	}

	/**
	 * Determines if the last failure was due to the database being read-only.
	 *
	 * @return bool
	 */
	public function wasReadOnlyError() {
		return $this->lastErrno() == 1223 ||
			( $this->lastErrno() == 1290 && strpos( $this->lastError(), '--read-only' ) !== false );
	}

	protected function isConnectionError( $errno ) {
		// https://mariadb.com/kb/en/mariadb-error-codes/
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/client-error-reference.html
		return in_array( $errno, [ 2013, 2006, 2003, 1927, 1053 ], true );
	}

	protected function isQueryTimeoutError( $errno ) {
		// https://mariadb.com/kb/en/mariadb-error-codes/
		// https://dev.mysql.com/doc/refman/8.0/en/client-error-reference.html
		// https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
		return in_array( $errno, [ 3024, 2062, 1969, 1028 ], true );
	}

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
			'CREATE',
			[ $oldName, $newName ]
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
		$query = new Query( "SHOW TABLES", self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE, 'SHOW' );
		$result = $this->query( $query, $fname );

		$endArray = [];

		foreach ( $result as $table ) {
			$vars = get_object_vars( $table );
			$table = array_pop( $vars );

			if ( !$prefix || strpos( $table, $prefix ) === 0 ) {
				$endArray[] = $table;
			}
		}

		return $endArray;
	}

	/**
	 * Lists VIEWs in the database
	 *
	 * @param string|null $prefix Only show VIEWs with this prefix, eg.
	 * unit_test_, or $wgDBprefix. Default: null, would return all views.
	 * @param string $fname Name of calling function
	 * @return array
	 * @since 1.22
	 */
	public function listViews( $prefix = null, $fname = __METHOD__ ) {
		// The name of the column containing the name of the VIEW
		$propertyName = 'Tables_in_' . $this->getDBname();
		$query = new Query(
			'SHOW FULL TABLES WHERE TABLE_TYPE = "VIEW"',
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SHOW'
		);
		// Query for the VIEWS
		$res = $this->query( $query, $fname );

		$allViews = [];
		foreach ( $res as $row ) {
			$allViews[] = $row->$propertyName;
		}

		if ( $prefix === null || $prefix === '' ) {
			return $allViews;
		}

		$filteredViews = [];
		foreach ( $allViews as $viewName ) {
			// Does the name of this VIEW start with the table-prefix?
			if ( strpos( $viewName, $prefix ) === 0 ) {
				$filteredViews[] = $viewName;
			}
		}

		return $filteredViews;
	}

	public function selectSQLText(
		$table,
		$vars,
		$conds = '',
		$fname = __METHOD__,
		$options = [],
		$join_conds = []
	) {
		$sql = parent::selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
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
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabaseMysqlBase::class, 'DatabaseMysqlBase' );
