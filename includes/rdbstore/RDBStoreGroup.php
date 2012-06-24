<?php
/**
 * This file deals with sharded database stores.
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
 * @ingroup RDBStore
 * @author Aaron Schulz
 */

/**
 * Factory class for getting RDBStore objects
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class RDBStoreGroup {
	/** @var RDBStoreGroup */
	protected static $instance = null;

	/** @var Array */
	protected $storeConfig = array(); // (store name => config array)
	/** @var Array */
	protected $tableGroups = array(); // (group name => table list)
	/** @var Array */
	protected $denormalizedTables = array(); // (table name => schema info array)

	/** @var Array */
	protected $storedTables = array(); // (wiki ID => table name => store name)

	/** @var Array */
	protected $extStoreInstances = array();  // (store name => ExternalRDBStore)
	/** @var Array */
	protected $intStoreInstances = array();  // (wiki ID => LocalRDBStore)

	protected function __construct() {}

	/**
	 * @return RDBStoreGroup
	 */
	public static function singleton() {
		if ( self::$instance == null ) {
			self::$instance = new self();
			self::$instance->initFromGlobals();
		}
		return self::$instance;
	}

	/**
	 * Destroy the singleton instance
	 *
	 * @return void
	 */
	public static function destroySingleton() {
		self::$instance = null;
	}

	/**
	 * Register db stores from the global variables
	 *
	 * @return void
	 */
	protected function initFromGlobals() {
		global $wgRDBStores, $wgRDBStoredTables;

		$this->storeConfig  = $wgRDBStores;
		$this->storedTables = $wgRDBStoredTables;
		// Let extensions register table groups and denormalized tables
		wfRunHooks( 'RDBStoreTableSchemaInfo',
			array( &$this->tableGroups, &$this->denormalizedTables ) );
	}

	/**
	 * Get a DB store on the main cluster of a wiki.
	 * This uses a multi-singleton pattern to track transactions.
	 *
	 * @param $wiki string Wiki ID
	 * @return LocalRDBStore
	 */
	public function getInternal( $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( $this->intStoreInstances[$wiki] ) ) {
			$this->intStoreInstances[$wiki] = new LocalRDBStore( array( 'wiki' => $wiki ) );
		}
		return $this->intStoreInstances[$wiki];
	}

	/**
	 * Get an external DB store by name.
	 * This uses a multi-singleton pattern to track transactions.
	 *
	 * @param $name string Storage group ID
	 * @return ExternalRDBStore
	 */
	public function getExternal( $name ) {
		if ( !isset( $this->extStoreInstances[$name] ) ) {
			if ( !isset( $this->storeConfig[$name] ) ) {
				throw new MWException( "No DB store defined with the name '$name'." );
			}
			$this->storeConfig[$name]['name'] = $name;
			$this->extStoreInstances[$name] = new ExternalRDBStore( $this->storeConfig[$name] );
		}
		return $this->extStoreInstances[$name];
	}

	/**
	 * Get the DB store designated for a certain DB table.
	 * A LocalRDBStore will be returned if one is not configured.
	 *
	 * @param $table string Table name
	 * @param $wiki string Wiki ID
	 * @return RDBStore
	 */
	public function getForTable( $table, $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( isset( $this->storedTables[$wiki][$table] ) ) {
			return $this->getExternal( $this->storedTables[$wiki][$table] );
		} else {
			return $this->getInternal( $wiki );
		}
	}

	/**
	 * Get the DB store designated for a certain DB table group.
	 * A LocalRDBStore will be returned if one is not configured.
	 *
	 * @param $name string Group ID
	 * @param $wiki string Wiki ID
	 * @return RDBStore
	 * @throws MWException
	 */
	public function getForTableGroup( $name, $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( isset( $this->tableGroups[$name] ) && count( $this->tableGroups[$name] ) ) {
			// Make sure all tables in this group are on the same store...
			$table = reset( $this->tableGroups[$name] ); // first table
			$store = $this->storeNameForTable( $table, $wiki );
			// Make sure all tables in a each group are in a single store...
			foreach ( $this->tableGroups[$name] as $table ) {
				if ( $this->storeNameForTable( $table, $wiki ) !== $store ) {
					$list = implode( ', ', $this->tableGroups[$name] );
					throw new MWException( "Table(s) '$list' do not map to a single RDB store." );
				}
			}
			return $this->getForTable( $table ); // store object for these tables
		} else {
			throw new MWException( "RDB store table group '$name' is undefined or empty." );
		}
	}

	/**
	 * Get an array of schema information about a table if it is denormalized when sharded
	 *
	 * @param $table string
	 * @return array|null Returns null if table is not denormalized
	 */
	public function getDenormalizedTableInfo( $table ) {
		return isset( $this->denormalizedTables[$table] )
			? $this->denormalizedTables[$table]
			: null;
	}

	/**
	 * @param $table string Store name
	 * @param $wiki string Wiki ID
	 * @return string|false Returns false if not an external store
	 */
	protected function storeNameForTable( $table, $wiki ) {
		return isset( $this->storedTables[$wiki][$table] )
			? $this->storedTables[$wiki][$table]
			: false; // local
	}
}
