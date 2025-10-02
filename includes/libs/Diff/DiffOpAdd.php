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
 * added from the first string array.
 *
 * @ingroup DifferenceEngine
 */
class DiffOpAdd extends DiffOp {
	/** @inheritDoc */
	public $type = 'add';

	/**
	 * @param string[] $lines
	 */
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

/** @deprecated class alias since 1.41 */
class_alias( DiffOpAdd::class, 'DiffOpAdd' );
