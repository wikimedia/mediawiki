<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * Create the SQLite database files
 *
 * @internal For use by the installer
 */
class SqliteCreateDatabaseTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'database';
	}

	/** @inheritDoc */
	public function getAliases() {
		return 'schema';
	}

	public function execute(): Status {
		$dir = $this->getConfigVar( MainConfigNames::SQLiteDataDir );

		# Double check (Only available in web installation). We checked this before but maybe someone
		# deleted the data dir between then and now
		$dir_status = $this->getSqliteUtils()->checkDataDir( $dir );
		if ( $dir_status->isGood() ) {
			$res = $this->createDataDir( $dir );
			if ( !$res->isGood() ) {
				return $res;
			}
		} else {
			return $dir_status;
		}

		$db = $this->getConfigVar( MainConfigNames::DBname );

		# Make the main and cache stub DB files
		$creator = $this->getDatabaseCreator();
		$status = Status::newGood();
		$status->merge( $creator->createLocally( $db ) );
		$status->merge( $creator->createLocally( "wikicache" ) );
		$status->merge( $creator->createLocally( "{$db}_l10n_cache" ) );
		$status->merge( $creator->createLocally( "{$db}_jobqueue" ) );
		if ( !$status->isOK() ) {
			return $status;
		}

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
		$mainConnStatus = $this->getConnection( ITaskContext::CONN_CREATE_TABLES );
		// Use WAL mode. This has better performance
		// when the DB is being read and written concurrently.
		// This causes the DB to be created in this mode
		// so we only have to do this on creation.
		$mainConnStatus->getDB()->query( "PRAGMA journal_mode=WAL", __METHOD__ );
		return $mainConnStatus;
	}

	private function getSqliteUtils(): SqliteUtils {
		return new SqliteUtils;
	}

	/**
	 * @param string $dir Path to the data directory
	 * @return Status Return good Status if without error
	 */
	private function createDataDir( $dir ): Status {
		if ( !is_dir( $dir ) ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$ok = @mkdir( $dir, 0700, true );
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

}
