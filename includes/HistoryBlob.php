<?php

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
	 * Returns the key for getItem()
	 */
	public function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
	 */
	public function getItem( $key );

	/**
	 * Set the "default text"
	 * This concept is an odd property of the current DB schema, whereby each text item has a revision
	 * associated with it. The default text is the text of the associated revision. There may, however,
	 * be other revisions in the same object.
	 *
	 * Default text is not required for two-part external storage URLs.
	 */
	public function setText( $text );

	/**
	 * Get default text. This is called from Revision::getRevisionText()
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
	public $mFast = 0, $mSize = 0;

	/** Constructor */
	public function ConcatenatedGzipHistoryBlob() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write this kind of history object (ConcatenatedGzipHistoryBlob)\n" );
		}
	}

	public function addItem( $text ) {
		$this->uncompress();
		$hash = md5( $text );
		$this->mItems[$hash] = $text;
		$this->mSize += strlen( $text );

		return $hash;
	}

	public function getItem( $hash ) {
		$this->uncompress();
		if ( array_key_exists( $hash, $this->mItems ) ) {
			return $this->mItems[$hash];
		} else {
			return false;
		}
	}

	public function setText( $text ) {
		$this->uncompress();
		$this->mDefaultHash = $this->addItem( $text );
	}

	public function getText() {
		$this->uncompress();
		return $this->getItem( $this->mDefaultHash );
	}

	/**
	 * Remove an item
	 */
	public function removeItem( $hash ) {
		$this->mSize -= strlen( $this->mItems[$hash] );
		unset( $this->mItems[$hash] );
	}

	/**
	 * Compress the bulk data in the object
	 */
	public function compress() {
		if ( !$this->mCompressed  ) {
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
	 */
	public function isHappy( $maxFactor, $factorThreshold ) {
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
 * Pointer object for an item within a CGZ blob stored in the text table.
 */
class HistoryBlobStub {
	var $mOldId, $mHash, $mRef;

	/**
	 * @param string $hash The content hash of the text
	 * @param integer $oldid The old_id for the CGZ object
	 */
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

	function getText() {
		$fname = 'HistoryBlobStub::getText';
		global $wgBlobCache;
		if( isset( $wgBlobCache[$this->mOldId] ) ) {
			$obj = $wgBlobCache[$this->mOldId];
		} else {
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'text', array( 'old_flags', 'old_text' ), array( 'old_id' => $this->mOldId ) );
			if( !$row ) {
				return false;
			}
			$flags = explode( ',', $row->old_flags );
			if( in_array( 'external', $flags ) ) {
				$url=$row->old_text;
				@list( /* $proto */ ,$path)=explode('://',$url,2);
				if ($path=="") {
					wfProfileOut( $fname );
					return false;
				}
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

	/**
	 * Get the content hash
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
	 * @param integer $curid The cur_id pointed to
	 */
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

	function getText() {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'cur', array( 'cur_text' ), array( 'cur_id' => $this->mCurId ) );
		if( !$row ) {
			return false;
		}
		return $row->cur_text;
	}
}

/**
 * Diff-based history compression
 * Requires xdiff 1.5+ and zlib
 */
class DiffHistoryBlob implements HistoryBlob {
	/** Uncompressed item cache */
	var $mItems = array();

	/** 
	 * Array of diffs, where $this->mDiffs[0] is the diff between 
	 * $this->mDiffs[0] and $this->mDiffs[1]
	 */
	var $mDiffs = array();

	/**
	 * The key for getText()
	 */
	var $mDefaultKey;

	/**
	 * Compressed storage
	 */
	var $mCompressed;

	/**
	 * True if the object is locked against further writes
	 */
	var $mFrozen = false;


	function __construct() {
		if ( !function_exists( 'xdiff_string_bdiff' ) ){ 
			throw new MWException( "Need xdiff 1.5+ support to read or write DiffHistoryBlob\n" );
		}
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write DiffHistoryBlob\n" );
		}
	}

	function addItem( $text ) {
		if ( $this->mFrozen ) {
			throw new MWException( __METHOD__.": Cannot add more items after sleep/wakeup" );
		}

		$this->mItems[] = $text;
		$i = count( $this->mItems ) - 1;
		if ( $i > 0 ) {
			# Need to do a null concatenation with warnings off, due to bugs in the current version of xdiff
			# "String is not zero-terminated"
			wfSuppressWarnings();
			$this->mDiffs[] = xdiff_string_bdiff( $this->mItems[$i-1], $text ) . '';
			wfRestoreWarnings();
		}
		return $i;
	}

	function getItem( $key ) {
		if ( $key > count( $this->mDiffs ) + 1 ) {
			return false;
		}
		$key = intval( $key );
		if ( $key == 0 ) {
			return $this->mItems[0];
		}

		$last = count( $this->mItems ) - 1;
		for ( $i = $last + 1; $i <= $key; $i++ ) {
			# Need to do a null concatenation with warnings off, due to bugs in the current version of xdiff
			# "String is not zero-terminated"
			wfSuppressWarnings();
			$this->mItems[$i] = xdiff_string_bpatch( $this->mItems[$i - 1], $this->mDiffs[$i - 1] ) . '';
			wfRestoreWarnings();
		}
		return $this->mItems[$key];
	}

	function setText( $text ) {
		$this->mDefaultKey = $this->addItem( $text );
	}

	function getText() {
		return $this->getItem( $this->mDefaultKey );
	}

	function __sleep() {
		if ( !isset( $this->mItems[0] ) ) {
			// Empty object
			$info = false;
		} else {
			$info = array(
				'base' => $this->mItems[0],
				'diffs' => $this->mDiffs
			);
		}
		if ( isset( $this->mDefaultKey ) ) {
			$info['default'] = $this->mDefaultKey;
		}
		$this->mCompressed = gzdeflate( serialize( $info ) );
		return array( 'mCompressed' );
	}

	function __wakeup() {
		// addItem() doesn't work if mItems is partially filled from mDiffs
		$this->mFrozen = true;
		$info = unserialize( gzinflate( $this->mCompressed ) );
		unset( $this->mCompressed );

		if ( !$info ) {
			// Empty object
			return;
		}

		if ( isset( $info['default'] ) ) {
			$this->mDefaultKey = $info['default'];
		}
		$this->mItems[0] = $info['base'];
		$this->mDiffs = $info['diffs'];
	}
}
