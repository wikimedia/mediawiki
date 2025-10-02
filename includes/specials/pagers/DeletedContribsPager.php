<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class DeletedContribsPager extends ContributionsPager {
	public function __construct(
		HookContainer $hookContainer,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		NamespaceInfo $namespaceInfo,
		CommentFormatter $commentFormatter,
		LinkBatchFactory $linkBatchFactory,
		UserFactory $userFactory,
		IContextSource $context,
		array $options,
		UserIdentity $targetUser
	) {
		$options['isArchive'] = true;

		parent::__construct(
			$linkRenderer,
			$linkBatchFactory,
			$hookContainer,
			$revisionStore,
			$namespaceInfo,
			$commentFormatter,
			$userFactory,
			$context,
			$options,
			$targetUser
		);

		$this->revisionIdField = 'ar_rev_id';
		$this->revisionParentIdField = 'ar_parent_id';
		$this->revisionTimestampField = 'ar_timestamp';
		$this->revisionLengthField = 'ar_len';
		$this->revisionDeletedField = 'ar_deleted';
		$this->revisionMinorField = 'ar_minor_edit';
		$this->userNameField = 'ar_user_text';
		$this->pageNamespaceField = 'ar_namespace';
		$this->pageTitleField = 'ar_title';
	}

	/**
	 * @inheritDoc
	 */
	protected function getRevisionQuery() {
		$queryBuilder = $this->revisionStore->newArchiveSelectQueryBuilder( $this->getDatabase() )
			->joinComment()
			->where( [ 'actor_name' => $this->targetUser->getName() ] );

		return $queryBuilder->getQueryInfo();
	}

	/**
	 * @return string[]
	 */
	protected function getExtraSortFields() {
		return [ 'ar_id' ];
	}

	/** @inheritDoc */
	public function getIndexField() {
		return 'ar_timestamp';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( DeletedContribsPager::class, 'DeletedContribsPager' );
