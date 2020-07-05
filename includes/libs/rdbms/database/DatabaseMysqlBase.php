<?php
/**
 * This is the MySQL database abstraction layer.
 *
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
 * @ingroup Database
 */
namespace Wikimedia\Rdbms;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use RuntimeException;
use stdClass;
use Wikimedia\AtEase\AtEase;

/**
 * Database abstraction object for MySQL.
 * Defines methods independent on used MySQL extension.
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 */
abstract class DatabaseMysqlBase extends Database {
	/** @var MySQLMasterPos */
	protected $lastKnownReplicaPos;
	/** @var string Method to detect replica DB lag */
	protected $lagDetectionMethod;
	/** @var array Method to detect replica DB lag */
	protected $lagDetectionOptions = [];
	/** @var bool bool Whether to use GTID methods */
	protected $useGTIDs = false;
	/** @var string|null */
	protected $sslKeyPath;
	/** @var string|null */
	protected $sslCertPath;
	/** @var string|null */
	protected $sslCAFile;
	/** @var string|null */
	protected $sslCAPath;
	/** @var string[]|null */
	protected $sslCiphers;
	/** @var string sql_mode value to send on connection */
	protected $sqlMode;
	/** @var bool Use experimental UTF-8 transmission encoding */
	protected $utf8Mode;
	/** @var bool|null */
	protected $defaultBigSelects = null;

	/** @var bool|null */
	private $insertSelectIsSafe = null;
	/** @var stdClass|null */
	private $replicationInfoRow = null;

	// Cache getServerId() for 24 hours
	private const SERVER_ID_CACHE_TTL = 86400;

	/** @var float Warn if lag estimates are made for transactions older than this many seconds */
	private const LAG_STALE_WARN_THRESHOLD = 0.100;

	/**
	 * Additional $params include:
	 *   - lagDetectionMethod : set to one of (Seconds_Behind_Master,pt-heartbeat).
	 *       pt-heartbeat assumes the table is at heartbeat.heartbeat
	 *       and uses UTC timestamps in the heartbeat.ts column.
	 *       (https://www.percona.com/doc/percona-toolkit/2.2/pt-heartbeat.html)
	 *   - lagDetectionOptions : if using pt-heartbeat, this can be set to an array map to change
	 *       the default behavior. Normally, the heartbeat row with the server
	 *       ID of this server's master will be used. Set the "conds" field to
	 *       override the query conditions, e.g. ['shard' => 's1'].
	 *   - useGTIDs : use GTID methods like MASTER_GTID_WAIT() when possible.
	 *   - insertSelectIsSafe : force that native INSERT SELECT is or is not safe [default: null]
	 *   - sslKeyPath : path to key file [default: null]
	 *   - sslCertPath : path to certificate file [default: null]
	 *   - sslCAFile: path to a single certificate authority PEM file [default: null]
	 *   - sslCAPath : parth to certificate authority PEM directory [default: null]
	 *   - sslCiphers : array list of allowable ciphers [default: null]
	 * @param array $params
	 */
	public function __construct( array $params ) {
		$this->lagDetectionMethod = $params['lagDetectionMethod'] ?? 'Seconds_Behind_Master';
		$this->lagDetectionOptions = $params['lagDetectionOptions'] ?? [];
		$this->useGTIDs = !empty( $params['useGTIDs' ] );
		foreach ( [ 'KeyPath', 'CertPath', 'CAFile', 'CAPath', 'Ciphers' ] as $name ) {
			$var = "ssl{$name}";
			if ( isset( $params[$var] ) ) {
				$this->$var = $params[$var];
			}
		}
		$this->sqlMode = $params['sqlMode'] ?? null;
		$this->utf8Mode = !empty( $params['utf8Mode'] );
		$this->insertSelectIsSafe = isset( $params['insertSelectIsSafe'] )
			? (bool)$params['insertSelectIsSafe'] : null;

		parent::__construct( $params );
	}

	/**
	 * @return string
	 */
	public function getType() {
		return 'mysql';
	}

	protected function open( $server, $user, $password, $dbName, $schema, $tablePrefix ) {
		$this->close( __METHOD__ );

		if ( $schema !== null ) {
			throw $this->newExceptionAfterConnectError( "Got schema '$schema'; not supported." );
		}

		$this->server = $server;
		$this->user = $user;
		$this->password = $password;

		$this->installErrorHandler();
		try {
			$this->conn = $this->mysqlConnect( $this->server, $dbName );
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
				strlen( $dbName ) ? $dbName : null,
				null,
				$tablePrefix
			);
			// Abstract over any insane MySQL defaults
			$set = [ 'group_concat_max_len = 262144' ];
			// Set SQL mode, default is turning them all off, can be overridden or skipped with null
			if ( is_string( $this->sqlMode ) ) {
				$set[] = 'sql_mode = ' . $this->addQuotes( $this->sqlMode );
			}
			// Set any custom settings defined by site config
			// (e.g. https://dev.mysql.com/doc/refman/4.1/en/innodb-parameters.html)
			foreach ( $this->connectionVariables as $var => $val ) {
				// Escape strings but not numbers to avoid MySQL complaining
				if ( !is_int( $val ) && !is_float( $val ) ) {
					$val = $this->addQuotes( $val );
				}
				$set[] = $this->addIdentifierQuotes( $var ) . ' = ' . $val;
			}

			// @phan-suppress-next-next-line PhanRedundantCondition
			// If kept for safety and to avoid broken query
			if ( $set ) {
				$this->query(
					'SET ' . implode( ', ', $set ),
					__METHOD__,
					self::QUERY_IGNORE_DBO_TRX | self::QUERY_NO_RETRY | self::QUERY_CHANGE_TRX
				);
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

			return true;
		}

		if ( $database !== $this->getDBname() ) {
			$sql = 'USE ' . $this->addIdentifierQuotes( $database );
			list( $res, $err, $errno ) =
				$this->executeQuery( $sql, __METHOD__, self::QUERY_IGNORE_DBO_TRX );

			if ( $res === false ) {
				$this->reportQueryError( $err, $errno, $sql, __METHOD__ );
				return false; // unreachable
			}
		}

		// Update that domain fields on success (no exception thrown)
		$this->currentDomain = $domain;

		return true;
	}

	/**
	 * Open a connection to a MySQL server
	 *
	 * @param string $realServer
	 * @param string|null $dbName
	 * @return mixed|null Driver connection handle
	 * @throws DBConnectionError
	 */
	abstract protected function mysqlConnect( $realServer, $dbName );

	/**
	 * @param IResultWrapper|resource $res
	 * @throws DBUnexpectedError
	 */
	public function freeResult( $res ) {
		AtEase::suppressWarnings();
		$ok = $this->mysqlFreeResult( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();
		if ( !$ok ) {
			throw new DBUnexpectedError( $this, "Unable to free MySQL result" );
		}
	}

	/**
	 * Free result memory
	 *
	 * @param resource $res Raw result
	 * @return bool
	 */
	abstract protected function mysqlFreeResult( $res );

	/**
	 * @param IResultWrapper|resource $res
	 * @return stdClass|bool
	 * @throws DBUnexpectedError
	 */
	public function fetchObject( $res ) {
		AtEase::suppressWarnings();
		$row = $this->mysqlFetchObject( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_object does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_object can cause.
		// See https://dev.mysql.com/doc/refman/5.0/en/mysql-fetch-row.html.
		if ( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError(
				$this,
				'Error in fetchObject(): ' . htmlspecialchars( $this->lastError() )
			);
		}

		return $row;
	}

	/**
	 * Fetch a result row as an object
	 *
	 * @param resource $res Raw result
	 * @return stdClass
	 */
	abstract protected function mysqlFetchObject( $res );

	/**
	 * @param IResultWrapper|resource $res
	 * @return array|bool
	 * @throws DBUnexpectedError
	 */
	public function fetchRow( $res ) {
		AtEase::suppressWarnings();
		$row = $this->mysqlFetchArray( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_array does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_array can cause.
		// See https://dev.mysql.com/doc/refman/5.0/en/mysql-fetch-row.html.
		if ( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError(
				$this,
				'Error in fetchRow(): ' . htmlspecialchars( $this->lastError() )
			);
		}

		return $row;
	}

	/**
	 * Fetch a result row as an associative and numeric array
	 *
	 * @param resource $res Raw result
	 * @return array|false
	 */
	abstract protected function mysqlFetchArray( $res );

	/**
	 * @throws DBUnexpectedError
	 * @param IResultWrapper|resource $res
	 * @return int
	 */
	public function numRows( $res ) {
		if ( is_bool( $res ) ) {
			$n = 0;
		} else {
			AtEase::suppressWarnings();
			$n = $this->mysqlNumRows( ResultWrapper::unwrap( $res ) );
			AtEase::restoreWarnings();
		}

		// Unfortunately, mysql_num_rows does not reset the last errno.
		// We are not checking for any errors here, since
		// there are no errors mysql_num_rows can cause.
		// See https://dev.mysql.com/doc/refman/5.0/en/mysql-fetch-row.html.
		// See https://phabricator.wikimedia.org/T44430
		return $n;
	}

	/**
	 * Get number of rows in result
	 *
	 * @param resource $res Raw result
	 * @return int
	 */
	abstract protected function mysqlNumRows( $res );

	/**
	 * @param IResultWrapper|resource $res
	 * @return int
	 */
	public function numFields( $res ) {
		return $this->mysqlNumFields( ResultWrapper::unwrap( $res ) );
	}

	/**
	 * Get number of fields in result
	 *
	 * @param resource $res Raw result
	 * @return int
	 */
	abstract protected function mysqlNumFields( $res );

	/**
	 * @param IResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	public function fieldName( $res, $n ) {
		return $this->mysqlFieldName( ResultWrapper::unwrap( $res ), $n );
	}

	/**
	 * Get the name of the specified field in a result
	 *
	 * @param IResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	abstract protected function mysqlFieldName( $res, $n );

	/**
	 * mysql_field_type() wrapper
	 * @param IResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	public function fieldType( $res, $n ) {
		return $this->mysqlFieldType( ResultWrapper::unwrap( $res ), $n );
	}

	/**
	 * Get the type of the specified field in a result
	 *
	 * @param IResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	abstract protected function mysqlFieldType( $res, $n );

	/**
	 * @param IResultWrapper|resource $res
	 * @param int $row
	 * @return bool
	 */
	public function dataSeek( $res, $row ) {
		return $this->mysqlDataSeek( ResultWrapper::unwrap( $res ), $row );
	}

	/**
	 * Move internal result pointer
	 *
	 * @param IResultWrapper|resource $res
	 * @param int $row
	 * @return bool
	 */
	abstract protected function mysqlDataSeek( $res, $row );

	/**
	 * @return string
	 */
	public function lastError() {
		if ( $this->conn ) {
			# Even if it's non-zero, it can still be invalid
			AtEase::suppressWarnings();
			$error = $this->mysqlError( $this->conn );
			if ( !$error ) {
				$error = $this->mysqlError();
			}
			AtEase::restoreWarnings();
		} else {
			$error = $this->mysqlError();
		}
		if ( $error ) {
			$error .= ' (' . $this->server . ')';
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

	protected function wasQueryTimeout( $error, $errno ) {
		// https://dev.mysql.com/doc/refman/8.0/en/client-error-reference.html
		// https://phabricator.wikimedia.org/T170638
		return in_array( $errno, [ 2062, 3024 ] );
	}

	protected function isInsertSelectSafe( array $insertOptions, array $selectOptions ) {
		$row = $this->getReplicationSafetyInfo();
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
	 * @return stdClass Process cached row
	 */
	protected function getReplicationSafetyInfo() {
		if ( $this->replicationInfoRow === null ) {
			$this->replicationInfoRow = $this->selectRow(
				false,
				[
					'innodb_autoinc_lock_mode' => '@@innodb_autoinc_lock_mode',
					'binlog_format' => '@@binlog_format',
				],
				[],
				__METHOD__
			);
		}

		return $this->replicationInfoRow;
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * Takes same arguments as Database::select()
	 *
	 * @param string|array $table
	 * @param string|array $var
	 * @param string|array $conds
	 * @param string $fname
	 * @param string|array $options
	 * @param array $join_conds
	 * @return bool|int
	 */
	public function estimateRowCount( $table, $var = '*', $conds = '',
		$fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->normalizeConditions( $conds, $fname );
		$column = $this->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $var, $conds, $fname, $options, $join_conds );
		if ( $res === false ) {
			return false;
		}
		if ( !$this->numRows( $res ) ) {
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
		list( $database, , $prefix, $table ) = $this->qualifiedTableComponents( $table );
		$tableName = "{$prefix}{$table}";

		if ( isset( $this->sessionTempTables[$tableName] ) ) {
			return true; // already known to exist and won't show in SHOW TABLES anyway
		}

		// We can't use buildLike() here, because it specifies an escape character
		// other than the backslash, which is the only one supported by SHOW TABLES
		$encLike = $this->escapeLikeInternal( $tableName, '\\' );

		// If the database has been specified (such as for shared tables), use "FROM"
		if ( $database !== '' ) {
			$encDatabase = $this->addIdentifierQuotes( $database );
			$sql = "SHOW TABLES FROM $encDatabase LIKE '$encLike'";
		} else {
			$sql = "SHOW TABLES LIKE '$encLike'";
		}

		$res = $this->query(
			$sql,
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		return $res->numRows() > 0;
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @return bool|MySQLField
	 */
	public function fieldInfo( $table, $field ) {
		$res = $this->query(
			"SELECT * FROM " . $this->tableName( $table ) . " LIMIT 1",
			__METHOD__,
			self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		if ( !$res ) {
			return false;
		}
		$n = $this->mysqlNumFields( ResultWrapper::unwrap( $res ) );
		for ( $i = 0; $i < $n; $i++ ) {
			$meta = $this->mysqlFetchField( ResultWrapper::unwrap( $res ), $i );
			if ( $field == $meta->name ) {
				return new MySQLField( $meta );
			}
		}

		return false;
	}

	/**
	 * Get column information from a result
	 *
	 * @param resource $res Raw result
	 * @param int $n
	 * @return stdClass
	 */
	abstract protected function mysqlFetchField( $res, $n );

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
		$index = $this->indexName( $index );

		$res = $this->query(
			'SHOW INDEX FROM ' . $this->tableName( $table ),
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

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
	 * @param string $s
	 * @return mixed
	 */
	abstract protected function mysqlRealEscapeString( $s );

	/**
	 * MySQL uses `backticks` for identifier quoting instead of the sql standard "double quotes".
	 *
	 * @param string $s
	 * @return string
	 */
	public function addIdentifierQuotes( $s ) {
		// Characters in the range \u0001-\uFFFF are valid in a quoted identifier
		// Remove NUL bytes and escape backticks by doubling
		return '`' . str_replace( [ "\0", '`' ], [ '', '``' ], $s ) . '`';
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return strlen( $name ) && $name[0] == '`' && substr( $name, -1, 1 ) == '`';
	}

	protected function doGetLag() {
		if ( $this->getLagDetectionMethod() === 'pt-heartbeat' ) {
			return $this->getLagFromPtHeartbeat();
		} else {
			return $this->getLagFromSlaveStatus();
		}
	}

	/**
	 * @return string
	 */
	protected function getLagDetectionMethod() {
		return $this->lagDetectionMethod;
	}

	/**
	 * @return bool|int
	 */
	protected function getLagFromSlaveStatus() {
		$res = $this->query(
			'SHOW SLAVE STATUS',
			__METHOD__,
			self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res ? $res->fetchObject() : false;
		// If the server is not replicating, there will be no row
		if ( $row && strval( $row->Seconds_Behind_Master ) !== '' ) {
			return intval( $row->Seconds_Behind_Master );
		}

		return false;
	}

	/**
	 * @return bool|float
	 */
	protected function getLagFromPtHeartbeat() {
		$options = $this->lagDetectionOptions;

		$currentTrxInfo = $this->getRecordedTransactionLagStatus();
		if ( $currentTrxInfo ) {
			// There is an active transaction and the initial lag was already queried
			$staleness = microtime( true ) - $currentTrxInfo['since'];
			if ( $staleness > self::LAG_STALE_WARN_THRESHOLD ) {
				// Avoid returning higher and higher lag value due to snapshot age
				// given that the isolation level will typically be REPEATABLE-READ
				$this->queryLogger->warning(
					"Using cached lag value for {db_server} due to active transaction",
					$this->getLogContext( [
						'method' => __METHOD__,
						'age' => $staleness,
						'exception' => new RuntimeException()
					] )
				);
			}

			return $currentTrxInfo['lag'];
		}

		if ( isset( $options['conds'] ) ) {
			// Best method for multi-DC setups: use logical channel names
			$data = $this->getHeartbeatData( $options['conds'] );
		} else {
			// Standard method: use master server ID (works with stock pt-heartbeat)
			$masterInfo = $this->getMasterServerInfo();
			if ( !$masterInfo ) {
				$this->queryLogger->error(
					"Unable to query master of {db_server} for server ID",
					$this->getLogContext( [
						'method' => __METHOD__
					] )
				);

				return false; // could not get master server ID
			}

			$conds = [ 'server_id' => intval( $masterInfo['serverId'] ) ];
			$data = $this->getHeartbeatData( $conds );
		}

		list( $time, $nowUnix ) = $data;
		if ( $time !== null ) {
			// @time is in ISO format like "2015-09-25T16:48:10.000510"
			$dateTime = new DateTime( $time, new DateTimeZone( 'UTC' ) );
			$timeUnix = (int)$dateTime->format( 'U' ) + $dateTime->format( 'u' ) / 1e6;

			return max( $nowUnix - $timeUnix, 0.0 );
		}

		$this->queryLogger->error(
			"Unable to find pt-heartbeat row for {db_server}",
			$this->getLogContext( [
				'method' => __METHOD__
			] )
		);

		return false;
	}

	protected function getMasterServerInfo() {
		$cache = $this->srvCache;
		$key = $cache->makeGlobalKey(
			'mysql',
			'master-info',
			// Using one key for all cluster replica DBs is preferable
			$this->topologyRootMaster ?? $this->getServer()
		);
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			function () use ( $cache, $key, $fname ) {
				// Get and leave a lock key in place for a short period
				if ( !$cache->lock( $key, 0, 10 ) ) {
					return false; // avoid master connection spike slams
				}

				$conn = $this->getLazyMasterHandle();
				if ( !$conn ) {
					return false; // something is misconfigured
				}

				$flags = self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
				// Connect to and query the master; catch errors to avoid outages
				try {
					$res = $conn->query( 'SELECT @@server_id AS id', $fname, $flags );
					$row = $res ? $res->fetchObject() : false;
					$id = $row ? (int)$row->id : 0;
				} catch ( DBError $e ) {
					$id = 0;
				}

				// Cache the ID if it was retrieved
				return $id ? [ 'serverId' => $id, 'asOf' => time() ] : false;
			}
		);
	}

	/**
	 * @param array $conds WHERE clause conditions to find a row
	 * @return array (heartbeat `ts` column value or null, UNIX timestamp) for the newest beat
	 * @see https://www.percona.com/doc/percona-toolkit/2.1/pt-heartbeat.html
	 */
	protected function getHeartbeatData( array $conds ) {
		// Query time and trip time are not counted
		$nowUnix = microtime( true );
		$whereSQL = $this->makeList( $conds, self::LIST_AND );
		// Use ORDER BY for channel based queries since that field might not be UNIQUE.
		// Note: this would use "TIMESTAMPDIFF(MICROSECOND,ts,UTC_TIMESTAMP(6))" but the
		// percision field is not supported in MySQL <= 5.5.
		$res = $this->query(
			"SELECT ts FROM heartbeat.heartbeat WHERE $whereSQL ORDER BY ts DESC LIMIT 1",
			__METHOD__,
			self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res ? $res->fetchObject() : false;

		return [ $row ? $row->ts : null, $nowUnix ];
	}

	protected function getApproximateLagStatus() {
		if ( $this->getLagDetectionMethod() === 'pt-heartbeat' ) {
			// Disable caching since this is fast enough and we don't wan't
			// to be *too* pessimistic by having both the cache TTL and the
			// pt-heartbeat interval count as lag in getSessionLagStatus()
			return parent::getApproximateLagStatus();
		}

		$key = $this->srvCache->makeGlobalKey( 'mysql-lag', $this->getServer() );
		$approxLag = $this->srvCache->get( $key );
		if ( !$approxLag ) {
			$approxLag = parent::getApproximateLagStatus();
			$this->srvCache->set( $key, $approxLag, 1 );
		}

		return $approxLag;
	}

	public function masterPosWait( DBMasterPos $pos, $timeout ) {
		if ( !( $pos instanceof MySQLMasterPos ) ) {
			throw new InvalidArgumentException( "Position not an instance of MySQLMasterPos" );
		}

		if ( $this->topologyRole === self::ROLE_STATIC_CLONE ) {
			$this->queryLogger->debug(
				"Bypassed replication wait; database has a static dataset",
				$this->getLogContext( [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
			);

			return 0; // this is a copy of a read-only dataset with no master DB
		} elseif ( $this->lastKnownReplicaPos && $this->lastKnownReplicaPos->hasReached( $pos ) ) {
			$this->queryLogger->debug(
				"Bypassed replication wait; replication known to have reached {raw_pos}",
				$this->getLogContext( [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
			);

			return 0; // already reached this point for sure
		}

		// Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		if ( $pos->getGTIDs() ) {
			// Get the GTIDs from this replica server too see the domains (channels)
			$refPos = $this->getReplicaPos();
			if ( !$refPos ) {
				$this->queryLogger->error(
					"Could not get replication position on replica DB to compare to {raw_pos}",
					$this->getLogContext( [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
				);

				return -1; // this is the master itself?
			}
			// GTIDs with domains (channels) that are active and are present on the replica
			$gtidsWait = $pos::getRelevantActiveGTIDs( $pos, $refPos );
			if ( !$gtidsWait ) {
				$this->queryLogger->error(
					"No active GTIDs in {raw_pos} share a domain with those in {current_pos}",
					$this->getLogContext( [
						'method' => __METHOD__,
						'raw_pos' => $pos,
						'current_pos' => $refPos
					] )
				);

				return -1; // $pos is from the wrong cluster?
			}
			// Wait on the GTID set
			$gtidArg = $this->addQuotes( implode( ',', $gtidsWait ) );
			if ( strpos( $gtidArg, ':' ) !== false ) {
				// MySQL GTIDs, e.g "source_id:transaction_id"
				$sql = "SELECT WAIT_FOR_EXECUTED_GTID_SET($gtidArg, $timeout)";
			} else {
				// MariaDB GTIDs, e.g."domain:server:sequence"
				$sql = "SELECT MASTER_GTID_WAIT($gtidArg, $timeout)";
			}
			$waitPos = implode( ',', $gtidsWait );
		} else {
			// Wait on the binlog coordinates
			$encFile = $this->addQuotes( $pos->getLogFile() );
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			$encPos = intval( $pos->getLogPosition()[$pos::CORD_EVENT] );
			$sql = "SELECT MASTER_POS_WAIT($encFile, $encPos, $timeout)";
			$waitPos = $pos->__toString();
		}

		$start = microtime( true );
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$res = $this->query( $sql, __METHOD__, $flags );
		$row = $this->fetchRow( $res );
		$seconds = max( microtime( true ) - $start, 0 );

		// Result can be NULL (error), -1 (timeout), or 0+ per the MySQL manual
		$status = ( $row[0] !== null ) ? intval( $row[0] ) : null;
		if ( $status === null ) {
			$this->replLogger->error(
				"An error occurred while waiting for replication to reach {raw_pos}",
				$this->getLogContext( [
					'raw_pos' => $pos,
					'wait_pos' => $waitPos,
					'sql' => $sql,
					'seconds_waited' => $seconds,
					'exception' => new RuntimeException()
				] )
			);
		} elseif ( $status < 0 ) {
			$this->replLogger->error(
				"Timed out waiting for replication to reach {raw_pos}",
				$this->getLogContext( [
					'raw_pos' => $pos,
					'wait_pos' => $waitPos,
					'timeout' => $timeout,
					'sql' => $sql,
					'seconds_waited' => $seconds,
					'exception' => new RuntimeException()
				] )
			);
		} elseif ( $status >= 0 ) {
			$this->replLogger->debug(
				"Replication has reached {raw_pos}",
				$this->getLogContext( [
					'raw_pos' => $pos,
					'wait_pos' => $waitPos,
					'seconds_waited' => $seconds,
				] )
			);
			// Remember that this position was reached to save queries next time
			$this->lastKnownReplicaPos = $pos;
		}

		return $status;
	}

	/**
	 * Get the position of the master from SHOW SLAVE STATUS
	 *
	 * @return MySQLMasterPos|bool
	 */
	public function getReplicaPos() {
		$now = microtime( true ); // as-of-time *before* fetching GTID variables

		if ( $this->useGTIDs() ) {
			// Try to use GTIDs, fallbacking to binlog positions if not possible
			$data = $this->getServerGTIDs( __METHOD__ );
			// Use gtid_slave_pos for MariaDB and gtid_executed for MySQL
			foreach ( [ 'gtid_slave_pos', 'gtid_executed' ] as $name ) {
				if ( isset( $data[$name] ) && strlen( $data[$name] ) ) {
					return new MySQLMasterPos( $data[$name], $now );
				}
			}
		}

		$data = $this->getServerRoleStatus( 'SLAVE', __METHOD__ );
		if ( $data && strlen( $data['Relay_Master_Log_File'] ) ) {
			return new MySQLMasterPos(
				"{$data['Relay_Master_Log_File']}/{$data['Exec_Master_Log_Pos']}",
				$now
			);
		}

		return false;
	}

	/**
	 * Get the position of the master from SHOW MASTER STATUS
	 *
	 * @return MySQLMasterPos|bool
	 */
	public function getMasterPos() {
		$now = microtime( true ); // as-of-time *before* fetching GTID variables

		$pos = false;
		if ( $this->useGTIDs() ) {
			// Try to use GTIDs, fallbacking to binlog positions if not possible
			$data = $this->getServerGTIDs( __METHOD__ );
			// Use gtid_binlog_pos for MariaDB and gtid_executed for MySQL
			foreach ( [ 'gtid_binlog_pos', 'gtid_executed' ] as $name ) {
				if ( isset( $data[$name] ) && strlen( $data[$name] ) ) {
					$pos = new MySQLMasterPos( $data[$name], $now );
					break;
				}
			}
			// Filter domains that are inactive or not relevant to the session
			if ( $pos ) {
				$pos->setActiveOriginServerId( $this->getServerId() );
				$pos->setActiveOriginServerUUID( $this->getServerUUID() );
				if ( isset( $data['gtid_domain_id'] ) ) {
					$pos->setActiveDomain( $data['gtid_domain_id'] );
				}
			}
		}

		if ( !$pos ) {
			$data = $this->getServerRoleStatus( 'MASTER', __METHOD__ );
			if ( $data && strlen( $data['File'] ) ) {
				$pos = new MySQLMasterPos( "{$data['File']}/{$data['Position']}", $now );
			}
		}

		return $pos;
	}

	/**
	 * @return int
	 * @throws DBQueryError If the variable doesn't exist for some reason
	 */
	protected function getServerId() {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeGlobalKey( 'mysql-server-id', $this->getServer() ),
			self::SERVER_ID_CACHE_TTL,
			function () use ( $fname ) {
				$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
				$res = $this->query( "SELECT @@server_id AS id", $fname, $flags );

				return intval( $this->fetchObject( $res )->id );
			}
		);
	}

	/**
	 * @return string|null
	 */
	protected function getServerUUID() {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeGlobalKey( 'mysql-server-uuid', $this->getServer() ),
			self::SERVER_ID_CACHE_TTL,
			function () use ( $fname ) {
				$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
				$res = $this->query( "SHOW GLOBAL VARIABLES LIKE 'server_uuid'", $fname, $flags );
				$row = $this->fetchObject( $res );

				return $row ? $row->Value : null;
			}
		);
	}

	/**
	 * @param string $fname
	 * @return string[]
	 */
	protected function getServerGTIDs( $fname = __METHOD__ ) {
		$map = [];

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;

		// Get global-only variables like gtid_executed
		$res = $this->query( "SHOW GLOBAL VARIABLES LIKE 'gtid_%'", $fname, $flags );
		foreach ( $res as $row ) {
			$map[$row->Variable_name] = $row->Value;
		}
		// Get session-specific (e.g. gtid_domain_id since that is were writes will log)
		$res = $this->query( "SHOW SESSION VARIABLES LIKE 'gtid_%'", $fname, $flags );
		foreach ( $res as $row ) {
			$map[$row->Variable_name] = $row->Value;
		}

		return $map;
	}

	/**
	 * @param string $role One of "MASTER"/"SLAVE"
	 * @param string $fname
	 * @return string[] Latest available server status row
	 */
	protected function getServerRoleStatus( $role, $fname = __METHOD__ ) {
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$res = $this->query( "SHOW $role STATUS", $fname, $flags );

		return $res->fetchRow() ?: [];
	}

	public function serverIsReadOnly() {
		// Avoid SHOW to avoid internal temporary tables
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$res = $this->query( "SELECT @@GLOBAL.read_only AS Value", __METHOD__, $flags );
		$row = $this->fetchObject( $res );

		return $row ? (bool)$row->Value : false;
	}

	/**
	 * @param string $index
	 * @return string
	 */
	public function useIndexClause( $index ) {
		return "FORCE INDEX (" . $this->indexName( $index ) . ")";
	}

	/**
	 * @param string $index
	 * @return string
	 */
	public function ignoreIndexClause( $index ) {
		return "IGNORE INDEX (" . $this->indexName( $index ) . ")";
	}

	/**
	 * @return string
	 */
	public function getSoftwareLink() {
		// MariaDB includes its name in its version string; this is how MariaDB's version of
		// the mysql command-line client identifies MariaDB servers (see mariadb_connection()
		// in libmysql/libmysql.c).
		$version = $this->getServerVersion();
		if ( strpos( $version, 'MariaDB' ) !== false || strpos( $version, '-maria-' ) !== false ) {
			return '[{{int:version-db-mariadb-url}} MariaDB]';
		}

		// Percona Server's version suffix is not very distinctive, and @@version_comment
		// doesn't give the necessary info for source builds, so assume the server is MySQL.
		// (Even Percona's version of mysql doesn't try to make the distinction.)
		return '[{{int:version-db-mysql-url}} MySQL]';
	}

	/**
	 * @return string
	 */
	public function getServerVersion() {
		$cache = $this->srvCache;
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$cache->makeGlobalKey( 'mysql-server-version', $this->getServer() ),
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
		if ( isset( $options['connTimeout'] ) ) {
			$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_TRX;
			$timeout = (int)$options['connTimeout'];
			$this->query( "SET net_read_timeout=$timeout", __METHOD__, $flags );
			$this->query( "SET net_write_timeout=$timeout", __METHOD__, $flags );
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

	/**
	 * Check to see if a named lock is available. This is non-blocking.
	 *
	 * @param string $lockName Name of lock to poll
	 * @param string $method Name of method calling us
	 * @return bool
	 * @since 1.20
	 */
	public function lockIsFree( $lockName, $method ) {
		if ( !parent::lockIsFree( $lockName, $method ) ) {
			return false; // already held
		}

		$encName = $this->addQuotes( $this->makeLockName( $lockName ) );

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$res = $this->query( "SELECT IS_FREE_LOCK($encName) AS lockstatus", $method, $flags );
		$row = $this->fetchObject( $res );

		return ( $row->lockstatus == 1 );
	}

	/**
	 * @param string $lockName
	 * @param string $method
	 * @param int $timeout
	 * @return bool
	 */
	public function lock( $lockName, $method, $timeout = 5 ) {
		$encName = $this->addQuotes( $this->makeLockName( $lockName ) );

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$res = $this->query( "SELECT GET_LOCK($encName, $timeout) AS lockstatus", $method, $flags );
		$row = $this->fetchObject( $res );

		if ( $row->lockstatus == 1 ) {
			parent::lock( $lockName, $method, $timeout ); // record
			return true;
		}

		$this->queryLogger->info( __METHOD__ . " failed to acquire lock '{lockname}'",
			[ 'lockname' => $lockName ] );

		return false;
	}

	/**
	 * FROM MYSQL DOCS:
	 * https://dev.mysql.com/doc/refman/5.0/en/miscellaneous-functions.html#function_release-lock
	 * @param string $lockName
	 * @param string $method
	 * @return bool
	 */
	public function unlock( $lockName, $method ) {
		$encName = $this->addQuotes( $this->makeLockName( $lockName ) );

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$res = $this->query( "SELECT RELEASE_LOCK($encName) as lockstatus", $method, $flags );
		$row = $this->fetchObject( $res );

		if ( $row->lockstatus == 1 ) {
			parent::unlock( $lockName, $method ); // record
			return true;
		}

		$this->queryLogger->warning( __METHOD__ . " failed to release lock '$lockName'\n" );

		return false;
	}

	private function makeLockName( $lockName ) {
		// https://dev.mysql.com/doc/refman/5.7/en/miscellaneous-functions.html#function_get-lock
		// Newer version enforce a 64 char length limit.
		return ( strlen( $lockName ) > 64 ) ? sha1( $lockName ) : $lockName;
	}

	public function namedLocksEnqueue() {
		return true;
	}

	public function tableLocksHaveTransactionScope() {
		return false; // tied to TCP connection
	}

	protected function doLockTables( array $read, array $write, $method ) {
		$items = [];
		foreach ( $write as $table ) {
			$items[] = $this->tableName( $table ) . ' WRITE';
		}
		foreach ( $read as $table ) {
			$items[] = $this->tableName( $table ) . ' READ';
		}

		$this->query(
			"LOCK TABLES " . implode( ',', $items ),
			$method,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_ROWS
		);

		return true;
	}

	protected function doUnlockTables( $method ) {
		$this->query(
			"UNLOCK TABLES",
			$method,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_ROWS
		);

		return true;
	}

	/**
	 * @param bool $value
	 */
	public function setBigSelects( $value = true ) {
		if ( $value === 'default' ) {
			if ( $this->defaultBigSelects === null ) {
				# Function hasn't been called before so it must already be set to the default
				return;
			} else {
				$value = $this->defaultBigSelects;
			}
		} elseif ( $this->defaultBigSelects === null ) {
			$this->defaultBigSelects =
				(bool)$this->selectField( false, '@@sql_big_selects', '', __METHOD__ );
		}

		$this->query(
			"SET sql_big_selects=" . ( $value ? '1' : '0' ),
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_TRX
		);
	}

	/**
	 * DELETE where the condition is a join. MySql uses multi-table deletes.
	 * @param string $delTable
	 * @param string $joinTable
	 * @param string $delVar
	 * @param string $joinVar
	 * @param array|string $conds
	 * @param bool|string $fname
	 * @throws DBUnexpectedError
	 */
	public function deleteJoin(
		$delTable, $joinTable, $delVar, $joinVar, $conds, $fname = __METHOD__
	) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE $delTable FROM $delTable, $joinTable WHERE $delVar=$joinVar ";

		if ( $conds != '*' ) {
			$sql .= ' AND ' . $this->makeList( $conds, self::LIST_AND );
		}

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	protected function doUpsert( $table, array $rows, array $uniqueKeys, array $set, $fname ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( $rows );
		$sqlColumnAssignments = $this->makeList( $set, self::LIST_SET );

		$sql =
			"INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples " .
			"ON DUPLICATE KEY UPDATE $sqlColumnAssignments";

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	protected function doReplace( $table, array $uniqueKeys, array $rows, $fname ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( $rows );

		$sql = "REPLACE INTO $encTable ($sqlColumns) VALUES $sqlTuples";

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	/**
	 * Determines how long the server has been up
	 *
	 * @return int
	 */
	public function getServerUptime() {
		$vars = $this->getMysqlStatus( 'Uptime' );

		return (int)$vars['Uptime'];
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
	 * Determines if the last failure was due to a lock timeout
	 *
	 * @return bool
	 */
	public function wasLockTimeout() {
		return $this->lastErrno() == 1205;
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

	public function wasConnectionError( $errno ) {
		return $errno == 2013 || $errno == 2006;
	}

	protected function wasKnownStatementRollbackError() {
		$errno = $this->lastErrno();

		if ( $errno === 1205 ) { // lock wait timeout
			// Note that this is uncached to avoid stale values of SET is used
			$row = $this->selectRow(
				false,
				[ 'innodb_rollback_on_timeout' => '@@innodb_rollback_on_timeout' ],
				[],
				__METHOD__
			);
			// https://dev.mysql.com/doc/refman/5.7/en/innodb-error-handling.html
			// https://dev.mysql.com/doc/refman/5.5/en/innodb-parameters.html
			return $row->innodb_rollback_on_timeout ? false : true;
		}

		// See https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html
		return in_array( $errno, [ 1022, 1062, 1216, 1217, 1137, 1146, 1051, 1054 ], true );
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
		$newName = $this->addIdentifierQuotes( $newName );
		$oldName = $this->addIdentifierQuotes( $oldName );

		return $this->query(
			"CREATE $tmp TABLE $newName (LIKE $oldName)",
			$fname,
			self::QUERY_PSEUDO_PERMANENT | self::QUERY_CHANGE_SCHEMA
		);
	}

	/**
	 * List all tables on the database
	 *
	 * @param string|null $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @return array
	 */
	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		$result = $this->query(
			"SHOW TABLES",
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

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
	 * Get status information from SHOW STATUS in an associative array
	 *
	 * @param string $which
	 * @return array
	 */
	private function getMysqlStatus( $which = "%" ) {
		$res = $this->query(
			"SHOW STATUS LIKE '{$which}'",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		$status = [];
		foreach ( $res as $row ) {
			$status[$row->Variable_name] = $row->Value;
		}

		return $status;
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

		// Query for the VIEWS
		$res = $this->query(
			'SHOW FULL TABLES WHERE TABLE_TYPE = "VIEW"',
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		$allViews = [];
		foreach ( $res as $row ) {
			array_push( $allViews, $row->$propertyName );
		}

		if ( $prefix === null || $prefix === '' ) {
			return $allViews;
		}

		$filteredViews = [];
		foreach ( $allViews as $viewName ) {
			// Does the name of this VIEW start with the table-prefix?
			if ( strpos( $viewName, $prefix ) === 0 ) {
				array_push( $filteredViews, $viewName );
			}
		}

		return $filteredViews;
	}

	/**
	 * Differentiates between a TABLE and a VIEW.
	 *
	 * @param string $name Name of the TABLE/VIEW to test
	 * @param string|null $prefix
	 * @return bool
	 * @since 1.22
	 */
	public function isView( $name, $prefix = null ) {
		return in_array( $name, $this->listViews( $prefix, __METHOD__ ) );
	}

	protected function isTransactableQuery( $sql ) {
		return parent::isTransactableQuery( $sql ) &&
			!preg_match( '/^SELECT\s+(GET|RELEASE|IS_FREE)_LOCK\(/', $sql );
	}

	public function buildStringCast( $field ) {
		return "CAST( $field AS BINARY )";
	}

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 */
	public function buildIntegerCast( $field ) {
		return 'CAST( ' . $field . ' AS SIGNED )';
	}

	/*
	 * @return bool Whether GTID support is used (mockable for testing)
	 */
	protected function useGTIDs() {
		return $this->useGTIDs;
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabaseMysqlBase::class, 'DatabaseMysqlBase' );
