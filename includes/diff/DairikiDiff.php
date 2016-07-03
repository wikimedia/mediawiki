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
 * The base class for all other DiffOp classes.
 *
 * The classes that extend DiffOp are: DiffOpCopy, DiffOpDelete, DiffOpAdd and
 * DiffOpChange. FakeDiffOp also extends DiffOp, but it is not located in this file.
 *
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
 * Extends DiffOp. Used to mark strings that have been
 * copied from one string array to the other.
 *
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
 * Extends DiffOp. Used to mark strings that have been
 * deleted from the first string array.
 *
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
 * Extends DiffOp. Used to mark strings that have been
 * added from the first string array.
 *
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
 * Extends DiffOp. Used to mark strings that have been
 * changed from the first string array (both added and subtracted).
 *
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

	protected $xv = [], $yv = [];
	protected $xind = [], $yind = [];

	protected $seq = [], $in_seq = [];

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

		$edits = [];
		$xi = $yi = 0;
		while ( $xi < $n_from || $yi < $n_to ) {
			assert( $yi < $n_to || $this->xchanged[$xi] );
			assert( $xi < $n_from || $this->ychanged[$yi] );

			// Skip matching "snake".
			$copy = [];
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
			$delete = [];
			while ( $xi < $n_from && $this->xchanged[$xi] ) {
				$delete[] = $from_lines[$xi++];
			}

			$add = [];
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
		$wikidiff3 = new WikiDiff3();
		$wikidiff3->diff( $from_lines, $to_lines );
		$this->xchanged = $wikidiff3->removed;
		$this->ychanged = $wikidiff3->added;
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

		assert( count( $lines ) == count( $changed ) );
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
				assert( $j < $other_len && ! $other_changed[$j] );
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
					assert( $j > 0 );
					while ( $other_changed[--$j] ) {
						continue;
					}
					assert( $j >= 0 && !$other_changed[$j] );
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

					assert( $j < $other_len && ! $other_changed[$j] );
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
				assert( $j > 0 );
				while ( $other_changed[--$j] ) {
					continue;
				}
				assert( $j >= 0 && !$other_changed[$j] );
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
		$rev->edits = [];
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
		$lines = [];

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
		$lines = [];

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

		assert( count( $from_lines ) == count( $mapped_from_lines ) );
		assert( count( $to_lines ) == count( $mapped_to_lines ) );

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

	private $lines = [];
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
			assert( !strstr( $word, "\n" ) );
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

		$words = [];
		$stripped = [];
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
				$m = [];
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

		return [ $words, $stripped ];
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
