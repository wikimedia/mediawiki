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

namespace MediaWiki\Specials;

use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Pager\DeletedContribsPager;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\ContributionsSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:DeletedContributions to display archived revisions
 *
 * @ingroup SpecialPage
 */
class SpecialDeletedContributions extends ContributionsSpecialPage {
	private ?DeletedContribsPager $pager = null;

	private RevisionStore $revisionStore;
	private CommentFormatter $commentFormatter;
	private LinkBatchFactory $linkBatchFactory;
	private TempUserConfig $tempUserConfig;

	/**
	 * @param PermissionManager $permissionManager
	 * @param IConnectionProvider $dbProvider
	 * @param RevisionStore $revisionStore
	 * @param NamespaceInfo $namespaceInfo
	 * @param UserNameUtils $userNameUtils
	 * @param UserNamePrefixSearch $userNamePrefixSearch
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param CommentFormatter $commentFormatter
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param UserFactory $userFactory
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param DatabaseBlockStore $blockStore
	 * @param TempUserConfig $tempUserConfig
	 */
	public function __construct(
		PermissionManager $permissionManager,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		NamespaceInfo $namespaceInfo,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		UserOptionsLookup $userOptionsLookup,
		CommentFormatter $commentFormatter,
		LinkBatchFactory $linkBatchFactory,
		UserFactory $userFactory,
		UserIdentityLookup $userIdentityLookup,
		DatabaseBlockStore $blockStore,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct(
			$permissionManager,
			$dbProvider,
			$namespaceInfo,
			$userNameUtils,
			$userNamePrefixSearch,
			$userOptionsLookup,
			$userFactory,
			$userIdentityLookup,
			$blockStore,
			'DeletedContributions',
			'deletedhistory'
		);
		$this->revisionStore = $revisionStore;
		$this->commentFormatter = $commentFormatter;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->tempUserConfig = $tempUserConfig;
	}

	/**
	 * @inheritDoc
	 */
	protected function getPager( $target ) {
		if ( $this->pager === null ) {
			$this->pager = new DeletedContribsPager(
				$this->getHookContainer(),
				$this->getLinkRenderer(),
				$this->dbProvider,
				$this->revisionStore,
				$this->namespaceInfo,
				$this->commentFormatter,
				$this->linkBatchFactory,
				$this->userFactory,
				$this->getContext(),
				$this->opts,
				$target
			);
		}

		return $this->pager;
	}

	/** @inheritDoc */
	public function isIncludable() {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	protected function getUserLinks(
		SpecialPage $sp,
		User $target
	) {
		$tools = parent::getUserLinks( $sp, $target );
		$linkRenderer = $sp->getLinkRenderer();

		$contributionsLink = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Contributions', $target->getName() ),
			$this->msg( 'sp-deletedcontributions-contribs' )->text()
		);
		if ( isset( $tools['deletedcontribs'] ) ) {
			// Swap out the deletedcontribs link for our contribs one
			$tools = wfArrayInsertAfter(
				$tools, [ 'contribs' => $contributionsLink ], 'deletedcontribs' );
			unset( $tools['deletedcontribs'] );
		} else {
			$tools['contribs'] = $contributionsLink;
		}

		return $tools;
	}

	/** @inheritDoc */
	protected function getResultsPageTitleMessageKey( UserIdentity $target ) {
		// The following messages are generated here:
		// * deletedcontributions-title
		// * deletedcontributions-title-for-ip-when-temporary-accounts-enabled
		$messageKey = 'deletedcontributions-title';
		if ( $this->tempUserConfig->isEnabled() && IPUtils::isIPAddress( $target->getName() ) ) {
			$messageKey .= '-for-ip-when-temporary-accounts-enabled';
		}
		return $messageKey;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialDeletedContributions::class, 'SpecialDeletedContributions' );
