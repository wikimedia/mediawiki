<?php
/**
 * DBMS-specific updater helper.
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
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/../../maintenance/Maintenance.php';

/**
 * Class for handling database updates. Roughly based off of updaters.inc, with
 * a few improvements :)
 *
 * @ingroup Deployment
 * @since 1.17
 */
abstract class DatabaseUpdater {
	const REPLICATION_WAIT_TIMEOUT = 300;

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
	 * Handle to the database subclass
	 *
	 * @var IMaintainableDatabase
	 */
	protected $db;

	/**
	 * @var Maintenance
	 */
	protected $maintenance;

	protected $shared = false;

	/**
	 * @var string[] Scripts to run after database update
	 * Should be a subclass of LoggedUpdateMaintenance
	 */
	protected $postDatabaseUpdateMaintenance = [
		DeleteDefaultMessages::class,
		PopulateRevisionLength::class,
		PopulateRevisionSha1::class,
		PopulateImageSha1::class,
		FixExtLinksProtocolRelative::class,
		PopulateFilearchiveSha1::class,
		PopulateBacklinkNamespace::class,
		FixDefaultJsonContentPages::class,
		CleanupEmptyCategories::class,
		AddRFCandPMIDInterwiki::class,
		PopulatePPSortKey::class,
		PopulateIpChanges::class,
		RefreshExternallinksIndex::class,
	];

	/**
	 * File handle for SQL output.
	 *
	 * @var resource
	 */
	protected $fileHandle = null;

	/**
	 * Flag specifying whether or not to skip schema (e.g. SQL-only) updates.
	 *
	 * @var bool
	 */
	protected $skipSchema = false;

	/**
	 * Hold the value of $wgContentHandlerUseDB during the upgrade.
	 */
	protected $holdContentHandlerUseDB = true;

	/**
	 * @param IMaintainableDatabase &$db To perform updates on
	 * @param bool $shared Whether to perform updates on shared tables
	 * @param Maintenance|null $maintenance Maintenance object which created us
	 */
	protected function __construct(
		IMaintainableDatabase &$db,
		$shared,
		Maintenance $maintenance = null
	) {
		$this->db = $db;
		$this->db->setFlag( DBO_DDLMODE ); // For Oracle's handling of schema files
		$this->shared = $shared;
		if ( $maintenance ) {
			$this->maintenance = $maintenance;
			$this->fileHandle = $maintenance->fileHandle;
		} else {
			$this->maintenance = new FakeMaintenance;
		}
		$this->maintenance->setDB( $db );
		$this->initOldGlobals();
		$this->loadExtensions();
		Hooks::run( 'LoadExtensionSchemaUpdates', [ $this ] );
	}

	/**
	 * Initialize all of the old globals. One day this should all become
	 * something much nicer
	 */
	private function initOldGlobals() {
		global $wgExtNewTables, $wgExtNewFields, $wgExtPGNewFields,
			$wgExtPGAlteredFields, $wgExtNewIndexes, $wgExtModifiedFields;

		# For extensions only, should be populated via hooks
		# $wgDBtype should be checked to specify the proper file
		$wgExtNewTables = []; // table, dir
		$wgExtNewFields = []; // table, column, dir
		$wgExtPGNewFields = []; // table, column, column attributes; for PostgreSQL
		$wgExtPGAlteredFields = []; // table, column, new type, conversion method; for PostgreSQL
		$wgExtNewIndexes = []; // table, index, dir
		$wgExtModifiedFields = []; // table, index, dir
	}

	/**
	 * Loads LocalSettings.php, if needed, and initialises everything needed for
	 * LoadExtensionSchemaUpdates hook.
	 */
	private function loadExtensions() {
		if ( !defined( 'MEDIAWIKI_INSTALL' ) || defined( 'MW_EXTENSIONS_LOADED' ) ) {
			return; // already loaded
		}
		$vars = Installer::getExistingLocalSettings();

		$registry = ExtensionRegistry::getInstance();
		$queue = $registry->getQueue();
		// Don't accidentally load extensions in the future
		$registry->clearQueue();

		// This will automatically add "AutoloadClasses" to $wgAutoloadClasses
		$data = $registry->readFromQueue( $queue );
		$hooks = $data['globals']['wgHooks']['LoadExtensionSchemaUpdates'] ?? [];
		if ( $vars && isset( $vars['wgHooks']['LoadExtensionSchemaUpdates'] ) ) {
			$hooks = array_merge_recursive( $hooks, $vars['wgHooks']['LoadExtensionSchemaUpdates'] );
		}
		global $wgHooks, $wgAutoloadClasses;
		$wgHooks['LoadExtensionSchemaUpdates'] = $hooks;
		if ( $vars && isset( $vars['wgAutoloadClasses'] ) ) {
			$wgAutoloadClasses += $vars['wgAutoloadClasses'];
		}
	}

	/**
	 * @param IMaintainableDatabase $db
	 * @param bool $shared
	 * @param Maintenance|null $maintenance
	 *
	 * @throws MWException
	 * @return DatabaseUpdater
	 */
	public static function newForDB(
		IMaintainableDatabase $db,
		$shared = false,
		Maintenance $maintenance = null
	) {
		$type = $db->getType();
		if ( in_array( $type, Installer::getDBTypes() ) ) {
			$class = ucfirst( $type ) . 'Updater';

			return new $class( $db, $shared, $maintenance );
		} else {
			throw new MWException( __METHOD__ . ' called for unsupported $wgDBtype' );
		}
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
	 * Output some text. If we're running from web, escape the text first.
	 *
	 * @param string $str Text to output
	 * @param-taint $str escapes_html
	 */
	public function output( $str ) {
		if ( $this->maintenance->isQuiet() ) {
			return;
		}
		global $wgCommandLineMode;
		if ( !$wgCommandLineMode ) {
			$str = htmlspecialchars( $str );
		}
		echo $str;
		flush();
	}

	/**
	 * Add a new update coming from an extension. This should be called by
	 * extensions while executing the LoadExtensionSchemaUpdates hook.
	 *
	 * @since 1.17
	 *
	 * @param array $update The update to run. Format is [ $callback, $params... ]
	 *   $callback is the method to call; either a DatabaseUpdater method name or a callable.
	 *   Must be serializable (ie. no anonymous functions allowed). The rest of the parameters
	 *   (if any) will be passed to the callback. The first parameter passed to the callback
	 *   is always this object.
	 */
	public function addExtensionUpdate( array $update ) {
		$this->extensionUpdates[] = $update;
	}

	/**
	 * Convenience wrapper for addExtensionUpdate() when adding a new table (which
	 * is the most common usage of updaters in an extension)
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
	 *
	 * @since 1.21
	 *
	 * @param string $tableName The table name
	 * @param string $indexName The index name
	 * @param string $sqlPath The path to the SQL change path
	 */
	public function dropExtensionIndex( $tableName, $indexName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'dropIndex', $tableName, $indexName, $sqlPath, true ];
	}

	/**
	 *
	 * @since 1.20
	 *
	 * @param string $tableName
	 * @param string $sqlPath
	 */
	public function dropExtensionTable( $tableName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'dropTable', $tableName, $sqlPath, true ];
	}

	/**
	 * Rename an index on an extension table
	 *
	 * @since 1.21
	 *
	 * @param string $tableName The table name
	 * @param string $oldIndexName The old index name
	 * @param string $newIndexName The new index name
	 * @param string $sqlPath The path to the SQL change path
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
	 * @since 1.21
	 *
	 * @param string $tableName The table name
	 * @param string $fieldName The field to be modified
	 * @param string $sqlPath The path to the SQL patch
	 */
	public function modifyExtensionField( $tableName, $fieldName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'modifyField', $tableName, $fieldName, $sqlPath, true ];
	}

	/**
	 * @since 1.31
	 *
	 * @param string $tableName The table name
	 * @param string $sqlPath The path to the SQL patch
	 */
	public function modifyExtensionTable( $tableName, $sqlPath ) {
		$this->extensionUpdates[] = [ 'modifyTable', $tableName, $sqlPath, true ];
	}

	/**
	 *
	 * @since 1.20
	 *
	 * @param string $tableName
	 * @return bool
	 */
	public function tableExists( $tableName ) {
		return ( $this->db->tableExists( $tableName, __METHOD__ ) );
	}

	/**
	 * Add a maintenance script to be run after the database updates are complete.
	 *
	 * Script should subclass LoggedUpdateMaintenance
	 *
	 * @since 1.19
	 *
	 * @param string $class Name of a Maintenance subclass
	 */
	public function addPostDatabaseUpdateMaintenance( $class ) {
		$this->postDatabaseUpdateMaintenance[] = $class;
	}

	/**
	 * Get the list of extension-defined updates
	 *
	 * @return array
	 */
	protected function getExtensionUpdates() {
		return $this->extensionUpdates;
	}

	/**
	 * @since 1.17
	 *
	 * @return string[]
	 */
	public function getPostDatabaseUpdateMaintenance() {
		return $this->postDatabaseUpdateMaintenance;
	}

	/**
	 * @since 1.21
	 *
	 * Writes the schema updates desired to a file for the DB Admin to run.
	 * @param array $schemaUpdate
	 */
	private function writeSchemaUpdateFile( array $schemaUpdate = [] ) {
		$updates = $this->updatesSkipped;
		$this->updatesSkipped = [];

		foreach ( $updates as $funcList ) {
			list( $func, $args, $origParams ) = $funcList;
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

		$what = array_flip( $what );
		$this->skipSchema = isset( $what['noschema'] ) || $this->fileHandle !== null;
		if ( isset( $what['core'] ) ) {
			$this->runUpdates( $this->getCoreUpdateList(), false );
		}
		if ( isset( $what['extensions'] ) ) {
			$this->runUpdates( $this->getOldGlobalUpdates(), false );
			$this->runUpdates( $this->getExtensionUpdates(), true );
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
	 * @param bool $passSelf Whether to pass this object we calling external functions
	 */
	private function runUpdates( array $updates, $passSelf ) {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$updatesDone = [];
		$updatesSkipped = [];
		foreach ( $updates as $params ) {
			$origParams = $params;
			$func = array_shift( $params );
			if ( !is_array( $func ) && method_exists( $this, $func ) ) {
				$func = [ $this, $func ];
			} elseif ( $passSelf ) {
				array_unshift( $params, $this );
			}
			$ret = $func( ...$params );
			flush();
			if ( $ret !== false ) {
				$updatesDone[] = $origParams;
				$lbFactory->waitForReplication( [ 'timeout' => self::REPLICATION_WAIT_TIMEOUT ] );
			} else {
				$updatesSkipped[] = [ $func, $params, $origParams ];
			}
		}
		$this->updatesSkipped = array_merge( $this->updatesSkipped, $updatesSkipped );
		$this->updates = array_merge( $this->updates, $updatesDone );
	}

	/**
	 * Helper function: check if the given key is present in the updatelog table.
	 * Obviously, only use this for updates that occur after the updatelog table was
	 * created!
	 * @param string $key Name of the key to check for
	 * @return bool
	 */
	public function updateRowExists( $key ) {
		$row = $this->db->selectRow(
			'updatelog',
			# T67813
			'1 AS X',
			[ 'ul_key' => $key ],
			__METHOD__
		);

		return (bool)$row;
	}

	/**
	 * Helper function: Add a key to the updatelog table
	 * Obviously, only use this for updates that occur after the updatelog table was
	 * created!
	 * @param string $key Name of key to insert
	 * @param string|null $val [optional] Value to insert along with the key
	 */
	public function insertUpdateRow( $key, $val = null ) {
		$this->db->clearFlag( DBO_DDLMODE );
		$values = [ 'ul_key' => $key ];
		if ( $val && $this->canUseNewUpdatelog() ) {
			$values['ul_value'] = $val;
		}
		$this->db->insert( 'updatelog', $values, __METHOD__, 'IGNORE' );
		$this->db->setFlag( DBO_DDLMODE );
	}

	/**
	 * Updatelog was changed in 1.17 to have a ul_value column so we can record
	 * more information about what kind of updates we've done (that's what this
	 * class does). Pre-1.17 wikis won't have this column, and really old wikis
	 * might not even have updatelog at all
	 *
	 * @return bool
	 */
	protected function canUseNewUpdatelog() {
		return $this->db->tableExists( 'updatelog', __METHOD__ ) &&
			$this->db->fieldExists( 'updatelog', 'ul_value', __METHOD__ );
	}

	/**
	 * Returns whether updates should be executed on the database table $name.
	 * Updates will be prevented if the table is a shared table and it is not
	 * specified to run updates on shared tables.
	 *
	 * @param string $name Table name
	 * @return bool
	 */
	protected function doTable( $name ) {
		global $wgSharedDB, $wgSharedTables;

		// Don't bother to check $wgSharedTables if there isn't a shared database
		// or the user actually also wants to do updates on the shared database.
		if ( $wgSharedDB === null || $this->shared ) {
			return true;
		}

		if ( in_array( $name, $wgSharedTables ) ) {
			$this->output( "...skipping update to shared table $name.\n" );
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Before 1.17, we used to handle updates via stuff like
	 * $wgExtNewTables/Fields/Indexes. This is nasty :) We refactored a lot
	 * of this in 1.17 but we want to remain back-compatible for a while. So
	 * load up these old global-based things into our update list.
	 *
	 * @return array
	 */
	protected function getOldGlobalUpdates() {
		global $wgExtNewFields, $wgExtNewTables, $wgExtModifiedFields,
			$wgExtNewIndexes;

		$updates = [];

		foreach ( $wgExtNewTables as $tableRecord ) {
			$updates[] = [
				'addTable', $tableRecord[0], $tableRecord[1], true
			];
		}

		foreach ( $wgExtNewFields as $fieldRecord ) {
			$updates[] = [
				'addField', $fieldRecord[0], $fieldRecord[1],
				$fieldRecord[2], true
			];
		}

		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$updates[] = [
				'addIndex', $fieldRecord[0], $fieldRecord[1],
				$fieldRecord[2], true
			];
		}

		foreach ( $wgExtModifiedFields as $fieldRecord ) {
			$updates[] = [
				'modifyField', $fieldRecord[0], $fieldRecord[1],
				$fieldRecord[2], true
			];
		}

		return $updates;
	}

	/**
	 * Get an array of updates to perform on the database. Should return a
	 * multi-dimensional array. The main key is the MediaWiki version (1.12,
	 * 1.13...) with the values being arrays of updates, identical to how
	 * updaters.inc did it (for now)
	 *
	 * @return array[]
	 */
	abstract protected function getCoreUpdateList();

	/**
	 * Append an SQL fragment to the open file handle.
	 *
	 * @param string $filename File name to open
	 */
	public function copyFile( $filename ) {
		$this->db->sourceFile(
			$filename,
			null,
			null,
			__METHOD__,
			[ $this, 'appendLine' ]
		);
	}

	/**
	 * Append a line to the open filehandle.  The line is assumed to
	 * be a complete SQL statement.
	 *
	 * This is used as a callback for sourceLine().
	 *
	 * @param string $line Text to append to the file
	 * @return bool False to skip actually executing the file
	 * @throws MWException
	 */
	public function appendLine( $line ) {
		$line = rtrim( $line ) . ";\n";
		if ( fwrite( $this->fileHandle, $line ) === false ) {
			throw new MWException( "trouble writing file" );
		}

		return false;
	}

	/**
	 * Applies a SQL patch
	 *
	 * @param string $path Path to the patch file
	 * @param bool $isFullPath Whether to treat $path as a relative or not
	 * @param string|null $msg Description of the patch
	 * @return bool False if patch is skipped.
	 */
	protected function applyPatch( $path, $isFullPath = false, $msg = null ) {
		if ( $msg === null ) {
			$msg = "Applying $path patch";
		}
		if ( $this->skipSchema ) {
			$this->output( "...skipping schema change ($msg).\n" );

			return false;
		}

		$this->output( "$msg ..." );

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
	 * Get the full path of a patch file. Originally based on archive()
	 * from updaters.inc. Keep in mind this always returns a patch, as
	 * it fails back to MySQL if no DB-specific patch can be found
	 *
	 * @param IDatabase $db
	 * @param string $patch The name of the patch, like patch-something.sql
	 * @return string Full path to patch file
	 */
	public function patchPath( IDatabase $db, $patch ) {
		global $IP;

		$dbType = $db->getType();
		if ( file_exists( "$IP/maintenance/$dbType/archives/$patch" ) ) {
			return "$IP/maintenance/$dbType/archives/$patch";
		} else {
			return "$IP/maintenance/archives/$patch";
		}
	}

	/**
	 * Add a new table to the database
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
		} else {
			return $this->applyPatch( $patch, $fullpath, "Creating $name table" );
		}

		return true;
	}

	/**
	 * Add a new field to an existing table
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
	 * Add a new index to an existing table if none of the given indexes exist
	 *
	 * @param string $table Name of the table to modify
	 * @param string[] $indexes Name of the indexes to check. $indexes[0] should
	 *  be the one actually being added.
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	protected function addIndexIfNoneExist( $table, $indexes, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...skipping: '$table' table doesn't exist yet.\n" );
			return true;
		}

		$newIndex = $indexes[0];
		foreach ( $indexes as $index ) {
			if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
				$this->output(
					"...skipping index $newIndex because index $index already set on $table table.\n"
				);
				return true;
			}
		}

		return $this->applyPatch( $patch, $fullpath, "Adding index $index to table $table" );
	}

	/**
	 * Drop a field from an existing table
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
		} else {
			$this->output( "...$table table does not contain $field field.\n" );
		}

		return true;
	}

	/**
	 * Drop an index from an existing table
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
		} else {
			$this->output( "...$index key doesn't exist.\n" );
		}

		return true;
	}

	/**
	 * Rename an index from an existing table
	 *
	 * @param string $table Name of the table to modify
	 * @param string $oldIndex Old name of the index
	 * @param string $newIndex New name of the index
	 * @param bool $skipBothIndexExistWarning Whether to warn if both the
	 * old and the new indexes exist.
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

		// Requirements have been satisfied, patch can be applied
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
	 * Public @since 1.20
	 *
	 * @param string $table Table to drop.
	 * @param string|bool $patch String of patch file that will drop the table. Default: false.
	 * @param bool $fullpath Whether $patch is a full path. Default: false.
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	public function dropTable( $table, $patch = false, $fullpath = false ) {
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
	 * @param string $table Name of the table to which the field belongs
	 * @param string $field Name of the field to modify
	 * @param string $patch Path to the patch file
	 * @param bool $fullpath Whether to treat $patch path as a relative or not
	 * @return bool False if this was skipped because schema changes are skipped
	 */
	public function modifyField( $table, $field, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		$updateKey = "$table-$field-$patch";
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping modify field patch.\n" );
		} elseif ( !$this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->output( "...$field field does not exist in $table table, " .
				"skipping modify field patch.\n" );
		} elseif ( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...$field in table $table already modified by patch $patch.\n" );
		} else {
			$apply = $this->applyPatch( $patch, $fullpath, "Modifying $field field of table $table" );
			if ( $apply ) {
				$this->insertUpdateRow( $updateKey );
			}
			return $apply;
		}
		return true;
	}

	/**
	 * Modify an existing table, similar to modifyField. Intended for changes that
	 *  touch more than one column on a table.
	 *
	 * @param string $table Name of the table to modify
	 * @param string $patch Name of the patch file to apply
	 * @param string|bool $fullpath Whether to treat $patch path as relative or not, defaults to false
	 * @return bool False if this was skipped because of schema changes being skipped
	 */
	public function modifyTable( $table, $patch, $fullpath = false ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		$updateKey = "$table-$patch";
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping modify table patch.\n" );
		} elseif ( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...table $table already modified by patch $patch.\n" );
		} else {
			$apply = $this->applyPatch( $patch, $fullpath, "Modifying table $table" );
			if ( $apply ) {
				$this->insertUpdateRow( $updateKey );
			}
			return $apply;
		}
		return true;
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
	 * @since 1.32
	 * @param string $class Maintenance subclass
	 * @param string $script Script path and filename, usually "maintenance/fooBar.php"
	 */
	public function runMaintenance( $class, $script ) {
		$this->output( "Running $script...\n" );
		$task = $this->maintenance->runChild( $class );
		$ok = $task->execute();
		if ( !$ok ) {
			throw new RuntimeException( "Execution of $script did not complete successfully." );
		}
		$this->output( "done.\n" );
	}

	/**
	 * Set any .htaccess files or equivilent for storage repos
	 *
	 * Some zones (e.g. "temp") used to be public and may have been initialized as such
	 */
	public function setFileAccess() {
		$repo = RepoGroup::singleton()->getLocalRepo();
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
		$this->db->delete( 'objectcache', '*', __METHOD__ );

		// LocalisationCache
		if ( $wgLocalisationCacheConf['manualRecache'] ) {
			$this->rebuildLocalisationCache();
		}

		// ResourceLoader: Message cache
		$blobStore = new MessageBlobStore(
			MediaWikiServices::getInstance()->getResourceLoader()
		);
		$blobStore->clear();

		// ResourceLoader: File-dependency cache
		$this->db->delete( 'module_deps', '*', __METHOD__ );
		$this->output( "done.\n" );
	}

	/**
	 * Check the site_stats table is not properly populated.
	 */
	protected function checkStats() {
		$this->output( "...site_stats is populated..." );
		$row = $this->db->selectRow( 'site_stats', '*', [ 'ss_row_id' => 1 ], __METHOD__ );
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
	 * Sets the number of active users in the site_stats table
	 */
	protected function doActiveUsersInit() {
		$activeUsers = $this->db->selectField( 'site_stats', 'ss_active_users', '', __METHOD__ );
		if ( $activeUsers == -1 ) {
			$activeUsers = $this->db->selectField( 'recentchanges',
				'COUNT( DISTINCT rc_user_text )',
				[ 'rc_user != 0', 'rc_bot' => 0, "rc_log_type != 'newusers'" ], __METHOD__
			);
			$this->db->update( 'site_stats',
				[ 'ss_active_users' => intval( $activeUsers ) ],
				[ 'ss_row_id' => 1 ], __METHOD__, [ 'LIMIT' => 1 ]
			);
		}
		$this->output( "...ss_active_users user count set...\n" );
	}

	/**
	 * Populates the log_user_text field in the logging table
	 */
	protected function doLogUsertextPopulation() {
		if ( !$this->updateRowExists( 'populate log_usertext' ) ) {
			$this->output(
				"Populating log_user_text field, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateLogUsertext.php.\n"
			);

			$task = $this->maintenance->runChild( PopulateLogUsertext::class );
			$task->execute();
			$this->output( "done.\n" );
		}
	}

	/**
	 * Migrate log params to new table and index for searching
	 */
	protected function doLogSearchPopulation() {
		if ( !$this->updateRowExists( 'populate log_search' ) ) {
			$this->output(
				"Populating log_search table, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateLogSearch.php.\n" );

			$task = $this->maintenance->runChild( PopulateLogSearch::class );
			$task->execute();
			$this->output( "done.\n" );
		}
	}

	/**
	 * Update CategoryLinks collation
	 */
	protected function doCollationUpdate() {
		global $wgCategoryCollation;
		if ( $this->db->fieldExists( 'categorylinks', 'cl_collation', __METHOD__ ) ) {
			if ( $this->db->selectField(
				'categorylinks',
				'COUNT(*)',
				'cl_collation != ' . $this->db->addQuotes( $wgCategoryCollation ),
				__METHOD__
				) == 0
			) {
				$this->output( "...collations up-to-date.\n" );

				return;
			}

			$this->output( "Updating category collations..." );
			$task = $this->maintenance->runChild( UpdateCollation::class );
			$task->execute();
			$this->output( "...done.\n" );
		}
	}

	/**
	 * Migrates user options from the user table blob to user_properties
	 */
	protected function doMigrateUserOptions() {
		if ( $this->db->tableExists( 'user_properties' ) ) {
			$cl = $this->maintenance->runChild( ConvertUserOptions::class, 'convertUserOptions.php' );
			$cl->execute();
			$this->output( "done.\n" );
		}
	}

	/**
	 * Enable profiling table when it's turned on
	 */
	protected function doEnableProfiling() {
		global $wgProfiler;

		if ( !$this->doTable( 'profiling' ) ) {
			return;
		}

		$profileToDb = false;
		if ( isset( $wgProfiler['output'] ) ) {
			$out = $wgProfiler['output'];
			if ( $out === 'db' ) {
				$profileToDb = true;
			} elseif ( is_array( $out ) && in_array( 'db', $out ) ) {
				$profileToDb = true;
			}
		}

		if ( $profileToDb && !$this->db->tableExists( 'profiling', __METHOD__ ) ) {
			$this->applyPatch( 'patch-profiling.sql', false, 'Add profiling table' );
		}
	}

	/**
	 * Rebuilds the localisation cache
	 */
	protected function rebuildLocalisationCache() {
		/**
		 * @var $cl RebuildLocalisationCache
		 */
		$cl = $this->maintenance->runChild(
			RebuildLocalisationCache::class, 'rebuildLocalisationCache.php'
		);
		$this->output( "Rebuilding localisation cache...\n" );
		$cl->setForce();
		$cl->execute();
		$this->output( "done.\n" );
	}

	/**
	 * Turns off content handler fields during parts of the upgrade
	 * where they aren't available.
	 */
	protected function disableContentHandlerUseDB() {
		global $wgContentHandlerUseDB;

		if ( $wgContentHandlerUseDB ) {
			$this->output( "Turning off Content Handler DB fields for this part of upgrade.\n" );
			$this->holdContentHandlerUseDB = $wgContentHandlerUseDB;
			$wgContentHandlerUseDB = false;
		}
	}

	/**
	 * Turns content handler fields back on.
	 */
	protected function enableContentHandlerUseDB() {
		global $wgContentHandlerUseDB;

		if ( $this->holdContentHandlerUseDB ) {
			$this->output( "Content Handler DB fields should be usable now.\n" );
			$wgContentHandlerUseDB = $this->holdContentHandlerUseDB;
		}
	}

	/**
	 * Migrate comments to the new 'comment' table
	 * @since 1.30
	 */
	protected function migrateComments() {
		if ( !$this->updateRowExists( 'MigrateComments' ) ) {
			$this->output(
				"Migrating comments to the 'comments' table, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/migrateComments.php.\n"
			);
			$task = $this->maintenance->runChild( MigrateComments::class, 'migrateComments.php' );
			$ok = $task->execute();
			$this->output( $ok ? "done.\n" : "errors were encountered.\n" );
		}
	}

	/**
	 * Merge `image_comment_temp` into the `image` table
	 * @since 1.32
	 */
	protected function migrateImageCommentTemp() {
		if ( $this->tableExists( 'image_comment_temp' ) ) {
			$this->output( "Merging image_comment_temp into the image table\n" );
			$task = $this->maintenance->runChild(
				MigrateImageCommentTemp::class, 'migrateImageCommentTemp.php'
			);
			$task->setForce();
			$ok = $task->execute();
			$this->output( $ok ? "done.\n" : "errors were encountered.\n" );
			if ( $ok ) {
				$this->dropTable( 'image_comment_temp' );
			}
		}
	}

	/**
	 * Migrate actors to the new 'actor' table
	 * @since 1.31
	 */
	protected function migrateActors() {
		global $wgActorTableSchemaMigrationStage;
		if ( ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) &&
			!$this->updateRowExists( 'MigrateActors' )
		) {
			$this->output(
				"Migrating actors to the 'actor' table, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/migrateActors.php.\n"
			);
			$task = $this->maintenance->runChild( 'MigrateActors', 'migrateActors.php' );
			$ok = $task->execute();
			$this->output( $ok ? "done.\n" : "errors were encountered.\n" );
		}
	}

	/**
	 * Migrate ar_text to modern storage
	 * @since 1.31
	 */
	protected function migrateArchiveText() {
		if ( $this->db->fieldExists( 'archive', 'ar_text', __METHOD__ ) ) {
			$this->output( "Migrating archive ar_text to modern storage.\n" );
			$task = $this->maintenance->runChild( MigrateArchiveText::class, 'migrateArchiveText.php' );
			$task->setForce();
			if ( $task->execute() ) {
				$this->applyPatch( 'patch-drop-ar_text.sql', false,
					'Dropping ar_text and ar_flags columns' );
			}
		}
	}

	/**
	 * Populate ar_rev_id, then make it not nullable
	 * @since 1.31
	 */
	 protected function populateArchiveRevId() {
		 $info = $this->db->fieldInfo( 'archive', 'ar_rev_id', __METHOD__ );
		 if ( !$info ) {
			 throw new MWException( 'Missing ar_rev_id field of archive table. Should not happen.' );
		 }
		 if ( $info->isNullable() ) {
			 $this->output( "Populating ar_rev_id.\n" );
			 $task = $this->maintenance->runChild( 'PopulateArchiveRevId', 'populateArchiveRevId.php' );
			 if ( $task->execute() ) {
				 $this->applyPatch( 'patch-ar_rev_id-not-null.sql', false,
					 'Making ar_rev_id not nullable' );
			 }
		 }
	 }

	/**
	 * Populates the externallinks.el_index_60 field
	 * @since 1.32
	 */
	protected function populateExternallinksIndex60() {
		if ( !$this->updateRowExists( 'populate externallinks.el_index_60' ) ) {
			$this->output(
				"Populating el_index_60 field, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateExternallinksIndex60.php.\n"
			);
			$task = $this->maintenance->runChild( 'PopulateExternallinksIndex60',
				'populateExternallinksIndex60.php' );
			$task->execute();
			$this->output( "done.\n" );
		}
	}

	/**
	 * Populates the MCR content tables
	 * @since 1.32
	 */
	protected function populateContentTables() {
		global $wgMultiContentRevisionSchemaMigrationStage;
		if ( ( $wgMultiContentRevisionSchemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) &&
			!$this->updateRowExists( 'PopulateContentTables' )
		) {
			$this->output(
				"Migrating revision data to the MCR 'slot' and 'content' tables, printing progress markers.\n" .
				"For large databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateContentTables.php.\n"
			);
			$task = $this->maintenance->runChild(
				PopulateContentTables::class, 'populateContentTables.php'
			);
			$ok = $task->execute();
			$this->output( $ok ? "done.\n" : "errors were encountered.\n" );
			if ( $ok ) {
				$this->insertUpdateRow( 'PopulateContentTables' );
			}
		}
	}
}
