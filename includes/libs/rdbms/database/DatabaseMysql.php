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
	 * @param string $sql
	 * @return resource False on error
	 */
	protected function doQuery( $sql ) {
		$conn = $this->getBindingHandle();

		if ( $this->bufferResults() ) {
			$ret = mysql_query( $sql, $conn );
		} else {
			$ret = mysql_unbuffered_query( $sql, $conn );
		}

		return $ret;
	}

	/**
	 * @param string $realServer
	 * @return bool|resource MySQL Database connection or false on failure to connect
	 * @throws DBConnectionError
	 */
	protected function mysqlConnect( $realServer ) {
		# Avoid a suppressed fatal error, which is very hard to track down
		if ( !extension_loaded( 'mysql' ) ) {
			throw new DBConnectionError(
				$this,
				"MySQL functions missing, have you compiled PHP with the --with-mysql option?\n"
			);
		}

		$connFlags = 0;
		if ( $this->mFlags & self::DBO_SSL ) {
			$connFlags |= MYSQL_CLIENT_SSL;
		}
		if ( $this->mFlags & self::DBO_COMPRESS ) {
			$connFlags |= MYSQL_CLIENT_COMPRESS;
		}

		if ( ini_get( 'mysql.connect_timeout' ) <= 3 ) {
			$numAttempts = 2;
		} else {
			$numAttempts = 1;
		}

		$conn = false;

		# The kernel's default SYN retransmission period is far too slow for us,
		# so we use a short timeout plus a manual retry. Retrying means that a small
		# but finite rate of SYN packet loss won't cause user-visible errors.
		for ( $i = 0; $i < $numAttempts && !$conn; $i++ ) {
			if ( $i > 1 ) {
				usleep( 1000 );
			}
			if ( $this->mFlags & self::DBO_PERSISTENT ) {
				$conn = mysql_pconnect( $realServer, $this->mUser, $this->mPassword, $connFlags );
			} else {
				# Create a new connection...
				$conn = mysql_connect( $realServer, $this->mUser, $this->mPassword, true, $connFlags );
			}
		}

		return $conn;
	}

	/**
	 * @param string $charset
	 * @return bool
	 */
	protected function mysqlSetCharset( $charset ) {
		$conn = $this->getBindingHandle();

		if ( function_exists( 'mysql_set_charset' ) ) {
			return mysql_set_charset( $charset, $conn );
		} else {
			return $this->query( 'SET NAMES ' . $charset, __METHOD__ );
		}
	}

	/**
	 * @return bool
	 */
	protected function closeConnection() {
		$conn = $this->getBindingHandle();

		return mysql_close( $conn );
	}

	/**
	 * @return int
	 */
	function insertId() {
		$conn = $this->getBindingHandle();

		return mysql_insert_id( $conn );
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
		$conn = $this->getBindingHandle();

		return mysql_affected_rows( $conn );
	}

	/**
	 * @param string $db
	 * @return bool
	 */
	function selectDB( $db ) {
		$conn = $this->getBindingHandle();

		$this->mDBname = $db;

		return mysql_select_db( $db, $conn );
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

	protected function mysqlFieldType( $res, $n ) {
		return mysql_field_type( $res, $n );
	}

	protected function mysqlDataSeek( $res, $row ) {
		return mysql_data_seek( $res, $row );
	}

	protected function mysqlError( $conn = null ) {
		return ( $conn !== null ) ? mysql_error( $conn ) : mysql_error(); // avoid warning
	}

	protected function mysqlRealEscapeString( $s ) {
		$conn = $this->getBindingHandle();

		return mysql_real_escape_string( $s, $conn );
	}
}
