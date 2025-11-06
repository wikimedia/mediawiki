<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\DAO\WikiAwareEntity;
use Wikimedia\Rdbms\LBFactory;

/**
 * @author Zabe
 *
 * @since 1.38
 */
class BlockRestrictionStoreFactory {
	private LBFactory $loadBalancerFactory;

	/** @var BlockRestrictionStore[] */
	private array $storeCache = [];

	public function __construct( LBFactory $loadBalancerFactory ) {
		$this->loadBalancerFactory = $loadBalancerFactory;
	}

	public function getBlockRestrictionStore( string|false $wikiId = WikiAwareEntity::LOCAL ): BlockRestrictionStore {
		if ( is_string( $wikiId ) && $this->loadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = WikiAwareEntity::LOCAL;
		}

		$storeCacheKey = $wikiId === WikiAwareEntity::LOCAL ? 'LOCAL' : 'crosswikistore-' . $wikiId;
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new BlockRestrictionStore(
				$this->loadBalancerFactory,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
