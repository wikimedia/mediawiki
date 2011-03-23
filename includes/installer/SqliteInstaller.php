<?php
/**
 * Sqlite-specific installer.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using SQLLite.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class SqliteInstaller extends DatabaseInstaller {

	protected $globalNames = array(
		'wgDBname',
		'wgSQLiteDataDir',
	);

	public function getName() {
		return 'sqlite';
	}

	public function isCompiled() {
		return self::checkExtension( 'pdo_sqlite' );
	}

	public function getGlobalDefaults() {
		if ( isset( $_SERVER['DOCUMENT_ROOT'] ) ) {
			$path = str_replace(
				array( '/', '\\' ),
				DIRECTORY_SEPARATOR,
				dirname( $_SERVER['DOCUMENT_ROOT'] ) . '/data'
			);
			return array( 'wgSQLiteDataDir' => $path );
		} else {
			return array();
		}
	}

	public function getConnectForm() {
		return $this->getTextBox( 'wgSQLiteDataDir', 'config-sqlite-dir', array(), $this->parent->getHelpBox( 'config-sqlite-dir-help' ) ) .
			$this->getTextBox( 'wgDBname', 'config-db-name', array(), $this->parent->getHelpBox( 'config-sqlite-name-help' ) );
	}

	/*
	 * Safe wrapper for PHP's realpath() that fails gracefully if it's unable to canonicalize the path.
	 */
	private static function realpath( $path ) {
		$result = realpath( $path );
		if ( !$result ) {
			return $path;
		}
		return $result;
	}

	public function submitConnectForm() {
		$this->setVarsFromRequest( array( 'wgSQLiteDataDir', 'wgDBname' ) );

		# Try realpath() if the directory already exists
		$dir = self::realpath( $this->getVar( 'wgSQLiteDataDir' ) );
		$result = self::dataDirOKmaybeCreate( $dir, true /* create? */ );
		if ( $result->isOK() )
		{
			# Try expanding again in case we've just created it
			$dir = self::realpath( $dir );
			$this->setVar( 'wgSQLiteDataDir', $dir );
		}
		return $result;
	}

	private static function dataDirOKmaybeCreate( $dir, $create = false ) {
		if ( !is_dir( $dir ) ) {
			if ( !is_writable( dirname( $dir ) ) ) {
				$webserverGroup = Installer::maybeGetWebserverPrimaryGroup();
				if ( $webserverGroup !== null ) {
					return Status::newFatal( 'config-sqlite-parent-unwritable-group', $dir, dirname( $dir ), basename( $dir ), $webserverGroup );
				} else {
					return Status::newFatal( 'config-sqlite-parent-unwritable-nogroup', $dir, dirname( $dir ), basename( $dir ) );
				}
			}

			# Called early on in the installer, later we just want to sanity check
			# if it's still writable
			if ( $create ) {
				wfSuppressWarnings();
				$ok = wfMkdirParents( $dir, 0700 );
				wfRestoreWarnings();
				if ( !$ok ) {
					return Status::newFatal( 'config-sqlite-mkdir-error', $dir );
				}
				# Put a .htaccess file in in case the user didn't take our advice
				file_put_contents( "$dir/.htaccess", "Deny from all\n" );
			}
		}
		if ( !is_writable( $dir ) ) {
			return Status::newFatal( 'config-sqlite-dir-unwritable', $dir );
		}

		# We haven't blown up yet, fall through
		return Status::newGood();
	}

	public function openConnection() {
		global $wgSQLiteDataDir;

		$status = Status::newGood();
		$dir = $this->getVar( 'wgSQLiteDataDir' );
		$dbName = $this->getVar( 'wgDBname' );
		try {
			# FIXME: need more sensible constructor parameters, e.g. single associative array
			# Setting globals kind of sucks
			$wgSQLiteDataDir = $dir;
			$db = new DatabaseSqlite( false, false, false, $dbName );
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-sqlite-connection-error', $e->getMessage() );
		}
		return $status;
	}

	public function needsUpgrade() {
		$dir = $this->getVar( 'wgSQLiteDataDir' );
		$dbName = $this->getVar( 'wgDBname' );
		// Don't create the data file yet
		if ( !file_exists( DatabaseSqlite::generateFileName( $dir, $dbName ) ) ) {
			return false;
		}

		// If the data file exists, look inside it
		return parent::needsUpgrade();
	}

	public function setupDatabase() {
		$dir = $this->getVar( 'wgSQLiteDataDir' );

		# Sanity check. We checked this before but maybe someone deleted the
		# data dir between then and now
		$dir_status = self::dataDirOKmaybeCreate( $dir, false /* create? */ );
		if ( !$dir_status->isOK() ) {
			return $dir_status;
		}

		$db = $this->getVar( 'wgDBname' );
		$file = DatabaseSqlite::generateFileName( $dir, $db );
		if ( file_exists( $file ) ) {
			if ( !is_writable( $file ) ) {
				return Status::newFatal( 'config-sqlite-readonly', $file );
			}
		} else {
			if ( file_put_contents( $file, '' ) === false ) {
				return Status::newFatal( 'config-sqlite-cant-create-db', $file );
			}
		}
		// nuke the unused settings for clarity
		$this->setVar( 'wgDBserver', '' );
		$this->setVar( 'wgDBuser', '' );
		$this->setVar( 'wgDBpassword', '' );
		$this->setupSchemaVars();
		return $this->getConnection();
	}

	public function createTables() {
		$status = parent::createTables();
		return $this->setupSearchIndex( $status );
	}

	public function setupSearchIndex( &$status ) {
		global $IP;

		$module = $this->db->getFulltextSearchModule();
		$fts3tTable = $this->db->checkForEnabledSearch();
		if ( $fts3tTable &&  !$module ) {
			$status->warning( 'config-sqlite-fts3-downgrade' );
			$this->db->sourceFile( "$IP/maintenance/sqlite/archives/searchindex-no-fts.sql" );
		} elseif ( !$fts3tTable && $module == 'FTS3' ) {
			$this->db->sourceFile( "$IP/maintenance/sqlite/archives/searchindex-fts3.sql" );
		}
		return $status;
	}

	public function getLocalSettings() {
		$dir = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgSQLiteDataDir' ) );
		return
"# SQLite-specific settings
\$wgSQLiteDataDir    = \"{$dir}\";";
	}
}
