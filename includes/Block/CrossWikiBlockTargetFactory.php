<?php

namespace MediaWiki\Block;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\WikiMap\WikiMap;

/**
 * Factory for BlockTargetFactory objects. This is needed for cross-wiki block
 * operations, since BlockTargetFactory needs a wiki ID passed to its constructor.
 *
 * @since 1.44
 */
class CrossWikiBlockTargetFactory {
	private ActorStoreFactory $actorStoreFactory;
	private UserNameUtils $userNameUtils;
	private ServiceOptions $options;

	/** @var BlockTargetFactory[] */
	private array $storeCache = [];

	/**
	 * @internal Only for use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = BlockTargetFactory::CONSTRUCTOR_OPTIONS;

	public function __construct(
		ServiceOptions $options,
		ActorStoreFactory $actorStoreFactory,
		UserNameUtils $userNameUtils
	) {
		$this->options = $options;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->userNameUtils = $userNameUtils;
	}

	public function getFactory( string|false $wikiId = WikiAwareEntity::LOCAL ): BlockTargetFactory {
		if ( is_string( $wikiId ) && WikiMap::getCurrentWikiId() === $wikiId ) {
			$wikiId = WikiAwareEntity::LOCAL;
		}

		$storeCacheKey = $wikiId === WikiAwareEntity::LOCAL ? 'LOCAL' : 'crosswikistore-' . $wikiId;
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new BlockTargetFactory(
				$this->options,
				$this->actorStoreFactory->getUserIdentityLookup( $wikiId ),
				$this->userNameUtils,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
