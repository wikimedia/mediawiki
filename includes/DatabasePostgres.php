<?php

/**
 * This is PostgreSQL database abstraction layer.
 *
 * As it includes more generic version for DB functions,
 * than MySQL ones, some of them should be moved to parent
 * Database class.
 *
 * @package MediaWiki
 */

/**
 * Depends on database
 */
require_once( 'Database.php' );

class DatabasePostgres extends Database {
	var $mInsertId = NULL;
	var $mLastResult = NULL;

	function DatabasePostgres($server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0 )
	{

		global $wgOut, $wgDBprefix, $wgCommandLineMode;
		# Can't get a reference if it hasn't been set yet
		if ( !isset( $wgOut ) ) {
			$wgOut = NULL;
		}
		$this->mOut =& $wgOut;
		$this->mFailFunction = $failFunction;
		$this->mFlags = $flags;

		$this->open( $server, $user, $password, $dbName);

	}

	static function newFromParams( $server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0)
	{
		return new DatabasePostgres( $server, $user, $password, $dbName, $failFunction, $flags );
	}

	/**
	 * Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 */
	function open( $server, $user, $password, $dbName ) {
		# Test for PostgreSQL support, to avoid suppressed fatal error
		if ( !function_exists( 'pg_connect' ) ) {
			throw new DBConnectionError( $this, "PostgreSQL functions missing, have you compiled PHP with the --with-pgsql option?\n" );
		}

		global $wgDBport;

		$this->close();
		$this->mServer = $server;
		$port = $wgDBport;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$success = false;

		$hstring="";
		if ($server!=false && $server!="") {
			$hstring="host=$server ";
		}
		if ($port!=false && $port!="") {
			$hstring .= "port=$port ";
		}

		error_reporting( E_ALL );

		@$this->mConn = pg_connect("$hstring dbname=$dbName user=$user password=$password");

		if ( $this->mConn == false ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError()."\n" );
			return false;
		}

		$this->mOpened = true;
		## If this is the initial connection, setup the schema stuff
		if (defined('MEDIAWIKI_INSTALL') and !defined('POSTGRES_SEARCHPATH')) {
			global $wgDBmwschema, $wgDBts2schema, $wgDBname;

			## Do we have the basic tsearch2 table?
			print "<li>Checking for tsearch2 ...";
			if (! $this->tableExists("pg_ts_dict", $wgDBts2schema)) {
				print "<b>FAILED</b>. Make sure tsearch2 is installed. See <a href=";
				print "'http://www.devx.com/opensource/Article/21674/0/page/2'>this article</a>";
				print " for instructions.</li>\n";
				dieout("</ul>");
			}				
			print "OK</li>\n";

			## Do we have plpgsql installed?
			print "<li>Checking for plpgsql ...";
			$SQL = "SELECT 1 FROM pg_catalog.pg_language WHERE lanname = 'plpgsql'";
			$res = $this->doQuery($SQL);
			$rows = $this->numRows($this->doQuery($SQL));
			if ($rows < 1) {
				print "<b>FAILED</b>. Make sure the language plpgsql is installed for the database <tt>$wgDBname</tt>t</li>";
				## XXX Better help
				dieout("</ul>");
			}
			print "OK</li>\n";

			## Does the schema already exist? Who owns it?
			$result = $this->schemaExists($wgDBmwschema);
			if (!$result) {
				print "<li>Creating schema <b>$wgDBmwschema</b> ...";
				$result = $this->doQuery("CREATE SCHEMA $wgDBmwschema");
				if (!$result) {
					print "FAILED.</li>\n";
					return false;
				}
				print "ok</li>\n";
			}
			else if ($result != $user) {
				print "<li>Schema <b>$wgDBmwschema</b> exists but is not owned by <b>$user</b>. Not ideal.</li>\n";
			}
			else {
				print "<li>Schema <b>$wgDBmwschema</b> exists and is owned by <b>$user ($result)</b>. Excellent.</li>\n";
			}

			## Fix up the search paths if needed
			print "<li>Setting the search path for user <b>$user</b> ...";
			$path = "$wgDBmwschema";
			if ($wgDBts2schema !== $wgDBmwschema)
				$path .= ", $wgDBts2schema";
			if ($wgDBmwschema !== 'public' and $wgDBts2schema !== 'public')
				$path .= ", public";
			$SQL = "ALTER USER $user SET search_path = $path";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "FAILED.</li>\n";
				return false;
			}
			print "ok</li>\n";
			## Set for the rest of this session
			$SQL = "SET search_path = $path";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<li>Failed to set search_path</li>\n";
				return false;
			}
			define( "POSTGRES_SEARCHPATH", $path );
		}

		return $this->mConn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 */
	function close() {
		$this->mOpened = false;
		if ( $this->mConn ) {
			return pg_close( $this->mConn );
		} else {
			return true;
		}
	}

	function doQuery( $sql ) {
		return $this->mLastResult=pg_query( $this->mConn , $sql);
	}

	function queryIgnore( $sql, $fname = '' ) {
		return $this->query( $sql, $fname, true );
	}

	function freeResult( $res ) {
		if ( !@pg_free_result( $res ) ) {
			throw new DBUnexpectedError($this,  "Unable to free PostgreSQL result\n" );
		}
	}

	function fetchObject( $res ) {
		@$row = pg_fetch_object( $res );
		# FIXME: HACK HACK HACK HACK debug

		# TODO:
		# hashar : not sure if the following test really trigger if the object
		#          fetching failled.
		if( pg_last_error($this->mConn) ) {
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $row;
	}

	function fetchRow( $res ) {
		@$row = pg_fetch_array( $res );
		if( pg_last_error($this->mConn) ) {
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $row;
	}

	function numRows( $res ) {
		@$n = pg_num_rows( $res );
		if( pg_last_error($this->mConn) ) {
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $n;
	}
	function numFields( $res ) { return pg_num_fields( $res ); }
	function fieldName( $res, $n ) { return pg_field_name( $res, $n ); }

	/**
	 * This must be called after nextSequenceVal
	 */
	function insertId() {
		return $this->mInsertId;
	}

	function dataSeek( $res, $row ) { return pg_result_seek( $res, $row ); }
	function lastError() {
		if ( $this->mConn ) {
			return pg_last_error();
		}
		else {
			return "No database connection";
		}
	}
	function lastErrno() { return 1; }

	function affectedRows() {
		return pg_affected_rows( $this->mLastResult );
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo( $table, $index, $fname = 'Database::indexExists' ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='$table'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return NULL;
		}

		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->indexname == $index ) {
				return $row;
			}
		}
		return false;
	}

	function indexUnique ($table, $index, $fname = 'Database::indexUnique' ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='{$table}'".
			" AND indexdef LIKE 'CREATE UNIQUE%({$index})'";
		$res = $this->query( $sql, $fname );
		if ( !$res )
			return NULL;
		while ($row = $this->fetchObject( $res ))
			return true;
		return false;

	}

	function insert( $table, $a, $fname = 'Database::insert', $options = array() ) {
		# PostgreSQL doesn't support options
		# We have a go at faking one of them
		# TODO: DELAYED, LOW_PRIORITY

		if ( !is_array($options))
			$options = array($options);

		if ( in_array( 'IGNORE', $options ) )
			$oldIgnore = $this->ignoreErrors( true );

		# IGNORE is performed using single-row inserts, ignoring errors in each
		# FIXME: need some way to distiguish between key collision and other types of error
		$oldIgnore = $this->ignoreErrors( true );
		if ( !is_array( reset( $a ) ) ) {
			$a = array( $a );
		}
		foreach ( $a as $row ) {
			parent::insert( $table, $row, $fname, array() );
		}
		$this->ignoreErrors( $oldIgnore );
		$retVal = true;

		if ( in_array( 'IGNORE', $options ) )
			$this->ignoreErrors( $oldIgnore );

		return $retVal;
	}

	function tableName( $name ) {
		# Replace backticks into double quotes
		$name = strtr($name,'`','"');

		# Now quote PG reserved keywords
		switch( $name ) {
			case 'user':
			case 'old':
			case 'group':
				return '"' . $name . '"';

			default:
				return $name;
		}
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 */
	function nextSequenceValue( $seqName ) {
		$safeseq = preg_replace( "/'/", "''", $seqName );
		$res = $this->query( "SELECT nextval('$safeseq')" );
		$row = $this->fetchRow( $res );
		$this->mInsertId = $row[0];
		$this->freeResult( $res );
		return $this->mInsertId;
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

	function limitResult($sql, $limit,$offset) {
		return "$sql LIMIT $limit ".(is_numeric($offset)?" OFFSET {$offset} ":"");
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
	function timestamp( $ts=0 ) {
		return wfTimestamp(TS_DB,$ts);
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
		return "[http://www.postgresql.org/ PostgreSQL]";
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		$res = $this->query( "SELECT version()" );
		$row = $this->fetchRow( $res );
		$version = $row[0];
		$this->freeResult( $res );
		return $version;
	}


	/**
	 * Query whether a given table exists (in the given schema, or the default mw one if not given)
	 */
	function tableExists( $table, $schema = false ) {
		global $wgDBmwschema;
		if (! $schema )
			$schema = $wgDBmwschema;
		$etable = preg_replace("/'/", "''", $table);
		$eschema = preg_replace("/'/", "''", $schema);
		$SQL = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n "
			. "WHERE c.relnamespace = n.oid AND c.relname = '$etable' AND n.nspname = '$eschema'";
		$res = $this->query( $SQL );
		$count = $res ? pg_num_rows($res) : 0;
		if ($res)
			$this->freeResult( $res );
		return $count;
	}


	/**
	 * Query whether a given schema exists. Returns the name of the owner
	 */
	function schemaExists( $schema ) {
		$eschema = preg_replace("/'/", "''", $schema);
		$SQL = "SELECT rolname FROM pg_catalog.pg_namespace n, pg_catalog.pg_roles r "
				."WHERE n.nspowner=r.oid AND n.nspname = '$eschema'";
		$res = $this->query( $SQL );
		$owner = $res ? pg_num_rows($res) ? pg_fetch_result($res, 0, 0) : false : false;
		if ($res)
			$this->freeResult($res);
		return $owner;
	}

	/**
	 * Query whether a given column exists in the mediawiki schema
	 */
	function fieldExists( $table, $field ) {
		global $wgDBmwschema;
		$etable = preg_replace("/'/", "''", $table);
		$eschema = preg_replace("/'/", "''", $wgDBmwschema);
		$ecol = preg_replace("/'/", "''", $field);
		$SQL = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n, pg_catalog.pg_attribute a "
			. "WHERE c.relnamespace = n.oid AND c.relname = '$etable' AND n.nspname = '$eschema' "
			. "AND a.attrelid = c.oid AND a.attname = '$ecol'";
		$res = $this->query( $SQL );
		$count = $res ? pg_num_rows($res) : 0;
		if ($res)
			$this->freeResult( $res );
		return $count;
	}

	function fieldInfo( $table, $field ) {
		$res = $this->query( "SELECT $field FROM $table LIMIT 1" );
		$type = pg_field_type( $res, 0 );
		return $type;
	}

	function begin( $fname = 'DatabasePostgrs::begin' ) {
		$this->query( 'BEGIN', $fname );
		$this->mTrxLevel = 1;
	}
	function immediateCommit( $fname = 'DatabasePostgres::immediateCommit' ) {
		return true;
	}
	function commit( $fname = 'DatabasePostgres::commit' ) {
		$this->query( 'COMMIT', $fname );
		$this->mTrxLevel = 0;
	}

	/* Not even sure why this is used in the main codebase... */
	function limitResultForUpdate($sql, $num) {
		return $sql;
	}

	function update_interwiki() {
		## Avoid the non-standard "REPLACE INTO" syntax
		## Called by config/index.php
		$f = fopen( "../maintenance/interwiki.sql", 'r' );
		if ($f == false ) {
			dieout( "<li>Could not find the interwiki.sql file");
		}
		## We simply assume it is already empty as we have just created it
		$SQL = "INSERT INTO interwiki(iw_prefix,iw_url,iw_local) VALUES ";
		while ( ! feof( $f ) ) {
			$line = fgets($f,1024);
			if (!preg_match("/^\s*(\(.+?),(\d)\)/", $line, $matches)) {
				continue;
			}
			$yesno = $matches[2]; ## ? "'true'" : "'false'";
			$this->query("$SQL $matches[1],$matches[2])");
		}
		print " (table interwiki successfully populated)...\n";
	}

	function encodeBlob($b) {
		return array('bytea',pg_escape_bytea($b));
	}
	function decodeBlob($b) {
		return pg_unescape_bytea( $b );
	}

	function strencode( $s ) { ## Should not be called by us
		return pg_escape_string( $s );
	}

	function addQuotes( $s ) {
		if ( is_null( $s ) ) {
			return 'NULL';
		} else if (is_array( $s )) { ## Assume it is bytea data
			return "E'$s[1]'";
		}
		return "'" . pg_escape_string($s) . "'";
		return "E'" . pg_escape_string($s) . "'";
	}

}

?>
