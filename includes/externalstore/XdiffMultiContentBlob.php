<?php
/* Much of this code was copied from that for DiffHistoryBlob.
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
 */

namespace MediaWiki\ExternalStore;

use DiffHistoryBlob;

/**
 * Xdiff-based history compression
 *
 * Requires xdiff 1.5+ for writing
 *
 * @since 1.32
 */
class XdiffMultiContentBlob extends MultiContentBlob {

	/** @var array Uncompressed item cache */
	protected $items = [];

	/** @var int Total uncompressed size */
	protected $size = 0;

	/**
	 * @var array Array of diffs. If a diff D from A to B is notated D = B - A,
	 * and Z is an empty string:
	 *
	 *              ⎧ item[map[i]] - item[map[i-1]]   where i > 0
	 *    diff[i] = ⎨
	 *              ⎩ item[map[i]] - Z                where i = 0
	 */
	protected $diffs;

	/** @var array The diff map, see above */
	protected $diffMap;

	/** @var bool True if the object is locked against further writes */
	protected $frozen = false;

	/** Constants from xdiff.h */
	const XDL_BDOP_INS = 1;
	const XDL_BDOP_CPY = 2;
	const XDL_BDOP_INSB = 3;

	public function getSize() {
		return $this->size;
	}

	public function getCount() {
		return count( $this->items );
	}

	public function addItem( $text ) {
		if ( $this->frozen ) {
			throw new \BadMethodCallException( __METHOD__ . ': Cannot add more items after sleep/wakeup' );
		}

		$this->items[] = $text;
		$this->size += strlen( $text );
		$this->diffs = null; // later
		return count( $this->items ) - 1;
	}

	public function getItem( $key ) {
		if ( array_key_exists( $key, $this->items ) ) {
			return $this->items[$key];
		} else {
			return false;
		}
	}

	protected function compress() {
		if ( isset( $this->diffs ) ) {
			// Already compressed
			return;
		}

		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
			throw new \BadMethodCallException( 'Need xdiff 1.5+ support to write ' . __CLASS__ );
		}

		if ( !count( $this->items ) ) {
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

		$itemsCount = count( $this->items );
		for ( $i = 0; $i < $itemsCount; $i++ ) {
			$text = $this->items[$i];
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
		$this->diffs = [];
		$this->diffMap = [];
		foreach ( $sequences as $seq ) {
			if ( !count( $seq['diffs'] ) ) {
				continue;
			}
			if ( $tail === '' ) {
				$this->diffs[] = $seq['diffs'][0];
			} else {
				$head = $this->patch( '', $seq['diffs'][0] );
				$this->diffs[] = $this->diff( $tail, $head );
			}
			$this->diffMap[] = $seq['map'][0];
			$diffsCount = count( $seq['diffs'] );
			for ( $i = 1; $i < $diffsCount; $i++ ) {
				$this->diffs[] = $seq['diffs'][$i];
				$this->diffMap[] = $seq['map'][$i];
			}
			$tail = $seq['tail'];
		}
	}

	/**
	 * @param string $t1
	 * @param string $t2
	 * @return string
	 */
	protected function diff( $t1, $t2 ) {
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
	protected function patch( $base, $diff ) {
		if ( function_exists( 'xdiff_string_bpatch' ) ) {
			MediaWiki\suppressWarnings();
			$text = xdiff_string_bpatch( $base, $diff ) . '';
			MediaWiki\restoreWarnings();
			return $text;
		}

		# Pure PHP implementation

		$header = unpack( 'Vofp/Vcsize', substr( $diff, 0, 8 ) );

		# Check the checksum
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
	 * the bytes backwards and initialised with 0 instead of 1. See T36428.
	 *
	 * @param string $s
	 * @return string
	 */
	protected function xdiffAdler32( $s ) {
		static $init;
		if ( $init === null ) {
			$init = str_repeat( "\xf0", 205 ) . "\xee" . str_repeat( "\xf0", 67 ) . "\x02";
		}

		// The real Adler-32 checksum of $init is zero, so it initialises the
		// state to zero, as it is at the start of LibXDiff's checksum
		// algorithm. Appending the subject string then simulates LibXDiff.
		return strrev( hash( 'adler32', $init . $s, true ) );
	}

	protected function uncompress() {
		$this->size = 0;
		$this->items = [];

		if ( !$this->diffs ) {
			return;
		}
		$tail = '';
		$diffsCount = count( $this->diffs );
		for ( $diffKey = 0; $diffKey < $diffsCount; $diffKey++ ) {
			$textKey = $this->diffMap[$diffKey];
			$text = $this->patch( $tail, $this->diffs[$diffKey] );
			$this->items[$textKey] = $text;
			$this->size += strlen( $text );
			$tail = $text;
		}
	}

	protected function getData() {
		$this->compress();
		$data = [];
		$metadata = [];
		if ( count( $this->items ) ) {
			// Take forward differences to improve the compression ratio for sequences
			$map = '';
			$prev = 0;
			foreach ( $this->diffMap as $i ) {
				if ( $map !== '' ) {
					$map .= ',';
				}
				$map .= $i - $prev;
				$prev = $i;
			}
			$data = $this->diffs;
			$metadata['map'] = $map;
		}

		return [ $data, $metadata ];
	}

	protected function setData( array $data, array $metadata = null ) {
		// addItem() doesn't work if items is partially filled from diffs
		$this->frozen = true;

		if ( !$data ) {
			// Empty object
			return;
		}

		if ( !isset( $metadata['map'] ) ) {
			throw new \InvalidArgumentException( '$metadata lacks \'map\'' );
		}

		$this->diffs = $data;
		$map = explode( ',', $metadata['map'] );
		$cur = 0;
		$this->diffMap = [];
		foreach ( $map as $i ) {
			$cur += $i;
			$this->diffMap[] = $cur;
		}
		$this->uncompress();
	}

	/**
	 * For migration, create a XdiffMultiContentBlob from a DiffHistoryBlob
	 * @param DiffHistoryBlob $blob
	 * @return XdiffMultiContentBlob
	 */
	public static function newFromDiffHistoryBlob( DiffHistoryBlob $blob ) {
		// We're careful here to not attempt to use xdiff unnecessarily, since
		// both classes only require xdiff for new writes. Thus we copy
		// DiffHistoryBlob's mDiffs and mDiffMap fields instead of calling our
		// compress(), and we only call DiffHistoryBlob's compress() if it
		// hasn't already been called.
		if ( !isset( $blob->mDiffs ) ) {
			$blob->compress();
		}

		$ret = new self;
		$ret->diffs = $blob->mDiffs;
		$ret->diffMap = $blob->mDiffMap;
		$ret->frozen = true;
		$ret->uncompress();
		return $ret;
	}

}
