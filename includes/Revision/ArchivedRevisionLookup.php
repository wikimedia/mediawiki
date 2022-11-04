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

use ChangeTags;
use MediaWiki\Page\PageIdentity;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @since 1.38
 */
class ArchivedRevisionLookup {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var RevisionStore */
	private $revisionStore;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param RevisionStore $revisionStore
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		RevisionStore $revisionStore
	) {
		$this->loadBalancer = $loadBalancer;
		$this->revisionStore = $revisionStore;
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * various archive table fields.
	 *
	 * @param PageIdentity $page
	 * @return IResultWrapper
	 */
	public function listRevisions( PageIdentity $page ) {
		$queryInfo = $this->revisionStore->getArchiveQueryInfo();

		$conds = [
			'ar_namespace' => $page->getNamespace(),
			'ar_title' => $page->getDBkey(),
		];

		// NOTE: ordering by ar_timestamp and ar_id, to remove ambiguity.
		// XXX: Ideally, we would be ordering by ar_timestamp and ar_rev_id, but since we
		// don't have an index on ar_rev_id, that causes a file sort.
		$options = [ 'ORDER BY' => [ 'ar_timestamp DESC', 'ar_id DESC' ] ];

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$conds,
			$queryInfo['joins'],
			$options,
			''
		);

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		return $dbr->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$conds,
			__METHOD__,
			$options,
			$queryInfo['joins']
		);
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
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		return $this->getRevisionByConditions(
			$page,
			[ 'ar_timestamp' => $dbr->timestamp( $timestamp ) ]
		);
	}

	/**
	 * Return the archived revision with the given ID.
	 *
	 * @param PageIdentity $page
	 * @param int $revId
	 * @return RevisionRecord|null
	 */
	public function getArchivedRevisionRecord( PageIdentity $page, int $revId ): ?RevisionRecord {
		return $this->getRevisionByConditions( $page, [ 'ar_rev_id' => $revId ] );
	}

	/**
	 * @param PageIdentity $page
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return RevisionRecord|null
	 */
	private function getRevisionByConditions(
		PageIdentity $page,
		array $conditions,
		array $options = []
	): ?RevisionRecord {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$arQuery = $this->revisionStore->getArchiveQueryInfo();

		$conditions += [
			'ar_namespace' => $page->getNamespace(),
			'ar_title' => $page->getDBkey(),
		];

		$row = $dbr->selectRow(
			$arQuery['tables'],
			$arQuery['fields'],
			$conditions,
			__METHOD__,
			$options,
			$arQuery['joins']
		);

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
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		// Check the previous deleted revision...
		$row = $dbr->selectRow( 'archive',
			[ 'ar_rev_id', 'ar_timestamp' ],
			[ 'ar_namespace' => $page->getNamespace(),
				'ar_title' => $page->getDBkey(),
				'ar_timestamp < ' .
				$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ],
			__METHOD__,
			[
				'ORDER BY' => 'ar_timestamp DESC',
			] );
		$prevDeleted = $row ? wfTimestamp( TS_MW, $row->ar_timestamp ) : false;
		$prevDeletedId = $row ? intval( $row->ar_rev_id ) : null;

		$row = $dbr->selectRow( [ 'page', 'revision' ],
			[ 'rev_id', 'rev_timestamp' ],
			[
				'page_namespace' => $page->getNamespace(),
				'page_title' => $page->getDBkey(),
				'page_id = rev_page',
				'rev_timestamp < ' .
				$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ],
			__METHOD__,
			[
				'ORDER BY' => 'rev_timestamp DESC',
			] );
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
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$revId = $dbr->selectField(
			'archive',
			'ar_rev_id',
			[ 'ar_namespace' => $page->getNamespace(),
				'ar_title' => $page->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => [ 'ar_timestamp DESC', 'ar_id DESC' ] ]
		);

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
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$row = $dbr->selectRow(
			'archive',
			'1', // We don't care about the value. Allow the database to optimize.
			[ 'ar_namespace' => $page->getNamespace(),
				'ar_title' => $page->getDBkey() ],
			__METHOD__
		);

		return (bool)$row;
	}

}
