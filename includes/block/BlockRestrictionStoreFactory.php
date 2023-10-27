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

use MediaWiki\DAO\WikiAwareEntity;
use Wikimedia\Rdbms\LBFactory;

/**
 * @author Zabe
 *
 * @since 1.38
 */
class BlockRestrictionStoreFactory {
	/** @var LBFactory */
	private $loadBalancerFactory;

	/** @var BlockRestrictionStore[] */
	private $storeCache = [];

	/** @var int */
	private $blockTargetMigrationStage;

	/**
	 * @param LBFactory $loadBalancerFactory
	 * @param int $blockTargetMigrationStage
	 */
	public function __construct(
		LBFactory $loadBalancerFactory,
		$blockTargetMigrationStage
	) {
		$this->loadBalancerFactory = $loadBalancerFactory;
		$this->blockTargetMigrationStage = $blockTargetMigrationStage;
	}

	/**
	 * @param string|false $wikiId
	 * @return BlockRestrictionStore
	 */
	public function getBlockRestrictionStore( $wikiId = WikiAwareEntity::LOCAL ): BlockRestrictionStore {
		if ( is_string( $wikiId ) && $this->loadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = WikiAwareEntity::LOCAL;
		}

		$storeCacheKey = $wikiId === WikiAwareEntity::LOCAL ? 'LOCAL' : 'crosswikistore-' . $wikiId;
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new BlockRestrictionStore(
				$this->loadBalancerFactory,
				$this->blockTargetMigrationStage,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
