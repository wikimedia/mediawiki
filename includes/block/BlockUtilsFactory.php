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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\WikiMap\WikiMap;

/**
 * @since 1.42
 */
class BlockUtilsFactory {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockUtils::CONSTRUCTOR_OPTIONS;

	/** @var ServiceOptions */
	private $options;

	/** @var ActorStoreFactory */
	private $actorStoreFactory;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var BlockUtils[] */
	private $storeCache;

	/**
	 * @param ServiceOptions $options
	 * @param ActorStoreFactory $actorStoreFactory
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ServiceOptions $options,
		ActorStoreFactory $actorStoreFactory,
		UserNameUtils $userNameUtils
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * @param string|false $wikiId
	 * @return BlockUtils
	 */
	public function getBlockUtils( $wikiId = Block::LOCAL ): BlockUtils {
		if ( is_string( $wikiId ) && WikiMap::getCurrentWikiId() === $wikiId ) {
			$wikiId = Block::LOCAL;
		}

		$storeCacheKey = $wikiId === Block::LOCAL ? 'LOCAL' : 'crosswikistore-' . $wikiId;
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new BlockUtils(
				$this->options,
				$this->actorStoreFactory->getUserIdentityLookup( $wikiId ),
				$this->userNameUtils,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
