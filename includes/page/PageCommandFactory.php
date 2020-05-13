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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionStore;
use MergeHistory;
use MovePage;
use NamespaceInfo;
use RepoGroup;
use Title;
use WatchedItemStoreInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Common factory to construct page handling classes.
 *
 * @since 1.35
 */
class PageCommandFactory implements MergeHistoryFactory, MovePageFactory {
	/** @var ServiceOptions */
	private $options;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var PermissionManager */
	private $permManager;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var RevisionStore */
	private $revisionStore;

	public const CONSTRUCTOR_OPTIONS = [
		'CategoryCollation'
	];

	public function __construct(
		ServiceOptions $options,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $nsInfo,
		WatchedItemStoreInterface $watchedItemStore,
		PermissionManager $permManager,
		RepoGroup $repoGroup,
		IContentHandlerFactory $contentHandlerFactory,
		RevisionStore $revisionStore
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->options = $options;
		$this->loadBalancer = $loadBalancer;
		$this->nsInfo = $nsInfo;
		$this->watchedItemStore = $watchedItemStore;
		$this->permManager = $permManager;
		$this->repoGroup = $repoGroup;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->revisionStore = $revisionStore;
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
			$this->permManager,
			$this->contentHandlerFactory,
			$this->revisionStore,
			$this->watchedItemStore
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
			$this->nsInfo,
			$this->watchedItemStore,
			$this->permManager,
			$this->repoGroup,
			$this->contentHandlerFactory,
			$this->revisionStore
		);
	}
}
