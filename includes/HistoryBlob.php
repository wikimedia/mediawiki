<?php
/**
 * Efficient concatenated text storage.
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
 */

/**
 * Base class for general text storage via the "object" flag in old_flags, or
 * two-part external storage URLs. Used for represent efficient concatenated
 * storage, and migration-related pointer objects.
 */
interface HistoryBlob
{
	/**
	 * Adds an item of text, returns a stub object which points to the item.
	 * You must call setLocation() on the stub object before storing it to the
	 * database
	 *
	 * @param $text string
	 *
	 * @return String: the key for getItem()
	 */
	function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
	 *
	 * @param $key string
	 *
	 * @return String or false
	 */
	function getItem( $key );

	/**
	 * Set the "default text"
	 * This concept is an odd property of the current DB schema, whereby each text item has a revision
	 * associated with it. The default text is the text of the associated revision. There may, however,
	 * be other revisions in the same object.
	 *
	 * Default text is not required for two-part external storage URLs.
	 *
	 * @param $text string
	 */
	function setText( $text );

	/**
	 * Get default text. This is called from Revision::getRevisionText()
	 *
	 * @return String
	 */
	function getText();
}

/**
 * Concatenated gzip (CGZ) storage
 * Improves compression ratio by concatenating like objects before gzipping
 */
class ConcatenatedGzipHistoryBlob implements HistoryBlob
{
	public $mVersion = 0, $mCompressed = false, $mItems = array(), $mDefaultHash = '';
	public $mSize = 0;
	public $mMaxSize = 10000000;
	public $mMaxCount = 100;

	/** Constructor */
	public function __construct() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write this kind of history object (ConcatenatedGzipHistoryBlob)\n" );
		}
	}

	/**
	 * @param $text string
	 * @return string
	 */
	public function addItem( $text ) {
		$this->uncompress();
		$hash = md5( $text );
		if ( !isset( $this->mItems[$hash] ) ) {
			$this->mItems[$hash] = $text;
			$this->mSize += strlen( $text );
		}
		return $hash;
	}

	/**
	 * @param $hash string
	 * @return array|bool
	 */
	public function getItem( $hash ) {
		$this->uncompress();
		if ( array_key_exists( $hash, $this->mItems ) ) {
			return $this->mItems[$hash];
		} else {
			return false;
		}
	}

	/**
	 * @param $text string
	 * @return void
	 */
	public function setText( $text ) {
		$this->uncompress();
		$this->mDefaultHash = $this->addItem( $text );
	}

	/**
	 * @return array|bool
	 */
	public function getText() {
		$this->uncompress();
		return $this->getItem( $this->mDefaultHash );
	}

	/**
	 * Remove an item
	 *
	 * @param $hash string
	 */
	public function removeItem( $hash ) {
		$this->mSize -= strlen( $this->mItems[$hash] );
		unset( $this->mItems[$hash] );
	}

	/**
	 * Compress the bulk data in the object
	 */
	public function compress() {
		if ( !$this->mCompressed ) {
			$this->mItems = gzdeflate( serialize( $this->mItems ) );
			$this->mCompressed = true;
		}
	}

	/**
	 * Uncompress bulk data
	 */
	public function uncompress() {
		if ( $this->mCompressed ) {
			$this->mItems = unserialize( gzinflate( $this->mItems ) );
			$this->mCompressed = false;
		}
	}

	/**
	 * @return array
	 */
	function __sleep() {
		$this->compress();
		return array( 'mVersion', 'mCompressed', 'mItems', 'mDefaultHash' );
	}

	function __wakeup() {
		$this->uncompress();
	}

	/**
	 * Helper function for compression jobs
	 * Returns true until the object is "full" and ready to be committed
	 *
	 * @return bool
	 */
	public function isHappy() {
		return $this->mSize < $this->mMaxSize
			&& count( $this->mItems ) < $this->mMaxCount;
	}
}

/**
 * Pointer object for an item within a CGZ blob stored in the text table.
 */
class HistoryBlobStub {
	/**
	 * One-step cache variable to hold base blobs; operations that
	 * pull multiple revisions may often pull multiple times from
	 * the same blob. By keeping the last-used one open, we avoid
	 * redundant unserialization and decompression overhead.
	 */
	protected static $blobCache = array();

	var $mOldId, $mHash, $mRef;

	/**
	 * @param string $hash the content hash of the text
	 * @param $oldid Integer the old_id for the CGZ object
	 */
	function __construct( $hash = '', $oldid = 0 ) {
		$this->mHash = $hash;
	}

	/**
	 * Sets the location (old_id) of the main object to which this object
	 * points
	 */
	function setLocation( $id ) {
		$this->mOldId = $id;
	}

	/**
	 * Sets the location (old_id) of the referring object
	 */
	function setReferrer( $id ) {
		$this->mRef = $id;
	}

	/**
	 * Gets the location of the referring object
	 */
	function getReferrer() {
		return $this->mRef;
	}

	/**
	 * @return string
	 */
	function getText() {
		if ( isset( self::$blobCache[$this->mOldId] ) ) {
			$obj = self::$blobCache[$this->mOldId];
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'text', array( 'old_flags', 'old_text' ), array( 'old_id' => $this->mOldId ) );
			if ( !$row ) {
				return false;
			}
			$flags = explode( ',', $row->old_flags );
			if ( in_array( 'external', $flags ) ) {
				$url = $row->old_text;
				$parts = explode( '://', $url, 2 );
				if ( !isset( $parts[1] ) || $parts[1] == '' ) {
					return false;
				}
				$row->old_text = ExternalStore::fetchFromUrl( $url );

			}
			if ( !in_array( 'object', $flags ) ) {
				return false;
			}

			if ( in_array( 'gzip', $flags ) ) {
				// This shouldn't happen, but a bug in the compress script
				// may at times gzip-compress a HistoryBlob object row.
				$obj = unserialize( gzinflate( $row->old_text ) );
			} else {
				$obj = unserialize( $row->old_text );
			}

			if ( !is_object( $obj ) ) {
				// Correct for old double-serialization bug.
				$obj = unserialize( $obj );
			}

			// Save this item for reference; if pulling many
			// items in a row we'll likely use it again.
			$obj->uncompress();
			self::$blobCache = array( $this->mOldId => $obj );
		}
		return $obj->getItem( $this->mHash );
	}

	/**
	 * Get the content hash
	 *
	 * @return string
	 */
	function getHash() {
		return $this->mHash;
	}
}

/**
 * To speed up conversion from 1.4 to 1.5 schema, text rows can refer to the
 * leftover cur table as the backend. This avoids expensively copying hundreds
 * of megabytes of data during the conversion downtime.
 *
 * Serialized HistoryBlobCurStub objects will be inserted into the text table
 * on conversion if $wgFastSchemaUpgrades is set to true.
 */
class HistoryBlobCurStub {
	var $mCurId;

	/**
	 * @param $curid Integer: the cur_id pointed to
	 */
	function __construct( $curid = 0 ) {
		$this->mCurId = $curid;
	}

	/**
	 * Sets the location (cur_id) of the main object to which this object
	 * points
	 *
	 * @param $id int
	 */
	function setLocation( $id ) {
		$this->mCurId = $id;
	}

	/**
	 * @return string|bool
	 */
	function getText() {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'cur', array( 'cur_text' ), array( 'cur_id' => $this->mCurId ) );
		if ( !$row ) {
			return false;
		}
		return $row->cur_text;
	}
}
