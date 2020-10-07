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

use ContentModelChange;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionStore;
use MergeHistory;
use MovePage;
use NamespaceInfo;
use RepoGroup;
use Title;
use User;
use WatchedItemStoreInterface;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * Common factory to construct page handling classes.
 *
 * @since 1.35
 */
class PageCommandFactory implements ContentModelChangeFactory, MergeHistoryFactory, MovePageFactory {
	/** @var ServiceOptions */
	private $options;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var SpamChecker */
	private $spamChecker;

	/** @var HookContainer */
	private $hookContainer;

	public const CONSTRUCTOR_OPTIONS = [
		'CategoryCollation'
	];

	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $namespaceInfo,
		WatchedItemStoreInterface $watchedItemStore,
		PermissionManager $permissionManager,
		RepoGroup $repoGroup,
		IContentHandlerFactory $contentHandlerFactory,
		RevisionStore $revisionStore,
		SpamChecker $spamChecker,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->loadBalancer = $loadBalancer;
		$this->namespaceInfo = $namespaceInfo;
		$this->watchedItemStore = $watchedItemStore;
		$this->permissionManager = $permissionManager;
		$this->repoGroup = $repoGroup;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->revisionStore = $revisionStore;
		$this->spamChecker = $spamChecker;
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @param User $user
	 * @param WikiPage $wikipage
	 * @param string $newContentModel
	 * @return ContentModelChange
	 */
	public function newContentModelChange(
		User $user,
		WikiPage $wikipage,
		string $newContentModel
	) : ContentModelChange {
		return new ContentModelChange(
			$this->contentHandlerFactory,
			$this->hookContainer,
			$this->permissionManager,
			$this->revisionStore,
			$user,
			$wikipage,
			$newContentModel
		);
	}

	/**
	 * @param Title $source
	 * @param Title $destination
	 * @param string|null $timestamp
	 * @return MergeHistory
	 */
	public function newMergeHistory(
		Title $source,
		Title $destination,
		string $timestamp = null
	) : MergeHistory {
		if ( $timestamp === null ) {
			// For compatibility with MergeHistory constructor until it can be changed
			$timestamp = false;
		}
		return new MergeHistory(
			$source,
			$destination,
			$timestamp,
			$this->loadBalancer,
			$this->permissionManager,
			$this->contentHandlerFactory,
			$this->revisionStore,
			$this->watchedItemStore,
			$this->spamChecker
		);
	}

	/**
	 * @param Title $from
	 * @param Title $to
	 * @return MovePage
	 */
	public function newMovePage( Title $from, Title $to ) : MovePage {
		return new MovePage(
			$from,
			$to,
			$this->options,
			$this->loadBalancer,
			$this->namespaceInfo,
			$this->watchedItemStore,
			$this->permissionManager,
			$this->repoGroup,
			$this->contentHandlerFactory,
			$this->revisionStore,
			$this->spamChecker,
			$this->hookContainer
		);
	}
}
