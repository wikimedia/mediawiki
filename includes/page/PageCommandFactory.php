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

use ActorMigration;
use Config;
use ContentModelChange;
use MediaWiki\Collation\CollationFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MergeHistory;
use MovePage;
use NamespaceInfo;
use ReadOnlyMode;
use RepoGroup;
use Title;
use TitleFactory;
use TitleFormatter;
use WatchedItemStoreInterface;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * Common factory to construct page handling classes.
 *
 * @since 1.35
 */
class PageCommandFactory implements
	ContentModelChangeFactory,
	MergeHistoryFactory,
	MovePageFactory,
	RollbackPageFactory
{

	/** @var Config */
	private $config;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var SpamChecker */
	private $spamChecker;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var HookContainer */
	private $hookContainer;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var UserFactory */
	private $userFactory;

	/** @var ActorMigration */
	private $actorMigration;

	/** @var ActorNormalization */
	private $actorNormalization;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var UserEditTracker */
	private $userEditTracker;

	/** @var CollationFactory */
	private $collationFactory;

	public function __construct(
		Config $config,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $namespaceInfo,
		WatchedItemStoreInterface $watchedItemStore,
		RepoGroup $repoGroup,
		ReadOnlyMode $readOnlyMode,
		IContentHandlerFactory $contentHandlerFactory,
		RevisionStore $revisionStore,
		SpamChecker $spamChecker,
		TitleFormatter $titleFormatter,
		HookContainer $hookContainer,
		WikiPageFactory $wikiPageFactory,
		UserFactory $userFactory,
		ActorMigration $actorMigration,
		ActorNormalization $actorNormalization,
		TitleFactory $titleFactory,
		UserEditTracker $userEditTracker,
		CollationFactory $collationFactory
	) {
		$this->config = $config;
		$this->loadBalancer = $loadBalancer;
		$this->namespaceInfo = $namespaceInfo;
		$this->watchedItemStore = $watchedItemStore;
		$this->repoGroup = $repoGroup;
		$this->readOnlyMode = $readOnlyMode;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->revisionStore = $revisionStore;
		$this->spamChecker = $spamChecker;
		$this->titleFormatter = $titleFormatter;
		$this->hookContainer = $hookContainer;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->userFactory = $userFactory;
		$this->actorMigration = $actorMigration;
		$this->actorNormalization = $actorNormalization;
		$this->titleFactory = $titleFactory;
		$this->userEditTracker = $userEditTracker;
		$this->collationFactory = $collationFactory;
	}

	/**
	 * @param Authority $performer
	 * @param WikiPage $wikipage
	 * @param string $newContentModel
	 * @return ContentModelChange
	 */
	public function newContentModelChange(
		Authority $performer,
		WikiPage $wikipage,
		string $newContentModel
	): ContentModelChange {
		return new ContentModelChange(
			$this->contentHandlerFactory,
			$this->hookContainer,
			$this->revisionStore,
			$this->userFactory,
			$performer,
			$wikipage,
			$newContentModel
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
		string $timestamp = null
	): MergeHistory {
		return new MergeHistory(
			$source,
			$destination,
			$timestamp,
			$this->loadBalancer,
			$this->contentHandlerFactory,
			$this->revisionStore,
			$this->watchedItemStore,
			$this->spamChecker,
			$this->hookContainer,
			$this->wikiPageFactory,
			$this->titleFormatter,
			$this->titleFactory
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
			$this->loadBalancer,
			$this->namespaceInfo,
			$this->watchedItemStore,
			$this->repoGroup,
			$this->contentHandlerFactory,
			$this->revisionStore,
			$this->spamChecker,
			$this->hookContainer,
			$this->wikiPageFactory,
			$this->userFactory,
			$this->userEditTracker,
			$this,
			$this->collationFactory
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
			$this->loadBalancer,
			$this->userFactory,
			$this->readOnlyMode,
			$this->revisionStore,
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
}
