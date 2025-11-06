<?php
/**
 * PostgreSQL-specific installer.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Class for setting up the MediaWiki database using Postgres.
 *
 * @ingroup Installer
 * @since 1.17
 */
class PostgresInstaller extends DatabaseInstaller {

	/** @inheritDoc */
	protected $globalNames = [
		'wgDBserver',
		'wgDBport',
		'wgDBname',
		'wgDBuser',
		'wgDBpassword',
		'wgDBssl',
		'wgDBmwschema',
	];

	/** @inheritDoc */
	protected $internalDefaults = [
		'_InstallUser' => 'postgres',
	];

	/** @inheritDoc */
	public static $minimumVersion = '10';
	/** @inheritDoc */
	protected static $notMinimumVersionMessage = 'config-postgres-old';

	/** @inheritDoc */
	public function getName() {
		return 'postgres';
	}

	/** @inheritDoc */
	public function isCompiled() {
		return self::checkExtension( 'pgsql' );
	}

	public function getConnectForm( WebInstaller $webInstaller ): DatabaseConnectForm {
		return new PostgresConnectForm( $webInstaller, $this );
	}

	public function getSettingsForm( WebInstaller $webInstaller ): DatabaseSettingsForm {
		return new PostgresSettingsForm( $webInstaller, $this );
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
	 *    - self::CONN_CREATE_DATABASE: A connection for creating DBs, suitable for pre-
	 *                                  installation.
	 *    - self::CONN_CREATE_SCHEMA:   A connection to the new DB, for creating schemas and
	 *                                  other similar objects in the new DB.
	 *    - self::CONN_CREATE_TABLES:   A connection with a role suitable for creating tables.
	 * @return ConnectionStatus On success, a connection object will be in the value member.
	 */
	protected function openConnection( string $type ) {
		switch ( $type ) {
			case self::CONN_CREATE_DATABASE:
				return $this->openConnectionToAnyDB(
					$this->getVar( '_InstallUser' ),
					$this->getVar( '_InstallPassword' ) );
			case self::CONN_CREATE_SCHEMA:
				return $this->openConnectionWithParams(
					$this->getVar( '_InstallUser' ),
					$this->getVar( '_InstallPassword' ),
					$this->getVar( 'wgDBname' ),
					$this->getVar( 'wgDBmwschema' ) );
			case self::CONN_CREATE_TABLES:
				$status = $this->openConnection( self::CONN_CREATE_SCHEMA );
				if ( $status->isOK() ) {
					$status->merge( $this->changeConnTypeFromSchemaToTables( $status->getDB() ) );
				}

				return $status;
			default:
				throw new InvalidArgumentException( "Invalid connection type: \"$type\"" );
		}
	}

	/** @inheritDoc */
	protected function changeConnTypeFromSchemaToTables( IMaintainableDatabase $conn ) {
		if ( !( $conn instanceof DatabasePostgres ) ) {
			throw new InvalidArgumentException( 'Invalid connection type' );
		}
		$status = new ConnectionStatus( $conn );
		$schema = $this->getVar( 'wgDBmwschema' );
		if ( !$conn->schemaExists( $schema ) ) {
			$status->fatal( 'config-install-pg-schema-not-exist' );
			return $status;
		}
		$conn->determineCoreSchema( $schema );

		$safeRole = $conn->addIdentifierQuotes( $this->getVar( 'wgDBuser' ) );
		$conn->query( "SET ROLE $safeRole", __METHOD__ );
		return $status;
	}

	public function openConnectionToAnyDB( string $user, string $password ): ConnectionStatus {
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function getGlobalDefaults() {
		// The default $wgDBmwschema is null, which breaks Postgres and other DBMSes that require
		// the use of a schema, so we need to set it here
		return array_merge( parent::getGlobalDefaults(), [
			'wgDBmwschema' => 'mediawiki',
		] );
	}
}
