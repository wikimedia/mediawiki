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
 */

/**
 * Class representing a 'diff' between two sequences of strings.
 * @private
 * @ingroup DifferenceEngine
 */
class Diff {

	/**
	 * @var DiffOp[]
	 */
	public $edits;

	/**
	 * @var int If this diff complexity is exceeded, a ComplexityException is thrown
	 *          0 means no limit.
	 */
	protected $bailoutComplexity = 0;

	/**
	 * Computes diff between sequences of strings.
	 *
	 * @param string[] $from_lines An array of strings.
	 *   Typically these are lines from a file.
	 * @param string[] $to_lines An array of strings.
	 * @throws \MediaWiki\Diff\ComplexityException
	 */
	public function __construct( $from_lines, $to_lines ) {
		$eng = new DiffEngine;
		$eng->setBailoutComplexity( $this->bailoutComplexity );
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
