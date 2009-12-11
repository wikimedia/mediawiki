<?php
/**
 * @ingroup Database
 * @file
 */

/**
 * This is the Oracle database abstraction layer.
 * @ingroup Database
 */
class ORABlob {
	var $mData;

	function __construct( $data ) {
		$this->mData = $data;
	}

	function getData() {
		return $this->mData;
	}
}

/**
 * The oci8 extension is fairly weak and doesn't support oci_num_rows, among
 * other things.  We use a wrapper class to handle that and other
 * Oracle-specific bits, like converting column names back to lowercase.
 * @ingroup Database
 */
class ORAResult {
	private $rows;
	private $cursor;
	private $stmt;
	private $nrows;

	private $unique;
	private function array_unique_md( $array_in ) {
		$array_out = array();
		$array_hashes = array();

		foreach ( $array_in as $key => $item ) {
			$hash = md5( serialize( $item ) );
			if ( !isset( $array_hashes[$hash] ) ) {
				$array_hashes[$hash] = $hash;
				$array_out[] = $item;
			}
		}
		
		return $array_out;
	}
	
	function __construct( &$db, $stmt, $unique = false ) {
		$this->db =& $db;

		if ( ( $this->nrows = oci_fetch_all( $stmt, $this->rows, 0, - 1, OCI_FETCHSTATEMENT_BY_ROW | OCI_NUM ) ) === false ) {
			$e = oci_error( $stmt );
			$db->reportQueryError( $e['message'], $e['code'], '', __FUNCTION__ );
			return;
		}

		if ( $unique ) {
			$this->rows = $this->array_unique_md( $this->rows );
			$this->nrows = count( $this->rows );
		}

		$this->cursor = 0;
		$this->stmt = $stmt;
	}

	public function free() {
		oci_free_statement( $this->stmt );
	}

	public function seek( $row ) {
		$this->cursor = min( $row, $this->nrows );
	}

	public function numRows() {
		return $this->nrows;
	}

	public function numFields() {
		return oci_num_fields( $this->stmt );
	}

	public function fetchObject() {
		if ( $this->cursor >= $this->nrows )
			return false;
		$row = $this->rows[$this->cursor++];
		$ret = new stdClass();
		foreach ( $row as $k => $v ) {
			$lc = strtolower( oci_field_name( $this->stmt, $k + 1 ) );
			$ret->$lc = $v;
		}

		return $ret;
	}

	public function fetchRow() {
		if ( $this->cursor >= $this->nrows )
			return false;

		$row = $this->rows[$this->cursor++];
		$ret = array();
		foreach ( $row as $k => $v ) {
			$lc = strtolower( oci_field_name( $this->stmt, $k + 1 ) );
			$ret[$lc] = $v;
			$ret[$k] = $v;
		}
		return $ret;
	}
}

/**
 * Utility class.
 * @ingroup Database
 */
class ORAField {
	private $name, $tablename, $default, $max_length, $nullable,
		$is_pk, $is_unique, $is_multiple, $is_key, $type;

	function __construct( $info ) {
		$this->name = $info['column_name'];
		$this->tablename = $info['table_name'];
		$this->default = $info['data_default'];
		$this->max_length = $info['data_length'];
		$this->nullable = $info['not_null'];
		$this->is_pk = isset( $info['prim'] ) && $info['prim'] == 1 ? 1 : 0;
		$this->is_unique = isset( $info['uniq'] ) && $info['uniq'] == 1 ? 1 : 0;
		$this->is_multiple = isset( $info['nonuniq'] ) && $info['nonuniq'] == 1 ? 1 : 0;
		$this->is_key = ( $this->is_pk || $this->is_unique || $this->is_multiple );
		$this->type = $info['data_type'];
	}
 
	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tablename;
	}

	function defaultValue() {
		return $this->default;
	}

	function maxLength() {
		return $this->max_length;
	}

	function nullable() {
		return $this->nullable;
	}
	
	function isKey() {
		return $this->is_key;
	}

	function isMultipleKey() {
		return $this->is_multiple;
	}

	function type() {
		return $this->type;
	}
}

/**
 * @ingroup Database
 */
class DatabaseOracle extends DatabaseBase {
	var $mInsertId = NULL;
	var $mLastResult = NULL;
	var $numeric_version = NULL;
	var $lastResult = null;
	var $cursor = 0;
	var $mAffectedRows;

	var $ignore_DUP_VAL_ON_INDEX = false;
	var $sequenceData = null;

	var $defaultCharset = 'AL32UTF8';

	function DatabaseOracle( $server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		$tablePrefix = $tablePrefix == 'get from global' ? $tablePrefix : strtoupper( $tablePrefix );
		parent::__construct( $server, $user, $password, $dbName, $failFunction, $flags, $tablePrefix );
		wfRunHooks( 'DatabaseOraclePostInit', array( &$this ) );
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
	function searchableIPs() {
		return true;
	}

	static function newFromParams( $server, $user, $password, $dbName, $failFunction = false, $flags = 0 )
	{
		return new DatabaseOracle( $server, $user, $password, $dbName, $failFunction, $flags );
	}

	/**
	 * Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 */
	function open( $server, $user, $password, $dbName ) {
		if ( !function_exists( 'oci_connect' ) ) {
			throw new DBConnectionError( $this, "Oracle functions missing, have you compiled PHP with the --with-oci8 option?\n (Note: if you recently installed PHP, you may need to restart your webserver and database)\n" );
		}
		
		// putenv("NLS_LANG=AMERICAN_AMERICA.AL32UTF8");

		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		if ( !strlen( $user ) ) { # # e.g. the class is being loaded
		return;
		}

		$session_mode = $this->mFlags & DBO_SYSDBA ? OCI_SYSDBA : OCI_DEFAULT;
		if ( $this->mFlags & DBO_DEFAULT )
			$this->mConn = oci_new_connect( $user, $password, $dbName, $this->defaultCharset, $session_mode );
		else
			$this->mConn = oci_connect( $user, $password, $dbName, $this->defaultCharset, $session_mode );

		if ( $this->mConn == false ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError() . "\n" );
			return false;
		}

		$this->mOpened = true;
		
		# removed putenv calls because they interfere with the system globaly
		$this->doQuery( 'ALTER SESSION SET NLS_TIMESTAMP_FORMAT=\'DD-MM-YYYY HH24:MI:SS.FF6\'' );
		$this->doQuery( 'ALTER SESSION SET NLS_TIMESTAMP_TZ_FORMAT=\'DD-MM-YYYY HH24:MI:SS.FF6\'' );
		return $this->mConn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 */
	function close() {
		$this->mOpened = false;
		if ( $this->mConn ) {
			return oci_close( $this->mConn );
		} else {
			return true;
		}
	}

	function execFlags() {
		return $this->mTrxLevel ? OCI_DEFAULT : OCI_COMMIT_ON_SUCCESS;
	}

	function doQuery( $sql ) {
		wfDebug( "SQL: [$sql]\n" );
		if ( !mb_check_encoding( $sql ) ) {
			throw new MWException( "SQL encoding is invalid\n$sql" );
		}

		// handle some oracle specifics
		// remove AS column/table/subquery namings
		if ( !defined( 'MEDIAWIKI_INSTALL' ) )
			$sql = preg_replace( '/ as /i', ' ', $sql );
		// Oracle has issues with UNION clause if the statement includes LOB fields
		// So we do a UNION ALL and then filter the results array with array_unique
		$union_unique = ( preg_match( '/\/\* UNION_UNIQUE \*\/ /', $sql ) != 0 );
		// EXPLAIN syntax in Oracle is EXPLAIN PLAN FOR and it return nothing
		// you have to select data from plan table after explain
		$olderr = error_reporting( E_ERROR );
		$explain_id = date( 'dmYHis' );
		error_reporting( $olderr );

		$sql = preg_replace( '/^EXPLAIN /', 'EXPLAIN PLAN SET STATEMENT_ID = \'' . $explain_id . '\' FOR', $sql, 1, $explain_count );
			
		
		$olderr = error_reporting( E_ERROR );
		
		if ( ( $this->mLastResult = $stmt = oci_parse( $this->mConn, $sql ) ) === false ) {
			$e = oci_error( $this->mConn );
			$this->reportQueryError( $e['message'], $e['code'], $sql, __FUNCTION__ );
		}

		$olderr = error_reporting( E_ERROR );
		if ( oci_execute( $stmt, $this->execFlags() ) == false ) {
			$e = oci_error( $stmt );
			if ( !$this->ignore_DUP_VAL_ON_INDEX || $e['code'] != '1' )
				$this->reportQueryError( $e['message'], $e['code'], $sql, __FUNCTION__ );
		}
		error_reporting( $olderr );

		if ( $explain_count > 0 ) {
			return $this->doQuery( 'SELECT id, cardinality "ROWS" FROM plan_table WHERE statement_id = \'' . $explain_id . '\'' );
		} elseif ( oci_statement_type( $stmt ) == "SELECT" ) {
			return new ORAResult( $this, $stmt, $union_unique );
		} else {
			$this->mAffectedRows = oci_num_rows( $stmt );
			return true;
		}
	}

	function queryIgnore( $sql, $fname = '' ) {
		return $this->query( $sql, $fname, true );
	}

	function freeResult( $res ) {
		if ( $res instanceof ORAResult ) {
			$res->free();
		} else {
			$res->result->free();
		}
	}

	function fetchObject( $res ) {
		if ( $res instanceof ORAResult ) {
			return $res->numRows();
		} else {
			return $res->result->fetchObject();
		}
	}

	function fetchRow( $res ) {
		if ( $res instanceof ORAResult ) {
			return $res->fetchRow();
		} else {
			return $res->result->fetchRow();
		}
	}

	function numRows( $res ) {
		if ( $res instanceof ORAResult ) {
			return $res->numRows();
		} else {
			return $res->result->numRows();
		}
	}

	function numFields( $res ) {
		if ( $res instanceof ORAResult ) {
			return $res->numFields();
		} else {
			return $res->result->numFields();
		}
	}

	function fieldName( $stmt, $n ) {
		return oci_field_name( $stmt, $n );
	}

	/**
	 * This must be called after nextSequenceVal
	 */
	function insertId() {
		return $this->mInsertId;
	}

	function dataSeek( $res, $row ) {
		if ( $res instanceof ORAResult ) {
			$res->seek( $row );
		} else {
			$res->result->seek( $row );
		}
	}

	function lastError() {
		if ( $this->mConn === false )
			$e = oci_error();
		else
			$e = oci_error( $this->mConn );
		return $e['message'];
	}

	function lastErrno() {
		if ( $this->mConn === false )
			$e = oci_error();
		else
			$e = oci_error( $this->mConn );
		return $e['code'];
	}

	function affectedRows() {
		return $this->mAffectedRows;
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo( $table, $index, $fname = 'DatabaseOracle::indexExists' ) {
		return false;
	}

	function indexUnique ( $table, $index, $fname = 'DatabaseOracle::indexUnique' ) {
		return false;
	}

	function insert( $table, $a, $fname = 'DatabaseOracle::insert', $options = array() ) {
		if ( !count( $a ) )
			return true;

		if ( !is_array( $options ) )
			$options = array( $options );

		if ( in_array( 'IGNORE', $options ) )
			$this->ignore_DUP_VAL_ON_INDEX = true;

		if ( !is_array( reset( $a ) ) ) {
			$a = array( $a );
		}

		foreach ( $a as &$row ) {
			$this->insertOneRow( $table, $row, $fname );
		}
		$retVal = true;

		if ( in_array( 'IGNORE', $options ) )
			$this->ignore_DUP_VAL_ON_INDEX = false;

		return $retVal;
	}

	function insertOneRow( $table, $row, $fname ) {
		global $wgLang;

		// "INSERT INTO tables (a, b, c)"
		$sql = "INSERT INTO " . $this->tableName( $table ) . " (" . join( ',', array_keys( $row ) ) . ')';
		$sql .= " VALUES (";

		// for each value, append ":key"
		$first = true;
		foreach ( $row as $col => $val ) {
			if ( $first )
				$sql .= $val !== NULL ? ':' . $col : 'NULL';
			else
				$sql .= $val !== NULL ? ', :' . $col : ', NULL';
			
			$first = false;
		}
		$sql .= ')';

		
		$stmt = oci_parse( $this->mConn, $sql );
		foreach ( $row as $col => &$val ) {
			$col_type = $this->fieldInfo( $this->tableName( $table ), $col )->type();
			
			if ( $val === NULL ) {
				// do nothing ... null was inserted in statement creation
			} elseif ( $col_type != 'BLOB' && $col_type != 'CLOB' ) {
				if ( is_object( $val ) )
					$val = $val->getData();
			
				if ( preg_match( '/^timestamp.*/i', $col_type ) == 1 && strtolower( $val ) == 'infinity' )
					$val = '31-12-2030 12:00:00.000000';
				
				$val = ( $wgLang != null ) ? $wgLang->checkTitleEncoding( $val ) : $val;
				if ( oci_bind_by_name( $stmt, ":$col", $val ) === false )
					$this->reportQueryError( $this->lastErrno(), $this->lastError(), $sql, __METHOD__ );
			} else {
				if ( ( $lob[$col] = oci_new_descriptor( $this->mConn, OCI_D_LOB ) ) === false ) {
					$e = oci_error( $stmt );
					throw new DBUnexpectedError( $this, "Cannot create LOB descriptor: " . $e['message'] );
				}
					
				if ( $col_type == 'BLOB' ) { // is_object($val)) {
					$lob[$col]->writeTemporary( $val ); // ->getData());
					oci_bind_by_name( $stmt, ":$col", $lob[$col], - 1, SQLT_BLOB );
				} else {
					$lob[$col]->writeTemporary( $val );
					oci_bind_by_name( $stmt, ":$col", $lob[$col], - 1, OCI_B_CLOB );
				}
			}
		}

		$olderr = error_reporting( E_ERROR );
		if ( oci_execute( $stmt, OCI_DEFAULT ) === false ) {
			$e = oci_error( $stmt );
			
			if ( !$this->ignore_DUP_VAL_ON_INDEX || $e['code'] != '1' )
				$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );
			else
				$this->mAffectedRows = oci_num_rows( $stmt );
		} else
			$this->mAffectedRows = oci_num_rows( $stmt );
		error_reporting( $olderr );
		
		if ( isset( $lob ) ) {
			foreach ( $lob as $lob_i => $lob_v ) {
				$lob_v->free();
			}
		}
		
		if ( !$this->mTrxLevel )
			oci_commit( $this->mConn );
		
		oci_free_statement( $stmt );
	}

	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'DatabaseOracle::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$destTable = $this->tableName( $destTable );
		if ( !is_array( $selectOptions ) ) {
			$selectOptions = array( $selectOptions );
		}
		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );
		if ( is_array( $srcTable ) ) {
			$srcTable =  implode( ',', array_map( array( &$this, 'tableName' ), $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}
		
		if ( ( $sequenceData = $this->getSequenceData( $destTable ) ) !== false &&
				!isset( $varMap[$sequenceData['column']] ) )
			$varMap[$sequenceData['column']] = 'GET_SEQUENCE_VALUE(\'' . $sequenceData['sequence'] . '\')';
		
		// count-alias subselect fields to avoid abigious definition errors
		$i = 0;
		foreach ( $varMap as $key => &$val )
			$val = $val . ' field' . ( $i++ );
		
		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
			" SELECT $startOpts " . implode( ',', $varMap ) .
			" FROM $srcTable $useIndex ";
		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		$sql .= " $tailOpts";
		
		if ( in_array( 'IGNORE', $insertOptions ) )
			$this->ignore_DUP_VAL_ON_INDEX = true;
		
		$retval = $this->query( $sql, $fname );
		
		if ( in_array( 'IGNORE', $insertOptions ) )
			$this->ignore_DUP_VAL_ON_INDEX = false;
		
		return $retval;
	}

	function tableName( $name ) {
		global $wgSharedDB, $wgSharedPrefix, $wgSharedTables;
		/*
		Replace reserved words with better ones
		Useing uppercase, because that's the only way oracle can handle 
		quoted tablenames
		*/
		switch( $name ) {
			case 'user':
				$name = 'MWUSER'; break;
			case 'text':
				$name = 'PAGECONTENT'; break;
		}

		/*
			The rest of procedure is equal to generic Databse class
			except for the quoting style
		*/
		if ( $name[0] == '"' && substr( $name, - 1, 1 ) == '"' ) return $name;

		if ( preg_match( '/(^|\s)(DISTINCT|JOIN|ON|AS)(\s|$)/i', $name ) !== 0 ) return $name;
		$dbDetails = array_reverse( explode( '.', $name, 2 ) );
		if ( isset( $dbDetails[1] ) ) @list( $table, $database ) = $dbDetails;
		else                         @list( $table ) = $dbDetails;
		
		$prefix = $this->mTablePrefix;
		
		if ( isset( $database ) ) $table = ( $table[0] == '`' ? $table : "`{$table}`" );
		
		if ( !isset( $database )
		 && isset( $wgSharedDB )
		 && $table[0] != '"'
		 && isset( $wgSharedTables )
		 && is_array( $wgSharedTables )
		 && in_array( $table, $wgSharedTables ) ) {
			$database = $wgSharedDB;
			$prefix   = isset( $wgSharedPrefix ) ? $wgSharedPrefix : $prefix;
		}
		
		if ( isset( $database ) ) $database = ( $database[0] == '"' ? $database : "\"{$database}\"" );
		$table = ( $table[0] == '"' ? $table : "\"{$prefix}{$table}\"" );
		
		$tableName = ( isset( $database ) ? "{$database}.{$table}" : "{$table}" );
		
		return strtoupper( $tableName );
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 */
	function nextSequenceValue( $seqName ) {
		$res = $this->query( "SELECT $seqName.nextval FROM dual" );
		$row = $this->fetchRow( $res );
		$this->mInsertId = $row[0];
		$this->freeResult( $res );
		return $this->mInsertId;
	}

	/**
	 * Return sequence_name if table has a sequence
	 */
	function getSequenceData( $table ) {
		if ( $this->sequenceData == NULL ) {
			$result = $this->query( "SELECT lower(us.sequence_name), lower(utc.table_name), lower(utc.column_name) from user_sequences us, user_tab_columns utc where us.sequence_name = utc.table_name||'_'||utc.column_name||'_SEQ'" );
			
			while ( ( $row = $result->fetchRow() ) !== false )
				$this->sequenceData[$this->tableName( $row[1] )] = array( 'sequence' => $row[0], 'column' => $row[2] );
		}
		
		return ( isset( $this->sequenceData[$table] ) ) ? $this->sequenceData[$table] : false;
	}
	

	# REPLACE query wrapper
	# Oracle simulates this with a DELETE followed by INSERT
	# $row is the row to insert, an associative array
	# $uniqueIndexes is an array of indexes. Each element may be either a
	# field name or an array of field names
	#
	# It may be more efficient to leave off unique indexes which are unlikely to collide.
	# However if you do this, you run the risk of encountering errors which wouldn't have
	# occurred in MySQL
	function replace( $table, $uniqueIndexes, $rows, $fname = 'DatabaseOracle::replace' ) {
		$table = $this->tableName( $table );

		if ( count( $rows ) == 0 ) {
			return;
		}

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		$sequenceData = $this->getSequenceData( $table );

		foreach ( $rows as $row ) {
			# Delete rows which collide
			if ( $uniqueIndexes ) {
				$condsDelete = array();
				foreach ( $uniqueIndexes as $index )
					$condsDelete[$index] = $row[$index];
				$this->delete( $table, $condsDelete, $fname );
/*
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
							$sql .= $col.'=' . $this->addQuotes( $row[$col] );
						}
					} else {
						$sql .= $index.'=' . $this->addQuotes( $row[$index] );
					}
				}
				$sql .= ')';

				$this->doQuery( $sql);//, $fname );
*/
			}

			if ( $sequenceData !== false && !isset( $row[$sequenceData['column']] ) )
				$row[$sequenceData['column']] = $this->nextSequenceValue( $sequenceData['sequence'] );

			# Now insert the row
			$this->insert( $table, $row, $fname );
		}
	}

	# DELETE where the condition is a join
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = "DatabaseOracle::deleteJoin" ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this,  'DatabaseOracle::deleteJoin() called with empty $conds' );
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
		$sql = "SELECT t.typname as ftype,a.atttypmod as size
			FROM pg_class c, pg_attribute a, pg_type t
			WHERE relname='$table' AND a.attrelid=c.oid AND
				a.atttypid=t.oid and a.attname='$field'";
		$res = $this->query( $sql );
		$row = $this->fetchObject( $res );
		if ( $row->ftype == "varchar" ) {
			$size = $row->size - 4;
		} else {
			$size = $row->size;
		}
		$this->freeResult( $res );
		return $size;
	}

	function limitResult( $sql, $limit, $offset = false ) {
		if ( $offset === false )
			$offset = 0;
		return "SELECT * FROM ($sql) WHERE rownum >= (1 + $offset) AND rownum < (1 + $limit + $offset)";
	}


	function unionQueries( $sqls, $all ) {
		$glue = ' UNION ALL ';
		return 'SELECT * ' . ( $all ? '':'/* UNION_UNIQUE */ ' ) . 'FROM (' . implode( $glue, $sqls ) . ')' ;
	}

	function wasDeadlock() {
		return $this->lastErrno() == 'OCI-00060';
	}


	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = 'DatabaseOracle::duplicateTableStructure' ) {
		$temporary = $temporary ? 'TRUE' : 'FALSE';
		return $this->query( 'BEGIN DUPLICATE_TABLE(\'' . $oldName . '\', \'' . $newName . '\', ' . $temporary . '); END;', $fname );
	}

	function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_ORACLE, $ts );
	}

	/**
	 * Return aggregated value function call
	 */
	function aggregateValue ( $valuedata, $valuename = 'value' ) {
		return $valuedata;
	}

	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		# Ignore errors during error handling to avoid infinite
		# recursion
		$ignore = $this->ignoreErrors( true );
		++$this->mErrorCount;

		if ( $ignore || $tempIgnore ) {
			wfDebug( "SQL ERROR (ignored): $error\n" );
			$this->ignoreErrors( $ignore );
		}
		else {
			throw new DBQueryError($this, $error, $errno, $sql, $fname);
		}
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	function getSoftwareLink() {
		return "[http://www.oracle.com/ Oracle]";
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		return oci_server_version( $this->mConn );
	}

	/**
	 * Query whether a given table exists (in the given schema, or the default mw one if not given)
	 */
	function tableExists( $table ) {
		$SQL = "SELECT 1 FROM user_tables WHERE table_name='$table'";
		$res = $this->doQuery( $SQL );
		if ( $res ) {
			$count = $res->numRows();
			$res->free();
		} else {
			$count = 0;
		}
		return $count;
	}

	/**
	 * Query whether a given column exists in the mediawiki schema
	 * based on prebuilt table to simulate MySQL field info and keep query speed minimal
	 */
	function fieldExists( $table, $field, $fname = 'DatabaseOracle::fieldExists' ) {
		if ( !isset( $this->fieldInfo_stmt ) )
			$this->fieldInfo_stmt = oci_parse( $this->mConn, 'SELECT * FROM wiki_field_info_full WHERE table_name = upper(:tab) and column_name = UPPER(:col)' );

		oci_bind_by_name( $this->fieldInfo_stmt, ':tab', trim( $table, '"' ) );
		oci_bind_by_name( $this->fieldInfo_stmt, ':col', $field );

		if ( oci_execute( $this->fieldInfo_stmt, OCI_DEFAULT ) === false ) {
			$e = oci_error( $this->fieldInfo_stmt );
			$this->reportQueryError( $e['message'], $e['code'], 'fieldInfo QUERY', __METHOD__ );
			return false;
		}
		$res = new ORAResult( $this, $this->fieldInfo_stmt );
		return $res->numRows() != 0;
	}

	function fieldInfo( $table, $field ) {
		if ( !isset( $this->fieldInfo_stmt ) )
			$this->fieldInfo_stmt = oci_parse( $this->mConn, 'SELECT * FROM wiki_field_info_full WHERE table_name = upper(:tab) and column_name = UPPER(:col)' );

		$table = trim( $table, '"' );
		oci_bind_by_name( $this->fieldInfo_stmt, ':tab', $table );
		oci_bind_by_name( $this->fieldInfo_stmt, ':col', $field );

		if ( oci_execute( $this->fieldInfo_stmt, OCI_DEFAULT ) === false ) {
			$e = oci_error( $this->fieldInfo_stmt );
			$this->reportQueryError( $e['message'], $e['code'], 'fieldInfo QUERY', __METHOD__ );
			return false;
		}
		$res = new ORAResult( $this, $this->fieldInfo_stmt );
		return new ORAField( $res->fetchRow() );
	}

	function begin( $fname = '' ) {
		$this->mTrxLevel = 1;
	}
	function immediateCommit( $fname = '' ) {
		return true;
	}
	function commit( $fname = '' ) {
		oci_commit( $this->mConn );
		$this->mTrxLevel = 0;
	}

	/* Not even sure why this is used in the main codebase... */
	function limitResultForUpdate( $sql, $num ) {
		return $sql;
	}

	/* defines must comply with ^define\s*([^\s=]*)\s*=\s?'\{\$([^\}]*)\}'; */
	function sourceStream( $fp, $lineCallback = false, $resultCallback = false ) {
		$cmd = "";
		$done = false;
		$dollarquote = false;
		
		$replacements = array();

		while ( ! feof( $fp ) ) {
			if ( $lineCallback ) {
				call_user_func( $lineCallback );
			}
			$line = trim( fgets( $fp, 1024 ) );
			$sl = strlen( $line ) - 1;

			if ( $sl < 0 ) { continue; }
			if ( '-' == $line { 0 } && '-' == $line { 1 } ) { continue; }

			// Allow dollar quoting for function declarations
			if ( substr( $line, 0, 8 ) == '/*$mw$*/' ) {
				if ( $dollarquote ) {
					$dollarquote = false;
					$done = true;
				}
				else {
					$dollarquote = true;
				}
			}
			else if ( !$dollarquote ) {
				if ( ';' == $line { $sl } && ( $sl < 2 || ';' != $line { $sl - 1 } ) ) {
					$done = true;
					$line = substr( $line, 0, $sl );
				}
			}

			if ( '' != $cmd ) { $cmd .= ' '; }
			$cmd .= "$line\n";

			if ( $done ) {
				$cmd = str_replace( ';;', ";", $cmd );
				if ( strtolower( substr( $cmd, 0, 6 ) ) == 'define' ) {
					if ( preg_match( '/^define\s*([^\s=]*)\s*=\s*\'\{\$([^\}]*)\}\'/', $cmd, $defines ) ) {
						$replacements[$defines[2]] = $defines[1];
					}
				} else {
					foreach ( $replacements as $mwVar => $scVar ) {
						$cmd = str_replace( '&' . $scVar . '.', '{$' . $mwVar . '}', $cmd );
					}

					$cmd = $this->replaceVars( $cmd );
					$res = $this->query( $cmd, __METHOD__ );
					if ( $resultCallback ) {
						call_user_func( $resultCallback, $res, $this );
					}
	
					if ( false === $res ) {
						$err = $this->lastError();
						return "Query \"{$cmd}\" failed with error code \"$err\".\n";
					}
				}

				$cmd = '';
				$done = false;
			}
		}
		return true;
	}

	function setup_database() {
		global $wgVersion, $wgDBmwschema, $wgDBts2schema, $wgDBport, $wgDBuser;
		
		echo "<li>Creating DB objects</li>\n";
		$res = $this->sourceFile( "../maintenance/ora/tables.sql" );
		
		// Avoid the non-standard "REPLACE INTO" syntax
		echo "<li>Populating table interwiki</li>\n";
		$f = fopen( "../maintenance/interwiki.sql", 'r' );
		if ( $f == false ) {
			dieout( "<li>Could not find the interwiki.sql file</li>" );
		}
		
		// do it like the postgres :D
		$SQL = "INSERT INTO interwiki(iw_prefix,iw_url,iw_local) VALUES ";
		while ( ! feof( $f ) ) {
			$line = fgets( $f, 1024 );
			$matches = array();
			if ( !preg_match( '/^\s*(\(.+?),(\d)\)/', $line, $matches ) ) {
				continue;
			}
			$this->query( "$SQL $matches[1],$matches[2])" );
		}

		echo "<li>Table interwiki successfully populated</li>\n";
	}

	function strencode( $s ) {
		return str_replace( "'", "''", $s );
	}

/*
	function encodeBlob($b) {
		return $b; //new ORABlob($b);
	}
	function decodeBlob($b) {
		return $b; //return $b->load();
	}
*/
	function addQuotes( $s ) {
		global $wgLang;
		if ( isset( $wgLang->mLoaded ) && $wgLang->mLoaded )
			$s = $wgLang->checkTitleEncoding( $s );
		return "'" . $this->strencode( $s ) . "'";
	}

	function quote_ident( $s ) {
		return $s;
	}

	function selectRow( $table, $vars, $conds, $fname = 'DatabaseOracle::selectRow', $options = array(), $join_conds = array() ) {
		global $wgLang;

		$conds2 = array();
		foreach ( $conds as $col => $val ) {
			$col_type = $this->fieldInfo( $this->tableName( $table ), $col )->type();
			if ( $col_type == 'CLOB' )
				$conds2['TO_CHAR(' . $col . ')'] = $wgLang->checkTitleEncoding( $val );
			elseif ( $col_type == 'VARCHAR2' && !mb_check_encoding( $val ) ) {
				$conds2[$col] = $wgLang->checkTitleEncoding( $val );
			} else {
				$conds2[$col] = $val;
			}
		}

		if ( is_array( $table ) )
			foreach ( $table as $tab )
				$tab = $this->tableName( $tab );
		else
			$table = $this->tableName( $table );

		return parent::selectRow( $table, $vars, $conds2, $fname, $options, $join_conds );
	}

	/**
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query
	 *
	 * @private
	 *
	 * @param $options Array: an associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions( $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = '';

		$noKeyOptions = array();
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		if ( isset( $options['GROUP BY'] ) ) $preLimitTail .= " GROUP BY {$options['GROUP BY']}";
		if ( isset( $options['ORDER BY'] ) ) $preLimitTail .= " ORDER BY {$options['ORDER BY']}";

		# if ( isset( $noKeyOptions['FOR UPDATE'] ) ) $tailOpts .= ' FOR UPDATE';
		# if ( isset( $noKeyOptions['LOCK IN SHARE MODE'] ) ) $tailOpts .= ' LOCK IN SHARE MODE';
		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) $startOpts .= 'DISTINCT';

		if ( isset( $options['USE INDEX'] ) && ! is_array( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}

		return array( $startOpts, $useIndex, $preLimitTail, $postLimitTail );
	}

	public function delete( $table, $conds, $fname = 'DatabaseOracle::delete' ) {
		global $wgLang;

		if ( $wgLang != null ) {
			$conds2 = array();
			foreach ( $conds as $col => $val ) {
				$col_type = $this->fieldInfo( $this->tableName( $table ), $col )->type();
				if ( $col_type == 'CLOB' )
					$conds2['TO_CHAR(' . $col . ')'] = $wgLang->checkTitleEncoding( $val );
				else
					if ( is_array( $val ) ) {
						$conds2[$col] = $val;
						foreach ( $conds2[$col] as &$val2 )
							$val2 = $wgLang->checkTitleEncoding( $val2 );
					} else {
						$conds2[$col] = $wgLang->checkTitleEncoding( $val );
					}
			}
		
			return parent::delete( $table, $conds2, $fname );
		} else return parent::delete( $table, $conds, $fname );
	}

	function bitNot( $field ) {
		// expecting bit-fields smaller than 4bytes
		return 'BITNOT(' . $bitField . ')';
	}

	function bitAnd( $fieldLeft, $fieldRight ) {
		return 'BITAND(' . $fieldLeft . ', ' . $fieldRight . ')';
	}

	function bitOr( $fieldLeft, $fieldRight ) {
		return 'BITOR(' . $fieldLeft . ', ' . $fieldRight . ')';
	}

	/**
	 * How lagged is this slave?
	 *
	 * @return int
	 */
	public function getLag() {
		# Not implemented for Oracle
		return 0;
	}

	function setFakeSlaveLag( $lag ) { }
	function setFakeMaster( $enabled = true ) { }

	function getDBname() {
		return $this->mDBname;
	}

	function getServer() {
		return $this->mServer;
	}
	
	public function replaceVars( $ins ) {
		$varnames = array( 'wgDBprefix' );
		if ( $this->mFlags & DBO_SYSDBA ) {
			$varnames[] = 'wgDBOracleDefTS';
			$varnames[] = 'wgDBOracleTempTS';
		}

		// Ordinary variables
		foreach ( $varnames as $var ) {
			if ( isset( $GLOBALS[$var] ) ) {
				$val = addslashes( $GLOBALS[$var] ); // FIXME: safety check?
				$ins = str_replace( '{$' . $var . '}', $val, $ins );
				$ins = str_replace( '/*$' . $var . '*/`', '`' . $val, $ins );
				$ins = str_replace( '/*$' . $var . '*/', $val, $ins );
			}
		}

		return parent::replaceVars( $ins );
	}

	public function getSearchEngine() {
		return "SearchOracle";
	}
} // end DatabaseOracle class
