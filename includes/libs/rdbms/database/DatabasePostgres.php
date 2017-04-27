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
use Wikimedia\WaitConditionLoop;

/**
 * @ingroup Database
 */
class DatabasePostgres extends Database {
	/** @var int|bool */
	protected $port;

	/** @var resource */
	protected $mLastResult = null;
	/** @var int The number of rows affected as an integer */
	protected $mAffectedRows = null;

	/** @var int */
	private $mInsertId = null;
	/** @var float|string */
	private $numericVersion = null;
	/** @var string Connect string to open a PostgreSQL connection */
	private $connectString;
	/** @var string */
	private $mCoreSchema;

	public function __construct( array $params ) {
		$this->port = isset( $params['port'] ) ? $params['port'] : false;
		parent::__construct( $params );
	}

	function getType() {
		return 'postgres';
	}

	function implicitGroupby() {
		return false;
	}

	function implicitOrderby() {
		return false;
	}

	function hasConstraint( $name ) {
		$conn = $this->getBindingHandle();

		$sql = "SELECT 1 FROM pg_catalog.pg_constraint c, pg_catalog.pg_namespace n " .
			"WHERE c.connamespace = n.oid AND conname = '" .
			pg_escape_string( $conn, $name ) . "' AND n.nspname = '" .
			pg_escape_string( $conn, $this->getCoreSchema() ) . "'";
		$res = $this->doQuery( $sql );

		return $this->numRows( $res );
	}

	/**
	 * Usually aborts on failure
	 * @param string $server
	 * @param string $user
	 * @param string $password
	 * @param string $dbName
	 * @throws DBConnectionError|Exception
	 * @return resource|bool|null
	 */
	function open( $server, $user, $password, $dbName ) {
		# Test for Postgres support, to avoid suppressed fatal error
		if ( !function_exists( 'pg_connect' ) ) {
			throw new DBConnectionError(
				$this,
				"Postgres functions missing, have you compiled PHP with the --with-pgsql\n" .
				"option? (Note: if you recently installed PHP, you may need to restart your\n" .
				"webserver and database)\n"
			);
		}

		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$connectVars = [
			'dbname' => $dbName,
			'user' => $user,
			'password' => $password
		];
		if ( $server != false && $server != '' ) {
			$connectVars['host'] = $server;
		}
		if ( (int)$this->port > 0 ) {
			$connectVars['port'] = (int)$this->port;
		}
		if ( $this->mFlags & self::DBO_SSL ) {
			$connectVars['sslmode'] = 1;
		}

		$this->connectString = $this->makeConnectionString( $connectVars );
		$this->close();
		$this->installErrorHandler();

		try {
			// Use new connections to let LoadBalancer/LBFactory handle reuse
			$this->mConn = pg_connect( $this->connectString, PGSQL_CONNECT_FORCE_NEW );
		} catch ( Exception $ex ) {
			$this->restoreErrorHandler();
			throw $ex;
		}

		$phpError = $this->restoreErrorHandler();

		if ( !$this->mConn ) {
			$this->queryLogger->debug(
				"DB connection error\n" .
				"Server: $server, Database: $dbName, User: $user, Password: " .
				substr( $password, 0, 3 ) . "...\n"
			);
			$this->queryLogger->debug( $this->lastError() . "\n" );
			throw new DBConnectionError( $this, str_replace( "\n", ' ', $phpError ) );
		}

		$this->mOpened = true;

		# If called from the command-line (e.g. importDump), only show errors
		if ( $this->cliMode ) {
			$this->doQuery( "SET client_min_messages = 'ERROR'" );
		}

		$this->query( "SET client_encoding='UTF8'", __METHOD__ );
		$this->query( "SET datestyle = 'ISO, YMD'", __METHOD__ );
		$this->query( "SET timezone = 'GMT'", __METHOD__ );
		$this->query( "SET standard_conforming_strings = on", __METHOD__ );
		if ( $this->getServerVersion() >= 9.0 ) {
			$this->query( "SET bytea_output = 'escape'", __METHOD__ ); // PHP bug 53127
		}

		$this->determineCoreSchema( $this->mSchema );
		// The schema to be used is now in the search path; no need for explicit qualification
		$this->mSchema = null;

		return $this->mConn;
	}

	/**
	 * Postgres doesn't support selectDB in the same way MySQL does. So if the
	 * DB name doesn't match the open connection, open a new one
	 * @param string $db
	 * @return bool
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
		return $this->mConn ? pg_close( $this->mConn ) : true;
	}

	public function doQuery( $sql ) {
		$conn = $this->getBindingHandle();

		$sql = mb_convert_encoding( $sql, 'UTF-8' );
		// Clear previously left over PQresult
		while ( $res = pg_get_result( $conn ) ) {
			pg_free_result( $res );
		}
		if ( pg_send_query( $conn, $sql ) === false ) {
			throw new DBUnexpectedError( $this, "Unable to post new query to PostgreSQL\n" );
		}
		$this->mLastResult = pg_get_result( $conn );
		$this->mAffectedRows = null;
		if ( pg_result_error( $this->mLastResult ) ) {
			return false;
		}

		return $this->mLastResult;
	}

	protected function dumpError() {
		$diags = [
			PGSQL_DIAG_SEVERITY,
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
			PGSQL_DIAG_SOURCE_FUNCTION
		];
		foreach ( $diags as $d ) {
			$this->queryLogger->debug( sprintf( "PgSQL ERROR(%d): %s\n",
				$d, pg_result_error_field( $this->mLastResult, $d ) ) );
		}
	}

	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		if ( $tempIgnore ) {
			/* Check for constraint violation */
			if ( $errno === '23505' ) {
				parent::reportQueryError( $error, $errno, $sql, $fname, $tempIgnore );

				return;
			}
		}
		/* Transaction stays in the ERROR state until rolled back */
		if ( $this->mTrxLevel ) {
			$ignore = $this->ignoreErrors( true );
			$this->rollback( __METHOD__ );
			$this->ignoreErrors( $ignore );
		}
		parent::reportQueryError( $error, $errno, $sql, $fname, false );
	}

	function queryIgnore( $sql, $fname = __METHOD__ ) {
		return $this->query( $sql, $fname, true );
	}

	/**
	 * @param stdClass|ResultWrapper $res
	 * @throws DBUnexpectedError
	 */
	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$ok = pg_free_result( $res );
		MediaWiki\restoreWarnings();
		if ( !$ok ) {
			throw new DBUnexpectedError( $this, "Unable to free Postgres result\n" );
		}
	}

	/**
	 * @param ResultWrapper|stdClass $res
	 * @return stdClass
	 * @throws DBUnexpectedError
	 */
	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$row = pg_fetch_object( $res );
		MediaWiki\restoreWarnings();
		# @todo FIXME: HACK HACK HACK HACK debug

		# @todo hashar: not sure if the following test really trigger if the object
		#          fetching failed.
		$conn = $this->getBindingHandle();
		if ( pg_last_error( $conn ) ) {
			throw new DBUnexpectedError(
				$this,
				'SQL error: ' . htmlspecialchars( pg_last_error( $conn ) )
			);
		}

		return $row;
	}

	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$row = pg_fetch_array( $res );
		MediaWiki\restoreWarnings();

		$conn = $this->getBindingHandle();
		if ( pg_last_error( $conn ) ) {
			throw new DBUnexpectedError(
				$this,
				'SQL error: ' . htmlspecialchars( pg_last_error( $conn ) )
			);
		}

		return $row;
	}

	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		MediaWiki\suppressWarnings();
		$n = pg_num_rows( $res );
		MediaWiki\restoreWarnings();

		$conn = $this->getBindingHandle();
		if ( pg_last_error( $conn ) ) {
			throw new DBUnexpectedError(
				$this,
				'SQL error: ' . htmlspecialchars( pg_last_error( $conn ) )
			);
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
	 * Return the result of the last call to nextSequenceValue();
	 * This must be called after nextSequenceValue().
	 *
	 * @return int|null
	 */
	function insertId() {
		return $this->mInsertId;
	}

	/**
	 * @param mixed $res
	 * @param int $row
	 * @return bool
	 */
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
		}

		return $this->getLastPHPError() ?: 'No database connection';
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
		if ( empty( $this->mLastResult ) ) {
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
	 *
	 * @param string $table
	 * @param string $vars
	 * @param string $conds
	 * @param string $fname
	 * @param array $options
	 * @return int
	 */
	function estimateRowCount( $table, $vars = '*', $conds = '',
		$fname = __METHOD__, $options = []
	) {
		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		$rows = -1;
		if ( $res ) {
			$row = $this->fetchRow( $res );
			$count = [];
			if ( preg_match( '/rows=(\d+)/', $row[0], $count ) ) {
				$rows = (int)$count[1];
			}
		}

		return $rows;
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|null
	 */
	function indexInfo( $table, $index, $fname = __METHOD__ ) {
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
	 * @param string $index
	 * @param bool|string $schema
	 * @return array
	 */
	function indexAttributes( $index, $schema = false ) {
		if ( $schema === false ) {
			$schema = $this->getCoreSchema();
		}
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
		$res = $this->query( $sql, __METHOD__ );
		$a = [];
		if ( $res ) {
			foreach ( $res as $row ) {
				$a[] = [
					$row->attname,
					$row->opcname,
					$row->amname,
					$row->option ];
			}
		} else {
			return null;
		}

		return $a;
	}

	function indexUnique( $table, $index, $fname = __METHOD__ ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='{$table}'" .
			" AND indexdef LIKE 'CREATE UNIQUE%(" .
			$this->strencode( $this->indexName( $index ) ) .
			")'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}

		return $res->numRows() > 0;
	}

	function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		// Change the FOR UPDATE option as necessary based on the join conditions. Then pass
		// to the parent function to get the actual SQL text.
		// In Postgres when using FOR UPDATE, only the main table and tables that are inner joined
		// can be locked. That means tables in an outer join cannot be FOR UPDATE locked. Trying to
		// do so causes a DB error. This wrapper checks which tables can be locked and adjusts it
		// accordingly.
		// MySQL uses "ORDER BY NULL" as an optimization hint, but that is illegal in PostgreSQL.
		if ( is_array( $options ) ) {
			$forUpdateKey = array_search( 'FOR UPDATE', $options, true );
			if ( $forUpdateKey !== false && $join_conds ) {
				unset( $options[$forUpdateKey] );

				foreach ( $join_conds as $table_cond => $join_cond ) {
					if ( 0 === preg_match( '/^(?:LEFT|RIGHT|FULL)(?: OUTER)? JOIN$/i', $join_cond[0] ) ) {
						$options['FOR UPDATE'][] = $table_cond;
					}
				}
			}

			if ( isset( $options['ORDER BY'] ) && $options['ORDER BY'] == 'NULL' ) {
				unset( $options['ORDER BY'] );
			}
		}

		return parent::selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $args may be a single associative array, or an array of these with numeric keys,
	 * for multi-row insert (Postgres version 8.2 and above only).
	 *
	 * @param string $table Name of the table to insert to.
	 * @param array $args Items to insert into the table.
	 * @param string $fname Name of the function, for profiling
	 * @param array|string $options String or array. Valid options: IGNORE
	 * @return bool Success of insert operation. IGNORE always returns true.
	 */
	function insert( $table, $args, $fname = __METHOD__, $options = [] ) {
		if ( !count( $args ) ) {
			return true;
		}

		$table = $this->tableName( $table );
		if ( !isset( $this->numericVersion ) ) {
			$this->getServerVersion();
		}

		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$multi = true;
			$keys = array_keys( $args[0] );
		} else {
			$multi = false;
			$keys = array_keys( $args );
		}

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$savepoint = $olde = null;
		$numrowsinserted = 0;
		if ( in_array( 'IGNORE', $options ) ) {
			$savepoint = new SavepointPostgres( $this, 'mw', $this->queryLogger );
			$olde = error_reporting( 0 );
			// For future use, we may want to track the number of actual inserts
			// Right now, insert (all writes) simply return true/false
		}

		$sql = "INSERT INTO $table (" . implode( ',', $keys ) . ') VALUES ';

		if ( $multi ) {
			if ( $this->numericVersion >= 8.2 && !$savepoint ) {
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
						$bar = pg_result_error( $this->mLastResult );
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
				$bar = pg_result_error( $this->mLastResult );
				if ( $bar != false ) {
					$savepoint->rollback();
				} else {
					$savepoint->release();
					$numrowsinserted++;
				}
			}
		}
		if ( $savepoint ) {
			error_reporting( $olde );
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
	 * $varMap must be an associative array of the form [ 'dest1' => 'source1', ... ]
	 * Source items may be literals rather then field names, but strings should
	 * be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 * @todo FIXME: Implement this a little better (seperate select/insert)?
	 *
	 * @param string $destTable
	 * @param array|string $srcTable
	 * @param array $varMap
	 * @param array $conds
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @return bool
	 */
	function nativeInsertSelect( $destTable, $srcTable, $varMap, $conds, $fname = __METHOD__,
		$insertOptions = [], $selectOptions = [] ) {
		$destTable = $this->tableName( $destTable );

		if ( !is_array( $insertOptions ) ) {
			$insertOptions = [ $insertOptions ];
		}

		/*
		 * If IGNORE is set, we use savepoints to emulate mysql's behavior
		 * Ignore LOW PRIORITY option, since it is MySQL-specific
		 */
		$savepoint = $olde = null;
		$numrowsinserted = 0;
		if ( in_array( 'IGNORE', $insertOptions ) ) {
			$savepoint = new SavepointPostgres( $this, 'mw', $this->queryLogger );
			$olde = error_reporting( 0 );
			$savepoint->savepoint();
		}

		if ( !is_array( $selectOptions ) ) {
			$selectOptions = [ $selectOptions ];
		}
		list( $startOpts, $useIndex, $tailOpts, $ignoreIndex ) =
			$this->makeSelectOptions( $selectOptions );
		if ( is_array( $srcTable ) ) {
			$srcTable = implode( ',', array_map( [ $this, 'tableName' ], $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}

		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
			" SELECT $startOpts " . implode( ',', $varMap ) .
			" FROM $srcTable $useIndex $ignoreIndex ";

		if ( $conds != '*' ) {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		$sql .= " $tailOpts";

		$res = (bool)$this->query( $sql, $fname, $savepoint );
		if ( $savepoint ) {
			$bar = pg_result_error( $this->mLastResult );
			if ( $bar != false ) {
				$savepoint->rollback();
			} else {
				$savepoint->release();
				$numrowsinserted++;
			}
			error_reporting( $olde );
			$savepoint->commit();

			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;

			// IGNORE always returns true
			return true;
		}

		return $res;
	}

	function tableName( $name, $format = 'quoted' ) {
		// Replace reserved words with better ones
		$name = $this->remappedTableName( $name );

		return parent::tableName( $name, $format );
	}

	/**
	 * @param string $name
	 * @return string Value of $name or remapped name if $name is a reserved keyword
	 * @TODO: dependency inject these...
	 */
	public function remappedTableName( $name ) {
		if ( $name === 'user' ) {
			return 'mwuser';
		} elseif ( $name === 'text' ) {
			return 'pagecontent';
		}

		return $name;
	}

	/**
	 * @param string $name
	 * @param string $format
	 * @return string Qualified and encoded (if requested) table name
	 */
	public function realTableName( $name, $format = 'quoted' ) {
		return parent::tableName( $name, $format );
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 *
	 * @param string $seqName
	 * @return int|null
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
	 *
	 * @param string $seqName
	 * @return int
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
		$res = $this->query( $sql );
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

	function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$newName = $this->addIdentifierQuotes( $newName );
		$oldName = $this->addIdentifierQuotes( $oldName );

		return $this->query( 'CREATE ' . ( $temporary ? 'TEMPORARY ' : '' ) . " TABLE $newName " .
			"(LIKE $oldName INCLUDING DEFAULTS)", $fname );
	}

	function listTables( $prefix = null, $fname = __METHOD__ ) {
		$eschema = $this->addQuotes( $this->getCoreSchema() );
		$result = $this->query(
			"SELECT tablename FROM pg_tables WHERE schemaname = $eschema", $fname );
		$endArray = [];

		foreach ( $result as $table ) {
			$vars = get_object_vars( $table );
			$table = array_pop( $vars );
			if ( !$prefix || strpos( $table, $prefix ) === 0 ) {
				$endArray[] = $table;
			}
		}

		return $endArray;
	}

	function timestamp( $ts = 0 ) {
		$ct = new ConvertibleTimestamp( $ts );

		return $ct->getTimestamp( TS_POSTGRES );
	}

	/**
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
	 * @param string $text Postgreql array returned in a text form like {a,b}
	 * @param string $output
	 * @param int|bool $limit
	 * @param int $offset
	 * @return string
	 */
	function pg_array_parse( $text, &$output, $limit = false, $offset = 1 ) {
		if ( false === $limit ) {
			$limit = strlen( $text ) - 1;
			$output = [];
		}
		if ( '{}' == $text ) {
			return $output;
		}
		do {
			if ( '{' != $text[$offset] ) {
				preg_match( "/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/",
					$text, $match, 0, $offset );
				$offset += strlen( $match[0] );
				$output[] = ( '"' != $match[1][0]
					? $match[1]
					: stripcslashes( substr( $match[1], 1, -1 ) ) );
				if ( '},' == $match[3] ) {
					return $output;
				}
			} else {
				$offset = $this->pg_array_parse( $text, $output, $limit, $offset + 1 );
			}
		} while ( $limit > $offset );

		return $output;
	}

	/**
	 * Return aggregated value function call
	 * @param array $valuedata
	 * @param string $valuename
	 * @return array
	 */
	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuedata;
	}

	/**
	 * @return string Wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink() {
		return '[{{int:version-db-postgres-url}} PostgreSQL]';
	}

	/**
	 * Return current schema (executes SELECT current_schema())
	 * Needs transaction
	 *
	 * @since 1.19
	 * @return string Default schema for the current session
	 */
	function getCurrentSchema() {
		$res = $this->query( "SELECT current_schema()", __METHOD__ );
		$row = $this->fetchRow( $res );

		return $row[0];
	}

	/**
	 * Return list of schemas which are accessible without schema name
	 * This is list does not contain magic keywords like "$user"
	 * Needs transaction
	 *
	 * @see getSearchPath()
	 * @see setSearchPath()
	 * @since 1.19
	 * @return array List of actual schemas for the current sesson
	 */
	function getSchemas() {
		$res = $this->query( "SELECT current_schemas(false)", __METHOD__ );
		$row = $this->fetchRow( $res );
		$schemas = [];

		/* PHP pgsql support does not support array type, "{a,b}" string is returned */

		return $this->pg_array_parse( $row[0], $schemas );
	}

	/**
	 * Return search patch for schemas
	 * This is different from getSchemas() since it contain magic keywords
	 * (like "$user").
	 * Needs transaction
	 *
	 * @since 1.19
	 * @return array How to search for table names schemas for the current user
	 */
	function getSearchPath() {
		$res = $this->query( "SHOW search_path", __METHOD__ );
		$row = $this->fetchRow( $res );

		/* PostgreSQL returns SHOW values as strings */

		return explode( ",", $row[0] );
	}

	/**
	 * Update search_path, values should already be sanitized
	 * Values may contain magic keywords like "$user"
	 * @since 1.19
	 *
	 * @param array $search_path List of schemas to be searched by default
	 */
	function setSearchPath( $search_path ) {
		$this->query( "SET search_path = " . implode( ", ", $search_path ) );
	}

	/**
	 * Determine default schema for the current application
	 * Adjust this session schema search path if desired schema exists
	 * and is not alread there.
	 *
	 * We need to have name of the core schema stored to be able
	 * to query database metadata.
	 *
	 * This will be also called by the installer after the schema is created
	 *
	 * @since 1.19
	 *
	 * @param string $desiredSchema
	 */
	function determineCoreSchema( $desiredSchema ) {
		$this->begin( __METHOD__, self::TRANSACTION_INTERNAL );
		if ( $this->schemaExists( $desiredSchema ) ) {
			if ( in_array( $desiredSchema, $this->getSchemas() ) ) {
				$this->mCoreSchema = $desiredSchema;
				$this->queryLogger->debug(
					"Schema \"" . $desiredSchema . "\" already in the search path\n" );
			} else {
				/**
				 * Prepend our schema (e.g. 'mediawiki') in front
				 * of the search path
				 * Fixes bug 15816
				 */
				$search_path = $this->getSearchPath();
				array_unshift( $search_path,
					$this->addIdentifierQuotes( $desiredSchema ) );
				$this->setSearchPath( $search_path );
				$this->mCoreSchema = $desiredSchema;
				$this->queryLogger->debug(
					"Schema \"" . $desiredSchema . "\" added to the search path\n" );
			}
		} else {
			$this->mCoreSchema = $this->getCurrentSchema();
			$this->queryLogger->debug(
				"Schema \"" . $desiredSchema . "\" not found, using current \"" .
				$this->mCoreSchema . "\"\n" );
		}
		/* Commit SET otherwise it will be rollbacked on error or IGNORE SELECT */
		$this->commit( __METHOD__, self::FLUSHING_INTERNAL );
	}

	/**
	 * Return schema name for core application tables
	 *
	 * @since 1.19
	 * @return string Core schema name
	 */
	function getCoreSchema() {
		return $this->mCoreSchema;
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		if ( !isset( $this->numericVersion ) ) {
			$conn = $this->getBindingHandle();
			$versionInfo = pg_version( $conn );
			if ( version_compare( $versionInfo['client'], '7.4.0', 'lt' ) ) {
				// Old client, abort install
				$this->numericVersion = '7.3 or earlier';
			} elseif ( isset( $versionInfo['server'] ) ) {
				// Normal client
				$this->numericVersion = $versionInfo['server'];
			} else {
				// Bug 16937: broken pgsql extension from PHP<5.3
				$this->numericVersion = pg_parameter_status( $conn, 'server_version' );
			}
		}

		return $this->numericVersion;
	}

	/**
	 * Query whether a given relation exists (in the given schema, or the
	 * default mw one if not given)
	 * @param string $table
	 * @param array|string $types
	 * @param bool|string $schema
	 * @return bool
	 */
	function relationExists( $table, $types, $schema = false ) {
		if ( !is_array( $types ) ) {
			$types = [ $types ];
		}
		if ( $schema === false ) {
			$schema = $this->getCoreSchema();
		}
		$etable = $this->addQuotes( $table );
		$eschema = $this->addQuotes( $schema );
		$sql = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n "
			. "WHERE c.relnamespace = n.oid AND c.relname = $etable AND n.nspname = $eschema "
			. "AND c.relkind IN ('" . implode( "','", $types ) . "')";
		$res = $this->query( $sql );
		$count = $res ? $res->numRows() : 0;

		return (bool)$count;
	}

	/**
	 * For backward compatibility, this function checks both tables and
	 * views.
	 * @param string $table
	 * @param string $fname
	 * @param bool|string $schema
	 * @return bool
	 */
	function tableExists( $table, $fname = __METHOD__, $schema = false ) {
		return $this->relationExists( $table, [ 'r', 'v' ], $schema );
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
			[
				'rulename' => $rule,
				'tablename' => $table,
				'schemaname' => $this->getCoreSchema()
			]
		);

		return $exists === $rule;
	}

	function constraintExists( $table, $constraint ) {
		$sql = sprintf( "SELECT 1 FROM information_schema.table_constraints " .
			"WHERE constraint_schema = %s AND table_name = %s AND constraint_name = %s",
			$this->addQuotes( $this->getCoreSchema() ),
			$this->addQuotes( $table ),
			$this->addQuotes( $constraint )
		);
		$res = $this->query( $sql );
		if ( !$res ) {
			return null;
		}
		$rows = $res->numRows();

		return $rows;
	}

	/**
	 * Query whether a given schema exists. Returns true if it does, false if it doesn't.
	 * @param string $schema
	 * @return bool
	 */
	function schemaExists( $schema ) {
		$exists = $this->selectField( '"pg_catalog"."pg_namespace"', 1,
			[ 'nspname' => $schema ], __METHOD__ );

		return (bool)$exists;
	}

	/**
	 * Returns true if a given role (i.e. user) exists, false otherwise.
	 * @param string $roleName
	 * @return bool
	 */
	function roleExists( $roleName ) {
		$exists = $this->selectField( '"pg_catalog"."pg_roles"', 1,
			[ 'rolname' => $roleName ], __METHOD__ );

		return (bool)$exists;
	}

	/**
	 * @var string $table
	 * @var string $field
	 * @return PostgresField|null
	 */
	function fieldInfo( $table, $field ) {
		return PostgresField::fromText( $this, $table, $field );
	}

	/**
	 * pg_field_type() wrapper
	 * @param ResultWrapper|resource $res ResultWrapper or PostgreSQL query result resource
	 * @param int $index Field number, starting from 0
	 * @return string
	 */
	function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return pg_field_type( $res, $index );
	}

	/**
	 * @param string $b
	 * @return Blob
	 */
	function encodeBlob( $b ) {
		return new PostgresBlob( pg_escape_bytea( $b ) );
	}

	function decodeBlob( $b ) {
		if ( $b instanceof PostgresBlob ) {
			$b = $b->fetch();
		} elseif ( $b instanceof Blob ) {
			return $b->fetch();
		}

		return pg_unescape_bytea( $b );
	}

	function strencode( $s ) {
		// Should not be called by us
		return pg_escape_string( $this->getBindingHandle(), $s );
	}

	/**
	 * @param string|int|null|bool|Blob $s
	 * @return string|int
	 */
	function addQuotes( $s ) {
		$conn = $this->getBindingHandle();

		if ( is_null( $s ) ) {
			return 'NULL';
		} elseif ( is_bool( $s ) ) {
			return intval( $s );
		} elseif ( $s instanceof Blob ) {
			if ( $s instanceof PostgresBlob ) {
				$s = $s->fetch();
			} else {
				$s = pg_escape_bytea( $conn, $s->fetch() );
			}
			return "'$s'";
		}

		return "'" . pg_escape_string( $conn, $s ) . "'";
	}

	/**
	 * Postgres specific version of replaceVars.
	 * Calls the parent version in Database.php
	 *
	 * @param string $ins SQL string, read from a stream (usually tables.sql)
	 * @return string SQL string
	 */
	protected function replaceVars( $ins ) {
		$ins = parent::replaceVars( $ins );

		if ( $this->numericVersion >= 8.3 ) {
			// Thanks for not providing backwards-compatibility, 8.3
			$ins = preg_replace( "/to_tsvector\s*\(\s*'default'\s*,/", 'to_tsvector(', $ins );
		}

		if ( $this->numericVersion <= 8.1 ) { // Our minimum version
			$ins = str_replace( 'USING gin', 'USING gist', $ins );
		}

		return $ins;
	}

	/**
	 * Various select options
	 *
	 * @param array $options An associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions( $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = $useIndex = $ignoreIndex = '';

		$noKeyOptions = [];
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		// if ( isset( $options['LIMIT'] ) ) {
		// 	$tailOpts .= $this->limitResult( '', $options['LIMIT'],
		// 		isset( $options['OFFSET'] ) ? $options['OFFSET']
		// 		: false );
		// }

		if ( isset( $options['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE OF ' .
				implode( ', ', array_map( [ $this, 'tableName' ], $options['FOR UPDATE'] ) );
		} elseif ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ];
	}

	function getDBname() {
		return $this->mDBname;
	}

	function getServer() {
		return $this->mServer;
	}

	function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	public function buildGroupConcatField(
		$delimiter, $table, $field, $conds = '', $options = [], $join_conds = []
	) {
		$fld = "array_to_string(array_agg($field)," . $this->addQuotes( $delimiter ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.28
	 */
	public function buildStringCast( $field ) {
		return $field . '::text';
	}

	public function streamStatementEnd( &$sql, &$newLine ) {
		# Allow dollar quoting for function declarations
		if ( substr( $newLine, 0, 4 ) == '$mw$' ) {
			if ( $this->delimiter ) {
				$this->delimiter = false;
			} else {
				$this->delimiter = ';';
			}
		}

		return parent::streamStatementEnd( $sql, $newLine );
	}

	/**
	 * Check to see if a named lock is available. This is non-blocking.
	 * See http://www.postgresql.org/docs/8.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
	 *
	 * @param string $lockName Name of lock to poll
	 * @param string $method Name of method calling us
	 * @return bool
	 * @since 1.20
	 */
	public function lockIsFree( $lockName, $method ) {
		$key = $this->addQuotes( $this->bigintFromLockName( $lockName ) );
		$result = $this->query( "SELECT (CASE(pg_try_advisory_lock($key))
			WHEN 'f' THEN 'f' ELSE pg_advisory_unlock($key) END) AS lockstatus", $method );
		$row = $this->fetchObject( $result );

		return ( $row->lockstatus === 't' );
	}

	/**
	 * See http://www.postgresql.org/docs/8.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
	 * @param string $lockName
	 * @param string $method
	 * @param int $timeout
	 * @return bool
	 */
	public function lock( $lockName, $method, $timeout = 5 ) {
		$key = $this->addQuotes( $this->bigintFromLockName( $lockName ) );
		$loop = new WaitConditionLoop(
			function () use ( $lockName, $key, $timeout, $method ) {
				$res = $this->query( "SELECT pg_try_advisory_lock($key) AS lockstatus", $method );
				$row = $this->fetchObject( $res );
				if ( $row->lockstatus === 't' ) {
					parent::lock( $lockName, $method, $timeout ); // record
					return true;
				}

				return WaitConditionLoop::CONDITION_CONTINUE;
			},
			$timeout
		);

		return ( $loop->invoke() === $loop::CONDITION_REACHED );
	}

	/**
	 * See http://www.postgresql.org/docs/8.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKSFROM
	 * PG DOCS: http://www.postgresql.org/docs/8.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
	 * @param string $lockName
	 * @param string $method
	 * @return bool
	 */
	public function unlock( $lockName, $method ) {
		$key = $this->addQuotes( $this->bigintFromLockName( $lockName ) );
		$result = $this->query( "SELECT pg_advisory_unlock($key) as lockstatus", $method );
		$row = $this->fetchObject( $result );

		if ( $row->lockstatus === 't' ) {
			parent::unlock( $lockName, $method ); // record
			return true;
		}

		$this->queryLogger->debug( __METHOD__ . " failed to release lock\n" );

		return false;
	}

	/**
	 * @param string $lockName
	 * @return string Integer
	 */
	private function bigintFromLockName( $lockName ) {
		return Wikimedia\base_convert( substr( sha1( $lockName ), 0, 15 ), 16, 10 );
	}
}
