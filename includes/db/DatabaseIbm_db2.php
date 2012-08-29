<?php
/**
 * This is the IBM DB2 database abstraction layer.
 * See maintenance/ibm_db2/README for development notes
 * and other specific information.
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
 * @author leo.petr+mediawiki@gmail.com
 */

/**
 * This represents a column in a DB2 database
 * @ingroup Database
 */
class IBM_DB2Field implements Field {
	private $name = '';
	private $tablename = '';
	private $type = '';
	private $nullable = false;
	private $max_length = 0;

	/**
	 * Builder method for the class
	 * @param $db DatabaseIbm_db2: Database interface
	 * @param $table String: table name
	 * @param $field String: column name
	 * @return IBM_DB2Field
	 */
	static function fromText( $db, $table, $field ) {
		global $wgDBmwschema;

		$q = <<<SQL
SELECT
lcase( coltype ) AS typname,
nulls AS attnotnull, length AS attlen
FROM sysibm.syscolumns
WHERE tbcreator=%s AND tbname=%s AND name=%s;
SQL;
		$res = $db->query(
			sprintf( $q,
				$db->addQuotes( $wgDBmwschema ),
				$db->addQuotes( $table ),
				$db->addQuotes( $field )
			)
		);
		$row = $db->fetchObject( $res );
		if ( !$row ) {
			return null;
		}
		$n = new IBM_DB2Field;
		$n->type = $row->typname;
		$n->nullable = ( $row->attnotnull == 'N' );
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
	function isNullable() { return $this->nullable; }
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

	public function __construct( $data ) {
		$this->mData = $data;
	}

	public function getData() {
		return $this->mData;
	}

	public function __toString() {
		return $this->mData;
	}
}

/**
 * Wrapper to address lack of certain operations in the DB2 driver
 *  ( seek, num_rows )
 * @ingroup Database
 * @since 1.19
 */
class IBM_DB2Result{
	private $db;
	private $result;
	private $num_rows;
	private $current_pos;
	private $columns = array();
	private $sql;

	private $resultSet = array();
	private $loadedLines = 0;

	/**
	 * Construct and initialize a wrapper for DB2 query results
	 * @param $db DatabaseBase
	 * @param $result Object
	 * @param $num_rows Integer
	 * @param $sql String
	 * @param $columns Array
	 */
	public function __construct( $db, $result, $num_rows, $sql, $columns ){
		$this->db = $db;

		if( $result instanceof ResultWrapper ){
			$this->result = $result->result;
		}
		else{
			$this->result = $result;
		}

		$this->num_rows = $num_rows;
		$this->current_pos = 0;
		if ( $this->num_rows > 0 ) {
			// Make a lower-case list of the column names
			// By default, DB2 column names are capitalized
			//  while MySQL column names are lowercase

			// Is there a reasonable maximum value for $i?
			// Setting to 2048 to prevent an infinite loop
			for( $i = 0; $i < 2048; $i++ ) {
				$name = db2_field_name( $this->result, $i );
				if ( $name != false ) {
					continue;
				}
				else {
					return false;
				}

				$this->columns[$i] = strtolower( $name );
			}
		}

		$this->sql = $sql;
	}

	/**
	 * Unwrap the DB2 query results
	 * @return mixed Object on success, false on failure
	 */
	public function getResult() {
		if ( $this->result ) {
			return $this->result;
		}
		else return false;
	}

	/**
	 * Get the number of rows in the result set
	 * @return integer
	 */
	public function getNum_rows() {
		return $this->num_rows;
	}

	/**
	 * Return a row from the result set in object format
	 * @return mixed Object on success, false on failure.
	 */
	public function fetchObject() {
		if ( $this->result
				&& $this->num_rows > 0
				&& $this->current_pos >= 0
				&& $this->current_pos < $this->num_rows )
		{
			$row = $this->fetchRow();
			$ret = new stdClass();

			foreach ( $row as $k => $v ) {
				$lc = $this->columns[$k];
				$ret->$lc = $v;
			}
			return $ret;
		}
		return false;
	}

	/**
	 * Return a row form the result set in array format
	 * @return mixed Array on success, false on failure
	 * @throws DBUnexpectedError
	 */
	public function fetchRow(){
		if ( $this->result
				&& $this->num_rows > 0
				&& $this->current_pos >= 0
				&& $this->current_pos < $this->num_rows )
		{
			if ( $this->loadedLines <= $this->current_pos ) {
				$row = db2_fetch_array( $this->result );
				$this->resultSet[$this->loadedLines++] = $row;
				if ( $this->db->lastErrno() ) {
					throw new DBUnexpectedError( $this->db, 'Error in fetchRow(): '
						. htmlspecialchars( $this->db->lastError() ) );
				}
			}

			if ( $this->loadedLines > $this->current_pos ){
				return $this->resultSet[$this->current_pos++];
			}

		}
		return false;
	}

	/**
	 * Free a DB2 result object
	 * @throws DBUnexpectedError
	 */
	public function freeResult(){
		unset( $this->resultSet );
		if ( !@db2_free_result( $this->result ) ) {
			throw new DBUnexpectedError( $this, "Unable to free DB2 result\n" );
		}
	}
}

/**
 * Primary database interface
 * @ingroup Database
 */
class DatabaseIbm_db2 extends DatabaseBase {
	/*
	 * Inherited members
	protected $mLastQuery = '';
	protected $mPHPError = false;

	protected $mServer, $mUser, $mPassword, $mConn = null, $mDBname;
	protected $mOpened = false;

	protected $mTablePrefix;
	protected $mFlags;
	protected $mTrxLevel = 0;
	protected $mErrorCount = 0;
	protected $mLBInfo = array();
	protected $mFakeSlaveLag = null, $mFakeMaster = false;
	 *
	 */

	/** Database server port */
	protected $mPort = null;
	/** Schema for tables, stored procedures, triggers */
	protected $mSchema = null;
	/** Whether the schema has been applied in this session */
	protected $mSchemaSet = false;
	/** Result of last query */
	protected $mLastResult = null;
	/** Number of rows affected by last INSERT/UPDATE/DELETE */
	protected $mAffectedRows = null;
	/** Number of rows returned by last SELECT */
	protected $mNumRows = null;
	/** Current row number on the cursor of the last SELECT */
	protected $currentRow = 0;

	/** Connection config options - see constructor */
	public $mConnOptions = array();
	/** Statement config options -- see constructor */
	public $mStmtOptions = array();

	/** Default schema */
	const USE_GLOBAL = 'get from global';

	/** Option that applies to nothing */
	const NONE_OPTION = 0x00;
	/** Option that applies to connection objects */
	const CONN_OPTION = 0x01;
	/** Option that applies to statement objects */
	const STMT_OPTION = 0x02;

	/** Regular operation mode -- minimal debug messages */
	const REGULAR_MODE = 'regular';
	/** Installation mode -- lots of debug messages */
	const INSTALL_MODE = 'install';

	/** Controls the level of debug message output */
	protected $mMode = self::REGULAR_MODE;

	/** Last sequence value used for a primary key */
	protected $mInsertId = null;

	######################################
	# Getters and Setters
	######################################

	/**
	 * Returns true if this database supports (and uses) cascading deletes
	 * @return bool
	 */
	function cascadingDeletes() {
		return true;
	}

	/**
	 * Returns true if this database supports (and uses) triggers (e.g. on the
	 *  page table)
	 * @return bool
	 */
	function cleanupTriggers() {
		return true;
	}

	/**
	 * Returns true if this database is strict about what can be put into an
	 *  IP field.
	 * Specifically, it uses a NULL value instead of an empty string.
	 * @return bool
	 */
	function strictIPs() {
		return true;
	}

	/**
	 * Returns true if this database uses timestamps rather than integers
	 * @return bool
	 */
	function realTimestamps() {
		return true;
	}

	/**
	 * Returns true if this database does an implicit sort when doing GROUP BY
	 * @return bool
	 */
	function implicitGroupby() {
		return false;
	}

	/**
	 * Returns true if this database does an implicit order by when the column
	 *  has an index
	 * For example: SELECT page_title FROM page LIMIT 1
	 * @return bool
	 */
	function implicitOrderby() {
		return false;
	}

	/**
	 * Returns true if this database can do a native search on IP columns
	 * e.g. this works as expected: .. WHERE rc_ip = '127.42.12.102/32';
	 * @return bool
	 */
	function searchableIPs() {
		return true;
	}

	/**
	 * Returns true if this database can use functional indexes
	 * @return bool
	 */
	function functionalIndexes() {
		return true;
	}

	/**
	 * Returns a unique string representing the wiki on the server
	 * @return string
	 */
	public function getWikiID() {
		if( $this->mSchema ) {
			return "{$this->mDBname}-{$this->mSchema}";
		} else {
			return $this->mDBname;
		}
	}

	/**
	 * Returns the database software identifieir
	 * @return string
	 */
	public function getType() {
		return 'ibm_db2';
	}

	/**
	 * Returns the database connection object
	 * @return Object
	 */
	public function getDb(){
		return $this->mConn;
	}

	/**
	 *
	 * @param $server String: hostname of database server
	 * @param $user String: username
	 * @param $password String: password
	 * @param $dbName String: database name on the server
	 * @param $flags Integer: database behaviour flags (optional, unused)
	 * @param $schema String
	 */
	public function __construct( $server = false, $user = false,
							$password = false,
							$dbName = false, $flags = 0,
							$schema = self::USE_GLOBAL )
	{
		global $wgDBmwschema;

		if ( $schema == self::USE_GLOBAL ) {
			$this->mSchema = $wgDBmwschema;
		} else {
			$this->mSchema = $schema;
		}

		// configure the connection and statement objects
		$this->setDB2Option( 'db2_attr_case', 'DB2_CASE_LOWER',
			self::CONN_OPTION | self::STMT_OPTION );
		$this->setDB2Option( 'deferred_prepare', 'DB2_DEFERRED_PREPARE_ON',
			self::STMT_OPTION );
		$this->setDB2Option( 'rowcount', 'DB2_ROWCOUNT_PREFETCH_ON',
			self::STMT_OPTION );
		parent::__construct( $server, $user, $password, $dbName, DBO_TRX | $flags );
	}

	/**
	 * Enables options only if the ibm_db2 extension version supports them
	 * @param $name String: name of the option in the options array
	 * @param $const String: name of the constant holding the right option value
	 * @param $type Integer: whether this is a Connection or Statement otion
	 */
	private function setDB2Option( $name, $const, $type ) {
		if ( defined( $const ) ) {
			if ( $type & self::CONN_OPTION ) {
				$this->mConnOptions[$name] = constant( $const );
			}
			if ( $type & self::STMT_OPTION ) {
				$this->mStmtOptions[$name] = constant( $const );
			}
		} else {
			$this->installPrint(
				"$const is not defined. ibm_db2 version is likely too low." );
		}
	}

	/**
	 * Outputs debug information in the appropriate place
	 * @param $string String: the relevant debug message
	 */
	private function installPrint( $string ) {
		wfDebug( "$string\n" );
		if ( $this->mMode == self::INSTALL_MODE ) {
			print "<li><pre>$string</pre></li>";
			flush();
		}
	}

	/**
	 * Opens a database connection and returns it
	 * Closes any existing connection
	 *
	 * @param $server String: hostname
	 * @param $user String
	 * @param $password String
	 * @param $dbName String: database name
	 * @return DatabaseBase a fresh connection
	 */
	public function open( $server, $user, $password, $dbName ) {
		wfProfileIn( __METHOD__ );

		# Load IBM DB2 driver if missing
		wfDl( 'ibm_db2' );

		# Test for IBM DB2 support, to avoid suppressed fatal error
		if ( !function_exists( 'db2_connect' ) ) {
			throw new DBConnectionError( $this, "DB2 functions missing, have you enabled the ibm_db2 extension for PHP?" );
		}

		global $wgDBport;

		// Close existing connection
		$this->close();
		// Cache conn info
		$this->mServer = $server;
		$this->mPort = $port = $wgDBport;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$this->openUncataloged( $dbName, $user, $password, $server, $port );

		if ( !$this->mConn ) {
			$this->installPrint( "DB connection error\n" );
			$this->installPrint(
				"Server: $server, Database: $dbName, User: $user, Password: "
				. substr( $password, 0, 3 ) . "...\n" );
			$this->installPrint( $this->lastError() . "\n" );
			wfProfileOut( __METHOD__ );
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError() . "\n" );
			throw new DBConnectionError( $this, $this->lastError() );
		}

		// Some MediaWiki code is still transaction-less (?).
		// The strategy is to keep AutoCommit on for that code
		//  but switch it off whenever a transaction is begun.
		db2_autocommit( $this->mConn, DB2_AUTOCOMMIT_ON );

		$this->mOpened = true;
		$this->applySchema();

		wfProfileOut( __METHOD__ );
		return $this->mConn;
	}

	/**
	 * Opens a cataloged database connection, sets mConn
	 */
	protected function openCataloged( $dbName, $user, $password ) {
		wfSuppressWarnings();
		$this->mConn = db2_pconnect( $dbName, $user, $password );
		wfRestoreWarnings();
	}

	/**
	 * Opens an uncataloged database connection, sets mConn
	 */
	protected function openUncataloged( $dbName, $user, $password, $server, $port )
	{
		$dsn = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$dbName;CHARSET=UTF-8;HOSTNAME=$server;PORT=$port;PROTOCOL=TCPIP;UID=$user;PWD=$password;";
		wfSuppressWarnings();
		$this->mConn = db2_pconnect( $dsn, "", "", array() );
		wfRestoreWarnings();
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 * @return bool
	 */
	protected function closeConnection() {
		return db2_close( $this->mConn );
	}

	/**
	 * Retrieves the most current database error
	 * Forces a database rollback
	 * @return bool|string
	 */
	public function lastError() {
		$connerr = db2_conn_errormsg();
		if ( $connerr ) {
			//$this->rollback( __METHOD__ );
			return $connerr;
		}
		$stmterr = db2_stmt_errormsg();
		if ( $stmterr ) {
			//$this->rollback( __METHOD__ );
			return $stmterr;
		}

		return false;
	}

	/**
	 * Get the last error number
	 * Return 0 if no error
	 * @return integer
	 */
	public function lastErrno() {
		$connerr = db2_conn_error();
		if ( $connerr ) {
			return $connerr;
		}
		$stmterr = db2_stmt_error();
		if ( $stmterr ) {
			return $stmterr;
		}
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
	 * @return object Result object for fetch functions or false on failure
	 */
	protected function doQuery( $sql ) {
		$this->applySchema();

		// Needed to handle any UTF-8 encoding issues in the raw sql
		// Note that we fully support prepared statements for DB2
		// prepare() and execute() should be used instead of doQuery() whenever possible
		$sql = utf8_decode( $sql );

		$ret = db2_exec( $this->mConn, $sql, $this->mStmtOptions );
		if( $ret == false ) {
			$error = db2_stmt_errormsg();

			$this->installPrint( "<pre>$sql</pre>" );
			$this->installPrint( $error );
			throw new DBUnexpectedError( $this, 'SQL error: '
				. htmlspecialchars( $error ) );
		}
		$this->mLastResult = $ret;
		$this->mAffectedRows = null; // Not calculated until asked for
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
	public function tableExists( $table, $fname = __METHOD__ ) {
		$schema = $this->mSchema;

		$sql = "SELECT COUNT( * ) FROM SYSIBM.SYSTABLES ST WHERE ST.NAME = '" .
			strtoupper( $table ) .
			"' AND ST.CREATOR = '" .
			strtoupper( $schema ) . "'";
		$res = $this->query( $sql );
		if ( !$res ) {
			return false;
		}

		// If the table exists, there should be one of it
		$row = $this->fetchRow( $res );
		$count = $row[0];
		if ( $count == '1' || $count == 1 ) {
			return true;
		}

		return false;
	}

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 *
	 * @param $res array|ResultWrapper SQL result object as returned from Database::query(), etc.
	 * @return DB2 row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$row = db2_fetch_object( $res );
		wfRestoreWarnings();
		if( $this->lastErrno() ) {
			throw new DBUnexpectedError( $this, 'Error in fetchObject(): '
				. htmlspecialchars( $this->lastError() ) );
		}
		return $row;
	}

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form. Fields are retrieved with $row['fieldname'].
	 *
	 * @param $res array|ResultWrapper SQL result object as returned from Database::query(), etc.
	 * @return ResultWrapper row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( db2_num_rows( $res ) > 0) {
			wfSuppressWarnings();
			$row = db2_fetch_array( $res );
			wfRestoreWarnings();
			if ( $this->lastErrno() ) {
				throw new DBUnexpectedError( $this, 'Error in fetchRow(): '
					. htmlspecialchars( $this->lastError() ) );
			}
			return $row;
		}
		return false;
	}

	/**
	 * Escapes strings
	 * Doesn't escape numbers
	 *
	 * @param $s String: string to escape
	 * @return string escaped string
	 */
	public function addQuotes( $s ) {
		//$this->installPrint( "DB2::addQuotes( $s )\n" );
		if ( is_null( $s ) ) {
			return 'NULL';
		} elseif ( $s instanceof Blob ) {
			return "'" . $s->fetch( $s ) . "'";
		} elseif ( $s instanceof IBM_DB2Blob ) {
			return "'" . $this->decodeBlob( $s ) . "'";
		}
		$s = $this->strencode( $s );
		if ( is_numeric( $s ) ) {
			return $s;
		} else {
			return "'$s'";
		}
	}

	/**
	 * Verifies that a DB2 column/field type is numeric
	 *
	 * @param $type String: DB2 column type
	 * @return Boolean: true if numeric
	 */
	public function is_numeric_type( $type ) {
		switch ( strtoupper( $type ) ) {
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
	 * @param $s String: string to escape
	 * @return string escaped string
	 */
	public function strencode( $s ) {
		// Bloody useless function
		//  Prepends backslashes to \x00, \n, \r, \, ', " and \x1a.
		//  But also necessary
		$s = db2_escape_string( $s );
		// Wide characters are evil -- some of them look like '
		$s = utf8_encode( $s );
		// Fix its stupidity
		$from =	array( 	"\\\\",	"\\'",	'\\n',	'\\t',	'\\"',	'\\r' );
		$to = array( 		"\\",		"''",		"\n",		"\t",		'"',		"\r" );
		$s = str_replace( $from, $to, $s ); // DB2 expects '', not \' escaping
		return $s;
	}

	/**
	 * Switch into the database schema
	 */
	protected function applySchema() {
		if ( !( $this->mSchemaSet ) ) {
			$this->mSchemaSet = true;
			$this->begin( __METHOD__ );
			$this->doQuery( "SET SCHEMA = $this->mSchema" );
			$this->commit( __METHOD__ );
		}
	}

	/**
	 * Start a transaction (mandatory)
	 */
	protected function doBegin( $fname = 'DatabaseIbm_db2::begin' ) {
		// BEGIN is implicit for DB2
		// However, it requires that AutoCommit be off.

		// Some MediaWiki code is still transaction-less (?).
		// The strategy is to keep AutoCommit on for that code
		//  but switch it off whenever a transaction is begun.
		db2_autocommit( $this->mConn, DB2_AUTOCOMMIT_OFF );

		$this->mTrxLevel = 1;
	}

	/**
	 * End a transaction
	 * Must have a preceding begin()
	 */
	protected function doCommit( $fname = 'DatabaseIbm_db2::commit' ) {
		db2_commit( $this->mConn );

		// Some MediaWiki code is still transaction-less (?).
		// The strategy is to keep AutoCommit on for that code
		//  but switch it off whenever a transaction is begun.
		db2_autocommit( $this->mConn, DB2_AUTOCOMMIT_ON );

		$this->mTrxLevel = 0;
	}

	/**
	 * Cancel a transaction
	 */
	protected function doRollback( $fname = 'DatabaseIbm_db2::rollback' ) {
		db2_rollback( $this->mConn );
		// turn auto-commit back on
		// not sure if this is appropriate
		db2_autocommit( $this->mConn, DB2_AUTOCOMMIT_ON );
		$this->mTrxLevel = 0;
	}

	/**
	 * Makes an encoded list of strings from an array
	 * $mode:
	 *   LIST_COMMA         - comma separated, no field names
	 *   LIST_AND           - ANDed WHERE clause (without the WHERE)
	 *   LIST_OR            - ORed WHERE clause (without the WHERE)
	 *   LIST_SET           - comma separated with field names, like a SET clause
	 *   LIST_NAMES         - comma separated field names
	 *   LIST_SET_PREPARED  - like LIST_SET, except with ? tokens as values
	 * @return string
	 */
	function makeList( $a, $mode = LIST_COMMA ) {
		if ( !is_array( $a ) ) {
			throw new DBUnexpectedError( $this,
				'DatabaseIbm_db2::makeList called with incorrect parameters' );
		}

		// if this is for a prepared UPDATE statement
		// (this should be promoted to the parent class
		//  once other databases use prepared statements)
		if ( $mode == LIST_SET_PREPARED ) {
			$first = true;
			$list = '';
			foreach ( $a as $field => $value ) {
				if ( !$first ) {
					$list .= ", $field = ?";
				} else {
					$list .= "$field = ?";
					$first = false;
				}
			}
			$list .= '';

			return $list;
		}

		// otherwise, call the usual function
		return parent::makeList( $a, $mode );
	}

	/**
	 * Construct a LIMIT query with optional offset
	 * This is used for query pages
	 *
	 * @param $sql string SQL query we will append the limit too
	 * @param $limit integer the SQL limit
	 * @param $offset integer the SQL offset (default false)
	 * @return string
	 */
	public function limitResult( $sql, $limit, $offset=false ) {
		if( !is_numeric( $limit ) ) {
			throw new DBUnexpectedError( $this,
				"Invalid non-numeric limit passed to limitResult()\n" );
		}
		if( $offset ) {
			if ( stripos( $sql, 'where' ) === false ) {
				return "$sql AND ( ROWNUM BETWEEN $offset AND $offset+$limit )";
			} else {
				return "$sql WHERE ( ROWNUM BETWEEN $offset AND $offset+$limit )";
			}
		}
		return "$sql FETCH FIRST $limit ROWS ONLY ";
	}

	/**
	 * Handle reserved keyword replacement in table names
	 *
	 * @param $name Object
	 * @param $format String Ignored parameter Default 'quoted'Boolean
	 * @return String
	 */
	public function tableName( $name, $format = 'quoted' ) {
		// we want maximum compatibility with MySQL schema
		return $name;
	}

	/**
	 * Generates a timestamp in an insertable format
	 *
	 * @param $ts string timestamp
	 * @return String: timestamp value
	 */
	public function timestamp( $ts = 0 ) {
		// TS_MW cannot be easily distinguished from an integer
		return wfTimestamp( TS_DB2, $ts );
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 * @param $seqName String: name of a defined sequence in the database
	 * @return int next value in that sequence
	 */
	public function nextSequenceValue( $seqName ) {
		// Not using sequences in the primary schema to allow for easier migration
		//  from MySQL
		// Emulating MySQL behaviour of using NULL to signal that sequences
		// aren't used
		/*
		$safeseq = preg_replace( "/'/", "''", $seqName );
		$res = $this->query( "VALUES NEXTVAL FOR $safeseq" );
		$row = $this->fetchRow( $res );
		$this->mInsertId = $row[0];
		return $this->mInsertId;
		*/
		return null;
	}

	/**
	 * This must be called after nextSequenceVal
	 * @return int Last sequence value used as a primary key
	 */
	public function insertId() {
		return $this->mInsertId;
	}

	/**
	 * Updates the mInsertId property with the value of the last insert
	 *  into a generated column
	 *
	 * @param $table      String: sanitized table name
	 * @param $primaryKey Mixed: string name of the primary key
	 * @param $stmt       Resource: prepared statement resource
	 *  of the SELECT primary_key FROM FINAL TABLE ( INSERT ... ) form
	 */
	private function calcInsertId( $table, $primaryKey, $stmt ) {
		if ( $primaryKey ) {
			$this->mInsertId = db2_last_insert_id( $this->mConn );
		}
	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $args may be a single associative array, or an array of arrays
	 *  with numeric keys, for multi-row insert
	 *
	 * @param $table   String: Name of the table to insert to.
	 * @param $args    Array: Items to insert into the table.
	 * @param $fname   String: Name of the function, for profiling
	 * @param $options String or Array. Valid options: IGNORE
	 *
	 * @return bool Success of insert operation. IGNORE always returns true.
	 */
	public function insert( $table, $args, $fname = 'DatabaseIbm_db2::insert',
		$options = array() )
	{
		if ( !count( $args ) ) {
			return true;
		}
		// get database-specific table name (not used)
		$table = $this->tableName( $table );
		// format options as an array
		$options = IBM_DB2Helper::makeArray( $options );
		// format args as an array of arrays
		if ( !( isset( $args[0] ) && is_array( $args[0] ) ) ) {
			$args = array( $args );
		}

		// prevent insertion of NULL into primary key columns
		list( $args, $primaryKeys ) = $this->removeNullPrimaryKeys( $table, $args );
		// if there's only one primary key
		// we'll be able to read its value after insertion
		$primaryKey = false;
		if ( count( $primaryKeys ) == 1 ) {
			$primaryKey = $primaryKeys[0];
		}

		// get column names
		$keys = array_keys( $args[0] );
		$key_count = count( $keys );

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$ignore = in_array( 'IGNORE', $options ) ? 'mw' : '';

		// assume success
		$res = true;
		// If we are not in a transaction, we need to be for savepoint trickery
		if ( !$this->mTrxLevel ) {
			$this->begin( __METHOD__ );
		}

		$sql = "INSERT INTO $table ( " . implode( ',', $keys ) . ' ) VALUES ';
		if ( $key_count == 1 ) {
			$sql .= '( ? )';
		} else {
			$sql .= '( ?' . str_repeat( ',?', $key_count-1 ) . ' )';
		}
		$this->installPrint( "Preparing the following SQL:" );
		$this->installPrint( "$sql" );
		$this->installPrint( print_r( $args, true ));
		$stmt = $this->prepare( $sql );

		// start a transaction/enter transaction mode
		$this->begin( __METHOD__ );

		if ( !$ignore ) {
			//$first = true;
			foreach ( $args as $row ) {
				//$this->installPrint( "Inserting " . print_r( $row, true ));
				// insert each row into the database
				$res = $res & $this->execute( $stmt, $row );
				if ( !$res ) {
					$this->installPrint( 'Last error:' );
					$this->installPrint( $this->lastError() );
				}
				// get the last inserted value into a generated column
				$this->calcInsertId( $table, $primaryKey, $stmt );
			}
		} else {
			$olde = error_reporting( 0 );
			// For future use, we may want to track the number of actual inserts
			// Right now, insert (all writes) simply return true/false
			$numrowsinserted = 0;

			// always return true
			$res = true;

			foreach ( $args as $row ) {
				$overhead = "SAVEPOINT $ignore ON ROLLBACK RETAIN CURSORS";
				db2_exec( $this->mConn, $overhead, $this->mStmtOptions );

				$res2 = $this->execute( $stmt, $row );

				if ( !$res2 ) {
					$this->installPrint( 'Last error:' );
					$this->installPrint( $this->lastError() );
				}
				// get the last inserted value into a generated column
				$this->calcInsertId( $table, $primaryKey, $stmt );

				$errNum = $this->lastErrno();
				if ( $errNum ) {
					db2_exec( $this->mConn, "ROLLBACK TO SAVEPOINT $ignore",
						$this->mStmtOptions );
				} else {
					db2_exec( $this->mConn, "RELEASE SAVEPOINT $ignore",
						$this->mStmtOptions );
					$numrowsinserted++;
				}
			}

			$olde = error_reporting( $olde );
			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;
		}
		// commit either way
		$this->commit( __METHOD__ );
		$this->freePrepared( $stmt );

		return $res;
	}

	/**
	 * Given a table name and a hash of columns with values
	 * Removes primary key columns from the hash where the value is NULL
	 *
	 * @param $table String: name of the table
	 * @param $args Array of hashes of column names with values
	 * @return Array: tuple( filtered array of columns, array of primary keys )
	 */
	private function removeNullPrimaryKeys( $table, $args ) {
		$schema = $this->mSchema;

		// find out the primary keys
		$keyres = $this->doQuery( "SELECT NAME FROM SYSIBM.SYSCOLUMNS WHERE TBNAME = '"
		  . strtoupper( $table )
		  . "' AND TBCREATOR = '"
		  . strtoupper( $schema )
		  . "' AND KEYSEQ > 0" );

		$keys = array();
		for (
			$row = $this->fetchRow( $keyres );
			$row != null;
			$row = $this->fetchRow( $keyres )
		)
		{
			$keys[] = strtolower( $row[0] );
		}
		// remove primary keys
		foreach ( $args as $ai => $row ) {
			foreach ( $keys as $key ) {
				if ( $row[$key] == null ) {
					unset( $row[$key] );
				}
			}
			$args[$ai] = $row;
		}
		// return modified hash
		return array( $args, $keys );
	}

	/**
	 * UPDATE wrapper, takes a condition array and a SET array
	 *
	 * @param $table  String: The table to UPDATE
	 * @param $values array An array of values to SET
	 * @param $conds  array An array of conditions ( WHERE ). Use '*' to update all rows.
	 * @param $fname  String: The Class::Function calling this function
	 *                ( for the log )
	 * @param $options array An array of UPDATE options, can be one or
	 *                 more of IGNORE, LOW_PRIORITY
	 * @return Boolean
	 */
	public function update( $table, $values, $conds, $fname = 'DatabaseIbm_db2::update',
		$options = array() )
	{
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET "
			. $this->makeList( $values, LIST_SET_PREPARED );
		if ( $conds != '*' ) {
			$sql .= " WHERE " . $this->makeList( $conds, LIST_AND );
		}
		$stmt = $this->prepare( $sql );
		$this->installPrint( 'UPDATE: ' . print_r( $values, true ) );
		// assuming for now that an array with string keys will work
		// if not, convert to simple array first
		$result = $this->execute( $stmt, $values );
		$this->freePrepared( $stmt );

		return $result;
	}

	/**
	 * DELETE query wrapper
	 *
	 * Use $conds == "*" to delete all rows
	 * @return bool|\ResultWrapper
	 */
	public function delete( $table, $conds, $fname = 'DatabaseIbm_db2::delete' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError( $this,
				'DatabaseIbm_db2::delete() called with no conditions' );
		}
		$table = $this->tableName( $table );
		$sql = "DELETE FROM $table";
		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		$result = $this->query( $sql, $fname );

		return $result;
	}

	/**
	 * Returns the number of rows affected by the last query or 0
	 * @return Integer: the number of rows affected by the last query
	 */
	public function affectedRows() {
		if ( !is_null( $this->mAffectedRows ) ) {
			// Forced result for simulated queries
			return $this->mAffectedRows;
		}
		if( empty( $this->mLastResult ) ) {
			return 0;
		}
		return db2_num_rows( $this->mLastResult );
	}

	/**
	 * Returns the number of rows in the result set
	 * Has to be called right after the corresponding select query
	 * @param $res Object result set
	 * @return Integer: number of rows
	 */
	public function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		if ( $this->mNumRows ) {
			return $this->mNumRows;
		} else {
			return 0;
		}
	}

	/**
	 * Moves the row pointer of the result set
	 * @param $res Object: result set
	 * @param $row Integer: row number
	 * @return bool success or failure
	 */
	public function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			return $res = $res->result;
		}
		if ( $res instanceof IBM_DB2Result ) {
			return $res->dataSeek( $row );
		}
		wfDebug( "dataSeek operation in DB2 database\n" );
		return false;
	}

	###
	# Fix notices in Block.php
	###

	/**
	 * Frees memory associated with a statement resource
	 * @param $res Object: statement resource to free
	 * @return Boolean success or failure
	 */
	public function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$ok = db2_free_result( $res );
		wfRestoreWarnings();
		if ( !$ok ) {
			throw new DBUnexpectedError( $this, "Unable to free DB2 result\n" );
		}
	}

	/**
	 * Returns the number of columns in a resource
	 * @param $res Object: statement resource
	 * @return Number of fields/columns in resource
	 */
	public function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( $res instanceof IBM_DB2Result ) {
			$res = $res->getResult();
		}
		return db2_num_fields( $res );
	}

	/**
	 * Returns the nth column name
	 * @param $res Object: statement resource
	 * @param $n Integer: Index of field or column
	 * @return String name of nth column
	 */
	public function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( $res instanceof IBM_DB2Result ) {
			$res = $res->getResult();
		}
		return db2_field_name( $res, $n );
	}

	/**
	 * SELECT wrapper
	 *
	 * @param $table   Array or string, table name(s) (prefix auto-added)
	 * @param $vars    Array or string, field name(s) to be retrieved
	 * @param $conds   Array or string, condition(s) for WHERE
	 * @param $fname   String: calling function name (use __METHOD__)
	 *                 for logs/profiling
	 * @param $options array Associative array of options
	 *                 (e.g. array( 'GROUP BY' => 'page_title' )),
	 *                 see Database::makeSelectOptions code for list of
	 *                 supported stuff
	 * @param $join_conds array Associative array of table join conditions (optional)
	 *                    (e.g. array( 'page' => array('LEFT JOIN',
	 *                    'page_latest=rev_id') )
	 * @return Mixed: database result resource for fetch functions or false
	 *                 on failure
	 */
	public function select( $table, $vars, $conds = '', $fname = 'DatabaseIbm_db2::select', $options = array(), $join_conds = array() )
	{
		$res = parent::select( $table, $vars, $conds, $fname, $options,
			$join_conds );
		$sql = $this->selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );

		// We must adjust for offset
		if ( isset( $options['LIMIT'] ) && isset ( $options['OFFSET'] ) ) {
			$limit = $options['LIMIT'];
			$offset = $options['OFFSET'];
		}

		// DB2 does not have a proper num_rows() function yet, so we must emulate
		// DB2 9.5.4 and the corresponding ibm_db2 driver will introduce
		//  a working one
		// TODO: Yay!

		// we want the count
		$vars2 = array( 'count( * ) as num_rows' );
		// respecting just the limit option
		$options2 = array();
		if ( isset( $options['LIMIT'] ) ) {
			$options2['LIMIT'] = $options['LIMIT'];
		}
		// but don't try to emulate for GROUP BY
		if ( isset( $options['GROUP BY'] ) ) {
			return $res;
		}

		$res2 = parent::select( $table, $vars2, $conds, $fname, $options2,
			$join_conds );

		$obj = $this->fetchObject( $res2 );
		$this->mNumRows = $obj->num_rows;

		return new ResultWrapper( $this, new IBM_DB2Result( $this, $res, $obj->num_rows, $vars, $sql ) );
	}

	/**
	 * Handles ordering, grouping, and having options ('GROUP BY' => colname)
	 * Has limited support for per-column options (colnum => 'DISTINCT')
	 *
	 * @private
	 *
	 * @param $options array Associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return Array
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

		if ( isset( $options['GROUP BY'] ) ) {
			$preLimitTail .= " GROUP BY {$options['GROUP BY']}";
		}
		if ( isset( $options['HAVING'] ) ) {
			$preLimitTail .= " HAVING {$options['HAVING']}";
		}
		if ( isset( $options['ORDER BY'] ) ) {
			$preLimitTail .= " ORDER BY {$options['ORDER BY']}";
		}

		if ( isset( $noKeyOptions['DISTINCT'] )
			|| isset( $noKeyOptions['DISTINCTROW'] ) )
		{
			$startOpts .= 'DISTINCT';
		}

		return array( $startOpts, '', $preLimitTail, $postLimitTail );
	}

	/**
	 * Returns link to IBM DB2 free download
	 * @return String: wikitext of a link to the server software's web site
	 */
	public static function getSoftwareLink() {
		return '[http://www.ibm.com/db2/express/ IBM DB2]';
	}

	/**
	 * Get search engine class. All subclasses of this
	 * need to implement this if they wish to use searching.
	 *
	 * @return String
	 */
	public function getSearchEngine() {
		return 'SearchIBM_DB2';
	}

	/**
	 * Did the last database access fail because of deadlock?
	 * @return Boolean
	 */
	public function wasDeadlock() {
		// get SQLSTATE
		$err = $this->lastErrno();
		switch( $err ) {
			// This is literal port of the MySQL logic and may be wrong for DB2
			case '40001':	// sql0911n, Deadlock or timeout, rollback
			case '57011':	// sql0904n, Resource unavailable, no rollback
			case '57033':	// sql0913n, Deadlock or timeout, no rollback
			$this->installPrint( "In a deadlock because of SQLSTATE $err" );
			return true;
		}
		return false;
	}

	/**
	 * Ping the server and try to reconnect if it there is no connection
	 * The connection may be closed and reopened while this happens
	 * @return Boolean: whether the connection exists
	 */
	public function ping() {
		// db2_ping() doesn't exist
		// Emulate
		$this->close();
		$this->openUncataloged( $this->mDBName, $this->mUser,
			$this->mPassword, $this->mServer, $this->mPort );

		return false;
	}
	######################################
	# Unimplemented and not applicable
	######################################

	/**
	 * Only useful with fake prepare like in base Database class
	 * @return	string
	 */
	public function fillPreparedArg( $matches ) {
		$this->installPrint( 'Not useful for DB2: fillPreparedArg()' );
		return '';
	}

	######################################
	# Reflection
	######################################

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 * @param $table String: table name
	 * @param $index String: index name
	 * @param $fname String: function name for logging and profiling
	 * @return Object query row in object form
	 */
	public function indexInfo( $table, $index,
		$fname = 'DatabaseIbm_db2::indexExists' )
	{
		$table = $this->tableName( $table );
		$sql = <<<SQL
SELECT name as indexname
FROM sysibm.sysindexes si
WHERE si.name='$index' AND si.tbname='$table'
AND sc.tbcreator='$this->mSchema'
SQL;
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		$row = $this->fetchObject( $res );
		if ( $row != null ) {
			return $row;
		} else {
			return false;
		}
	}

	/**
	 * Returns an information object on a table column
	 * @param $table String: table name
	 * @param $field String: column name
	 * @return IBM_DB2Field
	 */
	public function fieldInfo( $table, $field ) {
		return IBM_DB2Field::fromText( $this, $table, $field );
	}

	/**
	 * db2_field_type() wrapper
	 * @param $res Object: result of executed statement
	 * @param $index Mixed: number or name of the column
	 * @return String column type
	 */
	public function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( $res instanceof IBM_DB2Result ) {
			$res = $res->getResult();
		}
		return db2_field_type( $res, $index );
	}

	/**
	 * Verifies that an index was created as unique
	 * @param $table String: table name
	 * @param $index String: index name
	 * @param $fname string function name for profiling
	 * @return Bool
	 */
	public function indexUnique ( $table, $index,
		$fname = 'DatabaseIbm_db2::indexUnique' )
	{
		$table = $this->tableName( $table );
		$sql = <<<SQL
SELECT si.name as indexname
FROM sysibm.sysindexes si
WHERE si.name='$index' AND si.tbname='$table'
AND sc.tbcreator='$this->mSchema'
AND si.uniquerule IN ( 'U', 'P' )
SQL;
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		if ( $this->fetchObject( $res ) ) {
			return true;
		}
		return false;

	}

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 * @param $table String: table name
	 * @param $field String: column name
	 * @return Integer: length or -1 for unlimited
	 */
	public function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = <<<SQL
SELECT length as size
FROM sysibm.syscolumns sc
WHERE sc.name='$field' AND sc.tbname='$table'
AND sc.tbcreator='$this->mSchema'
SQL;
		$res = $this->query( $sql );
		$row = $this->fetchObject( $res );
		$size = $row->size;
		return $size;
	}

	/**
	 * Description is left as an exercise for the reader
	 * @param $b Mixed: data to be encoded
	 * @return IBM_DB2Blob
	 */
	public function encodeBlob( $b ) {
		return new IBM_DB2Blob( $b );
	}

	/**
	 * Description is left as an exercise for the reader
	 * @param $b IBM_DB2Blob: data to be decoded
	 * @return mixed
	 */
	public function decodeBlob( $b ) {
		return "$b";
	}

	/**
	 * Convert into a list of string being concatenated
	 * @param $stringList Array: strings that need to be joined together
	 *                    by the SQL engine
	 * @return String: joined by the concatenation operator
	 */
	public function buildConcat( $stringList ) {
		// || is equivalent to CONCAT
		// Sample query: VALUES 'foo' CONCAT 'bar' CONCAT 'baz'
		return implode( ' || ', $stringList );
	}

	/**
	 * Generates the SQL required to convert a DB2 timestamp into a Unix epoch
	 * @param $column String: name of timestamp column
	 * @return String: SQL code
	 */
	public function extractUnixEpoch( $column ) {
		// TODO
		// see SpecialAncientpages
	}

	######################################
	# Prepared statements
	######################################

	/**
	 * Intended to be compatible with the PEAR::DB wrapper functions.
	 * http://pear.php.net/manual/en/package.database.db.intro-execute.php
	 *
	 * ? = scalar value, quoted as necessary
	 * ! = raw SQL bit (a function for instance)
	 * & = filename; reads the file and inserts as a blob
	 *     (we don't use this though...)
	 * @param $sql String: SQL statement with appropriate markers
	 * @param $func String: Name of the function, for profiling
	 * @return resource a prepared DB2 SQL statement
	 */
	public function prepare( $sql, $func = 'DB2::prepare' ) {
		$stmt = db2_prepare( $this->mConn, $sql, $this->mStmtOptions );
		return $stmt;
	}

	/**
	 * Frees resources associated with a prepared statement
	 * @return Boolean success or failure
	 */
	public function freePrepared( $prepared ) {
		return db2_free_stmt( $prepared );
	}

	/**
	 * Execute a prepared query with the various arguments
	 * @param $prepared String: the prepared sql
	 * @param $args Mixed: either an array here, or put scalars as varargs
	 * @return Resource: results object
	 */
	public function execute( $prepared, $args = null ) {
		if( !is_array( $args ) ) {
			# Pull the var args
			$args = func_get_args();
			array_shift( $args );
		}
		$res = db2_execute( $prepared, $args );
		if ( !$res ) {
			$this->installPrint( db2_stmt_errormsg() );
		}
		return $res;
	}

	/**
	 * For faking prepared SQL statements on DBs that don't support
	 * it directly.
	 * @param $preparedQuery String: a 'preparable' SQL statement
	 * @param $args Array of arguments to fill it with
	 * @return String: executable statement
	 */
	public function fillPrepared( $preparedQuery, $args ) {
		reset( $args );
		$this->preparedArgs =& $args;

		foreach ( $args as $i => $arg ) {
			db2_bind_param( $preparedQuery, $i+1, $args[$i] );
		}

		return $preparedQuery;
	}

	/**
	 * Switches module between regular and install modes
	 * @return string
	 */
	public function setMode( $mode ) {
		$old = $this->mMode;
		$this->mMode = $mode;
		return $old;
	}

	/**
	 * Bitwise negation of a column or value in SQL
	 * Same as (~field) in C
	 * @param $field String
	 * @return String
	 */
	function bitNot( $field ) {
		// expecting bit-fields smaller than 4bytes
		return "BITNOT( $field )";
	}

	/**
	 * Bitwise AND of two columns or values in SQL
	 * Same as (fieldLeft & fieldRight) in C
	 * @param $fieldLeft String
	 * @param $fieldRight String
	 * @return String
	 */
	function bitAnd( $fieldLeft, $fieldRight ) {
		return "BITAND( $fieldLeft, $fieldRight )";
	}

	/**
	 * Bitwise OR of two columns or values in SQL
	 * Same as (fieldLeft | fieldRight) in C
	 * @param $fieldLeft String
	 * @param $fieldRight String
	 * @return String
	 */
	function bitOr( $fieldLeft, $fieldRight ) {
		return "BITOR( $fieldLeft, $fieldRight )";
	}
}

class IBM_DB2Helper {
	public static function makeArray( $maybeArray ) {
		if ( !is_array( $maybeArray ) ) {
			return array( $maybeArray );
		}

		return $maybeArray;
	}
}
