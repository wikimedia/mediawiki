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

use FSLockManager;
use LockManager;
use NullLockManager;
use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\Platform\SqlitePlatform;

/**
 * This is the SQLite database abstraction layer.
 *
 * See docs/sqlite.txt for development notes about MediaWiki's sqlite schema.
 *
 * @ingroup Database
 */
class DatabaseSqlite extends Database {
	/** @var string|null Directory for SQLite database files listed under their DB name */
	protected $dbDir;
	/** @var string|null Explicit path for the SQLite database file */
	protected $dbPath;
	/** @var string Transaction mode */
	protected $trxMode;

	/** @var int The number of rows affected as an integer */
	protected $lastAffectedRowCount;

	/** @var PDO|null */
	protected $conn;

	/** @var LockManager|null (hopefully on the same server as the DB) */
	protected $lockMgr;

	/** @var string|null */
	private $version;

	/** @var array List of shared database already attached to this connection */
	private $sessionAttachedDbs = [];

	/** @var string[] See https://www.sqlite.org/lang_transaction.html */
	private const VALID_TRX_MODES = [ '', 'DEFERRED', 'IMMEDIATE', 'EXCLUSIVE' ];

	/** @var string[][] */
	private const VALID_PRAGMAS = [
		// Optimizations or requirements regarding fsync() usage
		'synchronous' => [ 'EXTRA', 'FULL', 'NORMAL', 'OFF' ],
		// Optimizations for TEMPORARY tables
		'temp_store' => [ 'FILE', 'MEMORY' ],
		// Optimizations for disk use and page cache
		'mmap_size' => 'integer'
	];

	/** @var ISQLPlatform */
	protected $platform;

	/**
	 * Additional params include:
	 *   - dbDirectory : directory containing the DB and the lock file directory
	 *   - dbFilePath  : use this to force the path of the DB file
	 *   - trxMode     : one of (deferred, immediate, exclusive)
	 * @param array $params
	 */
	public function __construct( array $params ) {
		if ( isset( $params['dbFilePath'] ) ) {
			$this->dbPath = $params['dbFilePath'];
			if ( !isset( $params['dbname'] ) || $params['dbname'] === '' ) {
				$params['dbname'] = self::generateDatabaseName( $this->dbPath );
			}
		} elseif ( isset( $params['dbDirectory'] ) ) {
			$this->dbDir = $params['dbDirectory'];
		}

		parent::__construct( $params );

		$this->trxMode = strtoupper( $params['trxMode'] ?? '' );

		$this->lockMgr = $this->makeLockManager();
		$this->platform = new SqlitePlatform(
			$this,
			$params['queryLogger'],
			$this->currentDomain
		);
	}

	protected static function getAttributes() {
		return [
			self::ATTR_DB_IS_FILE => true,
			self::ATTR_DB_LEVEL_LOCKING => true
		];
	}

	/**
	 * @param string $filename
	 * @param array $p Options map; supports:
	 *   - flags       : (same as __construct counterpart)
	 *   - trxMode     : (same as __construct counterpart)
	 *   - dbDirectory : (same as __construct counterpart)
	 * @return DatabaseSqlite
	 * @since 1.25
	 */
	public static function newStandaloneInstance( $filename, array $p = [] ) {
		$p['dbFilePath'] = $filename;
		$p['schema'] = null;
		$p['tablePrefix'] = '';
		/** @var DatabaseSqlite $db */
		$db = Database::factory( 'sqlite', $p );
		'@phan-var DatabaseSqlite $db';

		return $db;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return 'sqlite';
	}

	protected function open( $server, $user, $password, $db, $schema, $tablePrefix ) {
		$this->close( __METHOD__ );

		// Note that for SQLite, $server, $user, and $pass are ignored

		if ( $schema !== null ) {
			throw $this->newExceptionAfterConnectError( "Got schema '$schema'; not supported." );
		}

		if ( $this->dbPath !== null ) {
			$path = $this->dbPath;
		} elseif ( $this->dbDir !== null ) {
			$path = self::generateFileName( $this->dbDir, $db );
		} else {
			throw $this->newExceptionAfterConnectError( "DB path or directory required" );
		}

		// Check if the database file already exists but is non-readable
		if ( !self::isProcessMemoryPath( $path ) && is_file( $path ) && !is_readable( $path ) ) {
			throw $this->newExceptionAfterConnectError( 'SQLite database file is not readable' );
		} elseif ( !in_array( $this->trxMode, self::VALID_TRX_MODES, true ) ) {
			throw $this->newExceptionAfterConnectError( "Got mode '{$this->trxMode}' for BEGIN" );
		}

		$attributes = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
			// Starting with PHP 8.1, The SQLite PDO returns proper types instead
			// of strings or null for everything. We cast every non-null value to
			// string to restore the old behavior.
			PDO::ATTR_STRINGIFY_FETCHES => true
		];
		if ( $this->getFlag( self::DBO_PERSISTENT ) ) {
			// Persistent connections can avoid some schema index reading overhead.
			// On the other hand, they can cause horrible contention with DBO_TRX.
			if ( $this->getFlag( self::DBO_TRX ) || $this->getFlag( self::DBO_DEFAULT ) ) {
				$this->connLogger->warning(
					__METHOD__ . ": ignoring DBO_PERSISTENT due to DBO_TRX or DBO_DEFAULT",
					$this->getLogContext()
				);
			} else {
				$attributes[PDO::ATTR_PERSISTENT] = true;
			}
		}

		try {
			// Open the database file, creating it if it does not yet exist
			$this->conn = new PDO( "sqlite:$path", null, null, $attributes );
		} catch ( PDOException $e ) {
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}

		$this->currentDomain = new DatabaseDomain( $db, null, $tablePrefix );
		$this->platform->setPrefix( $tablePrefix );

		try {
			$flags = self::QUERY_CHANGE_TRX | self::QUERY_NO_RETRY;
			// Enforce LIKE to be case sensitive, just like MySQL
			$this->query( 'PRAGMA case_sensitive_like = 1', __METHOD__, $flags );
			// Set any connection-level custom PRAGMA options
			$pragmas = array_intersect_key( $this->connectionVariables, self::VALID_PRAGMAS );
			$pragmas += $this->getDefaultPragmas();
			foreach ( $pragmas as $name => $value ) {
				$allowed = self::VALID_PRAGMAS[$name];
				if (
					( is_array( $allowed ) && in_array( $value, $allowed, true ) ) ||
					( is_string( $allowed ) && gettype( $value ) === $allowed )
				) {
					$this->query( "PRAGMA $name = $value", __METHOD__, $flags );
				}
			}
			$this->attachDatabasesFromTableAliases();
		} catch ( RuntimeException $e ) {
			throw $this->newExceptionAfterConnectError( $e->getMessage() );
		}
	}

	/**
	 * @return array Map of (name => value) for default values to set via PRAGMA
	 */
	private function getDefaultPragmas() {
		$variables = [];

		if ( !$this->cliMode ) {
			$variables['temp_store'] = 'MEMORY';
		}

		return $variables;
	}

	/**
	 * @return string|null SQLite DB file path
	 * @throws DBUnexpectedError
	 * @since 1.25
	 */
	public function getDbFilePath() {
		return $this->dbPath ?? self::generateFileName( $this->dbDir, $this->getDBname() );
	}

	/**
	 * @return string|null Lock file directory
	 */
	public function getLockFileDirectory() {
		if ( $this->dbPath !== null && !self::isProcessMemoryPath( $this->dbPath ) ) {
			return dirname( $this->dbPath ) . '/locks';
		} elseif ( $this->dbDir !== null && !self::isProcessMemoryPath( $this->dbDir ) ) {
			return $this->dbDir . '/locks';
		}

		return null;
	}

	/**
	 * Initialize/reset the LockManager instance
	 *
	 * @return LockManager
	 */
	private function makeLockManager(): LockManager {
		$lockDirectory = $this->getLockFileDirectory();
		if ( $lockDirectory !== null ) {
			return new FSLockManager( [
				'domain' => $this->getDomainID(),
				'lockDirectory' => $lockDirectory,
			] );
		} else {
			return new NullLockManager( [ 'domain' => $this->getDomainID() ] );
		}
	}

	/**
	 * Does not actually close the connection, just destroys the reference for GC to do its work
	 * @return bool
	 */
	protected function closeConnection() {
		$this->conn = null;
		// Release all locks, via FSLockManager::__destruct, as the base class expects
		$this->lockMgr = null;

		return true;
	}

	/**
	 * Generates a database file name. Explicitly public for installer.
	 * @param string $dir Directory where database resides
	 * @param string|bool $dbName Database name (or false from Database::factory, validated here)
	 * @return string
	 * @throws DBUnexpectedError
	 */
	public static function generateFileName( $dir, $dbName ) {
		if ( $dir == '' ) {
			throw new DBUnexpectedError( null, __CLASS__ . ": no DB directory specified" );
		} elseif ( self::isProcessMemoryPath( $dir ) ) {
			throw new DBUnexpectedError(
				null,
				__CLASS__ . ": cannot use process memory directory '$dir'"
			);
		} elseif ( !strlen( $dbName ) ) {
			throw new DBUnexpectedError( null, __CLASS__ . ": no DB name specified" );
		}

		return "$dir/$dbName.sqlite";
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private static function generateDatabaseName( $path ) {
		if ( preg_match( '/^(:memory:$|file::memory:)/', $path ) ) {
			// E.g. "file::memory:?cache=shared" => ":memory":
			return ':memory:';
		} elseif ( preg_match( '/^file::([^?]+)\?mode=memory(&|$)/', $path, $m ) ) {
			// E.g. "file:memdb1?mode=memory" => ":memdb1:"
			return ":{$m[1]}:";
		} else {
			// E.g. "/home/.../some_db.sqlite3" => "some_db"
			return preg_replace( '/\.sqlite\d?$/', '', basename( $path ) );
		}
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	private static function isProcessMemoryPath( $path ) {
		return preg_match( '/^(:memory:$|file:(:memory:|[^?]+\?mode=memory(&|$)))/', $path );
	}

	/**
	 * Returns version of currently supported SQLite fulltext search module or false if none present.
	 * @return string|false
	 */
	public static function getFulltextSearchModule() {
		static $cachedResult = null;
		if ( $cachedResult !== null ) {
			return $cachedResult;
		}
		$cachedResult = false;
		$table = 'dummy_search_test';

		$db = self::newStandaloneInstance( ':memory:' );
		if ( $db->query(
			"CREATE VIRTUAL TABLE $table USING FTS3(dummy_field)",
			__METHOD__,
			IDatabase::QUERY_SILENCE_ERRORS
		) ) {
			$cachedResult = 'FTS3';
		}
		$db->close( __METHOD__ );

		return $cachedResult;
	}

	/**
	 * Attaches external database to the connection handle
	 *
	 * @see https://sqlite.org/lang_attach.html
	 *
	 * @param string $name Database name to be used in queries like
	 *   SELECT foo FROM dbname.table
	 * @param bool|string $file Database file name. If omitted, will be generated
	 *   using $name and configured data directory
	 * @param string $fname Calling function name
	 * @return IResultWrapper
	 */
	public function attachDatabase( $name, $file = false, $fname = __METHOD__ ) {
		$file = is_string( $file ) ? $file : self::generateFileName( $this->dbDir, $name );
		$encFile = $this->addQuotes( $file );

		return $this->query(
			"ATTACH DATABASE $encFile AS $name",
			$fname,
			self::QUERY_CHANGE_TRX
		);
	}

	protected function doSingleStatementQuery( string $sql ): QueryStatus {
		$conn = $this->getBindingHandle();

		$res = $conn->query( $sql );
		$this->lastAffectedRowCount = $res ? $res->rowCount() : 0;

		return new QueryStatus(
			$res instanceof PDOStatement ? new SqliteResultWrapper( $res ) : $res,
			$res ? $res->rowCount() : 0,
			$this->lastError(),
			$this->lastErrno()
		);
	}

	protected function doSelectDomain( DatabaseDomain $domain ) {
		if ( $domain->getSchema() !== null ) {
			throw new DBExpectedError(
				$this,
				__CLASS__ . ": domain '{$domain->getId()}' has a schema component"
			);
		}

		$database = $domain->getDatabase();
		// A null database means "don't care" so leave it as is and update the table prefix
		if ( $database === null ) {
			$this->currentDomain = new DatabaseDomain(
				$this->currentDomain->getDatabase(),
				null,
				$domain->getTablePrefix()
			);
			$this->platform->setPrefix( $domain->getTablePrefix() );

			return true;
		}

		if ( $database !== $this->getDBname() ) {
			throw new DBExpectedError(
				$this,
				__CLASS__ . ": cannot change database (got '$database')"
			);
		}

		// Update that domain fields on success (no exception thrown)
		$this->currentDomain = $domain;
		$this->platform->setPrefix( $domain->getTablePrefix() );

		return true;
	}

	/**
	 * This must be called after nextSequenceVal
	 *
	 * @return int
	 */
	public function insertId() {
		// PDO::lastInsertId yields a string :(
		return intval( $this->getBindingHandle()->lastInsertId() );
	}

	/**
	 * @return string
	 */
	public function lastError() {
		if ( is_object( $this->conn ) ) {
			$e = $this->conn->errorInfo();

			return $e[2] ?? '';
		}
		return 'No database connection';
	}

	/**
	 * @return int
	 */
	public function lastErrno() {
		if ( is_object( $this->conn ) ) {
			$info = $this->conn->errorInfo();

			if ( isset( $info[1] ) ) {
				return $info[1];
			}
		}
		return 0;
	}

	/**
	 * @return int
	 */
	protected function fetchAffectedRowCount() {
		return $this->lastAffectedRowCount;
	}

	public function tableExists( $table, $fname = __METHOD__ ) {
		$tableRaw = $this->tableName( $table, 'raw' );
		if ( isset( $this->sessionTempTables[$tableRaw] ) ) {
			return true; // already known to exist
		}

		$encTable = $this->addQuotes( $tableRaw );
		$res = $this->query(
			"SELECT 1 FROM sqlite_master WHERE type='table' AND name=$encTable",
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		return $res->numRows() ? true : false;
	}

	/**
	 * Returns information about an index
	 * Returns false if the index does not exist
	 * - if errors are explicitly ignored, returns NULL on failure
	 *
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return array|false
	 */
	public function indexInfo( $table, $index, $fname = __METHOD__ ) {
		$sql = 'PRAGMA index_info(' . $this->addQuotes( $this->indexName( $index ) ) . ')';
		$res = $this->query( $sql, $fname, self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE );
		if ( !$res || $res->numRows() == 0 ) {
			return false;
		}
		$info = [];
		foreach ( $res as $row ) {
			$info[] = $row->name;
		}

		return $info;
	}

	/**
	 * @param string $table
	 * @param string $index
	 * @param string $fname
	 * @return bool|null
	 */
	public function indexUnique( $table, $index, $fname = __METHOD__ ) {
		$row = $this->selectRow( 'sqlite_master', '*',
			[
				'type' => 'index',
				'name' => $this->indexName( $index ),
			], $fname );
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

	protected function doReplace( $table, array $identityKey, array $rows, $fname ) {
		$encTable = $this->tableName( $table );
		list( $sqlColumns, $sqlTuples ) = $this->platform->makeInsertLists( $rows );
		// https://sqlite.org/lang_insert.html
		$this->query(
			"REPLACE INTO $encTable ($sqlColumns) VALUES $sqlTuples",
			$fname,
			self::QUERY_CHANGE_ROWS
		);
	}

	/**
	 * Returns the size of a text field, or -1 for "unlimited"
	 * In SQLite this is SQLITE_MAX_LENGTH, by default 1 GB. No way to query it though.
	 *
	 * @param string $table
	 * @param string $field
	 * @return int
	 */
	public function textFieldSize( $table, $field ) {
		return -1;
	}

	/**
	 * @return bool
	 */
	public function wasDeadlock() {
		return $this->lastErrno() == 5; // SQLITE_BUSY
	}

	/**
	 * @return bool
	 */
	public function wasReadOnlyError() {
		return $this->lastErrno() == 8; // SQLITE_READONLY;
	}

	protected function isConnectionError( $errno ) {
		return $errno == 17; // SQLITE_SCHEMA;
	}

	protected function isKnownStatementRollbackError( $errno ) {
		// ON CONFLICT ROLLBACK clauses make it so that SQLITE_CONSTRAINT error is
		// ambiguous with regard to whether it implies a ROLLBACK or an ABORT happened.
		// https://sqlite.org/lang_createtable.html#uniqueconst
		// https://sqlite.org/lang_conflict.html
		return false;
	}

	public function getTopologyBasedServerId() {
		// Sqlite topologies trivially consist of single primary server for the dataset
		return '0';
	}

	public function serverIsReadOnly() {
		$this->assertHasConnectionHandle();

		$path = $this->getDbFilePath();

		return ( !self::isProcessMemoryPath( $path ) && !is_writable( $path ) );
	}

	/**
	 * @return string Wikitext of a link to the server software's web site
	 */
	public function getSoftwareLink() {
		return "[{{int:version-db-sqlite-url}} SQLite]";
	}

	/**
	 * @return string Version information from the database
	 */
	public function getServerVersion() {
		if ( $this->version === null ) {
			$this->version = $this->getBindingHandle()->getAttribute( PDO::ATTR_SERVER_VERSION );
		}

		return $this->version;
	}

	/**
	 * Get information about a given field
	 * Returns false if the field does not exist.
	 *
	 * @param string $table
	 * @param string $field
	 * @return SQLiteField|false False on failure
	 */
	public function fieldInfo( $table, $field ) {
		$tableName = $this->tableName( $table );
		$res = $this->query(
			'PRAGMA table_info(' . $this->addQuotes( $tableName ) . ')',
			__METHOD__,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		foreach ( $res as $row ) {
			if ( $row->name == $field ) {
				return new SQLiteField( $row, $tableName );
			}
		}

		return false;
	}

	protected function doBegin( $fname = '' ) {
		if ( $this->trxMode != '' ) {
			$this->query( "BEGIN {$this->trxMode}", $fname, self::QUERY_CHANGE_TRX );
		} else {
			$this->query( 'BEGIN', $fname, self::QUERY_CHANGE_TRX );
		}
	}

	/**
	 * @param string $s
	 * @return string
	 */
	public function strencode( $s ) {
		return substr( $this->addQuotes( $s ), 1, -1 );
	}

	/**
	 * @param string $b
	 * @return Blob
	 */
	public function encodeBlob( $b ) {
		return new Blob( $b );
	}

	/**
	 * @param Blob|string $b
	 * @return string
	 */
	public function decodeBlob( $b ) {
		if ( $b instanceof Blob ) {
			$b = $b->fetch();
		}

		return $b;
	}

	/**
	 * @param string|int|float|null|bool|Blob $s
	 * @return string
	 */
	public function addQuotes( $s ) {
		if ( $s instanceof Blob ) {
			return "x'" . bin2hex( $s->fetch() ) . "'";
		} elseif ( is_bool( $s ) ) {
			return (string)(int)$s;
		} elseif ( is_int( $s ) ) {
			return (string)$s;
		} elseif ( strpos( (string)$s, "\0" ) !== false ) {
			// SQLite doesn't support \0 in strings, so use the hex representation as a workaround.
			// This is a known limitation of SQLite's mprintf function which PDO
			// should work around, but doesn't. I have reported this to php.net as bug #63419:
			// https://bugs.php.net/bug.php?id=63419
			// There was already a similar report for SQLite3::escapeString, bug #62361:
			// https://bugs.php.net/bug.php?id=62361
			// There is an additional bug regarding sorting this data after insert
			// on older versions of sqlite shipped with ubuntu 12.04
			// https://phabricator.wikimedia.org/T74367
			$this->queryLogger->debug(
				__FUNCTION__ .
				': Quoting value containing null byte. ' .
				'For consistency all binary data should have been ' .
				'first processed with self::encodeBlob()'
			);
			return "x'" . bin2hex( (string)$s ) . "'";
		} else {
			return $this->getBindingHandle()->quote( (string)$s );
		}
	}

	/**
	 * No-op version of deadlockLoop
	 *
	 * @param mixed ...$args
	 * @return mixed
	 */
	public function deadlockLoop( ...$args ) {
		$function = array_shift( $args );

		return $function( ...$args );
	}

	public function doLockIsFree( string $lockName, string $method ) {
		// Only locks by this thread will be checked
		return true;
	}

	public function doLock( string $lockName, string $method, int $timeout ) {
		$status = $this->lockMgr->lock( [ $lockName ], LockManager::LOCK_EX, $timeout );
		if (
			$this->lockMgr instanceof FSLockManager &&
			$status->hasMessage( 'lockmanager-fail-openlock' )
		) {
			throw new DBError( $this, "Cannot create directory \"{$this->getLockFileDirectory()}\"" );
		}

		return $status->isOK() ? microtime( true ) : null;
	}

	public function doUnlock( string $lockName, string $method ) {
		return $this->lockMgr->unlock( [ $lockName ], LockManager::LOCK_EX )->isGood();
	}

	/**
	 * @param string $oldName
	 * @param string $newName
	 * @param bool $temporary
	 * @param string $fname
	 * @return bool|IResultWrapper
	 * @throws RuntimeException
	 */
	public function duplicateTableStructure(
		$oldName, $newName, $temporary = false, $fname = __METHOD__
	) {
		$res = $this->query(
			"SELECT sql FROM sqlite_master WHERE tbl_name=" .
			$this->addQuotes( $oldName ) . " AND type='table'",
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		$obj = $res->fetchObject();
		if ( !$obj ) {
			throw new RuntimeException( "Couldn't retrieve structure for table $oldName" );
		}
		$sqlCreateTable = $obj->sql;
		$sqlCreateTable = preg_replace(
			'/(?<=\W)"?' .
				preg_quote( trim( $this->platform->addIdentifierQuotes( $oldName ), '"' ), '/' ) .
				'"?(?=\W)/',
			$this->platform->addIdentifierQuotes( $newName ),
			$sqlCreateTable,
			1
		);
		if ( $temporary ) {
			if ( preg_match( '/^\\s*CREATE\\s+VIRTUAL\\s+TABLE\b/i', $sqlCreateTable ) ) {
				$this->queryLogger->debug(
					"Table $oldName is virtual, can't create a temporary duplicate." );
			} else {
				$sqlCreateTable = str_replace(
					'CREATE TABLE',
					'CREATE TEMPORARY TABLE',
					$sqlCreateTable
				);
			}
		}

		// @phan-suppress-next-line SecurityCheck-SQLInjection SQL is taken from database
		$res = $this->query(
			$sqlCreateTable,
			$fname,
			self::QUERY_CHANGE_SCHEMA | self::QUERY_PSEUDO_PERMANENT
		);

		// Take over indexes
		$indexList = $this->query(
			'PRAGMA INDEX_LIST(' . $this->addQuotes( $oldName ) . ')',
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);
		foreach ( $indexList as $index ) {
			if ( strpos( $index->name, 'sqlite_autoindex' ) === 0 ) {
				continue;
			}

			if ( $index->unique ) {
				$sqlIndex = 'CREATE UNIQUE INDEX';
			} else {
				$sqlIndex = 'CREATE INDEX';
			}
			// Try to come up with a new index name, given indexes have database scope in SQLite
			$indexName = $newName . '_' . $index->name;
			$sqlIndex .= ' ' . $this->platform->addIdentifierQuotes( $indexName ) .
				' ON ' . $this->platform->addIdentifierQuotes( $newName );

			$indexInfo = $this->query(
				'PRAGMA INDEX_INFO(' . $this->addQuotes( $index->name ) . ')',
				$fname,
				self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
			);
			$fields = [];
			foreach ( $indexInfo as $indexInfoRow ) {
				$fields[$indexInfoRow->seqno] = $this->addQuotes( $indexInfoRow->name );
			}

			$sqlIndex .= '(' . implode( ',', $fields ) . ')';

			$this->query(
				$sqlIndex,
				__METHOD__,
				self::QUERY_CHANGE_SCHEMA | self::QUERY_PSEUDO_PERMANENT
			);
		}

		return $res;
	}

	/**
	 * List all tables on the database
	 *
	 * @param string|null $prefix Only show tables with this prefix, e.g. mw_
	 * @param string $fname Calling function name
	 *
	 * @return array
	 */
	public function listTables( $prefix = null, $fname = __METHOD__ ) {
		$result = $this->query(
			"SELECT name FROM sqlite_master WHERE type = 'table'",
			$fname,
			self::QUERY_IGNORE_DBO_TRX | self::QUERY_CHANGE_NONE
		);

		$endArray = [];

		foreach ( $result as $table ) {
			$vars = get_object_vars( $table );
			$table = array_pop( $vars );

			if ( !$prefix || strpos( $table, $prefix ) === 0 ) {
				if ( strpos( $table, 'sqlite_' ) !== 0 ) {
					$endArray[] = $table;
				}
			}
		}

		return $endArray;
	}

	protected function doTruncate( array $tables, $fname ) {
		$this->startAtomic( $fname );

		$encSeqNames = [];
		foreach ( $tables as $table ) {
			// Use "truncate" optimization; https://www.sqlite.org/lang_delete.html
			$sql = "DELETE FROM " . $this->tableName( $table );
			$this->query( $sql, $fname, self::QUERY_CHANGE_SCHEMA );

			$encSeqNames[] = $this->addQuotes( $this->tableName( $table, 'raw' ) );
		}

		$encMasterTable = $this->platform->addIdentifierQuotes( 'sqlite_sequence' );
		$this->query(
			"DELETE FROM $encMasterTable WHERE name IN(" . implode( ',', $encSeqNames ) . ")",
			$fname,
			self::QUERY_CHANGE_SCHEMA
		);

		$this->endAtomic( $fname );
	}

	public function setTableAliases( array $aliases ) {
		parent::setTableAliases( $aliases );
		if ( $this->isOpen() ) {
			$this->attachDatabasesFromTableAliases();
		}
	}

	/**
	 * Issue ATTATCH statements for all unattached foreign DBs in table aliases
	 */
	private function attachDatabasesFromTableAliases() {
		foreach ( $this->platform->getTableAliases() as $params ) {
			if (
				$params['dbname'] !== $this->getDBname() &&
				!isset( $this->sessionAttachedDbs[$params['dbname']] )
			) {
				$this->attachDatabase( $params['dbname'], false, __METHOD__ );
				$this->sessionAttachedDbs[$params['dbname']] = true;
			}
		}
	}

	public function databasesAreIndependent() {
		return true;
	}

	protected function doHandleSessionLossPreconnect() {
		$this->sessionAttachedDbs = [];
		// Release all locks, via FSLockManager::__destruct, as the base class expects;
		$this->lockMgr = null;
		// Create a new lock manager instance
		$this->lockMgr = $this->makeLockManager();
	}

	protected function doFlushSession( $fname ) {
		// Release all locks, via FSLockManager::__destruct, as the base class expects
		$this->lockMgr = null;
		// Create a new lock manager instance
		$this->lockMgr = $this->makeLockManager();
	}

	/**
	 * @return PDO
	 */
	protected function getBindingHandle() {
		return parent::getBindingHandle();
	}
}

/**
 * @deprecated since 1.29
 */
class_alias( DatabaseSqlite::class, 'DatabaseSqlite' );
