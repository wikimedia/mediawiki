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
	 * @param $sql string
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

	protected function mysqlConnect( $realServer ) {
		# Fail now
		# Otherwise we get a suppressed fatal error, which is very hard to track down
		if ( !function_exists( 'mysqli_init' ) ) {
			throw new DBConnectionError( $this, "MySQLi functions missing,"
				. " have you compiled PHP with the --with-mysqli option?\n" );
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
		$numAttempts = 2;

		for ( $i = 0; $i < $numAttempts; $i++ ) {
			if ( $i > 1 ) {
				usleep( 1000 );
			}
			if ( $mysqli->real_connect( $realServer, $this->mUser,
				$this->mPassword, $this->mDBname, null, null, $connFlags ) )
			{
				return $mysqli;
			}
		}

		return false;
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
	 * @param $db
	 * @return bool
	 */
	function selectDB( $db ) {
		$this->mDBname = $db;
		return $this->mConn->select_db( $db );
	}

	/**
	 * @return string
	 */
	function getServerVersion() {
		return $this->mConn->server_info;
	}

	protected function mysqlFreeResult( $res ) {
		$res->free_result();
		return true;
	}

	protected function mysqlFetchObject( $res ) {
		$object = $res->fetch_object();
		if ( $object === null ) {
			return false;
		}
		return $object;
	}

	protected function mysqlFetchArray( $res ) {
		$array = $res->fetch_array();
		if ( $array === null ) {
			return false;
		}
		return $array;
	}

	protected function mysqlNumRows( $res ) {
		return $res->num_rows;
	}

	protected function mysqlNumFields( $res ) {
		return $res->field_count;
	}

	protected function mysqlFetchField( $res, $n ) {
		$field = $res->fetch_field_direct( $n );
		$field->not_null = $field->flags & MYSQLI_NOT_NULL_FLAG;
		$field->primary_key = $field->flags & MYSQLI_PRI_KEY_FLAG;
		$field->unique_key = $field->flags & MYSQLI_UNIQUE_KEY_FLAG;
		$field->multiple_key = $field->flags & MYSQLI_MULTIPLE_KEY_FLAG;
		$field->binary = $field->flags & MYSQLI_BINARY_FLAG;
		return $field;
	}

	protected function mysqlFieldName( $res, $n ) {
		$field = $res->fetch_field_direct( $n );
		return $field->name;
	}

	protected function mysqlDataSeek( $res, $row ) {
		return $res->data_seek( $row );
	}

	protected function mysqlError( $conn = null ) {
		if ($conn === null) {
			return mysqli_connect_error();
		} else {
			return $conn->error;
		}
	}

	protected function mysqlRealEscapeString( $s ) {
		return $this->mConn->real_escape_string( $s );
	}

	protected function mysqlPing() {
		return $this->mConn->ping();
	}

}
