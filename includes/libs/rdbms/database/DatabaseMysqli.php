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
namespace Wikimedia\Rdbms;

use mysqli;
use mysqli_result;
use IP;
use stdClass;

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
	 * @return mysqli_result
	 */
	protected function doQuery( $sql ) {
		$conn = $this->getBindingHandle();

		if ( $this->bufferResults() ) {
			$ret = $conn->query( $sql );
		} else {
			$ret = $conn->query( $sql, MYSQLI_USE_RESULT );
		}

		return $ret;
	}

	/**
	 * @param string $realServer
	 * @return bool|mysqli
	 * @throws DBConnectionError
	 */
	protected function mysqlConnect( $realServer ) {
		# Avoid suppressed fatal error, which is very hard to track down
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

		$mysqli = mysqli_init();

		$connFlags = 0;
		if ( $this->flags & self::DBO_SSL ) {
			$connFlags |= MYSQLI_CLIENT_SSL;
			$mysqli->ssl_set(
				$this->sslKeyPath,
				$this->sslCertPath,
				$this->sslCAFile,
				$this->sslCAPath,
				$this->sslCiphers
			);
		}
		if ( $this->flags & self::DBO_COMPRESS ) {
			$connFlags |= MYSQLI_CLIENT_COMPRESS;
		}
		if ( $this->flags & self::DBO_PERSISTENT ) {
			$realServer = 'p:' . $realServer;
		}

		if ( $this->utf8Mode ) {
			// Tell the server we're communicating with it in UTF-8.
			// This may engage various charset conversions.
			$mysqli->options( MYSQLI_SET_CHARSET_NAME, 'utf8' );
		} else {
			$mysqli->options( MYSQLI_SET_CHARSET_NAME, 'binary' );
		}
		$mysqli->options( MYSQLI_OPT_CONNECT_TIMEOUT, 3 );

		if ( $mysqli->real_connect( $realServer, $this->user,
			$this->password, $this->dbName, $port, $socket, $connFlags )
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
		$conn = $this->getBindingHandle();

		if ( method_exists( $conn, 'set_charset' ) ) {
			return $conn->set_charset( $charset );
		} else {
			return $this->query( 'SET NAMES ' . $charset, __METHOD__ );
		}
	}

	/**
	 * @return bool
	 */
	protected function closeConnection() {
		$conn = $this->getBindingHandle();

		return $conn->close();
	}

	/**
	 * @return int
	 */
	function insertId() {
		$conn = $this->getBindingHandle();

		return (int)$conn->insert_id;
	}

	/**
	 * @return int
	 */
	function lastErrno() {
		if ( $this->conn instanceof mysqli ) {
			return $this->conn->errno;
		} else {
			return mysqli_connect_errno();
		}
	}

	/**
	 * @return int
	 */
	protected function fetchAffectedRowCount() {
		$conn = $this->getBindingHandle();

		return $conn->affected_rows;
	}

	/**
	 * @param string $db
	 * @return bool
	 */
	function selectDB( $db ) {
		$conn = $this->getBindingHandle();

		$this->dbName = $db;

		return $conn->select_db( $db );
	}

	/**
	 * @param mysqli_result $res
	 * @return bool
	 */
	protected function mysqlFreeResult( $res ) {
		$res->free_result();

		return true;
	}

	/**
	 * @param mysqli_result $res
	 * @return stdClass|bool
	 */
	protected function mysqlFetchObject( $res ) {
		$object = $res->fetch_object();
		if ( $object === null ) {
			return false;
		}

		return $object;
	}

	/**
	 * @param mysqli_result $res
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
	 * @param mysqli_result $res
	 * @return mixed
	 */
	protected function mysqlNumRows( $res ) {
		return $res->num_rows;
	}

	/**
	 * @param mysqli_result $res
	 * @return mixed
	 */
	protected function mysqlNumFields( $res ) {
		return $res->field_count;
	}

	/**
	 * @param mysqli_result $res
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
	 * @param mysqli_result $res
	 * @param int $n
	 * @return mixed
	 */
	protected function mysqlFieldName( $res, $n ) {
		$field = $res->fetch_field_direct( $n );

		return $field->name;
	}

	/**
	 * @param mysqli_result $res
	 * @param int $n
	 * @return mixed
	 */
	protected function mysqlFieldType( $res, $n ) {
		$field = $res->fetch_field_direct( $n );

		return $field->type;
	}

	/**
	 * @param mysqli_result $res
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
		$conn = $this->getBindingHandle();

		return $conn->real_escape_string( (string)$s );
	}

	/**
	 * Give an id for the connection
	 *
	 * mysql driver used resource id, but mysqli objects cannot be cast to string.
	 * @return string
	 */
	public function __toString() {
		if ( $this->conn instanceof mysqli ) {
			return (string)$this->conn->thread_id;
		} else {
			// mConn might be false or something.
			return (string)$this->conn;
		}
	}

	/**
	 * @return mysqli
	 */
	protected function getBindingHandle() {
		return parent::getBindingHandle();
	}
}

class_alias( DatabaseMysqli::class, 'DatabaseMysqli' );
