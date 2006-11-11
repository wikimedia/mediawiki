<?php

/**
 * Oracle.
 *
 * @package MediaWiki
 */

class OracleBlob extends DBObject {
	function isLOB() {
		return true;
	}
	function data() {
		return $this->mData;
	}
};

/**
 *
 * @package MediaWiki
 */
class DatabaseOracle extends Database {
	var $mInsertId = NULL;
	var $mLastResult = NULL;
	var $mFetchCache = array();
	var $mFetchID = array();
	var $mNcols = array();
	var $mFieldNames = array(), $mFieldTypes = array();
	var $mAffectedRows = array();
	var $mErr;

	function DatabaseOracle($server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		Database::Database( $server, $user, $password, $dbName, $failFunction, $flags, $tablePrefix );
	}

	/* static */ function newFromParams( $server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		return new DatabaseOracle( $server, $user, $password, $dbName, $failFunction, $flags, $tablePrefix );
	}

	/**
	 * Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 */
	function open( $server, $user, $password, $dbName ) {
		if ( !function_exists( 'oci_connect' ) ) {
			throw new DBConnectionError( $this, "Oracle functions missing, have you compiled PHP with the --with-oci8 option?\n" );
		}
		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$success = false;

		$hstring="";
		$this->mConn = oci_new_connect($user, $password, $dbName, "AL32UTF8");
		if ( $this->mConn === false ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: "
				. substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError()."\n" );
		} else {
			$this->mOpened = true;
		}
		return $this->mConn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 */
	function close() {
		$this->mOpened = false;
		if ($this->mConn) {
			return oci_close($this->mConn);
		} else {
			return true;
		}
	}

	function parseStatement($sql) {
		$this->mErr = $this->mLastResult = false;
		if (($stmt = oci_parse($this->mConn, $sql)) === false) {
			$this->lastError();
			return $this->mLastResult = false;
		}
		$this->mAffectedRows[$stmt] = 0;
		return $this->mLastResult = $stmt;
	}

	function doQuery($sql) {
		if (($stmt = $this->parseStatement($sql)) === false)
			return false;
		return $this->executeStatement($stmt);
	}

	function executeStatement($stmt) {
		if (!oci_execute($stmt, OCI_DEFAULT)) {
			$this->lastError();
			oci_free_statement($stmt);
			return false;
		}
		$this->mAffectedRows[$stmt] = oci_num_rows($stmt);
		$this->mFetchCache[$stmt] = array();
		$this->mFetchID[$stmt] = 0;
		$this->mNcols[$stmt] = oci_num_fields($stmt);
		if ($this->mNcols[$stmt] == 0)
			return $this->mLastResult;
		for ($i = 1; $i <= $this->mNcols[$stmt]; $i++) {
			$this->mFieldNames[$stmt][$i] = oci_field_name($stmt, $i);
			$this->mFieldTypes[$stmt][$i] = oci_field_type($stmt, $i);
		}
		while (($o = oci_fetch_array($stmt)) !== false) {
			foreach ($o as $key => $value) {
				if (is_object($value)) {
					$o[$key] = $value->load();
				}
			}
			$this->mFetchCache[$stmt][] = $o;
		}
		return $this->mLastResult;
	}

	function queryIgnore( $sql, $fname = '' ) {
		return $this->query( $sql, $fname, true );
	}

	function freeResult( $res ) {
		if (!oci_free_statement($res)) {
			throw new DBUnexpectedError( $this, "Unable to free Oracle result\n" );
		}
		unset($this->mFetchID[$res]);
		unset($this->mFetchCache[$res]);
		unset($this->mNcols[$res]);
		unset($this->mFieldNames[$res]);
		unset($this->mFieldTypes[$res]);
	}

	function fetchAssoc($res) {
		if ($this->mFetchID[$res] >= count($this->mFetchCache[$res]))
			return false;

		for ($i = 1; $i <= $this->mNcols[$res]; $i++) {
			$name = $this->mFieldNames[$res][$i];
			$type = $this->mFieldTypes[$res][$i];
			if (isset($this->mFetchCache[$res][$this->mFetchID[$res]][$name]))
				$value = $this->mFetchCache[$res][$this->mFetchID[$res]][$name];
			else	$value = NULL;
			$key = strtolower($name);
			wfdebug("'$key' => '$value'\n");
			$ret[$key] = $value;
		}
		$this->mFetchID[$res]++;
		return $ret;
	}

	function fetchRow($res) {
		$r = $this->fetchAssoc($res);
		if (!$r)
			return false;
		$i = 0;
		$ret = array();
		foreach ($r as $key => $value) {
			wfdebug("ret[$i]=[$value]\n");
			$ret[$i++] = $value;
		}
		return $ret;
	}

	function fetchObject($res) {
		$row = $this->fetchAssoc($res);
		if (!$row)
			return false;
		$ret = new stdClass;
		foreach ($row as $key => $value)
			$ret->$key = $value;
		return $ret;
	}

	function numRows($res) {
		return count($this->mFetchCache[$res]);
	}
	function numFields( $res ) { return pg_num_fields( $res ); }
	function fieldName( $res, $n ) { return pg_field_name( $res, $n ); }

	/**
	 * This must be called after nextSequenceVal
	 */
	function insertId() {
		return $this->mInsertId;
	}

	function dataSeek($res, $row) {
		$this->mFetchID[$res] = $row;
	}

	function lastError() {
		if ($this->mErr === false) {
			if ($this->mLastResult !== false) $what = $this->mLastResult;
			else if ($this->mConn !== false) $what = $this->mConn;
			else $what = false;
			$err = ($what !== false) ? oci_error($what) : oci_error();
			if ($err === false)
				$this->mErr = 'no error';
			else
				$this->mErr = $err['message'];
		}
		return str_replace("\n", '<br />', $this->mErr);
	}
	function lastErrno() {
		return 0;
	}

	function affectedRows() {
		return $this->mAffectedRows[$this->mLastResult];
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo ($table, $index, $fname = 'Database::indexInfo' ) {
		$table = $this->tableName($table, true);
		if ($index == 'PRIMARY')
			$index = "${table}_pk";
		$sql = "SELECT uniqueness FROM all_indexes WHERE table_name='" .
			$table . "' AND index_name='" .
			$this->strencode(strtoupper($index)) . "'";
		$res = $this->query($sql, $fname);
		if (!$res)
			return NULL;
		if (($row = $this->fetchObject($res)) == NULL)
			return false;
		$this->freeResult($res);
		$row->Non_unique = !$row->uniqueness;
		return $row;
		
		// BUG: !!!! This code needs to be synced up with database.php
		
	}

	function indexUnique ($table, $index, $fname = 'indexUnique') {
		if (!($i = $this->indexInfo($table, $index, $fname)))
			return $i;
		return $i->uniqueness == 'UNIQUE';
	}

	function fieldInfo( $table, $field ) {
		$o = new stdClass;
		$o->multiple_key = true; /* XXX */
		return $o;
	}

	function getColumnInformation($table, $field) {
		$table = $this->tableName($table, true);
		$field = strtoupper($field);

		$res = $this->doQuery("SELECT * FROM all_tab_columns " .
			"WHERE table_name='".$table."' " .
			"AND   column_name='".$field."'");
		if (!$res)
			return false;
		$o = $this->fetchObject($res);
		$this->freeResult($res);
		return $o;
	}

	function fieldExists( $table, $field, $fname = 'Database::fieldExists' ) {
		$column = $this->getColumnInformation($table, $field);
		if (!$column)
			return false;
		return true;
	}

	function tableName($name, $forddl = false) {
		# First run any transformations from the parent object
		$name = parent::tableName( $name );

		# Replace backticks into empty
		# Note: "foo" and foo are not the same in Oracle!
		$name = str_replace('`', '', $name);

		# Now quote Oracle reserved keywords
		switch( $name ) {
			case 'user':
			case 'group':
			case 'validate':
				if ($forddl)
					return $name;
				else
					return '"' . $name . '"';

			default:
				return strtoupper($name);
		}
	}

	function strencode( $s ) {
		return str_replace("'", "''", $s);
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 */
	function nextSequenceValue( $seqName ) {
		$r = $this->doQuery("SELECT $seqName.nextval AS val FROM dual");
		$o = $this->fetchObject($r);
		$this->freeResult($r);
		return $this->mInsertId = (int)$o->val;
	}

	/**
	 * USE INDEX clause
	 * PostgreSQL doesn't have them and returns ""
	 */
	function useIndexClause( $index ) {
		return '';
	}

	# REPLACE query wrapper
	# PostgreSQL simulates this with a DELETE followed by INSERT
	# $row is the row to insert, an associative array
	# $uniqueIndexes is an array of indexes. Each element may be either a
	# field name or an array of field names
	#
	# It may be more efficient to leave off unique indexes which are unlikely to collide.
	# However if you do this, you run the risk of encountering errors which wouldn't have
	# occurred in MySQL
	function replace( $table, $uniqueIndexes, $rows, $fname = 'Database::replace' ) {
		$table = $this->tableName( $table );

		if (count($rows)==0) {
			return;
		}

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		foreach( $rows as $row ) {
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
							$sql .= $col.'=' . $this->addQuotes( $row[$col] );
						}
					} else {
						$sql .= $index.'=' . $this->addQuotes( $row[$index] );
					}
				}
				$sql .= ')';
				$this->query( $sql, $fname );
			}

			# Now insert the row
			$sql = "INSERT INTO $table (" . $this->makeList( array_keys( $row ), LIST_NAMES ) .') VALUES (' .
				$this->makeList( $row, LIST_COMMA ) . ')';
			$this->query( $sql, $fname );
		}
	}

	# DELETE where the condition is a join
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = "Database::deleteJoin" ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'Database::deleteJoin() called with empty $conds' );
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
		$res =$this->query($sql);
		$row=$this->fetchObject($res);
		if ($row->ftype=="varchar") {
			$size=$row->size-4;
		} else {
			$size=$row->size;
		}
		$this->freeResult( $res );
		return $size;
	}

	function lowPriorityOption() {
		return '';
	}

	function limitResult($sql, $limit, $offset) {
		$ret = "SELECT * FROM ($sql) WHERE ROWNUM < " . ((int)$limit + (int)($offset+1));
		if (is_numeric($offset))
			$ret .= " AND ROWNUM >= " . (int)$offset;
		return $ret;
	}
	function limitResultForUpdate($sql, $limit) {
		return $sql;
	}
	/**
	 * Returns an SQL expression for a simple conditional.
	 * Uses CASE on PostgreSQL.
	 *
	 * @param string $cond SQL expression which will result in a boolean value
	 * @param string $trueVal SQL expression to return if true
	 * @param string $falseVal SQL expression to return if false
	 * @return string SQL fragment
	 */
	function conditional( $cond, $trueVal, $falseVal ) {
		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}

	# FIXME: actually detecting deadlocks might be nice
	function wasDeadlock() {
		return false;
	}

	# Return DB-style timestamp used for MySQL schema
	function timestamp($ts = 0) {
		return $this->strencode(wfTimestamp(TS_ORACLE, $ts));
#		return "TO_TIMESTAMP('" . $this->strencode(wfTimestamp(TS_DB, $ts)) . "', 'RRRR-MM-DD HH24:MI:SS')";
	}

	/**
	 * Return aggregated value function call
	 */
	function aggregateValue ($valuedata,$valuename='value') {
		return $valuedata;
	}


	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		$message = "A database error has occurred\n" .
			"Query: $sql\n" .
			"Function: $fname\n" .
			"Error: $errno $error\n";
		throw new DBUnexpectedError($this, $message);
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
		return oci_server_version($this->mConn);
	}

	function setSchema($schema=false) {
		$schemas=$this->mSchemas;
		if ($schema) { array_unshift($schemas,$schema); }
		$searchpath=$this->makeList($schemas,LIST_NAMES);
		$this->query("SET search_path = $searchpath");
	}

	function begin() {
	}

	function immediateCommit( $fname = 'Database::immediateCommit' ) {
		oci_commit($this->mConn);
		$this->mTrxLevel = 0;
	}
	function rollback( $fname = 'Database::rollback' ) {
		oci_rollback($this->mConn);
		$this->mTrxLevel = 0;
	}
	function getLag() {
		return false;
	}
	function getStatus($which=null) {
		$result = array('Threads_running' => 0, 'Threads_connected' => 0);
		return $result;
	}

	/**
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query
	 *
	 * @access private
	 *
	 * @param array $options an associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions($options) {
		$tailOpts = '';

		if (isset( $options['ORDER BY'])) {
			$tailOpts .= " ORDER BY {$options['ORDER BY']}";
		}

		return array('', $tailOpts);
	}

	function maxListLen() {
		return 1000;
	}

	/**
	 * Query whether a given table exists
	 */
	function tableExists( $table ) {
		$table = $this->tableName($table, true);
		$res = $this->query( "SELECT COUNT(*) as NUM FROM user_tables WHERE table_name='"
			. $table . "'" );
		if (!$res)
			return false;
		$row = $this->fetchObject($res);
		$this->freeResult($res);
		return $row->num >= 1;
	}

	/**
	 * UPDATE wrapper, takes a condition array and a SET array
	 */
	function update( $table, $values, $conds, $fname = 'Database::update' ) {
		$table = $this->tableName( $table );

		$sql = "UPDATE $table SET ";
		$first = true;
		foreach ($values as $field => $v) {
			if ($first)
				$first = false;
			else
				$sql .= ", ";
			$sql .= "$field = :n$field ";
		}
		if ( $conds != '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}
		$stmt = $this->parseStatement($sql);
		if ($stmt === false) {
			$this->reportQueryError( $this->lastError(), $this->lastErrno(), $stmt );
			return false;
		}
		if ($this->debug())
			wfDebug("SQL: $sql\n");
		$s = '';
		foreach ($values as $field => $v) {
			oci_bind_by_name($stmt, ":n$field", $values[$field]);
			if ($this->debug())
				$s .= " [$field] = [$v]\n";
		}
		if ($this->debug())
			wfdebug(" PH: $s\n");
		$ret = $this->executeStatement($stmt);
		return $ret;
	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $a may be a single associative array, or an array of these with numeric keys, for
	 * multi-row insert.
	 *
	 * Usually aborts on failure
	 * If errors are explicitly ignored, returns success
	 */
	function insert( $table, $a, $fname = 'Database::insert', $options = array() ) {
		# No rows to insert, easy just return now
		if ( !count( $a ) ) {
			return true;
		}

		$table = $this->tableName( $table );
		if (!is_array($options))
			$options = array($options);

		$oldIgnore = false;
		if (in_array('IGNORE', $options))
			$oldIgnore = $this->ignoreErrors( true );

		if ( isset( $a[0] ) && is_array( $a[0] ) ) {
			$multi = true;
			$keys = array_keys( $a[0] );
		} else {
			$multi = false;
			$keys = array_keys( $a );
		}

		$sql = "INSERT INTO $table (" . implode( ',', $keys ) . ') VALUES (';
		$return = '';
		$first = true;
		foreach ($a as $key => $value) {
			if ($first)
				$first = false;
			else
				$sql .= ", ";
			if (is_object($value) && $value->isLOB()) {
				$sql .= "EMPTY_BLOB()";
				$return = "RETURNING $key INTO :bobj";
			} else
				$sql .= ":$key";
		}
		$sql .= ") $return";

		if ($this->debug()) {
			wfDebug("SQL: $sql\n");
		}

		if (($stmt = $this->parseStatement($sql)) === false) {
			$this->reportQueryError($this->lastError(), $this->lastErrno(), $sql, $fname);
			$this->ignoreErrors($oldIgnore);
			return false;
		}

		/*
		 * If we're inserting multiple rows, parse the statement once and
		 * execute it for each set of values.  Otherwise, convert it into an
		 * array and pretend.
		 */
		if (!$multi)
			$a = array($a);

		foreach ($a as $key => $row) {
			$blob = false;
			$bdata = false;
			$s = '';
			foreach ($row as $k => $value) {
				if (is_object($value) && $value->isLOB()) {
					$blob = oci_new_descriptor($this->mConn, OCI_D_LOB);
					$bdata = $value->data();
					oci_bind_by_name($stmt, ":bobj", $blob, -1, OCI_B_BLOB);
				} else
					oci_bind_by_name($stmt, ":$k", $a[$key][$k], -1);
				if ($this->debug())
					$s .= " [$k] = {$row[$k]}";
			}
			if ($this->debug())
				wfDebug(" PH: $s\n");
			if (($s = $this->executeStatement($stmt)) === false) {
				$this->reportQueryError($this->lastError(), $this->lastErrno(), $sql, $fname);
				$this->ignoreErrors($oldIgnore);
				return false;
			}

			if ($blob) {
				$blob->save($bdata);
			}
		}
		$this->ignoreErrors($oldIgnore);
		return $this->mLastResult = $s;
	}

	function ping() {
		return true;
	}

	function encodeBlob($b) {
		return new OracleBlob($b);
	}
}

?>
