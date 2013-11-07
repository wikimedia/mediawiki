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
 * Database abstraction object for PHP extension mysql.
 *
 * @ingroup Database
 * @see Database
 */
class DatabaseMysql extends DatabaseMysqlBase {

	/**
	 * @param $sql string
	 * @return resource
	 */
	protected function doQuery( $sql ) {
		if ( $this->bufferResults() ) {
			$ret = mysql_query( $sql, $this->mConn );
		} else {
			$ret = mysql_unbuffered_query( $sql, $this->mConn );
		}
		return $ret;
	}

	protected function mysqlConnect( $realServer ) {
		# Fail now
		# Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !extension_loaded( 'mysql' ) ) {
			throw new DBConnectionError( $this, "MySQL functions missing, have you compiled PHP with the --with-mysql option?\n" );
		}

		$connFlags = 0;
		if ( $this->mFlags & DBO_SSL ) {
			$connFlags |= MYSQL_CLIENT_SSL;
		}
		if ( $this->mFlags & DBO_COMPRESS ) {
			$connFlags |= MYSQL_CLIENT_COMPRESS;
		}

		if ( ini_get( 'mysql.connect_timeout' ) <= 3 ) {
			$numAttempts = 2;
		} else {
			$numAttempts = 1;
		}

		$conn = false;

		for ( $i = 0; $i < $numAttempts && !$conn; $i++ ) {
			if ( $i > 1 ) {
				usleep( 1000 );
			}
			if ( $this->mFlags & DBO_PERSISTENT ) {
				$conn = mysql_pconnect( $realServer, $this->mUser, $this->mPassword, $connFlags );
			} else {
				# Create a new connection...
				$conn = mysql_connect( $realServer, $this->mUser, $this->mPassword, true, $connFlags );
			}
		}

		return $conn;
	}

	/**
	 * @return bool
	 */
	protected function closeConnection() {
		return mysql_close( $this->mConn );
	}

	/**
	 * @return int
	 */
	function insertId() {
		return mysql_insert_id( $this->mConn );
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
	 * @return int
	 */
	function affectedRows() {
		return mysql_affected_rows( $this->mConn );
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
	 * @return string
	 */
	function getServerVersion() {
		return mysql_get_server_info( $this->mConn );
	}

	protected function mysqlFreeResult( $res ) {
		return mysql_free_result( $res );
	}

	protected function mysqlFetchObject( $res ) {
		return mysql_fetch_object( $res );
	}

	protected function mysqlFetchArray( $res ) {
		return mysql_fetch_array( $res );
	}

	protected function mysqlNumRows( $res ) {
		return mysql_num_rows( $res );
	}

	protected function mysqlNumFields( $res ) {
		return mysql_num_fields( $res );
	}

	protected function mysqlFetchField( $res, $n ) {
		return mysql_fetch_field( $res, $n );
	}

	protected function mysqlFieldName( $res, $n ) {
		return mysql_field_name( $res, $n );
	}

	protected function mysqlDataSeek( $res, $row ) {
		return mysql_data_seek( $res, $row );
	}

	protected function mysqlError( $conn = null ) {
		return ( $conn !== null ) ? mysql_error( $conn ) : mysql_error(); // avoid warning
	}

	protected function mysqlRealEscapeString( $s ) {
		return mysql_real_escape_string( $s, $this->mConn );
	}

	protected function mysqlPing() {
		return mysql_ping( $this->mConn );
	}
}
