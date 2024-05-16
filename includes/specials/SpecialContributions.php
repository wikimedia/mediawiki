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
use MediaWiki\MediaWikiServices;
use MediaWiki\Pager\ContribsPager;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\ContributionsSpecialPage;
use MediaWiki\Specials\Contribute\ContributeFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Special:Contributions, show user contributions in a paged list
 *
 * @ingroup SpecialPage
 */
class SpecialContributions extends ContributionsSpecialPage {
	private LinkBatchFactory $linkBatchFactory;
	private RevisionStore $revisionStore;
	private CommentFormatter $commentFormatter;
	private ?ContribsPager $pager = null;

	/**
	 * @param LinkBatchFactory|null $linkBatchFactory
	 * @param PermissionManager|null $permissionManager
	 * @param IConnectionProvider|null $dbProvider
	 * @param RevisionStore|null $revisionStore
	 * @param NamespaceInfo|null $namespaceInfo
	 * @param UserNameUtils|null $userNameUtils
	 * @param UserNamePrefixSearch|null $userNamePrefixSearch
	 * @param UserOptionsLookup|null $userOptionsLookup
	 * @param CommentFormatter|null $commentFormatter
	 * @param UserFactory|null $userFactory
	 * @param UserIdentityLookup|null $userIdentityLookup
	 * @param DatabaseBlockStore|null $blockStore
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory = null,
		PermissionManager $permissionManager = null,
		IConnectionProvider $dbProvider = null,
		RevisionStore $revisionStore = null,
		NamespaceInfo $namespaceInfo = null,
		UserNameUtils $userNameUtils = null,
		UserNamePrefixSearch $userNamePrefixSearch = null,
		UserOptionsLookup $userOptionsLookup = null,
		CommentFormatter $commentFormatter = null,
		UserFactory $userFactory = null,
		UserIdentityLookup $userIdentityLookup = null,
		DatabaseBlockStore $blockStore = null
	) {
		// This class is extended and therefore falls back to global state - T269521
		$services = MediaWikiServices::getInstance();
		parent::__construct(
			$permissionManager ?? $services->getPermissionManager(),
			$dbProvider ?? $services->getConnectionProvider(),
			$namespaceInfo ?? $services->getNamespaceInfo(),
			$userNameUtils ?? $services->getUserNameUtils(),
			$userNamePrefixSearch ?? $services->getUserNamePrefixSearch(),
			$userOptionsLookup ?? $services->getUserOptionsLookup(),
			$userFactory ?? $services->getUserFactory(),
			$userIdentityLookup ?? $services->getUserIdentityLookup(),
			$blockStore ?? $services->getDatabaseBlockStore(),
			'Contributions',
			''
		);
		$this->linkBatchFactory = $linkBatchFactory ?? $services->getLinkBatchFactory();
		$this->revisionStore = $revisionStore ?? $services->getRevisionStore();
		$this->commentFormatter = $commentFormatter ?? $services->getCommentFormatter();
	}

	/**
	 * @inheritDoc
	 */
	protected function getPager( $targetUser ) {
		if ( $this->pager === null ) {
			$options = [
				'namespace' => $this->opts['namespace'],
				'tagfilter' => $this->opts['tagfilter'],
				'start' => $this->opts['start'] ?? '',
				'end' => $this->opts['end'] ?? '',
				'deletedOnly' => $this->opts['deletedOnly'],
				'topOnly' => $this->opts['topOnly'],
				'newOnly' => $this->opts['newOnly'],
				'hideMinor' => $this->opts['hideMinor'],
				'nsInvert' => $this->opts['nsInvert'],
				'associated' => $this->opts['associated'],
				'tagInvert' => $this->opts['tagInvert'],
			];

			$this->pager = new ContribsPager(
				$this->getContext(),
				$options,
				$this->getLinkRenderer(),
				$this->linkBatchFactory,
				$this->getHookContainer(),
				$this->dbProvider,
				$this->revisionStore,
				$this->namespaceInfo,
				$targetUser,
				$this->commentFormatter
			);
		}

		return $this->pager;
	}

	/**
	 * @inheritDoc
	 */
	public function getShortDescription( string $path = '' ): string {
		return $this->msg( 'special-tab-contributions-short' )->text();
	}

	/**
	 * @inheritDoc
	 */
	public function getAssociatedNavigationLinks(): array {
		if (
			ContributeFactory::isEnabledOnCurrentSkin(
				$this->getSkin(),
				$this->getConfig()->get( 'SpecialContributeSkinsEnabled' )
			)
		) {
			return ContributeFactory::getAssociatedNavigationLinks(
				$this->getUser(),
				$this->getSkin()->getRelevantUser()
			);
		}
		return [];
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialContributions::class, 'SpecialContributions' );
