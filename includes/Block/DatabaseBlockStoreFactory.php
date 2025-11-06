<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Session\SessionManagerInterface;
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

	private ServiceOptions $options;
	private LoggerInterface $logger;
	private ActorStoreFactory $actorStoreFactory;
	private BlockRestrictionStoreFactory $blockRestrictionStoreFactory;
	private CommentStore $commentStore;
	private HookContainer $hookContainer;
	private LBFactory $loadBalancerFactory;
	private ReadOnlyMode $readOnlyMode;
	private UserFactory $userFactory;
	private TempUserConfig $tempUserConfig;
	private CrossWikiBlockTargetFactory $crossWikiBlockTargetFactory;
	private AutoblockExemptionList $autoblockExemptionList;
	private SessionManagerInterface $sessionManager;

	/** @var DatabaseBlockStore[] */
	private array $storeCache = [];

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
		CrossWikiBlockTargetFactory $crossWikiBlockTargetFactory,
		AutoblockExemptionList $autoblockExemptionList,
		SessionManagerInterface $sessionManager
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
		$this->crossWikiBlockTargetFactory = $crossWikiBlockTargetFactory;
		$this->autoblockExemptionList = $autoblockExemptionList;
		$this->sessionManager = $sessionManager;
	}

	public function getDatabaseBlockStore( string|false $wikiId = DatabaseBlock::LOCAL ): DatabaseBlockStore {
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
				$this->crossWikiBlockTargetFactory->getFactory( $wikiId ),
				$this->autoblockExemptionList,
				$this->sessionManager,
				$wikiId
			);
		}
		return $this->storeCache[$storeCacheKey];
	}
}
