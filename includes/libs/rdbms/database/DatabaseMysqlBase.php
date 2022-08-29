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

use InvalidArgumentException;
use RuntimeException;
use stdClass;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\Platform\MySQLPlatform;

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
	/** @var MySQLPrimaryPos */
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
	/**
	 * Open SSL cipher list string
	 * @see https://www.openssl.org/docs/man1.0.2/man1/ciphers.html
	 * @var string|null
	 */
	protected $sslCiphers;
	/** @var bool Use experimental UTF-8 transmission encoding */
	protected $utf8Mode;
	/** @var bool|null */
	protected $defaultBigSelects;

	/** @var bool|null */
	private $insertSelectIsSafe;
	/** @var stdClass|null */
	private $replicationInfoRow;

	// Cache getServerId() for 24 hours
	private const SERVER_ID_CACHE_TTL = 86400;

	/** @var float Warn if lag estimates are made for transactions older than this many seconds */
	private const LAG_STALE_WARN_THRESHOLD = 0.100;

	/** @var ISQLPlatform */
	protected $platform;

	/**
	 * Additional $params include:
	 *   - lagDetectionMethod : set to one of (Seconds_Behind_Master,pt-heartbeat).
	 *       pt-heartbeat assumes the table is at heartbeat.heartbeat
	 *       and uses UTC timestamps in the heartbeat.ts column.
	 *       (https://www.percona.com/doc/percona-toolkit/2.2/pt-heartbeat.html)
	 *   - lagDetectionOptions : if using pt-heartbeat, this can be set to an array map to change
	 *       the default behavior. Normally, the heartbeat row with the server
	 *       ID of this server's primary DB will be used. Set the "conds" field to
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
		$this->utf8Mode = !empty( $params['utf8Mode'] );
		$this->insertSelectIsSafe = isset( $params['insertSelectIsSafe'] )
			? (bool)$params['insertSelectIsSafe'] : null;
		parent::__construct( $params );
		$this->platform = new MySQLPlatform(
			$this,
			$params['queryLogger'],
			$this->currentDomain
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
			// Abstract over any excessive MySQL defaults
			$set = [ 'group_concat_max_len = 262144' ];
			// Set any custom settings defined by site config
			// https://dev.mysql.com/doc/refman/5.7/en/innodb-parameters.html
			foreach ( $this->connectionVariables as $var => $val ) {
				// Escape strings but not numbers to avoid MySQL complaining
				if ( !is_int( $val ) && !is_float( $val ) ) {
					$val = $this->addQuotes( $val );
				}
				$set[] = $this->platform->addIdentifierQuotes( $var ) . ' = ' . $val;
			}

			// @phan-suppress-next-next-line PhanRedundantCondition
			// Safety check to avoid empty SET query
			if ( $set ) {
				$sql = 'SET ' . implode( ', ', $set );
				$flags = self::QUERY_NO_RETRY | self::QUERY_CHANGE_TRX;
				// Avoid using query() so that replaceLostConnection() does not throw
				// errors if the transaction status is STATUS_TRX_ERROR
				$qs = $this->executeQuery( $sql, __METHOD__, $flags, $sql );
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
			$qs = $this->executeQuery( $sql, __METHOD__, self::QUERY_IGNORE_DBO_TRX, $sql );
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
			$error = $this->mysqlError();
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
		list( $database, , $prefix, $table ) = $this->platform->qualifiedTableComponents( $table );
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
	 * @return MySQLField|false
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
	 * Escape special characters in a string for use in an SQL statement
	 *
	 * @param string $s
	 * @return mixed
	 */
	abstract protected function mysqlRealEscapeString( $s );

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
	 * @return int|false Second of lag
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
			// https://mariadb.com/kb/en/delayed-replication/
			// https://dev.mysql.com/doc/refman/5.6/en/replication-delayed.html
			return intval( $row->Seconds_Behind_Master + ( $row->SQL_Remaining_Delay ?? 0 ) );
		}

		return false;
	}

	/**
	 * @return float|false Seconds of lag
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
			$ago = $this->fetchSecondsSinceHeartbeat( $options['conds'] );
		} else {
			// Standard method: use primary server ID (works with stock pt-heartbeat)
			$masterInfo = $this->getPrimaryServerInfo();
			if ( !$masterInfo ) {
				$this->queryLogger->error(
					"Unable to query primary of {db_server} for server ID",
					$this->getLogContext( [
						'method' => __METHOD__
					] )
				);

				return false; // could not get primary server ID
			}

			$conds = [ 'server_id' => intval( $masterInfo['serverId'] ) ];
			$ago = $this->fetchSecondsSinceHeartbeat( $conds );
		}

		if ( $ago !== null ) {
			return max( $ago, 0.0 );
		}

		$this->queryLogger->error(
			"Unable to find pt-heartbeat row for {db_server}",
			$this->getLogContext( [
				'method' => __METHOD__
			] )
		);

		return false;
	}

	protected function getPrimaryServerInfo() {
		if ( !$this->topologicalPrimaryConnRef ) {
			return false; // something is misconfigured
		}

		$cache = $this->srvCache;
		$key = $cache->makeGlobalKey(
			'mysql',
			'master-info',
			// Using one key for all cluster replica DBs is preferable
			$this->topologicalPrimaryConnRef->getServerName()
		);
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			function () use ( $cache, $key, $fname ) {
				// Get and leave a lock key in place for a short period
				if ( !$cache->lock( $key, 0, 10 ) ) {
					return false; // avoid primary DB connection spike slams
				}

				$flags = self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
				// Connect to and query the primary DB; catch errors to avoid outages
				try {
					$res = $this->topologicalPrimaryConnRef->query(
						'SELECT @@server_id AS id',
						$fname,
						$flags
					);
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

	protected function getMasterServerInfo() {
		wfDeprecated( __METHOD__, '1.37' );
		return $this->getPrimaryServerInfo();
	}

	/**
	 * @param array $conds WHERE clause conditions to find a row
	 * @return float|null Elapsed seconds since the newest beat or null if none was found
	 * @see https://www.percona.com/doc/percona-toolkit/2.1/pt-heartbeat.html
	 */
	protected function fetchSecondsSinceHeartbeat( array $conds ) {
		$whereSQL = $this->makeList( $conds, self::LIST_AND );
		// User mysql server time so that query time and trip time are not counted.
		// Use ORDER BY for channel based queries since that field might not be UNIQUE.
		$res = $this->query(
			"SELECT TIMESTAMPDIFF(MICROSECOND,ts,UTC_TIMESTAMP(6)) AS us_ago " .
			"FROM heartbeat.heartbeat WHERE $whereSQL ORDER BY ts DESC LIMIT 1",
			__METHOD__,
			self::QUERY_SILENCE_ERRORS | self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res ? $res->fetchObject() : false;

		return $row ? ( $row->us_ago / 1e6 ) : null;
	}

	protected function getApproximateLagStatus() {
		if ( $this->getLagDetectionMethod() === 'pt-heartbeat' ) {
			// Disable caching since this is fast enough and we don't want
			// to be *too* pessimistic by having both the cache TTL and the
			// pt-heartbeat interval count as lag in getSessionLagStatus()
			return parent::getApproximateLagStatus();
		}

		$key = $this->srvCache->makeGlobalKey( 'mysql-lag', $this->getServerName() );
		$approxLag = $this->srvCache->get( $key );
		if ( !$approxLag ) {
			$approxLag = parent::getApproximateLagStatus();
			$this->srvCache->set( $key, $approxLag, 1 );
		}

		return $approxLag;
	}

	public function primaryPosWait( DBPrimaryPos $pos, $timeout ) {
		if ( !( $pos instanceof MySQLPrimaryPos ) ) {
			throw new InvalidArgumentException( "Position not an instance of MySQLPrimaryPos" );
		}

		if ( $this->topologyRole === self::ROLE_STATIC_CLONE ) {
			$this->queryLogger->debug(
				"Bypassed replication wait; database has a static dataset",
				$this->getLogContext( [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
			);

			return 0; // this is a copy of a read-only dataset with no primary DB
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

				return -1; // this is the primary DB itself?
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
		$row = $res->fetchRow();
		$seconds = max( microtime( true ) - $start, 0 );

		// Result can be NULL (error), -1 (timeout), or 0+ per the MySQL manual
		$status = ( $row[0] !== null ) ? intval( $row[0] ) : null;
		if ( $status === null ) {
			$this->replLogger->error(
				"An error occurred while waiting for replication to reach {wait_pos}",
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
				"Timed out waiting for replication to reach {wait_pos}",
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
				"Replication has reached {wait_pos}",
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
	 * Get the position of the primary DB from SHOW SLAVE STATUS
	 *
	 * @return MySQLPrimaryPos|false
	 */
	public function getReplicaPos() {
		$now = microtime( true ); // as-of-time *before* fetching GTID variables

		if ( $this->useGTIDs() ) {
			// Try to use GTIDs, fallbacking to binlog positions if not possible
			$data = $this->getServerGTIDs( __METHOD__ );
			// Use gtid_slave_pos for MariaDB and gtid_executed for MySQL
			foreach ( [ 'gtid_slave_pos', 'gtid_executed' ] as $name ) {
				if ( isset( $data[$name] ) && strlen( $data[$name] ) ) {
					return new MySQLPrimaryPos( $data[$name], $now );
				}
			}
		}

		$data = $this->getServerRoleStatus( 'SLAVE', __METHOD__ );
		if ( $data && strlen( $data['Relay_Master_Log_File'] ) ) {
			return new MySQLPrimaryPos(
				"{$data['Relay_Master_Log_File']}/{$data['Exec_Master_Log_Pos']}",
				$now
			);
		}

		return false;
	}

	/**
	 * Get the position of the primary DB from SHOW MASTER STATUS
	 *
	 * @return MySQLPrimaryPos|false
	 */
	public function getPrimaryPos() {
		$now = microtime( true ); // as-of-time *before* fetching GTID variables

		$pos = false;
		if ( $this->useGTIDs() ) {
			// Try to use GTIDs, fallbacking to binlog positions if not possible
			$data = $this->getServerGTIDs( __METHOD__ );
			// Use gtid_binlog_pos for MariaDB and gtid_executed for MySQL
			foreach ( [ 'gtid_binlog_pos', 'gtid_executed' ] as $name ) {
				if ( isset( $data[$name] ) && strlen( $data[$name] ) ) {
					$pos = new MySQLPrimaryPos( $data[$name], $now );
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
				$pos = new MySQLPrimaryPos( "{$data['File']}/{$data['Position']}", $now );
			}
		}

		return $pos;
	}

	/**
	 * @inheritDoc
	 * @return string|null 32 bit integer ID; null if not applicable or unknown
	 */
	public function getTopologyBasedServerId() {
		return $this->getServerId();
	}

	/**
	 * @return string Value of server_id (32-bit integer, unique to the replication topology)
	 * @throws DBQueryError
	 */
	protected function getServerId() {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeGlobalKey( 'mysql-server-id', $this->getServerName() ),
			self::SERVER_ID_CACHE_TTL,
			function () use ( $fname ) {
				$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
				$res = $this->query( "SELECT @@server_id AS id", $fname, $flags );

				return $res->fetchObject()->id;
			}
		);
	}

	/**
	 * @return string|null Value of server_uuid (hyphenated 128-bit hex string, globally unique)
	 * @throws DBQueryError
	 */
	protected function getServerUUID() {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeGlobalKey( 'mysql-server-uuid', $this->getServerName() ),
			self::SERVER_ID_CACHE_TTL,
			function () use ( $fname ) {
				$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
				$res = $this->query( "SHOW GLOBAL VARIABLES LIKE 'server_uuid'", $fname, $flags );
				$row = $res->fetchObject();

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
		$row = $res->fetchObject();

		return $row ? (bool)$row->Value : false;
	}

	/**
	 * @return string
	 */
	public function getSoftwareLink() {
		list( $variant ) = $this->getMySqlServerVariant();
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

		if ( $sqlAssignments ) {
			$this->query(
				'SET ' . implode( ', ', $sqlAssignments ),
				__METHOD__,
				self::QUERY_CHANGE_TRX | self::QUERY_CHANGE_NONE
			);
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
		$res = $this->query(
			$this->platform->lockIsFreeSQLText( $lockName ),
			$method,
			self::QUERY_CHANGE_LOCKS
		);
		$row = $res->fetchObject();

		return ( $row->unlocked == 1 );
	}

	public function doLock( string $lockName, string $method, int $timeout ) {
		$res = $this->query(
			$this->platform->lockSQLText( $lockName, $timeout ),
			$method,
			self::QUERY_CHANGE_LOCKS
		);
		$row = $res->fetchObject();

		return ( $row->acquired !== null ) ? (float)$row->acquired : null;
	}

	public function doUnlock( string $lockName, string $method ) {
		$res = $this->query(
			$this->platform->unlockSQLText( $lockName ),
			$method,
			self::QUERY_CHANGE_LOCKS
		);
		$row = $res->fetchObject();

		return ( $row->released == 1 );
	}

	public function namedLocksEnqueue() {
		return true;
	}

	protected function doFlushSession( $fname ) {
		$flags = self::QUERY_CHANGE_LOCKS | self::QUERY_NO_RETRY;
		// Note that RELEASE_ALL_LOCKS() is not supported well enough to use here.
		// https://mariadb.com/kb/en/release_all_locks/
		$releaseLockFields = [];
		foreach ( $this->sessionNamedLocks as $name => $info ) {
			$encName = $this->addQuotes( $this->platform->makeLockName( $name ) );
			$releaseLockFields[] = "RELEASE_LOCK($encName)";
		}
		if ( $releaseLockFields ) {
			$sql = 'SELECT ' . implode( ',', $releaseLockFields );
			$qs = $this->executeQuery( $sql, __METHOD__, $flags, $sql );
			if ( $qs->res === false ) {
				$this->reportQueryError( $qs->message, $qs->code, $sql, $fname, true );
			}
		}
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
			self::QUERY_CHANGE_TRX
		);
	}

	protected function doUpsert(
		string $table,
		array $rows,
		array $identityKey,
		array $set,
		string $fname
	) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->platform->makeInsertLists( $rows );
		$sqlColumnAssignments = $this->makeList( $set, self::LIST_SET );
		// No need to expose __NEW.* since buildExcludedValue() uses VALUES(column)

		// https://mariadb.com/kb/en/insert-on-duplicate-key-update/
		// https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html
		$sql =
			"INSERT INTO $encTable " .
			"($sqlColumns) VALUES $sqlTuples " .
			"ON DUPLICATE KEY UPDATE $sqlColumnAssignments";

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
	}

	protected function doReplace( $table, array $identityKey, array $rows, $fname ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->platform->makeInsertLists( $rows );

		$sql = "REPLACE INTO $encTable ($sqlColumns) VALUES $sqlTuples";

		$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
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
		if ( $errno === 1205 ) { // lock wait timeout
			// Note that this is uncached to avoid stale values if SET is used
			$res = $this->query(
				"SELECT @@innodb_rollback_on_timeout AS Value",
				__METHOD__,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
			);
			$row = $res ? $res->fetchObject() : false;
			// https://dev.mysql.com/doc/refman/5.7/en/innodb-error-handling.html
			// https://dev.mysql.com/doc/refman/5.5/en/innodb-parameters.html
			return ( $row && !$row->Value );
		}

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
			list( $vendor, $number ) = $this->getMySqlServerVariant();
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

	/**
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
