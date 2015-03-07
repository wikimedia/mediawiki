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
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using SQLLite.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class SqliteInstaller extends DatabaseInstaller {
	const MINIMUM_VERSION = '3.3.7';

	/**
	 * @var DatabaseSqlite
	 */
	public $db;

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

	/**
	 *
	 * @return Status
	 */
	public function checkPrerequisites() {
		$result = Status::newGood();
		// Bail out if SQLite is too old
		$db = DatabaseSqlite::newStandaloneInstance( ':memory:' );
		if ( version_compare( $db->getServerVersion(), self::MINIMUM_VERSION, '<' ) ) {
			$result->fatal( 'config-outdated-sqlite', $db->getServerVersion(), self::MINIMUM_VERSION );
		}
		// Check for FTS3 full-text search module
		if ( DatabaseSqlite::getFulltextSearchModule() != 'FTS3' ) {
			$result->warning( 'config-no-fts3' );
		}

		return $result;
	}

	public function getGlobalDefaults() {
		$defaults = parent::getGlobalDefaults();
		if ( isset( $_SERVER['DOCUMENT_ROOT'] ) ) {
			$path = str_replace(
				array( '/', '\\' ),
				DIRECTORY_SEPARATOR,
				dirname( $_SERVER['DOCUMENT_ROOT'] ) . '/data'
			);

			$defaults['wgSQLiteDataDir'] = $path;
		}
		return $defaults;
	}

	public function getConnectForm() {
		return $this->getTextBox(
			'wgSQLiteDataDir',
			'config-sqlite-dir', array(),
			$this->parent->getHelpBox( 'config-sqlite-dir-help' )
		) .
		$this->getTextBox(
			'wgDBname',
			'config-db-name',
			array(),
			$this->parent->getHelpBox( 'config-sqlite-name-help' )
		);
	}

	/**
	 * Safe wrapper for PHP's realpath() that fails gracefully if it's unable to canonicalize the path.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	private static function realpath( $path ) {
		$result = realpath( $path );
		if ( !$result ) {
			return $path;
		}

		return $result;
	}

	/**
	 * @return Status
	 */
	public function submitConnectForm() {
		$this->setVarsFromRequest( array( 'wgSQLiteDataDir', 'wgDBname' ) );

		# Try realpath() if the directory already exists
		$dir = self::realpath( $this->getVar( 'wgSQLiteDataDir' ) );
		$result = self::dataDirOKmaybeCreate( $dir, true /* create? */ );
		if ( $result->isOK() ) {
			# Try expanding again in case we've just created it
			$dir = self::realpath( $dir );
			$this->setVar( 'wgSQLiteDataDir', $dir );
		}
		# Table prefix is not used on SQLite, keep it empty
		$this->setVar( 'wgDBprefix', '' );

		return $result;
	}

	/**
	 * @param string $dir
	 * @param bool $create
	 * @return Status
	 */
	private static function dataDirOKmaybeCreate( $dir, $create = false ) {
		if ( !is_dir( $dir ) ) {
			if ( !is_writable( dirname( $dir ) ) ) {
				$webserverGroup = Installer::maybeGetWebserverPrimaryGroup();
				if ( $webserverGroup !== null ) {
					return Status::newFatal(
						'config-sqlite-parent-unwritable-group',
						$dir, dirname( $dir ), basename( $dir ),
						$webserverGroup
					);
				} else {
					return Status::newFatal(
						'config-sqlite-parent-unwritable-nogroup',
						$dir, dirname( $dir ), basename( $dir )
					);
				}
			}

			# Called early on in the installer, later we just want to sanity check
			# if it's still writable
			if ( $create ) {
				wfSuppressWarnings();
				$ok = wfMkdirParents( $dir, 0700, __METHOD__ );
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

	/**
	 * @return Status
	 */
	public function openConnection() {
		global $wgSQLiteDataDir;

		$status = Status::newGood();
		$dir = $this->getVar( 'wgSQLiteDataDir' );
		$dbName = $this->getVar( 'wgDBname' );
		try {
			# @todo FIXME: Need more sensible constructor parameters, e.g. single associative array
			# Setting globals kind of sucks
			$wgSQLiteDataDir = $dir;
			$db = DatabaseBase::factory( 'sqlite', array( 'dbname' => $dbName ) );
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-sqlite-connection-error', $e->getMessage() );
		}

		return $status;
	}

	/**
	 * @return bool
	 */
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

	/**
	 * @return Status
	 */
	public function setupDatabase() {
		$dir = $this->getVar( 'wgSQLiteDataDir' );

		# Sanity check. We checked this before but maybe someone deleted the
		# data dir between then and now
		$dir_status = self::dataDirOKmaybeCreate( $dir, false /* create? */ );
		if ( !$dir_status->isOK() ) {
			return $dir_status;
		}

		$db = $this->getVar( 'wgDBname' );

		# Make the main and cache stub DB files
		$status = Status::newGood();
		$status->merge( $this->makeStubDBFile( $dir, $db ) );
		$status->merge( $this->makeStubDBFile( $dir, "wikicache" ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		# Nuke the unused settings for clarity
		$this->setVar( 'wgDBserver', '' );
		$this->setVar( 'wgDBuser', '' );
		$this->setVar( 'wgDBpassword', '' );
		$this->setupSchemaVars();

		# Create the global cache DB
		try {
			global $wgSQLiteDataDir;
			# @todo FIXME: setting globals kind of sucks
			$wgSQLiteDataDir = $dir;
			$conn = DatabaseBase::factory( 'sqlite', array( 'dbname' => "wikicache" ) );
			# @todo: don't duplicate objectcache definition, though it's very simple
			$sql =
<<<EOT
	CREATE TABLE IF NOT EXISTS objectcache (
	  keyname BLOB NOT NULL default '' PRIMARY KEY,
	  value BLOB,
	  exptime TEXT
	)
EOT;
			$conn->query( $sql );
			$conn->query( "CREATE INDEX IF NOT EXISTS exptime ON objectcache (exptime)" );
			$conn->query( "PRAGMA journal_mode=WAL" ); // this is permanent
			$conn->close();
		} catch ( DBConnectionError $e ) {
			return Status::newFatal( 'config-sqlite-connection-error', $e->getMessage() );
		}

		# Open the main DB
		return $this->getConnection();
	}

	protected function makeStubDBFile( $dir, $db ) {
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
	 * @param Status $status
	 * @return Status
	 */
	public function setupSearchIndex( &$status ) {
		global $IP;

		$module = DatabaseSqlite::getFulltextSearchModule();
		$fts3tTable = $this->db->checkForEnabledSearch();
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

		return "# SQLite-specific settings
\$wgSQLiteDataDir = \"{$dir}\";
\$wgObjectCaches[CACHE_DB] = array(
	'class' => 'SqlBagOStuff',
	'loggroup' => 'SQLBagOStuff',
	'server' => array(
		'type' => 'sqlite',
		'dbname' => 'wikicache',
		'tablePrefix' => '',
		'flags' => 0
	)
);";
	}
}
