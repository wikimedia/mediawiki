<?php
/**
 * This is the Postgres database abstraction layer.
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
 */

class PostgresField implements Field {
	private $name, $tablename, $type, $nullable, $max_length, $deferred, $deferrable, $conname,
		$has_default, $default;

	/**
	 * @param $db DatabaseBase
	 * @param  $table
	 * @param  $field
	 * @return null|PostgresField
	 */
	static function fromText( $db, $table, $field ) {
		$q = <<<SQL
SELECT
 attnotnull, attlen, conname AS conname,
 atthasdef,
 adsrc,
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
LEFT JOIN pg_attrdef d on c.oid=d.adrelid and a.attnum=d.adnum
WHERE relkind = 'r'
AND nspname=%s
AND relname=%s
AND attname=%s;
SQL;

		$table = $db->tableName( $table, 'raw' );
		$res = $db->query(
			sprintf( $q,
				$db->addQuotes( $db->getCoreSchema() ),
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
		$n->has_default = ( $row->atthasdef === 't' );
		$n->default = $row->adsrc;
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
	/**
	 * @since 1.19
	 */
	function defaultValue() {
		if( $this->has_default ) {
			return $this->default;
		} else {
			return false;
		}
	}

}

/**
 * Used to debug transaction processing
 * Only used if $wgDebugDBTransactions is true
 *
 * @since 1.19
 * @ingroup Database
 */
class PostgresTransactionState {

	static $WATCHED = array(
		array(
			"desc" => "%s: Connection state changed from %s -> %s\n",
			"states" => array(
				PGSQL_CONNECTION_OK       => "OK",
				PGSQL_CONNECTION_BAD      => "BAD"
			)
		),
		array(
			"desc" => "%s: Transaction state changed from %s -> %s\n",
			"states" => array(
				PGSQL_TRANSACTION_IDLE    => "IDLE",
				PGSQL_TRANSACTION_ACTIVE  => "ACTIVE",
				PGSQL_TRANSACTION_INTRANS => "TRANS",
				PGSQL_TRANSACTION_INERROR => "ERROR",
				PGSQL_TRANSACTION_UNKNOWN => "UNKNOWN"
			)
		)
	);

	public function __construct( $conn ) {
		$this->mConn = $conn;
		$this->update();
		$this->mCurrentState = $this->mNewState;
	}

	public function update() {
		$this->mNewState = array(
			pg_connection_status( $this->mConn ),
			pg_transaction_status( $this->mConn )
		);
	}

	public function check() {
		global $wgDebugDBTransactions;
		$this->update();
		if ( $wgDebugDBTransactions ) {
			if ( $this->mCurrentState !== $this->mNewState ) {
				$old = reset( $this->mCurrentState );
				$new = reset( $this->mNewState );
				foreach ( self::$WATCHED as $watched ) {
					if ($old !== $new) {
						$this->log_changed($old, $new, $watched);
					}
					$old = next( $this->mCurrentState );
					$new = next( $this->mNewState );

				}
			}
		}
		$this->mCurrentState = $this->mNewState;
	}

	protected function describe_changed( $status, $desc_table ) {
		if( isset( $desc_table[$status] ) ) {
			return $desc_table[$status];
		} else {
			return "STATUS " . $status;
		}
	}

	protected function log_changed( $old, $new, $watched ) {
		wfDebug(sprintf($watched["desc"],
			$this->mConn,
			$this->describe_changed( $old, $watched["states"] ),
			$this->describe_changed( $new, $watched["states"] ))
		);
	}
}

/**
 * Manage savepoints within a transaction
 * @ingroup Database
 * @since 1.19
 */
class SavepointPostgres {
	/**
	 * Establish a savepoint within a transaction
	 */
	protected $dbw;
	protected $id;
	protected $didbegin;

	public function __construct ($dbw, $id) {
		$this->dbw = $dbw;
		$this->id = $id;
		$this->didbegin = false;
		/* If we are not in a transaction, we need to be for savepoint trickery */
		if ( !$dbw->trxLevel() ) {
				$dbw->begin( "FOR SAVEPOINT" );
				$this->didbegin = true;
		}
	}

	public function __destruct() {
		if ( $this->didbegin ) {
			$this->dbw->rollback();
		}
	}

	public function commit() {
		if ( $this->didbegin ) {
			$this->dbw->commit();
		}
	}

	protected function query( $keyword, $msg_ok, $msg_failed ) {
		global $wgDebugDBTransactions;
		if ( $this->dbw->doQuery( $keyword . " " . $this->id ) !== false ) {
			if ( $wgDebugDBTransactions ) {
				wfDebug( sprintf ($msg_ok, $this->id ) );
			}
		} else {
			wfDebug( sprintf ($msg_failed, $this->id ) );
		}
	}

	public function savepoint() {
		$this->query("SAVEPOINT",
			"Transaction state: savepoint \"%s\" established.\n",
			"Transaction state: establishment of savepoint \"%s\" FAILED.\n"
		);
	}

	public function release() {
		$this->query("RELEASE",
			"Transaction state: savepoint \"%s\" released.\n",
			"Transaction state: release of savepoint \"%s\" FAILED.\n"
		);
	}

	public function rollback() {
		$this->query("ROLLBACK TO",
			"Transaction state: savepoint \"%s\" rolled back.\n",
			"Transaction state: rollback of savepoint \"%s\" FAILED.\n"
		);
	}

	public function __toString() {
		return (string)$this->id;
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
		$SQL = "SELECT 1 FROM pg_catalog.pg_constraint c, pg_catalog.pg_namespace n WHERE c.connamespace = n.oid AND conname = '" .
				pg_escape_string( $this->mConn, $name ) . "' AND n.nspname = '" . pg_escape_string( $this->mConn, $this->getCoreSchema() ) ."'";
		$res = $this->doQuery( $SQL );
		return $this->numRows( $res );
	}

	/**
	 * Usually aborts on failure
	 * @param string $server
	 * @param string $user
	 * @param string $password
	 * @param string $dbName
	 * @throws DBConnectionError
	 * @return DatabaseBase|null
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
		if ( $this->mFlags & DBO_SSL ) {
			$connectVars['sslmode'] = 1;
		}

		$this->connectString = $this->makeConnectionString( $connectVars, PGSQL_CONNECT_FORCE_NEW );
		$this->close();
		$this->installErrorHandler();
		$this->mConn = pg_connect( $this->connectString );
		$phpError = $this->restoreErrorHandler();

		if ( !$this->mConn ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError() . "\n" );
			throw new DBConnectionError( $this, str_replace( "\n", ' ', $phpError ) );
		}

		$this->mOpened = true;
		$this->mTransactionState = new PostgresTransactionState( $this->mConn );

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
		$this->determineCoreSchema( $wgDBmwschema );

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
	 * @return bool
	 */
	protected function closeConnection() {
		return pg_close( $this->mConn );
	}

	public function doQuery( $sql ) {
		if ( function_exists( 'mb_convert_encoding' ) ) {
			$sql = mb_convert_encoding( $sql, 'UTF-8' );
		}
		$this->mTransactionState->check();
		if( pg_send_query( $this->mConn, $sql ) === false ) {
			throw new DBUnexpectedError( $this, "Unable to post new query to PostgreSQL\n" );
		}
		$this->mLastResult = pg_get_result( $this->mConn );
		$this->mTransactionState->check();
		$this->mAffectedRows = null;
		if ( pg_result_error( $this->mLastResult ) ) {
			return false;
		}
		return $this->mLastResult;
	}

	protected function dumpError () {
		$diags = array( PGSQL_DIAG_SEVERITY,
				PGSQL_DIAG_SQLSTATE,
				PGSQL_DIAG_MESSAGE_PRIMARY,
				PGSQL_DIAG_MESSAGE_DETAIL,
				PGSQL_DIAG_MESSAGE_HINT,
				PGSQL_DIAG_STATEMENT_POSITION,
				PGSQL_DIAG_INTERNAL_POSITION,
				PGSQL_DIAG_INTERNAL_QUERY,
				PGSQL_DIAG_CONTEXT,
				PGSQL_DIAG_SOURCE_FILE,
				PGSQL_DIAG_SOURCE_LINE,
				PGSQL_DIAG_SOURCE_FUNCTION );
		foreach ( $diags as $d ) {
			wfDebug( sprintf("PgSQL ERROR(%d): %s\n", $d, pg_result_error_field( $this->mLastResult, $d ) ) );
		}
	}

	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		/* Transaction stays in the ERROR state until rolledback */
		if ( $tempIgnore ) {
			/* Check for constraint violation */
			if ( $errno === '23505' ) {
				parent::reportQueryError( $error, $errno, $sql, $fname, $tempIgnore );
				return;
			}
		}
		/* Don't ignore serious errors */
		$this->rollback( __METHOD__ );
		parent::reportQueryError( $error, $errno, $sql, $fname, false );
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
	 * @return null
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
			if ( $this->mLastResult ) {
				return pg_result_error( $this->mLastResult );
			} else {
				return pg_last_error();
			}
		} else {
			return 'No database connection';
		}
	}
	function lastErrno() {
		if ( $this->mLastResult ) {
			return pg_result_error_field( $this->mLastResult, PGSQL_DIAG_SQLSTATE );
		} else {
			return false;
		}
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
	 * @return int
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
	 * @return bool|null
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

	/**
	 * Returns is of attributes used in index
	 *
	 * @since 1.19
	 * @return Array
	 */
	function indexAttributes ( $index, $schema = false ) {
		if ( $schema === false )
			$schema = $this->getCoreSchema();
		/*
		 * A subquery would be not needed if we didn't care about the order
		 * of attributes, but we do
		 */
		$sql = <<<__INDEXATTR__

			SELECT opcname,
				attname,
				i.indoption[s.g] as option,
				pg_am.amname
			FROM
				(SELECT generate_series(array_lower(isub.indkey,1), array_upper(isub.indkey,1)) AS g
					FROM
						pg_index isub
					JOIN pg_class cis
						ON cis.oid=isub.indexrelid
					JOIN pg_namespace ns
						ON cis.relnamespace = ns.oid
					WHERE cis.relname='$index' AND ns.nspname='$schema') AS s,
				pg_attribute,
				pg_opclass opcls,
				pg_am,
				pg_class ci
				JOIN pg_index i
					ON ci.oid=i.indexrelid
				JOIN pg_class ct
					ON ct.oid = i.indrelid
				JOIN pg_namespace n
					ON ci.relnamespace = n.oid
				WHERE
					ci.relname='$index' AND n.nspname='$schema'
					AND	attrelid = ct.oid
					AND	i.indkey[s.g] = attnum
					AND	i.indclass[s.g] = opcls.oid
					AND	pg_am.oid = opcls.opcmethod
__INDEXATTR__;
		$res = $this->query($sql, __METHOD__);
		$a = array();
		if ( $res ) {
			foreach ( $res as $row ) {
				$a[] = array(
					$row->attname,
					$row->opcname,
					$row->amname,
					$row->option);
			}
		} else {
			return null;
		}
		return $a;
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
		$savepoint = null;
		if ( in_array( 'IGNORE', $options ) ) {
			$savepoint = new SavepointPostgres( $this, 'mw' );
			$olde = error_reporting( 0 );
			// For future use, we may want to track the number of actual inserts
			// Right now, insert (all writes) simply return true/false
			$numrowsinserted = 0;
		}

		$sql = "INSERT INTO $table (" . implode( ',', $keys ) . ') VALUES ';

		if ( $multi ) {
			if ( $this->numeric_version >= 8.2 && !$savepoint ) {
				$first = true;
				foreach ( $args as $row ) {
					if ( $first ) {
						$first = false;
					} else {
						$sql .= ',';
					}
					$sql .= '(' . $this->makeList( $row ) . ')';
				}
				$res = (bool)$this->query( $sql, $fname, $savepoint );
			} else {
				$res = true;
				$origsql = $sql;
				foreach ( $args as $row ) {
					$tempsql = $origsql;
					$tempsql .= '(' . $this->makeList( $row ) . ')';

					if ( $savepoint ) {
						$savepoint->savepoint();
					}

					$tempres = (bool)$this->query( $tempsql, $fname, $savepoint );

					if ( $savepoint ) {
						$bar = pg_last_error();
						if ( $bar != false ) {
							$savepoint->rollback();
						} else {
							$savepoint->release();
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
			if ( $savepoint ) {
				$savepoint->savepoint();
			}

			$sql .= '(' . $this->makeList( $args ) . ')';
			$res = (bool)$this->query( $sql, $fname, $savepoint );
			if ( $savepoint ) {
				$bar = pg_last_error();
				if ( $bar != false ) {
					$savepoint->rollback();
				} else {
					$savepoint->release();
					$numrowsinserted++;
				}
			}
		}
		if ( $savepoint ) {
			$olde = error_reporting( $olde );
			$savepoint->commit();

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
	 * @return bool
	 */
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'DatabasePostgres::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$destTable = $this->tableName( $destTable );

		if( !is_array( $insertOptions ) ) {
			$insertOptions = array( $insertOptions );
		}

		/*
		 * If IGNORE is set, we use savepoints to emulate mysql's behavior
		 * Ignore LOW PRIORITY option, since it is MySQL-specific
		 */
		$savepoint = null;
		if ( in_array( 'IGNORE', $insertOptions ) ) {
			$savepoint = new SavepointPostgres( $this, 'mw' );
			$olde = error_reporting( 0 );
			$numrowsinserted = 0;
			$savepoint->savepoint();
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

		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
				" SELECT $startOpts " . implode( ',', $varMap ) .
				" FROM $srcTable $useIndex";

		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		$sql .= " $tailOpts";

		$res = (bool)$this->query( $sql, $fname, $savepoint );
		if( $savepoint ) {
			$bar = pg_last_error();
			if( $bar != false ) {
				$savepoint->rollback();
			} else {
				$savepoint->release();
				$numrowsinserted++;
			}
			$olde = error_reporting( $olde );
			$savepoint->commit();

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
	 * @return null
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
	 * @return
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
		$eschema = $this->addQuotes( $this->getCoreSchema() );
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

	/*
	 * Posted by cc[plus]php[at]c2se[dot]com on 25-Mar-2009 09:12
	 * to http://www.php.net/manual/en/ref.pgsql.php
	 *
	 * Parsing a postgres array can be a tricky problem, he's my
	 * take on this, it handles multi-dimensional arrays plus
	 * escaping using a nasty regexp to determine the limits of each
	 * data-item.
	 *
	 * This should really be handled by PHP PostgreSQL module
	 *
	 * @since 1.19
	 * @param $text   string: postgreql array returned in a text form like {a,b}
	 * @param $output string
	 * @param $limit  int
	 * @param $offset int
	 * @return string
	 */
	function pg_array_parse( $text, &$output, $limit = false, $offset = 1 ) {
		if( false === $limit ) {
			$limit = strlen( $text )-1;
			$output = array();
		}
		if( '{}' == $text ) {
			return $output;
		}
		do {
			if ( '{' != $text{$offset} ) {
				preg_match( "/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/",
					$text, $match, 0, $offset );
				$offset += strlen( $match[0] );
				$output[] = ( '"' != $match[1]{0}
						? $match[1]
						: stripcslashes( substr( $match[1], 1, -1 ) ) );
				if ( '},' == $match[3] ) {
					return $output;
				}
			} else {
				$offset = $this->pg_array_parse( $text, $output, $limit, $offset+1 );
			}
		} while ( $limit > $offset );
		return $output;
	}

	/**
	 * Return aggregated value function call
	 */
	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuedata;
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	public static function getSoftwareLink() {
		return '[http://www.postgresql.org/ PostgreSQL]';
	}


	/**
	 * Return current schema (executes SELECT current_schema())
	 * Needs transaction
	 *
	 * @since 1.19
	 * @return string return default schema for the current session
	 */
	function getCurrentSchema() {
		$res = $this->query( "SELECT current_schema()", __METHOD__);
		$row = $this->fetchRow( $res );
		return $row[0];
	}

	/**
	 * Return list of schemas which are accessible without schema name
	 * This is list does not contain magic keywords like "$user"
	 * Needs transaction
	 *
	 * @seealso getSearchPath()
	 * @seealso setSearchPath()
	 * @since 1.19
	 * @return array list of actual schemas for the current sesson
	 */
	function getSchemas() {
		$res = $this->query( "SELECT current_schemas(false)", __METHOD__);
		$row = $this->fetchRow( $res );
		$schemas = array();
		/* PHP pgsql support does not support array type, "{a,b}" string is returned */
		return $this->pg_array_parse($row[0], $schemas);
	}

	/**
	 * Return search patch for schemas
	 * This is different from getSchemas() since it contain magic keywords
	 * (like "$user").
	 * Needs transaction
	 *
	 * @since 1.19
	 * @return array how to search for table names schemas for the current user
	 */
	function getSearchPath() {
		$res = $this->query( "SHOW search_path", __METHOD__);
		$row = $this->fetchRow( $res );
		/* PostgreSQL returns SHOW values as strings */
		return explode(",", $row[0]);
	}

	/**
	 * Update search_path, values should already be sanitized
	 * Values may contain magic keywords like "$user"
	 * @since 1.19
	 *
	 * @param $search_path array list of schemas to be searched by default
	 */
	function setSearchPath( $search_path ) {
		$this->query( "SET search_path = " . implode(", ", $search_path) );
	}

	/**
	 * Determine default schema for MediaWiki core
	 * Adjust this session schema search path if desired schema exists
	 * and is not alread there.
	 *
	 * We need to have name of the core schema stored to be able
	 * to query database metadata.
	 *
	 * This will be also called by the installer after the schema is created
	 *
	 * @since 1.19
	 * @param $desired_schema string
	 */
	function determineCoreSchema( $desired_schema ) {
		$this->begin( __METHOD__ );
		if ( $this->schemaExists( $desired_schema ) ) {
			if ( in_array( $desired_schema, $this->getSchemas() ) ) {
				$this->mCoreSchema = $desired_schema;
				wfDebug("Schema \"" . $desired_schema . "\" already in the search path\n");
			} else {
				/**
				 * Prepend our schema (e.g. 'mediawiki') in front
				 * of the search path
				 * Fixes bug 15816
				 */
				$search_path = $this->getSearchPath();
				array_unshift( $search_path,
					$this->addIdentifierQuotes( $desired_schema ));
				$this->setSearchPath( $search_path );
				$this->mCoreSchema = $desired_schema;
				wfDebug("Schema \"" . $desired_schema . "\" added to the search path\n");
			}
		} else {
			$this->mCoreSchema = $this->getCurrentSchema();
			wfDebug("Schema \"" . $desired_schema . "\" not found, using current \"". $this->mCoreSchema ."\"\n");
		}
		/* Commit SET otherwise it will be rollbacked on error or IGNORE SELECT */
		$this->commit( __METHOD__ );
	}

	/**
	 * Return schema name fore core MediaWiki tables
	 *
	 * @since 1.19
	 * @return string core schema name
	 */
	function getCoreSchema() {
		return $this->mCoreSchema;
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
	 * @return bool
	 */
	function relationExists( $table, $types, $schema = false ) {
		if ( !is_array( $types ) ) {
			$types = array( $types );
		}
		if ( !$schema ) {
			$schema = $this->getCoreSchema();
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
	 * @return bool
	 */
	function tableExists( $table, $fname = __METHOD__, $schema = false ) {
		return $this->relationExists( $table, array( 'r', 'v' ), $schema );
	}

	function sequenceExists( $sequence, $schema = false ) {
		return $this->relationExists( $sequence, 'S', $schema );
	}

	function triggerExists( $table, $trigger ) {
		$q = <<<SQL
	SELECT 1 FROM pg_class, pg_namespace, pg_trigger
		WHERE relnamespace=pg_namespace.oid AND relkind='r'
			  AND tgrelid=pg_class.oid
			  AND nspname=%s AND relname=%s AND tgname=%s
SQL;
		$res = $this->query(
			sprintf(
				$q,
				$this->addQuotes( $this->getCoreSchema() ),
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
		$exists = $this->selectField( 'pg_rules', 'rulename',
			array(
				'rulename' => $rule,
				'tablename' => $table,
				'schemaname' => $this->getCoreSchema()
			)
		);
		return $exists === $rule;
	}

	function constraintExists( $table, $constraint ) {
		$SQL = sprintf( "SELECT 1 FROM information_schema.table_constraints ".
			   "WHERE constraint_schema = %s AND table_name = %s AND constraint_name = %s",
			$this->addQuotes( $this->getCoreSchema() ),
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
	 * @return bool
	 */
	function schemaExists( $schema ) {
		$exists = $this->selectField( '"pg_catalog"."pg_namespace"', 1,
			array( 'nspname' => $schema ), __METHOD__ );
		return (bool)$exists;
	}

	/**
	 * Returns true if a given role (i.e. user) exists, false otherwise.
	 * @return bool
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
	 * @return string
	 */
	function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_field_type( $res, $index );
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
