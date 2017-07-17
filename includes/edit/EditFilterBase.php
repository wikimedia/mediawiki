<?php
/**
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
 */

namespace MediaWiki\Edit;

use StatusValue;

abstract class EditFilterBase {

	const PRIORITY_FAST = 100;
	const PRIORITY_MID = 50;
	const PRIORITY_SLOW = 10;

	/**
	 * Numerical priority. Higher means to run earlier.
	 *
	 * @var int
	 */
	protected $priority = self::PRIORITY_MID;

	/**
	 * @param EditRequest $editRequest
	 * @return StatusValue
	 */
	abstract public function filter( EditRequest $editRequest );

}
