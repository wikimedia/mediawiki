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
interface HistoryBlob {
	/**
	 * Adds an item of text, returns a stub object which points to the item.
	 * You must call setLocation() on the stub object before storing it to the
	 * database
	 *
	 * @param string $text
	 *
	 * @return string The key for getItem()
	 */
	function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
	 *
	 * @param string $key
	 *
	 * @return string|bool
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
	 * @param string $text
	 */
	function setText( $text );

	/**
	 * Get default text. This is called from Revision::getRevisionText()
	 *
	 * @return string
	 */
	function getText();
}

/**
 * Concatenated gzip (CGZ) storage
 * Improves compression ratio by concatenating like objects before gzipping
 */
class ConcatenatedGzipHistoryBlob implements HistoryBlob {
	public $mVersion = 0, $mCompressed = false, $mItems = [], $mDefaultHash = '';
	public $mSize = 0;
	public $mMaxSize = 10000000;
	public $mMaxCount = 100;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write this "
				. "kind of history object (ConcatenatedGzipHistoryBlob)\n" );
		}
	}

	/**
	 * @param string $text
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
	 * @param string $hash
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
	 * @param string $text
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
	 * @param string $hash
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
		return [ 'mVersion', 'mCompressed', 'mItems', 'mDefaultHash' ];
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
	 * @var array One-step cache variable to hold base blobs; operations that
	 * pull multiple revisions may often pull multiple times from the same
	 * blob. By keeping the last-used one open, we avoid redundant
	 * unserialization and decompression overhead.
	 */
	protected static $blobCache = [];

	/** @var int */
	public $mOldId;

	/** @var string */
	public $mHash;

	/** @var string */
	public $mRef;

	/**
	 * @param string $hash The content hash of the text
	 * @param int $oldid The old_id for the CGZ object
	 */
	function __construct( $hash = '', $oldid = 0 ) {
		$this->mHash = $hash;
	}

	/**
	 * Sets the location (old_id) of the main object to which this object
	 * points
	 * @param int $id
	 */
	function setLocation( $id ) {
		$this->mOldId = $id;
	}

	/**
	 * Sets the location (old_id) of the referring object
	 * @param string $id
	 */
	function setReferrer( $id ) {
		$this->mRef = $id;
	}

	/**
	 * Gets the location of the referring object
	 * @return string
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
			$row = $dbr->selectRow(
				'text',
				[ 'old_flags', 'old_text' ],
				[ 'old_id' => $this->mOldId ]
			);

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
				$row->old_text = ExternalStore::fetchFromURL( $url );

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
			self::$blobCache = [ $this->mOldId => $obj ];
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
 * on conversion if $wgLegacySchemaConversion is set to true.
 */
class HistoryBlobCurStub {
	/** @var int */
	public $mCurId;

	/**
	 * @param int $curid The cur_id pointed to
	 */
	function __construct( $curid = 0 ) {
		$this->mCurId = $curid;
	}

	/**
	 * Sets the location (cur_id) of the main object to which this object
	 * points
	 *
	 * @param int $id
	 */
	function setLocation( $id ) {
		$this->mCurId = $id;
	}

	/**
	 * @return string|bool
	 */
	function getText() {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'cur', [ 'cur_text' ], [ 'cur_id' => $this->mCurId ] );
		if ( !$row ) {
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
	/** @var array Uncompressed item cache */
	public $mItems = [];

	/** @var int Total uncompressed size */
	public $mSize = 0;

	/**
	 * @var array Array of diffs. If a diff D from A to B is notated D = B - A,
	 * and Z is an empty string:
	 *
	 *              { item[map[i]] - item[map[i-1]]   where i > 0
	 *    diff[i] = {
	 *              { item[map[i]] - Z                where i = 0
	 */
	public $mDiffs;

	/** @var array The diff map, see above */
	public $mDiffMap;

	/** @var int The key for getText()
	 */
	public $mDefaultKey;

	/** @var string Compressed storage */
	public $mCompressed;

	/** @var bool True if the object is locked against further writes */
	public $mFrozen = false;

	/**
	 * @var int The maximum uncompressed size before the object becomes sad
	 * Should be less than max_allowed_packet
	 */
	public $mMaxSize = 10000000;

	/** @var int The maximum number of text items before the object becomes sad */
	public $mMaxCount = 100;

	/** Constants from xdiff.h */
	const XDL_BDOP_INS = 1;
	const XDL_BDOP_CPY = 2;
	const XDL_BDOP_INSB = 3;

	function __construct() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new MWException( "Need zlib support to read or write DiffHistoryBlob\n" );
		}
	}

	/**
	 * @throws MWException
	 * @param string $text
	 * @return int
	 */
	function addItem( $text ) {
		if ( $this->mFrozen ) {
			throw new MWException( __METHOD__ . ": Cannot add more items after sleep/wakeup" );
		}

		$this->mItems[] = $text;
		$this->mSize += strlen( $text );
		$this->mDiffs = null; // later
		return count( $this->mItems ) - 1;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	function getItem( $key ) {
		return $this->mItems[$key];
	}

	/**
	 * @param string $text
	 */
	function setText( $text ) {
		$this->mDefaultKey = $this->addItem( $text );
	}

	/**
	 * @return string
	 */
	function getText() {
		return $this->getItem( $this->mDefaultKey );
	}

	/**
	 * @throws MWException
	 */
	function compress() {
		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
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
		$sequences = [
			'small' => [
				'tail' => '',
				'diffs' => [],
				'map' => [],
			],
			'main' => [
				'tail' => '',
				'diffs' => [],
				'map' => [],
			],
		];
		$smallFactor = 0.5;

		$mItemsCount = count( $this->mItems );
		for ( $i = 0; $i < $mItemsCount; $i++ ) {
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
		$this->mDiffs = [];
		$this->mDiffMap = [];
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
			$diffsCount = count( $seq['diffs'] );
			for ( $i = 1; $i < $diffsCount; $i++ ) {
				$this->mDiffs[] = $seq['diffs'][$i];
				$this->mDiffMap[] = $seq['map'][$i];
			}
			$tail = $seq['tail'];
		}
	}

	/**
	 * @param string $t1
	 * @param string $t2
	 * @return string
	 */
	function diff( $t1, $t2 ) {
		# Need to do a null concatenation with warnings off, due to bugs in the current version of xdiff
		# "String is not zero-terminated"
		MediaWiki\suppressWarnings();
		$diff = xdiff_string_rabdiff( $t1, $t2 ) . '';
		MediaWiki\restoreWarnings();
		return $diff;
	}

	/**
	 * @param string $base
	 * @param string $diff
	 * @return bool|string
	 */
	function patch( $base, $diff ) {
		if ( function_exists( 'xdiff_string_bpatch' ) ) {
			MediaWiki\suppressWarnings();
			$text = xdiff_string_bpatch( $base, $diff ) . '';
			MediaWiki\restoreWarnings();
			return $text;
		}

		# Pure PHP implementation

		$header = unpack( 'Vofp/Vcsize', substr( $diff, 0, 8 ) );

		# Check the checksum if hash extension is available
		$ofp = $this->xdiffAdler32( $base );
		if ( $ofp !== false && $ofp !== substr( $diff, 0, 4 ) ) {
			wfDebug( __METHOD__ . ": incorrect base checksum\n" );
			return false;
		}
		if ( $header['csize'] != strlen( $base ) ) {
			wfDebug( __METHOD__ . ": incorrect base length\n" );
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
				wfDebug( __METHOD__ . ": invalid op\n" );
				return false;
			}
		}
		return $out;
	}

	/**
	 * Compute a binary "Adler-32" checksum as defined by LibXDiff, i.e. with
	 * the bytes backwards and initialised with 0 instead of 1. See bug 34428.
	 *
	 * @param string $s
	 * @return string|bool False if the hash extension is not available
	 */
	function xdiffAdler32( $s ) {
		if ( !function_exists( 'hash' ) ) {
			return false;
		}

		static $init;
		if ( $init === null ) {
			$init = str_repeat( "\xf0", 205 ) . "\xee" . str_repeat( "\xf0", 67 ) . "\x02";
		}

		// The real Adler-32 checksum of $init is zero, so it initialises the
		// state to zero, as it is at the start of LibXDiff's checksum
		// algorithm. Appending the subject string then simulates LibXDiff.
		return strrev( hash( 'adler32', $init . $s, true ) );
	}

	function uncompress() {
		if ( !$this->mDiffs ) {
			return;
		}
		$tail = '';
		$mDiffsCount = count( $this->mDiffs );
		for ( $diffKey = 0; $diffKey < $mDiffsCount; $diffKey++ ) {
			$textKey = $this->mDiffMap[$diffKey];
			$text = $this->patch( $tail, $this->mDiffs[$diffKey] );
			$this->mItems[$textKey] = $text;
			$tail = $text;
		}
	}

	/**
	 * @return array
	 */
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
			$info = [
				'diffs' => $this->mDiffs,
				'map' => $map
			];
		}
		if ( isset( $this->mDefaultKey ) ) {
			$info['default'] = $this->mDefaultKey;
		}
		$this->mCompressed = gzdeflate( serialize( $info ) );
		return [ 'mCompressed' ];
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
			$this->mDiffMap = [];
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
	 *
	 * @return bool
	 */
	function isHappy() {
		return $this->mSize < $this->mMaxSize
			&& count( $this->mItems ) < $this->mMaxCount;
	}

}
