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
namespace Wikimedia\Rdbms;

use RuntimeException;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\WaitConditionLoop;

/**
 * @ingroup Database
 */
class DatabasePostgres extends Database {
	/** @var int|null */
	private $port;
	/** @var string */
	private $coreSchema;
	/** @var string */
	private $tempSchema;
	/** @var string[] Map of (reserved table name => alternate table name) */
	private $keywordTableMap = [];
	/** @var float|string */
	private $numericVersion;

	/** @var resource|null */
	private $lastResultHandle;

	/**
	 * @see Database::__construct()
	 * @param array $params Additional parameters include:
	 *   - keywordTableMap : Map of reserved table names to alternative table names to use
	 */
	public function __construct( array $params ) {
		$this->port = intval( $params['port'] ?? null );
		$this->keywordTableMap = $params['keywordTableMap'] ?? [];

		parent::__construct( $params );
	}

	public function getType() {
		return 'postgres';
	}

	public function implicitOrderby() {
		return false;
	}

	public function hasConstraint( $name ) {
		foreach ( $this->getCoreSchemas() as $schema ) {
			$sql = "SELECT 1 FROM pg_catalog.pg_constraint c, pg_catalog.pg_namespace n " .
				"WHERE c.connamespace = n.oid AND conname = " .
				$this->addQuotes( $name ) . " AND n.nspname = " .
				$this->addQuotes( $schema );
			$res = $this->doQuery( $sql );
			if ( $res && $this->numRows( $res ) ) {
				return true;
			}
		}
		return false;
	}

	protected function open( $server, $user, $password, $dbName, $schema, $tablePrefix ) {
		if ( !function_exists( 'pg_connect' ) ) {
			throw $this->newExceptionAfterConnectError(
				"Postgres functions missing, have you compiled PHP with the --with-pgsql\n" .
				"option? (Note: if you recently installed PHP, you may need to restart your\n" .
				"webserver and database)"
			);
		}

		$this->close( __METHOD__ );

		$this->server = $server;
		$this->user = $user;
		$this->password = $password;

		$connectVars = [
			// A database must be specified in order to connect to Postgres. If $dbName is not
			// specified, then use the standard "postgres" database that should exist by default.
			'dbname' => strlen( $dbName ) ? $dbName : 'postgres',
			'user' => $user,
			'password' => $password
		];
		if ( strlen( $server ) ) {
			$connectVars['host'] = $server;
		}
		if ( $this->port > 0 ) {
			$connectVars['port'] = $this->port;
		}
		if ( $this->getFlag( self::DBO_SSL ) ) {
			$connectVars['sslmode'] = 'require';
		}
		$connectString = $this->makeConnectionString( $connectVars );

		$this->installErrorHandler();
		try {
			$this->conn = pg_connect( $connectString, PGSQL_CONNECT_FORCE_NEW ) ?: null;
		} catch ( RuntimeException $e ) {
			$this->restoreErrorHandler();
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
		$error = $this->restoreErrorHandler();

		if ( !$this->conn ) {
			throw $this->newExceptionAfterConnectError( $error ?: $this->lastError() );
		}

		try {
			// Since no transaction is active at this point, any SET commands should apply
			// for the entire session (e.g. will not be reverted on transaction rollback).
			// See https://www.postgresql.org/docs/8.3/sql-set.html
			$variables = [
				'client_encoding' => 'UTF8',
				'datestyle' => 'ISO, YMD',
				'timezone' => 'GMT',
				'standard_conforming_strings' => 'on',
				'bytea_output' => 'escape',
				'client_min_messages' => 'ERROR'
			];
			foreach ( $variables as $var => $val ) {
				$this->query(
					'SET ' . $this->addIdentifierQuotes( $var ) . ' = ' . $this->addQuotes( $val ),
					__METHOD__,
					self::QUERY_IGNORE_DBO_TRX | self::QUERY_NO_RETRY | self::QUERY_CHANGE_TRX
				);
			}
			$this->determineCoreSchema( $schema );
			$this->currentDomain = new DatabaseDomain( $dbName, $schema, $tablePrefix );
		} catch ( RuntimeException $e ) {
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
	}

	protected function relationSchemaQualifier() {
		if ( $this->coreSchema === $this->currentDomain->getSchema() ) {
			// The schema to be used is now in the search path; no need for explicit qualification
			return '';
		}

		return parent::relationSchemaQualifier();
	}

	public function databasesAreIndependent() {
		return true;
	}

	public function doSelectDomain( DatabaseDomain $domain ) {
		if ( $this->getDBname() !== $domain->getDatabase() ) {
			// Postgres doesn't support selectDB in the same way MySQL does.
			// So if the DB name doesn't match the open connection, open a new one
			$this->open(
				$this->server,
				$this->user,
				$this->password,
				$domain->getDatabase(),
				$domain->getSchema(),
				$domain->getTablePrefix()
			);
		} else {
			$this->currentDomain = $domain;
		}

		return true;
	}

	/**
	 * @param string[] $vars
	 * @return string
	 */
	private function makeConnectionString( $vars ) {
		$s = '';
		foreach ( $vars as $name => $value ) {
			$s .= "$name='" . str_replace( "'", "\\'", $value ) . "' ";
		}

		return $s;
	}

	protected function closeConnection() {
		return $this->conn ? pg_close( $this->conn ) : true;
	}

	protected function isTransactableQuery( $sql ) {
		return parent::isTransactableQuery( $sql ) &&
			!preg_match( '/^SELECT\s+pg_(try_|)advisory_\w+\(/', $sql );
	}

	/**
	 * @param string $sql
	 * @return bool|mixed|resource
	 */
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
		$this->lastResultHandle = pg_get_result( $conn );
		if ( pg_result_error( $this->lastResultHandle ) ) {
			return false;
		}

		return $this->lastResultHandle;
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
			$this->queryLogger->debug( sprintf( "PgSQL ERROR(%d): %s",
				$d, pg_result_error_field( $this->lastResultHandle, $d ) ) );
		}
	}

	public function freeResult( $res ) {
		AtEase::suppressWarnings();
		$ok = pg_free_result( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();
		if ( !$ok ) {
			throw new DBUnexpectedError( $this, "Unable to free Postgres result\n" );
		}
	}

	public function fetchObject( $res ) {
		AtEase::suppressWarnings();
		$row = pg_fetch_object( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();
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

	public function fetchRow( $res ) {
		AtEase::suppressWarnings();
		$row = pg_fetch_array( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();

		$conn = $this->getBindingHandle();
		if ( pg_last_error( $conn ) ) {
			throw new DBUnexpectedError(
				$this,
				'SQL error: ' . htmlspecialchars( pg_last_error( $conn ) )
			);
		}

		return $row;
	}

	public function numRows( $res ) {
		if ( $res === false ) {
			return 0;
		}

		AtEase::suppressWarnings();
		$n = pg_num_rows( ResultWrapper::unwrap( $res ) );
		AtEase::restoreWarnings();

		$conn = $this->getBindingHandle();
		if ( pg_last_error( $conn ) ) {
			throw new DBUnexpectedError(
				$this,
				'SQL error: ' . htmlspecialchars( pg_last_error( $conn ) )
			);
		}

		return $n;
	}

	public function numFields( $res ) {
		return pg_num_fields( ResultWrapper::unwrap( $res ) );
	}

	public function fieldName( $res, $n ) {
		return pg_field_name( ResultWrapper::unwrap( $res ), $n );
	}

	public function insertId() {
		$res = $this->query(
			"SELECT lastval()",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchRow( $res );

		return $row[0] === null ? null : (int)$row[0];
	}

	public function dataSeek( $res, $row ) {
		return pg_result_seek( ResultWrapper::unwrap( $res ), $row );
	}

	public function lastError() {
		if ( $this->conn ) {
			if ( $this->lastResultHandle ) {
				return pg_result_error( $this->lastResultHandle );
			} else {
				return pg_last_error();
			}
		}

		return $this->getLastPHPError() ?: 'No database connection';
	}

	public function lastErrno() {
		if ( $this->lastResultHandle ) {
			return pg_result_error_field( $this->lastResultHandle, PGSQL_DIAG_SQLSTATE );
		} else {
			return false;
		}
	}

	protected function fetchAffectedRowCount() {
		if ( !$this->lastResultHandle ) {
			return 0;
		}

		return pg_affected_rows( $this->lastResultHandle );
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * This is not necessarily an accurate estimate, so use sparingly
	 * Returns -1 if count cannot be found
	 * Takes same arguments as Database::select()
	 *
	 * @param string $table
	 * @param string $var
	 * @param string $conds
	 * @param string $fname
	 * @param array $options
	 * @param array $join_conds
	 * @return int
	 */
	public function estimateRowCount( $table, $var = '*', $conds = '',
		$fname = __METHOD__, $options = [], $join_conds = []
	) {
		$conds = $this->normalizeConditions( $conds, $fname );
		$column = $this->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $var, $conds, $fname, $options, $join_conds );
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

	public function indexInfo( $table, $index, $fname = __METHOD__ ) {
		$res = $this->query(
			"SELECT indexname FROM pg_indexes WHERE tablename='$table'",
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
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

	public function indexAttributes( $index, $schema = false ) {
		if ( $schema === false ) {
			$schemas = $this->getCoreSchemas();
		} else {
			$schemas = [ $schema ];
		}

		$eindex = $this->addQuotes( $index );

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		foreach ( $schemas as $schema ) {
			$eschema = $this->addQuotes( $schema );
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
						WHERE cis.relname=$eindex AND ns.nspname=$eschema) AS s,
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
						ci.relname=$eindex AND n.nspname=$eschema
						AND	attrelid = ct.oid
						AND	i.indkey[s.g] = attnum
						AND	i.indclass[s.g] = opcls.oid
						AND	pg_am.oid = opcls.opcmethod
__INDEXATTR__;
			$res = $this->query( $sql, __METHOD__, $flags );
			$a = [];
			if ( $res ) {
				foreach ( $res as $row ) {
					$a[] = [
						$row->attname,
						$row->opcname,
						$row->amname,
						$row->option ];
				}
				return $a;
			}
		}
		return null;
	}

	public function indexUnique( $table, $index, $fname = __METHOD__ ) {
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='{$table}'" .
			" AND indexdef LIKE 'CREATE UNIQUE%(" .
			$this->strencode( $this->indexName( $index ) ) .
			")'";
		$res = $this->query( $sql, $fname, $flags );
		if ( !$res ) {
			return null;
		}

		return $res->numRows() > 0;
	}

	public function selectSQLText(
		$table, $vars, $conds = '', $fname = __METHOD__, $options = [], $join_conds = []
	) {
		if ( is_string( $options ) ) {
			$options = [ $options ];
		}

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
				$options['FOR UPDATE'] = [];

				$toCheck = $table;
				reset( $toCheck );
				while ( $toCheck ) {
					$alias = key( $toCheck );
					$name = $toCheck[$alias];
					unset( $toCheck[$alias] );

					$hasAlias = !is_numeric( $alias );
					if ( !$hasAlias && is_string( $name ) ) {
						$alias = $name;
					}

					if ( !isset( $join_conds[$alias] ) ||
						!preg_match( '/^(?:LEFT|RIGHT|FULL)(?: OUTER)? JOIN$/i', $join_conds[$alias][0] )
					) {
						if ( is_array( $name ) ) {
							// It's a parenthesized group, process all the tables inside the group.
							$toCheck = array_merge( $toCheck, $name );
						} else {
							// Quote alias names so $this->tableName() won't mangle them
							$options['FOR UPDATE'][] = $hasAlias ? $this->addIdentifierQuotes( $alias ) : $alias;
						}
					}
				}
			}

			if ( isset( $options['ORDER BY'] ) && $options['ORDER BY'] == 'NULL' ) {
				unset( $options['ORDER BY'] );
			}
		}

		return parent::selectSQLText( $table, $vars, $conds, $fname, $options, $join_conds );
	}

	protected function makeInsertNonConflictingVerbAndOptions() {
		return [ 'INSERT INTO', 'ON CONFLICT DO NOTHING' ];
	}

	public function doInsertNonConflicting( $table, array $rows, $fname ) {
		// Postgres 9.5 supports "ON CONFLICT"
		if ( $this->getServerVersion() >= 9.5 ) {
			parent::doInsertNonConflicting( $table, $rows, $fname );

			return;
		}

		$affectedRowCount = 0;
		// Emulate INSERT IGNORE via savepoints/rollback
		$tok = $this->startAtomic( "$fname (outer)", self::ATOMIC_CANCELABLE );
		try {
			$encTable = $this->tableName( $table );
			foreach ( $rows as $row ) {
				list( $sqlColumns, $sqlTuples ) = $this->makeInsertLists( [ $row ] );
				$tempsql = "INSERT INTO $encTable ($sqlColumns) VALUES $sqlTuples";

				$this->startAtomic( "$fname (inner)", self::ATOMIC_CANCELABLE );
				try {
					$this->query( $tempsql, $fname, self::QUERY_CHANGE_ROWS );
					$this->endAtomic( "$fname (inner)" );
					$affectedRowCount++;
				} catch ( DBQueryError $e ) {
					$this->cancelAtomic( "$fname (inner)" );
					// Our IGNORE is supposed to ignore duplicate key errors, but not others.
					// (even though MySQL's version apparently ignores all errors)
					if ( $e->errno !== '23505' ) {
						throw $e;
					}
				}
			}
		} catch ( RuntimeException $e ) {
			$this->cancelAtomic( "$fname (outer)", $tok );
			throw $e;
		}
		$this->endAtomic( "$fname (outer)" );
		// Set the affected row count for the whole operation
		$this->affectedRowCount = $affectedRowCount;
	}

	protected function makeUpdateOptionsArray( $options ) {
		$options = $this->normalizeOptions( $options );
		// PostgreSQL doesn't support anything like "ignore" for UPDATE.
		$options = array_diff( $options, [ 'IGNORE' ] );

		return parent::makeUpdateOptionsArray( $options );
	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form [ 'dest1' => 'source1', ... ]
	 * Source items may be literals rather then field names, but strings should
	 * be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 * @todo FIXME: Implement this a little better (separate select/insert)?
	 *
	 * @param string $destTable
	 * @param array|string $srcTable
	 * @param array $varMap
	 * @param array $conds
	 * @param string $fname
	 * @param array $insertOptions
	 * @param array $selectOptions
	 * @param array $selectJoinConds
	 */
	protected function doInsertSelectNative(
		$destTable,
		$srcTable,
		array $varMap,
		$conds,
		$fname,
		array $insertOptions,
		array $selectOptions,
		$selectJoinConds
	) {
		if ( in_array( 'IGNORE', $insertOptions ) ) {
			if ( $this->getServerVersion() >= 9.5 ) {
				// Use "ON CONFLICT DO" if we have it for IGNORE
				$destTable = $this->tableName( $destTable );

				$selectSql = $this->selectSQLText(
					$srcTable,
					array_values( $varMap ),
					$conds,
					$fname,
					$selectOptions,
					$selectJoinConds
				);

				$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ') ' .
					$selectSql . ' ON CONFLICT DO NOTHING';

				$this->query( $sql, $fname, self::QUERY_CHANGE_ROWS );
			} else {
				// IGNORE and we don't have ON CONFLICT DO NOTHING, so just use the non-native version
				$this->doInsertSelectGeneric(
					$destTable, $srcTable, $varMap, $conds, $fname,
					$insertOptions, $selectOptions, $selectJoinConds
				);
			}
		} else {
			parent::doInsertSelectNative( $destTable, $srcTable, $varMap, $conds, $fname,
				$insertOptions, $selectOptions, $selectJoinConds );
		}
	}

	public function tableName( $name, $format = 'quoted' ) {
		// Replace reserved words with better ones
		$name = $this->remappedTableName( $name );

		return parent::tableName( $name, $format );
	}

	/**
	 * @param string $name
	 * @return string Value of $name or remapped name if $name is a reserved keyword
	 */
	public function remappedTableName( $name ) {
		return $this->keywordTableMap[$name] ?? $name;
	}

	/**
	 * @param string $name
	 * @param string $format
	 * @return string Qualified and encoded (if requested) table name
	 */
	public function realTableName( $name, $format = 'quoted' ) {
		return parent::tableName( $name, $format );
	}

	public function nextSequenceValue( $seqName ) {
		return new NextSequenceValue;
	}

	/**
	 * Return the current value of a sequence. Assumes it has been nextval'ed in this session.
	 *
	 * @param string $seqName
	 * @return int
	 */
	public function currentSequenceValue( $seqName ) {
		$res = $this->query(
			"SELECT currval('" . str_replace( "'", "''", $seqName ) . "')",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchRow( $res );
		$currval = $row[0];

		return $currval;
	}

	public function textFieldSize( $table, $field ) {
		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$encTable = $this->tableName( $table );
		$sql = "SELECT t.typname as ftype,a.atttypmod as size
			FROM pg_class c, pg_attribute a, pg_type t
			WHERE relname='$encTable' AND a.attrelid=c.oid AND
				a.atttypid=t.oid and a.attname='$field'";
		$res = $this->query( $sql, __METHOD__, $flags );
		$row = $this->fetchObject( $res );
		if ( $row->ftype == 'varchar' ) {
			$size = $row->size - 4;
		} else {
			$size = $row->size;
		}

		return $size;
	}

	public function limitResult( $sql, $limit, $offset = false ) {
		return "$sql LIMIT $limit " . ( is_numeric( $offset ) ? " OFFSET {$offset} " : '' );
	}

	public function wasDeadlock() {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		return $this->lastErrno() === '40P01';
	}

	public function wasLockTimeout() {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		return $this->lastErrno() === '55P03';
	}

	public function wasConnectionError( $errno ) {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		static $codes = [ '08000', '08003', '08006', '08001', '08004', '57P01', '57P03', '53300' ];

		return in_array( $errno, $codes, true );
	}

	protected function wasKnownStatementRollbackError() {
		return false; // transaction has to be rolled-back from error state
	}

	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$newNameE = $this->addIdentifierQuotes( $newName );
		$oldNameE = $this->addIdentifierQuotes( $oldName );

		$temporary = $temporary ? 'TEMPORARY' : '';

		$ret = $this->query(
			"CREATE $temporary TABLE $newNameE " .
				"(LIKE $oldNameE INCLUDING DEFAULTS INCLUDING INDEXES)",
			$fname,
			self::QUERY_PSEUDO_PERMANENT | self::QUERY_CHANGE_SCHEMA
		);
		if ( !$ret ) {
			return $ret;
		}

		$res = $this->query(
			'SELECT attname FROM pg_class c'
			. ' JOIN pg_namespace n ON (n.oid = c.relnamespace)'
			. ' JOIN pg_attribute a ON (a.attrelid = c.oid)'
			. ' JOIN pg_attrdef d ON (c.oid=d.adrelid and a.attnum=d.adnum)'
			. ' WHERE relkind = \'r\''
			. ' AND nspname = ' . $this->addQuotes( $this->getCoreSchema() )
			. ' AND relname = ' . $this->addQuotes( $oldName )
			. ' AND pg_get_expr(adbin, adrelid) LIKE \'nextval(%\'',
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchObject( $res );
		if ( $row ) {
			$field = $row->attname;
			$newSeq = "{$newName}_{$field}_seq";
			$fieldE = $this->addIdentifierQuotes( $field );
			$newSeqE = $this->addIdentifierQuotes( $newSeq );
			$newSeqQ = $this->addQuotes( $newSeq );
			$this->query(
				"CREATE $temporary SEQUENCE $newSeqE OWNED BY $newNameE.$fieldE",
				$fname,
				self::QUERY_CHANGE_SCHEMA
			);
			$this->query(
				"ALTER TABLE $newNameE ALTER COLUMN $fieldE SET DEFAULT nextval({$newSeqQ}::regclass)",
				$fname,
				self::QUERY_CHANGE_SCHEMA
			);
		}

		return $ret;
	}

	protected function doTruncate( array $tables, $fname ) {
		$encTables = $this->tableNamesN( ...$tables );
		$sql = "TRUNCATE TABLE " . implode( ',', $encTables ) . " RESTART IDENTITY";
		$this->query( $sql, $fname, self::QUERY_CHANGE_SCHEMA );
	}

	/**
	 * @param string $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @return string[]
	 * @suppress SecurityCheck-SQLInjection array_map not recognized T204911
	 */
	public function listTables( $prefix = '', $fname = __METHOD__ ) {
		$eschemas = implode( ',', array_map( [ $this, 'addQuotes' ], $this->getCoreSchemas() ) );
		$result = $this->query(
			"SELECT DISTINCT tablename FROM pg_tables WHERE schemaname IN ($eschemas)",
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$endArray = [];

		foreach ( $result as $table ) {
			$vars = get_object_vars( $table );
			$table = array_pop( $vars );
			if ( $prefix == '' || strpos( $table, $prefix ) === 0 ) {
				$endArray[] = $table;
			}
		}

		return $endArray;
	}

	public function timestamp( $ts = 0 ) {
		$ct = new ConvertibleTimestamp( $ts );

		return $ct->getTimestamp( TS_POSTGRES );
	}

	/**
	 * Posted by cc[plus]php[at]c2se[dot]com on 25-Mar-2009 09:12
	 * to https://www.php.net/manual/en/ref.pgsql.php
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
	 * @param string[] &$output
	 * @param int|bool $limit
	 * @param int $offset
	 * @return string[]
	 */
	private function pg_array_parse( $text, &$output, $limit = false, $offset = 1 ) {
		if ( $limit === false ) {
			$limit = strlen( $text ) - 1;
			$output = [];
		}
		if ( $text == '{}' ) {
			return $output;
		}
		do {
			if ( $text[$offset] != '{' ) {
				preg_match( "/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/",
					$text, $match, 0, $offset );
				$offset += strlen( $match[0] );
				$output[] = ( $match[1][0] != '"'
					? $match[1]
					: stripcslashes( substr( $match[1], 1, -1 ) ) );
				if ( $match[3] == '},' ) {
					return $output;
				}
			} else {
				$offset = $this->pg_array_parse( $text, $output, $limit, $offset + 1 );
			}
		} while ( $limit > $offset );

		return $output;
	}

	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuedata;
	}

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
	public function getCurrentSchema() {
		$res = $this->query(
			"SELECT current_schema()",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
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
	public function getSchemas() {
		$res = $this->query(
			"SELECT current_schemas(false)",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
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
	public function getSearchPath() {
		$res = $this->query(
			"SHOW search_path",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchRow( $res );

		/* PostgreSQL returns SHOW values as strings */

		return explode( ",", $row[0] );
	}

	/**
	 * Update search_path, values should already be sanitized
	 * Values may contain magic keywords like "$user"
	 * @since 1.19
	 *
	 * @param string[] $search_path List of schemas to be searched by default
	 */
	private function setSearchPath( $search_path ) {
		$this->query(
			"SET search_path = " . implode( ", ", $search_path ),
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_TRX
		);
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
	public function determineCoreSchema( $desiredSchema ) {
		if ( $this->trxLevel() ) {
			// We do not want the schema selection to change on ROLLBACK or INSERT SELECT.
			// See https://www.postgresql.org/docs/8.3/sql-set.html
			throw new DBUnexpectedError(
				$this,
				__METHOD__ . ": a transaction is currently active"
			);
		}

		if ( $this->schemaExists( $desiredSchema ) ) {
			if ( in_array( $desiredSchema, $this->getSchemas() ) ) {
				$this->coreSchema = $desiredSchema;
				$this->queryLogger->debug(
					"Schema \"" . $desiredSchema . "\" already in the search path\n" );
			} else {
				// Prepend the desired schema to the search path (T17816)
				$search_path = $this->getSearchPath();
				array_unshift( $search_path, $this->addIdentifierQuotes( $desiredSchema ) );
				$this->setSearchPath( $search_path );
				$this->coreSchema = $desiredSchema;
				$this->queryLogger->debug(
					"Schema \"" . $desiredSchema . "\" added to the search path\n" );
			}
		} else {
			$this->coreSchema = $this->getCurrentSchema();
			$this->queryLogger->debug(
				"Schema \"" . $desiredSchema . "\" not found, using current \"" .
				$this->coreSchema . "\"\n" );
		}
	}

	/**
	 * Return schema name for core application tables
	 *
	 * @since 1.19
	 * @return string Core schema name
	 */
	public function getCoreSchema() {
		return $this->coreSchema;
	}

	/**
	 * Return schema names for temporary tables and core application tables
	 *
	 * @since 1.31
	 * @return string[] schema names
	 */
	public function getCoreSchemas() {
		if ( $this->tempSchema ) {
			return [ $this->tempSchema, $this->getCoreSchema() ];
		}

		$res = $this->query(
			"SELECT nspname FROM pg_catalog.pg_namespace n WHERE n.oid = pg_my_temp_schema()",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchObject( $res );
		if ( $row ) {
			$this->tempSchema = $row->nspname;
			return [ $this->tempSchema, $this->getCoreSchema() ];
		}

		return [ $this->getCoreSchema() ];
	}

	public function getServerVersion() {
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
				// T18937: broken pgsql extension from PHP<5.3
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
	private function relationExists( $table, $types, $schema = false ) {
		if ( !is_array( $types ) ) {
			$types = [ $types ];
		}
		if ( $schema === false ) {
			$schemas = $this->getCoreSchemas();
		} else {
			$schemas = [ $schema ];
		}
		$table = $this->realTableName( $table, 'raw' );
		$etable = $this->addQuotes( $table );
		foreach ( $schemas as $schema ) {
			$eschema = $this->addQuotes( $schema );
			$sql = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n "
				. "WHERE c.relnamespace = n.oid AND c.relname = $etable AND n.nspname = $eschema "
				. "AND c.relkind IN ('" . implode( "','", $types ) . "')";
			$res = $this->query(
				$sql,
				__METHOD__,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
			);
			if ( $res && $res->numRows() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * For backward compatibility, this function checks both tables and views.
	 * @param string $table
	 * @param string $fname
	 * @param bool|string $schema
	 * @return bool
	 */
	public function tableExists( $table, $fname = __METHOD__, $schema = false ) {
		return $this->relationExists( $table, [ 'r', 'v' ], $schema );
	}

	public function sequenceExists( $sequence, $schema = false ) {
		return $this->relationExists( $sequence, 'S', $schema );
	}

	public function triggerExists( $table, $trigger ) {
		$q = <<<SQL
	SELECT 1 FROM pg_class, pg_namespace, pg_trigger
		WHERE relnamespace=pg_namespace.oid AND relkind='r'
			AND tgrelid=pg_class.oid
			AND nspname=%s AND relname=%s AND tgname=%s
SQL;
		foreach ( $this->getCoreSchemas() as $schema ) {
			$res = $this->query(
				sprintf(
					$q,
					$this->addQuotes( $schema ),
					$this->addQuotes( $table ),
					$this->addQuotes( $trigger )
				),
				__METHOD__,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
			);
			if ( $res && $res->numRows() ) {
				return true;
			}
		}

		return false;
	}

	public function ruleExists( $table, $rule ) {
		$exists = $this->selectField( 'pg_rules', 'rulename',
			[
				'rulename' => $rule,
				'tablename' => $table,
				'schemaname' => $this->getCoreSchemas()
			],
			__METHOD__
		);

		return $exists === $rule;
	}

	public function constraintExists( $table, $constraint ) {
		foreach ( $this->getCoreSchemas() as $schema ) {
			$sql = sprintf( "SELECT 1 FROM information_schema.table_constraints " .
				"WHERE constraint_schema = %s AND table_name = %s AND constraint_name = %s",
				$this->addQuotes( $schema ),
				$this->addQuotes( $table ),
				$this->addQuotes( $constraint )
			);
			$res = $this->query(
				$sql,
				__METHOD__,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
			);
			if ( $res && $res->numRows() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Query whether a given schema exists. Returns true if it does, false if it doesn't.
	 * @param string $schema
	 * @return bool
	 */
	public function schemaExists( $schema ) {
		if ( !strlen( $schema ) ) {
			return false; // short-circuit
		}

		$res = $this->query(
			"SELECT 1 FROM pg_catalog.pg_namespace " .
			"WHERE nspname = " . $this->addQuotes( $schema ) . " LIMIT 1",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		return ( $this->numRows( $res ) > 0 );
	}

	/**
	 * Returns true if a given role (i.e. user) exists, false otherwise.
	 * @param string $roleName
	 * @return bool
	 */
	public function roleExists( $roleName ) {
		$res = $this->query(
			"SELECT 1 FROM pg_catalog.pg_roles " .
			"WHERE rolname = " . $this->addQuotes( $roleName ) . " LIMIT 1",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		return ( $this->numRows( $res ) > 0 );
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @return PostgresField|null
	 */
	public function fieldInfo( $table, $field ) {
		return PostgresField::fromText( $this, $table, $field );
	}

	/**
	 * pg_field_type() wrapper
	 * @param ResultWrapper|resource $res ResultWrapper or PostgreSQL query result resource
	 * @param int $index Field number, starting from 0
	 * @return string
	 */
	public function fieldType( $res, $index ) {
		return pg_field_type( ResultWrapper::unwrap( $res ), $index );
	}

	public function encodeBlob( $b ) {
		return new PostgresBlob( pg_escape_bytea( $b ) );
	}

	public function decodeBlob( $b ) {
		if ( $b instanceof PostgresBlob ) {
			$b = $b->fetch();
		} elseif ( $b instanceof Blob ) {
			return $b->fetch();
		}

		return pg_unescape_bytea( $b );
	}

	public function strencode( $s ) {
		// Should not be called by us
		return pg_escape_string( $this->getBindingHandle(), (string)$s );
	}

	public function addQuotes( $s ) {
		$conn = $this->getBindingHandle();

		if ( $s === null ) {
			return 'NULL';
		} elseif ( is_bool( $s ) ) {
			return (string)intval( $s );
		} elseif ( is_int( $s ) ) {
			return (string)$s;
		} elseif ( $s instanceof Blob ) {
			if ( $s instanceof PostgresBlob ) {
				$s = $s->fetch();
			} else {
				$s = pg_escape_bytea( $conn, $s->fetch() );
			}
			return "'$s'";
		} elseif ( $s instanceof NextSequenceValue ) {
			return 'DEFAULT';
		}

		return "'" . pg_escape_string( $conn, (string)$s ) . "'";
	}

	protected function makeSelectOptions( array $options ) {
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

	public function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	public function buildGroupConcatField(
		$delimiter, $table, $field, $conds = '', $options = [], $join_conds = []
	) {
		$fld = "array_to_string(array_agg($field)," . $this->addQuotes( $delimiter ) . ')';

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

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

	public function doLockTables( array $read, array $write, $method ) {
		$tablesWrite = [];
		foreach ( $write as $table ) {
			$tablesWrite[] = $this->tableName( $table );
		}
		$tablesRead = [];
		foreach ( $read as $table ) {
			$tablesRead[] = $this->tableName( $table );
		}

		// Acquire locks for the duration of the current transaction...
		if ( $tablesWrite ) {
			$this->query(
				'LOCK TABLE ONLY ' . implode( ',', $tablesWrite ) . ' IN EXCLUSIVE MODE',
				$method,
				self::QUERY_CHANGE_ROWS
			);
		}
		if ( $tablesRead ) {
			$this->query(
				'LOCK TABLE ONLY ' . implode( ',', $tablesRead ) . ' IN SHARE MODE',
				$method,
				self::QUERY_CHANGE_ROWS
			);
		}

		return true;
	}

	public function lockIsFree( $lockName, $method ) {
		if ( !parent::lockIsFree( $lockName, $method ) ) {
			return false; // already held
		}
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->addQuotes( $this->bigintFromLockName( $lockName ) );
		$res = $this->query(
			"SELECT (CASE(pg_try_advisory_lock($key))
			WHEN 'f' THEN 'f' ELSE pg_advisory_unlock($key) END) AS lockstatus",
			$method,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchObject( $res );

		return ( $row->lockstatus === 't' );
	}

	public function lock( $lockName, $method, $timeout = 5 ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->addQuotes( $this->bigintFromLockName( $lockName ) );
		$loop = new WaitConditionLoop(
			function () use ( $lockName, $key, $timeout, $method ) {
				$res = $this->query(
					"SELECT pg_try_advisory_lock($key) AS lockstatus",
					$method,
					self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_ROWS
				);
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

	public function unlock( $lockName, $method ) {
		// http://www.postgresql.org/docs/9.2/static/functions-admin.html#FUNCTIONS-ADVISORY-LOCKS
		$key = $this->addQuotes( $this->bigintFromLockName( $lockName ) );
		$result = $this->query(
			"SELECT pg_advisory_unlock($key) as lockstatus",
			$method,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_ROWS
		);
		$row = $this->fetchObject( $result );

		if ( $row->lockstatus === 't' ) {
			parent::unlock( $lockName, $method ); // record
			return true;
		}

		$this->queryLogger->debug( __METHOD__ . " failed to release lock" );

		return false;
	}

	public function serverIsReadOnly() {
		$res = $this->query(
			"SHOW default_transaction_read_only",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $this->fetchObject( $res );

		return $row ? ( strtolower( $row->default_transaction_read_only ) === 'on' ) : false;
	}

	protected static function getAttributes() {
		return [ self::ATTR_SCHEMAS_AS_TABLE_GROUPS => true ];
	}

	/**
	 * @param string $lockName
	 * @return string Integer
	 */
	private function bigintFromLockName( $lockName ) {
		return \Wikimedia\base_convert( substr( sha1( $lockName ), 0, 15 ), 16, 10 );
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabasePostgres::class, 'DatabasePostgres' );
