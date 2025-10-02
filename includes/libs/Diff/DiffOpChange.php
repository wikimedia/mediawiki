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
 * changed from the first string array (both added and subtracted).
 *
 * @ingroup DifferenceEngine
 */
class DiffOpChange extends DiffOp {
	/** @inheritDoc */
	public $type = 'change';

	/**
	 * @param string[] $orig
	 * @param string[] $closing
	 */
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

/** @deprecated class alias since 1.41 */
class_alias( DiffOpChange::class, 'DiffOpChange' );
