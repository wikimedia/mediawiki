<?php
/**
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
 * @since 1.23
 *
 * @file
 */

/**
 * DataStore that allows to operate over a set of several stores as one.
 * The underlying stores must be already registered in $wgDataStores.
 */
class MultiDataStore extends DataStore {
	/* Store parameters */

	/**
	 * @var array: Array of underlying store names
	 */
	protected $stores = array();

	/**
	 * @var bool: Whether
	 */
	protected $saveToAll = true;

	/**
	 * @var bool:
	 */
	protected $getFromAll = true;

	/* End store parameters */

	/**
	 * @var array: Underlying store objects
	 */
	private $storeObjects = array();

	public function __construct( array $config  ) {
		parent::__construct( $config );
		if ( !$this->stores ) {
			throw new MWException( __CLASS__ . ": no underlying stores configured. Use 'stores' parameter to configure." );
		}
		foreach ( $this->stores as $name ) {
			$this->storeObjects[] = DataStore::getStore( $name );
		}
	}

	private function getReadStores() {
		if ( $this->getFromAll ) {
			return $this->storeObjects;
		}
		return array( $this->storeObjects[0] );
	}

	private function getWriteStores() {
		if ( $this->saveToAll ) {
			return $this->storeObjects;
		}
		return array( $this->storeObjects[0] );
	}

	/**
	 * Returns value for a given key or null if not found
	 *
	 * @param string $key: Data key
	 * @param bool $latest: Whether a replicated or distributed store should ensure that the data returned is latest
	 *
	 * @return mixed
	 */
	public function get( $key, $latest = false ) {
		foreach ( $this->getReadStores() as $store ) {
			$result = $store->get( $key, $latest );
			if ( !is_null( $result ) ) {
				return $result;
			}
		}
		return null;
	}

	/**
	 * Sets value for a given key
	 *
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
		foreach ( $this->getWriteStores() as $store ) {
			$store->set( $key, $value );
		}
	}

	/**
	 * Returns all values whose keys start with a given string
	 * Returns data only from first underlying store
	 *
	 * @param string $prefix
	 * @param callable $callback: Function that will receive data. Example: function( $key, $value )
	 * @param bool $latest: Whether a replicated or distributed store should ensure that the data returned is latest
	 */
	public function getByPrefix( $prefix, $callback, $latest = false ) {
		$this->storeObjects[0]->getByPrefix( $prefix, $callback, $latest );
	}

	/**
	 * @param $key
	 */
	public function delete( $key ) {
		foreach ( $this->getWriteStores() as $store ) {
			$store->delete( $key );
		}
	}

	/**
	 * @param $prefix
	 */
	protected function deleteByPrefixInternal( $prefix ) {
		foreach ( $this->getWriteStores() as $store ) {
			$store->deleteByPrefixInternal( $prefix );
		}
	}
}
