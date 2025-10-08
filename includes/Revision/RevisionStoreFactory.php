<?php

/**
 * @license GPL-2.0-or-later
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship (that file was removed entirely in 1.37,
 * but its history can still be found in prior versions of MediaWiki).
 *
 * @file
 */

namespace MediaWiki\Revision;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ILBFactory;

/**
 * Factory service for RevisionStore instances. This allows RevisionStores to be created for
 * cross-wiki access.
 *
 * @warning Beware compatibility issues with schema migration in the context of cross-wiki access!
 * This class assumes that all wikis are at compatible migration stages for all relevant schemas.
 * Relevant schemas are: revision storage (MCR), the revision comment table, and the actor table.
 * Migration stages are compatible as long as a) there are no wikis in the cluster that only write
 * the old schema or b) there are no wikis that read only the new schema.
 *
 * @since 1.32
 */
class RevisionStoreFactory {

	private BlobStoreFactory $blobStoreFactory;
	private ILBFactory $dbLoadBalancerFactory;
	private WANObjectCache $cache;
	private BagOStuff $localCache;
	private LoggerInterface $logger;
	private CommentStore $commentStore;
	private ActorStoreFactory $actorStoreFactory;
	private NameTableStoreFactory $nameTables;
	private SlotRoleRegistry $slotRoleRegistry;
	private IContentHandlerFactory $contentHandlerFactory;
	private PageStoreFactory $pageStoreFactory;
	private TitleFactory $titleFactory;
	private HookContainer $hookContainer;
	private RecentChangeLookup $recentChangeLookup;

	public function __construct(
		ILBFactory $dbLoadBalancerFactory,
		BlobStoreFactory $blobStoreFactory,
		NameTableStoreFactory $nameTables,
		SlotRoleRegistry $slotRoleRegistry,
		WANObjectCache $cache,
		BagOStuff $localCache,
		CommentStore $commentStore,
		ActorStoreFactory $actorStoreFactory,
		LoggerInterface $logger,
		IContentHandlerFactory $contentHandlerFactory,
		PageStoreFactory $pageStoreFactory,
		TitleFactory $titleFactory,
		HookContainer $hookContainer,
		RecentChangeLookup $recentChangeLookup
	) {
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->blobStoreFactory = $blobStoreFactory;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->nameTables = $nameTables;
		$this->cache = $cache;
		$this->localCache = $localCache;
		$this->commentStore = $commentStore;
		$this->actorStoreFactory = $actorStoreFactory;
		$this->logger = $logger;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->pageStoreFactory = $pageStoreFactory;
		$this->titleFactory = $titleFactory;
		$this->hookContainer = $hookContainer;
		$this->recentChangeLookup = $recentChangeLookup;
	}

	/**
	 * @since 1.32
	 *
	 * @param false|string $dbDomain DB domain of the relevant wiki or false for the current one
	 *
	 * @return RevisionStore for the given wikiId with all necessary services
	 */
	public function getRevisionStore( $dbDomain = false ): RevisionStore {
		return $this->getStore(
			$dbDomain,
			$this->actorStoreFactory->getActorStore( $dbDomain )
		);
	}

	/**
	 * @since 1.42
	 *
	 * @param false|string $dbDomain DB domain of the relevant wiki or false for the current one
	 *
	 * @return RevisionStore for the given wikiId with all necessary services
	 */
	public function getRevisionStoreForImport( $dbDomain = false ): RevisionStore {
		return $this->getStore(
			$dbDomain,
			$this->actorStoreFactory->getActorStoreForImport( $dbDomain )
		);
	}

	/**
	 * @since 1.43
	 *
	 * @param false|string $dbDomain DB domain of the relevant wiki or false for the current one
	 *
	 * @return RevisionStore for the given wikiId with all necessary services
	 */
	public function getRevisionStoreForUndelete( $dbDomain = false ): RevisionStore {
		return $this->getStore(
			$dbDomain,
			$this->actorStoreFactory->getActorStoreForUndelete( $dbDomain )
		);
	}

	/**
	 * @param false|string $dbDomain
	 * @param ActorStore $actorStore
	 *
	 * @return RevisionStore
	 */
	private function getStore( $dbDomain, ActorStore $actorStore ) {
		Assert::parameterType( [ 'string', 'false' ], $dbDomain, '$dbDomain' );
		if (
			// FIXME: We can't normalize the domain in tests, as RevisionStoreDbTest relies on this behaviour to test
			// cross-wikiness, in absence of a better way (T261848).
			!defined( 'MW_PHPUNIT_TEST' ) &&
			is_string( $dbDomain ) && $this->dbLoadBalancerFactory->getLocalDomainID() === $dbDomain
		) {
			$dbDomain = WikiAwareEntity::LOCAL;
		}

		$store = new RevisionStore(
			$this->dbLoadBalancerFactory->getMainLB( $dbDomain ),
			$this->blobStoreFactory->newSqlBlobStore( $dbDomain ),
			$this->cache, // Pass cache local to wiki; Leave cache sharing to RevisionStore.
			$this->localCache,
			$this->commentStore,
			$this->nameTables->getContentModels( $dbDomain ),
			$this->nameTables->getSlotRoles( $dbDomain ),
			$this->slotRoleRegistry,
			$actorStore,
			$this->contentHandlerFactory,
			$this->pageStoreFactory->getPageStore( $dbDomain ),
			$this->titleFactory,
			$this->hookContainer,
			$this->recentChangeLookup,
			$dbDomain
		);

		$store->setLogger( $this->logger );

		return $store;
	}
}
