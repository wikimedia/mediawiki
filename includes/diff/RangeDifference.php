<?php
/**
 * New version of the difference engine
 *
 * Copyright © 2008 Guy Van den Broeck <guy@guyvdb.eu>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup DifferenceEngine
 */

/**
 * Alternative representation of a set of changes, by the index
 * ranges that are changed.
 *
 * @ingroup DifferenceEngine
 */
class RangeDifference {

	/** @var int */
	public $leftstart;

	/** @var int */
	public $leftend;

	/** @var int */
	public $leftlength;

	/** @var int */
	public $rightstart;

	/** @var int */
	public $rightend;

	/** @var int */
	public $rightlength;

	function __construct( $leftstart, $leftend, $rightstart, $rightend ) {
		$this->leftstart = $leftstart;
		$this->leftend = $leftend;
		$this->leftlength = $leftend - $leftstart;
		$this->rightstart = $rightstart;
		$this->rightend = $rightend;
		$this->rightlength = $rightend - $rightstart;
	}

}
