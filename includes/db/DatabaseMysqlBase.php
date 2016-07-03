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
	protected $lastKnownSlavePos;
	/** @var string Method to detect slave lag */
	protected $lagDetectionMethod;
	/** @var array Method to detect slave lag */
	protected $lagDetectionOptions = [];

	/** @var string|null */
	private $serverVersion = null;

	/**
	 * Additional $params include:
	 *   - lagDetectionMethod : set to one of (Seconds_Behind_Master,pt-heartbeat).
	 *                          pt-heartbeat assumes the table is at heartbeat.heartbeat
	 *                          and uses UTC timestamps in the heartbeat.ts column.
	 *                          (https://www.percona.com/doc/percona-toolkit/2.2/pt-heartbeat.html)
	 *   - lagDetectionOptions : if using pt-heartbeat, this can be set to an array map to change
	 *                           the default behavior. Normally, the heartbeat row with the server
	 *                           ID of this server's master will be used. Set the "conds" field to
	 *                           override the query conditions, e.g. ['shard' => 's1'].
	 * @param array $params
	 */
	function __construct( array $params ) {
		parent::__construct( $params );

		$this->lagDetectionMethod = isset( $params['lagDetectionMethod'] )
			? $params['lagDetectionMethod']
			: 'Seconds_Behind_Master';
		$this->lagDetectionOptions = isset( $params['lagDetectionOptions'] )
			? $params['lagDetectionOptions']
			: [];
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
		global $wgAllDBsAreLocalhost, $wgSQLMode;

		# Close/unset connection handle
		$this->close();

		# Debugging hack -- fake cluster
		$realServer = $wgAllDBsAreLocalhost ? 'localhost' : $server;
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$this->installErrorHandler();
		try {
			$this->mConn = $this->mysqlConnect( $realServer );
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
			wfLogDBError(
				"Error connecting to {db_server}: {error}",
				$this->getLogContext( [
					'method' => __METHOD__,
					'error' => $error,
				] )
			);
			wfDebug( "DB connection error\n" .
				"Server: $server, User: $user, Password: " .
				substr( $password, 0, 3 ) . "..., error: " . $error . "\n" );

			$this->reportConnectionError( $error );
		}

		if ( $dbName != '' ) {
			MediaWiki\suppressWarnings();
			$success = $this->selectDB( $dbName );
			MediaWiki\restoreWarnings();
			if ( !$success ) {
				wfLogDBError(
					"Error selecting database {db_name} on server {db_server}",
					$this->getLogContext( [
						'method' => __METHOD__,
					] )
				);
				wfDebug( "Error selecting database $dbName on server {$this->mServer} " .
					"from client host " . wfHostname() . "\n" );

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
		if ( is_string( $wgSQLMode ) ) {
			$set[] = 'sql_mode = ' . $this->addQuotes( $wgSQLMode );
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
				wfLogDBError(
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
		global $wgDBmysql5;

		if ( $wgDBmysql5 ) {
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
		$sQuoted = $this->mysqlRealEscapeString( $s );

		if ( $sQuoted === false ) {
			$this->ping();
			$sQuoted = $this->mysqlRealEscapeString( $s );
		}

		return $sQuoted;
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

	/**
	 * @return bool
	 */
	function ping() {
		$ping = $this->mysqlPing();
		if ( $ping ) {
			// Connection was good or lost but reconnected...
			// @note: mysqlnd (php 5.6+) does not support this (PHP bug 52561)
			return true;
		}

		// Try a full disconnect/reconnect cycle if ping() failed
		$this->closeConnection();
		$this->mOpened = false;
		$this->mConn = false;
		$this->open( $this->mServer, $this->mUser, $this->mPassword, $this->mDBname );

		return true;
	}

	/**
	 * Ping a server connection or reconnect if there is no connection
	 *
	 * @return bool
	 */
	abstract protected function mysqlPing();

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
				wfLogDBError(
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

		wfLogDBError(
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
			// Using one key for all cluster slaves is preferable
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
		$whereSQL = $this->makeList( $conds, LIST_AND );
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

		if ( $this->lastKnownSlavePos && $this->lastKnownSlavePos->hasReached( $pos ) ) {
			return 0;
		}

		# Commit any open transactions
		$this->commit( __METHOD__, 'flush' );

		# Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		$encFile = $this->addQuotes( $pos->file );
		$encPos = intval( $pos->pos );
		$res = $this->doQuery( "SELECT MASTER_POS_WAIT($encFile, $encPos, $timeout)" );

		$row = $res ? $this->fetchRow( $res ) : false;
		if ( !$row ) {
			throw new DBExpectedError( $this, "Failed to query MASTER_POS_WAIT()" );
		}

		// Result can be NULL (error), -1 (timeout), or 0+ per the MySQL manual
		$status = ( $row[0] !== null ) ? intval( $row[0] ) : null;
		if ( $status === null ) {
			// T126436: jobs programmed to wait on master positions might be referencing binlogs
			// with an old master hostname. Such calls make MASTER_POS_WAIT() return null. Try
			// to detect this and treat the slave as having reached the position; a proper master
			// switchover already requires that the new master be caught up before the switch.
			$slavePos = $this->getSlavePos();
			if ( $slavePos && !$slavePos->channelsMatch( $pos ) ) {
				$this->lastKnownSlavePos = $slavePos;
				$status = 0;
			}
		} elseif ( $status >= 0 ) {
			// Remember that this position was reached to save queries next time
			$this->lastKnownSlavePos = $pos;
		}

		return $status;
	}

	/**
	 * Get the position of the master from SHOW SLAVE STATUS
	 *
	 * @return MySQLMasterPos|bool
	 */
	function getSlavePos() {
		$res = $this->query( 'SHOW SLAVE STATUS', 'DatabaseBase::getSlavePos' );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			$pos = isset( $row->Exec_master_log_pos )
				? $row->Exec_master_log_pos
				: $row->Exec_Master_Log_Pos;

			return new MySQLMasterPos( $row->Relay_Master_Log_File, $pos );
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
		$res = $this->query( 'SHOW MASTER STATUS', 'DatabaseBase::getMasterPos' );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			return new MySQLMasterPos( $row->File, $row->Position );
		} else {
			return false;
		}
	}

	/**
	 * @param string $index
	 * @return string
	 */
	function useIndexClause( $index ) {
		return "FORCE INDEX (" . $this->indexName( $index ) . ")";
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
		$lockName = $this->addQuotes( $this->makeLockName( $lockName ) );
		$result = $this->query( "SELECT IS_FREE_LOCK($lockName) AS lockstatus", $method );
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
		$lockName = $this->addQuotes( $this->makeLockName( $lockName ) );
		$result = $this->query( "SELECT GET_LOCK($lockName, $timeout) AS lockstatus", $method );
		$row = $this->fetchObject( $result );

		if ( $row->lockstatus == 1 ) {
			parent::lock( $lockName, $method, $timeout ); // record
			return true;
		}

		wfDebug( __METHOD__ . " failed to acquire lock\n" );

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
		$lockName = $this->addQuotes( $this->makeLockName( $lockName ) );
		$result = $this->query( "SELECT RELEASE_LOCK($lockName) as lockstatus", $method );
		$row = $this->fetchObject( $result );

		if ( $row->lockstatus == 1 ) {
			parent::unlock( $lockName, $method ); // record
			return true;
		}

		wfDebug( __METHOD__ . " failed to release lock\n" );

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
	 * Get search engine class. All subclasses of this
	 * need to implement this if they wish to use searching.
	 *
	 * @return string
	 */
	public function getSearchEngine() {
		return 'SearchMySQL';
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
			throw new DBUnexpectedError( $this, 'DatabaseBase::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE $delTable FROM $delTable, $joinTable WHERE $delVar=$joinVar ";

		if ( $conds != '*' ) {
			$sql .= ' AND ' . $this->makeList( $conds, LIST_AND );
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
		$sql .= " ON DUPLICATE KEY UPDATE " . $this->makeList( $set, LIST_SET );

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

	/**
	 * Determines if the last query error was something that should be dealt
	 * with by pinging the connection and reissuing the query
	 *
	 * @return bool
	 */
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
	 * Get the underlying binding handle, mConn
	 *
	 * Makes sure that mConn is set (disconnects and ping() failure can unset it).
	 * This catches broken callers than catch and ignore disconnection exceptions.
	 * Unlike checking isOpen(), this is safe to call inside of open().
	 *
	 * @return resource|object
	 * @throws DBUnexpectedError
	 * @since 1.26
	 */
	protected function getBindingHandle() {
		if ( !$this->mConn ) {
			throw new DBUnexpectedError(
				$this,
				'DB connection was already closed or the connection dropped.'
			);
		}

		return $this->mConn;
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
	 * @return array
	 */
	protected function getDefaultSchemaVars() {
		$vars = parent::getDefaultSchemaVars();
		$vars['wgDBTableOptions'] = str_replace( 'TYPE', 'ENGINE', $GLOBALS['wgDBTableOptions'] );
		$vars['wgDBTableOptions'] = str_replace(
			'CHARSET=mysql4',
			'CHARSET=binary',
			$vars['wgDBTableOptions']
		);

		return $vars;
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

		if ( !isset( $this->allViews ) ) {

			// The name of the column containing the name of the VIEW
			$propertyName = 'Tables_in_' . $this->mDBname;

			// Query for the VIEWS
			$result = $this->query( 'SHOW FULL TABLES WHERE TABLE_TYPE = "VIEW"' );
			$this->allViews = [];
			while ( ( $row = $this->fetchRow( $result ) ) !== false ) {
				array_push( $this->allViews, $row[$propertyName] );
			}
		}

		if ( is_null( $prefix ) || $prefix === '' ) {
			return $this->allViews;
		}

		$filteredViews = [];
		foreach ( $this->allViews as $viewName ) {
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

/**
 * Utility class.
 * @ingroup Database
 */
class MySQLField implements Field {
	private $name, $tablename, $default, $max_length, $nullable,
		$is_pk, $is_unique, $is_multiple, $is_key, $type, $binary,
		$is_numeric, $is_blob, $is_unsigned, $is_zerofill;

	function __construct( $info ) {
		$this->name = $info->name;
		$this->tablename = $info->table;
		$this->default = $info->def;
		$this->max_length = $info->max_length;
		$this->nullable = !$info->not_null;
		$this->is_pk = $info->primary_key;
		$this->is_unique = $info->unique_key;
		$this->is_multiple = $info->multiple_key;
		$this->is_key = ( $this->is_pk || $this->is_unique || $this->is_multiple );
		$this->type = $info->type;
		$this->binary = isset( $info->binary ) ? $info->binary : false;
		$this->is_numeric = isset( $info->numeric ) ? $info->numeric : false;
		$this->is_blob = isset( $info->blob ) ? $info->blob : false;
		$this->is_unsigned = isset( $info->unsigned ) ? $info->unsigned : false;
		$this->is_zerofill = isset( $info->zerofill ) ? $info->zerofill : false;
	}

	/**
	 * @return string
	 */
	function name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	function tableName() {
		return $this->tablename;
	}

	/**
	 * @return string
	 */
	function type() {
		return $this->type;
	}

	/**
	 * @return bool
	 */
	function isNullable() {
		return $this->nullable;
	}

	function defaultValue() {
		return $this->default;
	}

	/**
	 * @return bool
	 */
	function isKey() {
		return $this->is_key;
	}

	/**
	 * @return bool
	 */
	function isMultipleKey() {
		return $this->is_multiple;
	}

	/**
	 * @return bool
	 */
	function isBinary() {
		return $this->binary;
	}

	/**
	 * @return bool
	 */
	function isNumeric() {
		return $this->is_numeric;
	}

	/**
	 * @return bool
	 */
	function isBlob() {
		return $this->is_blob;
	}

	/**
	 * @return bool
	 */
	function isUnsigned() {
		return $this->is_unsigned;
	}

	/**
	 * @return bool
	 */
	function isZerofill() {
		return $this->is_zerofill;
	}
}

class MySQLMasterPos implements DBMasterPos {
	/** @var string */
	public $file;
	/** @var int Position */
	public $pos;
	/** @var float UNIX timestamp */
	public $asOfTime = 0.0;

	function __construct( $file, $pos ) {
		$this->file = $file;
		$this->pos = $pos;
		$this->asOfTime = microtime( true );
	}

	function asOfTime() {
		return $this->asOfTime;
	}

	function hasReached( DBMasterPos $pos ) {
		if ( !( $pos instanceof self ) ) {
			throw new InvalidArgumentException( "Position not an instance of " . __CLASS__ );
		}

		$thisPos = $this->getCoordinates();
		$thatPos = $pos->getCoordinates();

		return ( $thisPos && $thatPos && $thisPos >= $thatPos );
	}

	function channelsMatch( DBMasterPos $pos ) {
		if ( !( $pos instanceof self ) ) {
			throw new InvalidArgumentException( "Position not an instance of " . __CLASS__ );
		}

		$thisBinlog = $this->getBinlogName();
		$thatBinlog = $pos->getBinlogName();

		return ( $thisBinlog !== false && $thisBinlog === $thatBinlog );
	}

	function __toString() {
		// e.g db1034-bin.000976/843431247
		return "{$this->file}/{$this->pos}";
	}

	/**
	 * @return string|bool
	 */
	protected function getBinlogName() {
		$m = [];
		if ( preg_match( '!^(.+)\.(\d+)/(\d+)$!', (string)$this, $m ) ) {
			return $m[1];
		}

		return false;
	}

	/**
	 * @return array|bool (int, int)
	 */
	protected function getCoordinates() {
		$m = [];
		if ( preg_match( '!\.(\d+)/(\d+)$!', (string)$this, $m ) ) {
			return [ (int)$m[1], (int)$m[2] ];
		}

		return false;
	}
}
