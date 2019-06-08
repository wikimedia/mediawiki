<?php
/**
 * This is the MS SQL Server Native database abstraction layer.
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
 * @author Joel Penner <a-joelpe at microsoft dot com>
 * @author Chris Pucci <a-cpucci at microsoft dot com>
 * @author Ryan Biesemeyer <v-ryanbi at microsoft dot com>
 * @author Ryan Schmidt <skizzerz at gmail dot com>
 */

namespace Wikimedia\Rdbms;

use Wikimedia;
use Exception;
use stdClass;

/**
 * @ingroup Database
 */
class DatabaseMssql extends Database {
	/** @var int */
	protected $serverPort;
	/** @var bool */
	protected $useWindowsAuth = false;
	/** @var int|null */
	protected $lastInsertId = null;
	/** @var int|null */
	protected $lastAffectedRowCount = null;
	/** @var int */
	protected $subqueryId = 0;
	/** @var bool */
	protected $scrollableCursor = true;
	/** @var bool */
	protected $prepareStatements = true;
	/** @var stdClass[][]|null */
	protected $binaryColumnCache = null;
	/** @var stdClass[][]|null */
	protected $bitColumnCache = null;
	/** @var bool */
	protected $ignoreDupKeyErrors = false;
	/** @var string[] */
	protected $ignoreErrors = [];

	public function implicitGroupby() {
		return false;
	}

	public function implicitOrderby() {
		return false;
	}

	public function unionSupportsOrderAndLimit() {
		return false;
	}

	public function __construct( array $params ) {
		$this->serverPort = $params['port'];
		$this->useWindowsAuth = $params['UseWindowsAuth'];

		parent::__construct( $params );
	}

	protected function open( $server, $user, $password, $dbName, $schema, $tablePrefix ) {
		# Test for driver support, to avoid suppressed fatal error
		if ( !function_exists( 'sqlsrv_connect' ) ) {
			throw new DBConnectionError(
				$this,
				"Microsoft SQL Server Native (sqlsrv) functions missing.
				You can download the driver from: http://go.microsoft.com/fwlink/?LinkId=123470\n"
			);
		}

		# e.g. the class is being loaded
		if ( !strlen( $user ) ) {
			return null;
		}

		$this->close();
		$this->server = $server;
		$this->user = $user;
		$this->password = $password;

		$connectionInfo = [];

		if ( $dbName != '' ) {
			$connectionInfo['Database'] = $dbName;
		}

		// Decide which auth scenerio to use
		// if we are using Windows auth, then don't add credentials to $connectionInfo
		if ( !$this->useWindowsAuth ) {
			$connectionInfo['UID'] = $user;
			$connectionInfo['PWD'] = $password;
		}

		Wikimedia\suppressWarnings();
		$this->conn = sqlsrv_connect( $server, $connectionInfo );
		Wikimedia\restoreWarnings();

		if ( $this->conn === false ) {
			throw new DBConnectionError( $this, $this->lastError() );
		}

		$this->opened = true;
		$this->currentDomain = new DatabaseDomain(
			( $dbName != '' ) ? $dbName : null,
			null,
			$tablePrefix
		);

		return (bool)$this->conn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 * @return bool
	 */
	protected function closeConnection() {
		return sqlsrv_close( $this->conn );
	}

	/**
	 * @param bool|MssqlResultWrapper|resource $result
	 * @return bool|MssqlResultWrapper
	 */
	protected function resultObject( $result ) {
		if ( !$result ) {
			return false;
		} elseif ( $result instanceof MssqlResultWrapper ) {
			return $result;
		} elseif ( $result === true ) {
			// Successful write query
			return $result;
		} else {
			return new MssqlResultWrapper( $this, $result );
		}
	}

	/**
	 * @param string $sql
	 * @return bool|MssqlResultWrapper|resource
	 */
	protected function doQuery( $sql ) {
		// several extensions seem to think that all databases support limits
		// via LIMIT N after the WHERE clause, but  MSSQL uses SELECT TOP N,
		// so to catch any of those extensions we'll do a quick check for a
		// LIMIT clause and pass $sql through $this->LimitToTopN() which parses
		// the LIMIT clause and passes the result to $this->limitResult();
		if ( preg_match( '/\bLIMIT\s*/i', $sql ) ) {
			// massage LIMIT -> TopN
			$sql = $this->LimitToTopN( $sql );
		}

		// MSSQL doesn't have EXTRACT(epoch FROM XXX)
		if ( preg_match( '#\bEXTRACT\s*?\(\s*?EPOCH\s+FROM\b#i', $sql, $matches ) ) {
			// This is same as UNIX_TIMESTAMP, we need to calc # of seconds from 1970
			$sql = str_replace( $matches[0], "DATEDIFF(s,CONVERT(datetime,'1/1/1970'),", $sql );
		}

		// perform query

		// SQLSRV_CURSOR_STATIC is slower than SQLSRV_CURSOR_CLIENT_BUFFERED (one of the two is
		// needed if we want to be able to seek around the result set), however CLIENT_BUFFERED
		// has a bug in the sqlsrv driver where wchar_t types (such as nvarchar) that are empty
		// strings make php throw a fatal error "Severe error translating Unicode"
		if ( $this->scrollableCursor ) {
			$scrollArr = [ 'Scrollable' => SQLSRV_CURSOR_STATIC ];
		} else {
			$scrollArr = [];
		}

		if ( $this->prepareStatements ) {
			// we do prepare + execute so we can get its field metadata for later usage if desired
			$stmt = sqlsrv_prepare( $this->conn, $sql, [], $scrollArr );
			$success = sqlsrv_execute( $stmt );
		} else {
			$stmt = sqlsrv_query( $this->conn, $sql, [], $scrollArr );
			$success = (bool)$stmt;
		}

		// Make a copy to ensure what we add below does not get reflected in future queries
		$ignoreErrors = $this->ignoreErrors;

		if ( $this->ignoreDupKeyErrors ) {
			// ignore duplicate key errors
			// this emulates INSERT IGNORE in MySQL
			$ignoreErrors[] = '2601'; // duplicate key error caused by unique index
			$ignoreErrors[] = '2627'; // duplicate key error caused by primary key
			$ignoreErrors[] = '3621'; // generic "the statement has been terminated" error
		}

		if ( $success === false ) {
			$errors = sqlsrv_errors();
			$success = true;

			foreach ( $errors as $err ) {
				if ( !in_array( $err['code'], $ignoreErrors ) ) {
					$success = false;
					break;
				}
			}

			if ( $success === false ) {
				return false;
			}
		}
		// remember number of rows affected
		$this->lastAffectedRowCount = sqlsrv_rows_affected( $stmt );

		return $stmt;
	}

	public function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		sqlsrv_free_stmt( $res );
	}

	/**
	 * @param IResultWrapper $res
	 * @return stdClass
	 */
	public function fetchObject( $res ) {
		// $res is expected to be an instance of MssqlResultWrapper here
		return $res->fetchObject();
	}

	/**
	 * @param IResultWrapper $res
	 * @return array
	 */
	public function fetchRow( $res ) {
		return $res->fetchRow();
	}

	/**
	 * @param mixed $res
	 * @return int
	 */
	public function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		$ret = sqlsrv_num_rows( $res );

		if ( $ret === false ) {
			// we cannot get an amount of rows from this cursor type
			// has_rows returns bool true/false if the result has rows
			$ret = (int)sqlsrv_has_rows( $res );
		}

		return $ret;
	}

	/**
	 * @param mixed $res
	 * @return int
	 */
	public function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return sqlsrv_num_fields( $res );
	}

	/**
	 * @param mixed $res
	 * @param int $n
	 * @return int
	 */
	public function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return sqlsrv_field_metadata( $res )[$n]['Name'];
	}

	/**
	 * This must be called after nextSequenceVal
	 * @return int|null
	 */
	public function insertId() {
		return $this->lastInsertId;
	}

	/**
	 * @param MssqlResultWrapper $res
	 * @param int $row
	 * @return bool
	 */
	public function dataSeek( $res, $row ) {
		return $res->seek( $row );
	}

	/**
	 * @return string
	 */
	public function lastError() {
		$strRet = '';
		$retErrors = sqlsrv_errors( SQLSRV_ERR_ALL );
		if ( $retErrors != null ) {
			foreach ( $retErrors as $arrError ) {
				$strRet .= $this->formatError( $arrError ) . "\n";
			}
		} else {
			$strRet = "No errors found";
		}

		return $strRet;
	}

	/**
	 * @param array $err
	 * @return string
	 */
	private function formatError( $err ) {
		return '[SQLSTATE ' .
			$err['SQLSTATE'] . '][Error Code ' . $err['code'] . ']' . $err['message'];
	}

	/**
	 * @return string|int
	 */
	public function lastErrno() {
		$err = sqlsrv_errors( SQLSRV_ERR_ALL );
		if ( $err !== null && isset( $err[0] ) ) {
			return $err[0]['code'];
		} else {
			return 0;
		}
	}

	protected function wasKnownStatementRollbackError() {
		$errors = sqlsrv_errors( SQLSRV_ERR_ALL );
		if ( !$errors ) {
			return false;
		}
		// The transaction vs statement rollback behavior depends on XACT_ABORT, so make sure
		// that the "statement has been terminated" error (3621) is specifically present.
		// https://docs.microsoft.com/en-us/sql/t-sql/statements/set-xact-abort-transact-sql
		$statementOnly = false;
		$codeWhitelist = [ '2601', '2627', '547' ];
		foreach ( $errors as $error ) {
			if ( $error['code'] == '3621' ) {
				$statementOnly = true;
			} elseif ( !in_array( $error['code'], $codeWhitelist ) ) {
				$statementOnly = false;
				break;
			}
		}

		return $statementOnly;
	}

	/**
	 * @return int
	 */
	protected function fetchAffectedRowCount() {
		return $this->lastAffectedRowCount;
	}

	/**
	 * SELECT wrapper
	 *
	 * @param mixed $table Array or string, table name(s) (prefix auto-added)
	 * @param mixed $vars Array or string, field name(s) to be retrieved
	 * @param mixed $conds Array or string, condition(s) for WHERE
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param array $options Associative array of options (e.g.
	 *   [ 'GROUP BY' => 'page_title' ]), see Database::makeSelectOptions
	 *   code for list of supported stuff
	 * @param array $join_conds Associative array of table join conditions
	 *   (optional) (e.g. [ 'page' => [ 'LEFT JOIN','page_latest=rev_id' ] ]
	 * @return mixed Database result resource (feed to Database::fetchObject
	 *   or whatever), or false on failure
	 * @throws DBQueryError
	 * @throws DBUnexpectedError
	 * @throws Exception
	 */
	public function select( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
		if ( isset( $options['EXPLAIN'] ) ) {
			try {
				$this->scrollableCursor = false;
				$this->prepareStatements = false;
				$this->query( "SET SHOWPLAN_ALL ON" );
				$ret = $this->query( $sql, $fname );
				$this->query( "SET SHOWPLAN_ALL OFF" );
			} catch ( DBQueryError $dqe ) {
				if ( isset( $options['FOR COUNT'] ) ) {
					// likely don't have privs for SHOWPLAN, so run a select count instead
					$this->query( "SET SHOWPLAN_ALL OFF" );
					unset( $options['EXPLAIN'] );
					$ret = $this->select(
						$table,
						'COUNT(*) AS EstimateRows',
						$conds,
						$fname,
						$options,
						$join_conds
					);
				} else {
					// someone actually wanted the query plan instead of an est row count
					// let them know of the error
					$this->scrollableCursor = true;
					$this->prepareStatements = true;
					throw $dqe;
				}
			}
			$this->scrollableCursor = true;
			$this->prepareStatements = true;
			return $ret;
		}
		return $this->query( $sql, $fname );
	}

	/**
	 * SELECT wrapper
	 *
	 * @param mixed $table Array or string, table name(s) (prefix auto-added)
	 * @param mixed $vars Array or string, field name(s) to be retrieved
	 * @param mixed $conds Array or string, condition(s) for WHERE
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param array $options Associative array of options (e.g. [ 'GROUP BY' => 'page_title' ]),
	 *   see Database::makeSelectOptions code for list of supported stuff
	 * @param array $join_conds Associative array of table join conditions (optional)
	 *    (e.g. [ 'page' => [ 'LEFT JOIN','page_latest=rev_id' ] ]
	 * @return string The SQL text
	 */
	public function selectSQLText( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		if ( isset( $options['EXPLAIN'] ) ) {
			unset( $options['EXPLAIN'] );
		}

		$sql = parent::selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		// try to rewrite aggregations of bit columns (currently MAX and MIN)
		if ( strpos( $sql, 'MAX(' ) !== false || strpos( $sql, 'MIN(' ) !== false ) {
			$bitColumns = [];
			if ( is_array( $table ) ) {
				$tables = $table;
				while ( $tables ) {
					$t = array_pop( $tables );
					if ( is_array( $t ) ) {
						$tables = array_merge( $tables, $t );
					} else {
						$bitColumns += $this->getBitColumns( $this->tableName( $t ) );
					}
				}
			} else {
				$bitColumns = $this->getBitColumns( $this->tableName( $table ) );
			}

			foreach ( $bitColumns as $col => $info ) {
				$replace = [
					"MAX({$col})" => "MAX(CAST({$col} AS tinyint))",
					"MIN({$col})" => "MIN(CAST({$col} AS tinyint))",
				];
				$sql = str_replace( array_keys( $replace ), array_values( $replace ), $sql );
			}
		}

		return $sql;
	}

	public function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds,
		$fname = __METHOD__
	) {
		$this->scrollableCursor = false;
		try {
			parent::deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname );
		} catch ( Exception $e ) {
			$this->scrollableCursor = true;
			throw $e;
		}
		$this->scrollableCursor = true;
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		$this->scrollableCursor = false;
		try {
			parent::delete( $table, $conds, $fname );
		} catch ( Exception $e ) {
			$this->scrollableCursor = true;
			throw $e;
		}
		$this->scrollableCursor = true;

		return true;
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on SHOWPLAN_ALL output
	 * This is not necessarily an accurate estimate, so use sparingly
	 * Returns -1 if count cannot be found
	 * Takes same arguments as Database::select()
	 * @param string $table
	 * @param string $var
	 * @param string $conds
	 * @param string $fname
	 * @param array $options
	 * @param array $join_conds
	 * @return int
	 */
	public function estimateRowCount( $table, $var = '*', $conds = '',
		$fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->normalizeConditions( $conds, $fname );
		$column = $this->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		// http://msdn2.microsoft.com/en-us/library/aa259203.aspx
		$options['EXPLAIN'] = true;
		$options['FOR COUNT'] = true;
		$res = $this->select( $table, $var, $conds, $fname, $options, $join_conds );

		$rows = -1;
		if ( $res ) {
			$row = $this->fetchRow( $res );

			if ( isset( $row['EstimateRows'] ) ) {
				$rows = (int)$row['EstimateRows'];
			}
		}

		return $rows;
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return array|bool|null
	 */
	public function indexInfo( $table, $index, $fname = __METHOD__ ) {
		# This does not return the same info as MYSQL would, but that's OK
		# because MediaWiki never uses the returned value except to check for
		# the existence of indexes.
		$sql = "sp_helpindex '" . $this->tableName( $table ) . "'";
		$res = $this->query( $sql, $fname );

		if ( !$res ) {
			return null;
		}

		$result = [];
		foreach ( $res as $row ) {
			if ( $row->index_name == $index ) {
				$row->Non_unique = !stristr( $row->index_description, "unique" );
				$cols = explode( ", ", $row->index_keys );
				foreach ( $cols as $col ) {
					$row->Column_name = trim( $col );
					$result[] = clone $row;
				}
			} elseif ( $index == 'PRIMARY' && stristr( $row->index_description, 'PRIMARY' ) ) {
				$row->Non_unique = 0;
				$cols = explode( ", ", $row->index_keys );
				foreach ( $cols as $col ) {
					$row->Column_name = trim( $col );
					$result[] = clone $row;
				}
			}
		}

		return $result ?: false;
	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $arrToInsert may be a single associative array, or an array of these with numeric keys, for
	 * multi-row insert.
	 *
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 * @param string $table
	 * @param array $arrToInsert
	 * @param string $fname
	 * @param array $options
	 * @return bool
	 * @throws Exception
	 */
	public function insert( $table, $arrToInsert, $fname = __METHOD__, $options = [] ) {
		# No rows to insert, easy just return now
		if ( !count( $arrToInsert ) ) {
			return true;
		}

		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		$table = $this->tableName( $table );

		if ( !( isset( $arrToInsert[0] ) && is_array( $arrToInsert[0] ) ) ) { // Not multi row
			$arrToInsert = [ 0 => $arrToInsert ]; // make everything multi row compatible
		}

		// We know the table we're inserting into, get its identity column
		$identity = null;
		// strip matching square brackets and the db/schema from table name
		$tableRawArr = explode( '.', preg_replace( '#\[([^\]]*)\]#', '$1', $table ) );
		$tableRaw = array_pop( $tableRawArr );
		$res = $this->doQuery(
			"SELECT NAME AS idColumn FROM SYS.IDENTITY_COLUMNS " .
				"WHERE OBJECT_NAME(OBJECT_ID)='{$tableRaw}'"
		);
		if ( $res && sqlsrv_has_rows( $res ) ) {
			// There is an identity for this table.
			$identityArr = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC );
			$identity = array_pop( $identityArr );
		}
		sqlsrv_free_stmt( $res );

		// Determine binary/varbinary fields so we can encode data as a hex string like 0xABCDEF
		$binaryColumns = $this->getBinaryColumns( $table );

		// INSERT IGNORE is not supported by SQL Server
		// remove IGNORE from options list and set ignore flag to true
		if ( in_array( 'IGNORE', $options ) ) {
			$options = array_diff( $options, [ 'IGNORE' ] );
			$this->ignoreDupKeyErrors = true;
		}

		$ret = null;
		foreach ( $arrToInsert as $a ) {
			// start out with empty identity column, this is so we can return
			// it as a result of the INSERT logic
			$sqlPre = '';
			$sqlPost = '';
			$identityClause = '';

			// if we have an identity column
			if ( $identity ) {
				// iterate through
				foreach ( $a as $k => $v ) {
					if ( $k == $identity ) {
						if ( !is_null( $v ) ) {
							// there is a value being passed to us,
							// we need to turn on and off inserted identity
							$sqlPre = "SET IDENTITY_INSERT $table ON;";
							$sqlPost = ";SET IDENTITY_INSERT $table OFF;";
						} else {
							// we can't insert NULL into an identity column,
							// so remove the column from the insert.
							unset( $a[$k] );
						}
					}
				}

				// we want to output an identity column as result
				$identityClause = "OUTPUT INSERTED.$identity ";
			}

			$keys = array_keys( $a );

			// Build the actual query
			$sql = $sqlPre . 'INSERT ' . implode( ' ', $options ) .
				" INTO $table (" . implode( ',', $keys ) . ") $identityClause VALUES (";

			$first = true;
			foreach ( $a as $key => $value ) {
				if ( isset( $binaryColumns[$key] ) ) {
					$value = new MssqlBlob( $value );
				}
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ',';
				}
				if ( is_null( $value ) ) {
					$sql .= 'null';
				} else {
					$sql .= $this->addQuotes( $value );
				}
			}
			$sql .= ')' . $sqlPost;

			// Run the query
			$this->scrollableCursor = false;
			try {
				$ret = $this->query( $sql );
			} catch ( Exception $e ) {
				$this->scrollableCursor = true;
				$this->ignoreDupKeyErrors = false;
				throw $e;
			}
			$this->scrollableCursor = true;

			if ( $ret instanceof ResultWrapper && !is_null( $identity ) ) {
				// Then we want to get the identity column value we were assigned and save it off
				$row = $ret->fetchObject();
				if ( is_object( $row ) ) {
					$this->lastInsertId = $row->$identity;
					// It seems that mAffectedRows is -1 sometimes when OUTPUT INSERTED.identity is
					// used if we got an identity back, we know for sure a row was affected, so
					// adjust that here
					if ( $this->lastAffectedRowCount == -1 ) {
						$this->lastAffectedRowCount = 1;
					}
				}
			}
		}

		$this->ignoreDupKeyErrors = false;

		return true;
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form [ 'dest1' => 'source1', ... ]
	 * Source items may be literals rather than field names, but strings should
	 * be quoted with Database::addQuotes().
	 * @param string $destTable
	 * @param array|string $srcTable May be an array of tables.
	 * @param array $varMap
	 * @param array $conds May be "*" to copy the whole table.
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @param array $selectJoinConds
	 * @throws Exception
	 */
	protected function nativeInsertSelect( $destTable, $srcTable, $varMap, $conds, $fname = __METHOD__,
		$insertOptions = [], $selectOptions = [], $selectJoinConds = []
	) {
		$this->scrollableCursor = false;
		try {
			parent::nativeInsertSelect(
				$destTable,
				$srcTable,
				$varMap,
				$conds,
				$fname,
				$insertOptions,
				$selectOptions,
				$selectJoinConds
			);
		} catch ( Exception $e ) {
			$this->scrollableCursor = true;
			throw $e;
		}
		$this->scrollableCursor = true;
	}

	/**
	 * UPDATE wrapper. Takes a condition array and a SET array.
	 *
	 * @param string $table Name of the table to UPDATE. This will be passed through
	 *                Database::tableName().
	 *
	 * @param array $values An array of values to SET. For each array element,
	 *                the key gives the field name, and the value gives the data
	 *                to set that field to. The data will be quoted by
	 *                Database::addQuotes().
	 *
	 * @param array $conds An array of conditions (WHERE). See
	 *                Database::select() for the details of the format of
	 *                condition arrays. Use '*' to update all rows.
	 *
	 * @param string $fname The function name of the caller (from __METHOD__),
	 *                for logging and profiling.
	 *
	 * @param array $options An array of UPDATE options, can be:
	 *                   - IGNORE: Ignore unique key conflicts
	 *                   - LOW_PRIORITY: MySQL-specific, see MySQL manual.
	 * @return bool
	 * @throws DBUnexpectedError
	 * @throws Exception
	 */
	function update( $table, $values, $conds, $fname = __METHOD__, $options = [] ) {
		$table = $this->tableName( $table );
		$binaryColumns = $this->getBinaryColumns( $table );

		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, LIST_SET, $binaryColumns );

		if ( $conds !== [] && $conds !== '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND, $binaryColumns );
		}

		$this->scrollableCursor = false;
		try {
			$this->query( $sql );
		} catch ( Exception $e ) {
			$this->scrollableCursor = true;
			throw $e;
		}
		$this->scrollableCursor = true;
		return true;
	}

	/**
	 * Makes an encoded list of strings from an array
	 * @param array $a Containing the data
	 * @param int $mode Constant
	 *      - LIST_COMMA:          comma separated, no field names
	 *      - LIST_AND:            ANDed WHERE clause (without the WHERE). See
	 *        the documentation for $conds in Database::select().
	 *      - LIST_OR:             ORed WHERE clause (without the WHERE)
	 *      - LIST_SET:            comma separated with field names, like a SET clause
	 *      - LIST_NAMES:          comma separated field names
	 * @param array $binaryColumns Contains a list of column names that are binary types
	 *      This is a custom parameter only present for MS SQL.
	 *
	 * @throws DBUnexpectedError
	 * @return string
	 */
	public function makeList( $a, $mode = LIST_COMMA, $binaryColumns = [] ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this, __METHOD__ . ' called with incorrect parameters' );
		}

		if ( $mode != LIST_NAMES ) {
			// In MS SQL, values need to be specially encoded when they are
			// inserted into binary fields. Perform this necessary encoding
			// for the specified set of columns.
			foreach ( array_keys( $a ) as $field ) {
				if ( !isset( $binaryColumns[$field] ) ) {
					continue;
				}

				if ( is_array( $a[$field] ) ) {
					foreach ( $a[$field] as &$v ) {
						$v = new MssqlBlob( $v );
					}
					unset( $v );
				} else {
					$a[$field] = new MssqlBlob( $a[$field] );
				}
			}
		}

		return parent::makeList( $a, $mode );
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @return int Returns the size of a text field, or -1 for "unlimited"
	 */
	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SELECT CHARACTER_MAXIMUM_LENGTH,DATA_TYPE FROM INFORMATION_SCHEMA.Columns
			WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field'";
		$res = $this->query( $sql );
		$row = $this->fetchRow( $res );
		$size = -1;
		if ( strtolower( $row['DATA_TYPE'] ) != 'text' ) {
			$size = $row['CHARACTER_MAXIMUM_LENGTH'];
		}

		return $size;
	}

	/**
	 * Construct a LIMIT query with optional offset
	 * This is used for query pages
	 *
	 * @param string $sql SQL query we will append the limit too
	 * @param int $limit The SQL limit
	 * @param bool|int $offset The SQL offset (default false)
	 * @return array|string
	 * @throws DBUnexpectedError
	 */
	public function limitResult( $sql, $limit, $offset = false ) {
		if ( $offset === false || $offset == 0 ) {
			if ( strpos( $sql, "SELECT" ) === false ) {
				return "TOP {$limit} " . $sql;
			} else {
				return preg_replace( '/\bSELECT(\s+DISTINCT)?\b/Dsi',
					'SELECT$1 TOP ' . $limit, $sql, 1 );
			}
		} else {
			// This one is fun, we need to pull out the select list as well as any ORDER BY clause
			$select = $orderby = [];
			$s1 = preg_match( '#SELECT\s+(.+?)\s+FROM#Dis', $sql, $select );
			$s2 = preg_match( '#(ORDER BY\s+.+?)(\s*FOR XML .*)?$#Dis', $sql, $orderby );
			$postOrder = '';
			$first = $offset + 1;
			$last = $offset + $limit;
			$sub1 = 'sub_' . $this->subqueryId;
			$sub2 = 'sub_' . ( $this->subqueryId + 1 );
			$this->subqueryId += 2;
			if ( !$s1 ) {
				// wat
				throw new DBUnexpectedError( $this, "Attempting to LIMIT a non-SELECT query\n" );
			}
			if ( !$s2 ) {
				// no ORDER BY
				$overOrder = 'ORDER BY (SELECT 1)';
			} else {
				if ( !isset( $orderby[2] ) || !$orderby[2] ) {
					// don't need to strip it out if we're using a FOR XML clause
					$sql = str_replace( $orderby[1], '', $sql );
				}
				$overOrder = $orderby[1];
				$postOrder = ' ' . $overOrder;
			}
			$sql = "SELECT {$select[1]}
					FROM (
						SELECT ROW_NUMBER() OVER({$overOrder}) AS rowNumber, *
						FROM ({$sql}) {$sub1}
					) {$sub2}
					WHERE rowNumber BETWEEN {$first} AND {$last}{$postOrder}";

			return $sql;
		}
	}

	/**
	 * If there is a limit clause, parse it, strip it, and pass the remaining
	 * SQL through limitResult() with the appropriate parameters. Not the
	 * prettiest solution, but better than building a whole new parser. This
	 * exists becase there are still too many extensions that don't use dynamic
	 * sql generation.
	 *
	 * @param string $sql
	 * @return array|mixed|string
	 */
	public function LimitToTopN( $sql ) {
		// Matches: LIMIT {[offset,] row_count | row_count OFFSET offset}
		$pattern = '/\bLIMIT\s+((([0-9]+)\s*,\s*)?([0-9]+)(\s+OFFSET\s+([0-9]+))?)/i';
		if ( preg_match( $pattern, $sql, $matches ) ) {
			$row_count = $matches[4];
			$offset = $matches[3] ?: $matches[6] ?: false;

			// strip the matching LIMIT clause out
			$sql = str_replace( $matches[0], '', $sql );

			return $this->limitResult( $sql, $row_count, $offset );
		}

		return $sql;
	}

	/**
	 * @return string Wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink() {
		return "[{{int:version-db-mssql-url}} MS SQL Server]";
	}

	/**
	 * @return string Version information from the database
	 */
	public function getServerVersion() {
		$server_info = sqlsrv_server_info( $this->conn );
		$version = $server_info['SQLServerVersion'] ?? 'Error';

		return $version;
	}

	/**
	 * @param string $table
	 * @param string $fname
	 * @return bool
	 */
	public function tableExists( $table, $fname = __METHOD__ ) {
		list( $db, $schema, $table ) = $this->tableName( $table, 'split' );

		if ( $db !== false ) {
			// remote database
			$this->queryLogger->error( "Attempting to call tableExists on a remote table" );
			return false;
		}

		if ( $schema === false ) {
			$schema = $this->dbSchema();
		}

		$res = $this->query( "SELECT 1 FROM INFORMATION_SCHEMA.TABLES
			WHERE TABLE_TYPE = 'BASE TABLE'
			AND TABLE_SCHEMA = '$schema' AND TABLE_NAME = '$table'" );

		if ( $res->numRows() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Query whether a given column exists in the mediawiki schema
	 * @param string $table
	 * @param string $field
	 * @param string $fname
	 * @return bool
	 */
	public function fieldExists( $table, $field, $fname = __METHOD__ ) {
		list( $db, $schema, $table ) = $this->tableName( $table, 'split' );

		if ( $db !== false ) {
			// remote database
			$this->queryLogger->error( "Attempting to call fieldExists on a remote table" );
			return false;
		}

		$res = $this->query( "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_SCHEMA = '$schema' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$field'" );

		if ( $res->numRows() ) {
			return true;
		} else {
			return false;
		}
	}

	public function fieldInfo( $table, $field ) {
		list( $db, $schema, $table ) = $this->tableName( $table, 'split' );

		if ( $db !== false ) {
			// remote database
			$this->queryLogger->error( "Attempting to call fieldInfo on a remote table" );
			return false;
		}

		$res = $this->query( "SELECT * FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_SCHEMA = '$schema' AND TABLE_NAME = '$table' AND COLUMN_NAME = '$field'" );

		$meta = $res->fetchRow();
		if ( $meta ) {
			return new MssqlField( $meta );
		}

		return false;
	}

	protected function doSavepoint( $identifier, $fname ) {
		$this->query( 'SAVE TRANSACTION ' . $this->addIdentifierQuotes( $identifier ), $fname );
	}

	protected function doReleaseSavepoint( $identifier, $fname ) {
		// Not supported. Also not really needed, a new doSavepoint() for the
		// same identifier will overwrite the old.
	}

	protected function doRollbackToSavepoint( $identifier, $fname ) {
		$this->query( 'ROLLBACK TRANSACTION ' . $this->addIdentifierQuotes( $identifier ), $fname );
	}

	/**
	 * Begin a transaction, committing any previously open transaction
	 * @param string $fname
	 */
	protected function doBegin( $fname = __METHOD__ ) {
		sqlsrv_begin_transaction( $this->conn );
		$this->trxLevel = 1;
	}

	/**
	 * End a transaction
	 * @param string $fname
	 */
	protected function doCommit( $fname = __METHOD__ ) {
		sqlsrv_commit( $this->conn );
		$this->trxLevel = 0;
	}

	/**
	 * Rollback a transaction.
	 * No-op on non-transactional databases.
	 * @param string $fname
	 */
	protected function doRollback( $fname = __METHOD__ ) {
		sqlsrv_rollback( $this->conn );
		$this->trxLevel = 0;
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public function strencode( $s ) {
		// Should not be called by us
		return str_replace( "'", "''", $s );
	}

	/**
	 * @param string|int|null|bool|Blob $s
	 * @return string|int
	 */
	public function addQuotes( $s ) {
		if ( $s instanceof MssqlBlob ) {
			return $s->fetch();
		} elseif ( $s instanceof Blob ) {
			// this shouldn't really ever be called, but it's here if needed
			// (and will quite possibly make the SQL error out)
			$blob = new MssqlBlob( $s->fetch() );
			return $blob->fetch();
		} else {
			if ( is_bool( $s ) ) {
				$s = $s ? 1 : 0;
			}
			return parent::addQuotes( $s );
		}
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public function addIdentifierQuotes( $s ) {
		// http://msdn.microsoft.com/en-us/library/aa223962.aspx
		return '[' . $s . ']';
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function isQuotedIdentifier( $name ) {
		return strlen( $name ) && $name[0] == '[' && substr( $name, -1, 1 ) == ']';
	}

	/**
	 * MS SQL supports more pattern operators than other databases (ex: [,],^)
	 *
	 * @param string $s
	 * @param string $escapeChar
	 * @return string
	 */
	protected function escapeLikeInternal( $s, $escapeChar = '`' ) {
		return str_replace( [ $escapeChar, '%', '_', '[', ']', '^' ],
			[ "{$escapeChar}{$escapeChar}", "{$escapeChar}%", "{$escapeChar}_",
				"{$escapeChar}[", "{$escapeChar}]", "{$escapeChar}^" ],
			$s );
	}

	protected function doSelectDomain( DatabaseDomain $domain ) {
		if ( $domain->getSchema() !== null ) {
			throw new DBExpectedError( $this, __CLASS__ . ": domain schemas are not supported." );
		}

		$database = $domain->getDatabase();
		if ( $database !== $this->getDBname() ) {
			$encDatabase = $this->addIdentifierQuotes( $database );
			$res = $this->doQuery( "USE $encDatabase" );
			if ( !$res ) {
				throw new DBExpectedError( $this, "Could not select database '$database'." );
			}
		}
		// Update that domain fields on success (no exception thrown)
		$this->currentDomain = $domain;

		return true;
	}

	/**
	 * @param array $options An associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	public function makeSelectOptions( $options ) {
		$tailOpts = '';
		$startOpts = '';

		$noKeyOptions = [];
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$tailOpts .= $this->makeGroupByWithHaving( $options );

		$tailOpts .= $this->makeOrderBy( $options );

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		if ( isset( $noKeyOptions['FOR XML'] ) ) {
			// used in group concat field emulation
			$tailOpts .= " FOR XML PATH('')";
		}

		// we want this to be compatible with the output of parent::makeSelectOptions()
		return [ $startOpts, '', $tailOpts, '', '' ];
	}

	public function getType() {
		return 'mssql';
	}

	/**
	 * @param array $stringList
	 * @return string
	 */
	public function buildConcat( $stringList ) {
		return implode( ' + ', $stringList );
	}

	/**
	 * Build a GROUP_CONCAT or equivalent statement for a query.
	 * MS SQL doesn't have GROUP_CONCAT so we emulate it with other stuff (and boy is it nasty)
	 *
	 * This is useful for combining a field for several rows into a single string.
	 * NULL values will not appear in the output, duplicated values will appear,
	 * and the resulting delimiter-separated values have no defined sort order.
	 * Code using the results may need to use the PHP unique() or sort() methods.
	 *
	 * @param string $delim Glue to bind the results together
	 * @param string|array $table Table name
	 * @param string $field Field name
	 * @param string|array $conds Conditions
	 * @param string|array $join_conds Join conditions
	 * @return string SQL text
	 * @since 1.23
	 */
	public function buildGroupConcatField( $delim, $table, $field, $conds = '',
		$join_conds = []
	) {
		$gcsq = 'gcsq_' . $this->subqueryId;
		$this->subqueryId++;

		$delimLen = strlen( $delim );
		$fld = "{$field} + {$this->addQuotes( $delim )}";
		$sql = "(SELECT LEFT({$field}, LEN({$field}) - {$delimLen}) FROM ("
			. $this->selectSQLText( $table, $fld, $conds, null, [ 'FOR XML' ], $join_conds )
			. ") {$gcsq} ({$field}))";

		return $sql;
	}

	public function buildSubstring( $input, $startPosition, $length = null ) {
		$this->assertBuildSubstringParams( $startPosition, $length );
		if ( $length === null ) {
			/**
			 * MSSQL doesn't allow an empty length parameter, so when we don't want to limit the
			 * length returned use the default maximum size of text.
			 * @see https://docs.microsoft.com/en-us/sql/t-sql/statements/set-textsize-transact-sql
			 */
			$length = 2147483647;
		}
		return 'SUBSTRING(' . implode( ',', [ $input, $startPosition, $length ] ) . ')';
	}

	/**
	 * Returns an associative array for fields that are of type varbinary, binary, or image
	 * $table can be either a raw table name or passed through tableName() first
	 * @param string $table
	 * @return array
	 */
	private function getBinaryColumns( $table ) {
		$tableRawArr = explode( '.', preg_replace( '#\[([^\]]*)\]#', '$1', $table ) );
		$tableRaw = array_pop( $tableRawArr );

		if ( $this->binaryColumnCache === null ) {
			$this->populateColumnCaches();
		}

		return $this->binaryColumnCache[$tableRaw] ?? [];
	}

	/**
	 * @param string $table
	 * @return array
	 */
	private function getBitColumns( $table ) {
		$tableRawArr = explode( '.', preg_replace( '#\[([^\]]*)\]#', '$1', $table ) );
		$tableRaw = array_pop( $tableRawArr );

		if ( $this->bitColumnCache === null ) {
			$this->populateColumnCaches();
		}

		return $this->bitColumnCache[$tableRaw] ?? [];
	}

	private function populateColumnCaches() {
		$res = $this->select( 'INFORMATION_SCHEMA.COLUMNS', '*',
			[
				'TABLE_CATALOG' => $this->getDBname(),
				'TABLE_SCHEMA' => $this->dbSchema(),
				'DATA_TYPE' => [ 'varbinary', 'binary', 'image', 'bit' ]
			] );

		$this->binaryColumnCache = [];
		$this->bitColumnCache = [];
		foreach ( $res as $row ) {
			if ( $row->DATA_TYPE == 'bit' ) {
				$this->bitColumnCache[$row->TABLE_NAME][$row->COLUMN_NAME] = $row;
			} else {
				$this->binaryColumnCache[$row->TABLE_NAME][$row->COLUMN_NAME] = $row;
			}
		}
	}

	/**
	 * @param string $name
	 * @param string $format One of "quoted" (default), "raw", or "split".
	 * @return string|array When the requested $format is "split", a list of database, schema, and
	 *  table name is returned. Database and schema can be `false`.
	 */
	function tableName( $name, $format = 'quoted' ) {
		# Replace reserved words with better ones
		switch ( $name ) {
			case 'user':
				return $this->realTableName( 'mwuser', $format );
			default:
				return $this->realTableName( $name, $format );
		}
	}

	/**
	 * call this instead of tableName() in the updater when renaming tables
	 * @param string $name
	 * @param string $format One of "quoted" (default), "raw", or "split".
	 * @return string|array When the requested $format is "split", a list of database, schema, and
	 *  table name is returned. Database and schema can be `false`.
	 * @private
	 */
	function realTableName( $name, $format = 'quoted' ) {
		$table = parent::tableName( $name, $format );
		if ( $format == 'split' ) {
			// Used internally, we want the schema split off from the table name and returned
			// as a list with 3 elements (database, schema, table)
			return array_pad( explode( '.', $table, 3 ), -3, false );
		}
		return $table;
	}

	/**
	 * Delete a table
	 * @param string $tableName
	 * @param string $fName
	 * @return bool|ResultWrapper
	 * @since 1.18
	 */
	public function dropTable( $tableName, $fName = __METHOD__ ) {
		if ( !$this->tableExists( $tableName, $fName ) ) {
			return false;
		}

		// parent function incorrectly appends CASCADE, which we don't want
		$sql = "DROP TABLE " . $this->tableName( $tableName );

		return $this->query( $sql, $fName );
	}

	/**
	 * Called in the installer and updater.
	 * Probably doesn't need to be called anywhere else in the codebase.
	 * @param bool|null $value
	 * @return bool|null
	 */
	public function prepareStatements( $value = null ) {
		$old = $this->prepareStatements;
		if ( $value !== null ) {
			$this->prepareStatements = $value;
		}

		return $old;
	}

	/**
	 * Called in the installer and updater.
	 * Probably doesn't need to be called anywhere else in the codebase.
	 * @param bool|null $value
	 * @return bool|null
	 */
	public function scrollableCursor( $value = null ) {
		$old = $this->scrollableCursor;
		if ( $value !== null ) {
			$this->scrollableCursor = $value;
		}

		return $old;
	}

	public function buildStringCast( $field ) {
		return "CAST( $field AS NVARCHAR )";
	}

	public static function getAttributes() {
		return [ self::ATTR_SCHEMAS_AS_TABLE_GROUPS => true ];
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabaseMssql::class, 'DatabaseMssql' );
