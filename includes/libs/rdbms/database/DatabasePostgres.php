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

use RuntimeException;
use Wikimedia\Rdbms\Platform\PostgresPlatform;
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
	/** @var null|string[] Map of (reserved table name => alternate table name) */
	private $keywordTableMap;
	/** @var float|string */
	private $numericVersion;

	/** @var resource|null */
	private $lastResultHandle;

	/** @var PostgresPlatform */
	protected $platform;

	/**
	 * @see Database::__construct()
	 * @param array $params Additional parameters include:
	 *   - port: A port to append to the hostname
	 *   - keywordTableMap : Map of reserved table names to alternative table names to use
	 *   This is is deprecated since 1.37. Reserved identifiers should be quoted where necessary,
	 */
	public function __construct( array $params ) {
		$this->port = intval( $params['port'] ?? null );

		if ( isset( $params['keywordTableMap'] ) ) {
			wfDeprecatedMsg( 'Passing keywordTableMap parameter to ' .
				'DatabasePostgres::__construct() is deprecated', '1.37'
			);

			$this->keywordTableMap = $params['keywordTableMap'];
		}

		parent::__construct( $params );
		$this->platform = new PostgresPlatform(
			$this,
			$params['queryLogger'],
			$this->currentDomain
		);
	}

	public function getType() {
		return 'postgres';
	}

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
			'dbname' => strlen( $db ) ? $db : 'postgres',
			'user' => $user,
			'password' => $password
		];
		if ( strlen( $server ) ) {
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
				$this->query(
					'SET ' . $this->platform->addIdentifierQuotes( $var ) . ' = ' . $this->addQuotes( $val ),
					__METHOD__,
					self::QUERY_NO_RETRY | self::QUERY_CHANGE_TRX
				);
			}
			$this->determineCoreSchema( $schema );
			$this->currentDomain = new DatabaseDomain( $db, $schema, $tablePrefix );
			$this->platform->setCurrentDomain( $this->currentDomain );
		} catch ( RuntimeException $e ) {
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
	}

	public function databasesAreIndependent() {
		return true;
	}

	public function doSelectDomain( DatabaseDomain $domain ) {
		if ( $this->getDBname() !== $domain->getDatabase() ) {
			// Postgres doesn't support selectDB in the same way MySQL does.
			// So if the DB name doesn't match the open connection, open a new one
			$this->open(
				$this->connectionParams[self::CONN_HOST],
				$this->connectionParams[self::CONN_USER],
				$this->connectionParams[self::CONN_PASSWORD],
				$domain->getDatabase(),
				$domain->getSchema(),
				$domain->getTablePrefix()
			);
		} else {
			$this->currentDomain = $domain;
			$this->platform->setCurrentDomain( $this->currentDomain );
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

	public function doSingleStatementQuery( string $sql ): QueryStatus {
		$conn = $this->getBindingHandle();

		$sql = mb_convert_encoding( $sql, 'UTF-8' );
		// Clear any previously left over result
		while ( $priorRes = pg_get_result( $conn ) ) {
			pg_free_result( $priorRes );
		}

		if ( pg_send_query( $conn, $sql ) === false ) {
			throw new DBUnexpectedError( $this, "Unable to post new query to PostgreSQL\n" );
		}

		// Newer PHP versions use PgSql\Result instead of resource variables
		// https://www.php.net/manual/en/function.pg-get-result.php
		$pgRes = pg_get_result( $conn );
		$this->lastResultHandle = $pgRes;
		$res = pg_result_error( $pgRes ) ? false : $pgRes;

		return new QueryStatus(
			is_bool( $res ) ? $res : new PostgresResultWrapper( $this, $conn, $res ),
			$this->affectedRows(),
			$this->lastError(),
			$this->lastErrno()
		);
	}

	protected function doMultiStatementQuery( array $sqls ): array {
		$qsByStatementId = [];

		$conn = $this->getBindingHandle();
		// Clear any previously left over result
		while ( $pgResultSet = pg_get_result( $conn ) ) {
			pg_free_result( $pgResultSet );
		}

		$combinedSql = mb_convert_encoding( implode( ";\n", $sqls ), 'UTF-8' );
		pg_send_query( $conn, $combinedSql );

		reset( $sqls );
		while ( ( $pgResultSet = pg_get_result( $conn ) ) !== false ) {
			$this->lastResultHandle = $pgResultSet;

			$statementId = key( $sqls );
			if ( $statementId !== null ) {
				if ( pg_result_error( $pgResultSet ) ) {
					$res = false;
				} else {
					$res = new PostgresResultWrapper( $this, $conn, $pgResultSet );
				}
				$qsByStatementId[$statementId] = new QueryStatus(
					$res,
					pg_affected_rows( $pgResultSet ),
					(string)pg_result_error( $pgResultSet ),
					pg_result_error_field( $pgResultSet, PGSQL_DIAG_SQLSTATE )
				);
			}
			next( $sqls );
		}
		// Fill in status for statements aborted due to prior statement failure
		while ( ( $statementId = key( $sqls ) ) !== null ) {
			$qsByStatementId[$statementId] = new QueryStatus( false, 0, 'Query aborted', 0 );
			next( $sqls );
		}

		return $qsByStatementId;
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

	public function insertId() {
		$res = $this->query(
			"SELECT lastval()",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res->fetchRow();

		// @phan-suppress-next-line PhanTypeMismatchReturnProbablyReal Return type is undefined for no lastval
		return $row[0] === null ? null : (int)$row[0];
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
			$lastErrno = pg_result_error_field( $this->lastResultHandle, PGSQL_DIAG_SQLSTATE );
			if ( $lastErrno !== false ) {
				return $lastErrno;
			}
		}

		return '00000';
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
			return false;
		}

		return $res->numRows() > 0;
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
			parent::doInsertSelectNative( $destTable, $srcTable, $varMap, $conds, $fname,
				$insertOptions, $selectOptions, $selectJoinConds );
		}
	}

	/**
	 * @param string $name
	 * @return string Value of $name or remapped name if $name is a reserved keyword
	 * @deprecated since 1.37. Reserved identifiers should be quoted where necessary
	 */
	public function remappedTableName( $name ) {
		wfDeprecated( __METHOD__, '1.37' );

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
		$row = $res->fetchRow();
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
		$row = $res->fetchObject();
		if ( $row->ftype == 'varchar' ) {
			$size = $row->size - 4;
		} else {
			$size = $row->size;
		}

		return $size;
	}

	public function wasDeadlock() {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		return $this->lastErrno() === '40P01';
	}

	public function wasLockTimeout() {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		return $this->lastErrno() === '55P03';
	}

	protected function isConnectionError( $errno ) {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		static $codes = [ '08000', '08003', '08006', '08001', '08004', '57P01', '57P03', '53300' ];

		return in_array( $errno, $codes, true );
	}

	protected function isQueryTimeoutError( $errno ) {
		// https://www.postgresql.org/docs/9.2/static/errcodes-appendix.html
		return ( $errno === '57014' );
	}

	protected function isKnownStatementRollbackError( $errno ) {
		return false; // transaction has to be rolled-back from error state
	}

	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$newNameE = $this->platform->addIdentifierQuotes( $newName );
		$oldNameE = $this->platform->addIdentifierQuotes( $oldName );

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
		$row = $res->fetchObject();
		if ( $row ) {
			$field = $row->attname;
			$newSeq = "{$newName}_{$field}_seq";
			$fieldE = $this->platform->addIdentifierQuotes( $field );
			$newSeqE = $this->platform->addIdentifierQuotes( $newSeq );
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
		$res = $this->query(
			"SELECT current_schemas(false)",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
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
		$res = $this->query(
			"SHOW search_path",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
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
		$this->query(
			"SET search_path = " . implode( ", ", $search_path ),
			__METHOD__,
			self::QUERY_CHANGE_TRX
		);
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
				$this->platform->setCoreSchema( $desiredSchema );
				$this->queryLogger->debug(
					"Schema \"" . $desiredSchema . "\" already in the search path\n" );
			} else {
				// Prepend the desired schema to the search path (T17816)
				$search_path = $this->getSearchPath();
				array_unshift( $search_path, $this->platform->addIdentifierQuotes( $desiredSchema ) );
				$this->setSearchPath( $search_path );
				$this->platform->setCoreSchema( $desiredSchema );
				$this->queryLogger->debug(
					"Schema \"" . $desiredSchema . "\" added to the search path\n" );
			}
		} else {
			$this->platform->setCoreSchema( $this->getCurrentSchema() );
			$this->queryLogger->debug(
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

		$res = $this->query(
			"SELECT nspname FROM pg_catalog.pg_namespace n WHERE n.oid = pg_my_temp_schema()",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res->fetchObject();
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

		return ( $res->numRows() > 0 );
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

	public function doLockIsFree( string $lockName, string $method ) {
		$res = $this->query(
			$this->platform->lockIsFreeSQLText( $lockName ),
			$method,
			self::QUERY_CHANGE_LOCKS
		);
		$row = $res->fetchObject();

		return ( $row->unlocked === 't' );
	}

	public function doLock( string $lockName, string $method, int $timeout ) {
		$sql = $this->platform->lockSQLText( $lockName, $timeout );

		$acquired = null;
		$loop = new WaitConditionLoop(
			function () use ( $lockName, $sql, $timeout, $method, &$acquired ) {
				$res = $this->query(
					$sql,
					$method,
					self::QUERY_CHANGE_LOCKS
				);
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

	public function doUnlock( string $lockName, string $method ) {
		$result = $this->query(
			$this->platform->unlockSQLText( $lockName ),
			$method,
			self::QUERY_CHANGE_LOCKS
		);
		$row = $result->fetchObject();

		return ( $row->released === 't' );
	}

	protected function doFlushSession( $fname ) {
		$flags = self::QUERY_CHANGE_LOCKS | self::QUERY_NO_RETRY;

		// https://www.postgresql.org/docs/9.1/functions-admin.html
		$sql = "pg_advisory_unlock_all()";
		$qs = $this->executeQuery( $sql, __METHOD__, $flags, $sql );
		if ( $qs->res === false ) {
			$this->reportQueryError( $qs->message, $qs->code, $sql, $fname, true );
		}
	}

	public function serverIsReadOnly() {
		$res = $this->query(
			"SHOW default_transaction_read_only",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$row = $res->fetchObject();

		return $row ? ( strtolower( $row->default_transaction_read_only ) === 'on' ) : false;
	}

	protected static function getAttributes() {
		return [ self::ATTR_SCHEMAS_AS_TABLE_GROUPS => true ];
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabasePostgres::class, 'DatabasePostgres' );
