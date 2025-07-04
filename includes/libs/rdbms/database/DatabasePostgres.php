<?php
/**
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
 */

namespace Wikimedia\Rdbms;

use PgSql\Connection;
use PgSql\Result;
use RuntimeException;
use Wikimedia\Rdbms\Platform\PostgresPlatform;
use Wikimedia\Rdbms\Replication\ReplicationReporter;
use Wikimedia\WaitConditionLoop;

/**
 * Postgres database abstraction layer.
 *
 * @ingroup Database
 */
class DatabasePostgres extends Database {
	/** @var int */
	private $port;
	/** @var string */
	private $tempSchema;
	/** @var float|string|null */
	private $numericVersion;

	/** @var Result|null */
	private $lastResultHandle;

	/** @var PostgresPlatform */
	protected $platform;

	/**
	 * @see Database::__construct()
	 * @param array $params Additional parameters include:
	 *   - port: A port to append to the hostname
	 */
	public function __construct( array $params ) {
		$this->port = intval( $params['port'] ?? null );
		parent::__construct( $params );

		$this->platform = new PostgresPlatform(
			$this,
			$this->logger,
			$this->currentDomain,
			$this->errorLogger
		);
		$this->replicationReporter = new ReplicationReporter(
			$params['topologyRole'],
			$this->logger,
			$params['srvCache']
		);
	}

	/** @inheritDoc */
	public function getType() {
		return 'postgres';
	}

	/** @inheritDoc */
	protected function open( $server, $user, $password, $db, $schema, $tablePrefix ) {
		if ( !function_exists( 'pg_connect' ) ) {
			throw $this->newExceptionAfterConnectError(
				"Postgres functions missing, have you compiled PHP with the --with-pgsql\n" .
				"option? (Note: if you recently installed PHP, you may need to restart your\n" .
				"webserver and database)"
			);
		}

		$this->close( __METHOD__ );

		$connectVars = [
			// A database must be specified in order to connect to Postgres. If $dbName is not
			// specified, then use the standard "postgres" database that should exist by default.
			'dbname' => ( $db !== null && $db !== '' ) ? $db : 'postgres',
			'user' => $user,
			'password' => $password
		];
		if ( $server !== null && $server !== '' ) {
			$connectVars['host'] = $server;
		}
		if ( $this->port > 0 ) {
			$connectVars['port'] = $this->port;
		}
		if ( $this->ssl ) {
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
				$sql = 'SET ' . $this->platform->addIdentifierQuotes( $var ) . ' = ' . $this->addQuotes( $val );
				$query = new Query( $sql, self::QUERY_NO_RETRY | self::QUERY_CHANGE_TRX, 'SET' );
				$this->query( $query, __METHOD__ );
			}
			$this->determineCoreSchema( $schema );
			$this->currentDomain = new DatabaseDomain( $db, $schema, $tablePrefix );
			$this->platform->setCurrentDomain( $this->currentDomain );
		} catch ( RuntimeException $e ) {
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
	}

	/** @inheritDoc */
	public function databasesAreIndependent() {
		return true;
	}

	/** @inheritDoc */
	public function doSelectDomain( DatabaseDomain $domain ) {
		$database = $domain->getDatabase();
		if ( $database === null ) {
			// A null database means "don't care" so leave it as is and update the table prefix
			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				$domain->getSchema() ?? $this->currentDomain->getSchema(),
				$domain->getTablePrefix()
			);
			$this->platform->setCurrentDomain( $this->currentDomain );
		} elseif ( $this->getDBname() !== $database ) {
			// Postgres doesn't support selectDB in the same way MySQL does.
			// So if the DB name doesn't match the open connection, open a new one
			$this->open(
				$this->connectionParams[self::CONN_HOST],
				$this->connectionParams[self::CONN_USER],
				$this->connectionParams[self::CONN_PASSWORD],
				$database,
				$domain->getSchema(),
				$domain->getTablePrefix()
			);
		} else {
			$this->currentDomain = $domain;
			$this->platform->setCurrentDomain( $domain );
		}

		return true;
	}

	protected function getBindingHandle(): Connection {
		return parent::getBindingHandle();
	}

	/**
	 * @param string[] $vars
	 * @return string
	 */
	private function makeConnectionString( $vars ) {
		$s = '';
		foreach ( $vars as $name => $value ) {
			$s .= "$name='" . str_replace( [ "\\", "'" ], [ "\\\\", "\\'" ], $value ) . "' ";
		}

		return $s;
	}

	/** @inheritDoc */
	protected function closeConnection() {
		return $this->conn ? pg_close( $this->conn ) : true;
	}

	public function doSingleStatementQuery( string $sql ): QueryStatus {
		$conn = $this->getBindingHandle();

		$sql = mb_convert_encoding( $sql, 'UTF-8' );
		// Clear any previously left over result
		// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
		while ( $priorRes = pg_get_result( $conn ) ) {
			pg_free_result( $priorRes );
		}

		if ( pg_send_query( $conn, $sql ) === false ) {
			throw new DBUnexpectedError( $this, "Unable to post new query to PostgreSQL\n" );
		}

		$pgRes = pg_get_result( $conn );
		$this->lastResultHandle = $pgRes;
		$res = pg_result_error( $pgRes ) ? false : $pgRes;

		return new QueryStatus(
			is_bool( $res ) ? $res : new PostgresResultWrapper( $this, $conn, $res ),
			$pgRes ? pg_affected_rows( $pgRes ) : 0,
			$this->lastError(),
			$this->lastErrno()
		);
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
			$this->logger->debug( sprintf( "PgSQL ERROR(%d): %s",
				$d, pg_result_error_field( $this->lastResultHandle, $d ) ) );
		}
	}

	/** @inheritDoc */
	protected function lastInsertId() {
		// Avoid using query() to prevent unwanted side-effects like changing affected
		// row counts or connection retries. Note that lastval() is connection-specific.
		// Note that this causes "lastval is not yet defined in this session" errors if
		// nextval() was never directly or implicitly triggered (error out any transaction).
		$qs = $this->doSingleStatementQuery( "SELECT lastval() AS id" );

		return $qs->res ? (int)$qs->res->fetchRow()['id'] : 0;
	}

	/** @inheritDoc */
	public function lastError() {
		if ( $this->conn ) {
			if ( $this->lastResultHandle ) {
				return pg_result_error( $this->lastResultHandle );
			} else {
				return pg_last_error() ?: $this->lastConnectError;
			}
		}

		return $this->getLastPHPError() ?: 'No database connection';
	}

	/** @inheritDoc */
	public function lastErrno() {
		if ( $this->lastResultHandle ) {
			$lastErrno = pg_result_error_field( $this->lastResultHandle, PGSQL_DIAG_SQLSTATE );
			if ( $lastErrno !== false ) {
				return $lastErrno;
			}
		}

		return '00000';
	}

	/** @inheritDoc */
	public function estimateRowCount( $table, $var = '*', $conds = '',
		$fname = __METHOD__, $options = [], $join_conds = []
	): int {
		$conds = $this->platform->normalizeConditions( $conds, $fname );
		$column = $this->platform->extractSingleFieldFromList( $var );
		if ( is_string( $column ) && !in_array( $column, [ '*', '1' ] ) ) {
			$conds[] = "$column IS NOT NULL";
		}

		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $var, $conds, $fname, $options, $join_conds );
		$rows = -1;
		if ( $res ) {
			$row = $res->fetchRow();
			$count = [];
			if ( preg_match( '/rows=(\d+)/', $row[0], $count ) ) {
				$rows = (int)$count[1];
			}
		}

		return $rows;
	}

	/** @inheritDoc */
	public function indexInfo( $table, $index, $fname = __METHOD__ ) {
		$components = $this->platform->qualifiedTableComponents( $table );
		if ( count( $components ) === 1 ) {
			$schemas = $this->getCoreSchemas();
			$tableComponent = $components[0];
		} elseif ( count( $components ) === 2 ) {
			[ $schema, $tableComponent ] = $components;
			$schemas = [ $schema ];
		} else {
			[ , $schema, $tableComponent ] = $components;
			$schemas = [ $schema ];
		}
		foreach ( $schemas as $schema ) {
			$encSchema = $this->addQuotes( $schema );
			$encTable = $this->addQuotes( $tableComponent );
			$encIndex = $this->addQuotes( $index );
			$query = new Query(
				"SELECT indexname,indexdef FROM pg_indexes " .
				"WHERE schemaname=$encSchema AND tablename=$encTable AND indexname=$encIndex",
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
				'SELECT'
			);
			$res = $this->query( $query );
			$row = $res->fetchObject();

			if ( $row ) {
				return [ 'unique' => str_starts_with( $row->indexdef, 'CREATE UNIQUE ' ) ];
			}
		}

		return false;
	}

	/** @inheritDoc */
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
			$query = new Query( $sql, $flags, 'SELECT' );
			$res = $this->query( $query, __METHOD__ );
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

	/** @inheritDoc */
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
			// Use "ON CONFLICT DO" if we have it for IGNORE
			$destTableEnc = $this->tableName( $destTable );

			$selectSql = $this->selectSQLText(
				$srcTable,
				array_values( $varMap ),
				$conds,
				$fname,
				$selectOptions,
				$selectJoinConds
			);

			$sql = "INSERT INTO $destTableEnc (" . implode( ',', array_keys( $varMap ) ) . ') ' .
				$selectSql . ' ON CONFLICT DO NOTHING';
			$query = new Query( $sql, self::QUERY_CHANGE_ROWS, 'INSERT', $destTable );
			$this->query( $query, $fname );
		} else {
			parent::doInsertSelectNative( $destTable, $srcTable, $varMap, $conds, $fname,
				$insertOptions, $selectOptions, $selectJoinConds );
		}
	}

	/** @inheritDoc */
	public function getValueTypesForWithClause( $table ) {
		$typesByColumn = [];

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$encTable = $this->addQuotes( $table );
		foreach ( $this->getCoreSchemas() as $schema ) {
			$encSchema = $this->addQuotes( $schema );
			$sql = "SELECT column_name,udt_name " .
				"FROM information_schema.columns " .
				"WHERE table_name = $encTable AND table_schema = $encSchema";
			$query = new Query( $sql, $flags, 'SELECT' );
			$res = $this->query( $query, __METHOD__ );
			if ( $res->numRows() ) {
				foreach ( $res as $row ) {
					$typesByColumn[$row->column_name] = $row->udt_name;
				}
				break;
			}
		}

		return $typesByColumn;
	}

	/** @inheritDoc */
	protected function isConnectionError( $errno ) {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		static $codes = [ '08000', '08003', '08006', '08001', '08004', '57P01', '57P03', '53300' ];

		return in_array( $errno, $codes, true );
	}

	/** @inheritDoc */
	protected function isQueryTimeoutError( $errno ) {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		return ( $errno === '57014' );
	}

	/** @inheritDoc */
	protected function isKnownStatementRollbackError( $errno ) {
		return false; // transaction has to be rolled-back from error state
	}

	/** @inheritDoc */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$newNameE = $this->platform->addIdentifierQuotes( $newName );
		$oldNameE = $this->platform->addIdentifierQuotes( $oldName );

		$temporary = $temporary ? 'TEMPORARY' : '';
		$query = new Query(
			"CREATE $temporary TABLE $newNameE " .
			"(LIKE $oldNameE INCLUDING DEFAULTS INCLUDING INDEXES)",
			self::QUERY_PSEUDO_PERMANENT | self::QUERY_CHANGE_SCHEMA,
			$temporary ? 'CREATE TEMPORARY' : 'CREATE',
			// Use a dot to avoid double-prefixing in Database::getTempTableWrites()
			'.' . $newName
		);
		$ret = $this->query( $query, $fname );
		if ( !$ret ) {
			return $ret;
		}

		$sql = 'SELECT attname FROM pg_class c'
			. ' JOIN pg_namespace n ON (n.oid = c.relnamespace)'
			. ' JOIN pg_attribute a ON (a.attrelid = c.oid)'
			. ' JOIN pg_attrdef d ON (c.oid=d.adrelid and a.attnum=d.adnum)'
			. ' WHERE relkind = \'r\''
			. ' AND nspname = ' . $this->addQuotes( $this->getCoreSchema() )
			. ' AND relname = ' . $this->addQuotes( $oldName )
			. ' AND pg_get_expr(adbin, adrelid) LIKE \'nextval(%\'';
		$query = new Query(
			$sql,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);

		$res = $this->query( $query, $fname );
		$row = $res->fetchObject();
		if ( $row ) {
			$field = $row->attname;
			$newSeq = "{$newName}_{$field}_seq";
			$fieldE = $this->platform->addIdentifierQuotes( $field );
			$newSeqE = $this->platform->addIdentifierQuotes( $newSeq );
			$newSeqQ = $this->addQuotes( $newSeq );
			$query = new Query(
				"CREATE $temporary SEQUENCE $newSeqE OWNED BY $newNameE.$fieldE",
				self::QUERY_CHANGE_SCHEMA,
				'CREATE',
				// Do not treat this is as a table modification on top of the CREATE above.
				null
			);
			$this->query( $query, $fname );
			$query = new Query(
				"ALTER TABLE $newNameE ALTER COLUMN $fieldE SET DEFAULT nextval({$newSeqQ}::regclass)",
				self::QUERY_CHANGE_SCHEMA,
				'ALTER',
				// Do not treat this is as a table modification on top of the CREATE above.
				null
			);
			$this->query( $query, $fname );
		}

		return $ret;
	}

	/** @inheritDoc */
	public function truncateTable( $table, $fname = __METHOD__ ) {
		$sql = "TRUNCATE TABLE " . $this->tableName( $table ) . " RESTART IDENTITY";
		$query = new Query( $sql, self::QUERY_CHANGE_SCHEMA, 'TRUNCATE', $table );
		$this->query( $query, $fname );
	}

	/**
	 * @param string $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 * @return string[]
	 * @suppress SecurityCheck-SQLInjection array_map not recognized T204911
	 */
	public function listTables( $prefix = '', $fname = __METHOD__ ) {
		$eschemas = implode( ',', array_map( $this->addQuotes( ... ), $this->getCoreSchemas() ) );
		$query = new Query(
			"SELECT DISTINCT tablename FROM pg_tables WHERE schemaname IN ($eschemas)",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$result = $this->query( $query, $fname );
		$endArray = [];

		foreach ( $result as $table ) {
			$vars = get_object_vars( $table );
			$table = array_pop( $vars );
			if ( $prefix == '' || str_starts_with( $table, $prefix ) ) {
				$endArray[] = $table;
			}
		}

		return $endArray;
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
	 * @param int|false $limit
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

	/** @inheritDoc */
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
		$query = new Query(
			"SELECT current_schema()",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchRow();

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
	 * @return array List of actual schemas for the current session
	 */
	public function getSchemas() {
		$query = new Query(
			"SELECT current_schemas(false)",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchRow();
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
		$query = new Query(
			"SHOW search_path",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SHOW'
		);
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchRow();

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
		$query = new Query(
			"SET search_path = " . implode( ", ", $search_path ),
			self::QUERY_CHANGE_TRX,
			'SET'
		);
		$this->query( $query, __METHOD__ );
	}

	/**
	 * Determine default schema for the current application
	 * Adjust this session schema search path if desired schema exists
	 * and is not already there.
	 *
	 * We need to have name of the core schema stored to be able
	 * to query database metadata.
	 *
	 * This will be also called by the installer after the schema is created
	 *
	 * @since 1.19
	 *
	 * @param string|null $desiredSchema
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
				$this->platform->setCoreSchema( $desiredSchema );
				$this->logger->debug(
					"Schema \"" . $desiredSchema . "\" already in the search path\n" );
			} else {
				// Prepend the desired schema to the search path (T17816)
				$search_path = $this->getSearchPath();
				array_unshift( $search_path, $this->platform->addIdentifierQuotes( $desiredSchema ) );
				$this->setSearchPath( $search_path );
				$this->platform->setCoreSchema( $desiredSchema );
				$this->logger->debug(
					"Schema \"" . $desiredSchema . "\" added to the search path\n" );
			}
		} else {
			$this->platform->setCoreSchema( $this->getCurrentSchema() );
			$this->logger->debug(
				"Schema \"" . $desiredSchema . "\" not found, using current \"" .
				$this->getCoreSchema() . "\"\n" );
		}
	}

	/**
	 * Return schema name for core application tables
	 *
	 * @since 1.19
	 * @return string Core schema name
	 */
	public function getCoreSchema() {
		return $this->platform->getCoreSchema();
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
		$query = new Query(
			"SELECT nspname FROM pg_catalog.pg_namespace n WHERE n.oid = pg_my_temp_schema()",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchObject();
		if ( $row ) {
			$this->tempSchema = $row->nspname;
			return [ $this->tempSchema, $this->getCoreSchema() ];
		}

		return [ $this->getCoreSchema() ];
	}

	/** @inheritDoc */
	public function getServerVersion() {
		if ( $this->numericVersion === null ) {
			// Works on PG 7.4+
			$this->numericVersion = pg_version( $this->getBindingHandle() )['server'];
		}

		return $this->numericVersion;
	}

	/**
	 * Query whether a given relation exists (in the given schema, or the
	 * default mw one if not given)
	 * @param string $table
	 * @param array|string $types
	 * @return bool
	 */
	private function relationExists( $table, $types ) {
		if ( !is_array( $types ) ) {
			$types = [ $types ];
		}
		$schemas = $this->getCoreSchemas();
		$components = $this->platform->qualifiedTableComponents( $table );
		$etable = $this->addQuotes( end( $components ) );
		foreach ( $schemas as $schema ) {
			$eschema = $this->addQuotes( $schema );
			$sql = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n "
				. "WHERE c.relnamespace = n.oid AND c.relname = $etable AND n.nspname = $eschema "
				. "AND c.relkind IN ('" . implode( "','", $types ) . "')";
			$query = new Query(
				$sql,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
				'SELECT'
			);
			$res = $this->query( $query, __METHOD__ );
			if ( $res && $res->numRows() ) {
				return true;
			}
		}

		return false;
	}

	/** @inheritDoc */
	public function tableExists( $table, $fname = __METHOD__ ) {
		return $this->relationExists( $table, [ 'r', 'v' ] );
	}

	/** @inheritDoc */
	public function sequenceExists( $sequence ) {
		return $this->relationExists( $sequence, 'S' );
	}

	/** @inheritDoc */
	public function constraintExists( $table, $constraint ) {
		foreach ( $this->getCoreSchemas() as $schema ) {
			$sql = sprintf( "SELECT 1 FROM information_schema.table_constraints " .
				"WHERE constraint_schema = %s AND table_name = %s AND constraint_name = %s",
				$this->addQuotes( $schema ),
				$this->addQuotes( $table ),
				$this->addQuotes( $constraint )
			);
			$query = new Query(
				$sql,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
				'SELECT'
			);
			$res = $this->query( $query, __METHOD__ );
			if ( $res && $res->numRows() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Query whether a given schema exists. Returns true if it does, false if it doesn't.
	 * @param string|null $schema
	 * @return bool
	 */
	public function schemaExists( $schema ) {
		if ( !strlen( $schema ?? '' ) ) {
			return false; // short-circuit
		}
		$query = new Query(
			"SELECT 1 FROM pg_catalog.pg_namespace " .
			"WHERE nspname = " . $this->addQuotes( $schema ) . " LIMIT 1",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$res = $this->query( $query, __METHOD__ );

		return ( $res->numRows() > 0 );
	}

	/**
	 * Returns true if a given role (i.e. user) exists, false otherwise.
	 * @param string $roleName
	 * @return bool
	 */
	public function roleExists( $roleName ) {
		$query = new Query(
			"SELECT 1 FROM pg_catalog.pg_roles " .
			"WHERE rolname = " . $this->addQuotes( $roleName ) . " LIMIT 1",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SELECT'
		);
		$res = $this->query( $query, __METHOD__ );

		return ( $res->numRows() > 0 );
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @return PostgresField|null
	 */
	public function fieldInfo( $table, $field ) {
		return PostgresField::fromText( $this, $table, $field );
	}

	/** @inheritDoc */
	public function encodeBlob( $b ) {
		$conn = $this->getBindingHandle();

		return new PostgresBlob( pg_escape_bytea( $conn, $b ) );
	}

	/** @inheritDoc */
	public function decodeBlob( $b ) {
		if ( $b instanceof PostgresBlob ) {
			$b = $b->fetch();
		} elseif ( $b instanceof Blob ) {
			return $b->fetch();
		}

		return pg_unescape_bytea( $b );
	}

	/** @inheritDoc */
	public function strencode( $s ) {
		// Should not be called by us
		return pg_escape_string( $this->getBindingHandle(), (string)$s );
	}

	/** @inheritDoc */
	public function addQuotes( $s ) {
		if ( $s instanceof RawSQLValue ) {
			return $s->toSql();
		}
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
		}

		return "'" . pg_escape_string( $conn, (string)$s ) . "'";
	}

	/** @inheritDoc */
	public function streamStatementEnd( &$sql, &$newLine ) {
		# Allow dollar quoting for function declarations
		if ( str_starts_with( $newLine, '$mw$' ) ) {
			if ( $this->delimiter ) {
				$this->delimiter = false;
			} else {
				$this->delimiter = ';';
			}
		}

		return parent::streamStatementEnd( $sql, $newLine );
	}

	/** @inheritDoc */
	public function doLockIsFree( string $lockName, string $method ) {
		$query = new Query(
			$this->platform->lockIsFreeSQLText( $lockName ),
			self::QUERY_CHANGE_LOCKS,
			'SELECT'
		);
		$res = $this->query( $query, $method );
		$row = $res->fetchObject();

		return (bool)$row->unlocked;
	}

	/** @inheritDoc */
	public function doLock( string $lockName, string $method, int $timeout ) {
		$query = new Query(
			$this->platform->lockSQLText( $lockName, $timeout ),
			self::QUERY_CHANGE_LOCKS,
			'SELECT'
		);

		$acquired = null;
		$loop = new WaitConditionLoop(
			function () use ( $query, $method, &$acquired ) {
				$res = $this->query( $query, $method );
				$row = $res->fetchObject();

				if ( $row->acquired !== null ) {
					$acquired = (float)$row->acquired;

					return WaitConditionLoop::CONDITION_REACHED;
				}

				return WaitConditionLoop::CONDITION_CONTINUE;
			},
			$timeout
		);
		$loop->invoke();

		return $acquired;
	}

	/** @inheritDoc */
	public function doUnlock( string $lockName, string $method ) {
		$query = new Query(
			$this->platform->unlockSQLText( $lockName ),
			self::QUERY_CHANGE_LOCKS,
			'SELECT'
		);
		$result = $this->query( $query, $method );
		$row = $result->fetchObject();

		return (bool)$row->released;
	}

	/** @inheritDoc */
	protected function doFlushSession( $fname ) {
		$flags = self::QUERY_CHANGE_LOCKS | self::QUERY_NO_RETRY;

		// https://www.postgresql.org/docs/9.1/functions-admin.html
		$sql = "SELECT pg_advisory_unlock_all()";
		$query = new Query( $sql, $flags, 'UNLOCK' );
		$qs = $this->executeQuery( $query, __METHOD__, $flags );
		if ( $qs->res === false ) {
			$this->reportQueryError( $qs->message, $qs->code, $sql, $fname, true );
		}
	}

	/** @inheritDoc */
	public function serverIsReadOnly() {
		$query = new Query(
			"SHOW default_transaction_read_only",
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
			'SHOW'
		);
		$res = $this->query( $query, __METHOD__ );
		$row = $res->fetchObject();

		return $row && strtolower( $row->default_transaction_read_only ) === 'on';
	}

	/** @inheritDoc */
	protected function getInsertIdColumnForUpsert( $table ) {
		$column = null;

		$flags = self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE;
		$components = $this->platform->qualifiedTableComponents( $table );
		$encTable = $this->addQuotes( end( $components ) );
		foreach ( $this->getCoreSchemas() as $schema ) {
			$encSchema = $this->addQuotes( $schema );
			$query = new Query(
				"SELECT column_name,data_type,column_default " .
					"FROM information_schema.columns " .
					"WHERE table_name = $encTable AND table_schema = $encSchema",
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE,
				'SELECT'
			);
			$res = $this->query( $query, __METHOD__ );
			if ( $res->numRows() ) {
				foreach ( $res as $row ) {
					if (
						$row->column_default !== null &&
						str_starts_with( $row->column_default, "nextval(" ) &&
						in_array( $row->data_type, [ 'integer', 'bigint' ], true )
					) {
						$column = $row->column_name;
					}
				}
				break;
			}
		}

		return $column;
	}

	/** @inheritDoc */
	public static function getAttributes() {
		return [ self::ATTR_SCHEMAS_AS_TABLE_GROUPS => true ];
	}
}
