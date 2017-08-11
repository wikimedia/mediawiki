<?php
/**
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

use MediaWiki\Diff\ComplexityException;
use MediaWiki\Diff\WordAccumulator;

/**
 * Performs a word-level diff on several lines
 *
 * @ingroup DifferenceEngine
 */
class WordLevelDiff extends \Diff {
	/**
	 * @inheritDoc
	 */
	protected $bailoutComplexity = 40000000; // Roughly 6K x 6K words changed

	/**
	 * @param string[] $linesBefore
	 * @param string[] $linesAfter
	 */
	public function __construct( $linesBefore, $linesAfter ) {
		list( $wordsBefore, $wordsBeforeStripped ) = $this->split( $linesBefore );
		list( $wordsAfter, $wordsAfterStripped ) = $this->split( $linesAfter );

		try {
			parent::__construct( $wordsBeforeStripped, $wordsAfterStripped );
		} catch ( ComplexityException $ex ) {
			// Too hard to diff, just show whole paragraph(s) as changed
			$this->edits = [ new DiffOpChange( $linesBefore, $linesAfter ) ];
		}

		$xi = $yi = 0;
		$editCount = count( $this->edits );
		for ( $i = 0; $i < $editCount; $i++ ) {
			$orig = &$this->edits[$i]->orig;
			if ( is_array( $orig ) ) {
				$orig = array_slice( $wordsBefore, $xi, count( $orig ) );
				$xi += count( $orig );
			}

			$closing = &$this->edits[$i]->closing;
			if ( is_array( $closing ) ) {
				$closing = array_slice( $wordsAfter, $yi, count( $closing ) );
				$yi += count( $closing );
			}
		}
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
			if ( $first ) {
				$first = false;
			} else {
				$words[] = "\n";
				$stripped[] = "\n";
			}
			$m = [];
			if ( preg_match_all( '/ ( [^\S\n]+ | [0-9_A-Za-z\x80-\xff]+ | . ) (?: (?!< \n) [^\S\n])? /xs',
				$line, $m ) ) {
				foreach ( $m[0] as $word ) {
					$words[] = $word;
				}
				foreach ( $m[1] as $stripped_word ) {
					$stripped[] = $stripped_word;
				}
			}
		}

		return [ $words, $stripped ];
	}

	/**
	 * @return string[]
	 */
	public function orig() {
		$orig = new WordAccumulator;

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
		$closing = new WordAccumulator;

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
