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
		UserIdentity $target
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
			$target
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
			->where( [ 'actor_name' => $this->target ] );

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
