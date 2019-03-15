<?php
/**
 * HTMLFileCache cache purging
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
 */

use MediaWiki\MediaWikiServices;

/**
 * Handles purging the appropriate HTMLFileCache files given a list of titles
 * @ingroup Cache
 */
class HtmlFileCacheUpdate implements DeferrableUpdate {
	/** @var string[] Collection of prefixed DB keys for the pages to purge */
	private $prefixedDbKeys = [];

	/**
	 * @param string[] $prefixedDbKeys
	 */
	public function __construct( array $prefixedDbKeys ) {
		$this->prefixedDbKeys = $prefixedDbKeys;
	}

	/**
	 * Create an update object from an array of Title objects, or a TitleArray object
	 *
	 * @param Traversable|Title[] $titles
	 * @return HtmlFileCacheUpdate
	 */
	public static function newFromTitles( $titles ) {
		$prefixedDbKeys = [];
		foreach ( $titles as $title ) {
			$prefixedDbKeys[] = $title->getPrefixedDBkey();
		}

		return new self( $prefixedDbKeys );
	}

	public function doUpdate() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		if ( $config->get( 'UseFileCache' ) ) {
			HTMLFileCache::purge( $this->prefixedDbKeys );
		}
	}
}
