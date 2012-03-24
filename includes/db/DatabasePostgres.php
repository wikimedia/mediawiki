<?php
/**
 * This is the Postgres database abstraction layer.
 *
 * @file
 * @ingroup Database
 */

class PostgresField implements Field {
	private $name, $tablename, $type, $nullable, $max_length, $deferred, $deferrable, $conname;

	/**
	 * @param $db DatabaseBase
	 * @param  $table
	 * @param  $field
	 * @return null|PostgresField
	 */
	static function fromText( $db, $table, $field ) {
		global $wgDBmwschema;

		$q = <<<SQL
SELECT
 attnotnull, attlen, COALESCE(conname, '') AS conname,
 COALESCE(condeferred, 'f') AS deferred,
 COALESCE(condeferrable, 'f') AS deferrable,
 CASE WHEN typname = 'int2' THEN 'smallint'
  WHEN typname = 'int4' THEN 'integer'
  WHEN typname = 'int8' THEN 'bigint'
  WHEN typname = 'bpchar' THEN 'char'
 ELSE typname END AS typname
FROM pg_class c
JOIN pg_namespace n ON (n.oid = c.relnamespace)
JOIN pg_attribute a ON (a.attrelid = c.oid)
JOIN pg_type t ON (t.oid = a.atttypid)
LEFT JOIN pg_constraint o ON (o.conrelid = c.oid AND a.attnum = ANY(o.conkey) AND o.contype = 'f')
WHERE relkind = 'r'
AND nspname=%s
AND relname=%s
AND attname=%s;
SQL;

		$table = $db->tableName( $table, 'raw' );
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
		$n = new PostgresField;
		$n->type = $row->typname;
		$n->nullable = ( $row->attnotnull == 'f' );
		$n->name = $field;
		$n->tablename = $table;
		$n->max_length = $row->attlen;
		$n->deferrable = ( $row->deferrable == 't' );
		$n->deferred = ( $row->deferred == 't' );
		$n->conname = $row->conname;
		return $n;
	}

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tablename;
	}

	function type() {
		return $this->type;
	}

	function isNullable() {
		return $this->nullable;
	}

	function maxLength() {
		return $this->max_length;
	}

	function is_deferrable() {
		return $this->deferrable;
	}

	function is_deferred() {
		return $this->deferred;
	}

	function conname() {
		return $this->conname;
	}

}

/**
 * @ingroup Database
 */
class DatabasePostgres extends DatabaseBase {
	var $mInsertId = null;
	var $mLastResult = null;
	var $numeric_version = null;
	var $mAffectedRows = null;

	function getType() {
		return 'postgres';
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
	function functionalIndexes() {
		return true;
	}

	function hasConstraint( $name ) {
		global $wgDBmwschema;
		$SQL = "SELECT 1 FROM pg_catalog.pg_constraint c, pg_catalog.pg_namespace n WHERE c.connamespace = n.oid AND conname = '" .
				pg_escape_string( $this->mConn, $name ) . "' AND n.nspname = '" . pg_escape_string( $this->mConn, $wgDBmwschema ) ."'";
		$res = $this->doQuery( $SQL );
		return $this->numRows( $res );
	}

	/**
	 * Usually aborts on failure
	 */
	function open( $server, $user, $password, $dbName ) {
		# Test for Postgres support, to avoid suppressed fatal error
		if ( !function_exists( 'pg_connect' ) ) {
			throw new DBConnectionError( $this, "Postgres functions missing, have you compiled PHP with the --with-pgsql option?\n (Note: if you recently installed PHP, you may need to restart your webserver and database)\n" );
		}

		global $wgDBport;

		if ( !strlen( $user ) ) { # e.g. the class is being loaded
			return;
		}

		$this->close();
		$this->mServer = $server;
		$port = $wgDBport;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$connectVars = array(
			'dbname' => $dbName,
			'user' => $user,
			'password' => $password
		);
		if ( $server != false && $server != '' ) {
			$connectVars['host'] = $server;
		}
		if ( $port != false && $port != '' ) {
			$connectVars['port'] = $port;
		}
		$connectString = $this->makeConnectionString( $connectVars, PGSQL_CONNECT_FORCE_NEW );

		$this->installErrorHandler();
		$this->mConn = pg_connect( $connectString );
		$phpError = $this->restoreErrorHandler();

		if ( !$this->mConn ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError() . "\n" );
			throw new DBConnectionError( $this, str_replace( "\n", ' ', $phpError ) );
		}

		$this->mOpened = true;

		global $wgCommandLineMode;
		# If called from the command-line (e.g. importDump), only show errors
		if ( $wgCommandLineMode ) {
			$this->doQuery( "SET client_min_messages = 'ERROR'" );
		}

		$this->query( "SET client_encoding='UTF8'", __METHOD__ );
		$this->query( "SET datestyle = 'ISO, YMD'", __METHOD__ );
		$this->query( "SET timezone = 'GMT'", __METHOD__ );
		$this->query( "SET standard_conforming_strings = on", __METHOD__ );

		global $wgDBmwschema;
		if ( $this->schemaExists( $wgDBmwschema ) ) {
			$safeschema = $this->addIdentifierQuotes( $wgDBmwschema );
			$this->doQuery( "SET search_path = $safeschema" );
		} else {
			$this->doQuery( "SET search_path = public" );
		}

		return $this->mConn;
	}

	/**
	 * Postgres doesn't support selectDB in the same way MySQL does. So if the
	 * DB name doesn't match the open connection, open a new one
	 * @return
	 */
	function selectDB( $db ) {
		if ( $this->mDBname !== $db ) {
			return (bool)$this->open( $this->mServer, $this->mUser, $this->mPassword, $db );
		} else {
			return true;
		}
	}

	function makeConnectionString( $vars ) {
		$s = '';
		foreach ( $vars as $name => $value ) {
			$s .= "$name='" . str_replace( "'", "\\'", $value ) . "' ";
		}
		return $s;
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

	protected function doQuery( $sql ) {
		if ( function_exists( 'mb_convert_encoding' ) ) {
			$sql = mb_convert_encoding( $sql, 'UTF-8' );
		}
		$this->mLastResult = pg_query( $this->mConn, $sql );
		$this->mAffectedRows = null; // use pg_affected_rows(mLastResult)
		return $this->mLastResult;
	}

	function queryIgnore( $sql, $fname = 'DatabasePostgres::queryIgnore' ) {
		return $this->query( $sql, $fname, true );
	}

	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$ok = pg_free_result( $res );
		wfRestoreWarnings();
		if ( !$ok ) {
			throw new DBUnexpectedError( $this, "Unable to free Postgres result\n" );
		}
	}

	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$row = pg_fetch_object( $res );
		wfRestoreWarnings();
		# @todo FIXME: HACK HACK HACK HACK debug

		# @todo hashar: not sure if the following test really trigger if the object
		#          fetching failed.
		if( pg_last_error( $this->mConn ) ) {
			throw new DBUnexpectedError( $this, 'SQL error: ' . htmlspecialchars( pg_last_error( $this->mConn ) ) );
		}
		return $row;
	}

	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$row = pg_fetch_array( $res );
		wfRestoreWarnings();
		if( pg_last_error( $this->mConn ) ) {
			throw new DBUnexpectedError( $this, 'SQL error: ' . htmlspecialchars( pg_last_error( $this->mConn ) ) );
		}
		return $row;
	}

	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		wfSuppressWarnings();
		$n = pg_num_rows( $res );
		wfRestoreWarnings();
		if( pg_last_error( $this->mConn ) ) {
			throw new DBUnexpectedError( $this, 'SQL error: ' . htmlspecialchars( pg_last_error( $this->mConn ) ) );
		}
		return $n;
	}

	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_num_fields( $res );
	}

	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_field_name( $res, $n );
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
		return pg_result_seek( $res, $row );
	}

	function lastError() {
		if ( $this->mConn ) {
			return pg_last_error();
		} else {
			return 'No database connection';
		}
	}
	function lastErrno() {
		return pg_last_error() ? 1 : 0;
	}

	function affectedRows() {
		if ( !is_null( $this->mAffectedRows ) ) {
			// Forced result for simulated queries
			return $this->mAffectedRows;
		}
		if( empty( $this->mLastResult ) ) {
			return 0;
		}
		return pg_affected_rows( $this->mLastResult );
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * This is not necessarily an accurate estimate, so use sparingly
	 * Returns -1 if count cannot be found
	 * Takes same arguments as Database::select()
	 */
	function estimateRowCount( $table, $vars = '*', $conds='', $fname = 'DatabasePostgres::estimateRowCount', $options = array() ) {
		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		$rows = -1;
		if ( $res ) {
			$row = $this->fetchRow( $res );
			$count = array();
			if( preg_match( '/rows=(\d+)/', $row[0], $count ) ) {
				$rows = $count[1];
			}
		}
		return $rows;
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo( $table, $index, $fname = 'DatabasePostgres::indexInfo' ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='$table'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		foreach ( $res as $row ) {
			if ( $row->indexname == $this->indexName( $index ) ) {
				return $row;
			}
		}
		return false;
	}

	function indexUnique( $table, $index, $fname = 'DatabasePostgres::indexUnique' ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='{$table}'".
			" AND indexdef LIKE 'CREATE UNIQUE%(" .
			$this->strencode( $this->indexName( $index ) ) .
			")'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		foreach ( $res as $row ) {
			return true;
		}
		return false;
	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $args may be a single associative array, or an array of these with numeric keys,
	 * for multi-row insert (Postgres version 8.2 and above only).
	 *
	 * @param $table   String: Name of the table to insert to.
	 * @param $args    Array: Items to insert into the table.
	 * @param $fname   String: Name of the function, for profiling
	 * @param $options String or Array. Valid options: IGNORE
	 *
	 * @return bool Success of insert operation. IGNORE always returns true.
	 */
	function insert( $table, $args, $fname = 'DatabasePostgres::insert', $options = array() ) {
		if ( !count( $args ) ) {
			return true;
		}

		$table = $this->tableName( $table );
		if (! isset( $this->numeric_version ) ) {
			$this->getServerVersion();
		}

		if ( !is_array( $options ) ) {
			$options = array( $options );
		}

		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$multi = true;
			$keys = array_keys( $args[0] );
		} else {
			$multi = false;
			$keys = array_keys( $args );
		}

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$ignore = in_array( 'IGNORE', $options ) ? 'mw' : '';

		// If we are not in a transaction, we need to be for savepoint trickery
		$didbegin = 0;
		if ( $ignore ) {
			if ( !$this->mTrxLevel ) {
				$this->begin();
				$didbegin = 1;
			}
			$olde = error_reporting( 0 );
			// For future use, we may want to track the number of actual inserts
			// Right now, insert (all writes) simply return true/false
			$numrowsinserted = 0;
		}

		$sql = "INSERT INTO $table (" . implode( ',', $keys ) . ') VALUES ';

		if ( $multi ) {
			if ( $this->numeric_version >= 8.2 && !$ignore ) {
				$first = true;
				foreach ( $args as $row ) {
					if ( $first ) {
						$first = false;
					} else {
						$sql .= ',';
					}
					$sql .= '(' . $this->makeList( $row ) . ')';
				}
				$res = (bool)$this->query( $sql, $fname, $ignore );
			} else {
				$res = true;
				$origsql = $sql;
				foreach ( $args as $row ) {
					$tempsql = $origsql;
					$tempsql .= '(' . $this->makeList( $row ) . ')';

					if ( $ignore ) {
						pg_query( $this->mConn, "SAVEPOINT $ignore" );
					}

					$tempres = (bool)$this->query( $tempsql, $fname, $ignore );

					if ( $ignore ) {
						$bar = pg_last_error();
						if ( $bar != false ) {
							pg_query( $this->mConn, "ROLLBACK TO $ignore" );
						} else {
							pg_query( $this->mConn, "RELEASE $ignore" );
							$numrowsinserted++;
						}
					}

					// If any of them fail, we fail overall for this function call
					// Note that this will be ignored if IGNORE is set
					if ( !$tempres ) {
						$res = false;
					}
				}
			}
		} else {
			// Not multi, just a lone insert
			if ( $ignore ) {
				pg_query($this->mConn, "SAVEPOINT $ignore");
			}

			$sql .= '(' . $this->makeList( $args ) . ')';
			$res = (bool)$this->query( $sql, $fname, $ignore );
			if ( $ignore ) {
				$bar = pg_last_error();
				if ( $bar != false ) {
					pg_query( $this->mConn, "ROLLBACK TO $ignore" );
				} else {
					pg_query( $this->mConn, "RELEASE $ignore" );
					$numrowsinserted++;
				}
			}
		}
		if ( $ignore ) {
			$olde = error_reporting( $olde );
			if ( $didbegin ) {
				$this->commit();
			}

			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;

			// IGNORE always returns true
			return true;
		}

		return $res;
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	 * Source items may be literals rather then field names, but strings should be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 * @todo FIXME: Implement this a little better (seperate select/insert)?
	 */
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'DatabasePostgres::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$destTable = $this->tableName( $destTable );

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$ignore = in_array( 'IGNORE', $insertOptions ) ? 'mw' : '';

		if( is_array( $insertOptions ) ) {
			$insertOptions = implode( ' ', $insertOptions ); // FIXME: This is unused
		}
		if( !is_array( $selectOptions ) ) {
			$selectOptions = array( $selectOptions );
		}
		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );
		if( is_array( $srcTable ) ) {
			$srcTable = implode( ',', array_map( array( &$this, 'tableName' ), $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}

		// If we are not in a transaction, we need to be for savepoint trickery
		$didbegin = 0;
		if ( $ignore ) {
			if( !$this->mTrxLevel ) {
				$this->begin();
				$didbegin = 1;
			}
			$olde = error_reporting( 0 );
			$numrowsinserted = 0;
			pg_query( $this->mConn, "SAVEPOINT $ignore");
		}

		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
				" SELECT $startOpts " . implode( ',', $varMap ) .
				" FROM $srcTable $useIndex";

		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		$sql .= " $tailOpts";

		$res = (bool)$this->query( $sql, $fname, $ignore );
		if( $ignore ) {
			$bar = pg_last_error();
			if( $bar != false ) {
				pg_query( $this->mConn, "ROLLBACK TO $ignore" );
			} else {
				pg_query( $this->mConn, "RELEASE $ignore" );
				$numrowsinserted++;
			}
			$olde = error_reporting( $olde );
			if( $didbegin ) {
				$this->commit();
			}

			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;

			// IGNORE always returns true
			return true;
		}

		return $res;
	}

	function tableName( $name, $format = 'quoted' ) {
		# Replace reserved words with better ones
		switch( $name ) {
			case 'user':
				return $this->realTableName( 'mwuser', $format );
			case 'text':
				return $this->realTableName( 'pagecontent', $format );
			default:
				return $this->realTableName( $name, $format );
		}
	}

	/* Don't cheat on installer */
	function realTableName( $name, $format = 'quoted' ) {
		return parent::tableName( $name, $format );
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 */
	function nextSequenceValue( $seqName ) {
		$safeseq = str_replace( "'", "''", $seqName );
		$res = $this->query( "SELECT nextval('$safeseq')" );
		$row = $this->fetchRow( $res );
		$this->mInsertId = $row[0];
		return $this->mInsertId;
	}

	/**
	 * Return the current value of a sequence. Assumes it has been nextval'ed in this session.
	 */
	function currentSequenceValue( $seqName ) {
		$safeseq = str_replace( "'", "''", $seqName );
		$res = $this->query( "SELECT currval('$safeseq')" );
		$row = $this->fetchRow( $res );
		$currval = $row[0];
		return $currval;
	}

	# Returns the size of a text field, or -1 for "unlimited"
	function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SELECT t.typname as ftype,a.atttypmod as size
			FROM pg_class c, pg_attribute a, pg_type t
			WHERE relname='$table' AND a.attrelid=c.oid AND
				a.atttypid=t.oid and a.attname='$field'";
		$res =$this->query( $sql );
		$row = $this->fetchObject( $res );
		if ( $row->ftype == 'varchar' ) {
			$size = $row->size - 4;
		} else {
			$size = $row->size;
		}
		return $size;
	}

	function limitResult( $sql, $limit, $offset = false ) {
		return "$sql LIMIT $limit " . ( is_numeric( $offset ) ? " OFFSET {$offset} " : '' );
	}

	function wasDeadlock() {
		return $this->lastErrno() == '40P01';
	}

	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = 'DatabasePostgres::duplicateTableStructure' ) {
		$newName = $this->addIdentifierQuotes( $newName );
		$oldName = $this->addIdentifierQuotes( $oldName );
		return $this->query( 'CREATE ' . ( $temporary ? 'TEMPORARY ' : '' ) . " TABLE $newName (LIKE $oldName INCLUDING DEFAULTS)", $fname );
	}

	function listTables( $prefix = null, $fname = 'DatabasePostgres::listTables' ) {
		global $wgDBmwschema;
		$eschema = $this->addQuotes( $wgDBmwschema );
		$result = $this->query( "SELECT tablename FROM pg_tables WHERE schemaname = $eschema", $fname );

		$endArray = array();

		foreach( $result as $table ) {
			$vars = get_object_vars($table);
			$table = array_pop( $vars );
			if( !$prefix || strpos( $table, $prefix ) === 0 ) {
				$endArray[] = $table;
			}
		}

		return $endArray;
	}

	function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_POSTGRES, $ts );
	}

	/**
	 * Return aggregated value function call
	 */
	function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuedata;
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	public static function getSoftwareLink() {
		return '[http://www.postgresql.org/ PostgreSQL]';
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		if ( !isset( $this->numeric_version ) ) {
			$versionInfo = pg_version( $this->mConn );
			if ( version_compare( $versionInfo['client'], '7.4.0', 'lt' ) ) {
				// Old client, abort install
				$this->numeric_version = '7.3 or earlier';
			} elseif ( isset( $versionInfo['server'] ) ) {
				// Normal client
				$this->numeric_version = $versionInfo['server'];
			} else {
				// Bug 16937: broken pgsql extension from PHP<5.3
				$this->numeric_version = pg_parameter_status( $this->mConn, 'server_version' );
			}
		}
		return $this->numeric_version;
	}

	/**
	 * Query whether a given relation exists (in the given schema, or the
	 * default mw one if not given)
	 */
	function relationExists( $table, $types, $schema = false ) {
		global $wgDBmwschema;
		if ( !is_array( $types ) ) {
			$types = array( $types );
		}
		if ( !$schema ) {
			$schema = $wgDBmwschema;
		}
		$table = $this->realTableName( $table, 'raw' );
		$etable = $this->addQuotes( $table );
		$eschema = $this->addQuotes( $schema );
		$SQL = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n "
			. "WHERE c.relnamespace = n.oid AND c.relname = $etable AND n.nspname = $eschema "
			. "AND c.relkind IN ('" . implode( "','", $types ) . "')";
		$res = $this->query( $SQL );
		$count = $res ? $res->numRows() : 0;
		return (bool)$count;
	}

	/**
	 * For backward compatibility, this function checks both tables and
	 * views.
	 */
	function tableExists( $table, $fname = __METHOD__, $schema = false ) {
		return $this->relationExists( $table, array( 'r', 'v' ), $schema );
	}

	function sequenceExists( $sequence, $schema = false ) {
		return $this->relationExists( $sequence, 'S', $schema );
	}

	function triggerExists( $table, $trigger ) {
		global $wgDBmwschema;

		$q = <<<SQL
	SELECT 1 FROM pg_class, pg_namespace, pg_trigger
		WHERE relnamespace=pg_namespace.oid AND relkind='r'
			  AND tgrelid=pg_class.oid
			  AND nspname=%s AND relname=%s AND tgname=%s
SQL;
		$res = $this->query(
			sprintf(
				$q,
				$this->addQuotes( $wgDBmwschema ),
				$this->addQuotes( $table ),
				$this->addQuotes( $trigger )
			)
		);
		if ( !$res ) {
			return null;
		}
		$rows = $res->numRows();
		return $rows;
	}

	function ruleExists( $table, $rule ) {
		global $wgDBmwschema;
		$exists = $this->selectField( 'pg_rules', 'rulename',
			array(
				'rulename' => $rule,
				'tablename' => $table,
				'schemaname' => $wgDBmwschema
			)
		);
		return $exists === $rule;
	}

	function constraintExists( $table, $constraint ) {
		global $wgDBmwschema;
		$SQL = sprintf( "SELECT 1 FROM information_schema.table_constraints ".
			   "WHERE constraint_schema = %s AND table_name = %s AND constraint_name = %s",
			$this->addQuotes( $wgDBmwschema ),
			$this->addQuotes( $table ),
			$this->addQuotes( $constraint )
		);
		$res = $this->query( $SQL );
		if ( !$res ) {
			return null;
		}
		$rows = $res->numRows();
		return $rows;
	}

	/**
	 * Query whether a given schema exists. Returns true if it does, false if it doesn't.
	 */
	function schemaExists( $schema ) {
		$exists = $this->selectField( '"pg_catalog"."pg_namespace"', 1,
			array( 'nspname' => $schema ), __METHOD__ );
		return (bool)$exists;
	}

	/**
	 * Returns true if a given role (i.e. user) exists, false otherwise.
	 */
	function roleExists( $roleName ) {
		$exists = $this->selectField( '"pg_catalog"."pg_roles"', 1,
			array( 'rolname' => $roleName ), __METHOD__ );
		return (bool)$exists;
	}

	function fieldInfo( $table, $field ) {
		return PostgresField::fromText( $this, $table, $field );
	}

	/**
	 * pg_field_type() wrapper
	 */
	function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_field_type( $res, $index );
	}

	/* Not even sure why this is used in the main codebase... */
	function limitResultForUpdate( $sql, $num ) {
		return $sql;
	}

	/**
	 * @param $b
	 * @return Blob
	 */
	function encodeBlob( $b ) {
		return new Blob( pg_escape_bytea( $this->mConn, $b ) );
	}

	function decodeBlob( $b ) {
		if ( $b instanceof Blob ) {
			$b = $b->fetch();
		}
		return pg_unescape_bytea( $b );
	}

	function strencode( $s ) { # Should not be called by us
		return pg_escape_string( $this->mConn, $s );
	}

	/**
	 * @param $s null|bool|Blob
	 * @return int|string
	 */
	function addQuotes( $s ) {
		if ( is_null( $s ) ) {
			return 'NULL';
		} elseif ( is_bool( $s ) ) {
			return intval( $s );
		} elseif ( $s instanceof Blob ) {
			return "'" . $s->fetch( $s ) . "'";
		}
		return "'" . pg_escape_string( $this->mConn, $s ) . "'";
	}

	/**
	 * Postgres specific version of replaceVars.
	 * Calls the parent version in Database.php
	 *
	 * @private
	 *
	 * @param $ins String: SQL string, read from a stream (usually tables.sql)
	 *
	 * @return string SQL string
	 */
	protected function replaceVars( $ins ) {
		$ins = parent::replaceVars( $ins );

		if ( $this->numeric_version >= 8.3 ) {
			// Thanks for not providing backwards-compatibility, 8.3
			$ins = preg_replace( "/to_tsvector\s*\(\s*'default'\s*,/", 'to_tsvector(', $ins );
		}

		if ( $this->numeric_version <= 8.1 ) { // Our minimum version
			$ins = str_replace( 'USING gin', 'USING gist', $ins );
		}

		return $ins;
	}

	/**
	 * Various select options
	 *
	 * @private
	 *
	 * @param $options Array: an associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions( $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = $useIndex = '';

		$noKeyOptions = array();
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		if ( isset( $options['GROUP BY'] ) ) {
			$gb = is_array( $options['GROUP BY'] )
				? implode( ',', $options['GROUP BY'] )
				: $options['GROUP BY'];
			$preLimitTail .= " GROUP BY {$gb}";
		}

		if ( isset( $options['HAVING'] ) ) {
			$preLimitTail .= " HAVING {$options['HAVING']}";
		}

		if ( isset( $options['ORDER BY'] ) ) {
			$ob = is_array( $options['ORDER BY'] )
				? implode( ',', $options['ORDER BY'] )
				: $options['ORDER BY'];
			$preLimitTail .= " ORDER BY {$ob}";
		}

		//if ( isset( $options['LIMIT'] ) ) {
		//	$tailOpts .= $this->limitResult( '', $options['LIMIT'],
		//		isset( $options['OFFSET'] ) ? $options['OFFSET']
		//		: false );
		//}

		if ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}
		if ( isset( $noKeyOptions['LOCK IN SHARE MODE'] ) ) {
			$postLimitTail .= ' LOCK IN SHARE MODE';
		}
		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		return array( $startOpts, $useIndex, $preLimitTail, $postLimitTail );
	}

	function setFakeMaster( $enabled = true ) {}

	function getDBname() {
		return $this->mDBname;
	}

	function getServer() {
		return $this->mServer;
	}

	function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	public function getSearchEngine() {
		return 'SearchPostgres';
	}

	public function streamStatementEnd( &$sql, &$newLine ) {
		# Allow dollar quoting for function declarations
		if ( substr( $newLine, 0, 4 ) == '$mw$' ) {
			if ( $this->delimiter ) {
				$this->delimiter = false;
			}
			else {
				$this->delimiter = ';';
			}
		}
		return parent::streamStatementEnd( $sql, $newLine );
	}
} // end DatabasePostgres class
