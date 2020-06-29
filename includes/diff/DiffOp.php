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
	 */
	public $type;

	/**
	 * @var string[]|false
	 */
	public $orig;

	/**
	 * @var string[]|false
	 */
	public $closing;

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return string[]
	 */
	public function getOrig() {
		return $this->orig;
	}

	/**
	 * @param int|null $i
	 * @return string[]|string|null
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

	abstract public function reverse();

	/**
	 * @return int
	 */
	public function norig() {
		return $this->orig ? count( $this->orig ) : 0;
	}

	/**
	 * @return int
	 */
	public function nclosing() {
		return $this->closing ? count( $this->closing ) : 0;
	}
}
