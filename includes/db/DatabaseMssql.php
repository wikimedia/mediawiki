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

/**
 * @ingroup Database
 */
class DatabaseMssql extends DatabaseBase {
	protected $mInsertId = null;
	protected $mLastResult = null;
	protected $mAffectedRows = null;
	protected $mSubqueryId = 0;
	protected $mScrollableCursor = true;
	protected $mPrepareStatements = true;
	protected $mBinaryColumnCache = null;
	protected $mBitColumnCache = null;
	protected $mIgnoreDupKeyErrors = false;

	protected $mPort;

	public function cascadingDeletes() {
		return true;
	}

	public function cleanupTriggers() {
		return false;
	}

	public function strictIPs() {
		return false;
	}

	public function realTimestamps() {
		return false;
	}

	public function implicitGroupby() {
		return false;
	}

	public function implicitOrderby() {
		return false;
	}

	public function functionalIndexes() {
		return true;
	}

	public function unionSupportsOrderAndLimit() {
		return false;
	}

	/**
	 * Usually aborts on failure
	 * @param string $server
	 * @param string $user
	 * @param string $password
	 * @param string $dbName
	 * @throws DBConnectionError
	 * @return bool|DatabaseBase|null
	 */
	public function open( $server, $user, $password, $dbName ) {
		# Test for driver support, to avoid suppressed fatal error
		if ( !function_exists( 'sqlsrv_connect' ) ) {
			throw new DBConnectionError(
				$this,
				"Microsoft SQL Server Native (sqlsrv) functions missing.
				You can download the driver from: http://go.microsoft.com/fwlink/?LinkId=123470\n"
			);
		}

		global $wgDBport, $wgDBWindowsAuthentication;

		# e.g. the class is being loaded
		if ( !strlen( $user ) ) {
			return null;
		}

		$this->close();
		$this->mServer = $server;
		$this->mPort = $wgDBport;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$connectionInfo = array();

		if ( $dbName ) {
			$connectionInfo['Database'] = $dbName;
		}

		// Decide which auth scenerio to use
		// if we are using Windows auth, don't add credentials to $connectionInfo
		if ( !$wgDBWindowsAuthentication ) {
			$connectionInfo['UID'] = $user;
			$connectionInfo['PWD'] = $password;
		}

		MediaWiki\suppressWarnings();
		$this->mConn = sqlsrv_connect( $server, $connectionInfo );
		MediaWiki\restoreWarnings();

		if ( $this->mConn === false ) {
			throw new DBConnectionError( $this, $this->lastError() );
		}

		$this->mOpened = true;

		return $this->mConn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 * @return bool
	 */
	protected function closeConnection() {
		return sqlsrv_close( $this->mConn );
	}

	/**
	 * @param bool|MssqlResultWrapper|resource $result
	 * @return bool|MssqlResultWrapper
	 */
	public function resultObject( $result ) {
		if ( empty( $result ) ) {
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
	 * @return bool|MssqlResult
	 * @throws DBUnexpectedError
	 */
	protected function doQuery( $sql ) {
		if ( $this->debug() ) {
			wfDebug( "SQL: [$sql]\n" );
		}
		$this->offset = 0;

		// several extensions seem to think that all databases support limits
		// via LIMIT N after the WHERE clause well, MSSQL uses SELECT TOP N,
		// so to catch any of those extensions we'll do a quick check for a
		// LIMIT clause and pass $sql through $this->LimitToTopN() which parses
		// the limit clause and passes the result to $this->limitResult();
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
		if ( $this->mScrollableCursor ) {
			$scrollArr = array( 'Scrollable' => SQLSRV_CURSOR_STATIC );
		} else {
			$scrollArr = array();
		}

		if ( $this->mPrepareStatements ) {
			// we do prepare + execute so we can get its field metadata for later usage if desired
			$stmt = sqlsrv_prepare( $this->mConn, $sql, array(), $scrollArr );
			$success = sqlsrv_execute( $stmt );
		} else {
			$stmt = sqlsrv_query( $this->mConn, $sql, array(), $scrollArr );
			$success = (bool)$stmt;
		}

		if ( $this->mIgnoreDupKeyErrors ) {
			// ignore duplicate key errors, but nothing else
			// this emulates INSERT IGNORE in MySQL
			if ( $success === false ) {
				$errors = sqlsrv_errors( SQLSRV_ERR_ERRORS );
				$success = true;

				foreach ( $errors as $err ) {
					if ( $err['SQLSTATE'] == '23000' && $err['code'] == '2601' ) {
						continue; // duplicate key error caused by unique index
					} elseif ( $err['SQLSTATE'] == '23000' && $err['code'] == '2627' ) {
						continue; // duplicate key error caused by primary key
					} elseif ( $err['SQLSTATE'] == '01000' && $err['code'] == '3621' ) {
						continue; // generic "the statement has been terminated" error
					}

					$success = false; // getting here means we got an error we weren't expecting
					break;
				}

				if ( $success ) {
					$this->mAffectedRows = 0;
					return $stmt;
				}
			}
		}

		if ( $success === false ) {
			return false;
		}
		// remember number of rows affected
		$this->mAffectedRows = sqlsrv_rows_affected( $stmt );

		return $stmt;
	}

	public function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		sqlsrv_free_stmt( $res );
	}

	/**
	 * @param MssqlResultWrapper $res
	 * @return stdClass
	 */
	public function fetchObject( $res ) {
		// $res is expected to be an instance of MssqlResultWrapper here
		return $res->fetchObject();
	}

	/**
	 * @param MssqlResultWrapper $res
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

		return sqlsrv_num_rows( $res );
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

		$metadata = sqlsrv_field_metadata( $res );
		return $metadata[$n]['Name'];
	}

	/**
	 * This must be called after nextSequenceVal
	 * @return int|null
	 */
	public function insertId() {
		return $this->mInsertId;
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
		return '[SQLSTATE ' . $err['SQLSTATE'] . '][Error Code ' . $err['code'] . ']' . $err['message'];
	}

	/**
	 * @return string
	 */
	public function lastErrno() {
		$err = sqlsrv_errors( SQLSRV_ERR_ALL );
		if ( $err !== null && isset( $err[0] ) ) {
			return $err[0]['code'];
		} else {
			return 0;
		}
	}

	/**
	 * @return int
	 */
	public function affectedRows() {
		return $this->mAffectedRows;
	}

	/**
	 * SELECT wrapper
	 *
	 * @param mixed $table Array or string, table name(s) (prefix auto-added)
	 * @param mixed $vars Array or string, field name(s) to be retrieved
	 * @param mixed $conds Array or string, condition(s) for WHERE
	 * @param string $fname Calling function name (use __METHOD__) for logs/profiling
	 * @param array $options Associative array of options (e.g.
	 *   array('GROUP BY' => 'page_title')), see Database::makeSelectOptions
	 *   code for list of supported stuff
	 * @param array $join_conds Associative array of table join conditions
	 *   (optional) (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return mixed Database result resource (feed to Database::fetchObject
	 *   or whatever), or false on failure
	 * @throws DBQueryError
	 * @throws DBUnexpectedError
	 * @throws Exception
	 */
	public function select( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = array(), $join_conds = array()
	) {
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
		if ( isset( $options['EXPLAIN'] ) ) {
			try {
				$this->mScrollableCursor = false;
				$this->mPrepareStatements = false;
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
					$this->mScrollableCursor = true;
					$this->mPrepareStatements = true;
					throw $dqe;
				}
			}
			$this->mScrollableCursor = true;
			$this->mPrepareStatements = true;
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
	 * @param array $options Associative array of options (e.g. array('GROUP BY' => 'page_title')),
	 *   see Database::makeSelectOptions code for list of supported stuff
	 * @param array $join_conds Associative array of table join conditions (optional)
	 *    (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return string The SQL text
	 */
	public function selectSQLText( $table, $vars, $conds = '', $fname = __METHOD__,
		$options = array(), $join_conds = array()
	) {
		if ( isset( $options['EXPLAIN'] ) ) {
			unset( $options['EXPLAIN'] );
		}

		$sql = parent::selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		// try to rewrite aggregations of bit columns (currently MAX and MIN)
		if ( strpos( $sql, 'MAX(' ) !== false || strpos( $sql, 'MIN(' ) !== false ) {
			$bitColumns = array();
			if ( is_array( $table ) ) {
				foreach ( $table as $t ) {
					$bitColumns += $this->getBitColumns( $this->tableName( $t ) );
				}
			} else {
				$bitColumns = $this->getBitColumns( $this->tableName( $table ) );
			}

			foreach ( $bitColumns as $col => $info ) {
				$replace = array(
					"MAX({$col})" => "MAX(CAST({$col} AS tinyint))",
					"MIN({$col})" => "MIN(CAST({$col} AS tinyint))",
				);
				$sql = str_replace( array_keys( $replace ), array_values( $replace ), $sql );
			}
		}

		return $sql;
	}

	public function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds,
		$fname = __METHOD__
	) {
		$this->mScrollableCursor = false;
		try {
			parent::deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname );
		} catch ( Exception $e ) {
			$this->mScrollableCursor = true;
			throw $e;
		}
		$this->mScrollableCursor = true;
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		$this->mScrollableCursor = false;
		try {
			parent::delete( $table, $conds, $fname );
		} catch ( Exception $e ) {
			$this->mScrollableCursor = true;
			throw $e;
		}
		$this->mScrollableCursor = true;
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on SHOWPLAN_ALL output
	 * This is not necessarily an accurate estimate, so use sparingly
	 * Returns -1 if count cannot be found
	 * Takes same arguments as Database::select()
	 * @param string $table
	 * @param string $vars
	 * @param string $conds
	 * @param string $fname
	 * @param array $options
	 * @return int
	 */
	public function estimateRowCount( $table, $vars = '*', $conds = '',
		$fname = __METHOD__, $options = array()
	) {
		// http://msdn2.microsoft.com/en-us/library/aa259203.aspx
		$options['EXPLAIN'] = true;
		$options['FOR COUNT'] = true;
		$res = $this->select( $table, $vars, $conds, $fname, $options );

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
		# the existance of indexes.
		$sql = "sp_helpindex '" . $table . "'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}

		$result = array();
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

		return empty( $result ) ? false : $result;
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
	public function insert( $table, $arrToInsert, $fname = __METHOD__, $options = array() ) {
		# No rows to insert, easy just return now
		if ( !count( $arrToInsert ) ) {
			return true;
		}

		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		$table = $this->tableName( $table );

		if ( !( isset( $arrToInsert[0] ) && is_array( $arrToInsert[0] ) ) ) { // Not multi row
			$arrToInsert = array( 0 => $arrToInsert ); // make everything multi row compatible
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
			$options = array_diff( $options, array( 'IGNORE' ) );
			$this->mIgnoreDupKeyErrors = true;
		}

		foreach ( $arrToInsert as $a ) {
			// start out with empty identity column, this is so we can return
			// it as a result of the insert logic
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
				} elseif ( is_array( $value ) || is_object( $value ) ) {
					if ( is_object( $value ) && $value instanceof Blob ) {
						$sql .= $this->addQuotes( $value );
					} else {
						$sql .= $this->addQuotes( serialize( $value ) );
					}
				} else {
					$sql .= $this->addQuotes( $value );
				}
			}
			$sql .= ')' . $sqlPost;

			// Run the query
			$this->mScrollableCursor = false;
			try {
				$ret = $this->query( $sql );
			} catch ( Exception $e ) {
				$this->mScrollableCursor = true;
				$this->mIgnoreDupKeyErrors = false;
				throw $e;
			}
			$this->mScrollableCursor = true;

			if ( !is_null( $identity ) ) {
				// then we want to get the identity column value we were assigned and save it off
				$row = $ret->fetchObject();
				if ( is_object( $row ) ) {
					$this->mInsertId = $row->$identity;
				}
			}
		}
		$this->mIgnoreDupKeyErrors = false;
		return $ret;
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	 * Source items may be literals rather than field names, but strings should
	 * be quoted with Database::addQuotes().
	 * @param string $destTable
	 * @param array|string $srcTable May be an array of tables.
	 * @param array $varMap
	 * @param array $conds May be "*" to copy the whole table.
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @return null|ResultWrapper
	 * @throws Exception
	 */
	public function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = __METHOD__,
		$insertOptions = array(), $selectOptions = array()
	) {
		$this->mScrollableCursor = false;
		try {
			$ret = parent::insertSelect(
				$destTable,
				$srcTable,
				$varMap,
				$conds,
				$fname,
				$insertOptions,
				$selectOptions
			);
		} catch ( Exception $e ) {
			$this->mScrollableCursor = true;
			throw $e;
		}
		$this->mScrollableCursor = true;

		return $ret;
	}

	/**
	 * UPDATE wrapper. Takes a condition array and a SET array.
	 *
	 * @param string $table Name of the table to UPDATE. This will be passed through
	 *                DatabaseBase::tableName().
	 *
	 * @param array $values An array of values to SET. For each array element,
	 *                the key gives the field name, and the value gives the data
	 *                to set that field to. The data will be quoted by
	 *                DatabaseBase::addQuotes().
	 *
	 * @param array $conds An array of conditions (WHERE). See
	 *                DatabaseBase::select() for the details of the format of
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
	 * @throws MWException
	 */
	function update( $table, $values, $conds, $fname = __METHOD__, $options = array() ) {
		$table = $this->tableName( $table );
		$binaryColumns = $this->getBinaryColumns( $table );

		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeList( $values, LIST_SET, $binaryColumns );

		if ( $conds !== array() && $conds !== '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND, $binaryColumns );
		}

		$this->mScrollableCursor = false;
		try {
			$ret = $this->query( $sql );
		} catch ( Exception $e ) {
			$this->mScrollableCursor = true;
			throw $e;
		}
		$this->mScrollableCursor = true;
		return true;
	}

	/**
	 * Makes an encoded list of strings from an array
	 * @param array $a Containing the data
	 * @param int $mode Constant
	 *      - LIST_COMMA:          comma separated, no field names
	 *      - LIST_AND:            ANDed WHERE clause (without the WHERE). See
	 *        the documentation for $conds in DatabaseBase::select().
	 *      - LIST_OR:             ORed WHERE clause (without the WHERE)
	 *      - LIST_SET:            comma separated with field names, like a SET clause
	 *      - LIST_NAMES:          comma separated field names
	 * @param array $binaryColumns Contains a list of column names that are binary types
	 *      This is a custom parameter only present for MS SQL.
	 *
	 * @throws MWException|DBUnexpectedError
	 * @return string
	 */
	public function makeList( $a, $mode = LIST_COMMA, $binaryColumns = array() ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this,
				'DatabaseBase::makeList called with incorrect parameters' );
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
			$select = $orderby = array();
			$s1 = preg_match( '#SELECT\s+(.+?)\s+FROM#Dis', $sql, $select );
			$s2 = preg_match( '#(ORDER BY\s+.+?)(\s*FOR XML .*)?$#Dis', $sql, $orderby );
			$overOrder = $postOrder = '';
			$first = $offset + 1;
			$last = $offset + $limit;
			$sub1 = 'sub_' . $this->mSubqueryId;
			$sub2 = 'sub_' . ( $this->mSubqueryId + 1 );
			$this->mSubqueryId += 2;
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
		$server_info = sqlsrv_server_info( $this->mConn );
		$version = 'Error';
		if ( isset( $server_info['SQLServerVersion'] ) ) {
			$version = $server_info['SQLServerVersion'];
		}

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
			wfDebug( "Attempting to call tableExists on a remote table" );
			return false;
		}

		if ( $schema === false ) {
			global $wgDBmwschema;
			$schema = $wgDBmwschema;
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
			wfDebug( "Attempting to call fieldExists on a remote table" );
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
			wfDebug( "Attempting to call fieldInfo on a remote table" );
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

	/**
	 * Begin a transaction, committing any previously open transaction
	 * @param string $fname
	 */
	protected function doBegin( $fname = __METHOD__ ) {
		sqlsrv_begin_transaction( $this->mConn );
		$this->mTrxLevel = 1;
	}

	/**
	 * End a transaction
	 * @param string $fname
	 */
	protected function doCommit( $fname = __METHOD__ ) {
		sqlsrv_commit( $this->mConn );
		$this->mTrxLevel = 0;
	}

	/**
	 * Rollback a transaction.
	 * No-op on non-transactional databases.
	 * @param string $fname
	 */
	protected function doRollback( $fname = __METHOD__ ) {
		sqlsrv_rollback( $this->mConn );
		$this->mTrxLevel = 0;
	}

	/**
	 * Escapes a identifier for use inm SQL.
	 * Throws an exception if it is invalid.
	 * Reference: http://msdn.microsoft.com/en-us/library/aa224033%28v=SQL.80%29.aspx
	 * @param string $identifier
	 * @throws MWException
	 * @return string
	 */
	private function escapeIdentifier( $identifier ) {
		if ( strlen( $identifier ) == 0 ) {
			throw new MWException( "An identifier must not be empty" );
		}
		if ( strlen( $identifier ) > 128 ) {
			throw new MWException( "The identifier '$identifier' is too long (max. 128)" );
		}
		if ( ( strpos( $identifier, '[' ) !== false )
			|| ( strpos( $identifier, ']' ) !== false )
		) {
			// It may be allowed if you quoted with double quotation marks, but
			// that would break if QUOTED_IDENTIFIER is OFF
			throw new MWException( "Square brackets are not allowed in '$identifier'" );
		}

		return "[$identifier]";
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
	 * @param string|Blob $s
	 * @return string
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
	 * @param string $db
	 * @return bool
	 */
	public function selectDB( $db ) {
		try {
			$this->mDBname = $db;
			$this->query( "USE $db" );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}

	/**
	 * @param array $options An associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	public function makeSelectOptions( $options ) {
		$tailOpts = '';
		$startOpts = '';

		$noKeyOptions = array();
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
		return array( $startOpts, '', $tailOpts, '' );
	}

	/**
	 * Get the type of the DBMS, as it appears in $wgDBtype.
	 * @return string
	 */
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
		$join_conds = array()
	) {
		$gcsq = 'gcsq_' . $this->mSubqueryId;
		$this->mSubqueryId++;

		$delimLen = strlen( $delim );
		$fld = "{$field} + {$this->addQuotes( $delim )}";
		$sql = "(SELECT LEFT({$field}, LEN({$field}) - {$delimLen}) FROM ("
			. $this->selectSQLText( $table, $fld, $conds, null, array( 'FOR XML' ), $join_conds )
			. ") {$gcsq} ({$field}))";

		return $sql;
	}

	/**
	 * @return string
	 */
	public function getSearchEngine() {
		return "SearchMssql";
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

		if ( $this->mBinaryColumnCache === null ) {
			$this->populateColumnCaches();
		}

		return isset( $this->mBinaryColumnCache[$tableRaw] )
			? $this->mBinaryColumnCache[$tableRaw]
			: array();
	}

	/**
	 * @param string $table
	 * @return array
	 */
	private function getBitColumns( $table ) {
		$tableRawArr = explode( '.', preg_replace( '#\[([^\]]*)\]#', '$1', $table ) );
		$tableRaw = array_pop( $tableRawArr );

		if ( $this->mBitColumnCache === null ) {
			$this->populateColumnCaches();
		}

		return isset( $this->mBitColumnCache[$tableRaw] )
			? $this->mBitColumnCache[$tableRaw]
			: array();
	}

	private function populateColumnCaches() {
		$res = $this->select( 'INFORMATION_SCHEMA.COLUMNS', '*',
			array(
				'TABLE_CATALOG' => $this->mDBname,
				'TABLE_SCHEMA' => $this->mSchema,
				'DATA_TYPE' => array( 'varbinary', 'binary', 'image', 'bit' )
			) );

		$this->mBinaryColumnCache = array();
		$this->mBitColumnCache = array();
		foreach ( $res as $row ) {
			if ( $row->DATA_TYPE == 'bit' ) {
				$this->mBitColumnCache[$row->TABLE_NAME][$row->COLUMN_NAME] = $row;
			} else {
				$this->mBinaryColumnCache[$row->TABLE_NAME][$row->COLUMN_NAME] = $row;
			}
		}
	}

	/**
	 * @param string $name
	 * @param string $format
	 * @return string
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
	 * @param string $format One of quoted, raw, or split
	 * @return string
	 */
	function realTableName( $name, $format = 'quoted' ) {
		$table = parent::tableName( $name, $format );
		if ( $format == 'split' ) {
			// Used internally, we want the schema split off from the table name and returned
			// as a list with 3 elements (database, schema, table)
			$table = explode( '.', $table );
			while ( count( $table ) < 3 ) {
				array_unshift( $table, false );
			}
		}
		return $table;
	}

	/**
	 * Called in the installer and updater.
	 * Probably doesn't need to be called anywhere else in the codebase.
	 * @param bool|null $value
	 * @return bool|null
	 */
	public function prepareStatements( $value = null ) {
		return wfSetVar( $this->mPrepareStatements, $value );
	}

	/**
	 * Called in the installer and updater.
	 * Probably doesn't need to be called anywhere else in the codebase.
	 * @param bool|null $value
	 * @return bool|null
	 */
	public function scrollableCursor( $value = null ) {
		return wfSetVar( $this->mScrollableCursor, $value );
	}
} // end DatabaseMssql class

/**
 * Utility class.
 *
 * @ingroup Database
 */
class MssqlField implements Field {
	private $name, $tableName, $default, $max_length, $nullable, $type;

	function __construct( $info ) {
		$this->name = $info['COLUMN_NAME'];
		$this->tableName = $info['TABLE_NAME'];
		$this->default = $info['COLUMN_DEFAULT'];
		$this->max_length = $info['CHARACTER_MAXIMUM_LENGTH'];
		$this->nullable = !( strtolower( $info['IS_NULLABLE'] ) == 'no' );
		$this->type = $info['DATA_TYPE'];
	}

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tableName;
	}

	function defaultValue() {
		return $this->default;
	}

	function maxLength() {
		return $this->max_length;
	}

	function isNullable() {
		return $this->nullable;
	}

	function type() {
		return $this->type;
	}
}

class MssqlBlob extends Blob {
	public function __construct( $data ) {
		if ( $data instanceof MssqlBlob ) {
			return $data;
		} elseif ( $data instanceof Blob ) {
			$this->mData = $data->fetch();
		} elseif ( is_array( $data ) && is_object( $data ) ) {
			$this->mData = serialize( $data );
		} else {
			$this->mData = $data;
		}
	}

	/**
	 * Returns an unquoted hex representation of a binary string
	 * for insertion into varbinary-type fields
	 * @return string
	 */
	public function fetch() {
		if ( $this->mData === null ) {
			return 'null';
		}

		$ret = '0x';
		$dataLength = strlen( $this->mData );
		for ( $i = 0; $i < $dataLength; $i++ ) {
			$ret .= bin2hex( pack( 'C', ord( $this->mData[$i] ) ) );
		}

		return $ret;
	}
}

class MssqlResultWrapper extends ResultWrapper {
	private $mSeekTo = null;

	/**
	 * @return stdClass|bool
	 */
	public function fetchObject() {
		$res = $this->result;

		if ( $this->mSeekTo !== null ) {
			$result = sqlsrv_fetch_object( $res, 'stdClass', array(),
				SQLSRV_SCROLL_ABSOLUTE, $this->mSeekTo );
			$this->mSeekTo = null;
		} else {
			$result = sqlsrv_fetch_object( $res );
		}

		// MediaWiki expects us to return boolean false when there are no more rows instead of null
		if ( $result === null ) {
			return false;
		}

		return $result;
	}

	/**
	 * @return array|bool
	 */
	public function fetchRow() {
		$res = $this->result;

		if ( $this->mSeekTo !== null ) {
			$result = sqlsrv_fetch_array( $res, SQLSRV_FETCH_BOTH,
				SQLSRV_SCROLL_ABSOLUTE, $this->mSeekTo );
			$this->mSeekTo = null;
		} else {
			$result = sqlsrv_fetch_array( $res );
		}

		// MediaWiki expects us to return boolean false when there are no more rows instead of null
		if ( $result === null ) {
			return false;
		}

		return $result;
	}

	/**
	 * @param int $row
	 * @return bool
	 */
	public function seek( $row ) {
		$res = $this->result;

		// check bounds
		$numRows = $this->db->numRows( $res );
		$row = intval( $row );

		if ( $numRows === 0 ) {
			return false;
		} elseif ( $row < 0 || $row > $numRows - 1 ) {
			return false;
		}

		// Unlike MySQL, the seek actually happens on the next access
		$this->mSeekTo = $row;
		return true;
	}
}
