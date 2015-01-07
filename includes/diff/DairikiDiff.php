<?php
/**
 * A PHP diff engine for phpwiki. (Taken from phpwiki-1.3.3)
 *
 * Copyright © 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * You may copy this code freely under the conditions of the GPL.
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
 * @ingroup DifferenceEngine
 * @defgroup DifferenceEngine DifferenceEngine
 */

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
abstract class DiffOp {

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var string[]
	 */
	public $orig;

	/**
	 * @var string[]
	 */
	public $closing;

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return string[]
	 */
	public function getOrig() {
		return $this->orig;
	}

	/**
	 * @param int $i
	 * @return string|null
	 */
	public function getClosing( $i = null ) {
		if ( $i === null ) {
			return $this->closing;
		}
		if ( array_key_exists( $i, $this->closing ) ) {
			return $this->closing[$i];
		}
		return null;
	}

	abstract public function reverse();

	/**
	 * @return int
	 */
	public function norig() {
		return $this->orig ? count( $this->orig ) : 0;
	}

	/**
	 * @return int
	 */
	public function nclosing() {
		return $this->closing ? count( $this->closing ) : 0;
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class DiffOpCopy extends DiffOp {
	public $type = 'copy';

	public function __construct( $orig, $closing = false ) {
		if ( !is_array( $closing ) ) {
			$closing = $orig;
		}
		$this->orig = $orig;
		$this->closing = $closing;
	}

	/**
	 * @return DiffOpCopy
	 */
	public function reverse() {
		return new DiffOpCopy( $this->closing, $this->orig );
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class DiffOpDelete extends DiffOp {
	public $type = 'delete';

	public function __construct( $lines ) {
		$this->orig = $lines;
		$this->closing = false;
	}

	/**
	 * @return DiffOpAdd
	 */
	public function reverse() {
		return new DiffOpAdd( $this->orig );
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class DiffOpAdd extends DiffOp {
	public $type = 'add';

	public function __construct( $lines ) {
		$this->closing = $lines;
		$this->orig = false;
	}

	/**
	 * @return DiffOpDelete
	 */
	public function reverse() {
		return new DiffOpDelete( $this->closing );
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class DiffOpChange extends DiffOp {
	public $type = 'change';

	public function __construct( $orig, $closing ) {
		$this->orig = $orig;
		$this->closing = $closing;
	}

	/**
	 * @return DiffOpChange
	 */
	public function reverse() {
		return new DiffOpChange( $this->closing, $this->orig );
	}
}

/**
 * Class used internally by Diff to actually compute the diffs.
 *
 * The algorithm used here is mostly lifted from the perl module
 * Algorithm::Diff (version 1.06) by Ned Konz, which is available at:
 *     http://www.perl.com/CPAN/authors/id/N/NE/NEDKONZ/Algorithm-Diff-1.06.zip
 *
 * More ideas are taken from:
 *     http://www.ics.uci.edu/~eppstein/161/960229.html
 *
 * Some ideas (and a bit of code) are from analyze.c, from GNU
 * diffutils-2.7, which can be found at:
 *     ftp://gnudist.gnu.org/pub/gnu/diffutils/diffutils-2.7.tar.gz
 *
 * closingly, some ideas (subdivision by NCHUNKS > 2, and some optimizations)
 * are my own.
 *
 * Line length limits for robustness added by Tim Starling, 2005-08-31
 * Alternative implementation added by Guy Van den Broeck, 2008-07-30
 *
 * @author Geoffrey T. Dairiki, Tim Starling, Guy Van den Broeck
 * @private
 * @ingroup DifferenceEngine
 */
class DiffEngine {
	const MAX_XREF_LENGTH = 10000;

	protected $xchanged, $ychanged;

	protected $xv = array(), $yv = array();
	protected $xind = array(), $yind = array();

	protected $seq = array(), $in_seq = array();

	protected $lcs = 0;

	/**
	 * @param string[] $from_lines
	 * @param string[] $to_lines
	 *
	 * @return DiffOp[]
	 */
	public function diff( $from_lines, $to_lines ) {

		// Diff and store locally
		$this->diffLocal( $from_lines, $to_lines );

		// Merge edits when possible
		$this->shiftBoundaries( $from_lines, $this->xchanged, $this->ychanged );
		$this->shiftBoundaries( $to_lines, $this->ychanged, $this->xchanged );

		// Compute the edit operations.
		$n_from = count( $from_lines );
		$n_to = count( $to_lines );

		$edits = array();
		$xi = $yi = 0;
		while ( $xi < $n_from || $yi < $n_to ) {
			assert( '$yi < $n_to || $this->xchanged[$xi]' );
			assert( '$xi < $n_from || $this->ychanged[$yi]' );

			// Skip matching "snake".
			$copy = array();
			while ( $xi < $n_from && $yi < $n_to
				&& !$this->xchanged[$xi] && !$this->ychanged[$yi]
			) {
				$copy[] = $from_lines[$xi++];
				++$yi;
			}
			if ( $copy ) {
				$edits[] = new DiffOpCopy( $copy );
			}

			// Find deletes & adds.
			$delete = array();
			while ( $xi < $n_from && $this->xchanged[$xi] ) {
				$delete[] = $from_lines[$xi++];
			}

			$add = array();
			while ( $yi < $n_to && $this->ychanged[$yi] ) {
				$add[] = $to_lines[$yi++];
			}

			if ( $delete && $add ) {
				$edits[] = new DiffOpChange( $delete, $add );
			} elseif ( $delete ) {
				$edits[] = new DiffOpDelete( $delete );
			} elseif ( $add ) {
				$edits[] = new DiffOpAdd( $add );
			}
		}

		return $edits;
	}

	/**
	 * @param string[] $from_lines
	 * @param string[] $to_lines
	 */
	private function diffLocal( $from_lines, $to_lines ) {
		global $wgExternalDiffEngine;

		if ( $wgExternalDiffEngine == 'wikidiff3' ) {
			// wikidiff3
			$wikidiff3 = new WikiDiff3();
			$wikidiff3->diff( $from_lines, $to_lines );
			$this->xchanged = $wikidiff3->removed;
			$this->ychanged = $wikidiff3->added;
			unset( $wikidiff3 );
		} else {
			// old diff
			$n_from = count( $from_lines );
			$n_to = count( $to_lines );
			$this->xchanged = $this->ychanged = array();
			$this->xv = $this->yv = array();
			$this->xind = $this->yind = array();
			unset( $this->seq );
			unset( $this->in_seq );
			unset( $this->lcs );

			// Skip leading common lines.
			for ( $skip = 0; $skip < $n_from && $skip < $n_to; $skip++ ) {
				if ( $from_lines[$skip] !== $to_lines[$skip] ) {
					break;
				}
				$this->xchanged[$skip] = $this->ychanged[$skip] = false;
			}
			// Skip trailing common lines.
			$xi = $n_from;
			$yi = $n_to;
			for ( $endskip = 0; --$xi > $skip && --$yi > $skip; $endskip++ ) {
				if ( $from_lines[$xi] !== $to_lines[$yi] ) {
					break;
				}
				$this->xchanged[$xi] = $this->ychanged[$yi] = false;
			}

			// Ignore lines which do not exist in both files.
			for ( $xi = $skip; $xi < $n_from - $endskip; $xi++ ) {
				$xhash[$this->lineHash( $from_lines[$xi] )] = 1;
			}

			for ( $yi = $skip; $yi < $n_to - $endskip; $yi++ ) {
				$line = $to_lines[$yi];
				if ( ( $this->ychanged[$yi] = empty( $xhash[$this->lineHash( $line )] ) ) ) {
					continue;
				}
				$yhash[$this->lineHash( $line )] = 1;
				$this->yv[] = $line;
				$this->yind[] = $yi;
			}
			for ( $xi = $skip; $xi < $n_from - $endskip; $xi++ ) {
				$line = $from_lines[$xi];
				if ( ( $this->xchanged[$xi] = empty( $yhash[$this->lineHash( $line )] ) ) ) {
					continue;
				}
				$this->xv[] = $line;
				$this->xind[] = $xi;
			}

			// Find the LCS.
			$this->compareSeq( 0, count( $this->xv ), 0, count( $this->yv ) );
		}
	}

	/**
	 * Returns the whole line if it's small enough, or the MD5 hash otherwise
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	private function lineHash( $line ) {
		if ( strlen( $line ) > self::MAX_XREF_LENGTH ) {
			return md5( $line );
		} else {
			return $line;
		}
	}

	/**
	 * Divide the Largest Common Subsequence (LCS) of the sequences
	 * [XOFF, XLIM) and [YOFF, YLIM) into NCHUNKS approximately equally
	 * sized segments.
	 *
	 * Returns (LCS, PTS). LCS is the length of the LCS. PTS is an
	 * array of NCHUNKS+1 (X, Y) indexes giving the diving points between
	 * sub sequences.  The first sub-sequence is contained in [X0, X1),
	 * [Y0, Y1), the second in [X1, X2), [Y1, Y2) and so on.  Note
	 * that (X0, Y0) == (XOFF, YOFF) and
	 * (X[NCHUNKS], Y[NCHUNKS]) == (XLIM, YLIM).
	 *
	 * This function assumes that the first lines of the specified portions
	 * of the two files do not match, and likewise that the last lines do not
	 * match.  The caller must trim matching lines from the beginning and end
	 * of the portions it is going to specify.
	 *
	 * @param int $xoff
	 * @param int $xlim
	 * @param int $yoff
	 * @param int $ylim
	 * @param int $nchunks
	 *
	 * @return array List of two elements, integer and array[].
	 */
	private function diag( $xoff, $xlim, $yoff, $ylim, $nchunks ) {
		$flip = false;

		if ( $xlim - $xoff > $ylim - $yoff ) {
			// Things seems faster (I'm not sure I understand why)
			// when the shortest sequence in X.
			$flip = true;
			list( $xoff, $xlim, $yoff, $ylim ) = array( $yoff, $ylim, $xoff, $xlim );
		}

		if ( $flip ) {
			for ( $i = $ylim - 1; $i >= $yoff; $i-- ) {
				$ymatches[$this->xv[$i]][] = $i;
			}
		} else {
			for ( $i = $ylim - 1; $i >= $yoff; $i-- ) {
				$ymatches[$this->yv[$i]][] = $i;
			}
		}

		$this->lcs = 0;
		$this->seq[0] = $yoff - 1;
		$this->in_seq = array();
		$ymids[0] = array();

		$numer = $xlim - $xoff + $nchunks - 1;
		$x = $xoff;
		for ( $chunk = 0; $chunk < $nchunks; $chunk++ ) {
			if ( $chunk > 0 ) {
				for ( $i = 0; $i <= $this->lcs; $i++ ) {
					$ymids[$i][$chunk - 1] = $this->seq[$i];
				}
			}

			$x1 = $xoff + (int)( ( $numer + ( $xlim - $xoff ) * $chunk ) / $nchunks );
			// @codingStandardsIgnoreFile Ignore Squiz.WhiteSpace.SemicolonSpacing.Incorrect
			for ( ; $x < $x1; $x++ ) {
				// @codingStandardsIgnoreEnd
				$line = $flip ? $this->yv[$x] : $this->xv[$x];
				if ( empty( $ymatches[$line] ) ) {
					continue;
				}

				$k = 0;
				$matches = $ymatches[$line];
				reset( $matches );
				while ( list( , $y ) = each( $matches ) ) {
					if ( empty( $this->in_seq[$y] ) ) {
						$k = $this->lcsPos( $y );
						assert( '$k > 0' );
						$ymids[$k] = $ymids[$k - 1];
						break;
					}
				}

				while ( list( , $y ) = each( $matches ) ) {
					if ( $y > $this->seq[$k - 1] ) {
						assert( '$y < $this->seq[$k]' );
						// Optimization: this is a common case:
						//	next match is just replacing previous match.
						$this->in_seq[$this->seq[$k]] = false;
						$this->seq[$k] = $y;
						$this->in_seq[$y] = 1;
					} elseif ( empty( $this->in_seq[$y] ) ) {
						$k = $this->lcsPos( $y );
						assert( '$k > 0' );
						$ymids[$k] = $ymids[$k - 1];
					}
				}
			}
		}

		$seps[] = $flip ? array( $yoff, $xoff ) : array( $xoff, $yoff );
		$ymid = $ymids[$this->lcs];
		for ( $n = 0; $n < $nchunks - 1; $n++ ) {
			$x1 = $xoff + (int)( ( $numer + ( $xlim - $xoff ) * $n ) / $nchunks );
			$y1 = $ymid[$n] + 1;
			$seps[] = $flip ? array( $y1, $x1 ) : array( $x1, $y1 );
		}
		$seps[] = $flip ? array( $ylim, $xlim ) : array( $xlim, $ylim );

		return array( $this->lcs, $seps );
	}

	/**
	 * @param int $ypos
	 *
	 * @return int
	 */
	private function lcsPos( $ypos ) {
		$end = $this->lcs;
		if ( $end == 0 || $ypos > $this->seq[$end] ) {
			$this->seq[++$this->lcs] = $ypos;
			$this->in_seq[$ypos] = 1;

			return $this->lcs;
		}

		$beg = 1;
		while ( $beg < $end ) {
			$mid = (int)( ( $beg + $end ) / 2 );
			if ( $ypos > $this->seq[$mid] ) {
				$beg = $mid + 1;
			} else {
				$end = $mid;
			}
		}

		assert( '$ypos != $this->seq[$end]' );

		$this->in_seq[$this->seq[$end]] = false;
		$this->seq[$end] = $ypos;
		$this->in_seq[$ypos] = 1;

		return $end;
	}

	/**
	 * Find LCS of two sequences.
	 *
	 * The results are recorded in the vectors $this->{x,y}changed[], by
	 * storing a 1 in the element for each line that is an insertion
	 * or deletion (ie. is not in the LCS).
	 *
	 * The subsequence of file 0 is [XOFF, XLIM) and likewise for file 1.
	 *
	 * Note that XLIM, YLIM are exclusive bounds.
	 * All line numbers are origin-0 and discarded lines are not counted.
	 *
	 * @param int $xoff
	 * @param int $xlim
	 * @param int $yoff
	 * @param int $ylim
	 */
	private function compareSeq( $xoff, $xlim, $yoff, $ylim ) {
		// Slide down the bottom initial diagonal.
		while ( $xoff < $xlim && $yoff < $ylim && $this->xv[$xoff] == $this->yv[$yoff] ) {
			++$xoff;
			++$yoff;
		}

		// Slide up the top initial diagonal.
		while ( $xlim > $xoff && $ylim > $yoff
			&& $this->xv[$xlim - 1] == $this->yv[$ylim - 1]
		) {
			--$xlim;
			--$ylim;
		}

		if ( $xoff == $xlim || $yoff == $ylim ) {
			$lcs = 0;
		} else {
			// This is ad hoc but seems to work well.
			// $nchunks = sqrt(min($xlim - $xoff, $ylim - $yoff) / 2.5);
			// $nchunks = max(2,min(8,(int)$nchunks));
			$nchunks = min( 7, $xlim - $xoff, $ylim - $yoff ) + 1;
			list( $lcs, $seps ) = $this->diag( $xoff, $xlim, $yoff, $ylim, $nchunks );
		}

		if ( $lcs == 0 ) {
			// X and Y sequences have no common subsequence:
			// mark all changed.
			while ( $yoff < $ylim ) {
				$this->ychanged[$this->yind[$yoff++]] = 1;
			}
			while ( $xoff < $xlim ) {
				$this->xchanged[$this->xind[$xoff++]] = 1;
			}
		} else {
			// Use the partitions to split this problem into subproblems.
			reset( $seps );
			$pt1 = $seps[0];
			while ( $pt2 = next( $seps ) ) {
				$this->compareSeq( $pt1[0], $pt2[0], $pt1[1], $pt2[1] );
				$pt1 = $pt2;
			}
		}
	}

	/**
	 * Adjust inserts/deletes of identical lines to join changes
	 * as much as possible.
	 *
	 * We do something when a run of changed lines include a
	 * line at one end and has an excluded, identical line at the other.
	 * We are free to choose which identical line is included.
	 * `compareseq' usually chooses the one at the beginning,
	 * but usually it is cleaner to consider the following identical line
	 * to be the "change".
	 *
	 * This is extracted verbatim from analyze.c (GNU diffutils-2.7).
	 */
	private function shiftBoundaries( $lines, &$changed, $other_changed ) {
		$i = 0;
		$j = 0;

		assert( 'count($lines) == count($changed)' );
		$len = count( $lines );
		$other_len = count( $other_changed );

		while ( 1 ) {
			/*
			 * Scan forwards to find beginning of another run of changes.
			 * Also keep track of the corresponding point in the other file.
			 *
			 * Throughout this code, $i and $j are adjusted together so that
			 * the first $i elements of $changed and the first $j elements
			 * of $other_changed both contain the same number of zeros
			 * (unchanged lines).
			 * Furthermore, $j is always kept so that $j == $other_len or
			 * $other_changed[$j] == false.
			 */
			while ( $j < $other_len && $other_changed[$j] ) {
				$j++;
			}

			while ( $i < $len && !$changed[$i] ) {
				assert( '$j < $other_len && ! $other_changed[$j]' );
				$i++;
				$j++;
				while ( $j < $other_len && $other_changed[$j] ) {
					$j++;
				}
			}

			if ( $i == $len ) {
				break;
			}

			$start = $i;

			// Find the end of this run of changes.
			while ( ++$i < $len && $changed[$i] ) {
				continue;
			}

			do {
				/*
				 * Record the length of this run of changes, so that
				 * we can later determine whether the run has grown.
				 */
				$runlength = $i - $start;

				/*
				 * Move the changed region back, so long as the
				 * previous unchanged line matches the last changed one.
				 * This merges with previous changed regions.
				 */
				while ( $start > 0 && $lines[$start - 1] == $lines[$i - 1] ) {
					$changed[--$start] = 1;
					$changed[--$i] = false;
					while ( $start > 0 && $changed[$start - 1] ) {
						$start--;
					}
					assert( '$j > 0' );
					while ( $other_changed[--$j] ) {
						continue;
					}
					assert( '$j >= 0 && !$other_changed[$j]' );
				}

				/*
				 * Set CORRESPONDING to the end of the changed run, at the last
				 * point where it corresponds to a changed run in the other file.
				 * CORRESPONDING == LEN means no such point has been found.
				 */
				$corresponding = $j < $other_len ? $i : $len;

				/*
				 * Move the changed region forward, so long as the
				 * first changed line matches the following unchanged one.
				 * This merges with following changed regions.
				 * Do this second, so that if there are no merges,
				 * the changed region is moved forward as far as possible.
				 */
				while ( $i < $len && $lines[$start] == $lines[$i] ) {
					$changed[$start++] = false;
					$changed[$i++] = 1;
					while ( $i < $len && $changed[$i] ) {
						$i++;
					}

					assert( '$j < $other_len && ! $other_changed[$j]' );
					$j++;
					if ( $j < $other_len && $other_changed[$j] ) {
						$corresponding = $i;
						while ( $j < $other_len && $other_changed[$j] ) {
							$j++;
						}
					}
				}
			} while ( $runlength != $i - $start );

			/*
			 * If possible, move the fully-merged run of changes
			 * back to a corresponding run in the other file.
			 */
			while ( $corresponding < $i ) {
				$changed[--$start] = 1;
				$changed[--$i] = 0;
				assert( '$j > 0' );
				while ( $other_changed[--$j] ) {
					continue;
				}
				assert( '$j >= 0 && !$other_changed[$j]' );
			}
		}
	}
}

/**
 * Class representing a 'diff' between two sequences of strings.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class Diff {

	/**
	 * @var DiffOp[]
	 */
	public $edits;

	/**
	 * Constructor.
	 * Computes diff between sequences of strings.
	 *
	 * @param string[] $from_lines An array of strings.
	 *   Typically these are lines from a file.
	 * @param string[] $to_lines An array of strings.
	 */
	public function __construct( $from_lines, $to_lines ) {
		$eng = new DiffEngine;
		$this->edits = $eng->diff( $from_lines, $to_lines );
	}

	/**
	 * @return DiffOp[]
	 */
	public function getEdits() {
		return $this->edits;
	}

	/**
	 * Compute reversed Diff.
	 *
	 * SYNOPSIS:
	 *
	 *    $diff = new Diff($lines1, $lines2);
	 *    $rev = $diff->reverse();
	 *
	 * @return Object A Diff object representing the inverse of the
	 *   original diff.
	 */
	public function reverse() {
		$rev = $this;
		$rev->edits = array();
		/** @var DiffOp $edit */
		foreach ( $this->edits as $edit ) {
			$rev->edits[] = $edit->reverse();
		}

		return $rev;
	}

	/**
	 * Check for empty diff.
	 *
	 * @return bool True if two sequences were identical.
	 */
	public function isEmpty() {
		foreach ( $this->edits as $edit ) {
			if ( $edit->type != 'copy' ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Compute the length of the Longest Common Subsequence (LCS).
	 *
	 * This is mostly for diagnostic purposed.
	 *
	 * @return int The length of the LCS.
	 */
	public function lcs() {
		$lcs = 0;
		foreach ( $this->edits as $edit ) {
			if ( $edit->type == 'copy' ) {
				$lcs += count( $edit->orig );
			}
		}

		return $lcs;
	}

	/**
	 * Get the original set of lines.
	 *
	 * This reconstructs the $from_lines parameter passed to the
	 * constructor.
	 *
	 * @return string[] The original sequence of strings.
	 */
	public function orig() {
		$lines = array();

		foreach ( $this->edits as $edit ) {
			if ( $edit->orig ) {
				array_splice( $lines, count( $lines ), 0, $edit->orig );
			}
		}

		return $lines;
	}

	/**
	 * Get the closing set of lines.
	 *
	 * This reconstructs the $to_lines parameter passed to the
	 * constructor.
	 *
	 * @return string[] The sequence of strings.
	 */
	public function closing() {
		$lines = array();

		foreach ( $this->edits as $edit ) {
			if ( $edit->closing ) {
				array_splice( $lines, count( $lines ), 0, $edit->closing );
			}
		}

		return $lines;
	}
}

/**
 * @todo document, bad name.
 * @private
 * @ingroup DifferenceEngine
 */
class MappedDiff extends Diff {
	/**
	 * Constructor.
	 *
	 * Computes diff between sequences of strings.
	 *
	 * This can be used to compute things like
	 * case-insensitve diffs, or diffs which ignore
	 * changes in white-space.
	 *
	 * @param string[] $from_lines An array of strings.
	 *   Typically these are lines from a file.
	 * @param string[] $to_lines An array of strings.
	 * @param string[] $mapped_from_lines This array should
	 *   have the same size number of elements as $from_lines.
	 *   The elements in $mapped_from_lines and
	 *   $mapped_to_lines are what is actually compared
	 *   when computing the diff.
	 * @param string[] $mapped_to_lines This array should
	 *   have the same number of elements as $to_lines.
	 */
	public function __construct( $from_lines, $to_lines,
		$mapped_from_lines, $mapped_to_lines ) {

		assert( 'count( $from_lines ) == count( $mapped_from_lines )' );
		assert( 'count( $to_lines ) == count( $mapped_to_lines )' );

		parent::__construct( $mapped_from_lines, $mapped_to_lines );

		$xi = $yi = 0;
		$editCount = count( $this->edits );
		for ( $i = 0; $i < $editCount; $i++ ) {
			$orig = &$this->edits[$i]->orig;
			if ( is_array( $orig ) ) {
				$orig = array_slice( $from_lines, $xi, count( $orig ) );
				$xi += count( $orig );
			}

			$closing = &$this->edits[$i]->closing;
			if ( is_array( $closing ) ) {
				$closing = array_slice( $to_lines, $yi, count( $closing ) );
				$yi += count( $closing );
			}
		}
	}
}

/**
 * Additions by Axel Boldt follow, partly taken from diff.php, phpwiki-1.3.3
 */

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class HWLDFWordAccumulator {
	public $insClass = ' class="diffchange diffchange-inline"';
	public $delClass = ' class="diffchange diffchange-inline"';

	private $lines = array();
	private $line = '';
	private $group = '';
	private $tag = '';

	/**
	 * @param string $new_tag
	 */
	private function flushGroup( $new_tag ) {
		if ( $this->group !== '' ) {
			if ( $this->tag == 'ins' ) {
				$this->line .= "<ins{$this->insClass}>" .
					htmlspecialchars( $this->group ) . '</ins>';
			} elseif ( $this->tag == 'del' ) {
				$this->line .= "<del{$this->delClass}>" .
					htmlspecialchars( $this->group ) . '</del>';
			} else {
				$this->line .= htmlspecialchars( $this->group );
			}
		}
		$this->group = '';
		$this->tag = $new_tag;
	}

	/**
	 * @param string $new_tag
	 */
	private function flushLine( $new_tag ) {
		$this->flushGroup( $new_tag );
		if ( $this->line != '' ) {
			array_push( $this->lines, $this->line );
		} else {
			# make empty lines visible by inserting an NBSP
			array_push( $this->lines, '&#160;' );
		}
		$this->line = '';
	}

	/**
	 * @param string[] $words
	 * @param string $tag
	 */
	public function addWords( $words, $tag = '' ) {
		if ( $tag != $this->tag ) {
			$this->flushGroup( $tag );
		}

		foreach ( $words as $word ) {
			// new-line should only come as first char of word.
			if ( $word == '' ) {
				continue;
			}
			if ( $word[0] == "\n" ) {
				$this->flushLine( $tag );
				$word = substr( $word, 1 );
			}
			assert( '!strstr( $word, "\n" )' );
			$this->group .= $word;
		}
	}

	/**
	 * @return string[]
	 */
	public function getLines() {
		$this->flushLine( '~done' );

		return $this->lines;
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class WordLevelDiff extends MappedDiff {
	const MAX_LINE_LENGTH = 10000;

	/**
	 * @param string[] $orig_lines
	 * @param string[] $closing_lines
	 */
	public function __construct( $orig_lines, $closing_lines ) {

		list( $orig_words, $orig_stripped ) = $this->split( $orig_lines );
		list( $closing_words, $closing_stripped ) = $this->split( $closing_lines );

		parent::__construct( $orig_words, $closing_words,
			$orig_stripped, $closing_stripped );
	}

	/**
	 * @param string[] $lines
	 *
	 * @return array[]
	 */
	private function split( $lines ) {

		$words = array();
		$stripped = array();
		$first = true;
		foreach ( $lines as $line ) {
			# If the line is too long, just pretend the entire line is one big word
			# This prevents resource exhaustion problems
			if ( $first ) {
				$first = false;
			} else {
				$words[] = "\n";
				$stripped[] = "\n";
			}
			if ( strlen( $line ) > self::MAX_LINE_LENGTH ) {
				$words[] = $line;
				$stripped[] = $line;
			} else {
				$m = array();
				if ( preg_match_all( '/ ( [^\S\n]+ | [0-9_A-Za-z\x80-\xff]+ | . ) (?: (?!< \n) [^\S\n])? /xs',
					$line, $m )
				) {
					foreach ( $m[0] as $word ) {
						$words[] = $word;
					}
					foreach ( $m[1] as $stripped_word ) {
						$stripped[] = $stripped_word;
					}
				}
			}
		}

		return array( $words, $stripped );
	}

	/**
	 * @return string[]
	 */
	public function orig() {
		$orig = new HWLDFWordAccumulator;

		foreach ( $this->edits as $edit ) {
			if ( $edit->type == 'copy' ) {
				$orig->addWords( $edit->orig );
			} elseif ( $edit->orig ) {
				$orig->addWords( $edit->orig, 'del' );
			}
		}
		$lines = $orig->getLines();

		return $lines;
	}

	/**
	 * @return string[]
	 */
	public function closing() {
		$closing = new HWLDFWordAccumulator;

		foreach ( $this->edits as $edit ) {
			if ( $edit->type == 'copy' ) {
				$closing->addWords( $edit->closing );
			} elseif ( $edit->closing ) {
				$closing->addWords( $edit->closing, 'ins' );
			}
		}
		$lines = $closing->getLines();

		return $lines;
	}

}
