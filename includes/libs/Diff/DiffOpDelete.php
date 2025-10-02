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
 * deleted from the first string array.
 *
 * @ingroup DifferenceEngine
 */
class DiffOpDelete extends DiffOp {
	/** @inheritDoc */
	public $type = 'delete';

	/**
	 * @param string[] $lines
	 */
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

/** @deprecated class alias since 1.41 */
class_alias( DiffOpDelete::class, 'DiffOpDelete' );
