<?php

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
		return $this->getTextBox( 'wgSQLiteDataDir', 'config-sqlite-dir' ) .
			$this->parent->getHelpBox( 'config-sqlite-dir-help' ) .
			$this->getTextBox( 'wgDBname', 'config-db-name' ) .
			$this->parent->getHelpBox( 'config-sqlite-name-help' );
	}

	public function submitConnectForm() {
		$this->setVarsFromRequest( array( 'wgSQLiteDataDir', 'wgDBname' ) );

		$dir = realpath( $this->getVar( 'wgSQLiteDataDir' ) );
		if ( !$dir ) {
			// realpath() sometimes fails, especially on Windows
			$dir = $this->getVar( 'wgSQLiteDataDir' );
		}
		$this->setVar( 'wgSQLiteDataDir', $dir );
		return self::dataDirOKmaybeCreate( $dir, true /* create? */ );
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

	public function getConnection() {
		global $wgSQLiteDataDir;

		$status = Status::newGood();
		$dir = $this->getVar( 'wgSQLiteDataDir' );
		$dbName = $this->getVar( 'wgDBname' );

		try {
			# FIXME: need more sensible constructor parameters, e.g. single associative array
			# Setting globals kind of sucks
			$wgSQLiteDataDir = $dir;
			$this->db = new DatabaseSqlite( false, false, false, $dbName );
			$status->value = $this->db;
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

	public function getSettingsForm() {
		return false;
	}

	public function submitSettingsForm() {
		return Status::newGood();
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
		return $this->getConnection();
	}

	public function createTables() {
		global $IP;
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		// Process common MySQL/SQLite table definitions
		$err = $this->db->sourceFile( "$IP/maintenance/tables.sql" );
		if ( $err !== true ) {
			//@todo or...?
			$this->db->reportQueryError( $err, 0, $sql, __FUNCTION__ );
		}
		return $this->setupSearchIndex();
	}

	public function setupSearchIndex() {
		global $IP;

		$status = Status::newGood();

		$module = $this->db->getFulltextSearchModule();
		$fts3tTable = $this->db->checkForEnabledSearch();
		if ( $fts3tTable &&  !$module ) {
			$status->warning( 'config-sqlite-fts3-downgrade' );
			$this->db->sourceFile( "$IP/maintenance/sqlite/archives/searchindex-no-fts.sql" );
		} elseif ( !$fts3tTable && $module == 'FTS3' ) {
			$status->warning( 'config-sqlite-fts3-add' );
			$this->db->sourceFile( "$IP/maintenance/sqlite/archives/searchindex-fts3.sql" );
		} else {
			$status->warning( 'config-sqlite-fts3-ok' );
		}

		return $status;
	}

	public function doUpgrade() {
		global $wgDatabase;
		LBFactory::enableBackend();
		$wgDatabase = wfGetDB( DB_MASTER );
		ob_start( array( 'SqliteInstaller', 'outputHandler' ) );
		do_all_updates( false, true );
		ob_end_flush();
		return true;
	}

	public static function outputHandler( $string ) {
		return htmlspecialchars( $string );
	}

	public function getLocalSettings() {
		$dir = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgSQLiteDataDir' ) );
		return
"# SQLite-specific settings
\$wgSQLiteDataDir    = \"{$dir}\";";
	}
	
}