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

	/** @var RevisionStore */
	private $revisionStore;

	/** @var RevisionRenderer */
	private $revisionRenderer;

	/** @var SlotRoleRegistry */
	private $slotRoleRegistry;

	/** @var ParserCache */
	private $parserCache;

	/** @var JobQueueGroup */
	private $jobQueueGroup;

	/** @var Language */
	private $contLang;

	/** @var ILBFactory */
	private $loadbalancerFactory;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var DomainEventDispatcher */
	private $eventDispatcher;

	/** @var HookContainer */
	private $hookContainer;

	/** @var EditResultCache */
	private $editResultCache;

	/** @var LoggerInterface */
	private $logger;

	/** @var ServiceOptions */
	private $options;

	/** @var UserGroupManager */
	private $userGroupManager;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var ContentTransformer */
	private $contentTransformer;

	/** @var PageEditStash */
	private $pageEditStash;

	/** @var WANObjectCache */
	private $mainWANObjectCache;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var string[] */
	private $softwareTags;

	private ChangeTagsStore $changeTagsStore;

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
		RevisionStore $revisionStore,
		RevisionRenderer $revisionRenderer,
		SlotRoleRegistry $slotRoleRegistry,
		ParserCache $parserCache,
		JobQueueGroup $jobQueueGroup,
		Language $contLang,
		ILBFactory $loadbalancerFactory,
		IContentHandlerFactory $contentHandlerFactory,
		DomainEventDispatcher $eventDispatcher,
		HookContainer $hookContainer,
		EditResultCache $editResultCache,
		LoggerInterface $logger,
		ServiceOptions $options,
		UserGroupManager $userGroupManager,
		TitleFormatter $titleFormatter,
		ContentTransformer $contentTransformer,
		PageEditStash $pageEditStash,
		WANObjectCache $mainWANObjectCache,
		WikiPageFactory $wikiPageFactory,
		ChangeTagsStore $changeTagsStore,
		array $softwareTags
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->revisionStore = $revisionStore;
		$this->revisionRenderer = $revisionRenderer;
		$this->slotRoleRegistry = $slotRoleRegistry;
		$this->parserCache = $parserCache;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->contLang = $contLang;
		$this->loadbalancerFactory = $loadbalancerFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->eventDispatcher = $eventDispatcher;
		$this->hookContainer = $hookContainer;
		$this->editResultCache = $editResultCache;
		$this->logger = $logger;
		$this->options = $options;
		$this->userGroupManager = $userGroupManager;
		$this->titleFormatter = $titleFormatter;
		$this->contentTransformer = $contentTransformer;
		$this->pageEditStash = $pageEditStash;
		$this->mainWANObjectCache = $mainWANObjectCache;
		$this->softwareTags = $softwareTags;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->changeTagsStore = $changeTagsStore;
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
