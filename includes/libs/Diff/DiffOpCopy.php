<?php

/**
 * A PHP diff engine for phpwiki. (Taken from phpwiki-1.3.3)
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
 * Extends DiffOp. Used to mark strings that have been
 * copied from one string array to the other.
 *
 * @ingroup DifferenceEngine
 */
class DiffOpCopy extends DiffOp {
	/** @inheritDoc */
	public $type = 'copy';

	/**
	 * @param string[] $orig
	 * @param string[]|false $closing Should either be identical to $orig, or not given
	 */
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

/** @deprecated class alias since 1.41 */
class_alias( DiffOpCopy::class, 'DiffOpCopy' );
