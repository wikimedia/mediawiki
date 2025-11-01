<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Store a list of taggable revisions.
 *
 * @since 1.25
 * @ingroup ChangeTags
 */
class ChangeTagsRevisionList extends ChangeTagsList {
	/** @inheritDoc */
	public function getType() {
		return 'revision';
	}

	/**
	 * @param \Wikimedia\Rdbms\IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$queryBuilder = MediaWikiServices::getInstance()->getRevisionStore()->newSelectQueryBuilder( $db )
			->joinComment()
			->joinUser()
			->where( [ 'rev_page' => $this->page->getId(), 'rev_id' => $ids ] )
			->orderBy( 'rev_id', SelectQueryBuilder::SORT_DESC );

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'revision' );
		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new ChangeTagsRevisionItem( $this, $row );
	}

	/**
	 * Add/remove change tags from all the revisions in the list.
	 *
	 * @param string[] $tagsToAdd
	 * @param string[] $tagsToRemove
	 * @param string|null $params
	 * @param string $reason
	 * @param Authority $performer
	 * @return Status
	 */
	public function updateChangeTagsOnAll(
		array $tagsToAdd,
		array $tagsToRemove,
		?string $params,
		string $reason,
		Authority $performer
	) {
		$status = Status::newGood();
		for ( $this->reset(); $this->current(); $this->next() ) {
			$item = $this->current();
			$status = ChangeTags::updateTagsWithChecks( $tagsToAdd, $tagsToRemove,
				null, $item->getId(), null, $params, $reason, $performer );
			// Should only fail on second and subsequent times if the user trips
			// the rate limiter
			if ( !$status->isOK() ) {
				break;
			}
		}

		return $status;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangeTagsRevisionList::class, 'ChangeTagsRevisionList' );
