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
	 *
	 * @return String: the key for getItem()
	 */
	function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
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
	public function ConcatenatedGzipHistoryBlob() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write this kind of history object (ConcatenatedGzipHistoryBlob)\n" );
		}
	}

	public function addItem( $text ) {
		$this->uncompress();
		$hash = md5( $text );
		if ( !isset( $this->mItems[$hash] ) ) {
			$this->mItems[$hash] = $text;
			$this->mSize += strlen( $text );
		}
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
	 * @param $hash Strng: the content hash of the text
	 * @param $oldid Integer: the old_id for the CGZ object
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

	function getText() {
		$fname = 'HistoryBlobStub::getText';

		if( isset( self::$blobCache[$this->mOldId] ) ) {
			$obj = self::$blobCache[$this->mOldId];
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
			self::$blobCache = array( $this->mOldId => $obj );
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
	 * @param $curid Integer: the cur_id pointed to
	 */
	function __construct( $curid = 0 ) {
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

	/** Total uncompressed size */
	var $mSize = 0;

	/** 
	 * Array of diffs. If a diff D from A to B is notated D = B - A, and Z is 
	 * an empty string:
	 *
	 *              { item[map[i]] - item[map[i-1]]   where i > 0
	 *    diff[i] = { 
	 *              { item[map[i]] - Z                where i = 0
	 */
	var $mDiffs;

	/** The diff map, see above */
	var $mDiffMap;

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

	/**
	 * The maximum uncompressed size before the object becomes sad
	 * Should be less than max_allowed_packet
	 */
	var $mMaxSize = 10000000;

	/**
	 * The maximum number of text items before the object becomes sad
	 */
	var $mMaxCount = 100;
	
	/** Constants from xdiff.h */
	const XDL_BDOP_INS = 1;
	const XDL_BDOP_CPY = 2;
	const XDL_BDOP_INSB = 3;

	function __construct() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write DiffHistoryBlob\n" );
		}
	}

	function addItem( $text ) {
		if ( $this->mFrozen ) {
			throw new MWException( __METHOD__.": Cannot add more items after sleep/wakeup" );
		}

		$this->mItems[] = $text;
		$this->mSize += strlen( $text );
		$this->mDiffs = null; // later
		return count( $this->mItems ) - 1;
	}

	function getItem( $key ) {
		return $this->mItems[$key];
	}

	function setText( $text ) {
		$this->mDefaultKey = $this->addItem( $text );
	}

	function getText() {
		return $this->getItem( $this->mDefaultKey );
	}

	function compress() {
		if ( !function_exists( 'xdiff_string_rabdiff' ) ){ 
			throw new MWException( "Need xdiff 1.5+ support to write DiffHistoryBlob\n" );
		}
		if ( isset( $this->mDiffs ) ) {
			// Already compressed
			return;
		}
		if ( !count( $this->mItems ) ) {
			// Empty
			return;
		}

		// Create two diff sequences: one for main text and one for small text
		$sequences = array(
			'small' => array(
				'tail' => '',
				'diffs' => array(),
				'map' => array(),
			),
			'main' => array(
				'tail' => '',
				'diffs' => array(),
				'map' => array(),
			),
		);
		$smallFactor = 0.5;

		for ( $i = 0; $i < count( $this->mItems ); $i++ ) {
			$text = $this->mItems[$i];
			if ( $i == 0 ) {
				$seqName = 'main';
			} else {
				$mainTail = $sequences['main']['tail'];
				if ( strlen( $text ) < strlen( $mainTail ) * $smallFactor ) {
					$seqName = 'small';
				} else {
					$seqName = 'main';
				}
			}
			$seq =& $sequences[$seqName];
			$tail = $seq['tail'];
			$diff = $this->diff( $tail, $text );
			$seq['diffs'][] = $diff;
			$seq['map'][] = $i;
			$seq['tail'] = $text;
		}
		unset( $seq ); // unlink dangerous alias

		// Knit the sequences together
		$tail = '';
		$this->mDiffs = array();
		$this->mDiffMap = array();
		foreach ( $sequences as $seq ) {
			if ( !count( $seq['diffs'] ) ) {
				continue;
			}
			if ( $tail === '' ) {
				$this->mDiffs[] = $seq['diffs'][0];
			} else {
				$head = $this->patch( '', $seq['diffs'][0] );
				$this->mDiffs[] = $this->diff( $tail, $head );
			}
			$this->mDiffMap[] = $seq['map'][0];
			for ( $i = 1; $i < count( $seq['diffs'] ); $i++ ) {
				$this->mDiffs[] = $seq['diffs'][$i];
				$this->mDiffMap[] = $seq['map'][$i];
			}
			$tail = $seq['tail'];
		}
	}

	function diff( $t1, $t2 ) {
		# Need to do a null concatenation with warnings off, due to bugs in the current version of xdiff
		# "String is not zero-terminated"
		wfSuppressWarnings();
		$diff = xdiff_string_rabdiff( $t1, $t2 ) . '';
		wfRestoreWarnings();
		return $diff;
	}

	function patch( $base, $diff ) {
		if ( function_exists( 'xdiff_string_bpatch' ) ) {
			wfSuppressWarnings();
			$text = xdiff_string_bpatch( $base, $diff ) . '';
			wfRestoreWarnings();
			return $text;
		}

		# Pure PHP implementation

		$header = unpack( 'Vofp/Vcsize', substr( $diff, 0, 8 ) );
		
		# Check the checksum if mhash is available
		if ( extension_loaded( 'mhash' ) ) {
			$ofp = mhash( MHASH_ADLER32, $base );
			if ( $ofp !== substr( $diff, 0, 4 ) ) {
				wfDebug( __METHOD__. ": incorrect base checksum\n" );
				return false;
			}
		}
		if ( $header['csize'] != strlen( $base ) ) {
			wfDebug( __METHOD__. ": incorrect base length\n" );
			return false;
		}
		
		$p = 8;
		$out = '';
		while ( $p < strlen( $diff ) ) {
			$x = unpack( 'Cop', substr( $diff, $p, 1 ) );
			$op = $x['op'];
			++$p;
			switch ( $op ) {
			case self::XDL_BDOP_INS:
				$x = unpack( 'Csize', substr( $diff, $p, 1 ) );
				$p++;
				$out .= substr( $diff, $p, $x['size'] );
				$p += $x['size'];
				break;
			case self::XDL_BDOP_INSB:
				$x = unpack( 'Vcsize', substr( $diff, $p, 4 ) );
				$p += 4;
				$out .= substr( $diff, $p, $x['csize'] );
				$p += $x['csize'];
				break;
			case self::XDL_BDOP_CPY:
				$x = unpack( 'Voff/Vcsize', substr( $diff, $p, 8 ) );
				$p += 8;
				$out .= substr( $base, $x['off'], $x['csize'] );
				break;
			default:
				wfDebug( __METHOD__.": invalid op\n" );
				return false;
			}
		}
		return $out;
	}

	function uncompress() {
		if ( !$this->mDiffs ) {
			return;
		}
		$tail = '';
		for ( $diffKey = 0; $diffKey < count( $this->mDiffs ); $diffKey++ ) {
			$textKey = $this->mDiffMap[$diffKey];
			$text = $this->patch( $tail, $this->mDiffs[$diffKey] );
			$this->mItems[$textKey] = $text;
			$tail = $text;
		}
	}

	function __sleep() {
		$this->compress();
		if ( !count( $this->mItems ) ) {
			// Empty object
			$info = false;
		} else {
			// Take forward differences to improve the compression ratio for sequences
			$map = '';
			$prev = 0;
			foreach ( $this->mDiffMap as $i ) {
				if ( $map !== '' ) {
					$map .= ',';
				}
				$map .= $i - $prev;
				$prev = $i;
			}
			$info = array(
				'diffs' => $this->mDiffs,
				'map' => $map
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
		$this->mDiffs = $info['diffs'];
		if ( isset( $info['base'] ) ) {
			// Old format
			$this->mDiffMap = range( 0, count( $this->mDiffs ) - 1 );
			array_unshift( $this->mDiffs, 
				pack( 'VVCV', 0, 0, self::XDL_BDOP_INSB, strlen( $info['base'] ) ) .
				$info['base'] );
		} else {
			// New format
			$map = explode( ',', $info['map'] );
			$cur = 0;
			$this->mDiffMap = array();
			foreach ( $map as $i ) {
				$cur += $i;
				$this->mDiffMap[] = $cur;
			}
		}
		$this->uncompress();
	}

	/**
	 * Helper function for compression jobs
	 * Returns true until the object is "full" and ready to be committed
	 */
	function isHappy() {
		return $this->mSize < $this->mMaxSize 
			&& count( $this->mItems ) < $this->mMaxCount;
	}

}
