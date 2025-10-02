<?php
/**
 * Copyright © 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * You may copy this code freely under the conditions of the GPL.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 * @defgroup DifferenceEngine DifferenceEngine
 */

namespace Wikimedia\Diff;

/**
 * Performs a word-level diff on several lines
 *
 * @newable
 * @ingroup DifferenceEngine
 */
class WordLevelDiff extends Diff {
	/**
	 * @inheritDoc
	 */
	protected $bailoutComplexity = 40_000_000; // Roughly 6K x 6K words changed

	/**
	 * @stable to call
	 * @todo Don't do work in the constructor, use a service to create diffs instead (T257472).
	 *
	 * @param string[] $linesBefore
	 * @param string[] $linesAfter
	 */
	public function __construct( $linesBefore, $linesAfter ) {
		[ $wordsBefore, $wordsBeforeStripped ] = $this->split( $linesBefore );
		[ $wordsAfter, $wordsAfterStripped ] = $this->split( $linesAfter );

		try {
			parent::__construct( $wordsBeforeStripped, $wordsAfterStripped );
		} catch ( ComplexityException ) {
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

/** @deprecated class alias since 1.41 */
class_alias( WordLevelDiff::class, 'WordLevelDiff' );
