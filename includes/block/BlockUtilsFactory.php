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

namespace MediaWiki\Block;

use MediaWiki\WikiMap\WikiMap;

/**
 * @deprecated since 1.44 use CrossWikiBlockTargetFactory
 * @since 1.42
 */
class BlockUtilsFactory {
	private CrossWikiBlockTargetFactory $crossWikiBlockTargetFactory;

	/** @var BlockUtils[] */
	private array $storeCache = [];

	public function __construct(
		CrossWikiBlockTargetFactory $crossWikiBlockTargetFactory
	) {
		$this->crossWikiBlockTargetFactory = $crossWikiBlockTargetFactory;
	}

	public function getBlockUtils( string|false $wikiId = Block::LOCAL ): BlockUtils {
		if ( is_string( $wikiId ) && WikiMap::getCurrentWikiId() === $wikiId ) {
			$wikiId = Block::LOCAL;
		}

		$storeCacheKey = $wikiId === Block::LOCAL ? 'LOCAL' : 'crosswikistore-' . $wikiId;
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new BlockUtils(
				$this->crossWikiBlockTargetFactory->getFactory( $wikiId )
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
