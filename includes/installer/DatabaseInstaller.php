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

use Exception;
use MediaWiki\Status\Status;
use MWException;
use MWLBFactory;
use RuntimeException;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\DBExpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactorySingle;

/**
 * Base class for DBMS-specific installation helper classes.
 *
 * @ingroup Installer
 * @since 1.17
 */
abstract class DatabaseInstaller {

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
	 * The database connection.
	 *
	 * @var Database
	 */
	public $db = null;

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
	 * @return ConnectionStatus
	 */
	abstract public function openConnection();

	/**
	 * Create the database and return a Status object indicating success or
	 * failure.
	 *
	 * @return Status
	 */
	abstract public function setupDatabase();

	/**
	 * Connect to the database using the administrative user/password currently
	 * defined in the session. Returns a status object. On success, the status
	 * object will contain a Database object in its value member.
	 *
	 * This will return a cached connection if one is available.
	 *
	 * @return ConnectionStatus
	 */
	public function getConnection() {
		if ( $this->db ) {
			return new ConnectionStatus( $this->db );
		}

		$status = $this->openConnection();
		if ( $status->isOK() ) {
			$this->db = $status->value;
			// Enable autocommit
			$this->db->clearFlag( DBO_TRX );
			$this->db->commit( __METHOD__ );
		}

		return $status;
	}

	/**
	 * Apply a SQL source file to the database as part of running an installation step.
	 *
	 * @param string $sourceFileMethod
	 * @param string $stepName
	 * @param string|false $tableThatMustNotExist
	 * @return Status
	 */
	private function stepApplySourceFile(
		$sourceFileMethod,
		$stepName,
		$tableThatMustNotExist = false
	) {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->selectDatabase( $this->db, $this->getVar( 'wgDBname' ) );

		if ( $tableThatMustNotExist && $this->db->tableExists( $tableThatMustNotExist, __METHOD__ ) ) {
			$status->warning( "config-$stepName-tables-exist" );
			$this->enableLB();

			return $status;
		}

		$this->db->setFlag( DBO_DDLMODE );
		$this->db->begin( __METHOD__ );

		$error = $this->db->sourceFile(
			call_user_func( [ $this, $sourceFileMethod ], $this->db )
		);
		if ( $error !== true ) {
			$this->db->reportQueryError( $error, 0, '', __METHOD__ );
			$this->db->rollback( __METHOD__ );
			$status->fatal( "config-$stepName-tables-failed", $error );
		} else {
			$this->db->commit( __METHOD__ );
		}
		// Resume normal operations
		if ( $status->isOK() ) {
			$this->enableLB();
		}

		return $status;
	}

	/**
	 * Create database tables from scratch from the automatically generated file
	 *
	 * @return Status
	 */
	public function createTables() {
		return $this->stepApplySourceFile( 'getGeneratedSchemaPath', 'install', 'archive' );
	}

	/**
	 * Create database tables from scratch.
	 *
	 * @return Status
	 */
	public function createManualTables() {
		return $this->stepApplySourceFile( 'getSchemaPath', 'install-manual' );
	}

	/**
	 * Insert update keys into table to prevent running unneeded updates.
	 *
	 * @return Status
	 */
	public function insertUpdateKeys() {
		return $this->stepApplySourceFile( 'getUpdateKeysPath', 'updates', false );
	}

	/**
	 * Return a path to the DBMS-specific SQL file if it exists,
	 * otherwise default SQL file
	 *
	 * @param IDatabase $db
	 * @param string $filename
	 * @return string
	 */
	private function getSqlFilePath( $db, $filename ) {
		global $IP;

		$dbmsSpecificFilePath = "$IP/maintenance/" . $db->getType() . "/$filename";
		if ( file_exists( $dbmsSpecificFilePath ) ) {
			return $dbmsSpecificFilePath;
		} else {
			return "$IP/maintenance/$filename";
		}
	}

	/**
	 * Return a path to the DBMS-specific schema file,
	 * otherwise default to tables.sql
	 *
	 * @param IDatabase $db
	 * @return string
	 */
	public function getSchemaPath( $db ) {
		return $this->getSqlFilePath( $db, 'tables.sql' );
	}

	/**
	 * Return a path to the DBMS-specific automatically generated schema file.
	 *
	 * @param IDatabase $db
	 * @return string
	 */
	public function getGeneratedSchemaPath( $db ) {
		return $this->getSqlFilePath( $db, 'tables-generated.sql' );
	}

	/**
	 * Return a path to the DBMS-specific update key file,
	 * otherwise default to update-keys.sql
	 *
	 * @param IDatabase $db
	 * @return string
	 */
	public function getUpdateKeysPath( $db ) {
		return $this->getSqlFilePath( $db, 'update-keys.sql' );
	}

	/**
	 * Create the tables for each extension the user enabled
	 * @return Status
	 */
	public function createExtensionTables() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->enableLB();

		// Now run updates to create tables for old extensions
		$updater = DatabaseUpdater::newForDB( $this->db );
		$updater->setAutoExtensionHookContainer( $this->parent->getAutoExtensionHookContainer() );
		$updater->doUpdates( [ 'extensions' ] );

		return $status;
	}

	/**
	 * Get the DBMS-specific options for LocalSettings.php generation.
	 *
	 * @return string
	 */
	abstract public function getLocalSettings();

	/**
	 * Override this to provide DBMS-specific schema variables, to be
	 * substituted into tables.sql and other schema files.
	 * @return array
	 */
	public function getSchemaVars() {
		return [];
	}

	/**
	 * Set appropriate schema variables in the current database connection.
	 *
	 * This should be called after any request data has been imported, but before
	 * any write operations to the database.
	 */
	public function setupSchemaVars() {
		$status = $this->getConnection();
		if ( $status->isOK() ) {
			$status->getDB()->setSchemaVars( $this->getSchemaVars() );
		} else {
			$msg = __METHOD__ . ': unexpected error while establishing'
				. ' a database connection with message: '
				. $status->getMessage()->plain();
			throw new RuntimeException( $msg );
		}
	}

	/**
	 * Set up LBFactory so that getPrimaryDatabase() etc. works.
	 * We set up a special LBFactory instance which returns the current
	 * installer connection.
	 */
	public function enableLB() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			throw new RuntimeException( __METHOD__ . ': unexpected DB connection error' );
		}
		$connection = $status->value;
		$virtualDomains = array_merge(
			$this->parent->getVirtualDomains(),
			MWLBFactory::CORE_VIRTUAL_DOMAINS
		);

		$this->parent->resetMediaWikiServices( null, [
			'DBLoadBalancerFactory' => static function () use ( $virtualDomains, $connection ) {
				return LBFactorySingle::newFromConnection(
					$connection,
					[ 'virtualDomains' => $virtualDomains ]
				);
			}
		] );
	}

	/**
	 * Perform database upgrades
	 *
	 * @return bool
	 * @suppress SecurityCheck-XSS Escaping provided by $this->outputHandler
	 */
	public function doUpgrade() {
		$this->setupSchemaVars();
		$this->enableLB();

		$ret = true;
		ob_start( [ $this, 'outputHandler' ] );
		$up = DatabaseUpdater::newForDB( $this->db );
		try {
			$up->doUpdates();
			$up->purgeCache();
		} catch ( MWException $e ) {
			// TODO: Remove special casing in favour of MWExceptionRenderer
			echo "\nAn error occurred:\n";
			echo $e->getText();
			$ret = false;
		} catch ( Exception $e ) {
			echo "\nAn error occurred:\n";
			echo $e->getMessage();
			$ret = false;
		}
		ob_end_flush();

		return $ret;
	}

	/**
	 * Allow DB installers a chance to make last-minute changes before installation
	 * occurs. This happens before setupDatabase() or createTables() is called, but
	 * long after the constructor. Helpful for things like modifying setup steps :)
	 */
	public function preInstall() {
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
	 * @param WebInstaller $parent
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
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return false;
		}

		try {
			$this->selectDatabase( $this->db, $this->getVar( 'wgDBname' ) );
		} catch ( DBConnectionError $e ) {
			// Don't catch DBConnectionError
			throw $e;
		} catch ( DBExpectedError $e ) {
			return false;
		}

		return $this->db->tableExists( 'cur', __METHOD__ ) ||
			$this->db->tableExists( 'revision', __METHOD__ );
	}

	/**
	 * Common function for databases that don't understand the MySQLish syntax of interwiki.list.
	 *
	 * @return Status
	 */
	public function populateInterwikiTable() {
		$status = $this->getConnection();
		if ( !$status->isOK() ) {
			return $status;
		}
		$this->selectDatabase( $this->db, $this->getVar( 'wgDBname' ) );

		$row = $this->db->newSelectQueryBuilder()
			->select( '1' )
			->from( 'interwiki' )
			->caller( __METHOD__ )->fetchRow();
		if ( $row ) {
			$status->warning( 'config-install-interwiki-exists' );

			return $status;
		}
		global $IP;
		AtEase::suppressWarnings();
		$rows = file( "$IP/maintenance/interwiki.list",
			FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		AtEase::restoreWarnings();
		if ( !$rows ) {
			return Status::newFatal( 'config-install-interwiki-list' );
		}
		$insert = $this->db->newInsertQueryBuilder()
			->insertInto( 'interwiki' );
		foreach ( $rows as $row ) {
			$row = preg_replace( '/^\s*([^#]*?)\s*(#.*)?$/', '\\1', $row ); // strip comments - whee
			if ( $row == "" ) {
				continue;
			}
			$row .= "|";
			$insert->row(
				array_combine(
					[ 'iw_prefix', 'iw_url', 'iw_local', 'iw_api', 'iw_wikiid' ],
					explode( '|', $row )
				)
			);
		}
		$insert->caller( __METHOD__ )->execute();

		return Status::newGood();
	}

	public function outputHandler( $string ) {
		return htmlspecialchars( $string );
	}

	/**
	 * @param Database $conn
	 * @param string $database
	 * @return bool
	 * @since 1.39
	 */
	protected function selectDatabase( Database $conn, string $database ) {
		$schema = $conn->dbSchema();
		$prefix = $conn->tablePrefix();

		$conn->selectDomain( new DatabaseDomain(
			$database,
			// DatabaseDomain uses null for unspecified schemas
			( $schema !== '' ) ? $schema : null,
			$prefix
		) );

		return true;
	}
}
