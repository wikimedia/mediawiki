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
use Wikimedia\AtEase\AtEase;

/**
 * Database abstraction object for PHP extension mysqli.
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 * @phan-file-suppress PhanParamSignatureMismatch resource vs mysqli_result
 */
class DatabaseMysqli extends DatabaseMysqlBase {
	/**
	 * @param string $sql
	 * @return mysqli_result|bool
	 */
	protected function doQuery( $sql ) {
		AtEase::suppressWarnings();
		$res = $this->getBindingHandle()->query( $sql );
		AtEase::restoreWarnings();

		return $res;
	}

	/**
	 * @param string $realServer
	 * @param string|null $dbName
	 * @return mysqli|null
	 * @throws DBConnectionError
	 */
	protected function mysqlConnect( $realServer, $dbName ) {
		if ( !function_exists( 'mysqli_init' ) ) {
			throw $this->newExceptionAfterConnectError(
				"MySQLi functions missing, have you compiled PHP with the --with-mysqli option?"
			);
		}

		// Other than mysql_connect, mysqli_real_connect expects an explicit port number
		// e.g. "localhost:1234" or "127.0.0.1:1234"
		// or Unix domain socket path
		// e.g. "localhost:/socket_path" or "localhost:/foo/bar:bar:bar"
		// colons are known to be used by Google AppEngine,
		// see <https://cloud.google.com/sql/docs/mysql/connect-app-engine>
		//
		// We need to parse the port or socket path out of $realServer
		$port = null;
		$socket = null;
		$hostAndPort = IP::splitHostAndPort( $realServer );
		if ( $hostAndPort ) {
			$realServer = $hostAndPort[0];
			if ( $hostAndPort[1] ) {
				$port = $hostAndPort[1];
			}
		} elseif ( substr_count( $realServer, ':/' ) == 1 ) {
			// If we have a colon slash instead of a colon and a port number
			// after the ip or hostname, assume it's the Unix domain socket path
			list( $realServer, $socket ) = explode( ':', $realServer, 2 );
		}

		$mysqli = mysqli_init();
		// Make affectedRows() for UPDATE reflect the number of matching rows, regardless
		// of whether any column values changed. This is what callers want to know and is
		// consistent with what Postgres, SQLite, and SQL Server return.
		$connFlags = MYSQLI_CLIENT_FOUND_ROWS;
		if ( $this->getFlag( self::DBO_SSL ) ) {
			$connFlags |= MYSQLI_CLIENT_SSL;
			$mysqli->ssl_set(
				$this->sslKeyPath,
				$this->sslCertPath,
				$this->sslCAFile,
				$this->sslCAPath,
				$this->sslCiphers
			);
		}
		if ( $this->getFlag( self::DBO_COMPRESS ) ) {
			$connFlags |= MYSQLI_CLIENT_COMPRESS;
		}
		if ( $this->getFlag( self::DBO_PERSISTENT ) ) {
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

		if ( $mysqli->real_connect(
			$realServer,
			$this->user,
			$this->password,
			$dbName,
			$port,
			$socket,
			$connFlags
		) ) {
			return $mysqli;
		}

		return null;
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
	 * @return array|false
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
	 * @param mysqli|null $conn Optional connection object
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
	 * @return mysqli
	 */
	protected function getBindingHandle() {
		return parent::getBindingHandle();
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabaseMysqli::class, 'DatabaseMysqli' );
