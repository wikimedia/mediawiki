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

namespace MediaWiki\Revision;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.38
 */
class ArchivedRevisionLookup {

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var RevisionStore */
	private $revisionStore;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param RevisionStore $revisionStore
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore
	) {
		$this->dbProvider = $dbProvider;
		$this->revisionStore = $revisionStore;
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * various archive table fields.
	 *
	 * @param PageIdentity $page
	 * @param array $extraConds Extra conditions to be added to the query
	 * @param ?int $limit The limit to be applied to the query, or null for no limit
	 * @return IResultWrapper
	 */
	public function listRevisions( PageIdentity $page, array $extraConds = [], ?int $limit = null ) {
		$queryBuilder = $this->revisionStore->newArchiveSelectQueryBuilder( $this->dbProvider->getReplicaDatabase() )
			->joinComment()
			->where( $extraConds )
			->andWhere( [ 'ar_namespace' => $page->getNamespace(), 'ar_title' => $page->getDBkey() ] );

		// NOTE: ordering by ar_timestamp and ar_id, to remove ambiguity.
		// XXX: Ideally, we would be ordering by ar_timestamp and ar_rev_id, but since we
		// don't have an index on ar_rev_id, that causes a file sort.
		$queryBuilder->orderBy( [ 'ar_timestamp', 'ar_id' ], SelectQueryBuilder::SORT_DESC );
		if ( $limit !== null ) {
			$queryBuilder->limit( $limit );
		}

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'archive' );

		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/**
	 * Return a RevisionRecord object containing data for the deleted revision.
	 *
	 * @internal only for use in SpecialUndelete
	 *
	 * @param PageIdentity $page
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function getRevisionRecordByTimestamp( PageIdentity $page, $timestamp ): ?RevisionRecord {
		return $this->getRevisionByConditions(
			$page,
			[ 'ar_timestamp' => $this->dbProvider->getReplicaDatabase()->timestamp( $timestamp ) ]
		);
	}

	/**
	 * Return the archived revision with the given ID.
	 *
	 * @param PageIdentity|null $page
	 * @param int $revId
	 * @return RevisionRecord|null
	 */
	public function getArchivedRevisionRecord( ?PageIdentity $page, int $revId ): ?RevisionRecord {
		return $this->getRevisionByConditions( $page, [ 'ar_rev_id' => $revId ] );
	}

	/**
	 * @param PageIdentity|null $page
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return RevisionRecord|null
	 */
	private function getRevisionByConditions(
		?PageIdentity $page,
		array $conditions,
		array $options = []
	): ?RevisionRecord {
		$queryBuilder = $this->revisionStore->newArchiveSelectQueryBuilder( $this->dbProvider->getReplicaDatabase() )
			->joinComment()
			->where( $conditions )
			->options( $options );

		if ( $page ) {
			$queryBuilder->andWhere( [ 'ar_namespace' => $page->getNamespace(), 'ar_title' => $page->getDBkey() ] );
		}

		$row = $queryBuilder->caller( __METHOD__ )->fetchRow();

		if ( $row ) {
			return $this->revisionStore->newRevisionFromArchiveRow( $row, 0, $page );
		}

		return null;
	}

	/**
	 * Return the most-previous revision, either live or deleted, against
	 * the deleted revision given by timestamp.
	 *
	 * May produce unexpected results in case of history merges or other
	 * unusual time issues.
	 *
	 * @param PageIdentity $page
	 * @param string $timestamp
	 * @return RevisionRecord|null Null when there is no previous revision
	 */
	public function getPreviousRevisionRecord( PageIdentity $page, string $timestamp ): ?RevisionRecord {
		$dbr = $this->dbProvider->getReplicaDatabase();

		// Check the previous deleted revision...
		$row = $dbr->newSelectQueryBuilder()
			->select( [ 'ar_rev_id', 'ar_timestamp' ] )
			->from( 'archive' )
			->where( [
				'ar_namespace' => $page->getNamespace(),
				'ar_title' => $page->getDBkey(),
				$dbr->expr( 'ar_timestamp', '<', $dbr->timestamp( $timestamp ) ),
			] )
			->orderBy( 'ar_timestamp DESC' )
			->caller( __METHOD__ )->fetchRow();
		$prevDeleted = $row ? wfTimestamp( TS_MW, $row->ar_timestamp ) : false;
		$prevDeletedId = $row ? intval( $row->ar_rev_id ) : null;

		$row = $dbr->newSelectQueryBuilder()
			->select( [ 'rev_id', 'rev_timestamp' ] )
			->from( 'page' )
			->join( 'revision', null, 'page_id = rev_page' )
			->where( [
				'page_namespace' => $page->getNamespace(),
				'page_title' => $page->getDBkey(),
				$dbr->expr( 'rev_timestamp', '<', $dbr->timestamp( $timestamp ) )
			] )
			->orderBy( 'rev_timestamp DESC' )
			->caller( __METHOD__ )->fetchRow();
		$prevLive = $row ? wfTimestamp( TS_MW, $row->rev_timestamp ) : false;
		$prevLiveId = $row ? intval( $row->rev_id ) : null;

		if ( $prevLive && $prevLive > $prevDeleted ) {
			// Most prior revision was live
			$rec = $this->revisionStore->getRevisionById( $prevLiveId );
		} elseif ( $prevDeleted ) {
			// Most prior revision was deleted
			$rec = $this->getArchivedRevisionRecord( $page, $prevDeletedId );
		} else {
			$rec = null;
		}

		return $rec;
	}

	/**
	 * Returns the ID of the latest deleted revision.
	 *
	 * @param PageIdentity $page
	 *
	 * @return int|false The revision's ID, or false if there is no deleted revision.
	 */
	public function getLastRevisionId( PageIdentity $page ) {
		$revId = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'ar_rev_id' )
			->from( 'archive' )
			->where( [ 'ar_namespace' => $page->getNamespace(), 'ar_title' => $page->getDBkey() ] )
			->orderBy( [ 'ar_timestamp', 'ar_id' ], SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )->fetchField();

		return $revId ? intval( $revId ) : false;
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 * This says nothing about whether the page currently exists in the page table or not.
	 *
	 * @param PageIdentity $page
	 *
	 * @return bool
	 */
	public function hasArchivedRevisions( PageIdentity $page ): bool {
		$row = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( '1' ) // We don't care about the value. Allow the database to optimize.
			->from( 'archive' )
			->where( [
				'ar_namespace' => $page->getNamespace(),
				'ar_title' => $page->getDBkey()
			] )
			->caller( __METHOD__ )
			->fetchRow();

		return (bool)$row;
	}

}
