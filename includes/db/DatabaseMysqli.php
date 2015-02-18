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
 * Database abstraction object for PHP extension mysqli.
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 */
class DatabaseMysqli extends DatabaseMysqlBase {
	/**
	 * @param string $sql
	 * @return resource
	 */
	protected function doQuery( $sql ) {
		if ( $this->bufferResults() ) {
			$ret = $this->mConn->query( $sql );
		} else {
			$ret = $this->mConn->query( $sql, MYSQLI_USE_RESULT );
		}

		return $ret;
	}

	/**
	 * @param string $realServer
	 * @return bool|mysqli
	 * @throws DBConnectionError
	 */
	protected function mysqlConnect( $realServer ) {
		global $wgDBmysql5;
		# Fail now
		# Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !function_exists( 'mysqli_init' ) ) {
			throw new DBConnectionError( $this, "MySQLi functions missing,"
				. " have you compiled PHP with the --with-mysqli option?\n" );
		}

		// Other than mysql_connect, mysqli_real_connect expects an explicit port
		// and socket parameters. So we need to parse the port and socket out of
		// $realServer
		$port = null;
		$socket = null;
		$hostAndPort = IP::splitHostAndPort( $realServer );
		if ( $hostAndPort ) {
			$realServer = $hostAndPort[0];
			if ( $hostAndPort[1] ) {
				$port = $hostAndPort[1];
			}
		} elseif ( substr_count( $realServer, ':' ) == 1 ) {
			// If we have a colon and something that's not a port number
			// inside the hostname, assume it's the socket location
			$hostAndSocket = explode( ':', $realServer );
			$realServer = $hostAndSocket[0];
			$socket = $hostAndSocket[1];
		}

		$connFlags = 0;
		if ( $this->mFlags & DBO_SSL ) {
			$connFlags |= MYSQLI_CLIENT_SSL;
		}
		if ( $this->mFlags & DBO_COMPRESS ) {
			$connFlags |= MYSQLI_CLIENT_COMPRESS;
		}
		if ( $this->mFlags & DBO_PERSISTENT ) {
			$realServer = 'p:' . $realServer;
		}

		$mysqli = mysqli_init();
		if ( $wgDBmysql5 ) {
			// Tell the server we're communicating with it in UTF-8.
			// This may engage various charset conversions.
			$mysqli->options( MYSQLI_SET_CHARSET_NAME, 'utf8' );
		} else {
			$mysqli->options( MYSQLI_SET_CHARSET_NAME, 'binary' );
		}
		$mysqli->options( MYSQLI_OPT_CONNECT_TIMEOUT, 3 );

		if ( $mysqli->real_connect( $realServer, $this->mUser,
			$this->mPassword, $this->mDBname, $port, $socket, $connFlags )
		) {
			return $mysqli;
		}

		return false;
	}

	protected function connectInitCharset() {
		// already done in mysqlConnect()
		return true;
	}

	/**
	 * @param string $charset
	 * @return bool
	 */
	protected function mysqlSetCharset( $charset ) {
		if ( method_exists( $this->mConn, 'set_charset' ) ) {
			return $this->mConn->set_charset( $charset );
		} else {
			return $this->query( 'SET NAMES ' . $charset, __METHOD__ );
		}
	}

	/**
	 * @return bool
	 */
	protected function closeConnection() {
		return $this->mConn->close();
	}

	/**
	 * @return int
	 */
	function insertId() {
		return $this->mConn->insert_id;
	}

	/**
	 * @return int
	 */
	function lastErrno() {
		if ( $this->mConn ) {
			return $this->mConn->errno;
		} else {
			return mysqli_connect_errno();
		}
	}

	/**
	 * @return int
	 */
	function affectedRows() {
		return $this->mConn->affected_rows;
	}

	/**
	 * @param string $db
	 * @return bool
	 */
	function selectDB( $db ) {
		$this->mDBname = $db;

		return $this->mConn->select_db( $db );
	}

	/**
	 * @param mysqli $res
	 * @return bool
	 */
	protected function mysqlFreeResult( $res ) {
		$res->free_result();

		return true;
	}

	/**
	 * @param mysqli $res
	 * @return bool
	 */
	protected function mysqlFetchObject( $res ) {
		$object = $res->fetch_object();
		if ( $object === null ) {
			return false;
		}

		return $object;
	}

	/**
	 * @param mysqli $res
	 * @return bool
	 */
	protected function mysqlFetchArray( $res ) {
		$array = $res->fetch_array();
		if ( $array === null ) {
			return false;
		}

		return $array;
	}

	/**
	 * @param mysqli $res
	 * @return mixed
	 */
	protected function mysqlNumRows( $res ) {
		return $res->num_rows;
	}

	/**
	 * @param mysqli $res
	 * @return mixed
	 */
	protected function mysqlNumFields( $res ) {
		return $res->field_count;
	}

	/**
	 * @param mysqli $res
	 * @param int $n
	 * @return mixed
	 */
	protected function mysqlFetchField( $res, $n ) {
		$field = $res->fetch_field_direct( $n );

		// Add missing properties to result (using flags property)
		// which will be part of function mysql-fetch-field for backward compatibility
		$field->not_null = $field->flags & MYSQLI_NOT_NULL_FLAG;
		$field->primary_key = $field->flags & MYSQLI_PRI_KEY_FLAG;
		$field->unique_key = $field->flags & MYSQLI_UNIQUE_KEY_FLAG;
		$field->multiple_key = $field->flags & MYSQLI_MULTIPLE_KEY_FLAG;
		$field->binary = $field->flags & MYSQLI_BINARY_FLAG;
		$field->numeric = $field->flags & MYSQLI_NUM_FLAG;
		$field->blob = $field->flags & MYSQLI_BLOB_FLAG;
		$field->unsigned = $field->flags & MYSQLI_UNSIGNED_FLAG;
		$field->zerofill = $field->flags & MYSQLI_ZEROFILL_FLAG;

		return $field;
	}

	/**
	 * @param resource|ResultWrapper $res
	 * @param int $n
	 * @return mixed
	 */
	protected function mysqlFieldName( $res, $n ) {
		$field = $res->fetch_field_direct( $n );

		return $field->name;
	}

	/**
	 * @param resource|ResultWrapper $res
	 * @param int $n
	 * @return mixed
	 */
	protected function mysqlFieldType( $res, $n ) {
		$field = $res->fetch_field_direct( $n );

		return $field->type;
	}

	/**
	 * @param resource|ResultWrapper $res
	 * @param int $row
	 * @return mixed
	 */
	protected function mysqlDataSeek( $res, $row ) {
		return $res->data_seek( $row );
	}

	/**
	 * @param mysqli $conn Optional connection object
	 * @return string
	 */
	protected function mysqlError( $conn = null ) {
		if ( $conn === null ) {
			return mysqli_connect_error();
		} else {
			return $conn->error;
		}
	}

	/**
	 * Escapes special characters in a string for use in an SQL statement
	 * @param string $s
	 * @return string
	 */
	protected function mysqlRealEscapeString( $s ) {
		return $this->mConn->real_escape_string( $s );
	}

	protected function mysqlPing() {
		return $this->mConn->ping();
	}

	/**
	 * Give an id for the connection
	 *
	 * mysql driver used resource id, but mysqli objects cannot be cast to string.
	 * @return string
	 */
	public function __toString() {
		if ( $this->mConn instanceof Mysqli ) {
			return (string)$this->mConn->thread_id;
		} else {
			// mConn might be false or something.
			return (string)$this->mConn;
		}
	}
}
