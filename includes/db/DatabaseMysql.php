<?php
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

		# Test for missing mysql.so
		# First try to load it
		if (!@extension_loaded('mysql')) {
			@dl('mysql.so');
		}

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

		$success = false;

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
			if ($this->mConn === false) {
				#$iplus = $i + 1;
				#wfLogDBError("Connect loop error $iplus of $max ($server): " . mysql_errno() . " - " . mysql_error()."\n"); 
			}
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
			$success = false;
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
				}
				// Turn off strict mode
				$this->query( "SET sql_mode = ''", __METHOD__ );
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
	public function estimateRowCount( $table, $vars='*', $conds='', $fname = 'Database::estimateRowCount', $options = array() ) {
		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		if ( $res === false )
			return false;
		if ( !$this->numRows( $res ) ) {
			$this->freeResult($res);
			return 0;
		}

		$rows = 1;
		while( $plan = $this->fetchObject( $res ) ) {
			$rows *= $plan->rows > 0 ? $plan->rows : 1; // avoid resetting to zero
		}

		$this->freeResult($res);
		return $rows;		
	}

	function fieldInfo( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query( "SELECT * FROM $table LIMIT 1" );
		$n = mysql_num_fields( $res->result );
		for( $i = 0; $i < $n; $i++ ) {
			$meta = mysql_fetch_field( $res->result, $i );
			if( $field == $meta->name ) {
				return new MySQLField($meta);
			}
		}
		return false;
	}

	function selectDB( $db ) {
		$this->mDBname = $db;
		return mysql_select_db( $db, $this->mConn );
	}

	function strencode( $s ) {
		return mysql_real_escape_string( $s, $this->mConn );
	}

	function ping() {
		if( !function_exists( 'mysql_ping' ) ) {
			wfDebug( "Tried to call mysql_ping but this is ancient PHP version. Faking it!\n" );
			return true;
		}
		$ping = mysql_ping( $this->mConn );
		if ( $ping ) {
			return true;
		}

		// Need to reconnect manually in MySQL client 5.0.13+
		if ( version_compare( mysql_get_client_info(), '5.0.13', '>=' ) ) {
			mysql_close( $this->mConn );
			$this->mOpened = false;
			$this->mConn = false;
			$this->open( $this->mServer, $this->mUser, $this->mPassword, $this->mDBname );
			return true;
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

	function getSoftwareLink() {
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
		$this->freeResult( $result );

		if( $row->lockstatus == 1 ) {
			return true;
		} else {
			wfDebug( __METHOD__." failed to acquire lock\n" );
			return false;
		}
	}

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

}

/**
 * Legacy support: Database == DatabaseMysql
 */
class Database extends DatabaseMysql {}

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
