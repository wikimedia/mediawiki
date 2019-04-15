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
