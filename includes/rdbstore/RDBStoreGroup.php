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
	protected $storeConf = array(); // (store name => config array)
	/** @var Array */
	protected $storedTables = array(); // (table name => extenral store name)
	/** @var Array */
	protected $tableGroups = array(); // (group name => table list)
	/** @var Array */
	protected $tableGroupStores = array(); // (group name => extenral store name)

	/** @var Array */
	protected $lclStoreInstances = array();  // (wiki ID => RDBStore)
	/** @var Array */
	protected $extStoreInstances = array();  // (store name => RDBStore)

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
		global $wgRDBStores, $wgRDBStoredTables, $wgRDBStoreTableGroups;

		$this->storeConf    = $wgRDBStores;
		$this->storedTables = $wgRDBStoredTables;
		$this->tableGroups  = $wgRDBStoreTableGroups;
		foreach ( $this->tableGroups as $group => $tables ) {
			if ( count( $tables ) ) {
				$invalid = false; // all tables not in one store?
				// Make sure all tables in this group are on the same store...
				$store = $this->storeNameForTable( reset( $tables ) );
				foreach ( $tables as $table ) {
					if ( $this->storeNameForTable( $table ) !== $store ) {
						$invalid = true;
						$this->tableGroupStores[$group] = false; // broken
						break; // go to next group; lazily defer the exception
					}
				}
				if ( !$invalid && is_string( $store ) ) { // false => local store
					$this->tableGroupStores[$group] = $store; // string
				}
			} else {
				throw new MWException( "RDB store table group '$group' is empty." );
			}
		}
	}

	/**
	 * Get a DB store on the main cluster of a wiki.
	 * This uses a multi-singleton pattern to improve transactions.
	 *
	 * @param $wiki string Wiki ID
	 * @return LocalRDBStore
	 */
	public function getInternal( $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( $this->lclStoreInstances[$wiki] ) ) {
			$this->lclStoreInstances[$wiki] = new LocalRDBStore( array( 'wiki' => $wiki ) );
		}
		return $this->lclStoreInstances[$wiki];
	}

	/**
	 * Get an external DB store by name.
	 * This uses a multi-singleton pattern to improve transactions.
	 *
	 * @param $name string Storage group ID
	 * @return ExternalRDBStore
	 */
	public function getExternal( $name ) {
		if ( !isset( $this->extStoreInstances[$name] ) ) {
			if ( !isset( $this->storeConf[$name] ) ) {
				throw new MWException( "No DB store defined with the name '$name'." );
			}
			$this->storeConf[$name]['name'] = $name;
			$this->extStoreInstances[$name] = new ExternalRDBStore( $this->storeConf[$name] );
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
		if ( isset( $this->storedTables[$table] ) ) {
			return $this->getExternal( $this->storedTables[$table] );
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
	 */
	public function getForTableGroup( $name, $wiki = false ) {
		if ( isset( $this->tableGroups[$name] ) ) {
			if ( isset( $this->tableGroupStores[$name] ) ) { // external store
				if ( $this->tableGroupStores[$name] === false ) {
					throw new MWException(
						"RDB store table group '$name' does not map to a single store." );
				} else {
					return $this->getExternal( $this->tableGroupStores[$name] );
				}
			} else { // local store
				return $this->getInternal( $wiki );
			}
		} else {
			throw new MWException( "No DB table group defined with the name '$name'." );
		}
	}

	/**
	 * @param $table string Store name
	 * @return string|false Returns false if not an external store
	 */
	protected function storeNameForTable( $table ) {
		return isset( $this->storedTables[$table] ) ? $this->storedTables[$table] : false;
	}
}
