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
 * Database abstraction object for mySQL
 * Inherit all methods and properties of Database::Database()
 *
 * @ingroup Database
 * @see Database
 */
class DatabaseMysql extends DatabaseBase {

	/**
	 * @return string
	 */
	function getType() {
		return 'mysql';
	}

	/**
	 * @param $sql string
	 * @return resource
	 */
	protected function doQuery( $sql ) {
		if( $this->bufferResults() ) {
			$ret = mysql_query( $sql, $this->mConn );
		} else {
			$ret = mysql_unbuffered_query( $sql, $this->mConn );
		}
		return $ret;
	}

	/**
	 * @param $server string
	 * @param $user string
	 * @param $password string
	 * @param $dbName string
	 * @return bool
	 * @throws DBConnectionError
	 */
	function open( $server, $user, $password, $dbName ) {
		global $wgAllDBsAreLocalhost, $wgDBmysql5, $wgSQLMode;
		wfProfileIn( __METHOD__ );

		# Load mysql.so if we don't have it
		wfDl( 'mysql' );

		# Fail now
		# Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !function_exists( 'mysql_connect' ) ) {
			throw new DBConnectionError( $this, "MySQL functions missing, have you compiled PHP with the --with-mysql option?\n" );
		}

		# Debugging hack -- fake cluster
		if ( $wgAllDBsAreLocalhost ) {
			$realServer = 'localhost';
		} else {
			$realServer = $server;
		}
		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$connFlags = 0;
		if ( $this->mFlags & DBO_SSL ) {
			$connFlags |= MYSQL_CLIENT_SSL;
		}
		if ( $this->mFlags & DBO_COMPRESS ) {
			$connFlags |= MYSQL_CLIENT_COMPRESS;
		}

		wfProfileIn( "dbconnect-$server" );

		# The kernel's default SYN retransmission period is far too slow for us,
		# so we use a short timeout plus a manual retry. Retrying means that a small
		# but finite rate of SYN packet loss won't cause user-visible errors.
		$this->mConn = false;
		if ( ini_get( 'mysql.connect_timeout' ) <= 3 ) {
			$numAttempts = 2;
		} else {
			$numAttempts = 1;
		}
		$this->installErrorHandler();
		for ( $i = 0; $i < $numAttempts && !$this->mConn; $i++ ) {
			if ( $i > 1 ) {
				usleep( 1000 );
			}
			if ( $this->mFlags & DBO_PERSISTENT ) {
				$this->mConn = mysql_pconnect( $realServer, $user, $password, $connFlags );
			} else {
				# Create a new connection...
				$this->mConn = mysql_connect( $realServer, $user, $password, true, $connFlags );
			}
			#if ( $this->mConn === false ) {
				#$iplus = $i + 1;
				#wfLogDBError("Connect loop error $iplus of $max ($server): " . mysql_errno() . " - " . mysql_error()."\n");
			#}
		}
		$error = $this->restoreErrorHandler();

		wfProfileOut( "dbconnect-$server" );

		# Always log connection errors
		if ( !$this->mConn ) {
			if ( !$error ) {
				$error = $this->lastError();
			}
			wfLogDBError( "Error connecting to {$this->mServer}: $error\n" );
			wfDebug( "DB connection error\n" .
				"Server: $server, User: $user, Password: " .
				substr( $password, 0, 3 ) . "..., error: " . $error . "\n" );

			wfProfileOut( __METHOD__ );
			$this->reportConnectionError( $error );
		}

		if ( $dbName != '' ) {
			wfSuppressWarnings();
			$success = mysql_select_db( $dbName, $this->mConn );
			wfRestoreWarnings();
			if ( !$success ) {
				wfLogDBError( "Error selecting database $dbName on server {$this->mServer}\n" );
				wfDebug( "Error selecting database $dbName on server {$this->mServer} " .
					"from client host " . wfHostname() . "\n" );

				wfProfileOut( __METHOD__ );
				$this->reportConnectionError( "Error selecting database $dbName" );
			}
		}

		// Tell the server we're communicating with it in UTF-8.
		// This may engage various charset conversions.
		if( $wgDBmysql5 ) {
			$this->query( 'SET NAMES utf8', __METHOD__ );
		} else {
			$this->query( 'SET NAMES binary', __METHOD__ );
		}
		// Set SQL mode, default is turning them all off, can be overridden or skipped with null
		if ( is_string( $wgSQLMode ) ) {
			$mode = $this->addQuotes( $wgSQLMode );
			$this->query( "SET sql_mode = $mode", __METHOD__ );
		}

		$this->mOpened = true;
		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * @return bool
	 */
	protected function closeConnection() {
		return mysql_close( $this->mConn );
	}

	/**
	 * @param $res ResultWrapper
	 * @throws DBUnexpectedError
	 */
	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$ok = mysql_free_result( $res );
		wfRestoreWarnings();
		if ( !$ok ) {
			throw new DBUnexpectedError( $this, "Unable to free MySQL result" );
		}
	}

	/**
	 * @param $res ResultWrapper
	 * @return object|stdClass
	 * @throws DBUnexpectedError
	 */
	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$row = mysql_fetch_object( $res );
		wfRestoreWarnings();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_object does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_object can cause.
		// See http://dev.mysql.com/doc/refman/5.0/es/mysql-fetch-row.html.
		if( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError( $this, 'Error in fetchObject(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

	/**
	 * @param $res ResultWrapper
	 * @return array
	 * @throws DBUnexpectedError
	 */
	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$row = mysql_fetch_array( $res );
		wfRestoreWarnings();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_array does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_object can cause.
		// See http://dev.mysql.com/doc/refman/5.0/es/mysql-fetch-row.html.
		if( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError( $this, 'Error in fetchRow(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

	/**
	 * @throws DBUnexpectedError
	 * @param $res ResultWrapper
	 * @return int
	 */
	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$n = mysql_num_rows( $res );
		wfRestoreWarnings();
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in numRows(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $n;
	}

	/**
	 * @param $res ResultWrapper
	 * @return int
	 */
	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return mysql_num_fields( $res );
	}

	/**
	 * @param $res ResultWrapper
	 * @param $n string
	 * @return string
	 */
	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return mysql_field_name( $res, $n );
	}

	/**
	 * @return int
	 */
	function insertId() {
		return mysql_insert_id( $this->mConn );
	}

	/**
	 * @param $res ResultWrapper
	 * @param $row
	 * @return bool
	 */
	function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return mysql_data_seek( $res, $row );
	}

	/**
	 * @return int
	 */
	function lastErrno() {
		if ( $this->mConn ) {
			return mysql_errno( $this->mConn );
		} else {
			return mysql_errno();
		}
	}

	/**
	 * @return string
	 */
	function lastError() {
		if ( $this->mConn ) {
			# Even if it's non-zero, it can still be invalid
			wfSuppressWarnings();
			$error = mysql_error( $this->mConn );
			if ( !$error ) {
				$error = mysql_error();
			}
			wfRestoreWarnings();
		} else {
			$error = mysql_error();
		}
		if( $error ) {
			$error .= ' (' . $this->mServer . ')';
		}
		return $error;
	}

	/**
	 * @return int
	 */
	function affectedRows() {
		return mysql_affected_rows( $this->mConn );
	}

	/**
	 * @param $table string
	 * @param $uniqueIndexes
	 * @param $rows array
	 * @param $fname string
	 * @return ResultWrapper
	 */
	function replace( $table, $uniqueIndexes, $rows, $fname = 'DatabaseMysql::replace' ) {
		return $this->nativeReplace( $table, $rows, $fname );
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * Takes same arguments as Database::select()
	 *
	 * @param $table string|array
	 * @param $vars string|array
	 * @param $conds string|array
	 * @param $fname string
	 * @param $options string|array
	 * @return int
	 */
	public function estimateRowCount( $table, $vars='*', $conds='', $fname = 'DatabaseMysql::estimateRowCount', $options = array() ) {
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
		return $rows;
	}

	/**
	 * @param $table string
	 * @param $field string
	 * @return bool|MySQLField
	 */
	function fieldInfo( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query( "SELECT * FROM $table LIMIT 1", __METHOD__, true );
		if ( !$res ) {
			return false;
		}
		$n = mysql_num_fields( $res->result );
		for( $i = 0; $i < $n; $i++ ) {
			$meta = mysql_fetch_field( $res->result, $i );
			if( $field == $meta->name ) {
				return new MySQLField($meta);
			}
		}
		return false;
	}

	/**
	 * Get information about an index into an object
	 * Returns false if the index does not exist
	 *
	 * @param $table string
	 * @param $index string
	 * @param $fname string
	 * @return bool|array|null False or null on failure
	 */
	function indexInfo( $table, $index, $fname = 'DatabaseMysql::indexInfo' ) {
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

		$result = array();

		foreach ( $res as $row ) {
			if ( $row->Key_name == $index ) {
				$result[] = $row;
			}
		}

		return empty( $result ) ? false : $result;
	}

	/**
	 * @param $db
	 * @return bool
	 */
	function selectDB( $db ) {
		$this->mDBname = $db;
		return mysql_select_db( $db, $this->mConn );
	}

	/**
	 * @param $s string
	 *
	 * @return string
	 */
	function strencode( $s ) {
		$sQuoted = mysql_real_escape_string( $s, $this->mConn );

		if($sQuoted === false) {
			$this->ping();
			$sQuoted = mysql_real_escape_string( $s, $this->mConn );
		}
		return $sQuoted;
	}

	/**
	 * MySQL uses `backticks` for identifier quoting instead of the sql standard "double quotes".
	 *
	 * @param $s string
	 *
	 * @return string
	 */
	public function addIdentifierQuotes( $s ) {
		return "`" . $this->strencode( $s ) . "`";
	}

	/**
	 * @param $name string
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return strlen( $name ) && $name[0] == '`' && substr( $name, -1, 1 ) == '`';
	}

	/**
	 * @return bool
	 */
	function ping() {
		$ping = mysql_ping( $this->mConn );
		if ( $ping ) {
			return true;
		}

		mysql_close( $this->mConn );
		$this->mOpened = false;
		$this->mConn = false;
		$this->open( $this->mServer, $this->mUser, $this->mPassword, $this->mDBname );
		return true;
	}

	/**
	 * Returns slave lag.
	 *
	 * This will do a SHOW SLAVE STATUS
	 *
	 * @return int
	 */
	function getLag() {
		if ( !is_null( $this->mFakeSlaveLag ) ) {
			wfDebug( "getLag: fake slave lagged {$this->mFakeSlaveLag} seconds\n" );
			return $this->mFakeSlaveLag;
		}

		return $this->getLagFromSlaveStatus();
	}

	/**
	 * @return bool|int
	 */
	function getLagFromSlaveStatus() {
		$res = $this->query( 'SHOW SLAVE STATUS', __METHOD__ );
		if ( !$res ) {
			return false;
		}
		$row = $res->fetchObject();
		if ( !$row ) {
			return false;
		}
		if ( strval( $row->Seconds_Behind_Master ) === '' ) {
			return false;
		} else {
			return intval( $row->Seconds_Behind_Master );
		}
	}

	/**
	 * @deprecated in 1.19, use getLagFromSlaveStatus
	 *
	 * @return bool|int
	 */
	function getLagFromProcesslist() {
		wfDeprecated( __METHOD__, '1.19' );
		$res = $this->query( 'SHOW PROCESSLIST', __METHOD__ );
		if( !$res ) {
			return false;
		}
		# Find slave SQL thread
		foreach( $res as $row ) {
			/* This should work for most situations - when default db
			 * for thread is not specified, it had no events executed,
			 * and therefore it doesn't know yet how lagged it is.
			 *
			 * Relay log I/O thread does not select databases.
			 */
			if ( $row->User == 'system user' &&
				$row->State != 'Waiting for master to send event' &&
				$row->State != 'Connecting to master' &&
				$row->State != 'Queueing master event to the relay log' &&
				$row->State != 'Waiting for master update' &&
				$row->State != 'Requesting binlog dump' &&
				$row->State != 'Waiting to reconnect after a failed master event read' &&
				$row->State != 'Reconnecting after a failed master event read' &&
				$row->State != 'Registering slave on master'
				) {
				# This is it, return the time (except -ve)
				if ( $row->Time > 0x7fffffff ) {
					return false;
				} else {
					return $row->Time;
				}
			}
		}
		return false;
	}

	/**
	 * Wait for the slave to catch up to a given master position.
	 *
	 * @param $pos DBMasterPos object
	 * @param $timeout Integer: the maximum number of seconds to wait for synchronisation
	 * @return bool|string
	 */
	function masterPosWait( DBMasterPos $pos, $timeout ) {
		$fname = 'DatabaseBase::masterPosWait';
		wfProfileIn( $fname );

		# Commit any open transactions
		if ( $this->mTrxLevel ) {
			$this->commit( __METHOD__ );
		}

		if ( !is_null( $this->mFakeSlaveLag ) ) {
			$status = parent::masterPosWait( $pos, $timeout );
			wfProfileOut( $fname );
			return $status;
		}

		# Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		$encFile = $this->addQuotes( $pos->file );
		$encPos = intval( $pos->pos );
		$sql = "SELECT MASTER_POS_WAIT($encFile, $encPos, $timeout)";
		$res = $this->doQuery( $sql );

		if ( $res && $row = $this->fetchRow( $res ) ) {
			wfProfileOut( $fname );
			return $row[0];
		} else {
			wfProfileOut( $fname );
			return false;
		}
	}

	/**
	 * Get the position of the master from SHOW SLAVE STATUS
	 *
	 * @return MySQLMasterPos|bool
	 */
	function getSlavePos() {
		if ( !is_null( $this->mFakeSlaveLag ) ) {
			return parent::getSlavePos();
		}

		$res = $this->query( 'SHOW SLAVE STATUS', 'DatabaseBase::getSlavePos' );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			$pos = isset( $row->Exec_master_log_pos ) ? $row->Exec_master_log_pos : $row->Exec_Master_Log_Pos;
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
		if ( $this->mFakeMaster ) {
			return parent::getMasterPos();
		}

		$res = $this->query( 'SHOW MASTER STATUS', 'DatabaseBase::getMasterPos' );
		$row = $this->fetchObject( $res );

		if ( $row ) {
			return new MySQLMasterPos( $row->File, $row->Position );
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 */
	function getServerVersion() {
		return mysql_get_server_info( $this->mConn );
	}

	/**
	 * @param $index
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
	public static function getSoftwareLink() {
		return '[http://www.mysql.com/ MySQL]';
	}

	/**
	 * @param $options array
	 */
	public function setSessionOptions( array $options ) {
		if ( isset( $options['connTimeout'] ) ) {
			$timeout = (int)$options['connTimeout'];
			$this->query( "SET net_read_timeout=$timeout" );
			$this->query( "SET net_write_timeout=$timeout" );
		}
	}

	public function streamStatementEnd( &$sql, &$newLine ) {
		if ( strtoupper( substr( $newLine, 0, 9 ) ) == 'DELIMITER' ) {
			preg_match( '/^DELIMITER\s+(\S+)/' , $newLine, $m );
			$this->delimiter = $m[1];
			$newLine = '';
		}
		return parent::streamStatementEnd( $sql, $newLine );
	}

	/**
	 * Check to see if a named lock is available. This is non-blocking.
	 *
	 * @param $lockName String: name of lock to poll
	 * @param $method String: name of method calling us
	 * @return Boolean
	 * @since 1.20
	 */
	public function lockIsFree( $lockName, $method ) {
		$lockName = $this->addQuotes( $lockName );
		$result = $this->query( "SELECT IS_FREE_LOCK($lockName) AS lockstatus", $method );
		$row = $this->fetchObject( $result );
		return ( $row->lockstatus == 1 );
	}

	/**
	 * @param $lockName string
	 * @param $method string
	 * @param $timeout int
	 * @return bool
	 */
	public function lock( $lockName, $method, $timeout = 5 ) {
		$lockName = $this->addQuotes( $lockName );
		$result = $this->query( "SELECT GET_LOCK($lockName, $timeout) AS lockstatus", $method );
		$row = $this->fetchObject( $result );

		if( $row->lockstatus == 1 ) {
			return true;
		} else {
			wfDebug( __METHOD__." failed to acquire lock\n" );
			return false;
		}
	}

	/**
	 * FROM MYSQL DOCS: http://dev.mysql.com/doc/refman/5.0/en/miscellaneous-functions.html#function_release-lock
	 * @param $lockName string
	 * @param $method string
	 * @return bool
	 */
	public function unlock( $lockName, $method ) {
		$lockName = $this->addQuotes( $lockName );
		$result = $this->query( "SELECT RELEASE_LOCK($lockName) as lockstatus", $method );
		$row = $this->fetchObject( $result );
		return ( $row->lockstatus == 1 );
	}

	/**
	 * @param $read array
	 * @param $write array
	 * @param $method string
	 * @param $lowPriority bool
	 */
	public function lockTables( $read, $write, $method, $lowPriority = true ) {
		$items = array();

		foreach( $write as $table ) {
			$tbl = $this->tableName( $table ) .
					( $lowPriority ? ' LOW_PRIORITY' : '' ) .
					' WRITE';
			$items[] = $tbl;
		}
		foreach( $read as $table ) {
			$items[] = $this->tableName( $table ) . ' READ';
		}
		$sql = "LOCK TABLES " . implode( ',', $items );
		$this->query( $sql, $method );
	}

	/**
	 * @param $method string
	 */
	public function unlockTables( $method ) {
		$this->query( "UNLOCK TABLES", $method );
	}

	/**
	 * Get search engine class. All subclasses of this
	 * need to implement this if they wish to use searching.
	 *
	 * @return String
	 */
	public function getSearchEngine() {
		return 'SearchMySQL';
	}

	/**
	 * @param bool $value
	 * @return mixed
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
			$this->mDefaultBigSelects = (bool)$this->selectField( false, '@@sql_big_selects' );
		}
		$encValue = $value ? '1' : '0';
		$this->query( "SET sql_big_selects=$encValue", __METHOD__ );
	}

	/**
	 * DELETE where the condition is a join. MySql uses multi-table deletes.
	 * @param $delTable string
	 * @param $joinTable string
	 * @param $delVar string
	 * @param $joinVar string
	 * @param $conds array|string
	 * @param $fname bool
	 * @return bool|ResultWrapper
	 */
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = 'DatabaseBase::deleteJoin' ) {
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

	/**
	 * @param $oldName
	 * @param $newName
	 * @param $temporary bool
	 * @param $fname string
	 */
	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = 'DatabaseMysql::duplicateTableStructure' ) {
		$tmp = $temporary ? 'TEMPORARY ' : '';
		$newName = $this->addIdentifierQuotes( $newName );
		$oldName = $this->addIdentifierQuotes( $oldName );
		$query = "CREATE $tmp TABLE $newName (LIKE $oldName)";
		$this->query( $query, $fname );
	}

	/**
	 * List all tables on the database
	 *
	 * @param $prefix string Only show tables with this prefix, e.g. mw_
	 * @param $fname String: calling function name
	 * @return array
	 */
	function listTables( $prefix = null, $fname = 'DatabaseMysql::listTables' ) {
		$result = $this->query( "SHOW TABLES", $fname);

		$endArray = array();

		foreach( $result as $table ) {
			$vars = get_object_vars($table);
			$table = array_pop( $vars );

			if( !$prefix || strpos( $table, $prefix ) === 0 ) {
				$endArray[] = $table;
			}
		}

		return $endArray;
	}

	/**
	 * @param $tableName
	 * @param $fName string
	 * @return bool|ResultWrapper
	 */
	public function dropTable( $tableName, $fName = 'DatabaseMysql::dropTable' ) {
		if( !$this->tableExists( $tableName, $fName ) ) {
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
		$vars['wgDBTableOptions'] = str_replace( 'CHARSET=mysql4', 'CHARSET=binary', $vars['wgDBTableOptions'] );
		return $vars;
	}

	/**
	 * Get status information from SHOW STATUS in an associative array
	 *
	 * @param $which string
	 * @return array
	 */
	function getMysqlStatus( $which = "%" ) {
		$res = $this->query( "SHOW STATUS LIKE '{$which}'" );
		$status = array();

		foreach ( $res as $row ) {
			$status[$row->Variable_name] = $row->Value;
		}

		return $status;
	}

}

/**
 * Legacy support: Database == DatabaseMysql
 *
 * @deprecated in 1.16
 */
class Database extends DatabaseMysql {}

/**
 * Utility class.
 * @ingroup Database
 */
class MySQLField implements Field {
	private $name, $tablename, $default, $max_length, $nullable,
		$is_pk, $is_unique, $is_multiple, $is_key, $type;

	function __construct ( $info ) {
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
		return $this->tableName;
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
}

class MySQLMasterPos implements DBMasterPos {
	var $file, $pos;

	function __construct( $file, $pos ) {
		$this->file = $file;
		$this->pos = $pos;
	}

	function __toString() {
		return "{$this->file}/{$this->pos}";
	}
}
