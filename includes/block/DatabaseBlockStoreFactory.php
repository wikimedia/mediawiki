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

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @author Zabe
 *
 * @since 1.40
 */
class DatabaseBlockStoreFactory {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = DatabaseBlockStore::CONSTRUCTOR_OPTIONS;

	/** @var ServiceOptions */
	private $options;

	/** @var LoggerInterface */
	private $logger;

	/** @var ActorStoreFactory */
	private $actorStoreFactory;

	/** @var BlockRestrictionStoreFactory */
	private $blockRestrictionStoreFactory;

	/** @var CommentStore */
	private $commentStore;

	/** @var HookContainer */
	private $hookContainer;

	/** @var LBFactory */
	private $loadBalancerFactory;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var UserFactory */
	private $userFactory;

	/** @var TempUserConfig */
	private $tempUserConfig;

	/** @var BlockUtilsFactory */
	private $blockUtilsFactory;

	/** @var AutoblockExemptionList */
	private $autoblockExemptionList;

	/** @var DatabaseBlockStore[] */
	private $storeCache = [];

	/**
	 * @param ServiceOptions $options
	 * @param LoggerInterface $logger
	 * @param ActorStoreFactory $actorStoreFactory
	 * @param BlockRestrictionStoreFactory $blockRestrictionStoreFactory
	 * @param CommentStore $commentStore
	 * @param HookContainer $hookContainer
	 * @param LBFactory $loadBalancerFactory
	 * @param ReadOnlyMode $readOnlyMode
	 * @param UserFactory $userFactory
	 * @param TempUserConfig $tempUserConfig
	 * @param BlockUtilsFactory $blockUtilsFactory
	 * @param AutoblockExemptionList $autoblockExemptionList
	 */
	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		ActorStoreFactory $actorStoreFactory,
		BlockRestrictionStoreFactory $blockRestrictionStoreFactory,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		LBFactory $loadBalancerFactory,
		ReadOnlyMode $readOnlyMode,
		UserFactory $userFactory,
		TempUserConfig $tempUserConfig,
		BlockUtilsFactory $blockUtilsFactory,
		AutoblockExemptionList $autoblockExemptionList
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->logger = $logger;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->blockRestrictionStoreFactory = $blockRestrictionStoreFactory;
		$this->commentStore = $commentStore;
		$this->hookContainer = $hookContainer;
		$this->loadBalancerFactory = $loadBalancerFactory;
		$this->readOnlyMode = $readOnlyMode;
		$this->userFactory = $userFactory;
		$this->tempUserConfig = $tempUserConfig;
		$this->blockUtilsFactory = $blockUtilsFactory;
		$this->autoblockExemptionList = $autoblockExemptionList;
	}

	/**
	 * @param string|false $wikiId
	 * @return DatabaseBlockStore
	 */
	public function getDatabaseBlockStore( $wikiId = DatabaseBlock::LOCAL ): DatabaseBlockStore {
		if ( is_string( $wikiId ) && $this->loadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = DatabaseBlock::LOCAL;
		}

		$storeCacheKey = $wikiId === DatabaseBlock::LOCAL ? 'LOCAL' : 'crosswikistore-' . $wikiId;
		if ( !isset( $this->storeCache[$storeCacheKey] ) ) {
			$this->storeCache[$storeCacheKey] = new DatabaseBlockStore(
				$this->options,
				$this->logger,
				$this->actorStoreFactory,
				$this->blockRestrictionStoreFactory->getBlockRestrictionStore( $wikiId ),
				$this->commentStore,
				$this->hookContainer,
				$this->loadBalancerFactory,
				$this->readOnlyMode,
				$this->userFactory,
				$this->tempUserConfig,
				$this->blockUtilsFactory->getBlockUtils( $wikiId ),
				$this->autoblockExemptionList,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
