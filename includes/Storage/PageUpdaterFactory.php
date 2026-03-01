<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Storage;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ILBFactory;

/**
 * A factory for PageUpdater and DerivedPageDataUpdater instances.
 *
 * @since 1.37
 * @ingroup Page
 */
class PageUpdaterFactory {

	/**
	 * Options that have to be present in the ServiceOptions object passed to the constructor.
	 * @note must include PageUpdater::CONSTRUCTOR_OPTIONS
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ArticleCountMethod,
		MainConfigNames::RCWatchCategoryMembership,
		MainConfigNames::UseAutomaticEditSummaries,
		MainConfigNames::ManualRevertSearchRadius,
		MainConfigNames::UseRCPatrol,
		MainConfigNames::ParsoidCacheConfig,
		MainConfigNames::PageCreationLog,
	];

	/**
	 * @param RevisionStore $revisionStore
	 * @param RevisionRenderer $revisionRenderer
	 * @param SlotRoleRegistry $slotRoleRegistry
	 * @param ParserCache $parserCache
	 * @param JobQueueGroup $jobQueueGroup
	 * @param Language $contLang
	 * @param ILBFactory $loadbalancerFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param DomainEventDispatcher $eventDispatcher
	 * @param HookContainer $hookContainer
	 * @param EditResultCache $editResultCache
	 * @param LoggerInterface $logger
	 * @param ServiceOptions $options
	 * @param UserGroupManager $userGroupManager
	 * @param TitleFormatter $titleFormatter
	 * @param ContentTransformer $contentTransformer
	 * @param PageEditStash $pageEditStash
	 * @param WANObjectCache $mainWANObjectCache
	 * @param WikiPageFactory $wikiPageFactory
	 * @param ChangeTagsStore $changeTagsStore
	 * @param string[] $softwareTags
	 */
	public function __construct(
		private readonly RevisionStore $revisionStore,
		private readonly RevisionRenderer $revisionRenderer,
		private readonly SlotRoleRegistry $slotRoleRegistry,
		private readonly ParserCache $parserCache,
		private readonly JobQueueGroup $jobQueueGroup,
		private readonly Language $contLang,
		private readonly ILBFactory $loadbalancerFactory,
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly DomainEventDispatcher $eventDispatcher,
		private readonly HookContainer $hookContainer,
		private readonly EditResultCache $editResultCache,
		private readonly LoggerInterface $logger,
		private readonly ServiceOptions $options,
		private readonly UserGroupManager $userGroupManager,
		private readonly TitleFormatter $titleFormatter,
		private readonly ContentTransformer $contentTransformer,
		private readonly PageEditStash $pageEditStash,
		private readonly WANObjectCache $mainWANObjectCache,
		private readonly WikiPageFactory $wikiPageFactory,
		private readonly ChangeTagsStore $changeTagsStore,
		private readonly array $softwareTags,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Return a PageUpdater for building an update to a page.
	 *
	 * @internal For now, most code should keep using WikiPage::newPageUpdater() instead.
	 * @note We can only start using this method everywhere when WikiPage::prepareContentForEdit()
	 * and WikiPage::getCurrentUpdate() have been removed. For now, the WikiPage instance is
	 * used to make the state of an ongoing edit available to hook handlers.
	 *
	 * @param PageIdentity $page
	 * @param UserIdentity $user
	 *
	 * @return PageUpdater
	 * @since 1.37
	 */
	public function newPageUpdater(
		PageIdentity $page,
		UserIdentity $user
	): PageUpdater {
		return $this->newPageUpdaterForDerivedPageDataUpdater(
			$page,
			$user,
			$this->newDerivedPageDataUpdater( $page )
		);
	}

	/**
	 * Return a PageUpdater for building an update to a page, reusing the state of
	 * an existing DerivedPageDataUpdater.
	 *
	 * @param PageIdentity $page
	 * @param UserIdentity $user
	 * @param DerivedPageDataUpdater $derivedPageDataUpdater
	 *
	 * @return PageUpdater
	 * @internal needed by WikiPage to back the WikiPage::newPageUpdater method.
	 *
	 * @since 1.37
	 */
	public function newPageUpdaterForDerivedPageDataUpdater(
		PageIdentity $page,
		UserIdentity $user,
		DerivedPageDataUpdater $derivedPageDataUpdater
	): PageUpdater {
		$pageUpdater = new PageUpdater(
			$user,
			$page,
			$derivedPageDataUpdater,
			$this->loadbalancerFactory,
			$this->revisionStore,
			$this->slotRoleRegistry,
			$this->contentHandlerFactory,
			$this->hookContainer,
			$this->userGroupManager,
			$this->titleFormatter,
			new ServiceOptions(
				PageUpdater::CONSTRUCTOR_OPTIONS,
				$this->options
			),
			$this->softwareTags,
			$this->logger,
			$this->wikiPageFactory
		);

		$pageUpdater->setUsePageCreationLog(
			$this->options->get( MainConfigNames::PageCreationLog ) );

		$pageUpdater->setUseAutomaticEditSummaries(
			$this->options->get( MainConfigNames::UseAutomaticEditSummaries )
		);

		return $pageUpdater;
	}

	/**
	 * @param PageIdentity $page
	 *
	 * @return DerivedPageDataUpdater
	 * @internal Needed by WikiPage to back the deprecated prepareContentForEdit() method.
	 * @note Avoid direct usage of DerivedPageDataUpdater.
	 * @see docs/pageupdater.md for more information.
	 */
	public function newDerivedPageDataUpdater( PageIdentity $page ): DerivedPageDataUpdater {
		$derivedDataUpdater = new DerivedPageDataUpdater(
			$this->options,
			$page,
			$this->revisionStore,
			$this->revisionRenderer,
			$this->slotRoleRegistry,
			$this->parserCache,
			$this->jobQueueGroup,
			$this->contLang,
			$this->loadbalancerFactory,
			$this->contentHandlerFactory,
			$this->hookContainer,
			$this->eventDispatcher,
			$this->editResultCache,
			$this->contentTransformer,
			$this->pageEditStash,
			$this->mainWANObjectCache,
			$this->wikiPageFactory,
			$this->changeTagsStore,
		);

		$derivedDataUpdater->setLogger( $this->logger );
		$derivedDataUpdater->setArticleCountMethod(
			$this->options->get( MainConfigNames::ArticleCountMethod ) );

		return $derivedDataUpdater;
	}

}
