<?php
/**
 * Message cache purging and in-place update handler for specific message page changes
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

use Wikimedia\Assert\Assert;

/**
 * Message cache purging and in-place update handler for specific message page changes
 *
 * @ingroup Cache
 * @since 1.32
 */
class MessageCacheUpdate implements DeferrableUpdate, MergeableUpdate {
	/** @var array[] Map of (language code => list of (DB key, DB key without code)) */
	private $replacements = [];

	/**
	 * @param string $code Language code
	 * @param string $title Message cache key with initial uppercase letter
	 * @param string $msg Message cache key with initial uppercase letter and without the code
	 */
	public function __construct( $code, $title, $msg ) {
		$this->replacements[$code][] = [ $title, $msg ];
	}

	public function merge( MergeableUpdate $update ) {
		/** @var MessageCacheUpdate $update */
		Assert::parameterType( __CLASS__, $update, '$update' );

		foreach ( $update->replacements as $code => $messages ) {
			$this->replacements[$code] = array_merge( $this->replacements[$code] ?? [], $messages );
		}
	}

	public function doUpdate() {
		$messageCache = MessageCache::singleton();
		foreach ( $this->replacements as $code => $replacements ) {
			$messageCache->refreshAndReplaceInternal( $code, $replacements );
		}
	}
}
