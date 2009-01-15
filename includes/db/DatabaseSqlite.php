<?php
/**
 * This script is the SQLite database abstraction layer
 *
 * See maintenance/sqlite/README for development notes and other specific information
 * @ingroup Database
 * @file
 */

/**
 * @ingroup Database
 */
class DatabaseSqlite extends Database {

	var $mAffectedRows;
	var $mLastResult;
	var $mDatabaseFile;
	var $mName;

	/**
	 * Constructor
	 */
	function __construct($server = false, $user = false, $password = false, $dbName = false, $failFunction = false, $flags = 0) {
		global $wgOut,$wgSQLiteDataDir, $wgSQLiteDataDirMode;
		if ("$wgSQLiteDataDir" == '') $wgSQLiteDataDir = dirname($_SERVER['DOCUMENT_ROOT']).'/data';
		if (!is_dir($wgSQLiteDataDir)) wfMkdirParents( $wgSQLiteDataDir, $wgSQLiteDataDirMode );
		$this->mFailFunction = $failFunction;
		$this->mFlags = $flags;
		$this->mDatabaseFile = "$wgSQLiteDataDir/$dbName.sqlite";
		$this->mName = $dbName;
		$this->open($server, $user, $password, $dbName);
	}

	/**
	 * todo: check if these should be true like parent class
	 */
	function implicitGroupby()   { return false; }
	function implicitOrderby()   { return false; }

	static function newFromParams($server, $user, $password, $dbName, $failFunction = false, $flags = 0) {
		return new DatabaseSqlite($server, $user, $password, $dbName, $failFunction, $flags);
	}

	/** Open an SQLite database and return a resource handle to it
	 *  NOTE: only $dbName is used, the other parameters are irrelevant for SQLite databases
	 */
	function open($server,$user,$pass,$dbName) {
		$this->mConn = false;
		if ($dbName) {
			$file = $this->mDatabaseFile;
			try {
				if ( $this->mFlags & DBO_PERSISTENT ) {
					$this->mConn = new PDO( "sqlite:$file", $user, $pass, 
						array( PDO::ATTR_PERSISTENT => true ) );
				} else {
					$this->mConn = new PDO( "sqlite:$file", $user, $pass );
				}
			} catch ( PDOException $e ) {
				$err = $e->getMessage();
			}
			if ( $this->mConn === false ) {
				wfDebug( "DB connection error: $err\n" );
				if ( !$this->mFailFunction ) {
					throw new DBConnectionError( $this, $err );
				} else {
					return false;
				}

			}
			$this->mOpened = $this->mConn;
			# set error codes only, don't raise exceptions
			$this->mConn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT ); 
		}
		return $this->mConn;
	}

	/**
	 * Close an SQLite database
	 */
	function close() {
		$this->mOpened = false;
		if (is_object($this->mConn)) {
			if ($this->trxLevel()) $this->immediateCommit();
			$this->mConn = null;
		}
		return true;
	}

	/**
	 * SQLite doesn't allow buffered results or data seeking etc, so we'll use fetchAll as the result
	 */
	function doQuery($sql) {
		$res = $this->mConn->query($sql);
		if ($res === false) {
			return false;
		} else {
			$r = $res instanceof ResultWrapper ? $res->result : $res;
			$this->mAffectedRows = $r->rowCount();
			$res = new ResultWrapper($this,$r->fetchAll());
		}
		return $res;
	}

	function freeResult($res) {
		if ($res instanceof ResultWrapper) $res->result = NULL; else $res = NULL;
	}

	function fetchObject($res) {
		if ($res instanceof ResultWrapper) $r =& $res->result; else $r =& $res;
		$cur = current($r);
		if (is_array($cur)) {
			next($r);
			$obj = new stdClass;
			foreach ($cur as $k => $v) if (!is_numeric($k)) $obj->$k = $v;
			return $obj;
		}
		return false;
	}

	function fetchRow($res) {
		if ($res instanceof ResultWrapper) $r =& $res->result; else $r =& $res;
		$cur = current($r);
		if (is_array($cur)) {
			next($r);
			return $cur;
		}
		return false;
	}

	/**
	 * The PDO::Statement class implements the array interface so count() will work
	 */
	function numRows($res) {
		$r = $res instanceof ResultWrapper ? $res->result : $res;
		return count($r);
	}

	function numFields($res) {
		$r = $res instanceof ResultWrapper ? $res->result : $res;
		return is_array($r) ? count($r[0]) : 0;
	}

	function fieldName($res,$n) {
		$r = $res instanceof ResultWrapper ? $res->result : $res;
		if (is_array($r)) {
			$keys = array_keys($r[0]);
			return $keys[$n];
		}
		return  false;
	}

	/**
	 * Use MySQL's naming (accounts for prefix etc) but remove surrounding backticks
	 */
	function tableName($name) {
		return str_replace('`','',parent::tableName($name));
	}

	/**
	 * Index names have DB scope
	 */
	function indexName( $index ) {
		return $index;
	}

	/**
	 * This must be called after nextSequenceVal
	 */
	function insertId() {
		return $this->mConn->lastInsertId();
	}

	function dataSeek($res,$row) {
		if ($res instanceof ResultWrapper) $r =& $res->result; else $r =& $res;
		reset($r);
		if ($row > 0) for ($i = 0; $i < $row; $i++) next($r);
	}

	function lastError() {
		if (!is_object($this->mConn)) return "Cannot return last error, no db connection";
		$e = $this->mConn->errorInfo();
		return isset($e[2]) ? $e[2] : '';
	}

	function lastErrno() {
		if (!is_object($this->mConn)) {
			return "Cannot return last error, no db connection";
		} else {
			$info = $this->mConn->errorInfo();
			return $info[1];
		}
	}

	function affectedRows() {
		return $this->mAffectedRows;
	}

	/**
	 * Returns information about an index
	 * Returns false if the index does not exist
	 * - if errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo($table, $index, $fname = 'Database::indexExists') {
		$sql = 'PRAGMA index_info(' . $this->addQuotes( $this->indexName( $index ) ) . ')';
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		if ( $res->numRows() == 0 ) {
			return false;
		}
		$info = array();
		foreach ( $res as $row ) {
			$info[] = $row->name;
		}
		return $info;
	}

	function indexUnique($table, $index, $fname = 'Database::indexUnique') {
		$row = $this->selectRow( 'sqlite_master', '*', 
			array(
				'type' => 'index',
				'name' => $this->indexName( $index ),
			), $fname );
		if ( !$row || !isset( $row->sql ) ) {
			return null;
		}

		// $row->sql will be of the form CREATE [UNIQUE] INDEX ...
		$indexPos = strpos( $row->sql, 'INDEX' );
		if ( $indexPos === false ) {
			return null;
		}
		$firstPart = substr( $row->sql, 0, $indexPos );
		$options = explode( ' ', $firstPart );
		return in_array( 'UNIQUE', $options );
	}

	/**
	 * Filter the options used in SELECT statements
	 */
	function makeSelectOptions($options) {
		foreach ($options as $k => $v) if (is_numeric($k) && $v == 'FOR UPDATE') $options[$k] = '';
		return parent::makeSelectOptions($options);
	}

	/**
	 * Based on MySQL method (parent) with some prior SQLite-sepcific adjustments
	 */
	function insert($table, $a, $fname = 'DatabaseSqlite::insert', $options = array()) {
		if (!count($a)) return true;
		if (!is_array($options)) $options = array($options);

		# SQLite uses OR IGNORE not just IGNORE
		foreach ($options as $k => $v) if ($v == 'IGNORE') $options[$k] = 'OR IGNORE';

		# SQLite can't handle multi-row inserts, so divide up into multiple single-row inserts
		if (isset($a[0]) && is_array($a[0])) {
			$ret = true;
			foreach ($a as $k => $v) if (!parent::insert($table,$v,"$fname/multi-row",$options)) $ret = false;
		}
		else $ret = parent::insert($table,$a,"$fname/single-row",$options);

		return $ret;
	}

	/**
	 * SQLite does not have a "USE INDEX" clause, so return an empty string
	 */
	function useIndexClause($index) {
		return '';
	}

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 * In SQLite this is SQLITE_MAX_LENGTH, by default 1GB. No way to query it though.
	 */
	function textFieldSize($table, $field) {
		return -1;
	}

	/**
	 * No low priority option in SQLite
	 */
	function lowPriorityOption() {
		return '';
	}

	/**
	 * Returns an SQL expression for a simple conditional.
	 * - uses CASE on SQLite
	 */
	function conditional($cond, $trueVal, $falseVal) {
		return " (CASE WHEN $cond THEN $trueVal ELSE $falseVal END) ";
	}

	function wasDeadlock() {
		return $this->lastErrno() == SQLITE_BUSY;
	}

	function wasErrorReissuable() {
		return $this->lastErrno() ==  SQLITE_SCHEMA;
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	function getSoftwareLink() {
		return "[http://sqlite.org/ SQLite]";
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		global $wgContLang;
		$ver = $this->mConn->getAttribute(PDO::ATTR_SERVER_VERSION);
		return $ver;
	}

	/**
	 * Query whether a given column exists in the mediawiki schema
	 */
	function fieldExists($table, $field, $fname = '') {
		$info = $this->fieldInfo( $table, $field );
		return (bool)$info;
	}

	/**
	 * Get information about a given field
	 * Returns false if the field does not exist.
	 */
	function fieldInfo($table, $field) {
		$tableName = $this->tableName( $table );
		$sql = 'PRAGMA table_info(' . $this->addQuotes( $tableName ) . ')';
		$res = $this->query( $sql, __METHOD__ );
		foreach ( $res as $row ) {
			if ( $row->name == $field ) {
				return new SQLiteField( $row, $tableName );
			}
		}
		return false;
	}

	function begin( $fname = '' ) {
		if ($this->mTrxLevel == 1) $this->commit();
		$this->mConn->beginTransaction();
		$this->mTrxLevel = 1;
	}

	function commit( $fname = '' ) {
		if ($this->mTrxLevel == 0) return;
		$this->mConn->commit();
		$this->mTrxLevel = 0;
	}

	function rollback( $fname = '' ) {
		if ($this->mTrxLevel == 0) return;
		$this->mConn->rollBack();
		$this->mTrxLevel = 0;
	}

	function limitResultForUpdate($sql, $num) {
		return $this->limitResult( $sql, $num );
	}

	function strencode($s) {
		return substr($this->addQuotes($s),1,-1);
	}

	function encodeBlob($b) {
		return new Blob( $b );
	}

	function decodeBlob($b) {
		if ($b instanceof Blob) {
			$b = $b->fetch();
		}
		return $b;
	}

	function addQuotes($s) {
		if ( $s instanceof Blob ) {
			return "x'" . bin2hex( $s->fetch() ) . "'";
		} else {
			return $this->mConn->quote($s);
		}
	}

	function quote_ident($s) { return $s; }

	/**
	 * Not possible in SQLite
	 * We have ATTACH_DATABASE but that requires database selectors before the 
	 * table names and in any case is really a different concept to MySQL's USE
	 */
	function selectDB($db) {
		if ( $db != $this->mName ) {
			throw new MWException( 'selectDB is not implemented in SQLite' );
		}
	}

	/**
	 * not done
	 */
	public function setTimeout($timeout) { return; }

	/**
	 * No-op for a non-networked database
	 */
	function ping() {
		return true;
	}

	/**
	 * How lagged is this slave?
	 */
	public function getLag() {
		return 0;
	}

	/**
	 * Called by the installer script (when modified according to the MediaWikiLite installation instructions)
	 * - this is the same way PostgreSQL works, MySQL reads in tables.sql and interwiki.sql using dbsource (which calls db->sourceFile)
	 */
	public function setup_database() {
		global $IP,$wgSQLiteDataDir,$wgDBTableOptions;
		$wgDBTableOptions = '';

		# Process common MySQL/SQLite table definitions
		$err = $this->sourceFile( "$IP/maintenance/tables.sql" );
		if ($err !== true) {
			$this->reportQueryError($err,0,$sql,__FUNCTION__);
			exit( 1 );
		}

		# Use DatabasePostgres's code to populate interwiki from MySQL template
		$f = fopen("$IP/maintenance/interwiki.sql",'r');
		if ($f == false) dieout("<li>Could not find the interwiki.sql file");
		$sql = "INSERT INTO interwiki(iw_prefix,iw_url,iw_local) VALUES ";
		while (!feof($f)) {
			$line = fgets($f,1024);
			$matches = array();
			if (!preg_match('/^\s*(\(.+?),(\d)\)/', $line, $matches)) continue;
			$this->query("$sql $matches[1],$matches[2])");
		}
	}
	
	/** 
	 * No-op lock functions
	 */
	public function lock( $lockName, $method ) {
		return true;
	}
	public function unlock( $lockName, $method ) {
		return true;
	}
	
	public function getSearchEngine() {
		return "SearchEngineDummy";
	}

	/**
	 * No-op version of deadlockLoop
	 */
	public function deadlockLoop( /*...*/ ) {
		$args = func_get_args();
		$function = array_shift( $args );
		return call_user_func_array( $function, $args );
	}

	protected function replaceVars( $s ) {
		$s = parent::replaceVars( $s );
		if ( preg_match( '/^\s*CREATE TABLE/i', $s ) ) {
			// CREATE TABLE hacks to allow schema file sharing with MySQL
			
			// binary/varbinary column type -> blob
			$s = preg_replace( '/\b(var)?binary(\(\d+\))/i', 'blob\1', $s );
			// no such thing as unsigned
			$s = preg_replace( '/\bunsigned\b/i', '', $s );
			// INT -> INTEGER for primary keys
			$s = preg_replacE( '/\bint\b/i', 'integer', $s );
			// No ENUM type
			$s = preg_replace( '/enum\([^)]*\)/i', 'blob', $s );
			// binary collation type -> nothing
			$s = preg_replace( '/\bbinary\b/i', '', $s );
			// auto_increment -> autoincrement
			$s = preg_replace( '/\bauto_increment\b/i', 'autoincrement', $s );
			// No explicit options
			$s = preg_replace( '/\)[^)]*$/', ')', $s );
		} elseif ( preg_match( '/^\s*CREATE (\s*(?:UNIQUE|FULLTEXT)\s+)?INDEX/i', $s ) ) {
			// No truncated indexes
			$s = preg_replace( '/\(\d+\)/', '', $s );
			// No FULLTEXT
			$s = preg_replace( '/\bfulltext\b/i', '', $s );
		}
		return $s;
	}

} // end DatabaseSqlite class

/**
 * @ingroup Database
 */
class SQLiteField {
	private $info, $tableName;
	function __construct( $info, $tableName ) {
		$this->info = $info;
		$this->tableName = $tableName;
	}

	function name() {
		return $this->info->name;
	}

	function tableName() {
		return $this->tableName;
	}

	function defaultValue() {
		if ( is_string( $this->info->dflt_value ) ) {
			// Typically quoted
			if ( preg_match( '/^\'(.*)\'$', $this->info->dflt_value ) ) {
				return str_replace( "''", "'", $this->info->dflt_value );
			}
		}
		return $this->info->dflt_value;
	}

	function maxLength() {
		return -1;
	}

	function nullable() {
		// SQLite dynamic types are always nullable
		return true;
	}

	# isKey(),  isMultipleKey() not implemented, MySQL-specific concept. 
	# Suggest removal from base class [TS]
	
	function type() {
		return $this->info->type;
	}

} // end SQLiteField

