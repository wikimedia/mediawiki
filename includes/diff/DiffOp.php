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
 * @defgroup DifferenceEngine DifferenceEngine
 */

/**
 * The base class for all other DiffOp classes.
 *
 * The classes that extend DiffOp are: DiffOpCopy, DiffOpDelete, DiffOpAdd and
 * DiffOpChange. FakeDiffOp also extends DiffOp, but it is not located in this file.
 *
 * @internal
 * @ingroup DifferenceEngine
 */
abstract class DiffOp {

	/**
	 * @var string
	 * @private Please use {@see getType}
	 */
	public $type;

	/**
	 * @var string[]|false The left ("old") side of the diff, or false when it's an "add"
	 * @private Please use {@see getOrig}
	 */
	public $orig;

	/**
	 * @var string[]|false The right ("new") side of the diff, or false when it's a "delete"
	 * @private Please use {@see getClosing}
	 */
	public $closing;

	/**
	 * @return string Either "add", "change", "copy", or "delete"
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Returns either all lines on the left ("old") side of the diff, or false when it's an add
	 * operation.
	 *
	 * @return string[]|false
	 */
	public function getOrig() {
		return $this->orig;
	}

	/**
	 * Without a line number this returns either all lines on the right ("new") side of the diff, or
	 * false when it's a delete operation.
	 *
	 * With a line number this returns either the line or null if the line doesn't exist.
	 *
	 * @param int|null $i Line number, or null for all lines in the operation
	 * @return string[]|false|string|null
	 */
	public function getClosing( $i = null ) {
		if ( $i === null ) {
			return $this->closing;
		}
		if ( array_key_exists( $i, $this->closing ) ) {
			return $this->closing[$i];
		}
		return null;
	}

	/**
	 * @return self Inverted operation (a.k.a. revert or undo), e.g. "delete" becomes "add"
	 */
	abstract public function reverse();

	/**
	 * @return int Number of lines on the left ("old") side of the diff, {@see getOrig}
	 */
	public function norig() {
		return $this->orig ? count( $this->orig ) : 0;
	}

	/**
	 * @return int Number of lines on the right ("new") side of the diff, see {@see getClosing}
	 */
	public function nclosing() {
		return $this->closing ? count( $this->closing ) : 0;
	}
}
