<?php
/**
 * Portions taken from phpwiki-1.3.3.
 *
 * Copyright © 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * You may copy this code freely under the conditions of the GPL.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 */

namespace Wikimedia\Diff;

/**
 * A formatter that outputs unified diffs
 * @newable
 * @ingroup DifferenceEngine
 */
class UnifiedDiffFormatter extends DiffFormatter {

	/** @var int */
	protected $leadingContextLines = 2;

	/** @var int */
	protected $trailingContextLines = 2;

	/**
	 * @param string[] $lines
	 * @param string $prefix
	 */
	protected function lines( $lines, $prefix = ' ' ) {
		foreach ( $lines as $line ) {
			$this->writeOutput( "{$prefix}{$line}\n" );
		}
	}

	/**
	 * @param string[] $lines
	 */
	protected function added( $lines ) {
		$this->lines( $lines, '+' );
	}

	/**
	 * @param string[] $lines
	 */
	protected function deleted( $lines ) {
		$this->lines( $lines, '-' );
	}

	/**
	 * @param string[] $orig
	 * @param string[] $closing
	 */
	protected function changed( $orig, $closing ) {
		$this->deleted( $orig );
		$this->added( $closing );
	}

	/**
	 * @param int $xbeg
	 * @param int $xlen
	 * @param int $ybeg
	 * @param int $ylen
	 *
	 * @return string
	 */
	protected function blockHeader( $xbeg, $xlen, $ybeg, $ylen ) {
		return "@@ -$xbeg,$xlen +$ybeg,$ylen @@";
	}

}

/** @deprecated class alias since 1.41 */
class_alias( UnifiedDiffFormatter::class, 'UnifiedDiffFormatter' );
