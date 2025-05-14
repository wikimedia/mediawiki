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
 * @author DannyS712
 */

namespace MediaWiki\Page;

use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Collation\CollationFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\ContentModelChange;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\ActorMigration;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Psr\Log\LoggerInterface;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Implementation of various page action services.
 *
 * @internal
 */
class PageCommandFactory implements
	ContentModelChangeFactory,
	DeletePageFactory,
	MergeHistoryFactory,
	MovePageFactory,
	RollbackPageFactory,
	UndeletePageFactory
{

	private Config $config;
	private LBFactory $lbFactory;
	private NamespaceInfo $namespaceInfo;
	private WatchedItemStoreInterface $watchedItemStore;
	private RepoGroup $repoGroup;
	private ReadOnlyMode $readOnlyMode;
	private IContentHandlerFactory $contentHandlerFactory;
	private RevisionStoreFactory $revisionStoreFactory;
	private SpamChecker $spamChecker;
	private TitleFormatter $titleFormatter;
	private HookContainer $hookContainer;
	private DomainEventDispatcher $eventDispatcher;
	private WikiPageFactory $wikiPageFactory;
	private UserFactory $userFactory;
	private ActorMigration $actorMigration;
	private ActorNormalization $actorNormalization;
	private TitleFactory $titleFactory;
	private UserEditTracker $userEditTracker;
	private CollationFactory $collationFactory;
	private JobQueueGroup $jobQueueGroup;
	private CommentStore $commentStore;
	private BagOStuff $mainStash;
	private string $localWikiID;
	private string $webRequestID;
	private BacklinkCacheFactory $backlinkCacheFactory;
	private LoggerInterface $undeletePageLogger;
	private PageUpdaterFactory $pageUpdaterFactory;
	private ITextFormatter $contLangMsgTextFormatter;
	private ArchivedRevisionLookup $archivedRevisionLookup;
	private RestrictionStore $restrictionStore;
	private LinkTargetLookup $linkTargetLookup;
	private RedirectStore $redirectStore;
	private LogFormatterFactory $logFormatterFactory;

	public function __construct(
		Config $config,
		LBFactory $lbFactory,
		NamespaceInfo $namespaceInfo,
		WatchedItemStoreInterface $watchedItemStore,
		RepoGroup $repoGroup,
		ReadOnlyMode $readOnlyMode,
		IContentHandlerFactory $contentHandlerFactory,
		RevisionStoreFactory $revisionStoreFactory,
		SpamChecker $spamChecker,
		TitleFormatter $titleFormatter,
		HookContainer $hookContainer,
		DomainEventDispatcher $eventDispatcher,
		WikiPageFactory $wikiPageFactory,
		UserFactory $userFactory,
		ActorMigration $actorMigration,
		ActorNormalization $actorNormalization,
		TitleFactory $titleFactory,
		UserEditTracker $userEditTracker,
		CollationFactory $collationFactory,
		JobQueueGroup $jobQueueGroup,
		CommentStore $commentStore,
		BagOStuff $mainStash,
		string $localWikiID,
		string $webRequestID,
		BacklinkCacheFactory $backlinkCacheFactory,
		LoggerInterface $undeletePageLogger,
		PageUpdaterFactory $pageUpdaterFactory,
		ITextFormatter $contLangMsgTextFormatter,
		ArchivedRevisionLookup $archivedRevisionLookup,
		RestrictionStore $restrictionStore,
		LinkTargetLookup $linkTargetLookup,
		RedirectStore $redirectStore,
		LogFormatterFactory $logFormatterFactory
	) {
		$this->config = $config;
		$this->lbFactory = $lbFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->watchedItemStore = $watchedItemStore;
		$this->repoGroup = $repoGroup;
		$this->readOnlyMode = $readOnlyMode;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->revisionStoreFactory = $revisionStoreFactory;
		$this->spamChecker = $spamChecker;
		$this->titleFormatter = $titleFormatter;
		$this->hookContainer = $hookContainer;
		$this->eventDispatcher = $eventDispatcher;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->userFactory = $userFactory;
		$this->actorMigration = $actorMigration;
		$this->actorNormalization = $actorNormalization;
		$this->titleFactory = $titleFactory;
		$this->userEditTracker = $userEditTracker;
		$this->collationFactory = $collationFactory;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->commentStore = $commentStore;
		$this->mainStash = $mainStash;
		$this->localWikiID = $localWikiID;
		$this->webRequestID = $webRequestID;
		$this->backlinkCacheFactory = $backlinkCacheFactory;
		$this->undeletePageLogger = $undeletePageLogger;
		$this->pageUpdaterFactory = $pageUpdaterFactory;
		$this->contLangMsgTextFormatter = $contLangMsgTextFormatter;
		$this->archivedRevisionLookup = $archivedRevisionLookup;
		$this->restrictionStore = $restrictionStore;
		$this->linkTargetLookup = $linkTargetLookup;
		$this->redirectStore = $redirectStore;
		$this->logFormatterFactory = $logFormatterFactory;
	}

	/**
	 * @param Authority $performer
	 * @param PageIdentity $page
	 * @param string $newContentModel
	 * @return ContentModelChange
	 */
	public function newContentModelChange(
		Authority $performer,
		PageIdentity $page,
		string $newContentModel
	): ContentModelChange {
		return new ContentModelChange(
			$this->contentHandlerFactory,
			$this->hookContainer,
			$this->revisionStoreFactory->getRevisionStore(),
			$this->userFactory,
			$this->wikiPageFactory,
			$this->logFormatterFactory,
			$performer,
			$page,
			$newContentModel
		);
	}

	/**
	 * @inheritDoc
	 */
	public function newDeletePage( ProperPageIdentity $page, Authority $deleter ): DeletePage {
		return new DeletePage(
			$this->hookContainer,
			$this->eventDispatcher,
			$this->revisionStoreFactory->getRevisionStore(),
			$this->lbFactory,
			$this->jobQueueGroup,
			$this->commentStore,
			new ServiceOptions( DeletePage::CONSTRUCTOR_OPTIONS, $this->config ),
			$this->mainStash,
			$this->localWikiID,
			$this->webRequestID,
			$this->wikiPageFactory,
			$this->userFactory,
			$this->backlinkCacheFactory,
			$this->namespaceInfo,
			$this->contLangMsgTextFormatter,
			$this->redirectStore,
			$page,
			$deleter
		);
	}

	/**
	 * @param PageIdentity $source
	 * @param PageIdentity $destination
	 * @param string|null $timestamp
	 * @return MergeHistory
	 */
	public function newMergeHistory(
		PageIdentity $source,
		PageIdentity $destination,
		?string $timestamp = null
	): MergeHistory {
		return new MergeHistory(
			$source,
			$destination,
			$timestamp,
			$this->lbFactory,
			$this->contentHandlerFactory,
			$this->watchedItemStore,
			$this->spamChecker,
			$this->hookContainer,
			$this->pageUpdaterFactory,
			$this->titleFormatter,
			$this->titleFactory,
			$this
		);
	}

	/**
	 * @param Title $from
	 * @param Title $to
	 * @return MovePage
	 */
	public function newMovePage( Title $from, Title $to ): MovePage {
		return new MovePage(
			$from,
			$to,
			new ServiceOptions( MovePage::CONSTRUCTOR_OPTIONS, $this->config ),
			$this->lbFactory,
			$this->namespaceInfo,
			$this->watchedItemStore,
			$this->repoGroup,
			$this->contentHandlerFactory,
			$this->revisionStoreFactory->getRevisionStore(),
			$this->spamChecker,
			$this->hookContainer,
			$this->eventDispatcher,
			$this->wikiPageFactory,
			$this->userFactory,
			$this->userEditTracker,
			$this,
			$this->collationFactory,
			$this->pageUpdaterFactory,
			$this->restrictionStore,
			$this,
			$this->logFormatterFactory
		);
	}

	/**
	 * Create a new command instance for page rollback.
	 *
	 * @param PageIdentity $page
	 * @param Authority $performer
	 * @param UserIdentity $byUser
	 * @return RollbackPage
	 */
	public function newRollbackPage(
		PageIdentity $page,
		Authority $performer,
		UserIdentity $byUser
	): RollbackPage {
		return new RollbackPage(
			new ServiceOptions( RollbackPage::CONSTRUCTOR_OPTIONS, $this->config ),
			$this->lbFactory,
			$this->userFactory,
			$this->readOnlyMode,
			$this->revisionStoreFactory->getRevisionStore(),
			$this->titleFormatter,
			$this->hookContainer,
			$this->wikiPageFactory,
			$this->actorMigration,
			$this->actorNormalization,
			$page,
			$performer,
			$byUser
		);
	}

	/**
	 * @inheritDoc
	 */
	public function newUndeletePage( ProperPageIdentity $page, Authority $authority ): UndeletePage {
		return new UndeletePage(
			$this->hookContainer,
			$this->jobQueueGroup,
			$this->lbFactory,
			$this->readOnlyMode,
			$this->repoGroup,
			$this->undeletePageLogger,
			$this->revisionStoreFactory->getRevisionStoreForUndelete(),
			$this->wikiPageFactory,
			$this->pageUpdaterFactory,
			$this->contentHandlerFactory,
			$this->archivedRevisionLookup,
			$this->namespaceInfo,
			$this->contLangMsgTextFormatter,
			$page,
			$authority
		);
	}
}
