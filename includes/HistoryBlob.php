<?php
/**
 *
 * @package MediaWiki
 */

/**
 * Pure virtual parent
 * @package MediaWiki
 */
class HistoryBlob
{
	/**
	 * setMeta and getMeta currently aren't used for anything, I just thought
	 * they might be useful in the future.
	 * @param $meta String: a single string.
	 */
	function setMeta( $meta ) {}

	/**
	 * setMeta and getMeta currently aren't used for anything, I just thought
	 * they might be useful in the future.
	 * Gets the meta-value
	 */
	function getMeta() {}

	/**
	 * Adds an item of text, returns a stub object which points to the item.
	 * You must call setLocation() on the stub object before storing it to the
	 * database
	 */
	function addItem() {}

	/**
	 * Get item by hash
	 */
	function getItem( $hash ) {}

	# Set the "default text"
	# This concept is an odd property of the current DB schema, whereby each text item has a revision
	# associated with it. The default text is the text of the associated revision. There may, however,
	# be other revisions in the same object
	function setText() {}

	/**
	 * Get default text. This is called from Revision::getRevisionText()
	 */
	function getText() {}
}

/**
 * The real object
 * @package MediaWiki
 */
class ConcatenatedGzipHistoryBlob extends HistoryBlob
{
	/* private */ var $mVersion = 0, $mCompressed = false, $mItems = array(), $mDefaultHash = '';
	/* private */ var $mFast = 0, $mSize = 0;

	function ConcatenatedGzipHistoryBlob() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write this kind of history object (ConcatenatedGzipHistoryBlob)\n" );
		}
	}

	/** @todo document */
	function setMeta( $metaData ) {
		$this->uncompress();
		$this->mItems['meta'] = $metaData;
	}

	/** @todo document */
	function getMeta() {
		$this->uncompress();
		return $this->mItems['meta'];
	}

	/** @todo document */
	function addItem( $text ) {
		$this->uncompress();
		$hash = md5( $text );
		$this->mItems[$hash] = $text;
		$this->mSize += strlen( $text );

		$stub = new HistoryBlobStub( $hash );
		return $stub;
	}

	/** @todo document */
	function getItem( $hash ) {
		$this->uncompress();
		if ( array_key_exists( $hash, $this->mItems ) ) {
			return $this->mItems[$hash];
		} else {
			return false;
		}
	}

	/** @todo document */
	function removeItem( $hash ) {
		$this->mSize -= strlen( $this->mItems[$hash] );
		unset( $this->mItems[$hash] );
	}

	/** @todo document */
	function compress() {
		if ( !$this->mCompressed  ) {
			$this->mItems = gzdeflate( serialize( $this->mItems ) );
			$this->mCompressed = true;
		}
	}

	/** @todo document */
	function uncompress() {
		if ( $this->mCompressed ) {
			$this->mItems = unserialize( gzinflate( $this->mItems ) );
			$this->mCompressed = false;
		}
	}

	/** @todo document */
	function getText() {
		$this->uncompress();
		return $this->getItem( $this->mDefaultHash );
	}

	/** @todo document */
	function setText( $text ) {
		$this->uncompress();
		$stub = $this->addItem( $text );
		$this->mDefaultHash = $stub->mHash;
	}

	/** @todo document */
	function __sleep() {
		$this->compress();
		return array( 'mVersion', 'mCompressed', 'mItems', 'mDefaultHash' );
	}

	/** @todo document */
	function __wakeup() {
		$this->uncompress();
	}

	/**
	 * Determines if this object is happy
	 */
	function isHappy( $maxFactor, $factorThreshold ) {
		if ( count( $this->mItems ) == 0 ) {
			return true;
		}
		if ( !$this->mFast ) {
			$this->uncompress();
			$record = serialize( $this->mItems );
			$size = strlen( $record );
			$avgUncompressed = $size / count( $this->mItems );
			$compressed = strlen( gzdeflate( $record ) );

			if ( $compressed < $factorThreshold * 1024 ) {
				return true;
			} else {
				return $avgUncompressed * $maxFactor < $compressed;
			}
		} else {
			return count( $this->mItems ) <= 10;
		}
	}
}


/**
 * One-step cache variable to hold base blobs; operations that
 * pull multiple revisions may often pull multiple times from
 * the same blob. By keeping the last-used one open, we avoid
 * redundant unserialization and decompression overhead.
 */
global $wgBlobCache;
$wgBlobCache = array();


/**
 * @package MediaWiki
 */
class HistoryBlobStub {
	var $mOldId, $mHash, $mRef;

	/** @todo document */
	function HistoryBlobStub( $hash = '', $oldid = 0 ) {
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

	/** @todo document */
	function getText() {
		$fname = 'HistoryBlob::getText';
		global $wgBlobCache;
		if( isset( $wgBlobCache[$this->mOldId] ) ) {
			$obj = $wgBlobCache[$this->mOldId];
		} else {
			$dbr =& wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'text', array( 'old_flags', 'old_text' ), array( 'old_id' => $this->mOldId ) );
			if( !$row ) {
				return false;
			}
			$flags = explode( ',', $row->old_flags );
			if( in_array( 'external', $flags ) ) {
				$url=$row->old_text;
				@list($proto,$path)=explode('://',$url,2);
				if ($path=="") {
					wfProfileOut( $fname );
					return false;
				}
				require_once('ExternalStore.php');
				$row->old_text=ExternalStore::fetchFromUrl($url);

			}
			if( !in_array( 'object', $flags ) ) {
				return false;
			}

			if( in_array( 'gzip', $flags ) ) {
				// This shouldn't happen, but a bug in the compress script
				// may at times gzip-compress a HistoryBlob object row.
				$obj = unserialize( gzinflate( $row->old_text ) );
			} else {
				$obj = unserialize( $row->old_text );
			}

			if( !is_object( $obj ) ) {
				// Correct for old double-serialization bug.
				$obj = unserialize( $obj );
			}

			// Save this item for reference; if pulling many
			// items in a row we'll likely use it again.
			$obj->uncompress();
			$wgBlobCache = array( $this->mOldId => $obj );
		}
		return $obj->getItem( $this->mHash );
	}

	/** @todo document */
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
 *
 * @package MediaWiki
 */
class HistoryBlobCurStub {
	var $mCurId;

	/** @todo document */
	function HistoryBlobCurStub( $curid = 0 ) {
		$this->mCurId = $curid;
	}

	/**
	 * Sets the location (cur_id) of the main object to which this object
	 * points
	 */
	function setLocation( $id ) {
		$this->mCurId = $id;
	}

	/** @todo document */
	function getText() {
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'cur', array( 'cur_text' ), array( 'cur_id' => $this->mCurId ) );
		if( !$row ) {
			return false;
		}
		return $row->cur_text;
	}
}


?>
