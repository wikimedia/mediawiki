<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Installer;

use CleanupEmptyCategories;
use DeleteDefaultMessages;
use LogicException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Maintenance\FakeMaintenance;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\MessageBlobStore;
use MediaWiki\SiteStats\SiteStatsInit;
use MigrateLinksTable;
use RebuildLocalisationCache;
use RefreshImageMetadata;
use RuntimeException;
use UnexpectedValueException;
use UpdateCollation;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

require_once __DIR__ . '/../../maintenance/Maintenance.php';

/**
 * Apply database changes after updating MediaWiki.
 *
 * @ingroup Installer
 * @since 1.17
 */
abstract class DatabaseUpdater {
	public const REPLICATION_WAIT_TIMEOUT = 300;

	/**
	 * Array of updates to perform on the database
	 *
	 * @var array
	 */
	protected $updates = [];

	/**
	 * Array of updates that were skipped
	 *
	 * @var array
	 */
	protected $updatesSkipped = [];

	/**
	 * List of extension-provided database updates
	 * @var array
	 */
	protected $extensionUpdates = [];

	/**
	 * List of extension-provided database updates on virtual domain dbs
	 * @var array
	 */
	protected $extensionUpdatesWithVirtualDomains = [];

	/**
	 * Handle to the database subclass
	 *
	 * @var IMaintainableDatabase
	 */
	protected $db;

	/**
	 * @var Maintenance
	 */
	protected $maintenance;

	/** @var bool */
	protected $shared = false;

	/** @var HookContainer|null */
	protected $autoExtensionHookContainer;

	/**
	 * @var class-string<Maintenance>[] Scripts to run after database update
	 * Should be a subclass of LoggedUpdateMaintenance
	 */
	protected $postDatabaseUpdateMaintenance = [
		DeleteDefaultMessages::class,
		CleanupEmptyCategories::class,
	];

	/**
	 * File handle for SQL output.
	 *
	 * @var resource|null
	 */
	protected $fileHandle = null;

	/**
	 * Flag specifying whether to skip schema (e.g., SQL-only) updates.
	 *
	 * @var bool
	 */
	protected $skipSchema = false;

	/**
	 * The virtual domain currently being acted on
	 * @var string|null
	 */
	private $currentVirtualDomain = null;

	/**
	 * @param IMaintainableDatabase &$db To perform updates on
	 * @param bool $shared Whether to perform updates on shared tables
	 * @param Maintenance|null $maintenance Maintenance object which created us
	 */
	protected function __construct(
		IMaintainableDatabase &$db,
		$shared,
		?Maintenance $maintenance = null
	) {
		$this->db = $db;
		$this->db->setFlag( DBO_DDLMODE );
		$this->shared = $shared;
		if ( $maintenance ) {
			$this->maintenance = $maintenance;
			$this->fileHandle = $maintenance->fileHandle;
		} else {
			$this->maintenance = new FakeMaintenance;
		}
		$this->maintenance->setDB( $db );
	}

	/**
	 * Cause extensions to register any updates they need to perform.
	 */
	private function loadExtensionSchemaUpdates() {
		$hookContainer = $this->loadExtensions();
		( new HookRunner( $hookContainer ) )->onLoadExtensionSchemaUpdates( $this );
	}

	/**
	 * Loads LocalSettings.php, if needed, and initialises everything needed for
	 * LoadExtensionSchemaUpdates hook.
	 *
	 * @return HookContainer
	 */
	private function loadExtensions() {
		if ( $this->autoExtensionHookContainer ) {
			// Already injected by installer
			return $this->autoExtensionHookContainer;
		}
		if ( defined( 'MW_EXTENSIONS_LOADED' ) ) {
			throw new LogicException( __METHOD__ .
				' apparently called from installer but no hook container was injected' );
		}
		if ( !defined( 'MEDIAWIKI_INSTALL' ) ) {
			// Running under update.php: use the global locator
			return MediaWikiServices::getInstance()->getHookContainer();
		}
		// Web upgrade used to load extensions here, but it now injects a hook
		// container like install
		throw new LogicException( __METHOD__ .
			' an extension hook container needs to be injected' );
	}

	/**
	 * @param IMaintainableDatabase $db
	 * @param bool $shared
	 * @param Maintenance|null $maintenance
	 * @return DatabaseUpdater
	 */
	public static function newForDB(
		IMaintainableDatabase $db,
		$shared = false,
		?Maintenance $maintenance = null
	) {
		$type = $db->getType();
		if ( in_array( $type, Installer::getDBTypes() ) ) {
			$class = '\\MediaWiki\\Installer\\' . ucfirst( $type ) . 'Updater';

			return new $class( $db, $shared, $maintenance );
		}

		throw new UnexpectedValueException( __METHOD__ . ' called for unsupported DB type' );
	}

	/**
	 * Set the HookContainer to use for loading extension schema updates.
	 *
	 * @internal For use by DatabaseInstaller
	 * @since 1.36
	 * @param HookContainer $hookContainer
	 */
	public function setAutoExtensionHookContainer( HookContainer $hookContainer ) {
		$this->autoExtensionHookContainer = $hookContainer;
	}

	/**
	 * Get a database connection to run updates
	 *
	 * @return IMaintainableDatabase
	 */
	public function getDB() {
		return $this->db;
	}

	/**
	 * Output some text. If we're running via the web, escape the text first.
	 *
	 * @param string $str Text to output
	 * @param-taint $str escapes_html
	 */
	public function output( $str ) {
		if ( $this->maintenance->isQuiet() ) {
			return;
		}
		if ( MW_ENTRY_POINT !== 'cli' ) {
			$str = htmlspecialchars( $str );
		}
		echo $str;
		flush();
	}

	/**
	 * Add a new update coming from an extension.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.17
	 *
	 * @param array $update The update to run. Format is [ $callback, $params... ]
	 *   $callback is the method to call; either a DatabaseUpdater method name or a callable.
	 *   Must be serializable (i.e., no anonymous functions allowed). The rest of the parameters
	 *   (if any) will be passed to the callback. The first parameter passed to the callback
	 *   is always this object.
	 */
	public function addExtensionUpdate( array $update ) {
		$this->extensionUpdates[] = $update;
	}

	/**
	 * Add a new update coming from an extension on virtual domain databases.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.42
	 *
	 * @param array $update The update to run. The format is [ $virtualDomain, $callback, $params... ]
	 *   similarly to addExtensionUpdate()
	 */
	public function addExtensionUpdateOnVirtualDomain( array $update ) {
		$this->extensionUpdatesWithVirtualDomains[] = $update;
	}

	/**
	 * Convenience wrapper for addExtensionUpdate() when adding a new table (which
	 * is the most common usage of updaters in an extension)
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.18
	 *
	 * @param string $tableName Name of table to create
	 * @param string $sqlPath Full path to the schema file
	 */
	public function addExtensionTable( $tableName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'addTable', $tableName, $sqlPath, true ];
	}

	/**
	 * Add an index to an existing extension table.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.19
	 *
	 * @param string $tableName
	 * @param string $indexName
	 * @param string $sqlPath
	 */
	public function addExtensionIndex( $tableName, $indexName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'addIndex', $tableName, $indexName, $sqlPath, true ];
	}

	/**
	 * Add a field to an existing extension table.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.19
	 *
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $sqlPath
	 */
	public function addExtensionField( $tableName, $columnName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'addField', $tableName, $columnName, $sqlPath, true ];
	}

	/**
	 * Drop a field from an extension table.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.20
	 *
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $sqlPath
	 */
	public function dropExtensionField( $tableName, $columnName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'dropField', $tableName, $columnName, $sqlPath, true ];
	}

	/**
	 * Drop an index from an extension table
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string $indexName
	 * @param string $sqlPath The path to the SQL change path
	 */
	public function dropExtensionIndex( $tableName, $indexName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'dropIndex', $tableName, $indexName, $sqlPath, true ];
	}

	/**
	 * Drop an extension table.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.20
	 *
	 * @param string $tableName
	 * @param string|bool $sqlPath
	 */
	public function dropExtensionTable( $tableName, $sqlPath = false ) {
		$this->extensionUpdates[] = [ 'dropTable', $tableName, $sqlPath, true ];
	}

	/**
	 * Rename an index on an extension table
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string $oldIndexName
	 * @param string $newIndexName
	 * @param string $sqlPath The path to the SQL change file
	 * @param bool $skipBothIndexExistWarning Whether to warn if both the old
	 * and the new indexes exist. [facultative; by default, false]
	 */
	public function renameExtensionIndex( $tableName, $oldIndexName, $newIndexName,
		$sqlPath, $skipBothIndexExistWarning = false
	) {
		$this->extensionUpdates[] = [
			'renameIndex',
			$tableName,
			$oldIndexName,
			$newIndexName,
			$skipBothIndexExistWarning,
			$sqlPath,
			true
		];
	}

	/**
	 * Modify an existing field in an extension table.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string $fieldName The field to be modified
	 * @param string $sqlPath The path to the SQL patch
	 */
	public function modifyExtensionField( $tableName, $fieldName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'modifyField', $tableName, $fieldName, $sqlPath, true ];
	}

	/**
	 * Modify an existing extension table.
	 * Intended for use in LoadExtensionSchemaUpdates hook handlers.
	 *
	 * @since 1.31
	 *
	 * @param string $tableName
	 * @param string $sqlPath The path to the SQL patch
	 */
	public function modifyExtensionTable( $tableName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'modifyTable', $tableName, $sqlPath, true ];
	}

	/**
	 * @since 1.20
	 *
	 * @param string $tableName
	 * @return bool
	 */
	public function tableExists( $tableName ) {
		return ( $this->db->tableExists( $tableName, __METHOD__ ) );
	}

	/**
	 * @since 1.40
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 * @return bool
	 */
	public function fieldExists( $tableName, $fieldName ) {
		return ( $this->db->fieldExists( $tableName, $fieldName, __METHOD__ ) );
	}

	/**
	 * Add a maintenance script to be run after the database updates are complete.
	 *
	 * Script should subclass LoggedUpdateMaintenance
	 *
	 * @since 1.19
	 *
	 * @param class-string<Maintenance> $class Name of a Maintenance subclass
	 */
	public function addPostDatabaseUpdateMaintenance( $class ) {
		$this->postDatabaseUpdateMaintenance[] = $class;
	}

	/**
	 * @since 1.17
	 *
	 * @return class-string<Maintenance>[]
	 */
	public function getPostDatabaseUpdateMaintenance() {
		return $this->postDatabaseUpdateMaintenance;
	}

	/**
	 * @since 1.21
	 *
	 * Writes the schema updates desired to a file for the DB Admin to run.
	 */
	private function writeSchemaUpdateFile() {
		$updates = $this->updatesSkipped;
		$this->updatesSkipped = [];

		foreach ( $updates as [ $func, $args, $origParams ] ) {
			$func( ...$args );
			flush();
			$this->updatesSkipped[] = $origParams;
		}
	}

	/**
	 * Get appropriate schema variables in the current database connection.
	 *
	 * This should be called after any request data has been imported, but before
	 * any write operations to the database. The result should be passed to the DB
	 * setSchemaVars() method.
	 *
	 * @return array
	 * @since 1.28
	 */
	public function getSchemaVars() {
		return []; // DB-type specific
	}

	/**
	 * Do all the updates
	 *
	 * @param array $what What updates to perform
	 */
	public function doUpdates( array $what = [ 'core', 'extensions', 'stats' ] ) {
		$this->db->setSchemaVars( $this->getSchemaVars() );

		$what = array_fill_keys( $what, true );
		$this->skipSchema = isset( $what['noschema'] ) || $this->fileHandle !== null;

		if ( isset( $what['initial'] ) ) {
			$this->output( 'Inserting initial update keys...' );
			$this->insertInitialUpdateKeys();
			$this->output( "done.\n" );
		}
		if ( isset( $what['core'] ) ) {
			$this->runUpdates( $this->getCoreUpdateList(), false );
			$this->doCollationUpdate();
		}
		if ( isset( $what['extensions'] ) ) {
			$this->loadExtensionSchemaUpdates();
			$this->runUpdates( $this->extensionUpdates, true );
			$this->runUpdates( $this->extensionUpdatesWithVirtualDomains, true, true );
		}

		if ( isset( $what['stats'] ) ) {
			$this->checkStats();
		}

		if ( $this->fileHandle ) {
			$this->skipSchema = false;
			$this->writeSchemaUpdateFile();
		}
	}

	/**
	 * Helper function for doUpdates()
	 *
	 * @param array $updates Array of updates to run
	 * @param bool $passSelf Whether to pass this object when calling external functions
	 * @param bool $hasVirtualDomain Whether the updates' array include virtual domains
	 */
	private function runUpdates( array $updates, $passSelf, $hasVirtualDomain = false ) {
		$lbFactory = $this->getLBFactory();
		$updatesDone = [];
		$updatesSkipped = [];
		foreach ( $updates as $params ) {
			$origParams = $params;
			$oldDb = null;
			$this->currentVirtualDomain = null;
			if ( $hasVirtualDomain === true ) {
				$this->currentVirtualDomain = array_shift( $params );
				$oldDb = $this->db;
				$virtualDb = $lbFactory->getPrimaryDatabase( $this->currentVirtualDomain );
				'@phan-var IMaintainableDatabase $virtualDb';
				$this->maintenance->setDB( $virtualDb );
				$this->db = $virtualDb;
			}
			$func = array_shift( $params );
			if ( !is_array( $func ) && method_exists( $this, $func ) ) {
				$func = [ $this, $func ];
			} elseif ( $passSelf ) {
				array_unshift( $params, $this );
			}
			$ret = $func( ...$params );
			if ( $hasVirtualDomain === true && $oldDb ) {
				$this->db = $oldDb;
				$this->maintenance->setDB( $oldDb );
				$this->currentVirtualDomain = null;
			}

			flush();
			if ( $ret !== false ) {
				$updatesDone[] = $origParams;
				$lbFactory->waitForReplication( [ 'timeout' => self::REPLICATION_WAIT_TIMEOUT ] );
			} else {
				if ( $hasVirtualDomain === true ) {
					$params = $origParams;
					$func = array_shift( $params );
				}
				$updatesSkipped[] = [ $func, $params, $origParams ];
			}
		}
		$this->updatesSkipped = array_merge( $this->updatesSkipped, $updatesSkipped );
		$this->updates = array_merge( $this->updates, $updatesDone );
	}

	private function getLBFactory(): LBFactory {
		return MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
	}

	/**
	 * Helper function: check if the given key is present in the updatelog table.
	 *
	 * @param string $key Name of the key to check for
	 * @return bool
	 */
	public function updateRowExists( $key ) {
		// Return false if the updatelog table does not exist. This can occur if performing schema changes for tables
		// that are on a virtual database domain.
		if ( !$this->db->tableExists( 'updatelog', __METHOD__ ) ) {
			return false;
		}

		$row = $this->db->newSelectQueryBuilder()
			->select( '1 AS X' ) // T67813
			->from( 'updatelog' )
			->where( [ 'ul_key' => $key ] )
			->caller( __METHOD__ )->fetchRow();

		return (bool)$row;
	}

	/**
	 * Helper function: Add a key to the updatelog table
	 *
	 * @note Extensions must only use this from within callbacks registered with
	 * addExtensionUpdate(). In particular, this method must not be called directly
	 * from a LoadExtensionSchemaUpdates handler.
	 *
	 * @param string $key Name of the key to insert
	 * @param string|null $val [optional] Value to insert along with the key
	 */
	public function insertUpdateRow( $key, $val = null ) {
		// We cannot insert anything to the updatelog table if it does not exist. This can occur for schema changes
		// on tables that are on a virtual database domain.
		if ( !$this->db->tableExists( 'updatelog', __METHOD__ ) ) {
			return;
		}

		$this->db->clearFlag( DBO_DDLMODE );
		$values = [ 'ul_key' => $key ];
		if ( $val ) {
			$values['ul_value'] = $val;
		}
		$this->db->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->ignore()
			->row( $values )
			->caller( __METHOD__ )->execute();
		$this->db->setFlag( DBO_DDLMODE );
	}

	/**
	 * Add initial keys to the updatelog table. Should be called during installation.
	 */
	public function insertInitialUpdateKeys() {
		$this->db->clearFlag( DBO_DDLMODE );
		$iqb = $this->db->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->ignore()
			->caller( __METHOD__ );
		foreach ( $this->getInitialUpdateKeys() as $key ) {
			$iqb->row( [ 'ul_key' => $key ] );
		}
		$iqb->execute();
		$this->db->setFlag( DBO_DDLMODE );
	}

	/**
	 * Returns whether updates should be executed on the database table $name.
	 * Updates will be prevented if the table is a shared table, and it is not
	 * specified to run updates on shared tables.
	 *
	 * @param string $name Table name
	 * @return bool
	 */
	protected function doTable( $name ) {
		global $wgSharedDB, $wgSharedTables;

		if ( $this->shared ) {
			// Shared updates are enabled
			return true;
		}
		if ( $this->currentVirtualDomain
			&& $this->getLBFactory()->isSharedVirtualDomain( $this->currentVirtualDomain )
		) {
			$this->output( "...skipping update to table $name in shared virtual domain.\n" );
			return false;
		}
		if ( $wgSharedDB !== null && in_array( $name, $wgSharedTables ) ) {
			$this->output( "...skipping update to shared table $name.\n" );
			return false;
		}

		return true;
	}

	/**
	 * Get an array of updates to perform on the database. Should return a
	 * multidimensional array. The main key is the MediaWiki version (1.12,
	 * 1.13...) with the values being arrays of updates.
	 *
	 * @return array[]
	 */
	abstract protected function getCoreUpdateList();

	/**
	 * Get an array of update keys to insert into the updatelog table after a
	 * new installation. The named operations will then be skipped by a
	 * subsequent update.
	 *
	 * Add keys here to skip updates that are redundant or harmful on a new
	 * installation, for example reducing field sizes, adding constraints, etc.
	 *
	 * @return string[]
	 */
	abstract protected function getInitialUpdateKeys();

	/**
	 * Append an SQL fragment to the open file handle.
	 *
	 * @note protected since 1.35
	 *
	 * @param string $filename File name to open
	 */
	protected function copyFile( $filename ) {
		$this->db->sourceFile(
			$filename,
			null,
			null,
			__METHOD__,
			function ( $line ) {
				return $this->appendLine( $line );
			}
		);
	}

	/**
	 * Append a line to the open file handle. The line is assumed to
	 * be a complete SQL statement.
	 *
	 * This is used as a callback for sourceLine().
	 *
	 * @note protected since 1.35
	 *
	 * @param string $line Text to append to the file
	 * @return bool False to skip actually executing the file
	 */
	protected function appendLine( $line ) {
		$line = rtrim( $line ) . ";\n";
		if ( fwrite( $this->fileHandle, $line ) === false ) {
			throw new RuntimeException( "trouble writing file" );
		}

		return false;
	}

	/**
	 * Applies a SQL patch
	 *
	 * @note Do not use this in a LoadExtensionSchemaUpdates handler,
	 *       use addExtensionUpdate instead!
	 *
	 * @param string $path Path to the patch file
	 * @param bool $isFullPath Whether to treat $path as a relative or not
	 * @param string|null $msg Description of the patch
	 * @return bool False if the patch was skipped.
	 */
	protected function applyPatch( $path, $isFullPath = false, $msg = null ) {
		$msg ??= "Applying $path patch";
		if ( $this->skipSchema ) {
			$this->output( "...skipping schema change ($msg).\n" );

			return false;
		}

		$this->output( "{$msg}..." );

		if ( !$isFullPath ) {
			$path = $this->patchPath( $this->db, $path );
		}
		if ( $this->fileHandle !== null ) {
			$this->copyFile( $path );
		} else {
			$this->db->sourceFile( $path );
		}
		$this->output( "done.\n" );

		return true;
	}

	/**
	 * Get the full path to a patch file.
	 *
	 * @param IDatabase $db
	 * @param string $patch The basename of the patch, like patch-something.sql
	 * @return string Full path to patch file. It fails back to MySQL
	 *  if no DB-specific patch exists.
	 */
	public function patchPath( IDatabase $db, $patch ) {
		$baseDir = MW_INSTALL_PATH;

		$dbType = $db->getType();
		if ( file_exists( "$baseDir/sql/$dbType/$patch" ) ) {
			return "$baseDir/sql/$dbType/$patch";
		}

		// TODO: Is the fallback still needed after the changes from T382030?
		return "$baseDir/sql/mysql/$patch";
	}

	/**
	 * Add a new table to the database
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use addExtensionTable instead!
	 *
	 * @param string $name Name of the new table
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function addTable( $name, $patch, $fullpath = false ) {
		if ( !$this->doTable( $name ) ) {
			return true;
		}

		if ( $this->db->tableExists( $name, __METHOD__ ) ) {
			$this->output( "...$name table already exists.\n" );
			return true;
		}

		return $this->applyPatch( $patch, $fullpath, "Creating $name table" );
	}

	/**
	 * Add a new field to an existing table
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use addExtensionField instead!
	 *
	 * @param string $table Name of the table to modify
	 * @param string $field Name of the new field
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function addField( $table, $field, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping new field patch.\n" );
		} elseif ( $this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->output( "...have $field field in $table table.\n" );
		} else {
			return $this->applyPatch( $patch, $fullpath, "Adding $field field to table $table" );
		}

		return true;
	}

	/**
	 * Add a new index to an existing table
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use addExtensionIndex instead!
	 *
	 * @param string $table Name of the table to modify
	 * @param string $index Name of the new index
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function addIndex( $table, $index, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...skipping: '$table' table doesn't exist yet.\n" );
		} elseif ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "...index $index already set on $table table.\n" );
		} else {
			return $this->applyPatch( $patch, $fullpath, "Adding index $index to table $table" );
		}

		return true;
	}

	/**
	 * Drop a field from an existing table
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use dropExtensionField instead!
	 *
	 * @param string $table Name of the table to modify
	 * @param string $field Name of the old field
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function dropField( $table, $field, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( $this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			return $this->applyPatch( $patch, $fullpath, "Table $table contains $field field. Dropping" );
		}

		$this->output( "...$table table does not contain $field field.\n" );
		return true;
	}

	/**
	 * Drop an index from an existing table
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use dropExtensionIndex instead!
	 *
	 * @param string $table Name of the table to modify
	 * @param string $index Name of the index
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function dropIndex( $table, $index, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			return $this->applyPatch( $patch, $fullpath, "Dropping $index index from table $table" );
		}

		$this->output( "...$index key doesn't exist.\n" );
		return true;
	}

	/**
	 * Rename an index from an existing table
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use renameExtensionIndex instead!
	 *
	 * @param string $table Name of the table to modify
	 * @param string $oldIndex Old name of the index
	 * @param string $newIndex New name of the index
	 * @param bool $skipBothIndexExistWarning Whether to warn if both the old and new indexes exist.
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function renameIndex( $table, $oldIndex, $newIndex,
		$skipBothIndexExistWarning, $patch, $fullpath = false
	) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		// First requirement: the table must exist
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...skipping: '$table' table doesn't exist yet.\n" );

			return true;
		}

		// Second requirement: the new index must be missing
		if ( $this->db->indexExists( $table, $newIndex, __METHOD__ ) ) {
			$this->output( "...index $newIndex already set on $table table.\n" );
			if ( !$skipBothIndexExistWarning &&
				$this->db->indexExists( $table, $oldIndex, __METHOD__ )
			) {
				$this->output( "...WARNING: $oldIndex still exists, despite it has " .
					"been renamed into $newIndex (which also exists).\n" .
					"            $oldIndex should be manually removed if not needed anymore.\n" );
			}

			return true;
		}

		// Third requirement: the old index must exist
		if ( !$this->db->indexExists( $table, $oldIndex, __METHOD__ ) ) {
			$this->output( "...skipping: index $oldIndex doesn't exist.\n" );

			return true;
		}

		// Requirements have been satisfied, the patch can be applied
		return $this->applyPatch(
			$patch,
			$fullpath,
			"Renaming index $oldIndex into $newIndex to table $table"
		);
	}

	/**
	 * If the specified table exists, drop it, or execute the
	 * patch if one is provided.
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use dropExtensionTable instead!
	 *
	 * @note protected since 1.35
	 *
	 * @param string $table Table to drop.
	 * @param string|false $patch String of patch file that will drop the table. Default: false.
	 * @param bool $fullpath Whether $patch is a full path. Default: false.
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function dropTable( $table, $patch = false, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( $this->db->tableExists( $table, __METHOD__ ) ) {
			$msg = "Dropping table $table";

			if ( $patch === false ) {
				$this->output( "$msg ..." );
				$this->db->dropTable( $table, __METHOD__ );
				$this->output( "done.\n" );
			} else {
				return $this->applyPatch( $patch, $fullpath, $msg );
			}
		} else {
			$this->output( "...$table doesn't exist.\n" );
		}

		return true;
	}

	/**
	 * Modify an existing field
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use modifyExtensionField instead!
	 *
	 * @note protected since 1.35
	 *
	 * @param string $table Name of the table to which the field belongs
	 * @param string $field Name of the field to modify
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function modifyField( $table, $field, $patch, $fullpath = false ) {
		return $this->modifyFieldWithCondition(
			$table, $field,
			static function () {
				return true;
			},
			$patch, $fullpath
		);
	}

	/**
	 * Modify or set a PRIMARY KEY on a table.
	 *
	 * This checks the current table schema via the database layer to determine the existing
	 * PRIMARY KEY columns. If they already match the requested set, the patch is skipped;
	 * otherwise the supplied patch is applied.
	 *
	 * @param string $table Table name
	 * @param string[] $columns Desired PRIMARY KEY columns in order
	 * @param string $patch SQL patch path
	 * @param bool $fullpath Whether $patch is a full path
	 * @return bool False if the patch was skipped because schema changes are skipped
	 */
	protected function modifyPrimaryKey( $table, array $columns, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...skipping: '$table' table doesn't exist yet.\n" );
			return true;
		}

		// Compare desired PK to current PK columns from the DB layer
		$current = $this->db->getPrimaryKeyColumns( $table, __METHOD__ );
		if ( $current === array_values( $columns ) ) {
			$this->output( "...primary key already set on $table table.\n" );
			return true;
		}

		return $this->applyPatch( $patch, $fullpath, "Modifying primary key on table $table" );
	}

	/**
	 * Modify an existing table, similar to modifyField. Intended for changes that
	 *  touch more than one column on a table.
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use modifyExtensionTable instead!
	 *
	 * @note protected since 1.35
	 *
	 * @param string $table Name of the table to modify
	 * @param string $patch Name of the patch file to apply
	 * @param string|bool $fullpath Whether to treat $patch path as relative or not, defaults to false
	 * @return bool False if this was skipped because of schema changes being skipped
	 */
	protected function modifyTable( $table, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		$updateKey = "$table-$patch";
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping modify table patch.\n" );
		} elseif ( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...table $table already modified by patch $patch.\n" );
		} else {
			$apply = $this->applyPatch( $patch, $fullpath, "Modifying table $table with patch $patch" );
			if ( $apply ) {
				$this->insertUpdateRow( $updateKey );
			}
			return $apply;
		}
		return true;
	}

	/**
	 * Modify a table if a field doesn't exist. This helps extensions to avoid
	 * running updates on SQLite that are destructive because they don't copy
	 * new fields.
	 *
	 * @since 1.44
	 * @param string $table Name of the table to which the field belongs
	 * @param string $field Name of the field to check
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @param string|null $fieldBeingModified The field being modified. If this
	 *   is specified, the updatelog key will match that used by modifyField(),
	 *   so if the patch was previously applied via modifyField(), it won't be
	 *   applied again. Also, if the field doesn't exist, the patch will not be
	 *   applied. If this is null, the updatelog key will match that used by
	 *   modifyTable().
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function modifyTableIfFieldNotExists( $table, $field, $patch, $fullpath = false,
		$fieldBeingModified = null
	) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( $fieldBeingModified === null ) {
			$updateKey = "$table-$patch";
		} else {
			$updateKey = "$table-$fieldBeingModified-$patch";
		}

		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping patch $patch.\n" );
		} elseif ( $this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->output( "...$field field exists in $table table, skipping obsolete patch $patch.\n" );
		} elseif ( $fieldBeingModified !== null
			&& !$this->db->fieldExists( $table, $fieldBeingModified, __METHOD__ )
		) {
			$this->output( "...$fieldBeingModified field does not exist in $table table, " .
				"skipping patch $patch.\n" );
		} elseif ( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...table $table already modified by patch $patch.\n" );
		} else {
			$apply = $this->applyPatch( $patch, $fullpath, "Modifying table $table with patch $patch" );
			if ( $apply ) {
				$this->insertUpdateRow( $updateKey );
			}
			return $apply;
		}
		return true;
	}

	/**
	 * Modify a field if the field exists and is nullable
	 *
	 * @since 1.44
	 * @param string $table Name of the table to which the field belongs
	 * @param string $field Name of the field to modify
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function modifyFieldIfNullable( $table, $field, $patch, $fullpath = false ) {
		return $this->modifyFieldWithCondition(
			$table, $field,
			static function ( $fieldInfo ) {
				return $fieldInfo->isNullable();
			},
			$patch,
			$fullpath
		);
	}

	/**
	 * Modify a field if a field exists and a callback returns true. The callback
	 * is called with the FieldInfo of the field in question.
	 *
	 * @internal
	 * @param string $table Name of the table to modify
	 * @param string $field Name of the field to modify
	 * @param callable $condCallback A callback which will be called with the
	 *   \Wikimedia\Rdbms\Field object for the specified field. If the callback returns
	 *   true, the update will proceed.
	 * @param string $patch Name of the patch file to apply
	 * @param string|bool $fullpath Whether to treat $patch path as relative or not, defaults to false
	 * @return bool False if this was skipped because of schema changes being skipped
	 */
	private function modifyFieldWithCondition(
		$table, $field, $condCallback, $patch, $fullpath = false
	) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		$updateKey = "$table-$field-$patch";
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping modify field patch.\n" );
			return true;
		}
		$fieldInfo = $this->db->fieldInfo( $table, $field );
		if ( !$fieldInfo ) {
			$this->output( "...$field field does not exist in $table table, " .
				"skipping modify field patch.\n" );
			return true;
		}
		if ( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...$field in table $table already modified by patch $patch.\n" );
			return true;
		}
		if ( !$condCallback( $fieldInfo ) ) {
			$this->output( "...$field in table $table already has the required properties.\n" );
			return true;
		}

		$apply = $this->applyPatch( $patch, $fullpath, "Modifying $field field of table $table" );
		if ( $apply ) {
			$this->insertUpdateRow( $updateKey );
		}
		return $apply;
	}

	/**
	 * Run a maintenance script
	 *
	 * This should only be used when the maintenance script must run before
	 * later updates. If later updates don't depend on the script, add it to
	 * DatabaseUpdater::$postDatabaseUpdateMaintenance instead.
	 *
	 * The script's execute() method must return true to indicate successful
	 * completion, and must return false (or throw an exception) to indicate
	 * unsuccessful completion.
	 *
	 * @note Code in a LoadExtensionSchemaUpdates handler should
	 *       use addExtensionUpdate instead!
	 *
	 * @note protected since 1.35
	 *
	 * @since 1.32
	 * @param string $class Maintenance subclass
	 * @param string $unused Unused, kept for compatibility
	 */
	protected function runMaintenance( $class, $unused = '' ) {
		$this->output( "Running $class...\n" );
		$task = $this->maintenance->runChild( $class );
		$ok = $task->execute();
		if ( !$ok ) {
			throw new RuntimeException( "Execution of $class did not complete successfully." );
		}
		$this->output( "done.\n" );
	}

	/**
	 * Set any .htaccess files or equivalent for storage repos
	 *
	 * Some zones (e.g. "temp") used to be public and may have been initialized as such
	 */
	public function setFileAccess() {
		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$zonePath = $repo->getZonePath( 'temp' );
		if ( $repo->getBackend()->directoryExists( [ 'dir' => $zonePath ] ) ) {
			// If the directory was never made, then it will have the right ACLs when it is made
			$status = $repo->getBackend()->secure( [
				'dir' => $zonePath,
				'noAccess' => true,
				'noListing' => true
			] );
			if ( $status->isOK() ) {
				$this->output( "Set the local repo temp zone container to be private.\n" );
			} else {
				$this->output( "Failed to set the local repo temp zone container to be private.\n" );
			}
		}
	}

	/**
	 * Purge various database caches
	 */
	public function purgeCache() {
		global $wgLocalisationCacheConf;
		// We can't guarantee that the user will be able to use TRUNCATE,
		// but we know that DELETE is available to us
		$this->output( "Purging caches..." );

		// ObjectCache
		$this->db->newDeleteQueryBuilder()
			->deleteFrom( 'objectcache' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();

		// LocalisationCache
		if ( $wgLocalisationCacheConf['manualRecache'] ) {
			$this->rebuildLocalisationCache();
		}

		// ResourceLoader: Message cache
		$services = MediaWikiServices::getInstance();
		MessageBlobStore::clearGlobalCacheEntry(
			$services->getMainWANObjectCache()
		);
	}

	/**
	 * Check the site_stats table is not properly populated.
	 */
	protected function checkStats() {
		$this->output( "...site_stats is populated..." );
		$row = $this->db->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( [ 'ss_row_id' => 1 ] )
			->caller( __METHOD__ )->fetchRow();
		if ( $row === false ) {
			$this->output( "data is missing! rebuilding...\n" );
		} elseif ( isset( $row->site_stats ) && $row->ss_total_pages == -1 ) {
			$this->output( "missing ss_total_pages, rebuilding...\n" );
		} else {
			$this->output( "done.\n" );

			return;
		}
		SiteStatsInit::doAllAndCommit( $this->db );
	}

	# Common updater functions

	/**
	 * Update CategoryLinks collation
	 */
	protected function doCollationUpdate() {
		global $wgCategoryCollation;
		if ( $this->updateRowExists( 'UpdateCollation::' . $wgCategoryCollation ) ) {
			$this->output( "...collations up-to-date.\n" );
			return;
		}
		$this->output( "Updating category collations...\n" );
		$task = $this->maintenance->runChild( UpdateCollation::class );
		$ok = $task->execute();
		if ( $ok !== false ) {
			$this->output( "...done.\n" );
			$this->insertUpdateRow( 'UpdateCollation::' . $wgCategoryCollation );
		}
	}

	protected function doConvertDjvuMetadata() {
		if ( $this->updateRowExists( 'ConvertDjvuMetadata' ) ) {
			return;
		}
		$this->output( "Converting djvu metadata..." );
		$task = $this->maintenance->runChild( RefreshImageMetadata::class );
		'@phan-var RefreshImageMetadata $task';
		$task->loadParamsAndArgs( RefreshImageMetadata::class, [
			'force' => true,
			'mediatype' => 'OFFICE',
			'mime' => 'image/*',
			'batch-size' => 1,
			'sleep' => 1
		] );
		$ok = $task->execute();
		if ( $ok !== false ) {
			$this->output( "...done.\n" );
			$this->insertUpdateRow( 'ConvertDjvuMetadata' );
		}
	}

	/**
	 * Rebuilds the localisation cache
	 */
	protected function rebuildLocalisationCache() {
		/**
		 * @var RebuildLocalisationCache $cl
		 */
		$cl = $this->maintenance->runChild(
			RebuildLocalisationCache::class, 'rebuildLocalisationCache.php'
		);
		'@phan-var RebuildLocalisationCache $cl';
		$this->output( "Rebuilding localisation cache...\n" );
		$cl->setForce();
		$cl->execute();
		$this->output( "done.\n" );
	}

	protected function migrateTemplatelinks() {
		if ( $this->updateRowExists( MigrateLinksTable::class . 'templatelinks' ) ) {
			$this->output( "...templatelinks table has already been migrated.\n" );
			return;
		}
		/**
		 * @var MigrateLinksTable $task
		 */
		$task = $this->maintenance->runChild(
			MigrateLinksTable::class, 'migrateLinksTable.php'
		);
		'@phan-var MigrateLinksTable $task';
		$task->loadParamsAndArgs( MigrateLinksTable::class, [
			'force' => true,
			'table' => 'templatelinks'
		] );
		$this->output( "Running migrateLinksTable.php on templatelinks...\n" );
		$task->execute();
		$this->output( "done.\n" );
	}

	protected function migratePagelinks() {
		if ( $this->updateRowExists( MigrateLinksTable::class . 'pagelinks' ) ) {
			$this->output( "...pagelinks table has already been migrated.\n" );
			return;
		}
		/**
		 * @var MigrateLinksTable $task
		 */
		$task = $this->maintenance->runChild(
			MigrateLinksTable::class, 'migrateLinksTable.php'
		);
		'@phan-var MigrateLinksTable $task';
		$task->loadParamsAndArgs( MigrateLinksTable::class, [
			'force' => true,
			'table' => 'pagelinks'
		] );
		$this->output( "Running migrateLinksTable.php on pagelinks...\n" );
		$task->execute();
		$this->output( "done.\n" );
	}

	protected function migrateCategorylinks() {
		if ( $this->updateRowExists( MigrateLinksTable::class . 'categorylinks' ) ) {
			$this->output( "...categorylinks table has already been migrated.\n" );
			return;
		}
		/**
		 * @var MigrateLinksTable $task
		 */
		$task = $this->maintenance->runChild(
			MigrateLinksTable::class, 'migrateLinksTable.php'
		);
		'@phan-var MigrateLinksTable $task';
		$task->loadParamsAndArgs( MigrateLinksTable::class, [
			'force' => true,
			'table' => 'categorylinks'
		] );
		$this->output( "Running migrateLinksTable.php on categorylinks...\n" );
		$task->execute();
		$this->output( "done.\n" );
	}

	protected function normalizeCollation() {
		if ( $this->updateRowExists( 'normalizeCollation' ) ) {
			$this->output( "...collation table has already been normalized.\n" );
			return;
		}
		/**
		 * @var UpdateCollation $task
		 */
		$task = $this->maintenance->runChild(
			UpdateCollation::class, 'updateCollation.php'
		);
		'@phan-var UpdateCollation $task';
		$task->loadParamsAndArgs( UpdateCollation::class, [
			'only-migrate-normalization' => true,
		] );
		$this->output( "Running updateCollation.php --only-migrate-normalization...\n" );
		$task->execute();
		$this->insertUpdateRow( 'normalizeCollation' );
		$this->output( "done.\n" );
	}

	/**
	 * Only run a function if a table does not exist
	 *
	 * @since 1.35
	 * @param string $table Table to check.
	 *  If passed $this, it's assumed to be a call from runUpdates() with
	 *  $passSelf = true: all other parameters are shifted and $this is
	 *  prepended to the rest of $params.
	 * @param string|array|static $func Normally this is the string naming the method on $this to
	 *  call. It may also be an array style callable.
	 * @param mixed ...$params Parameters for `$func`
	 * @return mixed Whatever $func returns, or null when skipped.
	 */
	protected function ifTableNotExists( $table, $func, ...$params ) {
		// Handle $passSelf from runUpdates().
		$passSelf = false;
		if ( $table === $this ) {
			$passSelf = true;
			$table = $func;
			$func = array_shift( $params );
		}

		if ( $this->db->tableExists( $table, __METHOD__ ) ) {
			return null;
		}

		if ( !is_array( $func ) && method_exists( $this, $func ) ) {
			$func = [ $this, $func ];
		} elseif ( $passSelf ) {
			array_unshift( $params, $this );
		}

		// @phan-suppress-next-line PhanUndeclaredInvokeInCallable Phan is confused
		return $func( ...$params );
	}

	/**
	 * Only run a function if the named field exists
	 *
	 * @since 1.35
	 * @param string $table Table to check.
	 *  If passed $this, it's assumed to be a call from runUpdates() with
	 *  $passSelf = true: all other parameters are shifted and $this is
	 *  prepended to the rest of $params.
	 * @param string $field Field to check
	 * @param string|array|static $func Normally this is the string naming the method on $this to
	 *  call. It may also be an array style callable.
	 * @param mixed ...$params Parameters for `$func`
	 * @return mixed Whatever $func returns, or null when skipped.
	 */
	protected function ifFieldExists( $table, $field, $func, ...$params ) {
		// Handle $passSelf from runUpdates().
		$passSelf = false;
		if ( $table === $this ) {
			$passSelf = true;
			$table = $field;
			$field = $func;
			$func = array_shift( $params );
		}

		if ( !$this->db->tableExists( $table, __METHOD__ ) ||
			!$this->db->fieldExists( $table, $field, __METHOD__ )
		) {
			return null;
		}

		if ( !is_array( $func ) && method_exists( $this, $func ) ) {
			$func = [ $this, $func ];
		} elseif ( $passSelf ) {
			array_unshift( $params, $this );
		}

		// @phan-suppress-next-line PhanUndeclaredInvokeInCallable Phan is confused
		return $func( ...$params );
	}

}

/** @deprecated class alias since 1.42 */
class_alias( DatabaseUpdater::class, 'DatabaseUpdater' );
