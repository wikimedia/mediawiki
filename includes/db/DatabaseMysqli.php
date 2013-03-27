<?php
/**
 * This is the MySQLi database abstraction layer.
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
class DatabaseMysqli extends DatabaseMysql {

	function getType() {
		return 'mysqli';
	}

	function doQuery( $sql ) {
		return $this->mConn->query( $sql, $this->bufferResults() ? MYSQLI_STORE_RESULT : MYSQLI_USE_RESULT );
	}

	function doBegin( $fname ) {
		$this->mConn->autocommit( false );
		$this->mTrxLevel = 1;
	}

	function doCommit( $fname ) {
		if ( $this->mTrxLevel ) {
			$this->mConn->commit();
			$this->mConn->autocommit( true );
			$this->mTrxLevel = 0;
		}
	}

	function doRollback( $name ) {
		if ( $this->mTrxLevel ) {
			$this->mConn->rollback();
			$this->mConn->autocommit( true );
			$this->mTrxLevel = 0;
		}
	}

	function open( $server, $user, $password, $dbName ) {
		global $wgAllDBsAreLocalhost, $wgDBmysql5, $wgSQLMode;
		wfProfileIn( __METHOD__ );

		// Load mysqli.so if we don't have it
		wfDl( 'mysqli' );

		// Fail now
		// Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !class_exists( 'mysqli' ) ) {
			throw new DBConnectionError( $this, "MySQLi class missing, have you compiled PHP with the --with-mysqli option?\n" );
		}

		// Debugging hack -- fake cluster
		if ( $wgAllDBsAreLocalhost ) {
			$realServer = 'localhost';
		} else {
			$realServer = $server;
		}

		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName ?: null;

		$connFlags = 0;
		if ( $this->mFlags & DBO_SSL ) {
			$connFlags |= MYSQL_CLIENT_SSL;
		}
		if ( $this->mFlags & DBO_COMPRESS ) {
			$connFlags |= MYSQL_CLIENT_COMPRESS;
		}
		if ( $this->mFlags & DBO_PERSISTENT ) {
			$realServer = "p:$realServer";
		}

		wfProfileIn( "dbconnect-$server" );

		// The kernel's default SYN retransmission period is far too slow for us,
		// so we use a short timeout plus a manual retry. Retrying means that a small
		// but finite rate of SYN packet loss won't cause user-visible errors.
		$this->mConn = false;
		if ( ini_get( 'mysql.connect_timeout' ) <= 3 ) {
			$numAttempts = 2;
		} else {
			$numAttempts = 1;
		}

		$this->installErrorHandler();
		$this->mConn = mysqli_init();
		$ok = false;
		for ( $i = 0; $i < $numAttempts && !$ok; $i++ ) {
			if ( $i > 1 ) {
				usleep( 1000 );
			}
			$ok = $this->mConn->real_connect( $realServer, $user, $password, $this->mDBname, /* port */ null, /* $socket */ null, $connFlags );
		}
		$error = $this->restoreErrorHandler();

		wfProfileOut( "dbconnect-$server" );

		// Always log connection errors
		if ( !$ok ) {
			$this->mConn = false;
			if ( !$error ) {
				$error = $this->lastError();
			}
			wfLogDBError( "Error connecting to {$this->mServer}: $error\n" );
			wfDebug( "DB connection error\n" .
				"Server: $server, User: $user, Password: " .
				substr( $password, 0, 3 ) . "..., error: " . $error . "\n" );

			wfProfileOut( __METHOD__ );
			return $this->reportConnectionError( $error );
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

	function closeConnection() {
		return $this->mConn->close();
	}

	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		$res->close();
	}

	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		$row = $res->fetch_object();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_object does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_object can cause.
		// See http://dev.mysql.com/doc/refman/5.6/en/mysql-fetch-row.html.
		if( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError( $this, 'Error in fetchObject(): ' . htmlspecialchars( $this->lastError() ) );
		}

		return $row ?: false;
	}

	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		$row = $res->fetch_array();

		$errno = $this->lastErrno();
		// Unfortunately, mysql_fetch_array does not reset the last errno.
		// Only check for CR_SERVER_LOST and CR_UNKNOWN_ERROR, as
		// these are the only errors mysql_fetch_object can cause.
		// See http://dev.mysql.com/doc/refman/5.6/en/mysql-fetch-row.html.
		if( $errno == 2000 || $errno == 2013 ) {
			throw new DBUnexpectedError( $this, 'Error in fetchRow(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row ?: false;
	}

	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return $res->num_rows;
	}

	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return $res->field_count;
	}

	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return $res->fetch_field_direct( $n )->name;
	}

	function insertId() {
		return $this->mConn->insert_id;
	}

	function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return $res->data_seek( $row );
	}

	function lastErrno() {
		if ( $this->mConn ) {
			return $this->mConn->errno;
		} else {
			return mysqli_connect_errno();
		}
	}

	function lastError() {
		if ( $this->mConn ) {
			$error = $this->mConn->error;
		} else {
			$error = mysqli_connect_error();
		}
		if( $error ) {
			$error .= ' (' . $this->mServer . ')';
		}
		return $error;
	}

	function affectedRows() {
		return $this->mConn->affected_rows;
	}

	function fieldInfo( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query( "SELECT * FROM $table LIMIT 1", __METHOD__, true );
		if ( !$res ) {
			return false;
		}
		for( $i = 0; $i < $res->result->field_count; $i++ ) {
			$meta = $res->result->fetch_field( $i );
			if( $field == $meta->name ) {
				return new MySQLField( $meta );
			}
		}
		return false;
	}

	function selectDB( $db ) {
		$this->mDBname = $db;
		return $this->mConn->select_db( $db );
	}

	function strencode( $s ) {
		$sQuoted = $this->mConn->real_escape_string( $s );

		if( $sQuoted === false ) {
			$this->ping();
			$sQuoted = $this->mConn->real_escape_string( $s );
		}
		return $sQuoted;
	}

	function ping() {
		$ping = $this->mConn->ping();
		if ( $ping ) {
			return true;
		}

		$this->mConn->close();
		$this->mOpened = false;
		$this->mConn = false;
		$this->open( $this->mServer, $this->mUser, $this->mPassword, $this->mDBname );
		return true;
	}

	function getServerVersion() {
		return $this->mConn->server_version;
	}
}
