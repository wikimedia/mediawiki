<?php
/**
 * MySQL-specific installer.
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

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * Class for setting up the MediaWiki database using MySQL.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class MysqlInstaller extends DatabaseInstaller {

	protected $globalNames = [
		'wgDBserver',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBprefix',
		'wgDBTableOptions',
	];

	protected $internalDefaults = [
		'_MysqlEngine' => 'InnoDB',
		'_MysqlCharset' => 'binary',
		'_InstallUser' => 'root',
	];

	public $supportedEngines = [ 'InnoDB' ];

	public static $minimumVersion = '5.5.8';
	protected static $notMinimumVersionMessage = 'config-mysql-old';

	public $webUserPrivs = [
		'DELETE',
		'INSERT',
		'SELECT',
		'UPDATE',
		'CREATE TEMPORARY TABLES',
	];

	/**
	 * @return string
	 */
	public function getName() {
		return 'mysql';
	}

	/**
	 * @return bool
	 */
	public function isCompiled() {
		return self::checkExtension( 'mysqli' );
	}

	/**
	 * @return string
	 */
	public function getConnectForm() {
		return $this->getTextBox(
			'wgDBserver',
			'config-db-host',
			[],
			$this->parent->getHelpBox( 'config-db-host-help' )
		) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', [], wfMessage( 'config-db-wiki-settings' )->text() ) .
			$this->getTextBox( 'wgDBname', 'config-db-name', [ 'dir' => 'ltr' ],
				$this->parent->getHelpBox( 'config-db-name-help' ) ) .
			$this->getTextBox( 'wgDBprefix', 'config-db-prefix', [ 'dir' => 'ltr' ],
				$this->parent->getHelpBox( 'config-db-prefix-help' ) ) .
			Html::closeElement( 'fieldset' ) .
			$this->getInstallUserBox();
	}

	public function submitConnectForm() {
		// Get variables from the request.
		$newValues = $this->setVarsFromRequest( [ 'wgDBserver', 'wgDBname', 'wgDBprefix' ] );

		// Validate them.
		$status = Status::newGood();
		if ( !strlen( $newValues['wgDBserver'] ) ) {
			$status->fatal( 'config-missing-db-host' );
		}
		if ( !strlen( $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-missing-db-name' );
		} elseif ( !preg_match( '/^[a-z0-9+_-]+$/i', $newValues['wgDBname'] ) ) {
			$status->fatal( 'config-invalid-db-name', $newValues['wgDBname'] );
		}
		if ( !preg_match( '/^[a-z0-9_-]*$/i', $newValues['wgDBprefix'] ) ) {
			$status->fatal( 'config-invalid-db-prefix', $newValues['wgDBprefix'] );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		// Submit user box
		$status = $this->submitInstallUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Try to connect
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		/**
		 * @var Database $conn
		 */
		$conn = $status->value;
		'@phan-var Database $conn';

		// Check version
		return static::meetsMinimumRequirement( $conn->getServerVersion() );
	}

	/**
	 * @return Status
	 */
	public function openConnection() {
		$status = Status::newGood();
		try {
			/** @var DatabaseMysqlBase $db */
			$db = Database::factory( 'mysql', [
				'host' => $this->getVar( 'wgDBserver' ),
				'user' => $this->getVar( '_InstallUser' ),
				'password' => $this->getVar( '_InstallPassword' ),
				'dbname' => false,
				'flags' => 0,
				'tablePrefix' => $this->getVar( 'wgDBprefix' ) ] );
			$status->value = $db;
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}

		return $status;
	}

	public function preUpgrade() {
		global $wgDBuser, $wgDBpassword;

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			$this->parent->showStatusMessage( $status );

			return;
		}
		/**
		 * @var Database $conn
		 */
		$conn = $status->value;
		$conn->selectDB( $this->getVar( 'wgDBname' ) );

		# Determine existing default character set
		if ( $conn->tableExists( "revision", __METHOD__ ) ) {
			$revision = $this->escapeLikeInternal( $this->getVar( 'wgDBprefix' ) . 'revision', '\\' );
			$res = $conn->query( "SHOW TABLE STATUS LIKE '$revision'", __METHOD__ );
			$row = $conn->fetchObject( $res );
			if ( !$row ) {
				$this->parent->showMessage( 'config-show-table-status' );
				$existingSchema = false;
				$existingEngine = false;
			} else {
				if ( preg_match( '/^latin1/', $row->Collation ) ) {
					$existingSchema = 'latin1';
				} elseif ( preg_match( '/^utf8/', $row->Collation ) ) {
					$existingSchema = 'utf8';
				} elseif ( preg_match( '/^binary/', $row->Collation ) ) {
					$existingSchema = 'binary';
				} else {
					$existingSchema = false;
					$this->parent->showMessage( 'config-unknown-collation' );
				}
				$existingEngine = $row->Engine ?? $row->Type;
			}
		} else {
			$existingSchema = false;
			$existingEngine = false;
		}

		if ( $existingSchema && $existingSchema != $this->getVar( '_MysqlCharset' ) ) {
			$this->setVar( '_MysqlCharset', $existingSchema );
		}
		if ( $existingEngine && $existingEngine != $this->getVar( '_MysqlEngine' ) ) {
			$this->setVar( '_MysqlEngine', $existingEngine );
		}

		# Normal user and password are selected after this step, so for now
		# just copy these two
		$wgDBuser = $this->getVar( '_InstallUser' );
		$wgDBpassword = $this->getVar( '_InstallPassword' );
	}

	/**
	 * @param string $s
	 * @param string $escapeChar
	 * @return string
	 */
	protected function escapeLikeInternal( $s, $escapeChar = '`' ) {
		return str_replace( [ $escapeChar, '%', '_' ],
			[ "{$escapeChar}{$escapeChar}", "{$escapeChar}%", "{$escapeChar}_" ],
			$s );
	}

	/**
	 * Get a list of storage engines that are available and supported
	 *
	 * @return array
	 */
	public function getEngines() {
		$status = $this->getConnection();

		/**
		 * @var Database $conn
		 */
		$conn = $status->value;

		$engines = [];
		$res = $conn->query( 'SHOW ENGINES', __METHOD__ );
		foreach ( $res as $row ) {
			if ( $row->Support == 'YES' || $row->Support == 'DEFAULT' ) {
				$engines[] = $row->Engine;
			}
		}
		$engines = array_intersect( $this->supportedEngines, $engines );

		return $engines;
	}

	/**
	 * Get a list of character sets that are available and supported
	 *
	 * @return array
	 */
	public function getCharsets() {
		return [ 'binary', 'utf8' ];
	}

	/**
	 * Return true if the install user can create accounts
	 *
	 * @return bool
	 */
	public function canCreateAccounts() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return false;
		}
		/** @var Database $conn */
		$conn = $status->value;

		// Get current account name
		$currentName = $conn->selectField( '', 'CURRENT_USER()', '', __METHOD__ );
		$parts = explode( '@', $currentName );
		if ( count( $parts ) != 2 ) {
			return false;
		}
		$quotedUser = $conn->addQuotes( $parts[0] ) .
			'@' . $conn->addQuotes( $parts[1] );

		// The user needs to have INSERT on mysql.* to be able to CREATE USER
		// The grantee will be double-quoted in this query, as required
		$res = $conn->select( 'INFORMATION_SCHEMA.USER_PRIVILEGES', '*',
			[ 'GRANTEE' => $quotedUser ], __METHOD__ );
		$insertMysql = false;
		$grantOptions = array_flip( $this->webUserPrivs );
		foreach ( $res as $row ) {
			if ( $row->PRIVILEGE_TYPE == 'INSERT' ) {
				$insertMysql = true;
			}
			if ( $row->IS_GRANTABLE ) {
				unset( $grantOptions[$row->PRIVILEGE_TYPE] );
			}
		}

		// Check for DB-specific privs for mysql.*
		if ( !$insertMysql ) {
			$row = $conn->selectRow( 'INFORMATION_SCHEMA.SCHEMA_PRIVILEGES', '*',
				[
					'GRANTEE' => $quotedUser,
					'TABLE_SCHEMA' => 'mysql',
					'PRIVILEGE_TYPE' => 'INSERT',
				], __METHOD__ );
			if ( $row ) {
				$insertMysql = true;
			}
		}

		if ( !$insertMysql ) {
			return false;
		}

		// Check for DB-level grant options
		$res = $conn->select( 'INFORMATION_SCHEMA.SCHEMA_PRIVILEGES', '*',
			[
				'GRANTEE' => $quotedUser,
				'IS_GRANTABLE' => 1,
			], __METHOD__ );
		foreach ( $res as $row ) {
			$regex = $this->likeToRegex( $row->TABLE_SCHEMA );
			if ( preg_match( $regex, $this->getVar( 'wgDBname' ) ) ) {
				unset( $grantOptions[$row->PRIVILEGE_TYPE] );
			}
		}
		if ( count( $grantOptions ) ) {
			// Can't grant everything
			return false;
		}

		return true;
	}

	/**
	 * Convert a wildcard (as used in LIKE) to a regex
	 * Slashes are escaped, slash terminators included
	 * @param string $wildcard
	 * @return string
	 */
	protected function likeToRegex( $wildcard ) {
		$r = preg_quote( $wildcard, '/' );
		$r = strtr( $r, [
			'%' => '.*',
			'_' => '.'
		] );
		return "/$r/s";
	}

	/**
	 * @return string
	 */
	public function getSettingsForm() {
		if ( $this->canCreateAccounts() ) {
			$noCreateMsg = false;
		} else {
			$noCreateMsg = 'config-db-web-no-create-privs';
		}
		$s = $this->getWebUserBox( $noCreateMsg );

		// Do engine selector
		$engines = $this->getEngines();
		// If the current default engine is not supported, use an engine that is
		if ( !in_array( $this->getVar( '_MysqlEngine' ), $engines ) ) {
			$this->setVar( '_MysqlEngine', reset( $engines ) );
		}

		// If the current default charset is not supported, use a charset that is
		$charsets = $this->getCharsets();
		if ( !in_array( $this->getVar( '_MysqlCharset' ), $charsets ) ) {
			$this->setVar( '_MysqlCharset', reset( $charsets ) );
		}

		return $s;
	}

	/**
	 * @return Status
	 */
	public function submitSettingsForm() {
		$this->setVarsFromRequest( [ '_MysqlEngine', '_MysqlCharset' ] );
		$status = $this->submitWebUserBox();
		if ( !$status->isOK() ) {
			return $status;
		}

		// Validate the create checkbox
		$canCreate = $this->canCreateAccounts();
		if ( !$canCreate ) {
			$this->setVar( '_CreateDBAccount', false );
			$create = false;
		} else {
			$create = $this->getVar( '_CreateDBAccount' );
		}

		if ( !$create ) {
			// Test the web account
			try {
				Database::factory( 'mysql', [
					'host' => $this->getVar( 'wgDBserver' ),
					'user' => $this->getVar( 'wgDBuser' ),
					'password' => $this->getVar( 'wgDBpassword' ),
					'dbname' => false,
					'flags' => 0,
					'tablePrefix' => $this->getVar( 'wgDBprefix' )
				] );
			} catch ( DBConnectionError $e ) {
				return Status::newFatal( 'config-connection-error', $e->getMessage() );
			}
		}

		// Validate engines and charsets
		// This is done pre-submit already so it's just for security
		$engines = $this->getEngines();
		if ( !in_array( $this->getVar( '_MysqlEngine' ), $engines ) ) {
			$this->setVar( '_MysqlEngine', reset( $engines ) );
		}
		$charsets = $this->getCharsets();
		if ( !in_array( $this->getVar( '_MysqlCharset' ), $charsets ) ) {
			$this->setVar( '_MysqlCharset', reset( $charsets ) );
		}

		return Status::newGood();
	}

	public function preInstall() {
		# Add our user callback to installSteps, right before the tables are created.
		$callback = [
			'name' => 'user',
			'callback' => [ $this, 'setupUser' ],
		];
		$this->parent->addInstallStep( $callback, 'tables' );
	}

	/**
	 * @return Status
	 */
	public function setupDatabase() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		/** @var Database $conn */
		$conn = $status->value;
		$dbName = $this->getVar( 'wgDBname' );
		if ( !$this->databaseExists( $dbName ) ) {
			$conn->query(
				"CREATE DATABASE " . $conn->addIdentifierQuotes( $dbName ) . "CHARACTER SET utf8",
				__METHOD__
			);
		}
		$conn->selectDB( $dbName );
		$this->setupSchemaVars();

		return $status;
	}

	/**
	 * Try to see if a given database exists
	 * @param string $dbName Database name to check
	 * @return bool
	 */
	private function databaseExists( $dbName ) {
		$encDatabase = $this->db->addQuotes( $dbName );

		return $this->db->query(
			"SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = $encDatabase",
			__METHOD__
		)->numRows() > 0;
	}

	/**
	 * @return Status
	 */
	public function setupUser() {
		$dbUser = $this->getVar( 'wgDBuser' );
		if ( $dbUser == $this->getVar( '_InstallUser' ) ) {
			return Status::newGood();
		}
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}

		$this->setupSchemaVars();
		$dbName = $this->getVar( 'wgDBname' );
		$this->db->selectDB( $dbName );
		$server = $this->getVar( 'wgDBserver' );
		$password = $this->getVar( 'wgDBpassword' );
		$grantableNames = [];

		if ( $this->getVar( '_CreateDBAccount' ) ) {
			// Before we blindly try to create a user that already has access,
			try { // first attempt to connect to the database
				Database::factory( 'mysql', [
					'host' => $server,
					'user' => $dbUser,
					'password' => $password,
					'dbname' => false,
					'flags' => 0,
					'tablePrefix' => $this->getVar( 'wgDBprefix' )
				] );
				$grantableNames[] = $this->buildFullUserName( $dbUser, $server );
				$tryToCreate = false;
			} catch ( DBConnectionError $e ) {
				$tryToCreate = true;
			}
		} else {
			$grantableNames[] = $this->buildFullUserName( $dbUser, $server );
			$tryToCreate = false;
		}

		if ( $tryToCreate ) {
			$createHostList = [
				$server,
				'localhost',
				'localhost.localdomain',
				'%'
			];

			$createHostList = array_unique( $createHostList );
			$escPass = $this->db->addQuotes( $password );

			foreach ( $createHostList as $host ) {
				$fullName = $this->buildFullUserName( $dbUser, $host );
				if ( !$this->userDefinitelyExists( $host, $dbUser ) ) {
					try {
						$this->db->begin( __METHOD__ );
						$this->db->query( "CREATE USER $fullName IDENTIFIED BY $escPass", __METHOD__ );
						$this->db->commit( __METHOD__ );
						$grantableNames[] = $fullName;
					} catch ( DBQueryError $dqe ) {
						if ( $this->db->lastErrno() == 1396 /* ER_CANNOT_USER */ ) {
							// User (probably) already exists
							$this->db->rollback( __METHOD__ );
							$status->warning( 'config-install-user-alreadyexists', $dbUser );
							$grantableNames[] = $fullName;
							break;
						} else {
							// If we couldn't create for some bizzare reason and the
							// user probably doesn't exist, skip the grant
							$this->db->rollback( __METHOD__ );
							$status->warning( 'config-install-user-create-failed', $dbUser, $dqe->getMessage() );
						}
					}
				} else {
					$status->warning( 'config-install-user-alreadyexists', $dbUser );
					$grantableNames[] = $fullName;
					break;
				}
			}
		}

		// Try to grant to all the users we know exist or we were able to create
		$dbAllTables = $this->db->addIdentifierQuotes( $dbName ) . '.*';
		foreach ( $grantableNames as $name ) {
			try {
				$this->db->begin( __METHOD__ );
				$this->db->query( "GRANT ALL PRIVILEGES ON $dbAllTables TO $name", __METHOD__ );
				$this->db->commit( __METHOD__ );
			} catch ( DBQueryError $dqe ) {
				$this->db->rollback( __METHOD__ );
				$status->fatal( 'config-install-user-grant-failed', $dbUser, $dqe->getMessage() );
			}
		}

		return $status;
	}

	/**
	 * Return a formal 'User'@'Host' username for use in queries
	 * @param string $name Username, quotes will be added
	 * @param string $host Hostname, quotes will be added
	 * @return string
	 */
	private function buildFullUserName( $name, $host ) {
		return $this->db->addQuotes( $name ) . '@' . $this->db->addQuotes( $host );
	}

	/**
	 * Try to see if the user account exists. Our "superuser" may not have
	 * access to mysql.user, so false means "no" or "maybe"
	 * @param string $host Hostname to check
	 * @param string $user Username to check
	 * @return bool
	 */
	private function userDefinitelyExists( $host, $user ) {
		try {
			$res = $this->db->selectRow( 'mysql.user', [ 'Host', 'User' ],
				[ 'Host' => $host, 'User' => $user ], __METHOD__ );

			return (bool)$res;
		} catch ( DBQueryError $dqe ) {
			return false;
		}
	}

	/**
	 * Return any table options to be applied to all tables that don't
	 * override them.
	 *
	 * @return string
	 */
	protected function getTableOptions() {
		$options = [];
		if ( $this->getVar( '_MysqlEngine' ) !== null ) {
			$options[] = "ENGINE=" . $this->getVar( '_MysqlEngine' );
		}
		if ( $this->getVar( '_MysqlCharset' ) !== null ) {
			$options[] = 'DEFAULT CHARSET=' . $this->getVar( '_MysqlCharset' );
		}

		return implode( ', ', $options );
	}

	/**
	 * Get variables to substitute into tables.sql and the SQL patch files.
	 *
	 * @return array
	 */
	public function getSchemaVars() {
		return [
			'wgDBTableOptions' => $this->getTableOptions(),
			'wgDBname' => $this->getVar( 'wgDBname' ),
			'wgDBuser' => $this->getVar( 'wgDBuser' ),
			'wgDBpassword' => $this->getVar( 'wgDBpassword' ),
		];
	}

	public function getLocalSettings() {
		$prefix = LocalSettingsGenerator::escapePhpString( $this->getVar( 'wgDBprefix' ) );
		$tblOpts = LocalSettingsGenerator::escapePhpString( $this->getTableOptions() );

		return "# MySQL specific settings
\$wgDBprefix = \"{$prefix}\";

# MySQL table options to use during installation or update
\$wgDBTableOptions = \"{$tblOpts}\";";
	}
}
