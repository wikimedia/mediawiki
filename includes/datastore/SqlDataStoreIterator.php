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
 * @file
 */

/**
 * Iterator returned by SqlDataSore::getByPrefix()
 * @since 1.24
 */
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
