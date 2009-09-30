<?php
/**
 * @defgroup DifferenceEngine DifferenceEngine
 */

// A PHP diff engine for phpwiki. (Taken from phpwiki-1.3.3)
//
// Copyright (C) 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
// You may copy this code freely under the conditions of the GPL.
//

define('USE_ASSERTS', function_exists('assert'));

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp {
	var $type;
	var $orig;
	var $closing;

	function reverse() {
		trigger_error('pure virtual', E_USER_ERROR);
	}

	function norig() {
		return $this->orig ? sizeof($this->orig) : 0;
	}

	function nclosing() {
		return $this->closing ? sizeof($this->closing) : 0;
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Copy extends _DiffOp {
	var $type = 'copy';

	function _DiffOp_Copy ($orig, $closing = false) {
		if (!is_array($closing))
		$closing = $orig;
		$this->orig = $orig;
		$this->closing = $closing;
	}

	function reverse() {
		return new _DiffOp_Copy($this->closing, $this->orig);
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Delete extends _DiffOp {
	var $type = 'delete';

	function _DiffOp_Delete ($lines) {
		$this->orig = $lines;
		$this->closing = false;
	}

	function reverse() {
		return new _DiffOp_Add($this->orig);
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Add extends _DiffOp {
	var $type = 'add';

	function _DiffOp_Add ($lines) {
		$this->closing = $lines;
		$this->orig = false;
	}

	function reverse() {
		return new _DiffOp_Delete($this->closing);
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _DiffOp_Change extends _DiffOp {
	var $type = 'change';

	function _DiffOp_Change ($orig, $closing) {
		$this->orig = $orig;
		$this->closing = $closing;
	}

	function reverse() {
		return new _DiffOp_Change($this->closing, $this->orig);
	}
}

/**
 * Class used internally by Diff to actually compute the diffs.
 *
 * The algorithm used here is mostly lifted from the perl module
 * Algorithm::Diff (version 1.06) by Ned Konz, which is available at:
 *	 http://www.perl.com/CPAN/authors/id/N/NE/NEDKONZ/Algorithm-Diff-1.06.zip
 *
 * More ideas are taken from:
 *	 http://www.ics.uci.edu/~eppstein/161/960229.html
 *
 * Some ideas are (and a bit of code) are from from analyze.c, from GNU
 * diffutils-2.7, which can be found at:
 *	 ftp://gnudist.gnu.org/pub/gnu/diffutils/diffutils-2.7.tar.gz
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
class _DiffEngine {

	const MAX_XREF_LENGTH =  10000;

	function diff ($from_lines, $to_lines){
		wfProfileIn( __METHOD__ );

		// Diff and store locally
		$this->diff_local($from_lines, $to_lines);

		// Merge edits when possible
		$this->_shift_boundaries($from_lines, $this->xchanged, $this->ychanged);
		$this->_shift_boundaries($to_lines, $this->ychanged, $this->xchanged);

		// Compute the edit operations.
		$n_from = sizeof($from_lines);
		$n_to = sizeof($to_lines);

		$edits = array();
		$xi = $yi = 0;
		while ($xi < $n_from || $yi < $n_to) {
			USE_ASSERTS && assert($yi < $n_to || $this->xchanged[$xi]);
			USE_ASSERTS && assert($xi < $n_from || $this->ychanged[$yi]);

			// Skip matching "snake".
			$copy = array();
			while ( $xi < $n_from && $yi < $n_to
			&& !$this->xchanged[$xi] && !$this->ychanged[$yi]) {
				$copy[] = $from_lines[$xi++];
				++$yi;
			}
			if ($copy)
			$edits[] = new _DiffOp_Copy($copy);

			// Find deletes & adds.
			$delete = array();
			while ($xi < $n_from && $this->xchanged[$xi])
			$delete[] = $from_lines[$xi++];

			$add = array();
			while ($yi < $n_to && $this->ychanged[$yi])
			$add[] = $to_lines[$yi++];

			if ($delete && $add)
			$edits[] = new _DiffOp_Change($delete, $add);
			elseif ($delete)
			$edits[] = new _DiffOp_Delete($delete);
			elseif ($add)
			$edits[] = new _DiffOp_Add($add);
		}
		wfProfileOut( __METHOD__ );
		return $edits;
	}

	function diff_local ($from_lines, $to_lines) {
		global $wgExternalDiffEngine;
		wfProfileIn( __METHOD__);

		if($wgExternalDiffEngine == 'wikidiff3'){
			// wikidiff3
			$wikidiff3 = new WikiDiff3();
			$wikidiff3->diff($from_lines, $to_lines);
			$this->xchanged = $wikidiff3->removed;
			$this->ychanged = $wikidiff3->added;
			unset($wikidiff3);
		}else{
			// old diff
			$n_from = sizeof($from_lines);
			$n_to = sizeof($to_lines);
			$this->xchanged = $this->ychanged = array();
			$this->xv = $this->yv = array();
			$this->xind = $this->yind = array();
			unset($this->seq);
			unset($this->in_seq);
			unset($this->lcs);

			// Skip leading common lines.
			for ($skip = 0; $skip < $n_from && $skip < $n_to; $skip++) {
				if ($from_lines[$skip] !== $to_lines[$skip])
				break;
				$this->xchanged[$skip] = $this->ychanged[$skip] = false;
			}
			// Skip trailing common lines.
			$xi = $n_from; $yi = $n_to;
			for ($endskip = 0; --$xi > $skip && --$yi > $skip; $endskip++) {
				if ($from_lines[$xi] !== $to_lines[$yi])
				break;
				$this->xchanged[$xi] = $this->ychanged[$yi] = false;
			}

			// Ignore lines which do not exist in both files.
			for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
				$xhash[$this->_line_hash($from_lines[$xi])] = 1;
			}

			for ($yi = $skip; $yi < $n_to - $endskip; $yi++) {
				$line = $to_lines[$yi];
				if ( ($this->ychanged[$yi] = empty($xhash[$this->_line_hash($line)])) )
				continue;
				$yhash[$this->_line_hash($line)] = 1;
				$this->yv[] = $line;
				$this->yind[] = $yi;
			}
			for ($xi = $skip; $xi < $n_from - $endskip; $xi++) {
				$line = $from_lines[$xi];
				if ( ($this->xchanged[$xi] = empty($yhash[$this->_line_hash($line)])) )
				continue;
				$this->xv[] = $line;
				$this->xind[] = $xi;
			}

			// Find the LCS.
			$this->_compareseq(0, sizeof($this->xv), 0, sizeof($this->yv));
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Returns the whole line if it's small enough, or the MD5 hash otherwise
	 */
	function _line_hash( $line ) {
		if ( strlen( $line ) > self::MAX_XREF_LENGTH ) {
			return md5( $line );
		} else {
			return $line;
		}
	}

	/* Divide the Largest Common Subsequence (LCS) of the sequences
	 * [XOFF, XLIM) and [YOFF, YLIM) into NCHUNKS approximately equally
	 * sized segments.
	 *
	 * Returns (LCS, PTS).	LCS is the length of the LCS. PTS is an
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
	 */
	function _diag ($xoff, $xlim, $yoff, $ylim, $nchunks) {
		$flip = false;

		if ($xlim - $xoff > $ylim - $yoff) {
			// Things seems faster (I'm not sure I understand why)
			// when the shortest sequence in X.
			$flip = true;
			list ($xoff, $xlim, $yoff, $ylim)
			= array( $yoff, $ylim, $xoff, $xlim);
		}

		if ($flip)
		for ($i = $ylim - 1; $i >= $yoff; $i--)
		$ymatches[$this->xv[$i]][] = $i;
		else
		for ($i = $ylim - 1; $i >= $yoff; $i--)
		$ymatches[$this->yv[$i]][] = $i;

		$this->lcs = 0;
		$this->seq[0]= $yoff - 1;
		$this->in_seq = array();
		$ymids[0] = array();

		$numer = $xlim - $xoff + $nchunks - 1;
		$x = $xoff;
		for ($chunk = 0; $chunk < $nchunks; $chunk++) {
			if ($chunk > 0)
			for ($i = 0; $i <= $this->lcs; $i++)
			$ymids[$i][$chunk-1] = $this->seq[$i];

			$x1 = $xoff + (int)(($numer + ($xlim-$xoff)*$chunk) / $nchunks);
			for ( ; $x < $x1; $x++) {
				$line = $flip ? $this->yv[$x] : $this->xv[$x];
				if (empty($ymatches[$line]))
				continue;
				$matches = $ymatches[$line];
				reset($matches);
				while (list ($junk, $y) = each($matches))
				if (empty($this->in_seq[$y])) {
					$k = $this->_lcs_pos($y);
					USE_ASSERTS && assert($k > 0);
					$ymids[$k] = $ymids[$k-1];
					break;
				}
				while (list ( /* $junk */, $y) = each($matches)) {
					if ($y > $this->seq[$k-1]) {
						USE_ASSERTS && assert($y < $this->seq[$k]);
						// Optimization: this is a common case:
						//	next match is just replacing previous match.
						$this->in_seq[$this->seq[$k]] = false;
						$this->seq[$k] = $y;
						$this->in_seq[$y] = 1;
					} else if (empty($this->in_seq[$y])) {
						$k = $this->_lcs_pos($y);
						USE_ASSERTS && assert($k > 0);
						$ymids[$k] = $ymids[$k-1];
					}
				}
			}
		}

		$seps[] = $flip ? array($yoff, $xoff) : array($xoff, $yoff);
		$ymid = $ymids[$this->lcs];
		for ($n = 0; $n < $nchunks - 1; $n++) {
			$x1 = $xoff + (int)(($numer + ($xlim - $xoff) * $n) / $nchunks);
			$y1 = $ymid[$n] + 1;
			$seps[] = $flip ? array($y1, $x1) : array($x1, $y1);
		}
		$seps[] = $flip ? array($ylim, $xlim) : array($xlim, $ylim);

		return array($this->lcs, $seps);
	}

	function _lcs_pos ($ypos) {
		$end = $this->lcs;
		if ($end == 0 || $ypos > $this->seq[$end]) {
			$this->seq[++$this->lcs] = $ypos;
			$this->in_seq[$ypos] = 1;
			return $this->lcs;
		}

		$beg = 1;
		while ($beg < $end) {
			$mid = (int)(($beg + $end) / 2);
			if ( $ypos > $this->seq[$mid] )
			$beg = $mid + 1;
			else
			$end = $mid;
		}

		USE_ASSERTS && assert($ypos != $this->seq[$end]);

		$this->in_seq[$this->seq[$end]] = false;
		$this->seq[$end] = $ypos;
		$this->in_seq[$ypos] = 1;
		return $end;
	}

	/* Find LCS of two sequences.
	 *
	 * The results are recorded in the vectors $this->{x,y}changed[], by
	 * storing a 1 in the element for each line that is an insertion
	 * or deletion (ie. is not in the LCS).
	 *
	 * The subsequence of file 0 is [XOFF, XLIM) and likewise for file 1.
	 *
	 * Note that XLIM, YLIM are exclusive bounds.
	 * All line numbers are origin-0 and discarded lines are not counted.
	 */
	function _compareseq ($xoff, $xlim, $yoff, $ylim) {
		// Slide down the bottom initial diagonal.
		while ($xoff < $xlim && $yoff < $ylim
		&& $this->xv[$xoff] == $this->yv[$yoff]) {
			++$xoff;
			++$yoff;
		}

		// Slide up the top initial diagonal.
		while ($xlim > $xoff && $ylim > $yoff
		&& $this->xv[$xlim - 1] == $this->yv[$ylim - 1]) {
			--$xlim;
			--$ylim;
		}

		if ($xoff == $xlim || $yoff == $ylim)
		$lcs = 0;
		else {
			// This is ad hoc but seems to work well.
			//$nchunks = sqrt(min($xlim - $xoff, $ylim - $yoff) / 2.5);
			//$nchunks = max(2,min(8,(int)$nchunks));
			$nchunks = min(7, $xlim - $xoff, $ylim - $yoff) + 1;
			list ($lcs, $seps)
			= $this->_diag($xoff,$xlim,$yoff, $ylim,$nchunks);
		}

		if ($lcs == 0) {
			// X and Y sequences have no common subsequence:
			// mark all changed.
			while ($yoff < $ylim)
			$this->ychanged[$this->yind[$yoff++]] = 1;
			while ($xoff < $xlim)
			$this->xchanged[$this->xind[$xoff++]] = 1;
		} else {
			// Use the partitions to split this problem into subproblems.
			reset($seps);
			$pt1 = $seps[0];
			while ($pt2 = next($seps)) {
				$this->_compareseq ($pt1[0], $pt2[0], $pt1[1], $pt2[1]);
				$pt1 = $pt2;
			}
		}
	}

	/* Adjust inserts/deletes of identical lines to join changes
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
	function _shift_boundaries ($lines, &$changed, $other_changed) {
		wfProfileIn( __METHOD__ );
		$i = 0;
		$j = 0;

		USE_ASSERTS && assert('sizeof($lines) == sizeof($changed)');
		$len = sizeof($lines);
		$other_len = sizeof($other_changed);

		while (1) {
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
			while ($j < $other_len && $other_changed[$j])
			$j++;

			while ($i < $len && ! $changed[$i]) {
				USE_ASSERTS && assert('$j < $other_len && ! $other_changed[$j]');
				$i++; $j++;
				while ($j < $other_len && $other_changed[$j])
				$j++;
			}

			if ($i == $len)
			break;

			$start = $i;

			// Find the end of this run of changes.
			while (++$i < $len && $changed[$i])
			continue;

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
				while ($start > 0 && $lines[$start - 1] == $lines[$i - 1]) {
					$changed[--$start] = 1;
					$changed[--$i] = false;
					while ($start > 0 && $changed[$start - 1])
					$start--;
					USE_ASSERTS && assert('$j > 0');
					while ($other_changed[--$j])
					continue;
					USE_ASSERTS && assert('$j >= 0 && !$other_changed[$j]');
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
				while ($i < $len && $lines[$start] == $lines[$i]) {
					$changed[$start++] = false;
					$changed[$i++] = 1;
					while ($i < $len && $changed[$i])
					$i++;

					USE_ASSERTS && assert('$j < $other_len && ! $other_changed[$j]');
					$j++;
					if ($j < $other_len && $other_changed[$j]) {
						$corresponding = $i;
						while ($j < $other_len && $other_changed[$j])
						$j++;
					}
				}
			} while ($runlength != $i - $start);

			/*
			 * If possible, move the fully-merged run of changes
			 * back to a corresponding run in the other file.
			 */
			while ($corresponding < $i) {
				$changed[--$start] = 1;
				$changed[--$i] = 0;
				USE_ASSERTS && assert('$j > 0');
				while ($other_changed[--$j])
				continue;
				USE_ASSERTS && assert('$j >= 0 && !$other_changed[$j]');
			}
		}
		wfProfileOut( __METHOD__ );
	}
}

/**
 * Class representing a 'diff' between two sequences of strings.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class Diff
{
	var $edits;

	/**
	 * Constructor.
	 * Computes diff between sequences of strings.
	 *
	 * @param $from_lines array An array of strings.
	 *		  (Typically these are lines from a file.)
	 * @param $to_lines array An array of strings.
	 */
	function Diff($from_lines, $to_lines) {
		$eng = new _DiffEngine;
		$this->edits = $eng->diff($from_lines, $to_lines);
		//$this->_check($from_lines, $to_lines);
	}

	/**
	 * Compute reversed Diff.
	 *
	 * SYNOPSIS:
	 *
	 *	$diff = new Diff($lines1, $lines2);
	 *	$rev = $diff->reverse();
	 * @return object A Diff object representing the inverse of the
	 *				  original diff.
	 */
	function reverse () {
		$rev = $this;
		$rev->edits = array();
		foreach ($this->edits as $edit) {
			$rev->edits[] = $edit->reverse();
		}
		return $rev;
	}

	/**
	 * Check for empty diff.
	 *
	 * @return bool True iff two sequences were identical.
	 */
	function isEmpty () {
		foreach ($this->edits as $edit) {
			if ($edit->type != 'copy')
			return false;
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
	function lcs () {
		$lcs = 0;
		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
			$lcs += sizeof($edit->orig);
		}
		return $lcs;
	}

	/**
	 * Get the original set of lines.
	 *
	 * This reconstructs the $from_lines parameter passed to the
	 * constructor.
	 *
	 * @return array The original sequence of strings.
	 */
	function orig() {
		$lines = array();

		foreach ($this->edits as $edit) {
			if ($edit->orig)
			array_splice($lines, sizeof($lines), 0, $edit->orig);
		}
		return $lines;
	}

	/**
	 * Get the closing set of lines.
	 *
	 * This reconstructs the $to_lines parameter passed to the
	 * constructor.
	 *
	 * @return array The sequence of strings.
	 */
	function closing() {
		$lines = array();

		foreach ($this->edits as $edit) {
			if ($edit->closing)
			array_splice($lines, sizeof($lines), 0, $edit->closing);
		}
		return $lines;
	}

	/**
	 * Check a Diff for validity.
	 *
	 * This is here only for debugging purposes.
	 */
	function _check ($from_lines, $to_lines) {
		wfProfileIn( __METHOD__ );
		if (serialize($from_lines) != serialize($this->orig()))
		trigger_error("Reconstructed original doesn't match", E_USER_ERROR);
		if (serialize($to_lines) != serialize($this->closing()))
		trigger_error("Reconstructed closing doesn't match", E_USER_ERROR);

		$rev = $this->reverse();
		if (serialize($to_lines) != serialize($rev->orig()))
		trigger_error("Reversed original doesn't match", E_USER_ERROR);
		if (serialize($from_lines) != serialize($rev->closing()))
		trigger_error("Reversed closing doesn't match", E_USER_ERROR);


		$prevtype = 'none';
		foreach ($this->edits as $edit) {
			if ( $prevtype == $edit->type )
			trigger_error("Edit sequence is non-optimal", E_USER_ERROR);
			$prevtype = $edit->type;
		}

		$lcs = $this->lcs();
		trigger_error('Diff okay: LCS = '.$lcs, E_USER_NOTICE);
		wfProfileOut( __METHOD__ );
	}
}

/**
 * @todo document, bad name.
 * @private
 * @ingroup DifferenceEngine
 */
class MappedDiff extends Diff
{
	/**
	 * Constructor.
	 *
	 * Computes diff between sequences of strings.
	 *
	 * This can be used to compute things like
	 * case-insensitve diffs, or diffs which ignore
	 * changes in white-space.
	 *
	 * @param $from_lines array An array of strings.
	 *	(Typically these are lines from a file.)
	 *
	 * @param $to_lines array An array of strings.
	 *
	 * @param $mapped_from_lines array This array should
	 *	have the same size number of elements as $from_lines.
	 *	The elements in $mapped_from_lines and
	 *	$mapped_to_lines are what is actually compared
	 *	when computing the diff.
	 *
	 * @param $mapped_to_lines array This array should
	 *	have the same number of elements as $to_lines.
	 */
	function MappedDiff($from_lines, $to_lines,
	$mapped_from_lines, $mapped_to_lines) {
		wfProfileIn( __METHOD__ );

		assert(sizeof($from_lines) == sizeof($mapped_from_lines));
		assert(sizeof($to_lines) == sizeof($mapped_to_lines));

		$this->Diff($mapped_from_lines, $mapped_to_lines);

		$xi = $yi = 0;
		for ($i = 0; $i < sizeof($this->edits); $i++) {
			$orig = &$this->edits[$i]->orig;
			if (is_array($orig)) {
				$orig = array_slice($from_lines, $xi, sizeof($orig));
				$xi += sizeof($orig);
			}

			$closing = &$this->edits[$i]->closing;
			if (is_array($closing)) {
				$closing = array_slice($to_lines, $yi, sizeof($closing));
				$yi += sizeof($closing);
			}
		}
		wfProfileOut( __METHOD__ );
	}
}

/**
 * A class to format Diffs
 *
 * This class formats the diff in classic diff format.
 * It is intended that this class be customized via inheritance,
 * to obtain fancier outputs.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class DiffFormatter {
	/**
	 * Number of leading context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	var $leading_context_lines = 0;

	/**
	 * Number of trailing context "lines" to preserve.
	 *
	 * This should be left at zero for this class, but subclasses
	 * may want to set this to other values.
	 */
	var $trailing_context_lines = 0;

	/**
	 * Format a diff.
	 *
	 * @param $diff object A Diff object.
	 * @return string The formatted output.
	 */
	function format($diff) {
		wfProfileIn( __METHOD__ );

		$xi = $yi = 1;
		$block = false;
		$context = array();

		$nlead = $this->leading_context_lines;
		$ntrail = $this->trailing_context_lines;

		$this->_start_diff();

		foreach ($diff->edits as $edit) {
			if ($edit->type == 'copy') {
				if (is_array($block)) {
					if (sizeof($edit->orig) <= $nlead + $ntrail) {
						$block[] = $edit;
					}
					else{
						if ($ntrail) {
							$context = array_slice($edit->orig, 0, $ntrail);
							$block[] = new _DiffOp_Copy($context);
						}
						$this->_block($x0, $ntrail + $xi - $x0,
						$y0, $ntrail + $yi - $y0,
						$block);
						$block = false;
					}
				}
				$context = $edit->orig;
			}
			else {
				if (! is_array($block)) {
					$context = array_slice($context, sizeof($context) - $nlead);
					$x0 = $xi - sizeof($context);
					$y0 = $yi - sizeof($context);
					$block = array();
					if ($context)
					$block[] = new _DiffOp_Copy($context);
				}
				$block[] = $edit;
			}

			if ($edit->orig)
			$xi += sizeof($edit->orig);
			if ($edit->closing)
			$yi += sizeof($edit->closing);
		}

		if (is_array($block))
		$this->_block($x0, $xi - $x0,
		$y0, $yi - $y0,
		$block);

		$end = $this->_end_diff();
		wfProfileOut( __METHOD__ );
		return $end;
	}

	function _block($xbeg, $xlen, $ybeg, $ylen, &$edits) {
		wfProfileIn( __METHOD__ );
		$this->_start_block($this->_block_header($xbeg, $xlen, $ybeg, $ylen));
		foreach ($edits as $edit) {
			if ($edit->type == 'copy')
			$this->_context($edit->orig);
			elseif ($edit->type == 'add')
			$this->_added($edit->closing);
			elseif ($edit->type == 'delete')
			$this->_deleted($edit->orig);
			elseif ($edit->type == 'change')
			$this->_changed($edit->orig, $edit->closing);
			else
			trigger_error('Unknown edit type', E_USER_ERROR);
		}
		$this->_end_block();
		wfProfileOut( __METHOD__ );
	}

	function _start_diff() {
		ob_start();
	}

	function _end_diff() {
		$val = ob_get_contents();
		ob_end_clean();
		return $val;
	}

	function _block_header($xbeg, $xlen, $ybeg, $ylen) {
		if ($xlen > 1)
		$xbeg .= "," . ($xbeg + $xlen - 1);
		if ($ylen > 1)
		$ybeg .= "," . ($ybeg + $ylen - 1);

		return $xbeg . ($xlen ? ($ylen ? 'c' : 'd') : 'a') . $ybeg;
	}

	function _start_block($header) {
		echo $header . "\n";
	}

	function _end_block() {
	}

	function _lines($lines, $prefix = ' ') {
		foreach ($lines as $line)
		echo "$prefix $line\n";
	}

	function _context($lines) {
		$this->_lines($lines);
	}

	function _added($lines) {
		$this->_lines($lines, '>');
	}
	function _deleted($lines) {
		$this->_lines($lines, '<');
	}

	function _changed($orig, $closing) {
		$this->_deleted($orig);
		echo "---\n";
		$this->_added($closing);
	}
}

/**
 * A formatter that outputs unified diffs
 * @ingroup DifferenceEngine
 */

class UnifiedDiffFormatter extends DiffFormatter {
	var $leading_context_lines = 2;
	var $trailing_context_lines = 2;

	function _added($lines) {
		$this->_lines($lines, '+');
	}
	function _deleted($lines) {
		$this->_lines($lines, '-');
	}
	function _changed($orig, $closing) {
		$this->_deleted($orig);
		$this->_added($closing);
	}
	function _block_header($xbeg, $xlen, $ybeg, $ylen) {
		return "@@ -$xbeg,$xlen +$ybeg,$ylen @@";
	}
}

/**
 * A pseudo-formatter that just passes along the Diff::$edits array
 * @ingroup DifferenceEngine
 */
class ArrayDiffFormatter extends DiffFormatter {
	function format($diff) {
		$oldline = 1;
		$newline = 1;
		$retval = array();
		foreach($diff->edits as $edit)
		switch($edit->type) {
			case 'add':
				foreach($edit->closing as $l) {
					$retval[] = array(
							'action' => 'add',
							'new'=> $l,
							'newline' => $newline++
					);
				}
				break;
			case 'delete':
				foreach($edit->orig as $l) {
					$retval[] = array(
							'action' => 'delete',
							'old' => $l,
							'oldline' => $oldline++,
					);
				}
				break;
			case 'change':
				foreach($edit->orig as $i => $l) {
					$retval[] = array(
							'action' => 'change',
							'old' => $l,
							'new' => @$edit->closing[$i],
							'oldline' => $oldline++,
							'newline' => $newline++,
					);
				}
				break;
			case 'copy':
				$oldline += count($edit->orig);
				$newline += count($edit->orig);
		}
		return $retval;
	}
}

/**
 *	Additions by Axel Boldt follow, partly taken from diff.php, phpwiki-1.3.3
 *
 */

define('NBSP', '&#160;'); // iso-8859-x non-breaking space.

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class _HWLDF_WordAccumulator {
	function _HWLDF_WordAccumulator () {
		$this->_lines = array();
		$this->_line = '';
		$this->_group = '';
		$this->_tag = '';
	}

	function _flushGroup ($new_tag) {
		if ($this->_group !== '') {
			if ($this->_tag == 'ins')
			$this->_line .= '<ins class="diffchange diffchange-inline">' .
			htmlspecialchars ( $this->_group ) . '</ins>';
			elseif ($this->_tag == 'del')
			$this->_line .= '<del class="diffchange diffchange-inline">' .
			htmlspecialchars ( $this->_group ) . '</del>';
			else
			$this->_line .= htmlspecialchars ( $this->_group );
		}
		$this->_group = '';
		$this->_tag = $new_tag;
	}

	function _flushLine ($new_tag) {
		$this->_flushGroup($new_tag);
		if ($this->_line != '')
		array_push ( $this->_lines, $this->_line );
		else
		# make empty lines visible by inserting an NBSP
		array_push ( $this->_lines, NBSP );
		$this->_line = '';
	}

	function addWords ($words, $tag = '') {
		if ($tag != $this->_tag)
		$this->_flushGroup($tag);

		foreach ($words as $word) {
			// new-line should only come as first char of word.
			if ($word == '')
			continue;
			if ($word[0] == "\n") {
				$this->_flushLine($tag);
				$word = substr($word, 1);
			}
			assert(!strstr($word, "\n"));
			$this->_group .= $word;
		}
	}

	function getLines() {
		$this->_flushLine('~done');
		return $this->_lines;
	}
}

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class WordLevelDiff extends MappedDiff {
	const MAX_LINE_LENGTH = 10000;

	function WordLevelDiff ($orig_lines, $closing_lines) {
		wfProfileIn( __METHOD__ );

		list ($orig_words, $orig_stripped) = $this->_split($orig_lines);
		list ($closing_words, $closing_stripped) = $this->_split($closing_lines);

		$this->MappedDiff($orig_words, $closing_words,
		$orig_stripped, $closing_stripped);
		wfProfileOut( __METHOD__ );
	}

	function _split($lines) {
		wfProfileIn( __METHOD__ );

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
				if (preg_match_all('/ ( [^\S\n]+ | [0-9_A-Za-z\x80-\xff]+ | . ) (?: (?!< \n) [^\S\n])? /xs',
				$line, $m))
				{
					$words = array_merge( $words, $m[0] );
					$stripped = array_merge( $stripped, $m[1] );
				}
			}
		}
		wfProfileOut( __METHOD__ );
		return array($words, $stripped);
	}

	function orig () {
		wfProfileIn( __METHOD__ );
		$orig = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
			$orig->addWords($edit->orig);
			elseif ($edit->orig)
			$orig->addWords($edit->orig, 'del');
		}
		$lines = $orig->getLines();
		wfProfileOut( __METHOD__ );
		return $lines;
	}

	function closing () {
		wfProfileIn( __METHOD__ );
		$closing = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit) {
			if ($edit->type == 'copy')
			$closing->addWords($edit->closing);
			elseif ($edit->closing)
			$closing->addWords($edit->closing, 'ins');
		}
		$lines = $closing->getLines();
		wfProfileOut( __METHOD__ );
		return $lines;
	}
}

/**
 * Wikipedia Table style diff formatter.
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class TableDiffFormatter extends DiffFormatter {
	function TableDiffFormatter() {
		$this->leading_context_lines = 2;
		$this->trailing_context_lines = 2;
	}

	public static function escapeWhiteSpace( $msg ) {
		$msg = preg_replace( '/^ /m', '&nbsp; ', $msg );
		$msg = preg_replace( '/ $/m', ' &nbsp;', $msg );
		$msg = preg_replace( '/  /', '&nbsp; ', $msg );
		return $msg;
	}

	function _block_header( $xbeg, $xlen, $ybeg, $ylen ) {
		$r = '<tr><td colspan="2" class="diff-lineno"><!--LINE '.$xbeg."--></td>\n" .
		  '<td colspan="2" class="diff-lineno"><!--LINE '.$ybeg."--></td></tr>\n";
		return $r;
	}

	function _start_block( $header ) {
		echo $header;
	}

	function _end_block() {
	}

	function _lines( $lines, $prefix=' ', $color='white' ) {
	}

	# HTML-escape parameter before calling this
	function addedLine( $line ) {
		return $this->wrapLine( '+', 'diff-addedline', $line );
	}

	# HTML-escape parameter before calling this
	function deletedLine( $line ) {
		return $this->wrapLine( '-', 'diff-deletedline', $line );
	}

	# HTML-escape parameter before calling this
	function contextLine( $line ) {
		return $this->wrapLine( ' ', 'diff-context', $line );
	}

	private function wrapLine( $marker, $class, $line ) {
		if( $line !== '' ) {
			// The <div> wrapper is needed for 'overflow: auto' style to scroll properly
			$line = Xml::tags( 'div', null, $this->escapeWhiteSpace( $line ) );
		}
		return "<td class='diff-marker'>$marker</td><td class='$class'>$line</td>";
	}

	function emptyLine() {
		return '<td colspan="2">&nbsp;</td>';
	}

	function _added( $lines ) {
		foreach ($lines as $line) {
			echo '<tr>' . $this->emptyLine() .
			$this->addedLine( '<ins class="diffchange">' .
			htmlspecialchars ( $line ) . '</ins>' ) . "</tr>\n";
		}
	}

	function _deleted($lines) {
		foreach ($lines as $line) {
			echo '<tr>' . $this->deletedLine( '<del class="diffchange">' .
			htmlspecialchars ( $line ) . '</del>' ) .
			$this->emptyLine() . "</tr>\n";
		}
	}

	function _context( $lines ) {
		foreach ($lines as $line) {
			echo '<tr>' .
			$this->contextLine( htmlspecialchars ( $line ) ) .
			$this->contextLine( htmlspecialchars ( $line ) ) . "</tr>\n";
		}
	}

	function _changed( $orig, $closing ) {
		wfProfileIn( __METHOD__ );

		$diff = new WordLevelDiff( $orig, $closing );
		$del = $diff->orig();
		$add = $diff->closing();

		# Notice that WordLevelDiff returns HTML-escaped output.
		# Hence, we will be calling addedLine/deletedLine without HTML-escaping.

		while ( $line = array_shift( $del ) ) {
			$aline = array_shift( $add );
			echo '<tr>' . $this->deletedLine( $line ) .
			$this->addedLine( $aline ) . "</tr>\n";
		}
		foreach ($add as $line) {	# If any leftovers
			echo '<tr>' . $this->emptyLine() .
			$this->addedLine( $line ) . "</tr>\n";
		}
		wfProfileOut( __METHOD__ );
	}
}
