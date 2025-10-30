<?php
/**
 * Holders of revision list for a single page
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RevisionList;

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\SelectQueryBuilder;

class RevisionList extends RevisionListBase {
	/** @inheritDoc */
	public function getType() {
		return 'revision';
	}

	/** @inheritDoc */
	public function doQuery( $db ) {
		$queryBuilder = MediaWikiServices::getInstance()->getRevisionStore()->newSelectQueryBuilder( $db )
			->joinComment()
			->joinPage()
			->joinUser()
			->where( [ 'rev_page' => $this->page->getId() ] )
			->orderBy( 'rev_id', SelectQueryBuilder::SORT_DESC );
		if ( $this->ids !== null ) {
			$queryBuilder->andWhere( [ 'rev_id' => array_map( 'intval', $this->ids ) ] );
		}
		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new RevisionItem( $this, $row );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( RevisionList::class, 'RevisionList' );
