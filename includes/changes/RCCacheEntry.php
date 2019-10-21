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

class RCCacheEntry extends RecentChange {
	/** @var string|null */
	public $curlink;
	/** @var string|null */
	public $difflink;
	/** @var string|null */
	public $lastlink;
	/** @var string|null */
	public $link;
	/** @var string|null */
	public $timestamp;
	/** @var bool|null */
	public $unpatrolled;
	/** @var string|null */
	public $userlink;
	/** @var string|null */
	public $usertalklink;
	/** @var bool|null */
	public $watched;

	/**
	 * @param RecentChange $rc
	 * @return RCCacheEntry
	 */
	static function newFromParent( $rc ) {
		$rc2 = new RCCacheEntry;
		$rc2->mAttribs = $rc->mAttribs;
		$rc2->mExtra = $rc->mExtra;

		return $rc2;
	}
}
