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
use MediaWiki\MediaWikiServices;
use Title;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @unstable Don't use this, it WILL change
 */
class ArchivedRevisionLookup {

	/** @var Title */
	protected $title;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var RevisionStore */
	private $revisionStore;

	/**
	 * @param Title $title
	 */
	public function __construct( Title $title ) {
		$this->title = $title;

		$services = MediaWikiServices::getInstance();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->revisionStore = $services->getRevisionStore();
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * various archive table fields.
	 *
	 * @return IResultWrapper|bool
	 */
	public function listRevisions() {
		$queryInfo = $this->revisionStore->getArchiveQueryInfo();

		$conds = [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
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
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function getRevisionRecordByTimestamp( $timestamp ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$rec = $this->getRevisionByConditions(
			[ 'ar_timestamp' => $dbr->timestamp( $timestamp ) ]
		);
		return $rec;
	}

	/**
	 * Return the archived revision with the given ID.
	 *
	 * @param int $revId
	 * @return RevisionRecord|null
	 */
	public function getArchivedRevisionRecord( int $revId ) {
		return $this->getRevisionByConditions( [ 'ar_rev_id' => $revId ] );
	}

	/**
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return RevisionRecord|null
	 */
	private function getRevisionByConditions( array $conditions, array $options = [] ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$arQuery = $this->revisionStore->getArchiveQueryInfo();

		$conditions += [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
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
			return $this->revisionStore->newRevisionFromArchiveRow( $row, 0, $this->title );
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
	 * @param string $timestamp
	 * @return RevisionRecord|null Null when there is no previous revision
	 */
	public function getPreviousRevisionRecord( string $timestamp ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		// Check the previous deleted revision...
		$row = $dbr->selectRow( 'archive',
			[ 'ar_rev_id', 'ar_timestamp' ],
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey(),
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
				'page_namespace' => $this->title->getNamespace(),
				'page_title' => $this->title->getDBkey(),
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
			$rec = $this->getArchivedRevisionRecord( $prevDeletedId );
		} else {
			$rec = null;
		}

		return $rec;
	}

	/**
	 * Returns the ID of the latest deleted revision.
	 *
	 * @return int|false The revision's ID, or false if there is no deleted revision.
	 */
	public function getLastRevisionId() {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$revId = $dbr->selectField(
			'archive',
			'ar_rev_id',
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => [ 'ar_timestamp DESC', 'ar_id DESC' ] ]
		);

		return $revId ? intval( $revId ) : false;
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 * This says nothing about whether the page currently exists in the page table or not.
	 *
	 * @return bool
	 */
	public function isDeleted() {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$row = $dbr->selectRow(
			[ 'archive' ],
			'1', // We don't care about the value. Allow the database to optimize.
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey() ],
			__METHOD__
		);

		return (bool)$row;
	}

}
