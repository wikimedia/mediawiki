<?php
/**
 * This script is the IBM DB2 database abstraction layer
 *
 * See maintenance/ibm_db2/README for development notes and other specific information
 * @ingroup Database
 * @file
 * @author leo.petr+mediawiki@gmail.com
 */

/**
 * Utility class for generating blank objects
 * Intended as an equivalent to {} in Javascript
 * @ingroup Database
 */
class BlankObject {
}

/**
 * This represents a column in a DB2 database
 * @ingroup Database
 */
class IBM_DB2Field {
	private $name, $tablename, $type, $nullable, $max_length;

	/**
	 * Builder method for the class 
	 * @param Object $db Database interface
	 * @param string $table table name
	 * @param string $field column name
	 * @return IBM_DB2Field
	 */
	static function fromText($db, $table, $field) {
		global $wgDBmwschema;

		$q = <<<END
SELECT
lcase(coltype) AS typname,
nulls AS attnotnull, length AS attlen
FROM sysibm.syscolumns
WHERE tbcreator=%s AND tbname=%s AND name=%s;
END;
		$res = $db->query(sprintf($q,
				$db->addQuotes($wgDBmwschema),
				$db->addQuotes($table),
				$db->addQuotes($field)));
		$row = $db->fetchObject($res);
		if (!$row)
			return null;
		$n = new IBM_DB2Field;
		$n->type = $row->typname;
		$n->nullable = ($row->attnotnull == 'N');
		$n->name = $field;
		$n->tablename = $table;
		$n->max_length = $row->attlen;
		return $n;
	}
	/**
	 * Get column name
	 * @return string column name
	 */
	function name() { return $this->name; }
	/**
	 * Get table name
	 * @return string table name
	 */
	function tableName() { return $this->tablename; }
	/**
	 * Get column type
	 * @return string column type
	 */
	function type() { return $this->type; }
	/**
	 * Can column be null?
	 * @return bool true or false
	 */
	function nullable() { return $this->nullable; }
	/**
	 * How much can you fit in the column per row?
	 * @return int length
	 */
	function maxLength() { return $this->max_length; }
}

/**
 * Wrapper around binary large objects
 * @ingroup Database
 */
class IBM_DB2Blob {
	private $mData;

	function __construct($data) {
		$this->mData = $data;
	}

	function getData() {
		return $this->mData;
	}
}

/**
 * Primary database interface
 * @ingroup Database
 */
class DatabaseIbm_db2 extends Database {
	/*
	 * Inherited members
	protected $mLastQuery = '';
	protected $mPHPError = false;

	protected $mServer, $mUser, $mPassword, $mConn = null, $mDBname;
	protected $mOut, $mOpened = false;

	protected $mFailFunction;
	protected $mTablePrefix;
	protected $mFlags;
	protected $mTrxLevel = 0;
	protected $mErrorCount = 0;
	protected $mLBInfo = array();
	protected $mFakeSlaveLag = null, $mFakeMaster = false;
	 *
	 */
	
	/// Server port for uncataloged connections
	protected $mPort = NULL;
	/// Whether connection is cataloged
	protected $mCataloged = NULL;
	/// Schema for tables, stored procedures, triggers
	protected $mSchema = NULL;
	/// Whether the schema has been applied in this session
	protected $mSchemaSet = false;
	/// Result of last query
	protected $mLastResult = NULL;
	/// Number of rows affected by last INSERT/UPDATE/DELETE
	protected $mAffectedRows = NULL;
	/// Number of rows returned by last SELECT
	protected $mNumRows = NULL;
	
	
	const CATALOGED = "cataloged";
	const UNCATALOGED = "uncataloged";
	const USE_GLOBAL = "get from global";
	
	/// Last sequence value used for a primary key
	protected $mInsertId = NULL;
	
	/*
	 * These can be safely inherited
	 * 
	 * Getter/Setter: (18)
	 * failFunction
	 * setOutputPage
	 * bufferResults
	 * ignoreErrors
	 * trxLevel
	 * errorCount
	 * getLBInfo
	 * setLBInfo
	 * lastQuery
	 * isOpen
	 * setFlag
	 * clearFlag
	 * getFlag
	 * getProperty
	 * getDBname
	 * getServer
	 * tableNameCallback
	 * tablePrefix
	 * 
	 * Administrative: (8)
	 * debug
	 * installErrorHandler
	 * restoreErrorHandler
	 * connectionErrorHandler
	 * reportConnectionError
	 * sourceFile
	 * sourceStream
	 * replaceVars
	 * 
	 * Database: (5)
	 * query
	 * set
	 * selectField
	 * generalizeSQL
	 * update
	 * strreplace
	 * deadlockLoop
	 * 
	 * Prepared Statement: 6
	 * prepare
	 * freePrepared
	 * execute
	 * safeQuery
	 * fillPrepared
	 * fillPreparedArg
	 * 
	 * Slave/Master: (4) 
	 * masterPosWait
	 * getSlavePos
	 * getMasterPos
	 * getLag
	 * 
	 * Generation: (9)
	 * tableNames
	 * tableNamesN
	 * tableNamesWithUseIndexOrJOIN
	 * escapeLike
	 * delete
	 * insertSelect
	 * timestampOrNull
	 * resultObject
	 * aggregateValue
	 * selectSQLText
	 * selectRow
	 * makeUpdateOptions
	 * 
	 * Reflection: (1)
	 * indexExists
	 */
	
	/*
	 * These need to be implemented TODO
	 * 
	 * Administrative: 7 / 7
	 * constructor [Done]
	 * open [Done]
	 * openCataloged [Done]
	 * close [Done]
	 * newFromParams [Done]
	 * openUncataloged [Done]
	 * setup_database [Done]
	 * 
	 * Getter/Setter: 13 / 13
	 * cascadingDeletes [Done]
	 * cleanupTriggers  [Done]
	 * strictIPs  [Done]
	 * realTimestamps  [Done]
	 * impliciGroupby  [Done]
	 * implicitOrderby  [Done]
	 * searchableIPs  [Done]
	 * functionalIndexes  [Done]
	 * getWikiID  [Done]
	 * isOpen [Done]
	 * getServerVersion [Done]
	 * getSoftwareLink [Done]
	 * getSearchEngine [Done]
	 * 
	 * Database driver wrapper: 23 / 23
	 * lastError [Done]
	 * lastErrno [Done]
	 * doQuery [Done]
	 * tableExists [Done]
	 * fetchObject [Done]
	 * fetchRow [Done]
	 * freeResult [Done]
	 * numRows [Done]
	 * numFields [Done]
	 * fieldName [Done]
	 * insertId [Done]
	 * dataSeek [Done]
	 * affectedRows [Done]
	 * selectDB [Done]
	 * strencode [Done]
	 * conditional [Done]
	 * wasDeadlock [Done]
	 * ping [Done]
	 * getStatus [Done]
	 * setTimeout [Done]
	 * lock [Done]
	 * unlock [Done]
	 * insert [Done]
	 * select [Done]
	 * 
	 * Slave/master: 2 / 2
	 * setFakeSlaveLag [Done]
	 * setFakeMaster [Done]
	 * 
	 * Reflection: 6 / 6
	 * fieldExists [Done]
	 * indexInfo [Done]
	 * fieldInfo [Done]
	 * fieldType [Done]
	 * indexUnique [Done]
	 * textFieldSize [Done]
	 * 
	 * Generation: 16 / 16
	 * tableName [Done]
	 * addQuotes [Done]
	 * makeList [Done]
	 * makeSelectOptions [Done]
	 * estimateRowCount [Done]
	 * nextSequenceValue [Done]
	 * useIndexClause [Done]
	 * replace [Done]
	 * deleteJoin [Done]
	 * lowPriorityOption [Done]
	 * limitResult [Done]
	 * limitResultForUpdate [Done]
	 * timestamp [Done]
	 * encodeBlob [Done]
	 * decodeBlob [Done]
	 * buildConcat [Done]
	 */
	
	######################################
	# Getters and Setters
	######################################
	
	/**
	 * Returns true if this database supports (and uses) cascading deletes
	 */
	function cascadingDeletes() {
		return true;
	}

	/**
	 * Returns true if this database supports (and uses) triggers (e.g. on the page table)
	 */
	function cleanupTriggers() {
		return true;
	}

	/**
	 * Returns true if this database is strict about what can be put into an IP field.
	 * Specifically, it uses a NULL value instead of an empty string.
	 */
	function strictIPs() {
		return true;
	}
	
	/**
	 * Returns true if this database uses timestamps rather than integers
	*/
	function realTimestamps() {
		return true;
	}

	/**
	 * Returns true if this database does an implicit sort when doing GROUP BY
	 */
	function implicitGroupby() {
		return false;
	}

	/**
	 * Returns true if this database does an implicit order by when the column has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 */
	function implicitOrderby() {
		return false;
	}

	/**
	 * Returns true if this database can do a native search on IP columns
	 * e.g. this works as expected: .. WHERE rc_ip = '127.42.12.102/32';
	 */
	function searchableIPs() {
		return true;
	}

	/**
	 * Returns true if this database can use functional indexes
	 */
	function functionalIndexes() {
		return true;
	}
	
	/**
	 * Returns a unique string representing the wiki on the server
	 */
	function getWikiID() {
		if( $this->mSchema ) {
			return "{$this->mDBname}-{$this->mSchema}";
		} else {
			return $this->mDBname;
		}
	}
	
	
	######################################
	# Setup
	######################################
	
	
	/**
	 * 
	 * @param string $server hostname of database server
	 * @param string $user username
	 * @param string $password
	 * @param string $dbName database name on the server
	 * @param function $failFunction (optional)
	 * @param integer $flags database behaviour flags (optional, unused)
	 */
	public function DatabaseIbm_db2($server = false, $user = false, $password = false,
							$dbName = false, $failFunction = false, $flags = 0,
							$schema = self::USE_GLOBAL )
	{

		global $wgOut, $wgDBmwschema;
		# Can't get a reference if it hasn't been set yet
		if ( !isset( $wgOut ) ) {
			$wgOut = NULL;
		}
		$this->mOut =& $wgOut;
		$this->mFailFunction = $failFunction;
		$this->mFlags = DBO_TRX | $flags;
		
		if ( $schema == self::USE_GLOBAL ) {
			$this->mSchema = $wgDBmwschema;
		}
		else {
			$this->mSchema = $schema;
		}
		
		$this->open( $server, $user, $password, $dbName);
	}
	
	/**
	 * Opens a database connection and returns it
	 * Closes any existing connection
	 * @return a fresh connection
	 * @param string $server hostname
	 * @param string $user
	 * @param string $password
	 * @param string $dbName database name
	 */
	public function open( $server, $user, $password, $dbName )
	{
		// Load the port number
		global $wgDBport_db2, $wgDBcataloged;
		wfProfileIn( __METHOD__ );
		
		// Load IBM DB2 driver if missing
		if (!@extension_loaded('ibm_db2')) {
			@dl('ibm_db2.so');
		}
		// Test for IBM DB2 support, to avoid suppressed fatal error
		if ( !function_exists( 'db2_connect' ) ) {
			$error = "DB2 functions missing, have you enabled the ibm_db2 extension for PHP?\n";
			wfDebug($error);
			$this->reportConnectionError($error);
		}

		if (!strlen($user)) { // Copied from Postgres
			return null;
		}
		
		// Close existing connection
		$this->close();
		// Cache conn info
		$this->mServer = $server;
		$this->mPort = $port = $wgDBport_db2;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;
		$this->mCataloged = $cataloged = $wgDBcataloged;
		
		if ( $cataloged == self::CATALOGED ) {
			$this->openCataloged($dbName, $user, $password);
		}
		elseif ( $cataloged == self::UNCATALOGED ) {
			$this->openUncataloged($dbName, $user, $password, $server, $port);
		}
		// Don't do this
		// Not all MediaWiki code is transactional
		// Rather, turn it off in the begin function and turn on after a commit
		// db2_autocommit($this->mConn, DB2_AUTOCOMMIT_OFF);
		db2_autocommit($this->mConn, DB2_AUTOCOMMIT_ON);

		if ( $this->mConn == false ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError()."\n" );
			return null;
		}

		$this->mOpened = true;
		$this->applySchema();
		
		wfProfileOut( __METHOD__ );
		return $this->mConn;
	}
	
	/**
	 * Opens a cataloged database connection, sets mConn
	 */
	protected function openCataloged( $dbName, $user, $password )
	{
		@$this->mConn = db2_connect($dbName, $user, $password);
	}
	
	/**
	 * Opens an uncataloged database connection, sets mConn
	 */
	protected function openUncataloged( $dbName, $user, $password, $server, $port )
	{
		$str = "DRIVER={IBM DB2 ODBC DRIVER};";
		$str .= "DATABASE=$dbName;";
		$str .= "HOSTNAME=$server;";
		if ($port) $str .= "PORT=$port;";
		$str .= "PROTOCOL=TCPIP;";
		$str .= "UID=$user;";
		$str .= "PWD=$password;";
		
		@$this->mConn = db2_connect($str, $user, $password);
	}
	
	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 */
	public function close() {
		$this->mOpened = false;
		if ( $this->mConn ) {
			if ($this->trxLevel() > 0) {
				$this->commit();
			}
			return db2_close( $this->mConn );
		}
		else {
			return true;
		}
	}
	
	/**
	 * Returns a fresh instance of this class
	 * @static
	 * @return 
	 * @param string $server hostname of database server
	 * @param string $user username
	 * @param string $password
	 * @param string $dbName database name on the server
	 * @param function $failFunction (optional)
	 * @param integer $flags database behaviour flags (optional, unused)
	 */
	static function newFromParams( $server, $user, $password, $dbName, $failFunction = false, $flags = 0)
	{
		return new DatabaseIbm_db2( $server, $user, $password, $dbName, $failFunction, $flags );
	}
	
	/**
	 * Retrieves the most current database error
	 * Forces a database rollback
	 */
	public function lastError() {
		if ($this->lastError2()) {
			$this->rollback();
			return true;
		}
		return false;
	}
	
	private function lastError2() {
		$connerr = db2_conn_errormsg();
		if ($connerr) return $connerr;
		$stmterr = db2_stmt_errormsg();
		if ($stmterr) return $stmterr;
		if ($this->mConn) return "No open connection.";
		if ($this->mOpened) return "No open connection allegedly.";
		
		return false;
	}
	
	/**
	 * Get the last error number
	 * Return 0 if no error
	 * @return integer
	 */
	public function lastErrno() {
		$connerr = db2_conn_error();
		if ($connerr) return $connerr;
		$stmterr = db2_stmt_error();
		if ($stmterr) return $stmterr;
		return 0;
	}
	
	/**
	 * Is a database connection open?
	 * @return 
	 */
	public function isOpen() { return $this->mOpened; }
	
	/**
	 * The DBMS-dependent part of query()
	 * @param  $sql String: SQL query.
	 * @return object Result object to feed to fetchObject, fetchRow, ...; or false on failure
	 * @access private
	 */
	/*private*/
	public function doQuery( $sql ) {
		//print "<li><pre>$sql</pre></li>";
		// Switch into the correct namespace
		$this->applySchema();
		
		$ret = db2_exec( $this->mConn, $sql );
		if( !$ret ) {
			print "<br><pre>";
			print $sql;
			print "</pre><br>";
			$error = db2_stmt_errormsg();
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( $error ) );
		}
		$this->mLastResult = $ret;
		$this->mAffectedRows = NULL;	// Not calculated until asked for
		return $ret;
	}
	
	/**
	 * @return string Version information from the database
	 */
	public function getServerVersion() {
		$info = db2_server_info( $this->mConn );
		return $info->DBMS_VER;
	}
	
	/**
	 * Queries whether a given table exists
	 * @return boolean
	 */
	public function tableExists( $table ) {
		$schema = $this->mSchema;
		$sql = <<< EOF
SELECT COUNT(*) FROM SYSIBM.SYSTABLES ST
WHERE ST.NAME = '$table' AND ST.CREATOR = '$schema'
EOF;
		$res = $this->query( $sql );
		if (!$res) return false;
		
		// If the table exists, there should be one of it
		@$row = $this->fetchRow($res);
		$count = $row[0];
		if ($count == '1' or $count == 1) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 *
	 * @param $res SQL result object as returned from Database::query(), etc.
	 * @return DB2 row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@$row = db2_fetch_object( $res );
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchObject(): ' . htmlspecialchars( $this->lastError() ) );
		}
		// Make field names lowercase for compatibility with MySQL
		if ($row)
		{
			$row2 = new BlankObject();
			foreach ($row as $key => $value)
			{
				$keyu = strtolower($key);
				$row2->$keyu = $value;
			}
			$row = $row2;
		}
		return $row;
	}

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form.  Fields are retrieved with $row['fieldname'].
	 *
	 * @param $res SQL result object as returned from Database::query(), etc.
	 * @return DB2 row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@$row = db2_fetch_array( $res );
		if ( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchRow(): ' . htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}
	
	/**
	 * Override if introduced to base Database class
	 */
	public function initial_setup() {
		// do nothing
	}
	
	/**
	 * Create tables, stored procedures, and so on
	 */
	public function setup_database() {
		// Timeout was being changed earlier due to mysterious crashes
		// Changing it now may cause more problems than not changing it
		//set_time_limit(240);
		try {
			// TODO: switch to root login if available
			
			// Switch into the correct namespace
			$this->applySchema();
			$this->begin();
			
			$res = dbsource( "../maintenance/ibm_db2/tables.sql", $this);
			$res = null;
	
			// TODO: update mediawiki_version table
			
			// TODO: populate interwiki links
			
			$this->commit();
		}
		catch (MWException $mwe)
		{
			print "<br><pre>$mwe</pre><br>";
		}
	}

	/**
	 * Escapes strings
	 * Doesn't escape numbers
	 * @param string s string to escape
	 * @return escaped string
	 */
	public function addQuotes( $s ) {
		//wfDebug("DB2::addQuotes($s)\n");
		if ( is_null( $s ) ) {
			return "NULL";
		} else if ($s instanceof Blob) {
			return "'".$s->fetch($s)."'";
		}
		$s = $this->strencode($s);
		if ( is_numeric($s) ) {
			return $s;
		}
		else {
			return "'$s'";
		}
	}
	
	/**
	 * Escapes strings
	 * Only escapes numbers going into non-numeric fields
	 * @param string s string to escape
	 * @return escaped string
	 */
	public function addQuotesSmart( $table, $field, $s ) {
		if ( is_null( $s ) ) {
			return "NULL";
		} else if ($s instanceof Blob) {
			return "'".$s->fetch($s)."'";
		}
		$s = $this->strencode($s);
		if ( is_numeric($s) ) {
			// Check with the database if the column is actually numeric
			// This allows for numbers in titles, etc
			$res = $this->doQuery("SELECT $field FROM $table FETCH FIRST 1 ROWS ONLY");
			$type = db2_field_type($res, strtoupper($field));
			if ( $this->is_numeric_type( $type ) ) {
				//wfDebug("DB2: Numeric value going in a numeric column: $s in $type $field in $table\n");
				return $s;
			}
			else {
				wfDebug("DB2: Numeric in non-numeric: '$s' in $type $field in $table\n");
				return "'$s'";
			}
		}
		else {
			return "'$s'";
		}
	}
	
	/**
	 * Verifies that a DB2 column/field type is numeric
	 * @return bool true if numeric
	 * @param string $type DB2 column type
	 */
	public function is_numeric_type( $type ) {
		switch (strtoupper($type)) {
		case 'SMALLINT':
		case 'INTEGER':
		case 'INT':
		case 'BIGINT':
		case 'DECIMAL':
		case 'REAL':
		case 'DOUBLE':
		case 'DECFLOAT':
			return true;
		}
		return false;
	}
	
	/**
	 * Alias for addQuotes()
	 * @param string s string to escape
	 * @return escaped string
	 */
	public function strencode( $s ) {
		// Bloody useless function
		//  Prepends backslashes to \x00, \n, \r, \, ', " and \x1a. 
		//  But also necessary
		$s = db2_escape_string($s);
		// Wide characters are evil -- some of them look like '
		$s = utf8_encode($s);
		// Fix its stupidity
		$from =	array("\\\\",	"\\'",	'\\n',	'\\t',	'\\"',	'\\r');
		$to =	array("\\",		"''",	"\n",	"\t",	'"',	"\r");
		$s = str_replace($from, $to, $s); // DB2 expects '', not \' escaping
		return $s;
	}
	
	/**
	 * Switch into the database schema
	 */
	protected function applySchema() {
		if ( !($this->mSchemaSet) ) {
			$this->mSchemaSet = true;
			$this->begin();
			$this->doQuery("SET SCHEMA = $this->mSchema");
			$this->commit();
		}	
	}
	
	/**
	 * Start a transaction (mandatory)
	 */
	public function begin() {
		// turn off auto-commit
		db2_autocommit($this->mConn, DB2_AUTOCOMMIT_OFF);
		$this->mTrxLevel = 1;
	}
	
	/**
	 * End a transaction
	 * Must have a preceding begin()
	 */
	public function commit() {
		db2_commit($this->mConn);
		// turn auto-commit back on
		db2_autocommit($this->mConn, DB2_AUTOCOMMIT_ON);
		$this->mTrxLevel = 0;
	}
	
	/**
	 * Cancel a transaction
	 */
	public function rollback() {
		db2_rollback($this->mConn);
		// turn auto-commit back on
		// not sure if this is appropriate
		db2_autocommit($this->mConn, DB2_AUTOCOMMIT_ON);
		$this->mTrxLevel = 0;
	}
	
	/**
	 * Makes an encoded list of strings from an array
	 * $mode:
	 *        LIST_COMMA         - comma separated, no field names
	 *        LIST_AND           - ANDed WHERE clause (without the WHERE)
	 *        LIST_OR            - ORed WHERE clause (without the WHERE)
	 *        LIST_SET           - comma separated with field names, like a SET clause
	 *        LIST_NAMES         - comma separated field names
	 */
	public function makeList( $a, $mode = LIST_COMMA ) {
		wfDebug("DB2::makeList()\n");
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this, 'Database::makeList called with incorrect parameters' );
		}

		$first = true;
		$list = '';
		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == LIST_AND ) {
					$list .= ' AND ';
				} elseif($mode == LIST_OR) {
					$list .= ' OR ';
				} else {
					$list .= ',';
				}
			} else {
				$first = false;
			}
			if ( ($mode == LIST_AND || $mode == LIST_OR) && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( ($mode == LIST_SET) && is_numeric( $field ) ) {
				$list .= "$value";
			} elseif ( ($mode == LIST_AND || $mode == LIST_OR) && is_array($value) ) {
				if( count( $value ) == 0 ) {
					throw new MWException( __METHOD__.': empty input' );
				} elseif( count( $value ) == 1 ) {
					// Special-case single values, as IN isn't terribly efficient
					// Don't necessarily assume the single key is 0; we don't
					// enforce linear numeric ordering on other arrays here.
					$value = array_values( $value );
					$list .= $field." = ".$this->addQuotes( $value[0] );
				} else {
					$list .= $field." IN (".$this->makeList($value).") ";
				}
			} elseif( is_null($value) ) {
				if ( $mode == LIST_AND || $mode == LIST_OR ) {
					$list .= "$field IS ";
				} elseif ( $mode == LIST_SET ) {
					$list .= "$field = ";
				}
				$list .= 'NULL';
			} else {
				if ( $mode == LIST_AND || $mode == LIST_OR || $mode == LIST_SET ) {
					$list .= "$field = ";
				}
				if ( $mode == LIST_NAMES ) {
					$list .= $value;
				}
				// Leo: Can't insert quoted numbers into numeric columns
				// (?) Might cause other problems. May have to check column type before insertion.
				else if ( is_numeric($value) ) {
					$list .= $value;
				}
				else {
					$list .= $this->addQuotes( $value );
				}
			}
		}
		return $list;
	}
	
	/**
	 * Makes an encoded list of strings from an array
	 * Quotes numeric values being inserted into non-numeric fields
	 * @return string
	 * @param string $table name of the table
	 * @param array $a list of values
	 * @param $mode:
	 *        LIST_COMMA         - comma separated, no field names
	 *        LIST_AND           - ANDed WHERE clause (without the WHERE)
	 *        LIST_OR            - ORed WHERE clause (without the WHERE)
	 *        LIST_SET           - comma separated with field names, like a SET clause
	 *        LIST_NAMES         - comma separated field names
	 */
	public function makeListSmart( $table, $a, $mode = LIST_COMMA ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this, 'Database::makeList called with incorrect parameters' );
		}

		$first = true;
		$list = '';
		foreach ( $a as $field => $value ) {
			if ( !$first ) {
				if ( $mode == LIST_AND ) {
					$list .= ' AND ';
				} elseif($mode == LIST_OR) {
					$list .= ' OR ';
				} else {
					$list .= ',';
				}
			} else {
				$first = false;
			}
			if ( ($mode == LIST_AND || $mode == LIST_OR) && is_numeric( $field ) ) {
				$list .= "($value)";
			} elseif ( ($mode == LIST_SET) && is_numeric( $field ) ) {
				$list .= "$value";
			} elseif ( ($mode == LIST_AND || $mode == LIST_OR) && is_array($value) ) {
				if( count( $value ) == 0 ) {
					throw new MWException( __METHOD__.': empty input' );
				} elseif( count( $value ) == 1 ) {
					// Special-case single values, as IN isn't terribly efficient
					// Don't necessarily assume the single key is 0; we don't
					// enforce linear numeric ordering on other arrays here.
					$value = array_values( $value );
					$list .= $field." = ".$this->addQuotes( $value[0] );
				} else {
					$list .= $field." IN (".$this->makeList($value).") ";
				}
			} elseif( is_null($value) ) {
				if ( $mode == LIST_AND || $mode == LIST_OR ) {
					$list .= "$field IS ";
				} elseif ( $mode == LIST_SET ) {
					$list .= "$field = ";
				}
				$list .= 'NULL';
			} else {
				if ( $mode == LIST_AND || $mode == LIST_OR || $mode == LIST_SET ) {
					$list .= "$field = ";
				}
				if ( $mode == LIST_NAMES ) {
					$list .= $value;
				}
				else {
					$list .= $this->addQuotesSmart( $table, $field, $value );
				}
			}
		}
		return $list;
	}
	
	/**
	 * Construct a LIMIT query with optional offset
	 * This is used for query pages
	 * $sql string SQL query we will append the limit too
	 * $limit integer the SQL limit
	 * $offset integer the SQL offset (default false)
	 */
	public function limitResult($sql, $limit, $offset=false) {
		if( !is_numeric($limit) ) {
			throw new DBUnexpectedError( $this, "Invalid non-numeric limit passed to limitResult()\n" );
		}
		if( $offset ) {
			wfDebug("Offset parameter not supported in limitResult()\n");
		}
		// TODO implement proper offset handling
		// idea: get all the rows between 0 and offset, advance cursor to offset
		return "$sql FETCH FIRST $limit ROWS ONLY ";
	}
	
	/**
	 * Handle reserved keyword replacement in table names
	 * @return 
	 * @param $name Object
	 */
	public function tableName( $name ) {
		# Replace reserved words with better ones
		switch( $name ) {
			case 'user':
				return 'mwuser';
			case 'text':
				return 'pagecontent';
			default:
				return $name;
		}
	}
	
	/**
	 * Generates a timestamp in an insertable format
	 * @return string timestamp value
	 * @param timestamp $ts
	 */
	public function timestamp( $ts=0 ) {
		// TS_MW cannot be easily distinguished from an integer
		return wfTimestamp(TS_DB2,$ts);
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 * @param string seqName Name of a defined sequence in the database
	 * @return next value in that sequence
	 */
	public function nextSequenceValue( $seqName ) {
		$safeseq = preg_replace( "/'/", "''", $seqName );
		$res = $this->query( "VALUES NEXTVAL FOR $safeseq" );
		$row = $this->fetchRow( $res );
		$this->mInsertId = $row[0];
		$this->freeResult( $res );
		return $this->mInsertId;
	}
	
	/**
	 * This must be called after nextSequenceVal
	 * @return Last sequence value used as a primary key
	 */
	public function insertId() {
		return $this->mInsertId;
	}
	
	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $args may be a single associative array, or an array of these with numeric keys,
	 * for multi-row insert
	 *
	 * @param array $table   String: Name of the table to insert to.
	 * @param array $args    Array: Items to insert into the table.
	 * @param array $fname   String: Name of the function, for profiling
	 * @param mixed $options String or Array. Valid options: IGNORE
	 *
	 * @return bool Success of insert operation. IGNORE always returns true.
	 */
	public function insert( $table, $args, $fname = 'DatabaseIbm_db2::insert', $options = array() ) {
		wfDebug("DB2::insert($table)\n");
		if ( !count( $args ) ) {
			return true;
		}

		$table = $this->tableName( $table );

		if ( !is_array( $options ) )
			$options = array( $options );

		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
		}
		else {
			$args = array($args);
		}
		$keys = array_keys( $args[0] );

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$ignore = in_array( 'IGNORE', $options ) ? 'mw' : '';
		
		// Cache autocommit value at the start
		$oldautocommit = db2_autocommit($this->mConn);

		// If we are not in a transaction, we need to be for savepoint trickery
		$didbegin = 0;
		if (! $this->mTrxLevel) {
			$this->begin();
			$didbegin = 1;
		}
		if ( $ignore ) {
			$olde = error_reporting( 0 );
			// For future use, we may want to track the number of actual inserts
			// Right now, insert (all writes) simply return true/false
			$numrowsinserted = 0;
		}

		$sql = "INSERT INTO $table (" . implode( ',', $keys ) . ') VALUES ';

		if ( !$ignore ) {
			$first = true;
			foreach ( $args as $row ) {
				if ( $first ) {
					$first = false;
				} else {
					$sql .= ',';
				}
				$sql .= '(' . $this->makeListSmart( $table, $row ) . ')';
			}
			$res = (bool)$this->query( $sql, $fname, $ignore );
		}
		else {
			$res = true;
			$origsql = $sql;
			foreach ( $args as $row ) {
				$tempsql = $origsql;
				$tempsql .= '(' . $this->makeListSmart( $table, $row ) . ')';

				if ( $ignore ) {
					db2_exec($this->mConn, "SAVEPOINT $ignore");
				}

				$tempres = (bool)$this->query( $tempsql, $fname, $ignore );

				if ( $ignore ) {
					$bar = db2_stmt_error();
					if ($bar != false) {
						db2_exec( $this->mConn, "ROLLBACK TO SAVEPOINT $ignore" );
					}
					else {
						db2_exec( $this->mConn, "RELEASE SAVEPOINT $ignore" );
						$numrowsinserted++;
					}
				}

				// If any of them fail, we fail overall for this function call
				// Note that this will be ignored if IGNORE is set
				if (! $tempres)
					$res = false;
			}
		}

		if ($didbegin) {
			$this->commit();
		}
		// if autocommit used to be on, it's ok to commit everything
		else if ($oldautocommit)
		{
			$this->commit();
		}
		
		if ( $ignore ) {
			$olde = error_reporting( $olde );
			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;

			// IGNORE always returns true
			return true;
		}
		
		return $res;
	}
	
	/**
	 * UPDATE wrapper, takes a condition array and a SET array
	 *
	 * @param string $table  The table to UPDATE
	 * @param array  $values An array of values to SET
	 * @param array  $conds  An array of conditions (WHERE). Use '*' to update all rows.
	 * @param string $fname  The Class::Function calling this function
	 *                       (for the log)
	 * @param array  $options An array of UPDATE options, can be one or
	 *                        more of IGNORE, LOW_PRIORITY
	 * @return bool
	 */
	function update( $table, $values, $conds, $fname = 'Database::update', $options = array() ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET " . $this->makeListSmart( $table, $values, LIST_SET );
		if ( $conds != '*' ) {
			$sql .= " WHERE " . $this->makeListSmart( $table, $conds, LIST_AND );
		}
		return $this->query( $sql, $fname );
	}
	
	/**
	 * DELETE query wrapper
	 *
	 * Use $conds == "*" to delete all rows
	 */
	function delete( $table, $conds, $fname = 'Database::delete' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this, 'Database::delete() called with no conditions' );
		}
		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";
		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeListSmart( $table, $conds, LIST_AND );
		}
		return $this->query( $sql, $fname );
	}
	
	/**
	 * Returns the number of rows affected by the last query or 0
	 * @return int the number of rows affected by the last query
	 */
	public function affectedRows() {
		if ( !is_null( $this->mAffectedRows ) ) {
			// Forced result for simulated queries
			return $this->mAffectedRows;
		}
		if( empty( $this->mLastResult ) )
			return 0;
		return db2_num_rows( $this->mLastResult );
	}
	
	/**
	 * USE INDEX clause
	 * DB2 doesn't have them and returns ""
	 * @param sting $index
	 */
	public function useIndexClause( $index ) {
		return "";
	}
	
	/**
	 * Simulates REPLACE with a DELETE followed by INSERT
	 * @param $table Object
	 * @param array $uniqueIndexes array consisting of indexes and arrays of indexes
	 * @param array $rows Rows to insert
	 * @param string $fname Name of the function for profiling
	 * @return nothing
	 */
	function replace( $table, $uniqueIndexes, $rows, $fname = 'DatabaseIbm_db2::replace' ) {
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
	
	/**
	 * Returns the number of rows in the result set
	 * Has to be called right after the corresponding select query
	 * @param Object $res result set
	 * @return int number of rows
	 */
	public function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( $this->mNumRows ) {
			return $this->mNumRows;
		}
		else {
			return 0;
		}
	}
	
	/**
	 * Moves the row pointer of the result set
	 * @param Object $res result set
	 * @param int $row row number
	 * @return success or failure
	 */
	public function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return db2_fetch_row( $res, $row );
	}
	
	###
	# Fix notices in Block.php 
	###
	
	/**
	 * Frees memory associated with a statement resource
	 * @param Object $res Statement resource to free
	 * @return bool success or failure
	 */
	public function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( !@db2_free_result( $res ) ) {
			throw new DBUnexpectedError($this,  "Unable to free DB2 result\n" );
		}
	}
	
	/**
	 * Returns the number of columns in a resource
	 * @param Object $res Statement resource
	 * @return Number of fields/columns in resource
	 */
	public function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return db2_num_fields( $res );
	}
	
	/**
	 * Returns the nth column name
	 * @param Object $res Statement resource
	 * @param int $n Index of field or column
	 * @return string name of nth column
	 */
	public function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return db2_field_name( $res, $n );
	}
	
	/**
	 * SELECT wrapper
	 *
	 * @param mixed  $table   Array or string, table name(s) (prefix auto-added)
	 * @param mixed  $vars    Array or string, field name(s) to be retrieved
	 * @param mixed  $conds   Array or string, condition(s) for WHERE
	 * @param string $fname   Calling function name (use __METHOD__) for logs/profiling
	 * @param array  $options Associative array of options (e.g. array('GROUP BY' => 'page_title')),
	 *                        see Database::makeSelectOptions code for list of supported stuff
	 * @param array $join_conds Associative array of table join conditions (optional)
	 *                        (e.g. array( 'page' => array('LEFT JOIN','page_latest=rev_id') )
	 * @return mixed Database result resource (feed to Database::fetchObject or whatever), or false on failure
	 */
	public function select( $table, $vars, $conds='', $fname = 'DatabaseIbm_db2::select', $options = array(), $join_conds = array() )
	{
		$res = parent::select( $table, $vars, $conds, $fname, $options, $join_conds );
		
		// We must adjust for offset
		if ( isset( $options['LIMIT'] ) ) {
			if ( isset ($options['OFFSET'] ) ) {
				$limit = $options['LIMIT'];
				$offset = $options['OFFSET'];
			}
		}
		
		
		// DB2 does not have a proper num_rows() function yet, so we must emulate it
		// DB2 9.5.3/9.5.4 and the corresponding ibm_db2 driver will introduce a working one
		// Yay!
		
		// we want the count
		$vars2 = array('count(*) as num_rows');
		// respecting just the limit option
		$options2 = array();
		if ( isset( $options['LIMIT'] ) ) $options2['LIMIT'] = $options['LIMIT'];
		// but don't try to emulate for GROUP BY
		if ( isset( $options['GROUP BY'] ) ) return $res;
		
		$res2 = parent::select( $table, $vars2, $conds, $fname, $options2, $join_conds );
		$obj = $this->fetchObject($res2);
		$this->mNumRows = $obj->num_rows;
		
		wfDebug("DatabaseIbm_db2::select: There are $this->mNumRows rows.\n");
		
		return $res;
	}
	
	/**
	 * Handles ordering, grouping, and having options ('GROUP BY' => colname)
	 * Has limited support for per-column options (colnum => 'DISTINCT')
	 * 
	 * @private
	 *
	 * @param array $options an associative array of options to be turned into
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
		if ( isset( $options['HAVING'] ) ) $preLimitTail .= " HAVING {$options['HAVING']}";
		if ( isset( $options['ORDER BY'] ) ) $preLimitTail .= " ORDER BY {$options['ORDER BY']}";
		
		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) $startOpts .= 'DISTINCT';
		
		return array( $startOpts, '', $preLimitTail, $postLimitTail );
	}
	
	/**
	 * Returns link to IBM DB2 free download
	 * @return string wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink() {
		return "[http://www.ibm.com/software/data/db2/express/?s_cmp=ECDDWW01&s_tact=MediaWiki IBM DB2]";
	}
	
	/**
	 * Does nothing
	 * @param object $db
	 * @return bool true
	 */
	public function selectDB( $db ) {
		return true;
	}
	
	/**
	 * Returns an SQL expression for a simple conditional.
	 * Uses CASE on DB2
	 *
	 * @param string $cond SQL expression which will result in a boolean value
	 * @param string $trueVal SQL expression to return if true
	 * @param string $falseVal SQL expression to return if false
	 * @return string SQL fragment
	 */
	public function conditional( $cond, $trueVal, $falseVal ) {
		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}
	
	###
	# Fix search crash
	###
	/**
	 * Get search engine class. All subclasses of this
	 * need to implement this if they wish to use searching.
	 * 
	 * @return string
	 */
	public function getSearchEngine() {
		return "SearchIBM_DB2";
	}
	
	###
	# Tuesday the 14th of October, 2008
	###
	/**
	 * Did the last database access fail because of deadlock?
	 * @return bool
	 */
	public function wasDeadlock() {
		// get SQLSTATE
		$err = $this->lastErrno();
		switch($err) {
			case '40001':	// sql0911n, Deadlock or timeout, rollback
			case '57011':	// sql0904n, Resource unavailable, no rollback
			case '57033':	// sql0913n, Deadlock or timeout, no rollback
			wfDebug("In a deadlock because of SQLSTATE $err");
			return true;
		}
		return false;
	}
	
	/**
	 * Ping the server and try to reconnect if it there is no connection
	 * The connection may be closed and reopened while this happens
	 * @return bool whether the connection exists
	 */
	public function ping() {
		// db2_ping() doesn't exist
		// Emulate
		$this->close();
		if ($this->mCataloged == NULL) {
			return false;
		}
		else if ($this->mCataloged) {
			$this->mConn = $this->openCataloged($this->mDBName, $this->mUser, $this->mPassword);
		}
		else if (!$this->mCataloged) {
			$this->mConn = $this->openUncataloged($this->mDBName, $this->mUser, $this->mPassword, $this->mServer, $this->mPort);
		}
		return false;
	}
	######################################
	# Unimplemented and not applicable
	######################################
	/**
	 * Not implemented
	 * @return string ''
	 * @deprecated
	 */
	public function getStatus( $which ) { wfDebug('Not implemented for DB2: getStatus()'); return ''; }
	/**
	 * Not implemented
	 * @deprecated
	 */
	public function setTimeout( $timeout ) { wfDebug('Not implemented for DB2: setTimeout()'); }
	/**
	 * Not implemented
	 * TODO
	 * @return bool true
	 */
	public function lock( $lockName, $method ) { wfDebug('Not implemented for DB2: lock()'); return true; }
	/**
	 * Not implemented
	 * TODO
	 * @return bool true
	 */
	public function unlock( $lockName, $method ) { wfDebug('Not implemented for DB2: unlock()'); return true; }
	/**
	 * Not implemented
	 * @deprecated
	 */
	public function setFakeSlaveLag( $lag ) { wfDebug('Not implemented for DB2: setFakeSlaveLag()'); }
	/**
	 * Not implemented
	 * @deprecated
	 */
	public function setFakeMaster( $enabled ) { wfDebug('Not implemented for DB2: setFakeMaster()'); }
	/**
	 * Not implemented
	 * @return string $sql
	 * @deprecated
	 */ 
	public function limitResultForUpdate($sql, $num) { return $sql; }
	/**
	 * No such option
	 * @return string ''
	 * @deprecated
	 */
	public function lowPriorityOption() { return ''; }
	
	######################################
	# Reflection
	######################################
	
	/**
	 * Query whether a given column exists in the mediawiki schema
	 * @param string $table name of the table
	 * @param string $field name of the column
	 * @param string $fname function name for logging and profiling
	 */
	public function fieldExists( $table, $field, $fname = 'DatabaseIbm_db2::fieldExists' ) {
		$table = $this->tableName( $table );
		$schema = $this->mSchema;
		$etable = preg_replace("/'/", "''", $table);
		$eschema = preg_replace("/'/", "''", $schema);
		$ecol = preg_replace("/'/", "''", $field);
		$sql = <<<SQL
SELECT 1 as fieldexists
FROM sysibm.syscolumns sc
WHERE sc.name='$ecol' AND sc.tbname='$etable' AND sc.tbcreator='$eschema'
SQL;
		$res = $this->query( $sql, $fname );
		$count = $res ? $this->numRows($res) : 0;
		if ($res)
			$this->freeResult( $res );
		return $count;
	}
	
	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 * @param string $table table name
	 * @param string $index index name
	 * @param string
	 * @return object query row in object form
	 */
	public function indexInfo( $table, $index, $fname = 'DatabaseIbm_db2::indexExists' ) {
		$table = $this->tableName( $table );
		$sql = <<<SQL
SELECT name as indexname
FROM sysibm.sysindexes si
WHERE si.name='$index' AND si.tbname='$table' AND sc.tbcreator='$this->mSchema'
SQL;
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return NULL;
		}
		$row = $this->fetchObject( $res );
		if ($row != NULL) return $row;
		else return false;
	}
	
	/**
	 * Returns an information object on a table column
	 * @param string $table table name
	 * @param string $field column name
	 * @return IBM_DB2Field
	 */
	public function fieldInfo( $table, $field ) {
		return IBM_DB2Field::fromText($this, $table, $field);
	}
	
	/**
	 * db2_field_type() wrapper
	 * @param Object $res Result of executed statement
	 * @param mixed $index number or name of the column
	 * @return string column type
	 */
	public function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return db2_field_type( $res, $index );
	}
	
	/**
	 * Verifies that an index was created as unique
	 * @param string $table table name
	 * @param string $index index name
	 * @param string $fnam function name for profiling
	 * @return bool
	 */
	public function indexUnique ($table, $index, $fname = 'Database::indexUnique' ) {
		$table = $this->tableName( $table );
		$sql = <<<SQL
SELECT si.name as indexname
FROM sysibm.sysindexes si
WHERE si.name='$index' AND si.tbname='$table' AND sc.tbcreator='$this->mSchema'
AND si.uniquerule IN ('U', 'P')
SQL;
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		if ($this->fetchObject( $res )) {
			return true;
		}
		return false;

	}
	
	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 * @param string $table table name
	 * @param string $field column name
	 * @return int length or -1 for unlimited
	 */
	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = <<<SQL
SELECT length as size
FROM sysibm.syscolumns sc
WHERE sc.name='$field' AND sc.tbname='$table' AND sc.tbcreator='$this->mSchema'
SQL;
		$res = $this->query($sql);
		$row = $this->fetchObject($res);
		$size = $row->size;
		$this->freeResult( $res );
		return $size;
	}
	
	/**
	 * DELETE where the condition is a join
	 * @param string $delTable deleting from this table
	 * @param string $joinTable using data from this table
	 * @param string $delVar variable in deleteable table
	 * @param string $joinVar variable in data table
	 * @param array $conds conditionals for join table
	 * @param string $fname function name for profiling
	 */
	public function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = "DatabaseIbm_db2::deleteJoin" ) {
		if ( !$conds ) {
			throw new DBUnexpectedError($this,  'Database::deleteJoin() called with empty $conds' );
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
	
	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on COUNT(*) output
	 * Takes same arguments as Database::select()
	 * @param string $table table name
	 * @param array $vars unused
	 * @param array $conds filters on the table
	 * @param string $fname function name for profiling
	 * @param array $options options for select
	 * @return int row count
	 */
	public function estimateRowCount( $table, $vars='*', $conds='', $fname = 'Database::estimateRowCount', $options = array() ) {
		$rows = 0;
		$res = $this->select ($table, 'COUNT(*) as mwrowcount', $conds, $fname, $options );
		if ($res) {
			$row = $this->fetchRow($res);
			$rows = (isset($row['mwrowcount'])) ? $row['mwrowcount'] : 0;
		}
		$this->freeResult($res);
		return $rows;
	}
	
	/**
	 * Description is left as an exercise for the reader
	 * @param mixed $b data to be encoded
	 * @return IBM_DB2Blob
	 */
	public function encodeBlob($b) {
		return new IBM_DB2Blob($b);
	}
	
	/**
	 * Description is left as an exercise for the reader
	 * @param IBM_DB2Blob $b data to be decoded
	 * @return mixed
	 */
	public function decodeBlob($b) {
		return $b->getData();
	}
	
	/**
	 * Convert into a list of string being concatenated
	 * @param array $stringList strings that need to be joined together by the SQL engine
	 * @return string joined by the concatenation operator
	 */
	public function buildConcat( $stringList ) {
		// || is equivalent to CONCAT
		// Sample query: VALUES 'foo' CONCAT 'bar' CONCAT 'baz'
		return implode( ' || ', $stringList );
	}
	
	/**
	 * Generates the SQL required to convert a DB2 timestamp into a Unix epoch
	 * @param string $column name of timestamp column
	 * @return string SQL code
	 */
	public function extractUnixEpoch( $column ) {
		// TODO
		// see SpecialAncientpages
	}
}
?>