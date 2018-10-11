<?php
/**
 * This is the Oracle database abstraction layer.
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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\Blob;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\DBExpectedError;

/**
 * @ingroup Database
 */
class DatabaseOracle extends Database {
	/** @var resource */
	protected $mLastResult = null;

	/** @var int The number of rows affected as an integer */
	protected $mAffectedRows;

	/** @var bool */
	private $ignoreDupValOnIndex = false;

	/** @var bool|array */
	private $sequenceData = null;

	/** @var string Character set for Oracle database */
	private $defaultCharset = 'AL32UTF8';

	/** @var array */
	private $mFieldInfoCache = [];

	function __construct( array $p ) {
		global $wgDBprefix;

		if ( $p['tablePrefix'] == 'get from global' ) {
			$p['tablePrefix'] = $wgDBprefix;
		}
		$p['tablePrefix'] = strtoupper( $p['tablePrefix'] );
		parent::__construct( $p );
		Hooks::run( 'DatabaseOraclePostInit', [ $this ] );
	}

	function __destruct() {
		if ( $this->opened ) {
			Wikimedia\suppressWarnings();
			$this->close();
			Wikimedia\restoreWarnings();
		}
	}

	function getType() {
		return 'oracle';
	}

	function implicitGroupby() {
		return false;
	}

	function implicitOrderby() {
		return false;
	}

	protected function open( $server, $user, $password, $dbName, $schema, $tablePrefix ) {
		global $wgDBOracleDRCP;

		if ( !function_exists( 'oci_connect' ) ) {
			throw new DBConnectionError(
				$this,
				"Oracle functions missing, have you compiled PHP with the --with-oci8 option?\n " .
					"(Note: if you recently installed PHP, you may need to restart your webserver\n " .
					"and database)\n" );
		}

		$this->close();
		$this->user = $user;
		$this->password = $password;
		if ( !$server ) {
			// Backward compatibility (server used to be null and TNS was supplied in dbname)
			$this->server = $dbName;
			$realDatabase = $user;
		} else {
			// $server now holds the TNS endpoint
			$this->server = $server;
			// $dbName is schema name if different from username
			$realDatabase = $dbName ?: $user;
		}

		if ( !strlen( $user ) ) { # e.g. the class is being loaded
			return null;
		}

		if ( $wgDBOracleDRCP ) {
			$this->setFlag( DBO_PERSISTENT );
		}

		$session_mode = $this->flags & DBO_SYSDBA ? OCI_SYSDBA : OCI_DEFAULT;

		Wikimedia\suppressWarnings();
		if ( $this->flags & DBO_PERSISTENT ) {
			$this->conn = oci_pconnect(
				$this->user,
				$this->password,
				$this->server,
				$this->defaultCharset,
				$session_mode
			);
		} elseif ( $this->flags & DBO_DEFAULT ) {
			$this->conn = oci_new_connect(
				$this->user,
				$this->password,
				$this->server,
				$this->defaultCharset,
				$session_mode
			);
		} else {
			$this->conn = oci_connect(
				$this->user,
				$this->password,
				$this->server,
				$this->defaultCharset,
				$session_mode
			);
		}
		Wikimedia\restoreWarnings();

		if ( $this->user != $realDatabase ) {
			// change current schema in session
			$this->selectDB( $realDatabase );
		} else {
			$this->currentDomain = new DatabaseDomain(
				$realDatabase,
				null,
				$tablePrefix
			);
		}

		if ( !$this->conn ) {
			throw new DBConnectionError( $this, $this->lastError() );
		}

		$this->opened = true;

		# removed putenv calls because they interfere with the system globaly
		$this->doQuery( 'ALTER SESSION SET NLS_TIMESTAMP_FORMAT=\'DD-MM-YYYY HH24:MI:SS.FF6\'' );
		$this->doQuery( 'ALTER SESSION SET NLS_TIMESTAMP_TZ_FORMAT=\'DD-MM-YYYY HH24:MI:SS.FF6\'' );
		$this->doQuery( 'ALTER SESSION SET NLS_NUMERIC_CHARACTERS=\'.,\'' );

		return (bool)$this->conn;
	}

	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 * @return bool
	 */
	protected function closeConnection() {
		return oci_close( $this->conn );
	}

	function execFlags() {
		return $this->trxLevel ? OCI_NO_AUTO_COMMIT : OCI_COMMIT_ON_SUCCESS;
	}

	protected function doQuery( $sql ) {
		wfDebug( "SQL: [$sql]\n" );
		if ( !StringUtils::isUtf8( $sql ) ) {
			throw new InvalidArgumentException( "SQL encoding is invalid\n$sql" );
		}

		// handle some oracle specifics
		// remove AS column/table/subquery namings
		if ( !$this->getFlag( DBO_DDLMODE ) ) {
			$sql = preg_replace( '/ as /i', ' ', $sql );
		}

		// Oracle has issues with UNION clause if the statement includes LOB fields
		// So we do a UNION ALL and then filter the results array with array_unique
		$union_unique = ( preg_match( '/\/\* UNION_UNIQUE \*\/ /', $sql ) != 0 );
		// EXPLAIN syntax in Oracle is EXPLAIN PLAN FOR and it return nothing
		// you have to select data from plan table after explain
		$explain_id = MWTimestamp::getLocalInstance()->format( 'dmYHis' );

		$sql = preg_replace(
			'/^EXPLAIN /',
			'EXPLAIN PLAN SET STATEMENT_ID = \'' . $explain_id . '\' FOR',
			$sql,
			1,
			$explain_count
		);

		Wikimedia\suppressWarnings();

		$this->mLastResult = $stmt = oci_parse( $this->conn, $sql );
		if ( $stmt === false ) {
			$e = oci_error( $this->conn );
			$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

			return false;
		}

		if ( !oci_execute( $stmt, $this->execFlags() ) ) {
			$e = oci_error( $stmt );
			if ( !$this->ignoreDupValOnIndex || $e['code'] != '1' ) {
				$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

				return false;
			}
		}

		Wikimedia\restoreWarnings();

		if ( $explain_count > 0 ) {
			return $this->doQuery( 'SELECT id, cardinality "ROWS" FROM plan_table ' .
				'WHERE statement_id = \'' . $explain_id . '\'' );
		} elseif ( oci_statement_type( $stmt ) == 'SELECT' ) {
			return new ORAResult( $this, $stmt, $union_unique );
		} else {
			$this->mAffectedRows = oci_num_rows( $stmt );

			return true;
		}
	}

	function queryIgnore( $sql, $fname = '' ) {
		return $this->query( $sql, $fname, true );
	}

	/**
	 * Frees resources associated with the LOB descriptor
	 * @param ResultWrapper|ORAResult $res
	 */
	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		$res->free();
	}

	/**
	 * @param ResultWrapper|ORAResult $res
	 * @return mixed
	 */
	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $res->fetchObject();
	}

	/**
	 * @param ResultWrapper|ORAResult $res
	 * @return mixed
	 */
	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $res->fetchRow();
	}

	/**
	 * @param ResultWrapper|ORAResult $res
	 * @return int
	 */
	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $res->numRows();
	}

	/**
	 * @param ResultWrapper|ORAResult $res
	 * @return int
	 */
	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}

		return $res->numFields();
	}

	function fieldName( $stmt, $n ) {
		return oci_field_name( $stmt, $n );
	}

	function insertId() {
		$res = $this->query( "SELECT lastval_pkg.getLastval FROM dual" );
		$row = $this->fetchRow( $res );
		return is_null( $row[0] ) ? null : (int)$row[0];
	}

	/**
	 * @param mixed $res
	 * @param int $row
	 */
	function dataSeek( $res, $row ) {
		if ( $res instanceof ORAResult ) {
			$res->seek( $row );
		} else {
			$res->result->seek( $row );
		}
	}

	function lastError() {
		if ( $this->conn === false ) {
			$e = oci_error();
		} else {
			$e = oci_error( $this->conn );
		}

		return $e['message'];
	}

	function lastErrno() {
		if ( $this->conn === false ) {
			$e = oci_error();
		} else {
			$e = oci_error( $this->conn );
		}

		return $e['code'];
	}

	protected function fetchAffectedRowCount() {
		return $this->mAffectedRows;
	}

	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool
	 */
	function indexInfo( $table, $index, $fname = __METHOD__ ) {
		return false;
	}

	function indexUnique( $table, $index, $fname = __METHOD__ ) {
		return false;
	}

	function insert( $table, $a, $fname = __METHOD__, $options = [] ) {
		if ( !count( $a ) ) {
			return true;
		}

		if ( !is_array( $options ) ) {
			$options = [ $options ];
		}

		if ( in_array( 'IGNORE', $options ) ) {
			$this->ignoreDupValOnIndex = true;
		}

		if ( !is_array( reset( $a ) ) ) {
			$a = [ $a ];
		}

		foreach ( $a as &$row ) {
			$this->insertOneRow( $table, $row, $fname );
		}
		$retVal = true;

		if ( in_array( 'IGNORE', $options ) ) {
			$this->ignoreDupValOnIndex = false;
		}

		return $retVal;
	}

	private function fieldBindStatement( $table, $col, &$val, $includeCol = false ) {
		$col_info = $this->fieldInfoMulti( $table, $col );
		$col_type = $col_info != false ? $col_info->type() : 'CONSTANT';

		$bind = '';
		if ( is_numeric( $col ) ) {
			$bind = $val;
			$val = null;

			return $bind;
		} elseif ( $includeCol ) {
			$bind = "$col = ";
		}

		if ( $val == '' && $val !== 0 && $col_type != 'BLOB' && $col_type != 'CLOB' ) {
			$val = null;
		}

		if ( $val === 'NULL' ) {
			$val = null;
		}

		if ( $val === null ) {
			if ( $col_info != false && $col_info->isNullable() == 0 && $col_info->defaultValue() != null ) {
				$bind .= 'DEFAULT';
			} else {
				$bind .= 'NULL';
			}
		} else {
			$bind .= ':' . $col;
		}

		return $bind;
	}

	/**
	 * @param string $table
	 * @param array $row
	 * @param string $fname
	 * @return bool
	 * @throws DBUnexpectedError
	 */
	private function insertOneRow( $table, $row, $fname ) {
		$table = $this->tableName( $table );
		// "INSERT INTO tables (a, b, c)"
		$sql = "INSERT INTO " . $table . " (" . implode( ',', array_keys( $row ) ) . ')';
		$sql .= " VALUES (";

		// for each value, append ":key"
		$first = true;
		foreach ( $row as $col => &$val ) {
			if ( !$first ) {
				$sql .= ', ';
			} else {
				$first = false;
			}
			if ( $this->isQuotedIdentifier( $val ) ) {
				$sql .= $this->removeIdentifierQuotes( $val );
				unset( $row[$col] );
			} else {
				$sql .= $this->fieldBindStatement( $table, $col, $val );
			}
		}
		$sql .= ')';

		$this->mLastResult = $stmt = oci_parse( $this->conn, $sql );
		if ( $stmt === false ) {
			$e = oci_error( $this->conn );
			$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

			return false;
		}
		foreach ( $row as $col => &$val ) {
			$col_info = $this->fieldInfoMulti( $table, $col );
			$col_type = $col_info != false ? $col_info->type() : 'CONSTANT';

			if ( $val === null ) {
				// do nothing ... null was inserted in statement creation
			} elseif ( $col_type != 'BLOB' && $col_type != 'CLOB' ) {
				if ( is_object( $val ) ) {
					$val = $val->fetch();
				}

				// backward compatibility
				if ( preg_match( '/^timestamp.*/i', $col_type ) == 1 && strtolower( $val ) == 'infinity' ) {
					$val = $this->getInfinity();
				}

				$val = MediaWikiServices::getInstance()->getContentLanguage()->
					checkTitleEncoding( $val );
				if ( oci_bind_by_name( $stmt, ":$col", $val, -1, SQLT_CHR ) === false ) {
					$e = oci_error( $stmt );
					$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

					return false;
				}
			} else {
				/** @var OCI_Lob[] $lob */
				$lob[$col] = oci_new_descriptor( $this->conn, OCI_D_LOB );
				if ( $lob[$col] === false ) {
					$e = oci_error( $stmt );
					throw new DBUnexpectedError( $this, "Cannot create LOB descriptor: " . $e['message'] );
				}

				if ( is_object( $val ) ) {
					$val = $val->fetch();
				}

				if ( $col_type == 'BLOB' ) {
					$lob[$col]->writeTemporary( $val, OCI_TEMP_BLOB );
					oci_bind_by_name( $stmt, ":$col", $lob[$col], -1, OCI_B_BLOB );
				} else {
					$lob[$col]->writeTemporary( $val, OCI_TEMP_CLOB );
					oci_bind_by_name( $stmt, ":$col", $lob[$col], -1, OCI_B_CLOB );
				}
			}
		}

		Wikimedia\suppressWarnings();

		if ( oci_execute( $stmt, $this->execFlags() ) === false ) {
			$e = oci_error( $stmt );
			if ( !$this->ignoreDupValOnIndex || $e['code'] != '1' ) {
				$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

				return false;
			} else {
				$this->mAffectedRows = oci_num_rows( $stmt );
			}
		} else {
			$this->mAffectedRows = oci_num_rows( $stmt );
		}

		Wikimedia\restoreWarnings();

		if ( isset( $lob ) ) {
			foreach ( $lob as $lob_v ) {
				$lob_v->free();
			}
		}

		if ( !$this->trxLevel ) {
			oci_commit( $this->conn );
		}

		return oci_free_statement( $stmt );
	}

	function nativeInsertSelect( $destTable, $srcTable, $varMap, $conds, $fname = __METHOD__,
		$insertOptions = [], $selectOptions = [], $selectJoinConds = []
	) {
		$destTable = $this->tableName( $destTable );

		$sequenceData = $this->getSequenceData( $destTable );
		if ( $sequenceData !== false &&
			!isset( $varMap[$sequenceData['column']] )
		) {
			$varMap[$sequenceData['column']] = 'GET_SEQUENCE_VALUE(\'' . $sequenceData['sequence'] . '\')';
		}

		// count-alias subselect fields to avoid abigious definition errors
		$i = 0;
		foreach ( $varMap as &$val ) {
			$val = $val . ' field' . ( $i++ );
		}

		$selectSql = $this->selectSQLText(
			$srcTable,
			array_values( $varMap ),
			$conds,
			$fname,
			$selectOptions,
			$selectJoinConds
		);

		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ') ' . $selectSql;

		if ( in_array( 'IGNORE', $insertOptions ) ) {
			$this->ignoreDupValOnIndex = true;
		}

		$retval = $this->query( $sql, $fname );

		if ( in_array( 'IGNORE', $insertOptions ) ) {
			$this->ignoreDupValOnIndex = false;
		}

		return $retval;
	}

	public function upsert( $table, array $rows, array $uniqueIndexes, array $set,
		$fname = __METHOD__
	) {
		if ( !count( $rows ) ) {
			return true; // nothing to do
		}

		if ( !is_array( reset( $rows ) ) ) {
			$rows = [ $rows ];
		}

		$sequenceData = $this->getSequenceData( $table );
		if ( $sequenceData !== false ) {
			// add sequence column to each list of columns, when not set
			foreach ( $rows as &$row ) {
				if ( !isset( $row[$sequenceData['column']] ) ) {
					$row[$sequenceData['column']] =
						$this->addIdentifierQuotes( 'GET_SEQUENCE_VALUE(\'' .
							$sequenceData['sequence'] . '\')' );
				}
			}
		}

		return parent::upsert( $table, $rows, $uniqueIndexes, $set, $fname );
	}

	function tableName( $name, $format = 'quoted' ) {
		/*
		Replace reserved words with better ones
		Using uppercase because that's the only way Oracle can handle
		quoted tablenames
		*/
		switch ( $name ) {
			case 'user':
				$name = 'MWUSER';
				break;
			case 'text':
				$name = 'PAGECONTENT';
				break;
		}

		return strtoupper( parent::tableName( $name, $format ) );
	}

	function tableNameInternal( $name ) {
		$name = $this->tableName( $name );

		return preg_replace( '/.*\.(.*)/', '$1', $name );
	}

	/**
	 * Return sequence_name if table has a sequence
	 *
	 * @param string $table
	 * @return bool
	 */
	private function getSequenceData( $table ) {
		if ( $this->sequenceData == null ) {
			$result = $this->doQuery( "SELECT lower(asq.sequence_name),
				lower(atc.table_name),
				lower(atc.column_name)
			FROM all_sequences asq, all_tab_columns atc
			WHERE decode(
					atc.table_name,
					'{$this->tablePrefix}MWUSER',
					'{$this->tablePrefix}USER',
					atc.table_name
				) || '_' ||
				atc.column_name || '_SEQ' = '{$this->tablePrefix}' || asq.sequence_name
				AND asq.sequence_owner = upper('{$this->getDBname()}')
				AND atc.owner = upper('{$this->getDBname()}')" );

			while ( ( $row = $result->fetchRow() ) !== false ) {
				$this->sequenceData[$row[1]] = [
					'sequence' => $row[0],
					'column' => $row[2]
				];
			}
		}
		$table = strtolower( $this->removeIdentifierQuotes( $this->tableName( $table ) ) );

		return $this->sequenceData[$table] ?? false;
	}

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 *
	 * @param string $table
	 * @param string $field
	 * @return mixed
	 */
	function textFieldSize( $table, $field ) {
		$fieldInfoData = $this->fieldInfo( $table, $field );

		return $fieldInfoData->maxLength();
	}

	function limitResult( $sql, $limit, $offset = false ) {
		if ( $offset === false ) {
			$offset = 0;
		}

		return "SELECT * FROM ($sql) WHERE rownum >= (1 + $offset) AND rownum < (1 + $limit + $offset)";
	}

	function encodeBlob( $b ) {
		return new Blob( $b );
	}

	function decodeBlob( $b ) {
		if ( $b instanceof Blob ) {
			$b = $b->fetch();
		}

		return $b;
	}

	function unionQueries( $sqls, $all ) {
		$glue = ' UNION ALL ';

		return 'SELECT * ' . ( $all ? '' : '/* UNION_UNIQUE */ ' ) .
			'FROM (' . implode( $glue, $sqls ) . ')';
	}

	function wasDeadlock() {
		return $this->lastErrno() == 'OCI-00060';
	}

	function duplicateTableStructure( $oldName, $newName, $temporary = false,
		$fname = __METHOD__
	) {
		$temporary = $temporary ? 'TRUE' : 'FALSE';

		$newName = strtoupper( $newName );
		$oldName = strtoupper( $oldName );

		$tabName = substr( $newName, strlen( $this->tablePrefix ) );
		$oldPrefix = substr( $oldName, 0, strlen( $oldName ) - strlen( $tabName ) );
		$newPrefix = strtoupper( $this->tablePrefix );

		return $this->doQuery( "BEGIN DUPLICATE_TABLE( '$tabName', " .
			"'$oldPrefix', '$newPrefix', $temporary ); END;" );
	}

	function listTables( $prefix = null, $fname = __METHOD__ ) {
		$listWhere = '';
		if ( !empty( $prefix ) ) {
			$listWhere = ' AND table_name LIKE \'' . strtoupper( $prefix ) . '%\'';
		}

		$owner = strtoupper( $this->getDBname() );
		$result = $this->doQuery( "SELECT table_name FROM all_tables " .
			"WHERE owner='$owner' AND table_name NOT LIKE '%!_IDX\$_' ESCAPE '!' $listWhere" );

		// dirty code ... i know
		$endArray = [];
		$endArray[] = strtoupper( $prefix . 'MWUSER' );
		$endArray[] = strtoupper( $prefix . 'PAGE' );
		$endArray[] = strtoupper( $prefix . 'IMAGE' );
		$fixedOrderTabs = $endArray;
		while ( ( $row = $result->fetchRow() ) !== false ) {
			if ( !in_array( $row['table_name'], $fixedOrderTabs ) ) {
				$endArray[] = $row['table_name'];
			}
		}

		return $endArray;
	}

	public function dropTable( $tableName, $fName = __METHOD__ ) {
		$tableName = $this->tableName( $tableName );
		if ( !$this->tableExists( $tableName ) ) {
			return false;
		}

		return $this->doQuery( "DROP TABLE $tableName CASCADE CONSTRAINTS PURGE" );
	}

	function timestamp( $ts = 0 ) {
		return wfTimestamp( TS_ORACLE, $ts );
	}

	/**
	 * Return aggregated value function call
	 *
	 * @param array $valuedata
	 * @param string $valuename
	 * @return mixed
	 */
	public function aggregateValue( $valuedata, $valuename = 'value' ) {
		return $valuedata;
	}

	/**
	 * @return string Wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink() {
		return '[{{int:version-db-oracle-url}} Oracle]';
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		// better version number, fallback on driver
		$rset = $this->doQuery(
			'SELECT version FROM product_component_version ' .
				'WHERE UPPER(product) LIKE \'ORACLE DATABASE%\''
		);
		$row = $rset->fetchRow();
		if ( !$row ) {
			return oci_server_version( $this->conn );
		}

		return $row['version'];
	}

	/**
	 * Query whether a given index exists
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool
	 */
	function indexExists( $table, $index, $fname = __METHOD__ ) {
		$table = $this->tableName( $table );
		$table = strtoupper( $this->removeIdentifierQuotes( $table ) );
		$index = strtoupper( $index );
		$owner = strtoupper( $this->getDBname() );
		$sql = "SELECT 1 FROM all_indexes WHERE owner='$owner' AND index_name='{$table}_{$index}'";
		$res = $this->doQuery( $sql );
		if ( $res ) {
			$count = $res->numRows();
			$res->free();
		} else {
			$count = 0;
		}

		return $count != 0;
	}

	/**
	 * Query whether a given table exists (in the given schema, or the default mw one if not given)
	 * @param string $table
	 * @param string $fname
	 * @return bool
	 */
	function tableExists( $table, $fname = __METHOD__ ) {
		$table = $this->tableName( $table );
		$table = $this->addQuotes( strtoupper( $this->removeIdentifierQuotes( $table ) ) );
		$owner = $this->addQuotes( strtoupper( $this->getDBname() ) );
		$sql = "SELECT 1 FROM all_tables WHERE owner=$owner AND table_name=$table";
		$res = $this->doQuery( $sql );
		if ( $res && $res->numRows() > 0 ) {
			$exists = true;
		} else {
			$exists = false;
		}

		$res->free();

		return $exists;
	}

	/**
	 * Function translates mysql_fetch_field() functionality on ORACLE.
	 * Caching is present for reducing query time.
	 * For internal calls. Use fieldInfo for normal usage.
	 * Returns false if the field doesn't exist
	 *
	 * @param array|string $table
	 * @param string $field
	 * @return ORAField|ORAResult|false
	 */
	private function fieldInfoMulti( $table, $field ) {
		$field = strtoupper( $field );
		if ( is_array( $table ) ) {
			$table = array_map( [ $this, 'tableNameInternal' ], $table );
			$tableWhere = 'IN (';
			foreach ( $table as &$singleTable ) {
				$singleTable = $this->removeIdentifierQuotes( $singleTable );
				if ( isset( $this->mFieldInfoCache["$singleTable.$field"] ) ) {
					return $this->mFieldInfoCache["$singleTable.$field"];
				}
				$tableWhere .= '\'' . $singleTable . '\',';
			}
			$tableWhere = rtrim( $tableWhere, ',' ) . ')';
		} else {
			$table = $this->removeIdentifierQuotes( $this->tableNameInternal( $table ) );
			if ( isset( $this->mFieldInfoCache["$table.$field"] ) ) {
				return $this->mFieldInfoCache["$table.$field"];
			}
			$tableWhere = '= \'' . $table . '\'';
		}

		$fieldInfoStmt = oci_parse(
			$this->conn,
			'SELECT * FROM wiki_field_info_full WHERE table_name ' .
				$tableWhere . ' and column_name = \'' . $field . '\''
		);
		if ( oci_execute( $fieldInfoStmt, $this->execFlags() ) === false ) {
			$e = oci_error( $fieldInfoStmt );
			$this->reportQueryError( $e['message'], $e['code'], 'fieldInfo QUERY', __METHOD__ );

			return false;
		}
		$res = new ORAResult( $this, $fieldInfoStmt );
		if ( $res->numRows() == 0 ) {
			if ( is_array( $table ) ) {
				foreach ( $table as &$singleTable ) {
					$this->mFieldInfoCache["$singleTable.$field"] = false;
				}
			} else {
				$this->mFieldInfoCache["$table.$field"] = false;
			}
			$fieldInfoTemp = null;
		} else {
			$fieldInfoTemp = new ORAField( $res->fetchRow() );
			$table = $fieldInfoTemp->tableName();
			$this->mFieldInfoCache["$table.$field"] = $fieldInfoTemp;
		}
		$res->free();

		return $fieldInfoTemp;
	}

	/**
	 * @throws DBUnexpectedError
	 * @param string $table
	 * @param string $field
	 * @return ORAField
	 */
	function fieldInfo( $table, $field ) {
		if ( is_array( $table ) ) {
			throw new DBUnexpectedError( $this, 'DatabaseOracle::fieldInfo called with table array!' );
		}

		return $this->fieldInfoMulti( $table, $field );
	}

	protected function doBegin( $fname = __METHOD__ ) {
		$this->trxLevel = 1;
		$this->doQuery( 'SET CONSTRAINTS ALL DEFERRED' );
	}

	protected function doCommit( $fname = __METHOD__ ) {
		if ( $this->trxLevel ) {
			$ret = oci_commit( $this->conn );
			if ( !$ret ) {
				throw new DBUnexpectedError( $this, $this->lastError() );
			}
			$this->trxLevel = 0;
			$this->doQuery( 'SET CONSTRAINTS ALL IMMEDIATE' );
		}
	}

	protected function doRollback( $fname = __METHOD__ ) {
		if ( $this->trxLevel ) {
			oci_rollback( $this->conn );
			$this->trxLevel = 0;
			$this->doQuery( 'SET CONSTRAINTS ALL IMMEDIATE' );
		}
	}

	function sourceStream(
		$fp,
		callable $lineCallback = null,
		callable $resultCallback = null,
		$fname = __METHOD__, callable $inputCallback = null
	) {
		$cmd = '';
		$done = false;
		$dollarquote = false;

		$replacements = [];
		// Defines must comply with ^define\s*([^\s=]*)\s*=\s?'\{\$([^\}]*)\}';
		while ( !feof( $fp ) ) {
			if ( $lineCallback ) {
				call_user_func( $lineCallback );
			}
			$line = trim( fgets( $fp, 1024 ) );
			$sl = strlen( $line ) - 1;

			if ( $sl < 0 ) {
				continue;
			}
			if ( '-' == $line[0] && '-' == $line[1] ) {
				continue;
			}

			// Allow dollar quoting for function declarations
			if ( substr( $line, 0, 8 ) == '/*$mw$*/' ) {
				if ( $dollarquote ) {
					$dollarquote = false;
					$line = str_replace( '/*$mw$*/', '', $line ); // remove dollarquotes
					$done = true;
				} else {
					$dollarquote = true;
				}
			} elseif ( !$dollarquote ) {
				if ( ';' == $line[$sl] && ( $sl < 2 || ';' != $line[$sl - 1] ) ) {
					$done = true;
					$line = substr( $line, 0, $sl );
				}
			}

			if ( $cmd != '' ) {
				$cmd .= ' ';
			}
			$cmd .= "$line\n";

			if ( $done ) {
				$cmd = str_replace( ';;', ";", $cmd );
				if ( strtolower( substr( $cmd, 0, 6 ) ) == 'define' ) {
					if ( preg_match( '/^define\s*([^\s=]*)\s*=\s*\'\{\$([^\}]*)\}\'/', $cmd, $defines ) ) {
						$replacements[$defines[2]] = $defines[1];
					}
				} else {
					foreach ( $replacements as $mwVar => $scVar ) {
						$cmd = str_replace( '&' . $scVar . '.', '`{$' . $mwVar . '}`', $cmd );
					}

					$cmd = $this->replaceVars( $cmd );
					if ( $inputCallback ) {
						call_user_func( $inputCallback, $cmd );
					}
					$res = $this->doQuery( $cmd );
					if ( $resultCallback ) {
						call_user_func( $resultCallback, $res, $this );
					}

					if ( false === $res ) {
						$err = $this->lastError();

						return "Query \"{$cmd}\" failed with error code \"$err\".\n";
					}
				}

				$cmd = '';
				$done = false;
			}
		}

		return true;
	}

	protected function doSelectDomain( DatabaseDomain $domain ) {
		if ( $domain->getSchema() !== null ) {
			// We use the *database* aspect of $domain for schema, not the domain schema
			throw new DBExpectedError( $this, __CLASS__ . ": domain schemas are not supported." );
		}

		$database = $domain->getDatabase();
		if ( $database === null || $database === $this->user ) {
			// Backward compatibility
			$this->currentDomain = $domain;

			return true;
		}

		// https://docs.oracle.com/javadb/10.8.3.0/ref/rrefsqlj32268.html
		$encDatabase = $this->addIdentifierQuotes( strtoupper( $database ) );
		$sql = "ALTER SESSION SET CURRENT_SCHEMA=$encDatabase";
		$stmt = oci_parse( $this->conn, $sql );
		Wikimedia\suppressWarnings();
		$success = oci_execute( $stmt );
		Wikimedia\restoreWarnings();
		if ( $success ) {
			// Update that domain fields on success (no exception thrown)
			$this->currentDomain = $domain;
		} else {
			$e = oci_error( $stmt );
			$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );
		}

		return true;
	}

	function strencode( $s ) {
		return str_replace( "'", "''", $s );
	}

	function addQuotes( $s ) {
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		if ( isset( $contLang->mLoaded ) && $contLang->mLoaded ) {
			$s = $contLang->checkTitleEncoding( $s );
		}

		return "'" . $this->strencode( $s ) . "'";
	}

	public function addIdentifierQuotes( $s ) {
		if ( !$this->getFlag( DBO_DDLMODE ) ) {
			$s = '/*Q*/' . $s;
		}

		return $s;
	}

	public function removeIdentifierQuotes( $s ) {
		return strpos( $s, '/*Q*/' ) === false ? $s : substr( $s, 5 );
	}

	public function isQuotedIdentifier( $s ) {
		return strpos( $s, '/*Q*/' ) !== false;
	}

	private function wrapFieldForWhere( $table, &$col, &$val ) {
		$col_info = $this->fieldInfoMulti( $table, $col );
		$col_type = $col_info != false ? $col_info->type() : 'CONSTANT';
		if ( $col_type == 'CLOB' ) {
			$col = 'TO_CHAR(' . $col . ')';
			$val =
				MediaWikiServices::getInstance()->getContentLanguage()->checkTitleEncoding( $val );
		} elseif ( $col_type == 'VARCHAR2' ) {
			$val =
				MediaWikiServices::getInstance()->getContentLanguage()->checkTitleEncoding( $val );
		}
	}

	private function wrapConditionsForWhere( $table, $conds, $parentCol = null ) {
		$conds2 = [];
		foreach ( $conds as $col => $val ) {
			if ( is_array( $val ) ) {
				$conds2[$col] = $this->wrapConditionsForWhere( $table, $val, $col );
			} else {
				if ( is_numeric( $col ) && $parentCol != null ) {
					$this->wrapFieldForWhere( $table, $parentCol, $val );
				} else {
					$this->wrapFieldForWhere( $table, $col, $val );
				}
				$conds2[$col] = $val;
			}
		}

		return $conds2;
	}

	function selectRow( $table, $vars, $conds, $fname = __METHOD__,
		$options = [], $join_conds = []
	) {
		if ( is_array( $conds ) ) {
			$conds = $this->wrapConditionsForWhere( $table, $conds );
		}

		return parent::selectRow( $table, $vars, $conds, $fname, $options, $join_conds );
	}

	/**
	 * Returns an optional USE INDEX clause to go after the table, and a
	 * string to go at the end of the query
	 *
	 * @param array $options An associative array of options to be turned into
	 *   an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions( $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = '';

		$noKeyOptions = [];
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		$preLimitTail .= $this->makeGroupByWithHaving( $options );

		$preLimitTail .= $this->makeOrderBy( $options );

		if ( isset( $noKeyOptions['FOR UPDATE'] ) ) {
			$postLimitTail .= ' FOR UPDATE';
		}

		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) {
			$startOpts .= 'DISTINCT';
		}

		if ( isset( $options['USE INDEX'] ) && !is_array( $options['USE INDEX'] ) ) {
			$useIndex = $this->useIndexClause( $options['USE INDEX'] );
		} else {
			$useIndex = '';
		}

		if ( isset( $options['IGNORE INDEX'] ) && !is_array( $options['IGNORE INDEX'] ) ) {
			$ignoreIndex = $this->ignoreIndexClause( $options['IGNORE INDEX'] );
		} else {
			$ignoreIndex = '';
		}

		return [ $startOpts, $useIndex, $preLimitTail, $postLimitTail, $ignoreIndex ];
	}

	public function delete( $table, $conds, $fname = __METHOD__ ) {
		global $wgActorTableSchemaMigrationStage;

		if ( is_array( $conds ) ) {
			$conds = $this->wrapConditionsForWhere( $table, $conds );
		}
		// a hack for deleting pages, users and images (which have non-nullable FKs)
		// all deletions on these tables have transactions so final failure rollbacks these updates
		// @todo: Normalize the schema to match MySQL, no special FKs and such
		$table = $this->tableName( $table );
		if ( $table == $this->tableName( 'user' ) &&
			( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD )
		) {
			$this->update( 'archive', [ 'ar_user' => 0 ],
				[ 'ar_user' => $conds['user_id'] ], $fname );
			$this->update( 'ipblocks', [ 'ipb_user' => 0 ],
				[ 'ipb_user' => $conds['user_id'] ], $fname );
			$this->update( 'image', [ 'img_user' => 0 ],
				[ 'img_user' => $conds['user_id'] ], $fname );
			$this->update( 'oldimage', [ 'oi_user' => 0 ],
				[ 'oi_user' => $conds['user_id'] ], $fname );
			$this->update( 'filearchive', [ 'fa_deleted_user' => 0 ],
				[ 'fa_deleted_user' => $conds['user_id'] ], $fname );
			$this->update( 'filearchive', [ 'fa_user' => 0 ],
				[ 'fa_user' => $conds['user_id'] ], $fname );
			$this->update( 'uploadstash', [ 'us_user' => 0 ],
				[ 'us_user' => $conds['user_id'] ], $fname );
			$this->update( 'recentchanges', [ 'rc_user' => 0 ],
				[ 'rc_user' => $conds['user_id'] ], $fname );
			$this->update( 'logging', [ 'log_user' => 0 ],
				[ 'log_user' => $conds['user_id'] ], $fname );
		} elseif ( $table == $this->tableName( 'image' ) ) {
			$this->update( 'oldimage', [ 'oi_name' => 0 ],
				[ 'oi_name' => $conds['img_name'] ], $fname );
		}

		return parent::delete( $table, $conds, $fname );
	}

	/**
	 * @param string $table
	 * @param array $values
	 * @param array $conds
	 * @param string $fname
	 * @param array $options
	 * @return bool
	 * @throws DBUnexpectedError
	 */
	function update( $table, $values, $conds, $fname = __METHOD__, $options = [] ) {
		$table = $this->tableName( $table );
		$opts = $this->makeUpdateOptions( $options );
		$sql = "UPDATE $opts $table SET ";

		$first = true;
		foreach ( $values as $col => &$val ) {
			$sqlSet = $this->fieldBindStatement( $table, $col, $val, true );

			if ( !$first ) {
				$sqlSet = ', ' . $sqlSet;
			} else {
				$first = false;
			}
			$sql .= $sqlSet;
		}

		if ( $conds !== [] && $conds !== '*' ) {
			$conds = $this->wrapConditionsForWhere( $table, $conds );
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		$this->mLastResult = $stmt = oci_parse( $this->conn, $sql );
		if ( $stmt === false ) {
			$e = oci_error( $this->conn );
			$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

			return false;
		}
		foreach ( $values as $col => &$val ) {
			$col_info = $this->fieldInfoMulti( $table, $col );
			$col_type = $col_info != false ? $col_info->type() : 'CONSTANT';

			if ( $val === null ) {
				// do nothing ... null was inserted in statement creation
			} elseif ( $col_type != 'BLOB' && $col_type != 'CLOB' ) {
				if ( is_object( $val ) ) {
					$val = $val->getData();
				}

				if ( preg_match( '/^timestamp.*/i', $col_type ) == 1 && strtolower( $val ) == 'infinity' ) {
					$val = '31-12-2030 12:00:00.000000';
				}

				$val = MediaWikiServices::getInstance()->getContentLanguage()->
					checkTitleEncoding( $val );
				if ( oci_bind_by_name( $stmt, ":$col", $val ) === false ) {
					$e = oci_error( $stmt );
					$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

					return false;
				}
			} else {
				/** @var OCI_Lob[] $lob */
				$lob[$col] = oci_new_descriptor( $this->conn, OCI_D_LOB );
				if ( $lob[$col] === false ) {
					$e = oci_error( $stmt );
					throw new DBUnexpectedError( $this, "Cannot create LOB descriptor: " . $e['message'] );
				}

				if ( is_object( $val ) ) {
					$val = $val->getData();
				}

				if ( $col_type == 'BLOB' ) {
					$lob[$col]->writeTemporary( $val );
					oci_bind_by_name( $stmt, ":$col", $lob[$col], -1, SQLT_BLOB );
				} else {
					$lob[$col]->writeTemporary( $val );
					oci_bind_by_name( $stmt, ":$col", $lob[$col], -1, OCI_B_CLOB );
				}
			}
		}

		Wikimedia\suppressWarnings();

		if ( oci_execute( $stmt, $this->execFlags() ) === false ) {
			$e = oci_error( $stmt );
			if ( !$this->ignoreDupValOnIndex || $e['code'] != '1' ) {
				$this->reportQueryError( $e['message'], $e['code'], $sql, __METHOD__ );

				return false;
			} else {
				$this->mAffectedRows = oci_num_rows( $stmt );
			}
		} else {
			$this->mAffectedRows = oci_num_rows( $stmt );
		}

		Wikimedia\restoreWarnings();

		if ( isset( $lob ) ) {
			foreach ( $lob as $lob_v ) {
				$lob_v->free();
			}
		}

		if ( !$this->trxLevel ) {
			oci_commit( $this->conn );
		}

		return oci_free_statement( $stmt );
	}

	function bitNot( $field ) {
		// expecting bit-fields smaller than 4bytes
		return 'BITNOT(' . $field . ')';
	}

	function bitAnd( $fieldLeft, $fieldRight ) {
		return 'BITAND(' . $fieldLeft . ', ' . $fieldRight . ')';
	}

	function bitOr( $fieldLeft, $fieldRight ) {
		return 'BITOR(' . $fieldLeft . ', ' . $fieldRight . ')';
	}

	function getServer() {
		return $this->server;
	}

	public function buildGroupConcatField(
		$delim, $table, $field, $conds = '', $join_conds = []
	) {
		$fld = "LISTAGG($field," . $this->addQuotes( $delim ) . ") WITHIN GROUP (ORDER BY $field)";

		return '(' . $this->selectSQLText( $table, $fld, $conds, null, [], $join_conds ) . ')';
	}

	public function buildSubstring( $input, $startPosition, $length = null ) {
		$this->assertBuildSubstringParams( $startPosition, $length );
		$params = [ $input, $startPosition ];
		if ( $length !== null ) {
			$params[] = $length;
		}
		return 'SUBSTR(' . implode( ',', $params ) . ')';
	}

	/**
	 * @param string $field Field or column to cast
	 * @return string
	 * @since 1.28
	 */
	public function buildStringCast( $field ) {
		return 'CAST ( ' . $field . ' AS VARCHAR2 )';
	}

	public function getInfinity() {
		return '31-12-2030 12:00:00.000000';
	}
}
