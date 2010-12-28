<?php
/**
 * This is the MySQL database abstraction layer.
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
	function getType() {
		return 'mysql';
	}

	/*private*/ function doQuery( $sql ) {
		if( $this->bufferResults() ) {
			$ret = mysql_query( $sql, $this->mConn );
		} else {
			$ret = mysql_unbuffered_query( $sql, $this->mConn );
		}
		return $ret;
	}

	function open( $server, $user, $password, $dbName ) {
		global $wgAllDBsAreLocalhost;
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

		wfProfileIn("dbconnect-$server");

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
				$this->mConn = mysql_pconnect( $realServer, $user, $password );
			} else {
				# Create a new connection...
				$this->mConn = mysql_connect( $realServer, $user, $password, true );
			}
			#if ( $this->mConn === false ) {
				#$iplus = $i + 1;
				#wfLogDBError("Connect loop error $iplus of $max ($server): " . mysql_errno() . " - " . mysql_error()."\n");
			#}
		}
		$phpError = $this->restoreErrorHandler();
		# Always log connection errors
		if ( !$this->mConn ) {
			$error = $this->lastError();
			if ( !$error ) {
				$error = $phpError;
			}
			wfLogDBError( "Error connecting to {$this->mServer}: $error\n" );
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, User: $user, Password: " .
				substr( $password, 0, 3 ) . "..., error: " . mysql_error() . "\n" );
		}

		wfProfileOut("dbconnect-$server");

		if ( $dbName != '' && $this->mConn !== false ) {
			$success = @/**/mysql_select_db( $dbName, $this->mConn );
			if ( !$success ) {
				$error = "Error selecting database $dbName on server {$this->mServer} " .
					"from client host " . wfHostname() . "\n";
				wfLogDBError(" Error selecting database $dbName on server {$this->mServer} \n");
				wfDebug( $error );
			}
		} else {
			# Delay USE query
			$success = (bool)$this->mConn;
		}

		if ( $success ) {
			$version = $this->getServerVersion();
			if ( version_compare( $version, '4.1' ) >= 0 ) {
				// Tell the server we're communicating with it in UTF-8.
				// This may engage various charset conversions.
				global $wgDBmysql5;
				if( $wgDBmysql5 ) {
					$this->query( 'SET NAMES utf8', __METHOD__ );
				} else {
					$this->query( 'SET NAMES binary', __METHOD__ );
				}
				// Set SQL mode, default is turning them all off, can be overridden or skipped with null
				global $wgSQLMode;
				if ( is_string( $wgSQLMode ) ) {
					$mode = $this->addQuotes( $wgSQLMode );
					$this->query( "SET sql_mode = $mode", __METHOD__ );
				}
			}

			// Turn off strict mode if it is on
		} else {
			$this->reportConnectionError( $phpError );
		}

		$this->mOpened = $success;
		wfProfileOut( __METHOD__ );
		return $success;
	}

	function close() {
		$this->mOpened = false;
		if ( $this->mConn ) {
			if ( $this->trxLevel() ) {
				$this->commit();
			}
			return mysql_close( $this->mConn );
		} else {
			return true;
		}
	}

	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( !@/**/mysql_free_result( $res ) ) {
			throw new DBUnexpectedError( $this, "Unable to free MySQL result" );
		}
	}

	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@/**/$row = mysql_fetch_object( $res );
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchObject(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

 	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@/**/$row = mysql_fetch_array( $res );
		if ( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchRow(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@/**/$n = mysql_num_rows( $res );
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in numRows(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $n;
	}

	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return mysql_num_fields( $res );
	}

	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return mysql_field_name( $res, $n );
	}

	function insertId() { return mysql_insert_id( $this->mConn ); }

	function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return mysql_data_seek( $res, $row );
	}

	function lastErrno() {
		if ( $this->mConn ) {
			return mysql_errno( $this->mConn );
		} else {
			return mysql_errno();
		}
	}

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

	function affectedRows() { return mysql_affected_rows( $this->mConn ); }

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * Takes same arguments as Database::select()
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

	function selectDB( $db ) {
		$this->mDBname = $db;
		return mysql_select_db( $db, $this->mConn );
	}

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
	 */
	public function addIdentifierQuotes( $s ) {
		return "`" . $this->strencode( $s ) . "`";
	}

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
	 * At the moment, this will only work if the DB user has the PROCESS privilege
	 * @result int
	 */
	function getLag() {
		if ( !is_null( $this->mFakeSlaveLag ) ) {
			wfDebug( "getLag: fake slave lagged {$this->mFakeSlaveLag} seconds\n" );
			return $this->mFakeSlaveLag;
		}
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

	function getServerVersion() {
		return mysql_get_server_info( $this->mConn );
	}

	function useIndexClause( $index ) {
		return "FORCE INDEX (" . $this->indexName( $index ) . ")";
	}

	function lowPriorityOption() {
		return 'LOW_PRIORITY';
	}

	public static function getSoftwareLink() {
		return '[http://www.mysql.com/ MySQL]';
	}

	function standardSelectDistinct() {
		return false;
	}

	public function setTimeout( $timeout ) {
		$this->query( "SET net_read_timeout=$timeout" );
		$this->query( "SET net_write_timeout=$timeout" );
	}

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
	 */
	public function unlock( $lockName, $method ) {
		$lockName = $this->addQuotes( $lockName );
		$result = $this->query( "SELECT RELEASE_LOCK($lockName) as lockstatus", $method );
		$row = $this->fetchObject( $result );
		return $row->lockstatus;
	}

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

	public function unixTimestamp( $field ) {
		return "UNIX_TIMESTAMP($field)";
	}

	/**
	 * Determines if the last failure was due to a deadlock
	 */
	function wasDeadlock() {
		return $this->lastErrno() == 1213;
	}

	/**
	 * Determines if the last query error was something that should be dealt
	 * with by pinging the connection and reissuing the query
	 */
	function wasErrorReissuable() {
		return $this->lastErrno() == 2013 || $this->lastErrno() == 2006;
	}

	/**
	 * Determines if the last failure was due to the database being read-only.
	 */
	function wasReadOnlyError() {
		return $this->lastErrno() == 1223 ||
			( $this->lastErrno() == 1290 && strpos( $this->lastError(), '--read-only' ) !== false );
	}

	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = 'DatabaseMysql::duplicateTableStructure' ) {
		$tmp = $temporary ? 'TEMPORARY ' : '';
		if ( strcmp( $this->getServerVersion(), '4.1' ) < 0 ) {
			# Hack for MySQL versions < 4.1, which don't support
			# "CREATE TABLE ... LIKE". Note that
			# "CREATE TEMPORARY TABLE ... SELECT * FROM ... LIMIT 0"
			# would not create the indexes we need....
			#
			# Note that we don't bother changing around the prefixes here be-
			# cause we know we're using MySQL anyway.

			$res = $this->query( "SHOW CREATE TABLE $oldName" );
			$row = $this->fetchRow( $res );
			$oldQuery = $row[1];
			$query = preg_replace( '/CREATE TABLE `(.*?)`/',
				"CREATE $tmp TABLE `$newName`", $oldQuery );
			if ($oldQuery === $query) {
				# Couldn't do replacement
				throw new MWException( "could not create temporary table $newName" );
			}
		} else {
			$query = "CREATE $tmp TABLE $newName (LIKE $oldName)";
		}
		$this->query( $query, $fname );
	}

	public function dropTable( $tableName, $fName = 'DatabaseMysql::dropTable' ) {
		if( !$this->tableExists( $tableName ) ) {
			return false;
		}
		return $this->query( "DROP TABLE IF EXISTS " . $this->tableName( $tableName ), $fName );
	}

}

/**
 * Legacy support: Database == DatabaseMysql
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

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tableName;
	}

	function type() {
		return $this->type;
	}

	function isNullable() {
		return $this->nullable;
	}

	function defaultValue() {
		return $this->default;
	}

	function isKey() {
		return $this->is_key;
	}

	function isMultipleKey() {
		return $this->is_multiple;
	}
}

class MySQLMasterPos {
	var $file, $pos;

	function __construct( $file, $pos ) {
		$this->file = $file;
		$this->pos = $pos;
	}

	function __toString() {
		return "{$this->file}/{$this->pos}";
	}
}
