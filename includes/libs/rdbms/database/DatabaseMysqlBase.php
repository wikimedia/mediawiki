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

/**
 * Database abstraction object for MySQL.
 * Defines methods independent on used MySQL extension.
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 */
abstract class DatabaseMysqlBase extends Database {
	/** @var MysqlMasterPos */
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
	protected $sslCAPath;
	/** @var string[]|null */
	protected $sslCiphers;
	/** @var string sql_mode value to send on connection */
	protected $sqlMode;
	/** @var bool Use experimental UTF-8 transmission encoding */
	protected $utf8Mode;

	/** @var string|null */
	private $serverVersion = null;

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
	 *   - sslKeyPath : path to key file [default: null]
	 *   - sslCertPath : path to certificate file [default: null]
	 *   - sslCAPath : parth to certificate authority PEM files [default: null]
	 *   - sslCiphers : array list of allowable ciphers [default: null]
	 * @param array $params
	 */
	function __construct( array $params ) {
		$this->lagDetectionMethod = isset( $params['lagDetectionMethod'] )
			? $params['lagDetectionMethod']
			: 'Seconds_Behind_Master';
		$this->lagDetectionOptions = isset( $params['lagDetectionOptions'] )
			? $params['lagDetectionOptions']
			: [];
		$this->useGTIDs = !empty( $params['useGTIDs' ] );
		foreach ( [ 'KeyPath', 'CertPath', 'CAPath', 'Ciphers' ] as $name ) {
			$var = "ssl{$name}";
			if ( isset( $params[$var] ) ) {
				$this->$var = $params[$var];
			}
		}
		$this->sqlMode = isset( $params['sqlMode'] ) ? $params['sqlMode'] : '';
		$this->utf8Mode = !empty( $params['utf8Mode'] );

		parent::__construct( $params );
	}

	/**
	 * @return string
	 */
	function getType() {
		return 'mysql';
	}

	/**
	 * @param string $server
	 * @param string $user
	 * @param string $password
	 * @param string $dbName
	 * @throws Exception|DBConnectionError
	 * @return bool
	 */
	function open( $server, $user, $password, $dbName ) {
		# Close/unset connection handle
		$this->close();

		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$this->installErrorHandler();
		try {
			$this->mConn = $this->mysqlConnect( $this->mServer );
		} catch ( Exception $ex ) {
			$this->restoreErrorHandler();
			throw $ex;
		}
		$error = $this->restoreErrorHandler();

		# Always log connection errors
		if ( !$this->mConn ) {
			if ( !$error ) {
				$error = $this->lastError();
			}
			$this->connLogger->error(
				"Error connecting to {db_server}: {error}",
				$this->getLogContext( [
					'method' => __METHOD__,
					'error' => $error,
				] )
			);
			$this->connLogger->debug( "DB connection error\n" .
				"Server: $server, User: $user, Password: " .
				substr( $password, 0, 3 ) . "..., error: " . $error . "\n" );

			$this->reportConnectionError( $error );
		}

		if ( $dbName != '' ) {
			MediaWiki\suppressWarnings();
			$success = $this->selectDB( $dbName );
			MediaWiki\restoreWarnings();
			if ( !$success ) {
				$this->queryLogger->error(
					"Error selecting database {db_name} on server {db_server}",
					$this->getLogContext( [
						'method' => __METHOD__,
					] )
				);
				$this->queryLogger->debug(
					"Error selecting database $dbName on server {$this->mServer}" );

				$this->reportConnectionError( "Error selecting database $dbName" );
			}
		}

		// Tell the server what we're communicating with
		if ( !$this->connectInitCharset() ) {
			$this->reportConnectionError( "Error setting character set" );
		}

		// Abstract over any insane MySQL defaults
		$set = [ 'group_concat_max_len = 262144' ];
		// Set SQL mode, default is turning them all off, can be overridden or skipped with null
		if ( is_string( $this->sqlMode ) ) {
			$set[] = 'sql_mode = ' . $this->addQuotes( $this->sqlMode );
		}
		// Set any custom settings defined by site config
		// (e.g. https://dev.mysql.com/doc/refman/4.1/en/innodb-parameters.html)
		foreach ( $this->mSessionVars as $var => $val ) {
			// Escape strings but not numbers to avoid MySQL complaining
			if ( !is_int( $val ) && !is_float( $val ) ) {
				$val = $this->addQuotes( $val );
			}
			$set[] = $this->addIdentifierQuotes( $var ) . ' = ' . $val;
		}

		if ( $set ) {
			// Use doQuery() to avoid opening implicit transactions (DBO_TRX)
			$success = $this->doQuery( 'SET ' . implode( ', ', $set ) );
			if ( !$success ) {
				$this->queryLogger->error(
					'Error setting MySQL variables on server {db_server} (check $wgSQLMode)',
					$this->getLogContext( [
						'method' => __METHOD__,
					] )
				);
				$this->reportConnectionError(
					'Error setting MySQL variables on server {db_server} (check $wgSQLMode)' );
			}
		}

		$this->mOpened = true;

		return true;
	}

	/**
	 * Set the character set information right after connection
	 * @return bool
	 */
	protected function connectInitCharset() {
		if ( $this->utf8Mode ) {
			// Tell the server we're communicating with it in UTF-8.
			// This may engage various charset conversions.
			return $this->mysqlSetCharset( 'utf8' );
		} else {
			return $this->mysqlSetCharset( 'binary' );
		}
	}

	/**
	 * Open a connection to a MySQL server
	 *
	 * @param string $realServer
	 * @return mixed Raw connection
	 * @throws DBConnectionError
	 */
	abstract protected function mysqlConnect( $realServer );

	/**
	 * Set the character set of the MySQL link
	 *
	 * @param string $charset
	 * @return bool
	 */
	abstract protected function mysqlSetCharset( $charset );

	/**
	 * @param ResultWrapper|resource $res
	 * @throws DBUnexpectedError
	 */
	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$ok = $this->mysqlFreeResult( $res );
		MediaWiki\restoreWarnings();
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
	 * @param ResultWrapper|resource $res
	 * @return stdClass|bool
	 * @throws DBUnexpectedError
	 */
	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$row = $this->mysqlFetchObject( $res );
		MediaWiki\restoreWarnings();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_object does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_object can cause.
		// See http://dev.mysql.com/doc/refman/5.0/en/mysql-fetch-row.html.
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
	 * @param ResultWrapper|resource $res
	 * @return array|bool
	 * @throws DBUnexpectedError
	 */
	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$row = $this->mysqlFetchArray( $res );
		MediaWiki\restoreWarnings();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_array does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_array can cause.
		// See http://dev.mysql.com/doc/refman/5.0/en/mysql-fetch-row.html.
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
	 * @return array
	 */
	abstract protected function mysqlFetchArray( $res );

	/**
	 * @throws DBUnexpectedError
	 * @param ResultWrapper|resource $res
	 * @return int
	 */
	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$n = $this->mysqlNumRows( $res );
		MediaWiki\restoreWarnings();

		// Unfortunately, mysql_num_rows does not reset the last errno.
		// We are not checking for any errors here, since
		// these are no errors mysql_num_rows can cause.
		// See http://dev.mysql.com/doc/refman/5.0/en/mysql-fetch-row.html.
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
	 * @param ResultWrapper|resource $res
	 * @return int
	 */
	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $this->mysqlNumFields( $res );
	}

	/**
	 * Get number of fields in result
	 *
	 * @param resource $res Raw result
	 * @return int
	 */
	abstract protected function mysqlNumFields( $res );

	/**
	 * @param ResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $this->mysqlFieldName( $res, $n );
	}

	/**
	 * Get the name of the specified field in a result
	 *
	 * @param ResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	abstract protected function mysqlFieldName( $res, $n );

	/**
	 * mysql_field_type() wrapper
	 * @param ResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	public function fieldType( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $this->mysqlFieldType( $res, $n );
	}

	/**
	 * Get the type of the specified field in a result
	 *
	 * @param ResultWrapper|resource $res
	 * @param int $n
	 * @return string
	 */
	abstract protected function mysqlFieldType( $res, $n );

	/**
	 * @param ResultWrapper|resource $res
	 * @param int $row
	 * @return bool
	 */
	function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $this->mysqlDataSeek( $res, $row );
	}

	/**
	 * Move internal result pointer
	 *
	 * @param ResultWrapper|resource $res
	 * @param int $row
	 * @return bool
	 */
	abstract protected function mysqlDataSeek( $res, $row );

	/**
	 * @return string
	 */
	function lastError() {
		if ( $this->mConn ) {
			# Even if it's non-zero, it can still be invalid
			MediaWiki\suppressWarnings();
			$error = $this->mysqlError( $this->mConn );
			if ( !$error ) {
				$error = $this->mysqlError();
			}
			MediaWiki\restoreWarnings();
		} else {
			$error = $this->mysqlError();
		}
		if ( $error ) {
			$error .= ' (' . $this->mServer . ')';
		}

		return $error;
	}

	/**
	 * Returns the text of the error message from previous MySQL operation
	 *
	 * @param resource $conn Raw connection
	 * @return string
	 */
	abstract protected function mysqlError( $conn = null );

	/**
	 * @param string $table
	 * @param array $uniqueIndexes
	 * @param array $rows
	 * @param string $fname
	 * @return ResultWrapper
	 */
	function replace( $table, $uniqueIndexes, $rows, $fname = __METHOD__ ) {
		return $this->nativeReplace( $table, $rows, $fname );
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * Takes same arguments as Database::select()
	 *
	 * @param string|array $table
	 * @param string|array $vars
	 * @param string|array $conds
	 * @param string $fname
	 * @param string|array $options
	 * @return bool|int
	 */
	public function estimateRowCount( $table, $vars = '*', $conds = '',
		$fname = __METHOD__, $options = []
	) {
		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
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

	function tableExists( $table, $fname = __METHOD__ ) {
		$table = $this->tableName( $table, 'raw' );
		if ( isset( $this->mSessionTempTables[$table] ) ) {
			return true; // already known to exist and won't show in SHOW TABLES anyway
		}

		$encLike = $this->buildLike( $table );

		return $this->query( "SHOW TABLES $encLike", $fname )->numRows() > 0;
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @return bool|MySQLField
	 */
	function fieldInfo( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query( "SELECT * FROM $table LIMIT 1", __METHOD__, true );
		if ( !$res ) {
			return false;
		}
		$n = $this->mysqlNumFields( $res->result );
		for ( $i = 0; $i < $n; $i++ ) {
			$meta = $this->mysqlFetchField( $res->result, $i );
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
	function indexInfo( $table, $index, $fname = __METHOD__ ) {
		# SHOW INDEX works in MySQL 3.23.58, but SHOW INDEXES does not.
		# SHOW INDEX should work for 3.x and up:
		# http://dev.mysql.com/doc/mysql/en/SHOW_INDEX.html
		$table = $this->tableName( $table );
		$index = $this->indexName( $index );

		$sql = 'SHOW INDEX FROM ' . $table;
		$res = $this->query( $sql, $fname );

		if ( !$res ) {
			return null;
		}

		$result = [];

		foreach ( $res as $row ) {
			if ( $row->Key_name == $index ) {
				$result[] = $row;
			}
		}

		return empty( $result ) ? false : $result;
	}

	/**
	 * @param string $s
	 * @return string
	 */
	function strencode( $s ) {
		return $this->mysqlRealEscapeString( $s );
	}

	/**
	 * @param string $s
	 * @return mixed
	 */
	abstract protected function mysqlRealEscapeString( $s );

	public function addQuotes( $s ) {
		if ( is_bool( $s ) ) {
			// Parent would transform to int, which does not play nice with MySQL type juggling.
			// When searching for an int in a string column, the strings are cast to int, which
			// means false would match any string not starting with a number.
			$s = (string)(int)$s;
		}
		return parent::addQuotes( $s );
	}

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

	function getLag() {
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
		$res = $this->query( 'SHOW SLAVE STATUS', __METHOD__ );
		$row = $res ? $res->fetchObject() : false;
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
			$this->getLBInfo( 'clusterMasterHost' ) ?: $this->getServer()
		);

		return $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			function () use ( $cache, $key ) {
				// Get and leave a lock key in place for a short period
				if ( !$cache->lock( $key, 0, 10 ) ) {
					return false; // avoid master connection spike slams
				}

				$conn = $this->getLazyMasterHandle();
				if ( !$conn ) {
					return false; // something is misconfigured
				}

				// Connect to and query the master; catch errors to avoid outages
				try {
					$res = $conn->query( 'SELECT @@server_id AS id', __METHOD__ );
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
		$whereSQL = $this->makeList( $conds, self::LIST_AND );
		// Use ORDER BY for channel based queries since that field might not be UNIQUE.
		// Note: this would use "TIMESTAMPDIFF(MICROSECOND,ts,UTC_TIMESTAMP(6))" but the
		// percision field is not supported in MySQL <= 5.5.
		$res = $this->query(
			"SELECT ts FROM heartbeat.heartbeat WHERE $whereSQL ORDER BY ts DESC LIMIT 1"
		);
		$row = $res ? $res->fetchObject() : false;

		return [ $row ? $row->ts : null, microtime( true ) ];
	}

	public function getApproximateLagStatus() {
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

	function masterPosWait( DBMasterPos $pos, $timeout ) {
		if ( !( $pos instanceof MySQLMasterPos ) ) {
			throw new InvalidArgumentException( "Position not an instance of MySQLMasterPos" );
		}

		if ( $this->getLBInfo( 'is static' ) === true ) {
			return 0; // this is a copy of a read-only dataset with no master DB
		} elseif ( $this->lastKnownReplicaPos && $this->lastKnownReplicaPos->hasReached( $pos ) ) {
			return 0; // already reached this point for sure
		}

		// Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		if ( $this->useGTIDs && $pos->gtids ) {
			// Wait on the GTID set (MariaDB only)
			$gtidArg = $this->addQuotes( implode( ',', $pos->gtids ) );
			$res = $this->doQuery( "SELECT MASTER_GTID_WAIT($gtidArg, $timeout)" );
		} else {
			// Wait on the binlog coordinates
			$encFile = $this->addQuotes( $pos->file );
			$encPos = intval( $pos->pos );
			$res = $this->doQuery( "SELECT MASTER_POS_WAIT($encFile, $encPos, $timeout)" );
		}

		$row = $res ? $this->fetchRow( $res ) : false;
		if ( !$row ) {
			throw new DBExpectedError( $this, "Failed to query MASTER_POS_WAIT()" );
		}

		// Result can be NULL (error), -1 (timeout), or 0+ per the MySQL manual
		$status = ( $row[0] !== null ) ? intval( $row[0] ) : null;
		if ( $status === null ) {
			// T126436: jobs programmed to wait on master positions might be referencing binlogs
			// with an old master hostname. Such calls make MASTER_POS_WAIT() return null. Try
			// to detect this and treat the replica DB as having reached the position; a proper master
			// switchover already requires that the new master be caught up before the switch.
			$replicationPos = $this->getReplicaPos();
			if ( $replicationPos && !$replicationPos->channelsMatch( $pos ) ) {
				$this->lastKnownReplicaPos = $replicationPos;
				$status = 0;
			}
		} elseif ( $status >= 0 ) {
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
	function getReplicaPos() {
		$res = $this->query( 'SHOW SLAVE STATUS', __METHOD__ );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			$pos = isset( $row->Exec_master_log_pos )
				? $row->Exec_master_log_pos
				: $row->Exec_Master_Log_Pos;
			// Also fetch the last-applied GTID set (MariaDB)
			if ( $this->useGTIDs ) {
				$res = $this->query( "SHOW GLOBAL VARIABLES LIKE 'gtid_slave_pos'", __METHOD__ );
				$gtidRow = $this->fetchObject( $res );
				$gtidSet = $gtidRow ? $gtidRow->Value : '';
			} else {
				$gtidSet = '';
			}

			return new MySQLMasterPos( $row->Relay_Master_Log_File, $pos, $gtidSet );
		} else {
			return false;
		}
	}

	/**
	 * Get the position of the master from SHOW MASTER STATUS
	 *
	 * @return MySQLMasterPos|bool
	 */
	function getMasterPos() {
		$res = $this->query( 'SHOW MASTER STATUS', __METHOD__ );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			// Also fetch the last-written GTID set (MariaDB)
			if ( $this->useGTIDs ) {
				$res = $this->query( "SHOW GLOBAL VARIABLES LIKE 'gtid_binlog_pos'", __METHOD__ );
				$gtidRow = $this->fetchObject( $res );
				$gtidSet = $gtidRow ? $gtidRow->Value : '';
			} else {
				$gtidSet = '';
			}

			return new MySQLMasterPos( $row->File, $row->Position, $gtidSet );
		} else {
			return false;
		}
	}

	public function serverIsReadOnly() {
		$res = $this->query( "SHOW GLOBAL VARIABLES LIKE 'read_only'", __METHOD__ );
		$row = $this->fetchObject( $res );

		return $row ? ( strtolower( $row->Value ) === 'on' ) : false;
	}

	/**
	 * @param string $index
	 * @return string
	 */
	function useIndexClause( $index ) {
		return "FORCE INDEX (" . $this->indexName( $index ) . ")";
	}

	/**
	 * @param string $index
	 * @return string
	 */
	function ignoreIndexClause( $index ) {
		return "IGNORE INDEX (" . $this->indexName( $index ) . ")";
	}

	/**
	 * @return string
	 */
	function lowPriorityOption() {
		return 'LOW_PRIORITY';
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
		// Not using mysql_get_server_info() or similar for consistency: in the handshake,
		// MariaDB 10 adds the prefix "5.5.5-", and only some newer client libraries strip
		// it off (see RPL_VERSION_HACK in include/mysql_com.h).
		if ( $this->serverVersion === null ) {
			$this->serverVersion = $this->selectField( '', 'VERSION()', '', __METHOD__ );
		}
		return $this->serverVersion;
	}

	/**
	 * @param array $options
	 */
	public function setSessionOptions( array $options ) {
		if ( isset( $options['connTimeout'] ) ) {
			$timeout = (int)$options['connTimeout'];
			$this->query( "SET net_read_timeout=$timeout" );
			$this->query( "SET net_write_timeout=$timeout" );
		}
	}

	/**
	 * @param string $sql
	 * @param string $newLine
	 * @return bool
	 */
	public function streamStatementEnd( &$sql, &$newLine ) {
		if ( strtoupper( substr( $newLine, 0, 9 ) ) == 'DELIMITER' ) {
			preg_match( '/^DELIMITER\s+(\S+)/', $newLine, $m );
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
		$encName = $this->addQuotes( $this->makeLockName( $lockName ) );
		$result = $this->query( "SELECT IS_FREE_LOCK($encName) AS lockstatus", $method );
		$row = $this->fetchObject( $result );

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
		$result = $this->query( "SELECT GET_LOCK($encName, $timeout) AS lockstatus", $method );
		$row = $this->fetchObject( $result );

		if ( $row->lockstatus == 1 ) {
			parent::lock( $lockName, $method, $timeout ); // record
			return true;
		}

		$this->queryLogger->warning( __METHOD__ . " failed to acquire lock '$lockName'\n" );

		return false;
	}

	/**
	 * FROM MYSQL DOCS:
	 * http://dev.mysql.com/doc/refman/5.0/en/miscellaneous-functions.html#function_release-lock
	 * @param string $lockName
	 * @param string $method
	 * @return bool
	 */
	public function unlock( $lockName, $method ) {
		$encName = $this->addQuotes( $this->makeLockName( $lockName ) );
		$result = $this->query( "SELECT RELEASE_LOCK($encName) as lockstatus", $method );
		$row = $this->fetchObject( $result );

		if ( $row->lockstatus == 1 ) {
			parent::unlock( $lockName, $method ); // record
			return true;
		}

		$this->queryLogger->warning( __METHOD__ . " failed to release lock '$lockName'\n" );

		return false;
	}

	private function makeLockName( $lockName ) {
		// http://dev.mysql.com/doc/refman/5.7/en/miscellaneous-functions.html#function_get-lock
		// Newer version enforce a 64 char length limit.
		return ( strlen( $lockName ) > 64 ) ? sha1( $lockName ) : $lockName;
	}

	public function namedLocksEnqueue() {
		return true;
	}

	/**
	 * @param array $read
	 * @param array $write
	 * @param string $method
	 * @param bool $lowPriority
	 * @return bool
	 */
	public function lockTables( $read, $write, $method, $lowPriority = true ) {
		$items = [];

		foreach ( $write as $table ) {
			$tbl = $this->tableName( $table ) .
				( $lowPriority ? ' LOW_PRIORITY' : '' ) .
				' WRITE';
			$items[] = $tbl;
		}
		foreach ( $read as $table ) {
			$items[] = $this->tableName( $table ) . ' READ';
		}
		$sql = "LOCK TABLES " . implode( ',', $items );
		$this->query( $sql, $method );

		return true;
	}

	/**
	 * @param string $method
	 * @return bool
	 */
	public function unlockTables( $method ) {
		$this->query( "UNLOCK TABLES", $method );

		return true;
	}

	/**
	 * @param bool $value
	 */
	public function setBigSelects( $value = true ) {
		if ( $value === 'default' ) {
			if ( $this->mDefaultBigSelects === null ) {
				# Function hasn't been called before so it must already be set to the default
				return;
			} else {
				$value = $this->mDefaultBigSelects;
			}
		} elseif ( $this->mDefaultBigSelects === null ) {
			$this->mDefaultBigSelects =
				(bool)$this->selectField( false, '@@sql_big_selects', '', __METHOD__ );
		}
		$encValue = $value ? '1' : '0';
		$this->query( "SET sql_big_selects=$encValue", __METHOD__ );
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
	 * @return bool|ResultWrapper
	 */
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = __METHOD__ ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE $delTable FROM $delTable, $joinTable WHERE $delVar=$joinVar ";

		if ( $conds != '*' ) {
			$sql .= ' AND ' . $this->makeList( $conds, self::LIST_AND );
		}

		return $this->query( $sql, $fname );
	}

	/**
	 * @param string $table
	 * @param array $rows
	 * @param array $uniqueIndexes
	 * @param array $set
	 * @param string $fname
	 * @return bool
	 */
	public function upsert( $table, array $rows, array $uniqueIndexes,
		array $set, $fname = __METHOD__
	) {
		if ( !count( $rows ) ) {
			return true; // nothing to do
		}

		if ( !is_array( reset( $rows ) ) ) {
			$rows = [ $rows ];
		}

		$table = $this->tableName( $table );
		$columns = array_keys( $rows[0] );

		$sql = "INSERT INTO $table (" . implode( ',', $columns ) . ') VALUES ';
		$rowTuples = [];
		foreach ( $rows as $row ) {
			$rowTuples[] = '(' . $this->makeList( $row ) . ')';
		}
		$sql .= implode( ',', $rowTuples );
		$sql .= " ON DUPLICATE KEY UPDATE " . $this->makeList( $set, self::LIST_SET );

		return (bool)$this->query( $sql, $fname );
	}

	/**
	 * Determines how long the server has been up
	 *
	 * @return int
	 */
	function getServerUptime() {
		$vars = $this->getMysqlStatus( 'Uptime' );

		return (int)$vars['Uptime'];
	}

	/**
	 * Determines if the last failure was due to a deadlock
	 *
	 * @return bool
	 */
	function wasDeadlock() {
		return $this->lastErrno() == 1213;
	}

	/**
	 * Determines if the last failure was due to a lock timeout
	 *
	 * @return bool
	 */
	function wasLockTimeout() {
		return $this->lastErrno() == 1205;
	}

	function wasErrorReissuable() {
		return $this->lastErrno() == 2013 || $this->lastErrno() == 2006;
	}

	/**
	 * Determines if the last failure was due to the database being read-only.
	 *
	 * @return bool
	 */
	function wasReadOnlyError() {
		return $this->lastErrno() == 1223 ||
			( $this->lastErrno() == 1290 && strpos( $this->lastError(), '--read-only' ) !== false );
	}

	function wasConnectionError( $errno ) {
		return $errno == 2013 || $errno == 2006;
	}

	/**
	 * @param string $oldName
	 * @param string $newName
	 * @param bool $temporary
	 * @param string $fname
	 * @return bool
	 */
	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = __METHOD__ ) {
		$tmp = $temporary ? 'TEMPORARY ' : '';
		$newName = $this->addIdentifierQuotes( $newName );
		$oldName = $this->addIdentifierQuotes( $oldName );
		$query = "CREATE $tmp TABLE $newName (LIKE $oldName)";

		return $this->query( $query, $fname );
	}

	/**
	 * List all tables on the database
	 *
	 * @param string $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @return array
	 */
	function listTables( $prefix = null, $fname = __METHOD__ ) {
		$result = $this->query( "SHOW TABLES", $fname );

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
	 * @param string $tableName
	 * @param string $fName
	 * @return bool|ResultWrapper
	 */
	public function dropTable( $tableName, $fName = __METHOD__ ) {
		if ( !$this->tableExists( $tableName, $fName ) ) {
			return false;
		}

		return $this->query( "DROP TABLE IF EXISTS " . $this->tableName( $tableName ), $fName );
	}

	/**
	 * Get status information from SHOW STATUS in an associative array
	 *
	 * @param string $which
	 * @return array
	 */
	function getMysqlStatus( $which = "%" ) {
		$res = $this->query( "SHOW STATUS LIKE '{$which}'" );
		$status = [];

		foreach ( $res as $row ) {
			$status[$row->Variable_name] = $row->Value;
		}

		return $status;
	}

	/**
	 * Lists VIEWs in the database
	 *
	 * @param string $prefix Only show VIEWs with this prefix, eg.
	 * unit_test_, or $wgDBprefix. Default: null, would return all views.
	 * @param string $fname Name of calling function
	 * @return array
	 * @since 1.22
	 */
	public function listViews( $prefix = null, $fname = __METHOD__ ) {
		// The name of the column containing the name of the VIEW
		$propertyName = 'Tables_in_' . $this->mDBname;

		// Query for the VIEWS
		$res = $this->query( 'SHOW FULL TABLES WHERE TABLE_TYPE = "VIEW"' );
		$allViews = [];
		foreach ( $res as $row ) {
			array_push( $allViews, $row->$propertyName );
		}

		if ( is_null( $prefix ) || $prefix === '' ) {
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
	 * @param string $prefix
	 * @return bool
	 * @since 1.22
	 */
	public function isView( $name, $prefix = null ) {
		return in_array( $name, $this->listViews( $prefix ) );
	}
}

