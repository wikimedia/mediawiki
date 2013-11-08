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

	/**
	 * @var string
	 */
	public $secureName;

	/**
	 * @var string
	 */
	public $link;

	/**
	 * @var string
	 */
	public $curlink;

	/**
	 * @var string
	 */
	public $difflink;

	/**
	 * @var string
	 */
	public $lastlink;

	/**
	 * @var string
	 */
	public $userlink;

	/**
	 * @var string
	 */
	public $usertalklink;

	/**
	 * @var string
	 */
	public $versionlink;

	/**
	 * @var	string
	 */
	public $timestamp;

	/**
	 * @var boolean
	 */
	public $watched;

	/**
	 * @param $rc RecentChange
	 * @return RCCacheEntry
	 */
	static function newFromParent( $rc ) {
		$cacheEntry = new RCCacheEntry;
		$cacheEntry->mAttribs = $rc->mAttribs;
		$cacheEntry->mExtra = $rc->mExtra;

		return $cacheEntry;
	}

	/**
	 * @param string $secureName
	 */
	public function setSecureName( $secureName ) {
		$this->secureName = $secureName;
	}

	/**
	 * @param string $link
	 */
	public function setLink( $link ) {
		$this->link = $link;
	}

	/**
	 * @param string $curlink
	 */
	public function setCurLink( $curlink ) {
		$this->curlink = $curlink;
	}

	/**
	 * @param string $difflink
	 */
	public function setDiffLink( $difflink ) {
		$this->difflink = $difflink;
	}

	/**
	 * @param string $lastlink
	 */
	public function setLastLink( $lastlink ) {
		$this->lastlink = $lastlink;
	}

	/**
	 * @param string $userlink
	 */
	public function setUserLink( $userlink ) {
		$this->userlink = $userlink;
	}

	/**
	 * @param string $usertalklink
	 */
	public function setUserTalkLink( $usertalklink ) {
		$this->usertalklink = $usertalklink;
	}

	/**
	 * @param string $versionlink
	 */
	public function setVersionLink( $versionlink ) {
		$this->versionlink = $versionlink;
	}

	/**
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @param boolean $watched
	 */
	public function setWatched( $watched ) {
		$this->watched = $watched;
	}

}
