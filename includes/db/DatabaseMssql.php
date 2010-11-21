<?php
/**
 * This is the MS SQL Server Native database abstraction layer.
 *
 * @file
 * @ingroup Database
 * @author Joel Penner <a-joelpe at microsoft dot com>
 * @author Chris Pucci <a-cpucci at microsoft dot com>
 * @author Ryan Biesemeyer <v-ryanbi at microsoft dot com>
 */

/**
 * @ingroup Database
 */
class DatabaseMssql extends DatabaseBase {
	var $mInsertId = NULL;
	var $mLastResult = NULL;
	var $mAffectedRows = NULL;

	function __construct( $server = false, $user = false, $password = false, $dbName = false,
		$flags = 0 )
	{
		$this->mFlags = $flags;
		$this->open( $server, $user, $password, $dbName );
	}

	function cascadingDeletes() {
		return true;
	}
	function cleanupTriggers() {
		return true;
	}
	function strictIPs() {
		return true;
	}
	function realTimestamps() {
		return true;
	}
	function implicitGroupby() {
		return false;
	}
	function implicitOrderby() {
		return false;
	}
	function functionalIndexes() {
		return true;
	}
	function unionSupportsOrderAndLimit() {
		return false;
	}

	static function newFromParams( $server, $user, $password, $dbName, $flags = 0 ) {
		return new DatabaseMssql( $server, $user, $password, $dbName, $flags );
	}

	/**
	 * Usually aborts on failure
	 */
	function open( $server, $user, $password, $dbName ) {
		# Test for driver support, to avoid suppressed fatal error
		if ( !function_exists( 'sqlsrv_connect' ) ) {
			throw new DBConnectionError( $this, "MS Sql Server Native (sqlsrv) functions missing. You can download the driver from: http://go.microsoft.com/fwlink/?LinkId=123470\n" );
		}

		global $wgDBport;

		if ( !strlen( $user ) ) { # e.g. the class is being loaded
			return;
		}

		$this->close();
		$this->mServer = $server;
		$this->mPort = $wgDBport;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$connectionInfo = array();

		if( $dbName ) {
			$connectionInfo['Database'] = $dbName;
		}

		// Start NT Auth Hack
		// Quick and dirty work around to provide NT Auth designation support.
		// Current solution requires installer to know to input 'ntauth' for both username and password
		// to trigger connection via NT Auth. - ugly, ugly, ugly
		// TO-DO: Make this better and add NT Auth choice to MW installer when SQL Server option is chosen.
		$ntAuthUserTest = strtolower( $user );
		$ntAuthPassTest = strtolower( $password );

		// Decide which auth scenerio to use
		if( ( $ntAuthPassTest == 'ntauth' && $ntAuthUserTest == 'ntauth' ) ){
			// Don't add credentials to $connectionInfo
		} else {
			$connectionInfo['UID'] = $user;
			$connectionInfo['PWD'] = $password;
		}
		// End NT Auth Hack

		$this->mConn = @sqlsrv_connect( $server, $connectionInfo );

		if ( $this->mConn === false ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError() . "\n" );
			return false;
		}

		$this->mOpened = true;
		return $this->mConn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 */
	function close() {
		$this->mOpened = false;
		if ( $this->mConn ) {
			return sqlsrv_close( $this->mConn );
		} else {
			return true;
		}
	}

	function doQuery( $sql ) {
		wfDebug( "SQL: [$sql]\n" );
		$this->offset = 0;

		// several extensions seem to think that all databases support limits via LIMIT N after the WHERE clause
		// well, MSSQL uses SELECT TOP N, so to catch any of those extensions we'll do a quick check for a LIMIT
		// clause and pass $sql through $this->LimitToTopN() which parses the limit clause and passes the result to
		// $this->limitResult();
		if ( preg_match( '/\bLIMIT\s*/i', $sql ) ) {
			// massage LIMIT -> TopN
			$sql = $this->LimitToTopN( $sql ) ;
		}

		// MSSQL doesn't have EXTRACT(epoch FROM XXX)
		if ( preg_match('#\bEXTRACT\s*?\(\s*?EPOCH\s+FROM\b#i', $sql, $matches ) ) {
			// This is same as UNIX_TIMESTAMP, we need to calc # of seconds from 1970
			$sql = str_replace( $matches[0], "DATEDIFF(s,CONVERT(datetime,'1/1/1970'),", $sql );
		}

		// perform query
		$stmt = sqlsrv_query( $this->mConn, $sql );
		if ( $stmt == false ) {
			$message = "A database error has occurred.  Did you forget to run maintenance/update.php after upgrading?  See: http://www.mediawiki.org/wiki/Manual:Upgrading#Run_the_update_script\n" .
				"Query: " . htmlentities( $sql ) . "\n" .
				"Function: " . __METHOD__ . "\n";
			// process each error (our driver will give us an array of errors unlike other providers)
			foreach ( sqlsrv_errors() as $error ) {
				$message .= $message . "ERROR[" . $error['code'] . "] " . $error['message'] . "\n";
			}

			throw new DBUnexpectedError( $this, $message );
		}
		// remember number of rows affected
		$this->mAffectedRows = sqlsrv_rows_affected( $stmt );

		// if it is a SELECT statement, or an insert with a request to output something we want to return a row.
		if ( ( preg_match( '#\bSELECT\s#i', $sql ) ) ||
			( preg_match( '#\bINSERT\s#i', $sql ) && preg_match( '#\bOUTPUT\s+INSERTED\b#i', $sql ) ) ) {
			// this is essentially a rowset, but Mediawiki calls these 'result'
			// the rowset owns freeing the statement
			$res = new MssqlResult( $stmt );
		} else {
			// otherwise we simply return it was successful, failure throws an exception
			$res = true;
		}
		return $res;
	}

	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		$res->free();
	}

	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		$row = $res->fetch( 'OBJECT' );
		return $row;
	}

	function getErrors() {
		$strRet = '';
		$retErrors = sqlsrv_errors( SQLSRV_ERR_ALL );
		if ( $retErrors != null ) {
			foreach ( $retErrors as $arrError ) {
				$strRet .= "SQLState: " . $arrError[ 'SQLSTATE'] . "\n";
				$strRet .= "Error Code: " . $arrError[ 'code'] . "\n";
				$strRet .= "Message: " . $arrError[ 'message'] . "\n";
			}
		} else {
			$strRet = "No errors found";
		}
		return $strRet;
	}

	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		$row = $res->fetch( SQLSRV_FETCH_BOTH );
		return $row;
	}

	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return ( $res ) ? $res->numrows() : 0;
	}

	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return ( $res ) ? $res->numfields() : 0;
	}

	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return ( $res ) ? $res->fieldname( $n ) : 0;
	}

	/**
	 * This must be called after nextSequenceVal
	 */
	function insertId() {
		return $this->mInsertId;
	}

	function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return ( $res ) ? $res->seek( $row ) : false;
	}

	function lastError() {
		if ( $this->mConn ) {
			return $this->getErrors();
		}
		else {
			return "No database connection";
		}
	}

	function lastErrno() {
		$err = sqlsrv_errors( SQLSRV_ERR_ALL );
		if ( $err[0] ) return $err[0]['code'];
		else return 0;
	}

	function affectedRows() {
		return $this->mAffectedRows;
	}

	/**
	 * SELECT wrapper
	 *
	 * @param $table   Mixed: array or string, table name(s) (prefix auto-added)
	 * @param $vars    Mixed: array or string, field name(s) to be retrieved
	 * @param $conds   Mixed: array or string, condition(s) for WHERE
	 * @param $fname   String: calling function name (use __METHOD__) for logs/profiling
	 * @param $options Array: associative array of options (e.g. array('GROUP BY' => 'page_title')),
	 *                 see Database::makeSelectOptions code for list of supported stuff
	 * @param $join_conds Array: Associative array of table join conditions (optional)
	 *						   (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return Mixed: database result resource (feed to Database::fetchObject or whatever), or false on failure
	 */
	function select( $table, $vars, $conds = '', $fname = 'DatabaseMssql::select', $options = array(), $join_conds = array() )
	{
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
		if ( isset( $options['EXPLAIN'] ) ) {
			sqlsrv_query( $this->mConn, "SET SHOWPLAN_ALL ON;" );
			$ret = $this->query( $sql, $fname );
			sqlsrv_query( $this->mConn, "SET SHOWPLAN_ALL OFF;" );
			return $ret;
		}
		return $this->query( $sql, $fname );
	}

	/**
	 * SELECT wrapper
	 *
	 * @param $table   Mixed:  Array or string, table name(s) (prefix auto-added)
	 * @param $vars    Mixed:  Array or string, field name(s) to be retrieved
	 * @param $conds   Mixed:  Array or string, condition(s) for WHERE
	 * @param $fname   String: Calling function name (use __METHOD__) for logs/profiling
	 * @param $options Array:  Associative array of options (e.g. array('GROUP BY' => 'page_title')),
	 *                 see Database::makeSelectOptions code for list of supported stuff
	 * @param $join_conds Array: Associative array of table join conditions (optional)
	 *                    (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return string, the SQL text
	 */
	function selectSQLText( $table, $vars, $conds = '', $fname = 'DatabaseMssql::select', $options = array(), $join_conds = array() ) {
		if ( isset( $options['EXPLAIN'] ) ) {
			unset( $options['EXPLAIN'] );
		}
		return parent::selectSQLText(  $table, $vars, $conds, $fname, $options, $join_conds );
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on SHOWPLAN_ALL output
	 * This is not necessarily an accurate estimate, so use sparingly
	 * Returns -1 if count cannot be found
	 * Takes same arguments as Database::select()
	 */
	function estimateRowCount( $table, $vars = '*', $conds = '', $fname = 'DatabaseMssql::estimateRowCount', $options = array() ) {
		$options['EXPLAIN'] = true;// http://msdn2.microsoft.com/en-us/library/aa259203.aspx
		$res = $this->select( $table, $vars, $conds, $fname, $options );

		$rows = -1;
		if ( $res ) {
			$row = $this->fetchRow( $res );
			if ( isset( $row['EstimateRows'] ) ) $rows = $row['EstimateRows'];
		}
		return $rows;
	}


	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo( $table, $index, $fname = 'DatabaseMssql::indexExists' ) {
		# This does not return the same info as MYSQL would, but that's OK because MediaWiki never uses the
		# returned value except to check for the existance of indexes.
		$sql = "sp_helpindex '" . $table . "'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return NULL;
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
			} else if ( $index == 'PRIMARY' && stristr( $row->index_description, 'PRIMARY' ) ) {
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
	 */
	function insert( $table, $arrToInsert, $fname = 'DatabaseMssql::insert', $options = array() ) {
		# No rows to insert, easy just return now
		if ( !count( $arrToInsert ) ) {
			return true;
		}

		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		$table = $this->tableName( $table );

		if ( !( isset( $arrToInsert[0] ) && is_array( $arrToInsert[0] ) ) ) {// Not multi row
			$arrToInsert = array( 0 => $arrToInsert );// make everything multi row compatible
		}

		$allOk = true;


		// We know the table we're inserting into, get its identity column
		$identity = null;
		$tableRaw = preg_replace( '#\[([^\]]*)\]#', '$1', $table ); // strip matching square brackets from table name
		$res = $this->doQuery( "SELECT NAME AS idColumn FROM SYS.IDENTITY_COLUMNS WHERE OBJECT_NAME(OBJECT_ID)='{$tableRaw}'" );
		if( $res && $res->numrows() ){
			// There is an identity for this table.
			$identity = array_pop( $res->fetch( SQLSRV_FETCH_ASSOC ) );
		}
		unset( $res );

		foreach ( $arrToInsert as $a ) {
			// start out with empty identity column, this is so we can return it as a result of the insert logic
			$sqlPre = '';
			$sqlPost = '';
			$identityClause = '';

			// if we have an identity column
			if( $identity ) {
				// iterate through
				foreach ($a as $k => $v ) {
					if ( $k == $identity ) {
						if( !is_null($v) ){
							// there is a value being passed to us, we need to turn on and off inserted identity
							$sqlPre = "SET IDENTITY_INSERT $table ON;" ;
							$sqlPost = ";SET IDENTITY_INSERT $table OFF;";

						} else {
							// we can't insert NULL into an identity column, so remove the column from the insert.
							unset( $a[$k] );
						}
					}
				}
				$identityClause = "OUTPUT INSERTED.$identity "; // we want to output an identity column as result
			}

			$keys = array_keys( $a );


			// INSERT IGNORE is not supported by SQL Server
			// remove IGNORE from options list and set ignore flag to true
			$ignoreClause = false;
			foreach ( $options as $k => $v ) {
				if ( strtoupper( $v ) == "IGNORE" ) {
					unset( $options[$k] );
					$ignoreClause = true;
				}
			}

			// translate MySQL INSERT IGNORE to something SQL Server can use
			// example:
			// MySQL: INSERT IGNORE INTO user_groups (ug_user,ug_group) VALUES ('1','sysop')
			// MSSQL: IF NOT EXISTS (SELECT * FROM user_groups WHERE ug_user = '1') INSERT INTO user_groups (ug_user,ug_group) VALUES ('1','sysop')
			if ( $ignoreClause == true ) {
				$prival = $a[$keys[0]];
				$sqlPre .= "IF NOT EXISTS (SELECT * FROM $table WHERE $keys[0] = '$prival')";
			}

			// Build the actual query
			$sql = $sqlPre . 'INSERT ' . implode( ' ', $options ) .
				" INTO $table (" . implode( ',', $keys ) . ") $identityClause VALUES (";

			$first = true;
			foreach ( $a as $value ) {
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ',';
				}
				if ( is_string( $value ) ) {
					$sql .= $this->quote_ident( $value );
				} elseif ( is_null( $value ) ) {
					$sql .= 'null';
				} elseif ( is_array( $value ) || is_object( $value ) ) {
					if ( is_object( $value ) && strtolower( get_class( $value ) ) == 'blob' ) {
						$sql .= $this->quote_ident( $value->fetch() );
					}  else {
						$sql .= $this->quote_ident( serialize( $value ) );
					}
				} else {
					$sql .= $value;
				}
			}
			$sql .= ')' . $sqlPost;

			// Run the query
			$ret = sqlsrv_query( $this->mConn, $sql );

			if ( $ret === false ) {
				throw new DBQueryError( $this, $this->getErrors(), $this->lastErrno(), $sql, $fname );
			} elseif ( $ret != NULL ) {
				// remember number of rows affected
				$this->mAffectedRows = sqlsrv_rows_affected( $ret );
				if ( !is_null($identity) ) {
					// then we want to get the identity column value we were assigned and save it off
					$row = sqlsrv_fetch_object( $ret );
					$this->mInsertId = $row->$identity;
				}
				sqlsrv_free_stmt( $ret );
				continue;
			}
			$allOk = false;
		}
		return $allOk;
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	 * Source items may be literals rather than field names, but strings should be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 */
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'DatabaseMssql::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$ret = parent::insertSelect( $destTable, $srcTable, $varMap, $conds, $fname, $insertOptions, $selectOptions );

		if ( $ret === false ) {
			throw new DBQueryError( $this, $this->getErrors(), $this->lastErrno(), $sql, $fname );
		} elseif ( $ret != NULL ) {
			// remember number of rows affected
			$this->mAffectedRows = sqlsrv_rows_affected( $ret );
			return $ret;
		}
		return NULL;
	}

	/**
	 * Format a table name ready for use in constructing an SQL query
	 *
	 * This does two important things: it brackets table names which as necessary,
	 * and it adds a table prefix if there is one.
	 *
	 * All functions of this object which require a table name call this function
	 * themselves. Pass the canonical name to such functions. This is only needed
	 * when calling query() directly.
	 *
	 * @param $name String: database table name
	 */
	function tableName( $name ) {
		global $wgSharedDB;
		# Skip quoted literals
		if ( $name != '' && $name { 0 } != '[' ) {
			if ( $this->mTablePrefix !== '' &&  strpos( '.', $name ) === false ) {
				$name = "{$this->mTablePrefix}$name";
			}
			if ( isset( $wgSharedDB ) && "{$this->mTablePrefix}user" == $name ) {
				$name = "[$wgSharedDB].[$name]";
			} else {
				# Standard quoting
				if ( $name != '' ) $name = "[$name]";
			}
		}
		return $name;
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 */
	function nextSequenceValue( $seqName ) {
		if ( !$this->tableExists( 'sequence_' . $seqName ) ) {
			sqlsrv_query( $this->mConn, "CREATE TABLE [sequence_$seqName] (id INT NOT NULL IDENTITY PRIMARY KEY, junk varchar(10) NULL)" );
		}
		sqlsrv_query( $this->mConn, "INSERT INTO [sequence_$seqName] (junk) VALUES ('')" );
		$ret = sqlsrv_query( $this->mConn, "SELECT TOP 1 id FROM [sequence_$seqName] ORDER BY id DESC" );
		$row = sqlsrv_fetch_array( $ret, SQLSRV_FETCH_ASSOC );// KEEP ASSOC THERE, weird weird bug dealing with the return value if you don't

		sqlsrv_free_stmt( $ret );
		$this->mInsertId = $row['id'];
		return $row['id'];
	}

	/**
	 * Return the current value of a sequence. Assumes it has ben nextval'ed in this session.
	 */
	function currentSequenceValue( $seqName ) {
		$ret = sqlsrv_query( $this->mConn, "SELECT TOP 1 id FROM [sequence_$seqName] ORDER BY id DESC" );
		if ( $ret !== false ) {
			$row = sqlsrv_fetch_array( $ret );
			sqlsrv_free_stmt( $ret );
			return $row['id'];
		} else {
			return $this->nextSequenceValue( $seqName );
		}
	}


	# REPLACE query wrapper
	# MSSQL simulates this with a DELETE followed by INSERT
	# $row is the row to insert, an associative array
	# $uniqueIndexes is an array of indexes. Each element may be either a
	# field name or an array of field names
	#
	# It may be more efficient to leave off unique indexes which are unlikely to collide.
	# However if you do this, you run the risk of encountering errors which wouldn't have
	# occurred in MySQL
	function replace( $table, $uniqueIndexes, $rows, $fname = 'DatabaseMssql::replace' ) {
		$table = $this->tableName( $table );

		if ( count( $rows ) == 0 ) {
			return;
		}

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		foreach ( $rows as $row ) {
			# Delete rows which collide
			if ( $uniqueIndexes ) {
				$sql = "DELETE FROM $table WHERE ";
				$first = true;
				foreach ( $uniqueIndexes as $index ) {
					if ( $first ) {
						$first = false;
						$sql .= "(";
					} else {
						$sql .= ') OR (';
					}
					if ( is_array( $index ) ) {
						$first2 = true;
						foreach ( $index as $col ) {
							if ( $first2 ) {
								$first2 = false;
							} else {
								$sql .= ' AND ';
							}
							$sql .= $col . '=' . $this->addQuotes( $row[$col] );
						}
					} else {
						$sql .= $index . '=' . $this->addQuotes( $row[$index] );
					}
				}
				$sql .= ')';
				$this->query( $sql, $fname );
			}

			# Now insert the row
			$sql = "INSERT INTO $table (" . $this->makeList( array_keys( $row ), LIST_NAMES ) . ') VALUES (' .
				$this->makeList( $row, LIST_COMMA ) . ')';
			$this->query( $sql, $fname );
		}
	}

	# DELETE where the condition is a join
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = "DatabaseMssql::deleteJoin" ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'DatabaseMssql::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE FROM $delTable WHERE $delVar IN (SELECT $joinVar FROM $joinTable ";
		if ( $conds != '*' ) {
			$sql .= 'WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		$sql .= ')';

		$this->query( $sql, $fname );
	}

	# Returns the size of a text field, or -1 for "unlimited"
	function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SELECT CHARACTER_MAXIMUM_LENGTH,DATA_TYPE FROM INFORMATION_SCHEMA.Columns
			WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field'";
		$res = $this->query( $sql );
		$row = $this->fetchRow( $res );
		$size = -1;
		if ( strtolower( $row['DATA_TYPE'] ) != 'text' ) $size = $row['CHARACTER_MAXIMUM_LENGTH'];
		return $size;
	}

	/**
	 * Construct a LIMIT query with optional offset
	 * This is used for query pages
	 * $sql string SQL query we will append the limit too
	 * $limit integer the SQL limit
	 * $offset integer the SQL offset (default false)
	 */
	function limitResult( $sql, $limit, $offset = false ) {
		if ( $offset === false || $offset == 0 ) {
			if ( strpos( $sql, "SELECT" ) === false ) {
				return "TOP {$limit} " . $sql;
			} else {
				return preg_replace( '/\bSELECT(\s*DISTINCT)?\b/Dsi', 'SELECT$1 TOP ' . $limit, $sql, 1 );
			}
		} else {
			$sql = '
				SELECT * FROM (
				  SELECT sub2.*, ROW_NUMBER() OVER(ORDER BY sub2.line2) AS line3 FROM (
					SELECT 1 AS line2, sub1.* FROM (' . $sql . ') AS sub1
				  ) as sub2
				) AS sub3
				WHERE line3 BETWEEN ' . ( $offset + 1 ) . ' AND ' . ( $offset + $limit );
			return $sql;
		}
	}

	// If there is a limit clause, parse it, strip it, and pass the remaining sql through limitResult()
	// with the appropriate parameters. Not the prettiest solution, but better than building a whole new parser.
	// This exists becase there are still too many extensions that don't use dynamic sql generation.
	function LimitToTopN( $sql ) {
		// Matches: LIMIT {[offset,] row_count | row_count OFFSET offset}
		$pattern = '/\bLIMIT\s+((([0-9]+)\s*,\s*)?([0-9]+)(\s+OFFSET\s+([0-9]+))?)/i';
		if ( preg_match( $pattern, $sql, $matches ) ) {
			// row_count = $matches[4]
			$row_count = $matches[4];
			// offset = $matches[3] OR $matches[6]
			$offset = $matches[3] or
				$offset = $matches[6] or
				$offset = false;

			// strip the matching LIMIT clause out
			$sql = str_replace( $matches[0], '', $sql );
			return $this->limitResult( $sql, $row_count, $offset );
		}
		return $sql;
	}

	// MSSQL does support this, but documentation is too thin to make a generalized
	// function for this. Apparently UPDATE TOP (N) works, but the sort order
	// may not be what we're expecting so the top n results may be a random selection.
	// TODO: Implement properly.
	function limitResultForUpdate( $sql, $num ) {
		return $sql;
	}


	function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_ISO_8601, $ts );
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	public static function getSoftwareLink() {
		return "[http://www.microsoft.com/sql/ MS SQL Server]";
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		$server_info = sqlsrv_server_info( $this->mConn );
		$version = 'Error';
		if ( isset( $server_info['SQLServerVersion'] ) ) $version = $server_info['SQLServerVersion'];
		return $version;
	}

	function tableExists ( $table, $schema = false ) {
		$res = sqlsrv_query( $this->mConn, "SELECT * FROM information_schema.tables
			WHERE table_type='BASE TABLE' AND table_name = '$table'" );
		if ( $res === false ) {
			print( "Error in tableExists query: " . $this->getErrors() );
			return false;
		}
		if ( sqlsrv_fetch( $res ) )
			return true;
		else
			return false;
	}

	/**
	 * Query whether a given column exists in the mediawiki schema
	 */
	function fieldExists( $table, $field, $fname = 'DatabaseMssql::fieldExists' ) {
		$table = $this->tableName( $table );
		$res = sqlsrv_query( $this->mConn, "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.Columns
			WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field'" );
		if ( $res === false ) {
			print( "Error in fieldExists query: " . $this->getErrors() );
			return false;
		}
		if ( sqlsrv_fetch( $res ) )
			return true;
		else
			return false;
	}

	function fieldInfo( $table, $field ) {
		$table = $this->tableName( $table );
		$res = sqlsrv_query( $this->mConn, "SELECT * FROM INFORMATION_SCHEMA.Columns
			WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field'" );
		if ( $res === false ) {
			print( "Error in fieldInfo query: " . $this->getErrors() );
			return false;
		}
		$meta = $this->fetchRow( $res );
		if ( $meta ) {
			return new MssqlField( $meta );
		}
		return false;
	}

	/**
	 * Begin a transaction, committing any previously open transaction
	 */
	function begin( $fname = 'DatabaseMssql::begin' ) {
		sqlsrv_begin_transaction( $this->mConn );
		$this->mTrxLevel = 1;
	}

	/**
	 * End a transaction
	 */
	function commit( $fname = 'DatabaseMssql::commit' ) {
		sqlsrv_commit( $this->mConn );
		$this->mTrxLevel = 0;
	}

	/**
	 * Rollback a transaction.
	 * No-op on non-transactional databases.
	 */
	function rollback( $fname = 'DatabaseMssql::rollback' ) {
		sqlsrv_rollback( $this->mConn );
		$this->mTrxLevel = 0;
	}

	function setup_database() {
		global $wgDBuser;

		// Make sure that we can write to the correct schema
		$ctest = "mediawiki_test_table";
		if ( $this->tableExists( $ctest ) ) {
			$this->doQuery( "DROP TABLE $ctest" );
		}
		$SQL = "CREATE TABLE $ctest (a int)";
		$res = $this->doQuery( $SQL );
		if ( !$res ) {
			print "<b>FAILED</b>. Make sure that the user " . htmlspecialchars( $wgDBuser ) . " can write to the database</li>\n";
			dieout( "" );
		}
		$this->doQuery( "DROP TABLE $ctest" );

		$res = $this->sourceFile( "../maintenance/mssql/tables.sql" );
		if ( $res !== true ) {
			echo " <b>FAILED</b></li>";
			dieout( htmlspecialchars( $res ) );
		}

		# Avoid the non-standard "REPLACE INTO" syntax
		$f = fopen( "../maintenance/interwiki.sql", 'r' );
		if ( $f == false ) {
			dieout( "<li>Could not find the interwiki.sql file" );
		}
		# We simply assume it is already empty as we have just created it
		$SQL = "INSERT INTO interwiki(iw_prefix,iw_url,iw_local) VALUES ";
		while ( ! feof( $f ) ) {
			$line = fgets( $f, 1024 );
			$matches = array();
			if ( !preg_match( '/^\s*(\(.+?),(\d)\)/', $line, $matches ) ) {
				continue;
			}
			$this->query( "$SQL $matches[1],$matches[2])" );
		}
		print " (table interwiki successfully populated)...\n";

		$this->commit();
	}

	/**
	 * Escapes a identifier for use inm SQL.
	 * Throws an exception if it is invalid.
	 * Reference: http://msdn.microsoft.com/en-us/library/aa224033%28v=SQL.80%29.aspx
	 */
	private function escapeIdentifier( $identifier ) {
		if ( strlen( $identifier ) == 0 ) {
			throw new MWException( "An identifier must not be empty" );
		}
		if ( strlen( $identifier ) > 128 ) {
			throw new MWException( "The identifier '$identifier' is too long (max. 128)" );
		}
		if ( ( strpos( $identifier, '[' ) !== false ) || ( strpos( $identifier, ']' ) !== false ) ) {
			// It may be allowed if you quoted with double quotation marks, but that would break if QUOTED_IDENTIFIER is OFF
			throw new MWException( "You can't use square brackers in the identifier '$identifier'" );
		}
		return "[$identifier]";
	}

	/**
	 * Initial setup.
	 * Precondition: This object is connected as the superuser.
	 * Creates the database, schema, user and login.
	 */
	function initial_setup( $dbName, $newUser, $loginPassword ) {
		$dbName = $this->escapeIdentifier( $dbName );

		// It is not clear what can be used as a login,
		// From http://msdn.microsoft.com/en-us/library/ms173463.aspx
		// a sysname may be the same as an identifier.
		$newUser = $this->escapeIdentifier( $newUser );
		$loginPassword = $this->addQuotes( $loginPassword );

		$this->doQuery("CREATE DATABASE $dbName;");
		$this->doQuery("USE $dbName;");
		$this->doQuery("CREATE SCHEMA $dbName;");
		$this->doQuery("
						CREATE
							LOGIN $newUser
						WITH
							PASSWORD=$loginPassword
						;
					");
		$this->doQuery("
						CREATE
							USER $newUser
						FOR
							LOGIN $newUser
						WITH
							DEFAULT_SCHEMA=$dbName
						;
					");
		$this->doQuery("
						GRANT
							BACKUP DATABASE,
							BACKUP LOG,
							CREATE DEFAULT,
							CREATE FUNCTION,
							CREATE PROCEDURE,
							CREATE RULE,
							CREATE TABLE,
							CREATE VIEW,
							CREATE FULLTEXT CATALOG
						ON
							DATABASE::$dbName
						TO $newUser
						;
					");
		$this->doQuery("
						GRANT
							CONTROL
						ON
							SCHEMA::$dbName
						TO $newUser
						;
					");


	}

	function encodeBlob( $b ) {
	// we can't have zero's and such, this is a simple encoding to make sure we don't barf
		return base64_encode( $b );
	}

	function decodeBlob( $b ) {
	// we can't have zero's and such, this is a simple encoding to make sure we don't barf
	return base64_decode( $b );
	}

	/**
	 * @private
	 */
	function tableNamesWithUseIndexOrJOIN( $tables, $use_index = array(), $join_conds = array() ) {
		$ret = array();
		$retJOIN = array();
		$use_index_safe = is_array( $use_index ) ? $use_index : array();
		$join_conds_safe = is_array( $join_conds ) ? $join_conds : array();
		foreach ( $tables as $table ) {
			// Is there a JOIN and INDEX clause for this table?
			if ( isset( $join_conds_safe[$table] ) && isset( $use_index_safe[$table] ) ) {
				$tableClause = $join_conds_safe[$table][0] . ' ' . $this->tableName( $table );
				$tableClause .= ' ' . $this->useIndexClause( implode( ',', (array)$use_index_safe[$table] ) );
				$tableClause .= ' ON (' . $this->makeList( (array)$join_conds_safe[$table][1], LIST_AND ) . ')';
				$retJOIN[] = $tableClause;
			// Is there an INDEX clause?
			} else if ( isset( $use_index_safe[$table] ) ) {
				$tableClause = $this->tableName( $table );
				$tableClause .= ' ' . $this->useIndexClause( implode( ',', (array)$use_index_safe[$table] ) );
				$ret[] = $tableClause;
			// Is there a JOIN clause?
			} else if ( isset( $join_conds_safe[$table] ) ) {
				$tableClause = $join_conds_safe[$table][0] . ' ' . $this->tableName( $table );
				$tableClause .= ' ON (' . $this->makeList( (array)$join_conds_safe[$table][1], LIST_AND ) . ')';
				$retJOIN[] = $tableClause;
			} else {
				$tableClause = $this->tableName( $table );
				$ret[] = $tableClause;
			}
		}
		// We can't separate explicit JOIN clauses with ',', use ' ' for those
		$straightJoins = !empty( $ret ) ? implode( ',', $ret ) : "";
		$otherJoins = !empty( $retJOIN ) ? implode( ' ', $retJOIN ) : "";
		// Compile our final table clause
		return implode( ' ', array( $straightJoins, $otherJoins ) );
	}

	function strencode( $s ) { # Should not be called by us
		return str_replace( "'", "''", $s );
	}

	function addQuotes( $s ) {
		if ( $s instanceof Blob ) {
			return "'" . $s->fetch( $s ) . "'";
		} else {
			return parent::addQuotes( $s );
		}
	}

	function quote_ident( $s ) {
		return "'" . str_replace( "'", "''", $s ) . "'";
	}

	function selectDB( $db ) {
		return ( $this->query( "SET DATABASE $db" ) !== false );
	}

	/**
	 * @private
	 *
	 * @param $options Array: an associative array of options to be turned into
	 *                 an SQL query, valid keys are listed in the function.
	 * @return Array
	 */
	function makeSelectOptions( $options ) {
		$tailOpts = '';
		$startOpts = '';

		$noKeyOptions = array();
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		if ( isset( $options['GROUP BY'] ) ) $tailOpts .= " GROUP BY {$options['GROUP BY']}";
		if ( isset( $options['HAVING'] ) )   $tailOpts .= " HAVING {$options['GROUP BY']}";
		if ( isset( $options['ORDER BY'] ) ) $tailOpts .= " ORDER BY {$options['ORDER BY']}";

		if ( isset( $noKeyOptions['DISTINCT'] ) && isset( $noKeyOptions['DISTINCTROW'] ) ) $startOpts .= 'DISTINCT';

		// we want this to be compatible with the output of parent::makeSelectOptions()
		return array( $startOpts, '' , $tailOpts, '' );
	}

	/**
	 * Get the type of the DBMS, as it appears in $wgDBtype.
	 */
	function getType(){
		return 'mssql';
	}

	function buildConcat( $stringList ) {
		return implode( ' + ', $stringList );
	}

	public function getSearchEngine() {
		return "SearchMssql";
	}

} // end DatabaseMssql class

/**
 * Utility class.
 *
 * @ingroup Database
 */
class MssqlField implements Field {
	private $name, $tablename, $default, $max_length, $nullable, $type;
	function __construct ( $info ) {
		$this->name = $info['COLUMN_NAME'];
		$this->tablename = $info['TABLE_NAME'];
		$this->default = $info['COLUMN_DEFAULT'];
		$this->max_length = $info['CHARACTER_MAXIMUM_LENGTH'];
		$this->nullable = ( strtolower( $info['IS_NULLABLE'] ) == 'no' ) ? false:true;
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

/**
 * The MSSQL PHP driver doesn't support sqlsrv_num_rows, so we recall all rows into an array and maintain our
 * own cursor index into that array...This is similar to the way the Oracle driver handles this same issue
 *
 * @ingroup Database
 */
class MssqlResult {

  public function __construct( $queryresult = false ) {
	$this->mCursor = 0;
	$this->mRows = array();
	$this->mNumFields = sqlsrv_num_fields( $queryresult );
	$this->mFieldMeta = sqlsrv_field_metadata( $queryresult );
	while ( $row = sqlsrv_fetch_array( $queryresult, SQLSRV_FETCH_ASSOC ) ) {
		if ( $row !== null ) {
			foreach ( $row as $k => $v ) {
				if ( is_object( $v ) && method_exists( $v, 'format' ) ) {// DateTime Object
					$row[$k] = $v->format( "Y-m-d\TH:i:s\Z" );
				}
			}
			$this->mRows[] = $row;// read results into memory, cursors are not supported
		}
	}
	$this->mRowCount = count( $this->mRows );
	sqlsrv_free_stmt( $queryresult );
  }

  private function array_to_obj( $array, &$obj ) {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$obj->$key = new stdClass();
				array_to_obj( $value, $obj->$key );
			} else {
				if ( !empty( $key ) ) {
					$obj->$key = $value;
				}
			}
		}
		return $obj;
  }

  public function fetch( $mode = SQLSRV_FETCH_BOTH, $object_class = 'stdClass' ) {
	if ( $this->mCursor >= $this->mRowCount || $this->mRowCount == 0 ) {
		return false;
	}
	$arrNum = array();
	if ( $mode == SQLSRV_FETCH_NUMERIC || $mode == SQLSRV_FETCH_BOTH ) {
		foreach ( $this->mRows[$this->mCursor] as $value ) {
			$arrNum[] = $value;
		}
	}
	switch( $mode ) {
		case SQLSRV_FETCH_ASSOC:
			$ret = $this->mRows[$this->mCursor];
			break;
		case SQLSRV_FETCH_NUMERIC:
			$ret = $arrNum;
			break;
		case 'OBJECT':
			$o = new $object_class;
			$ret = $this->array_to_obj( $this->mRows[$this->mCursor], $o );
			break;
		case SQLSRV_FETCH_BOTH:
		default:
			$ret = $this->mRows[$this->mCursor] + $arrNum;
			break;
	}

	$this->mCursor++;
	return $ret;
  }

  public function get( $pos, $fld ) {
	return $this->mRows[$pos][$fld];
  }

  public function numrows() {
	return $this->mRowCount;
  }

  public function seek( $iRow ) {
	$this->mCursor = min( $iRow, $this->mRowCount );
  }

  public function numfields() {
	return $this->mNumFields;
  }

  public function fieldname( $nr ) {
	$arrKeys = array_keys( $this->mRows[0] );
	return $arrKeys[$nr];
  }

  public function fieldtype( $nr ) {
	$i = 0;
	$intType = -1;
	foreach ( $this->mFieldMeta as $meta ) {
		if ( $nr == $i ) {
			$intType = $meta['Type'];
			break;
		}
		$i++;
	}
	// http://msdn.microsoft.com/en-us/library/cc296183.aspx contains type table
	switch( $intType ) {
		case SQLSRV_SQLTYPE_BIGINT: 		$strType = 'bigint'; break;
		case SQLSRV_SQLTYPE_BINARY: 		$strType = 'binary'; break;
		case SQLSRV_SQLTYPE_BIT: 			$strType = 'bit'; break;
		case SQLSRV_SQLTYPE_CHAR: 			$strType = 'char'; break;
		case SQLSRV_SQLTYPE_DATETIME: 		$strType = 'datetime'; break;
		case SQLSRV_SQLTYPE_DECIMAL/*($precision, $scale)*/: $strType = 'decimal'; break;
		case SQLSRV_SQLTYPE_FLOAT: 			$strType = 'float'; break;
		case SQLSRV_SQLTYPE_IMAGE: 			$strType = 'image'; break;
		case SQLSRV_SQLTYPE_INT: 			$strType = 'int'; break;
		case SQLSRV_SQLTYPE_MONEY: 			$strType = 'money'; break;
		case SQLSRV_SQLTYPE_NCHAR/*($charCount)*/: $strType = 'nchar'; break;
		case SQLSRV_SQLTYPE_NUMERIC/*($precision, $scale)*/: $strType = 'numeric'; break;
		case SQLSRV_SQLTYPE_NVARCHAR/*($charCount)*/: $strType = 'nvarchar'; break;
		// case SQLSRV_SQLTYPE_NVARCHAR('max'): $strType = 'nvarchar(MAX)'; break;
		case SQLSRV_SQLTYPE_NTEXT: 			$strType = 'ntext'; break;
		case SQLSRV_SQLTYPE_REAL: 			$strType = 'real'; break;
		case SQLSRV_SQLTYPE_SMALLDATETIME: 	$strType = 'smalldatetime'; break;
		case SQLSRV_SQLTYPE_SMALLINT: 		$strType = 'smallint'; break;
		case SQLSRV_SQLTYPE_SMALLMONEY: 	$strType = 'smallmoney'; break;
		case SQLSRV_SQLTYPE_TEXT: 			$strType = 'text'; break;
		case SQLSRV_SQLTYPE_TIMESTAMP: 		$strType = 'timestamp'; break;
		case SQLSRV_SQLTYPE_TINYINT: 		$strType = 'tinyint'; break;
		case SQLSRV_SQLTYPE_UNIQUEIDENTIFIER: $strType = 'uniqueidentifier'; break;
		case SQLSRV_SQLTYPE_UDT: 			$strType = 'UDT'; break;
		case SQLSRV_SQLTYPE_VARBINARY/*($byteCount)*/: $strType = 'varbinary'; break;
		// case SQLSRV_SQLTYPE_VARBINARY('max'): $strType = 'varbinary(MAX)'; break;
		case SQLSRV_SQLTYPE_VARCHAR/*($charCount)*/: $strType = 'varchar'; break;
		// case SQLSRV_SQLTYPE_VARCHAR('max'): $strType = 'varchar(MAX)'; break;
		case SQLSRV_SQLTYPE_XML: 			$strType = 'xml'; break;
		default: $strType = $intType;
	}
	return $strType;
  }

  public function free() {
	unset( $this->mRows );
	return;
  }

}
