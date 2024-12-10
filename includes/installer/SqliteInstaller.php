<?php

/**
 * Sqlite-specific installer.
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
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * Class for setting up the MediaWiki database using SQLLite.
 *
 * @ingroup Installer
 * @since 1.17
 */
class SqliteInstaller extends DatabaseInstaller {

	/** @inheritDoc */
	public static $minimumVersion = '3.8.0';
	/** @inheritDoc */
	protected static $notMinimumVersionMessage = 'config-outdated-sqlite';

	/**
	 * @var DatabaseSqlite
	 */
	public $db;

	/** @inheritDoc */
	protected $globalNames = [
		'wgDBname',
		'wgSQLiteDataDir',
	];

	public function getName() {
		return 'sqlite';
	}

	public function isCompiled() {
		return self::checkExtension( 'pdo_sqlite' );
	}

	public function getConnectForm( WebInstaller $webInstaller ): DatabaseConnectForm {
		return new SqliteConnectForm( $webInstaller, $this );
	}

	public function getSettingsForm( WebInstaller $webInstaller ): DatabaseSettingsForm {
		return new DatabaseSettingsForm( $webInstaller, $this );
	}

	/**
	 * @return Status
	 */
	public function checkPrerequisites() {
		// Bail out if SQLite is too old
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		$result = static::meetsMinimumRequirement( $db );
		// Check for FTS3 full-text search module
		if ( DatabaseSqlite::getFulltextSearchModule() != 'FTS3' ) {
			$result->warning( 'config-no-fts3' );
		}

		return $result;
	}

	public function getGlobalDefaults() {
		global $IP;
		$defaults = parent::getGlobalDefaults();
		if ( !empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
			$path = dirname( $_SERVER['DOCUMENT_ROOT'] );
		} else {
			// We use $IP when unable to get $_SERVER['DOCUMENT_ROOT']
			$path = $IP;
		}
		$defaults['wgSQLiteDataDir'] = str_replace(
			[ '/', '\\' ],
			DIRECTORY_SEPARATOR,
			$path . '/data'
		);
		return $defaults;
	}

	/**
	 * Safe wrapper for PHP's realpath() that fails gracefully if it's unable to canonicalize the path.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function realpath( $path ) {
		return realpath( $path ) ?: $path;
	}

	/**
	 * Check if the data directory is writable or can be created
	 * @param string $dir Path to the data directory
	 * @return Status Return fatal Status if $dir un-writable or no permission to create a directory
	 */
	public static function checkDataDir( $dir ): Status {
		if ( is_dir( $dir ) ) {
			if ( !is_readable( $dir ) ) {
				return Status::newFatal( 'config-sqlite-dir-unwritable', $dir );
			}
		} elseif ( !is_writable( dirname( $dir ) ) ) {
			// Check the parent directory if $dir not exists
			$webserverGroup = Installer::maybeGetWebserverPrimaryGroup();
			if ( $webserverGroup !== null ) {
				return Status::newFatal(
					'config-sqlite-parent-unwritable-group',
					$dir, dirname( $dir ), basename( $dir ),
					$webserverGroup
				);
			}

			return Status::newFatal(
				'config-sqlite-parent-unwritable-nogroup',
				$dir, dirname( $dir ), basename( $dir )
			);
		}
		return Status::newGood();
	}

	/**
	 * @param string $dir Path to the data directory
	 * @return Status Return good Status if without error
	 */
	private static function createDataDir( $dir ): Status {
		if ( !is_dir( $dir ) ) {
			AtEase::suppressWarnings();
			$ok = wfMkdirParents( $dir, 0700, __METHOD__ );
			AtEase::restoreWarnings();
			if ( !$ok ) {
				return Status::newFatal( 'config-sqlite-mkdir-error', $dir );
			}
		}
		# Put a .htaccess file in case the user didn't take our advice
		file_put_contents( "$dir/.htaccess",
			"Require all denied\n" .
			"Satisfy All\n" );
		return Status::newGood();
	}

	/**
	 * @param string $type
	 * @return ConnectionStatus
	 */
	public function openConnection( string $type ) {
		$status = new ConnectionStatus;
		$dir = $this->getVar( 'wgSQLiteDataDir' );
		$dbName = $this->getVar( 'wgDBname' );

		// Don't implicitly create the file
		$file = DatabaseSqlite::generateFileName( $dir, $dbName );
		if ( !file_exists( $file ) ) {
			$status->fatal( 'config-sqlite-connection-error',
				'file does not exist' );
			return $status;
		}

		try {
			$db = MediaWikiServices::getInstance()->getDatabaseFactory()->create(
				'sqlite', [ 'dbname' => $dbName, 'dbDirectory' => $dir ]
			);
			$status->setDB( $db );
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-sqlite-connection-error', $e->getMessage() );
		}

		return $status;
	}

	/**
	 * @return Status
	 */
	public function setupDatabase() {
		$dir = $this->getVar( 'wgSQLiteDataDir' );

		# Double check (Only available in web installation). We checked this before but maybe someone
		# deleted the data dir between then and now
		$dir_status = self::checkDataDir( $dir );
		if ( $dir_status->isGood() ) {
			$res = self::createDataDir( $dir );
			if ( !$res->isGood() ) {
				return $res;
			}
		} else {
			return $dir_status;
		}

		$db = $this->getVar( 'wgDBname' );

		# Make the main and cache stub DB files
		$status = Status::newGood();
		$status->merge( $this->makeStubDBFile( $dir, $db ) );
		$status->merge( $this->makeStubDBFile( $dir, "wikicache" ) );
		$status->merge( $this->makeStubDBFile( $dir, "{$db}_l10n_cache" ) );
		$status->merge( $this->makeStubDBFile( $dir, "{$db}_jobqueue" ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		# Nuke the unused settings for clarity
		$this->setVar( 'wgDBserver', '' );
		$this->setVar( 'wgDBuser', '' );
		$this->setVar( 'wgDBpassword', '' );

		# Create the l10n cache DB
		try {
			$conn = ( new DatabaseFactory() )->create(
				'sqlite', [ 'dbname' => "{$db}_l10n_cache", 'dbDirectory' => $dir ] );
			# @todo: don't duplicate l10n_cache definition, though it's very simple
			$sql =
<<<EOT
	CREATE TABLE l10n_cache (
		lc_lang BLOB NOT NULL,
		lc_key TEXT NOT NULL,
		lc_value BLOB NOT NULL,
		PRIMARY KEY (lc_lang, lc_key)
	);
EOT;
			$conn->query( $sql, __METHOD__ );
			$conn->query( "PRAGMA journal_mode=WAL", __METHOD__ ); // this is permanent
			$conn->close( __METHOD__ );
		} catch ( DBConnectionError $e ) {
			return Status::newFatal( 'config-sqlite-connection-error', $e->getMessage() );
		}

		# Create the job queue DB
		try {
			$conn = ( new DatabaseFactory() )->create(
				'sqlite', [ 'dbname' => "{$db}_jobqueue", 'dbDirectory' => $dir ] );
			# @todo: don't duplicate job definition, though it's very static
			$sql =
<<<EOT
	CREATE TABLE job (
		job_id INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
		job_cmd BLOB NOT NULL default '',
		job_namespace INTEGER NOT NULL,
		job_title TEXT  NOT NULL,
		job_timestamp BLOB NULL default NULL,
		job_params BLOB NOT NULL,
		job_random integer  NOT NULL default 0,
		job_attempts integer  NOT NULL default 0,
		job_token BLOB NOT NULL default '',
		job_token_timestamp BLOB NULL default NULL,
		job_sha1 BLOB NOT NULL default ''
	);
	CREATE INDEX job_sha1 ON job (job_sha1);
	CREATE INDEX job_cmd_token ON job (job_cmd,job_token,job_random);
	CREATE INDEX job_cmd_token_id ON job (job_cmd,job_token,job_id);
	CREATE INDEX job_cmd ON job (job_cmd, job_namespace, job_title, job_params);
	CREATE INDEX job_timestamp ON job (job_timestamp);
EOT;
			$conn->query( $sql, __METHOD__ );
			$conn->query( "PRAGMA journal_mode=WAL", __METHOD__ ); // this is permanent
			$conn->close( __METHOD__ );
		} catch ( DBConnectionError $e ) {
			return Status::newFatal( 'config-sqlite-connection-error', $e->getMessage() );
		}

		# Open the main DB
		$mainConnStatus = $this->getConnection( self::CONN_CREATE_TABLES );
		// Use WAL mode. This has better performance
		// when the DB is being read and written concurrently.
		// This causes the DB to be created in this mode
		// so we only have to do this on creation.
		$mainConnStatus->getDB()->query( "PRAGMA journal_mode=WAL", __METHOD__ );
		return $mainConnStatus;
	}

	/**
	 * @param string $dir
	 * @param string $db
	 * @return Status
	 */
	protected function makeStubDBFile( $dir, $db ) {
		$file = DatabaseSqlite::generateFileName( $dir, $db );

		if ( file_exists( $file ) ) {
			if ( !is_writable( $file ) ) {
				return Status::newFatal( 'config-sqlite-readonly', $file );
			}
			return Status::newGood();
		}

		$oldMask = umask( 0177 );
		if ( file_put_contents( $file, '' ) === false ) {
			umask( $oldMask );
			return Status::newFatal( 'config-sqlite-cant-create-db', $file );
		}
		umask( $oldMask );

		return Status::newGood();
	}

	/**
	 * @return Status
	 */
	public function createTables() {
		$status = parent::createTables();
		return $this->setupSearchIndex( $status );
	}

	/**
	 * @param Status &$status
	 * @return Status
	 */
	public function setupSearchIndex( &$status ) {
		global $IP;

		$module = DatabaseSqlite::getFulltextSearchModule();
		$searchIndexSql = (string)$this->db->newSelectQueryBuilder()
			->select( 'sql' )
			->from( 'sqlite_master' )
			->where( [ 'tbl_name' => $this->db->tableName( 'searchindex', 'raw' ) ] )
			->caller( __METHOD__ )->fetchField();
		$fts3tTable = ( stristr( $searchIndexSql, 'fts' ) !== false );

		if ( $fts3tTable && !$module ) {
			$status->warning( 'config-sqlite-fts3-downgrade' );
			$this->db->sourceFile( "$IP/maintenance/sqlite/archives/searchindex-no-fts.sql" );
		} elseif ( !$fts3tTable && $module == 'FTS3' ) {
			$this->db->sourceFile( "$IP/maintenance/sqlite/archives/searchindex-fts3.sql" );
		}

		return $status;
	}

	/**
	 * @return string
	 */
	public function getLocalSettings() {
		$dir = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgSQLiteDataDir' ) );
		// These tables have frequent writes and are thus split off from the main one.
		// Since the code using these tables only uses transactions for writes, then set
		// them to using BEGIN IMMEDIATE. This avoids frequent lock errors on the first write action.
		return "# SQLite-specific settings
\$wgSQLiteDataDir = \"{$dir}\";
\$wgObjectCaches[CACHE_DB] = [
	'class' => SqlBagOStuff::class,
	'loggroup' => 'SQLBagOStuff',
	'server' => [
		'type' => 'sqlite',
		'dbname' => 'wikicache',
		'tablePrefix' => '',
		'variables' => [ 'synchronous' => 'NORMAL' ],
		'dbDirectory' => \$wgSQLiteDataDir,
		'trxMode' => 'IMMEDIATE',
		'flags' => 0
	]
];
\$wgLocalisationCacheConf['storeServer'] = [
	'type' => 'sqlite',
	'dbname' => \"{\$wgDBname}_l10n_cache\",
	'tablePrefix' => '',
	'variables' => [ 'synchronous' => 'NORMAL' ],
	'dbDirectory' => \$wgSQLiteDataDir,
	'trxMode' => 'IMMEDIATE',
	'flags' => 0
];
\$wgJobTypeConf['default'] = [
	'class' => 'JobQueueDB',
	'claimTTL' => 3600,
	'server' => [
		'type' => 'sqlite',
		'dbname' => \"{\$wgDBname}_jobqueue\",
		'tablePrefix' => '',
		'variables' => [ 'synchronous' => 'NORMAL' ],
		'dbDirectory' => \$wgSQLiteDataDir,
		'trxMode' => 'IMMEDIATE',
		'flags' => 0
	]
];
\$wgResourceLoaderUseObjectCacheForDeps = true;";
	}
}
