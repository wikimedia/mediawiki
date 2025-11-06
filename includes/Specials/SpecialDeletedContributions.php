<?php
/**
 * @license GPL-2.0-or-later
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
use MediaWiki\User\UserGroupAssignmentService;
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
		UserGroupAssignmentService $userGroupAssignmentService,
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
			$userGroupAssignmentService,
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
	protected function getPager( $targetUser ) {
		if ( $this->pager === null ) {
			// Fields in the opts property are usually not normalised, mainly
			// for validations in HTMLForm, especially the 'target' field.
			$options = $this->opts;
			unset( $options['target'] );

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
				$options,
				$targetUser
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
