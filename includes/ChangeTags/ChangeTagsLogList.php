<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Store a list of taggable log entries.
 *
 * @since 1.25
 * @ingroup ChangeTags
 */
class ChangeTagsLogList extends ChangeTagsList {
	/** @inheritDoc */
	public function getType() {
		return 'logentry';
	}

	/**
	 * @param \Wikimedia\Rdbms\IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$queryBuilder = DatabaseLogEntry::newSelectQueryBuilder( $db )
			->where( [ 'log_id' => $ids ] )
			->orderBy( [ 'log_timestamp', 'log_id' ], SelectQueryBuilder::SORT_DESC );

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'logging' );
		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new ChangeTagsLogItem( $this, $row );
	}

	/**
	 * Add/remove change tags from all the log entries in the list.
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
				null, null, $item->getId(), $params, $reason, $performer );
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
class_alias( ChangeTagsLogList::class, 'ChangeTagsLogList' );
