<?php
/**
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
 */
namespace Wikimedia\Rdbms;

use mysqli;
use mysqli_result;
use Wikimedia\AtEase\AtEase;
use Wikimedia\IPUtils;

/**
 * Database abstraction object for PHP extension mysqli.
 *
 * TODO: This could probably be merged with DatabaseMysqlBase.
 * The split was created to support a transition from the old "mysql" extension
 * to mysqli, and there may be an argument for retaining it in order to support
 * some future transition to something else, but it's complexity and YAGNI.
 *
 * @ingroup Database
 * @since 1.22
 * @see Database
 */
class DatabaseMysqli extends DatabaseMysqlBase {
	protected function doSingleStatementQuery( string $sql ): QueryStatus {
		AtEase::suppressWarnings();
		$res = $this->getBindingHandle()->query( $sql );
		AtEase::restoreWarnings();

		return new QueryStatus(
			$res instanceof mysqli_result ? new MysqliResultWrapper( $this, $res ) : $res,
			$this->affectedRows(),
			$this->lastError(),
			$this->lastErrno()
		);
	}

	protected function doMultiStatementQuery( array $sqls ): array {
		$qsByStatementId = [];

		$conn = $this->getBindingHandle();
		// Clear any previously left over result
		while ( $conn->more_results() && $conn->next_result() ) {
			$mysqliResult = $conn->store_result();
			$mysqliResult->free();
		}

		$combinedSql = implode( ";\n", $sqls );
		$conn->multi_query( $combinedSql );

		reset( $sqls );
		$done = false;
		do {
			$mysqliResult = $conn->store_result();
			$statementId = key( $sqls );
			if ( $statementId !== null ) {
				// Database uses "true" for successful queries without result sets
				if ( $mysqliResult === false ) {
					$res = ( $conn->errno === 0 );
				} elseif ( $mysqliResult instanceof mysqli_result ) {
					$res = new MysqliResultWrapper( $this, $mysqliResult );
				} else {
					$res = $mysqliResult;
				}
				$qsByStatementId[$statementId] = new QueryStatus(
					$res,
					$conn->affected_rows,
					$conn->error,
					$conn->errno
				);
				next( $sqls );
			}
			if ( $conn->more_results() ) {
				$conn->next_result();
			} else {
				$done = true;
			}
		} while ( !$done );
		// Fill in status for statements aborted due to prior statement failure
		while ( ( $statementId = key( $sqls ) ) !== null ) {
			$qsByStatementId[$statementId] = new QueryStatus( false, 0, 'Query aborted', 0 );
			next( $sqls );
		}

		return $qsByStatementId;
	}

	/**
	 * @param string|null $server
	 * @param string|null $user
	 * @param string|null $password
	 * @param string|null $db
	 * @return mysqli|null
	 * @throws DBConnectionError
	 */
	protected function mysqlConnect( $server, $user, $password, $db ) {
		if ( !function_exists( 'mysqli_init' ) ) {
			throw $this->newExceptionAfterConnectError(
				"MySQLi functions missing, have you compiled PHP with the --with-mysqli option?"
			);
		}

		// PHP 8.1.0+ throws exceptions by default. Turn that off for consistency.
		mysqli_report( MYSQLI_REPORT_OFF );

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
		$hostAndPort = IPUtils::splitHostAndPort( $server );
		if ( $hostAndPort ) {
			$realServer = $hostAndPort[0];
			if ( $hostAndPort[1] ) {
				$port = $hostAndPort[1];
			}
		} elseif ( substr_count( $server, ':/' ) == 1 ) {
			// If we have a colon slash instead of a colon and a port number
			// after the ip or hostname, assume it's the Unix domain socket path
			list( $realServer, $socket ) = explode( ':', $server, 2 );
		} else {
			$realServer = $server;
		}

		$mysqli = mysqli_init();
		// Make affectedRows() for UPDATE reflect the number of matching rows, regardless
		// of whether any column values changed. This is what callers want to know and is
		// consistent with what Postgres and SQLite return.
		$flags = MYSQLI_CLIENT_FOUND_ROWS;
		if ( $this->ssl ) {
			$flags |= MYSQLI_CLIENT_SSL;
			$mysqli->ssl_set(
				$this->sslKeyPath,
				$this->sslCertPath,
				$this->sslCAFile,
				$this->sslCAPath,
				$this->sslCiphers
			);
		}
		if ( $this->getFlag( self::DBO_COMPRESS ) ) {
			$flags |= MYSQLI_CLIENT_COMPRESS;
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

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal socket seems set when used
		$ok = $mysqli->real_connect( $realServer, $user, $password, $db, $port, $socket, $flags );

		return $ok ? $mysqli : null;
	}

	protected function closeConnection() {
		$conn = $this->getBindingHandle();

		return $conn->close();
	}

	public function insertId() {
		$conn = $this->getBindingHandle();

		return (int)$conn->insert_id;
	}

	/**
	 * @return int
	 */
	public function lastErrno() {
		if ( $this->conn instanceof mysqli ) {
			return (int)$this->conn->errno;
		} else {
			return mysqli_connect_errno();
		}
	}

	protected function fetchAffectedRowCount() {
		$conn = $this->getBindingHandle();

		return $conn->affected_rows;
	}

	/**
	 * @param mysqli|null $conn Optional connection object
	 * @return string
	 */
	protected function mysqlError( $conn = null ) {
		if ( $conn === null ) {
			return (string)mysqli_connect_error();
		} else {
			return $conn->error;
		}
	}

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
