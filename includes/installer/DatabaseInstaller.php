<?php

/**
 * DBMS-specific installation helper.
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

use MediaWiki\Installer\Task\ITaskContext;
use MediaWiki\Status\Status;
use RuntimeException;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Base class for DBMS-specific installation helper classes.
 *
 * @ingroup Installer
 * @since 1.17
 */
abstract class DatabaseInstaller implements ITaskContext {

	/**
	 * The Installer object.
	 *
	 * @var Installer
	 */
	public $parent;

	/**
	 * @var string Set by subclasses
	 */
	public static $minimumVersion;

	/**
	 * @var string Set by subclasses
	 */
	protected static $notMinimumVersionMessage;

	/**
	 * @deprecated since 1.43 -- use definitelyGetConnection()
	 * @var IMaintainableDatabase
	 */
	public $db = null;

	/** @var IMaintainableDatabase|null */
	private $cachedConn;
	/** @var string|null */
	private $cachedConnType;

	/**
	 * Internal variables for installation.
	 *
	 * @var array
	 */
	protected $internalDefaults = [];

	/**
	 * Array of MW configuration globals this class uses.
	 *
	 * @var array
	 */
	protected $globalNames = [];

	/** @var array */
	private $provisions = [];

	/**
	 * Whether the provided version meets the necessary requirements for this type
	 *
	 * @param IDatabase $conn
	 * @return Status
	 * @since 1.30
	 */
	public static function meetsMinimumRequirement( IDatabase $conn ) {
		$serverVersion = $conn->getServerVersion();
		if ( version_compare( $serverVersion, static::$minimumVersion ) < 0 ) {
			return Status::newFatal(
				static::$notMinimumVersionMessage, static::$minimumVersion, $serverVersion
			);
		}

		return Status::newGood();
	}

	/**
	 * Return the internal name, e.g. 'mysql', or 'sqlite'.
	 */
	abstract public function getName();

	/**
	 * @return bool Returns true if the client library is compiled in.
	 */
	abstract public function isCompiled();

	/**
	 * Checks for installation prerequisites other than those checked by isCompiled()
	 * @since 1.19
	 * @return Status
	 */
	public function checkPrerequisites() {
		return Status::newGood();
	}

	/**
	 * Open a connection to the database using the administrative user/password
	 * currently defined in the session, without any caching. Returns a status
	 * object. On success, the status object will contain a Database object in
	 * its value member.
	 *
	 * The database should not be implicitly created.
	 *
	 * @param string $type One of the self::CONN_* constants, except CONN_DONT_KNOW
	 * @return ConnectionStatus
	 */
	abstract protected function openConnection( string $type );

	/**
	 * Connect to the database using the administrative user/password currently
	 * defined in the session. Returns a status object. On success, the status
	 * object will contain a Database object in its value member.
	 *
	 * This will return a cached connection if one is available.
	 *
	 * @param string $type One of the self::CONN_* constants. Using CONN_DONT_KNOW
	 *   is deprecated and will cause an exception to be thrown in a future release.
	 * @return ConnectionStatus
	 */
	public function getConnection( $type = self::CONN_DONT_KNOW ): ConnectionStatus {
		if ( $type === self::CONN_DONT_KNOW ) {
			if ( $this->cachedConnType ) {
				$type = $this->cachedConnType;
			} else {
				$type = self::CONN_CREATE_DATABASE;
			}
		}
		if ( $this->cachedConn ) {
			if ( $this->cachedConnType === $type ) {
				return new ConnectionStatus( $this->cachedConn );
			} else {
				return $this->changeConnType( $this->cachedConn, $this->cachedConnType, $type );
			}
		}
		$status = $this->openConnection( $type );
		if ( $status->isOK() ) {
			$this->cachedConn = $status->getDB();
			$this->cachedConnType = $type;
			// Assign to $this->db for b/c
			$this->db = $this->cachedConn;

			if ( $type === self::CONN_CREATE_SCHEMA || $type === self::CONN_CREATE_TABLES ) {
				$this->cachedConn->setSchemaVars( $this->getSchemaVars() );
			}
		}

		return $status;
	}

	/**
	 * Get a connection and unwrap it from its Status object, throwing an
	 * exception on failure.
	 *
	 * @param string $type
	 * @return IMaintainableDatabase
	 */
	public function definitelyGetConnection( string $type ): IMaintainableDatabase {
		$status = $this->getConnection( $type );
		if ( !$status->isOK() ) {
			throw new RuntimeException( __METHOD__ . ': unexpected DB connection error' );
		}
		return $status->getDB();
	}

	/**
	 * Change the type of a connection.
	 *
	 * CONN_CREATE_DATABASE means the domain is indeterminate and irrelevant,
	 * so converting from this type can be done by selecting the domain, and
	 * converting to it is a no-op.
	 *
	 * CONN_CREATE_SCHEMA means the domain is correct but tables created by
	 * PostgreSQL will have the incorrect role. So to convert from this to
	 * CONN_CREATE_TABLES, we set the role.
	 *
	 * CONN_CREATE_TABLES means a fully-configured connection, suitable for
	 * most tasks, so converting from it is a no-op.
	 *
	 * @param IMaintainableDatabase $conn
	 * @param string &$storedType One of the self::CONN_* constants. An in/out
	 *   parameter, set to the new type on success. It is set to the "real" new
	 *   type, reflecting the highest configuration level reached, to avoid
	 *   unnecessary selectDomain() calls when we need to temporarily give an
	 *   unconfigured connection.
	 * @param string $newType One of the self::CONN_* constants
	 * @return ConnectionStatus
	 */
	protected function changeConnType( IMaintainableDatabase $conn, &$storedType, $newType ) {
		// Change type from database to schema, if requested
		if ( $storedType === self::CONN_CREATE_DATABASE ) {
			if ( $newType === self::CONN_CREATE_SCHEMA || $newType === self::CONN_CREATE_TABLES ) {
				// TODO: catch exceptions from selectDomain and report as a Status
				$conn->selectDomain( new DatabaseDomain(
					$this->getVar( 'wgDBname' ),
					$this->getVar( 'wgDBmwschema' ),
					$this->getVar( 'wgDBprefix' ) ?? ''
				) );
				$conn->setSchemaVars( $this->getSchemaVars() );
				$storedType = self::CONN_CREATE_SCHEMA;
			}
		}
		// Change type from schema to tables, if requested
		if ( $newType === self::CONN_CREATE_TABLES && $storedType === self::CONN_CREATE_SCHEMA ) {
			$status = $this->changeConnTypeFromSchemaToTables( $conn );
			if ( $status->isOK() ) {
				$storedType = self::CONN_CREATE_TABLES;
			}
			return $status;
		}
		return new ConnectionStatus( $conn );
	}

	/**
	 * Change the type of a connection from CONN_CREATE_SCHEMA to CONN_CREATE_TABLES.
	 * Postgres overrides this.
	 *
	 * @param IMaintainableDatabase $conn
	 * @return ConnectionStatus
	 */
	protected function changeConnTypeFromSchemaToTables( IMaintainableDatabase $conn ) {
		return new ConnectionStatus( $conn );
	}

	public function getDbType(): string {
		return $this->getName();
	}

	/** @inheritDoc */
	public function getConfigVar( string $name ) {
		return $this->getVar( "wg$name" );
	}

	/** @inheritDoc */
	public function getOption( string $name ) {
		return $this->getVar( "_$name" );
	}

	/** @inheritDoc */
	public function provide( string $name, $value ) {
		$this->provisions[$name] = $value;
	}

	/** @inheritDoc */
	public function getProvision( string $name ) {
		if ( isset( $this->provisions[$name] ) ) {
			return $this->provisions[$name];
		} else {
			throw new \RuntimeException( "Can't find provided data \"$name\"" );
		}
	}

	/**
	 * Get the DBMS-specific options for LocalSettings.php generation.
	 *
	 * @return string
	 */
	abstract public function getLocalSettings();

	/**
	 * Override this to provide DBMS-specific schema variables, to be
	 * substituted into the schema files.
	 * @return array
	 */
	public function getSchemaVars() {
		return [];
	}

	/**
	 * Allow DB installers a chance to make checks before upgrade.
	 */
	public function preUpgrade() {
	}

	/**
	 * Get an array of MW configuration globals that will be configured by this class.
	 * @return array
	 */
	public function getGlobalNames() {
		return $this->globalNames;
	}

	/**
	 * Construct and initialise parent.
	 * This is typically only called from Installer::getDBInstaller()
	 * @param Installer $parent
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Convenience function.
	 * Check if a named extension is present.
	 *
	 * @param string $name
	 * @return bool
	 */
	protected static function checkExtension( $name ) {
		return extension_loaded( $name );
	}

	/**
	 * Get the internationalised name for this DBMS.
	 * @return string
	 */
	public function getReadableName() {
		// Messages: config-type-mysql, config-type-postgres, config-type-sqlite
		return wfMessage( 'config-type-' . $this->getName() )->text();
	}

	/**
	 * Get a name=>value map of MW configuration globals for the default values.
	 * @return array
	 * @return-taint none
	 */
	public function getGlobalDefaults() {
		$defaults = [];
		foreach ( $this->getGlobalNames() as $var ) {
			if ( isset( $GLOBALS[$var] ) ) {
				$defaults[$var] = $GLOBALS[$var];
			}
		}
		return $defaults;
	}

	/**
	 * Get a name=>value map of internal variables used during installation.
	 * @return array
	 */
	public function getInternalDefaults() {
		return $this->internalDefaults;
	}

	/**
	 * Get a variable, taking local defaults into account.
	 * @param string $var
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function getVar( $var, $default = null ) {
		$defaults = $this->getGlobalDefaults();
		$internal = $this->getInternalDefaults();
		if ( isset( $defaults[$var] ) ) {
			$default = $defaults[$var];
		} elseif ( isset( $internal[$var] ) ) {
			$default = $internal[$var];
		}

		return $this->parent->getVar( $var, $default );
	}

	/**
	 * Convenience alias for $this->parent->setVar()
	 * @param string $name
	 * @param mixed $value
	 */
	public function setVar( $name, $value ) {
		$this->parent->setVar( $name, $value );
	}

	abstract public function getConnectForm( WebInstaller $webInstaller ): DatabaseConnectForm;

	abstract public function getSettingsForm( WebInstaller $webInstaller ): DatabaseSettingsForm;

	/**
	 * Determine whether an existing installation of MediaWiki is present in
	 * the configured administrative connection. Returns true if there is
	 * such a wiki, false if the database doesn't exist.
	 *
	 * Traditionally, this is done by testing for the existence of either
	 * the revision table or the cur table.
	 *
	 * @return bool
	 */
	public function needsUpgrade() {
		$status = $this->getConnection( self::CONN_CREATE_SCHEMA );
		if ( !$status->isOK() ) {
			return false;
		}
		$db = $status->getDB();
		return $db->tableExists( 'cur', __METHOD__ ) ||
			$db->tableExists( 'revision', __METHOD__ );
	}

}
