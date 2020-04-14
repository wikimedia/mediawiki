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

use Wikimedia\Assert\Assert;

/**
 * HTMLFileCache purge update for a set of titles
 *
 * @ingroup Cache
 * @since 1.35
 */
class HtmlFileCacheUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var string[] List of page prefixed DB keys */
	private $prefixedDbKeys = [];

	/**
	 * @param string[] $prefixedDbKeys List of page prefixed DB keys
	 */
	public function __construct( array $prefixedDbKeys ) {
		$this->prefixedDbKeys = $prefixedDbKeys;
	}

	public function merge( MergeableUpdate $update ) {
		/** @var self $update */
		Assert::parameterType( __CLASS__, $update, '$update' );
		'@phan-var self $update';

		$this->prefixedDbKeys = array_merge( $this->prefixedDbKeys, $update->prefixedDbKeys );
	}

	/**
	 * @param Traversable|Title[] $titles Array or iterator of Title instances
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
		foreach ( array_unique( $this->prefixedDbKeys ) as $prefixedDbKey ) {
			HTMLFileCache::clearFileCache( $prefixedDbKey );
		}
	}
}
