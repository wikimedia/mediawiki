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
 * DataStore that uses database as a storage backend.
 * It is used by MediaWiki by default.
 */
class SqlDataStore extends DataStore {
	/* Store parameters */

	/**
	 * @var int: Batch size for certain operations like getByPrefix
	 */
	protected $batchSize = 500;

	/**
	 * @var string|bool: Name of wiki which DB to use or false for current wiki
	 */
	protected $wiki = false;

	/* End store parameters */

	public function get( $key, $latest = false ) {
		wfProfileIn( __METHOD__ );
		$db = $this->getDB( $latest );
		$res = $db->selectField( 'store', 'store_value', array( 'store_key' => $key ), __METHOD__ );
		wfProfileOut( __METHOD__ );

		return $res === false ? null : $res;
	}

	public function getMulti( array $keys, $latest = false ) {
		wfProfileIn( __METHOD__ );
		$db = $this->getDB( $latest );
		$res = $db->select( 'store',
			array( 'store_key', 'store_value' ),
			array( 'store_key' => $keys ),
			__METHOD__
		);
		$result = array();
		foreach ( $res as $row ) {
			$result[$row->store_key] = $row->store_value;
		}
		wfProfileOut( __METHOD__ );
		return $result;
	}

	public function set( $key, $value ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->replace( 'store',
			array( 'store_key' ),
			array( 'store_key' => $key, 'store_value' => $value ),
			__METHOD__
		);
		wfProfileOut( __METHOD__ );
	}

	public  function setMulti( array $data ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$input = array();
		foreach ( $data as $key => $value ) {
			$input[] = array( 'store_key' => $key, 'store_value' => $value );
		}
		$dbw->replace( 'store',
			array( 'store_key' ),
			$input,
			__METHOD__
		);
		wfProfileOut( __METHOD__ );
	}

	public function getByPrefix( $prefix, $callback, $latest = false ) {
		wfProfileIn( __METHOD__ );

		$db = $this->getDB( $latest );
		do {
			$res = $db->select( 'store',
				array( 'store_key', 'store_value' ),
				array( "store_key >= {$db->addQuotes( $prefix )}" ),
				__METHOD__,
				array( 'ORDER BY' => 'store_key', 'LIMIT' => $this->batchSize + 1 )
			);
			$count = 0;
			foreach( $res as $row ) {
				$prefix = $row->store_key;
				if( ++$count > $this->batchSize ) {
					break;
				}
				call_user_func( $callback, $row->store_key, $row->store_value );
			}
		} while ( $count > $this->batchSize );

		wfProfileOut( __METHOD__ );
	}

	public function delete( $key ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->delete( 'store', array( 'store_key' => $key ), __METHOD__ );
		wfProfileOut( __METHOD__ );
	}

	protected function deleteByPrefixInternal( $prefix ) {
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->delete( 'store', array( 'store_key' . $dbw->buildLike( $prefix, $dbw->anyString() ) ), __METHOD__ );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Returns a database connection
	 *
	 * @param int $master: Whether this connection should be master
	 *
	 * @return DatabaseBase
	 */
	protected function getDB( $master ) {
		if ( $master ) {
			return wfGetDB( DB_MASTER, 'datastore', $this->wiki );
		}
		return wfGetDB( DB_SLAVE, 'datastore', $this->wiki );
	}
}
