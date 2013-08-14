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
	 * @var int Batch size for certain operations like getByPrefix
	 */
	protected $batchSize = 500;

	/**
	 * @var string|bool Name of wiki which DB to use or false for current wiki
	 */
	protected $wiki = false;

	/**
	 * @var string|false DB cluster name for LBFactory or false for default
	 */
	protected $cluster = false;

	/* End store parameters */

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

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
		if ( !$data ) {
			return;
		}
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

	public function getByPrefix( $prefix, $latest = false ) {
		$db = $this->getDB( $latest );
		return new SqlDataStoreIterator( $db, $prefix, $this->batchSize );
	}

	public function delete( $keys ) {
		if ( !$keys ) {
			return;
		}
		wfProfileIn( __METHOD__ );
		$dbw = $this->getDB( true );
		$dbw->delete( 'store', array( 'store_key' => $keys ), __METHOD__ );
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
	 * @param int $master Whether this connection should be master
	 *
	 * @return DatabaseBase
	 */
	protected function getDB( $master ) {
		if ( !$this->loadBalancer ) {
			if ( $this->cluster !== false ) {
				$this->loadBalancer = wfGetLBFactory()->getExternalLB( $this->cluster );
			} else {
				$this->loadBalancer = wfGetLB( $this->wiki );
			}
		}
		return $this->loadBalancer->getConnection(
			$master ? DB_MASTER : DB_SLAVE,
			'datastore',
			$this->wiki
		);
	}
}

class SqlDataStoreIterator implements Iterator {
	/** @var DatabaseBase */
	private $db;
	/** @var string */
	private $prefix;
	/** @var int */
	private $batchSize;
	/** @var string|null */
	private $lastKey;
	/** @var ResultWrapper */
	private $result;

	public function __construct( DatabaseBase $db, $prefix, $batchSize ) {
		$this->db = $db;
		$this->prefix = $prefix;
		$this->batchSize = $batchSize;
		$this->rewind();
	}

	private function nextBatch() {
		wfProfileIn( __METHOD__ );
		$db = $this->db;
		$conds = array(
			'store_key ' . $db->buildLike( $this->prefix, $db->anyString() )
		);
		if ( $this->lastKey !== null ) {
			$conds[] = 'store_key > ' . $db->addQuotes( $this->lastKey );
		}

		$this->result = $db->select(
			'store',
			array( 'store_key', 'store_value' ),
			$conds,
			__METHOD__,
			array( 'ORDER BY' => 'store_key', 'LIMIT' => $this->batchSize )
		);
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Stored data
	 */
	public function current() {
		$current = $this->result->current();
		return $current ? $current->store_value : null;
	}

	/**
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next() {
		if ( $this->result->valid() ) {
			$this->lastKey = $this->result->current()->store_key;
			$this->result->next();
			if ( !$this->result->valid() && $this->result->numRows() == $this->batchSize ) {
				$this->nextBatch();
			}
		}
	}

	/**
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		$current = $this->result->current();
		return $current ? $current->store_key : null;
	}

	/**
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid() {
		return $this->result->valid();
	}

	/**
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind() {
		$this->lastKey = null;
		$this->nextBatch();
	}
}
