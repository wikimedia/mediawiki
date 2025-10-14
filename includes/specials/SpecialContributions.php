<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\ContribsPager;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\ContributionsSpecialPage;
use MediaWiki\Specials\Contribute\ContributeFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupAssignmentService;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use Wikimedia\IPUtils;
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
	private TempUserConfig $tempUserConfig;
	private ?ContribsPager $pager = null;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		PermissionManager $permissionManager,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		NamespaceInfo $namespaceInfo,
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		UserOptionsLookup $userOptionsLookup,
		CommentFormatter $commentFormatter,
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
			'Contributions',
			''
		);
		$this->linkBatchFactory = $linkBatchFactory;
		$this->revisionStore = $revisionStore;
		$this->commentFormatter = $commentFormatter;
		$this->tempUserConfig = $tempUserConfig;
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
				$this->getConfig()->get( MainConfigNames::SpecialContributeSkinsEnabled )
			)
		) {
			return ContributeFactory::getAssociatedNavigationLinks(
				$this->getUser(),
				$this->getSkin()->getRelevantUser()
			);
		}
		return [];
	}

	/** @inheritDoc */
	protected function getResultsPageTitleMessageKey( UserIdentity $target ) {
		// The following messages are generated here:
		// * contributions-title
		// * contributions-title-for-ip-when-temporary-accounts-enabled
		$messageKey = 'contributions-title';
		if ( $this->tempUserConfig->isEnabled() && IPUtils::isIPAddress( $target->getName() ) ) {
			$messageKey .= '-for-ip-when-temporary-accounts-enabled';
		}
		return $messageKey;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialContributions::class, 'SpecialContributions' );
