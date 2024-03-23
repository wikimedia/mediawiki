<?php
/**
 * PostgreSQL-specific installer.
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

use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBQueryError;

/**
 * Class for setting up the MediaWiki database using Postgres.
 *
 * @ingroup Installer
 * @since 1.17
 */
class PostgresInstaller extends DatabaseInstaller {

	protected $globalNames = [
		'wgDBserver',
		'wgDBport',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBssl',
		'wgDBmwschema',
	];

	protected $internalDefaults = [
		'_InstallUser' => 'postgres',
	];

	public static $minimumVersion = '10';
	protected static $notMinimumVersionMessage = 'config-postgres-old';
	public $maxRoleSearchDepth = 5;

	/**
	 * @var DatabasePostgres[]
	 */
	protected $pgConns = [];

	public function getName() {
		return 'postgres';
	}

	public function isCompiled() {
		return self::checkExtension( 'pgsql' );
	}

	public function getConnectForm( WebInstaller $webInstaller ): DatabaseConnectForm {
		return new PostgresConnectForm( $webInstaller, $this );
	}

	public function getSettingsForm( WebInstaller $webInstaller ): DatabaseSettingsForm {
		return new PostgresSettingsForm( $webInstaller, $this );
	}

	public function getConnection() {
		$status = $this->getPgConnection( 'create-tables' );
		if ( $status->isOK() ) {
			$this->db = $status->value;
		}

		return $status;
	}

	public function openConnection() {
		return $this->openPgConnection( 'create-tables' );
	}

	/**
	 * Open a PG connection with given parameters
	 * @param string $user User name
	 * @param string $password
	 * @param string $dbName Database name
	 * @param string $schema Database schema
	 * @return ConnectionStatus
	 */
	protected function openConnectionWithParams( $user, $password, $dbName, $schema ) {
		$status = new ConnectionStatus;
		try {
			$db = MediaWikiServices::getInstance()->getDatabaseFactory()->create( 'postgres', [
				'host' => $this->getVar( 'wgDBserver' ),
				'port' => $this->getVar( 'wgDBport' ),
				'user' => $user,
				'password' => $password,
				'ssl' => $this->getVar( 'wgDBssl' ),
				'dbname' => $dbName,
				'schema' => $schema,
			] );
			$status->setDB( $db );
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}

		return $status;
	}

	/**
	 * Get a special type of connection
	 * @param string $type See openPgConnection() for details.
	 * @return ConnectionStatus
	 */
	public function getPgConnection( $type ) {
		if ( isset( $this->pgConns[$type] ) ) {
			return new ConnectionStatus( $this->pgConns[$type] );
		}
		$status = $this->openPgConnection( $type );

		if ( $status->isOK() ) {
			$conn = $status->getDB();
			$conn->clearFlag( DBO_TRX );
			$conn->commit( __METHOD__ );
			$this->pgConns[$type] = $conn;
		}

		return $status;
	}

	/**
	 * Get a connection of a specific PostgreSQL-specific type. Connections
	 * of a given type are cached.
	 *
	 * PostgreSQL lacks cross-database operations, so after the new database is
	 * created, you need to make a separate connection to connect to that
	 * database and add tables to it.
	 *
	 * New tables are owned by the user that creates them, and MediaWiki's
	 * PostgreSQL support has always assumed that the table owner will be
	 * $wgDBuser. So before we create new tables, we either need to either
	 * connect as the other user or to execute a SET ROLE command. Using a
	 * separate connection for this allows us to avoid accidental cross-module
	 * dependencies.
	 *
	 * @param string $type The type of connection to get:
	 *    - create-db:     A connection for creating DBs, suitable for pre-
	 *                     installation.
	 *    - create-schema: A connection to the new DB, for creating schemas and
	 *                     other similar objects in the new DB.
	 *    - create-tables: A connection with a role suitable for creating tables.
	 * @return ConnectionStatus On success, a connection object will be in the value member.
	 */
	protected function openPgConnection( $type ) {
		switch ( $type ) {
			case 'create-db':
				return $this->openConnectionToAnyDB(
					$this->getVar( '_InstallUser' ),
					$this->getVar( '_InstallPassword' ) );
			case 'create-schema':
				return $this->openConnectionWithParams(
					$this->getVar( '_InstallUser' ),
					$this->getVar( '_InstallPassword' ),
					$this->getVar( 'wgDBname' ),
					$this->getVar( 'wgDBmwschema' ) );
			case 'create-tables':
				$status = $this->openPgConnection( 'create-schema' );
				if ( $status->isOK() ) {
					$conn = $status->getDB();
					$safeRole = $conn->addIdentifierQuotes( $this->getVar( 'wgDBuser' ) );
					$conn->query( "SET ROLE $safeRole", __METHOD__ );
				}

				return $status;
			default:
				throw new InvalidArgumentException( "Invalid special connection type: \"$type\"" );
		}
	}

	public function openConnectionToAnyDB( $user, $password ) {
		$dbs = [
			'template1',
			'postgres',
		];
		if ( !in_array( $this->getVar( 'wgDBname' ), $dbs ) ) {
			array_unshift( $dbs, $this->getVar( 'wgDBname' ) );
		}
		$conn = false;
		$status = new ConnectionStatus;
		foreach ( $dbs as $db ) {
			try {
				$p = [
					'host' => $this->getVar( 'wgDBserver' ),
					'port' => $this->getVar( 'wgDBport' ),
					'user' => $user,
					'password' => $password,
					'ssl' => $this->getVar( 'wgDBssl' ),
					'dbname' => $db
				];
				$conn = ( new DatabaseFactory() )->create( 'postgres', $p );
			} catch ( DBConnectionError $error ) {
				$conn = false;
				$status->fatal( 'config-pg-test-error', $db,
					$error->getMessage() );
			}
			if ( $conn !== false ) {
				break;
			}
		}
		if ( $conn !== false ) {
			return new ConnectionStatus( $conn );
		} else {
			return $status;
		}
	}

	protected function getInstallUserPermissions() {
		$status = $this->getPgConnection( 'create-db' );
		if ( !$status->isOK() ) {
			return false;
		}
		$conn = $status->getDB();
		$superuser = $this->getVar( '_InstallUser' );

		$row = $conn->selectRow( '"pg_catalog"."pg_roles"', '*',
			[ 'rolname' => $superuser ], __METHOD__ );

		return $row;
	}

	public function canCreateAccounts() {
		$perms = $this->getInstallUserPermissions();
		return ( $perms && $perms->rolsuper ) || $perms->rolcreaterole;
	}

	protected function isSuperUser() {
		$perms = $this->getInstallUserPermissions();
		return $perms && $perms->rolsuper;
	}

	/**
	 * Returns true if the install user is able to create objects owned
	 * by the web user, false otherwise.
	 * @return bool
	 */
	public function canCreateObjectsForWebUser() {
		if ( $this->isSuperUser() ) {
			return true;
		}

		$status = $this->getPgConnection( 'create-db' );
		if ( !$status->isOK() ) {
			return false;
		}
		$conn = $status->value;
		$installerId = $conn->selectField( '"pg_catalog"."pg_roles"', 'oid',
			[ 'rolname' => $this->getVar( '_InstallUser' ) ], __METHOD__ );
		$webId = $conn->selectField( '"pg_catalog"."pg_roles"', 'oid',
			[ 'rolname' => $this->getVar( 'wgDBuser' ) ], __METHOD__ );

		return $this->isRoleMember( $conn, $installerId, $webId, $this->maxRoleSearchDepth );
	}

	/**
	 * Recursive helper for canCreateObjectsForWebUser().
	 * @param Database $conn
	 * @param int $targetMember Role ID of the member to look for
	 * @param int $group Role ID of the group to look for
	 * @param int $maxDepth Maximum recursive search depth
	 * @return bool
	 */
	protected function isRoleMember( $conn, $targetMember, $group, $maxDepth ) {
		if ( $targetMember === $group ) {
			// A role is always a member of itself
			return true;
		}
		// Get all members of the given group
		$res = $conn->select( '"pg_catalog"."pg_auth_members"', [ 'member' ],
			[ 'roleid' => $group ], __METHOD__ );
		foreach ( $res as $row ) {
			if ( $row->member == $targetMember ) {
				// Found target member
				return true;
			}
			// Recursively search each member of the group to see if the target
			// is a member of it, up to the given maximum depth.
			if ( $maxDepth > 0 &&
				$this->isRoleMember( $conn, $targetMember, $row->member, $maxDepth - 1 )
			) {
				// Found member of member
				return true;
			}
		}

		return false;
	}

	public function preInstall() {
		$createDbAccount = [
			'name' => 'user',
			'callback' => [ $this, 'setupUser' ],
		];
		$commitCB = [
			'name' => 'pg-commit',
			'callback' => [ $this, 'commitChanges' ],
		];
		$plpgCB = [
			'name' => 'pg-plpgsql',
			'callback' => [ $this, 'setupPLpgSQL' ],
		];
		$schemaCB = [
			'name' => 'schema',
			'callback' => [ $this, 'setupSchema' ]
		];

		if ( $this->getVar( '_CreateDBAccount' ) ) {
			$this->parent->addInstallStep( $createDbAccount, 'database' );
		}
		$this->parent->addInstallStep( $commitCB, 'interwiki' );
		$this->parent->addInstallStep( $plpgCB, 'database' );
		$this->parent->addInstallStep( $schemaCB, 'database' );
	}

	public function setupDatabase() {
		$status = $this->getPgConnection( 'create-db' );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->value;

		$dbName = $this->getVar( 'wgDBname' );

		$exists = (bool)$conn->selectField( '"pg_catalog"."pg_database"', '1',
			[ 'datname' => $dbName ], __METHOD__ );
		if ( !$exists ) {
			$safedb = $conn->addIdentifierQuotes( $dbName );
			$conn->query( "CREATE DATABASE $safedb", __METHOD__ );
		}

		return Status::newGood();
	}

	public function setupSchema() {
		// Get a connection to the target database
		$status = $this->getPgConnection( 'create-schema' );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		'@phan-var DatabasePostgres $conn'; /** @var DatabasePostgres $conn */

		// Create the schema if necessary
		$schema = $this->getVar( 'wgDBmwschema' );
		$safeschema = $conn->addIdentifierQuotes( $schema );
		$safeuser = $conn->addIdentifierQuotes( $this->getVar( 'wgDBuser' ) );
		if ( !$conn->schemaExists( $schema ) ) {
			try {
				$conn->query( "CREATE SCHEMA $safeschema AUTHORIZATION $safeuser", __METHOD__ );
			} catch ( DBQueryError $e ) {
				return Status::newFatal( 'config-install-pg-schema-failed',
					$this->getVar( '_InstallUser' ), $schema );
			}
		}

		// Select the new schema in the current connection
		$conn->determineCoreSchema( $schema );

		return Status::newGood();
	}

	public function commitChanges() {
		$this->db->commit( __METHOD__ );

		return Status::newGood();
	}

	public function setupUser() {
		if ( !$this->getVar( '_CreateDBAccount' ) ) {
			return Status::newGood();
		}

		$status = $this->getPgConnection( 'create-db' );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		'@phan-var DatabasePostgres $conn'; /** @var DatabasePostgres $conn */

		$safeuser = $conn->addIdentifierQuotes( $this->getVar( 'wgDBuser' ) );
		$safepass = $conn->addQuotes( $this->getVar( 'wgDBpassword' ) );

		// Check if the user already exists
		$userExists = $conn->roleExists( $this->getVar( 'wgDBuser' ) );
		if ( !$userExists ) {
			// Create the user
			try {
				$sql = "CREATE ROLE $safeuser NOCREATEDB LOGIN PASSWORD $safepass";

				// If the install user is not a superuser, we need to make the install
				// user a member of the new user's group, so that the install user will
				// be able to create a schema and other objects on behalf of the new user.
				if ( !$this->isSuperUser() ) {
					$sql .= ' ROLE' . $conn->addIdentifierQuotes( $this->getVar( '_InstallUser' ) );
				}

				$conn->query( $sql, __METHOD__ );
			} catch ( DBQueryError $e ) {
				return Status::newFatal( 'config-install-user-create-failed',
					$this->getVar( 'wgDBuser' ), $e->getMessage() );
			}
		}

		return Status::newGood();
	}

	public function getLocalSettings() {
		$port = $this->getVar( 'wgDBport' );
		$useSsl = $this->getVar( 'wgDBssl' ) ? 'true' : 'false';
		$schema = $this->getVar( 'wgDBmwschema' );

		return "# Postgres specific settings
\$wgDBport = \"{$port}\";
\$wgDBssl = {$useSsl};
\$wgDBmwschema = \"{$schema}\";";
	}

	public function preUpgrade() {
		global $wgDBuser, $wgDBpassword;

		# Normal user and password are selected after this step, so for now
		# just copy these two
		$wgDBuser = $this->getVar( '_InstallUser' );
		$wgDBpassword = $this->getVar( '_InstallPassword' );
	}

	public function createTables() {
		$schema = $this->getVar( 'wgDBmwschema' );

		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();
		'@phan-var DatabasePostgres $conn'; /** @var DatabasePostgres $conn */

		if ( $conn->tableExists( 'archive', __METHOD__ ) ) {
			$status->warning( 'config-install-tables-exist' );
			$this->enableLB();

			return $status;
		}

		$conn->begin( __METHOD__ );

		if ( !$conn->schemaExists( $schema ) ) {
			$status->fatal( 'config-install-pg-schema-not-exist' );

			return $status;
		}

		$error = $conn->sourceFile( $this->getGeneratedSchemaPath( $conn ) );
		if ( $error !== true ) {
			$conn->reportQueryError( $error, 0, '', __METHOD__ );
			$conn->rollback( __METHOD__ );
			$status->fatal( 'config-install-tables-failed', $error );
		} else {
			$error = $conn->sourceFile( $this->getSchemaPath( $conn ) );
			if ( $error !== true ) {
				$conn->reportQueryError( $error, 0, '', __METHOD__ );
				$conn->rollback( __METHOD__ );
				$status->fatal( 'config-install-tables-manual-failed', $error );
			} else {
				$conn->commit( __METHOD__ );
			}
		}
		// Resume normal operations
		if ( $status->isOK() ) {
			$this->enableLB();
		}

		return $status;
	}

	public function createManualTables() {
		// Already handled above. Do nothing.
		return Status::newGood();
	}

	public function getGlobalDefaults() {
		// The default $wgDBmwschema is null, which breaks Postgres and other DBMSes that require
		// the use of a schema, so we need to set it here
		return array_merge( parent::getGlobalDefaults(), [
			'wgDBmwschema' => 'mediawiki',
		] );
	}

	public function setupPLpgSQL() {
		// Connect as the install user, since it owns the database and so is
		// the user that needs to run "CREATE LANGUAGE"
		$status = $this->getPgConnection( 'create-schema' );
		if ( !$status->isOK() ) {
			return $status;
		}
		$conn = $status->getDB();

		$exists = (bool)$conn->selectField( '"pg_catalog"."pg_language"', '1',
			[ 'lanname' => 'plpgsql' ], __METHOD__ );
		if ( $exists ) {
			// Already exists, nothing to do
			return Status::newGood();
		}

		// plpgsql is not installed, but if we have a pg_pltemplate table, we
		// should be able to create it
		$exists = (bool)$conn->selectField(
			[ '"pg_catalog"."pg_class"', '"pg_catalog"."pg_namespace"' ],
			'1',
			[
				'pg_namespace.oid=relnamespace',
				'nspname' => 'pg_catalog',
				'relname' => 'pg_pltemplate',
			],
			__METHOD__ );
		if ( $exists ) {
			try {
				$conn->query( 'CREATE LANGUAGE plpgsql', __METHOD__ );
			} catch ( DBQueryError $e ) {
				return Status::newFatal( 'config-pg-no-plpgsql', $this->getVar( 'wgDBname' ) );
			}
		} else {
			return Status::newFatal( 'config-pg-no-plpgsql', $this->getVar( 'wgDBname' ) );
		}

		return Status::newGood();
	}
}
