<?php

/**
 * @license GPL-2.0-or-later
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
