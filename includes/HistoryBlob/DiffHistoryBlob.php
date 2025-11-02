<?php
/**
 * Efficient concatenated text storage.
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Diff-based history compression
 * Requires xdiff and zlib
 *
 * WARNING: Objects of this class are serialized and permanently stored in the DB.
 * Do not change the name or visibility of any property!
 */
class DiffHistoryBlob implements HistoryBlob {
	/** @var string[] Uncompressed item cache */
	public $mItems = [];

	/** @var int Total uncompressed size */
	public $mSize = 0;

	/**
	 * @var array|null Array of diffs. If a diff D from A to B is notated D = B - A,
	 * and Z is an empty string:
	 *
	 *              { item[map[i]] - item[map[i-1]]   where i > 0
	 *    diff[i] = {
	 *              { item[map[i]] - Z                where i = 0
	 */
	public $mDiffs;

	/** @var array The diff map, see above */
	public $mDiffMap;

	/** @var string|null The key for getText()
	 */
	public $mDefaultKey;

	/** @var string|null Compressed storage */
	public $mCompressed;

	/** @var bool True if the object is locked against further writes */
	public $mFrozen = false;

	/**
	 * @var int The maximum uncompressed size before the object becomes sad
	 * Should be less than max_allowed_packet
	 */
	public $mMaxSize = 10_000_000;

	/** @var int The maximum number of text items before the object becomes sad */
	public $mMaxCount = 100;

	/** Constants from xdiff.h */
	private const XDL_BDOP_INS = 1;
	private const XDL_BDOP_CPY = 2;
	private const XDL_BDOP_INSB = 3;

	public function __construct() {
		if ( !function_exists( 'gzdeflate' ) ) {
			throw new RuntimeException( "Need zlib support to read or write DiffHistoryBlob\n" );
		}
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public function addItem( $text ) {
		if ( $this->mFrozen ) {
			throw new BadMethodCallException( __METHOD__ . ": Cannot add more items after sleep/wakeup" );
		}

		$this->mItems[] = $text;
		$this->mSize += strlen( $text );
		$this->mDiffs = null; // later
		return (string)( count( $this->mItems ) - 1 );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function getItem( $key ) {
		return $this->mItems[(int)$key];
	}

	/**
	 * @param string $text
	 */
	public function setText( $text ) {
		$this->mDefaultKey = $this->addItem( $text );
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->getItem( $this->mDefaultKey );
	}

	private function compress() {
		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
			throw new RuntimeException( "Need xdiff support to write DiffHistoryBlob\n" );
		}
		if ( $this->mDiffs !== null ) {
			// Already compressed
			return;
		}
		if ( $this->mItems === [] ) {
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

			$tail = $sequences[$seqName]['tail'];
			$diff = $this->diff( $tail, $text );
			$sequences[$seqName]['diffs'][] = $diff;
			$sequences[$seqName]['map'][] = $i;
			$sequences[$seqName]['tail'] = $text;
		}

		// Knit the sequences together
		$tail = '';
		$this->mDiffs = [];
		$this->mDiffMap = [];
		foreach ( $sequences as $seq ) {
			if ( $seq['diffs'] === [] ) {
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
	private function diff( $t1, $t2 ) {
		return xdiff_string_rabdiff( $t1, $t2 );
	}

	/**
	 * @param string $base
	 * @param string $diff
	 * @return bool|string
	 */
	private function patch( $base, $diff ) {
		if ( function_exists( 'xdiff_string_bpatch' ) ) {
			return xdiff_string_bpatch( $base, $diff );
		}

		# Pure PHP implementation

		$header = unpack( 'Vofp/Vcsize', substr( $diff, 0, 8 ) );

		# Check the checksum
		$ofp = $this->xdiffAdler32( $base );
		if ( $ofp !== substr( $diff, 0, 4 ) ) {
			wfDebug( __METHOD__ . ": incorrect base checksum" );
			return false;
		}
		if ( $header['csize'] != strlen( $base ) ) {
			wfDebug( __METHOD__ . ": incorrect base length" );
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
					wfDebug( __METHOD__ . ": invalid op" );
					return false;
			}
		}
		return $out;
	}

	/**
	 * Compute a binary "Adler-32" checksum as defined by LibXDiff, i.e. with
	 * the bytes backwards and initialised with 0 instead of 1. See T36428.
	 *
	 * @param string $s
	 * @return string
	 */
	public function xdiffAdler32( $s ) {
		static $init = null;
		$init ??= str_repeat( "\xf0", 205 ) . "\xee" . str_repeat( "\xf0", 67 ) . "\x02";

		// The real Adler-32 checksum of $init is zero, so it initialises the
		// state to zero, as it is at the start of LibXDiff's checksum
		// algorithm. Appending the subject string then simulates LibXDiff.
		return strrev( hash( 'adler32', $init . $s, true ) );
	}

	private function uncompress() {
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
	public function __sleep() {
		$this->compress();
		if ( $this->mItems === [] ) {
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
		if ( $this->mDefaultKey !== null ) {
			$info['default'] = $this->mDefaultKey;
		}
		$this->mCompressed = gzdeflate( serialize( $info ) );
		return [ 'mCompressed' ];
	}

	public function __wakeup() {
		// addItem() doesn't work if mItems is partially filled from mDiffs
		$this->mFrozen = true;
		$info = HistoryBlobUtils::unserializeArray( gzinflate( $this->mCompressed ) );
		$this->mCompressed = null;

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
	public function isHappy() {
		return $this->mSize < $this->mMaxSize
			&& count( $this->mItems ) < $this->mMaxCount;
	}

}
