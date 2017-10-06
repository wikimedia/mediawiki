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
 * @ingroup RevisionDelete
 */

use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * List for revision table items
 *
 * This will check both the 'revision' table for live revisions and the
 * 'archive' table for traditionally-deleted revisions that have an
 * ar_rev_id saved.
 *
 * See RevDelRevisionItem and RevDelArchivedRevisionItem for items.
 */
class RevDelRevisionList extends RevDelList {
	/** @var int */
	public $currentRevId;

	public function getType() {
		return 'revision';
	}

	public static function getRelationType() {
		return 'rev_id';
	}

	public static function getRestriction() {
		return 'deleterevision';
	}

	public static function getRevdelConstant() {
		return Revision::DELETED_TEXT;
	}

	public static function suggestTarget( $target, array $ids ) {
		$rev = Revision::newFromId( $ids[0] );
		return $rev ? $rev->getTitle() : $target;
	}

	/**
	 * @param IDatabase $db
	 * @return mixed
	 */
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		$revQuery = Revision::getQueryInfo( [ 'page', 'user' ] );
		$queryInfo = [
			'tables' => $revQuery['tables'],
			'fields' => $revQuery['fields'],
			'conds' => [
				'rev_page' => $this->title->getArticleID(),
				'rev_id' => $ids,
			],
			'options' => [
				'ORDER BY' => 'rev_id DESC',
				'USE INDEX' => [ 'revision' => 'PRIMARY' ] // workaround for MySQL bug (T104313)
			],
			'join_conds' => $revQuery['joins'],
		];
		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			''
		);

		$live = $db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			__METHOD__,
			$queryInfo['options'],
			$queryInfo['join_conds']
		);
		if ( $live->numRows() >= count( $ids ) ) {
			// All requested revisions are live, keeps things simple!
			return $live;
		}

		$arQuery = Revision::getArchiveQueryInfo();
		$archiveQueryInfo = [
			'tables' => $arQuery['tables'],
			'fields' => $arQuery['fields'],
			'conds' => [
				'ar_rev_id' => $ids,
			],
			'options' => [ 'ORDER BY' => 'ar_rev_id DESC' ],
			'join_conds' => $arQuery['joins'],
		];

		ChangeTags::modifyDisplayQuery(
			$archiveQueryInfo['tables'],
			$archiveQueryInfo['fields'],
			$archiveQueryInfo['conds'],
			$archiveQueryInfo['join_conds'],
			$archiveQueryInfo['options'],
			''
		);

		// Check if any requested revisions are available fully deleted.
		$archived = $db->select(
			$archiveQueryInfo['tables'],
			$archiveQueryInfo['fields'],
			$archiveQueryInfo['conds'],
			__METHOD__,
			$archiveQueryInfo['options'],
			$archiveQueryInfo['join_conds']
		);

		if ( $archived->numRows() == 0 ) {
			return $live;
		} elseif ( $live->numRows() == 0 ) {
			return $archived;
		} else {
			// Combine the two! Whee
			$rows = [];
			foreach ( $live as $row ) {
				$rows[$row->rev_id] = $row;
			}
			foreach ( $archived as $row ) {
				$rows[$row->ar_rev_id] = $row;
			}
			krsort( $rows );
			return new FakeResultWrapper( array_values( $rows ) );
		}
	}

	public function newItem( $row ) {
		if ( isset( $row->rev_id ) ) {
			return new RevDelRevisionItem( $this, $row );
		} elseif ( isset( $row->ar_rev_id ) ) {
			return new RevDelArchivedRevisionItem( $this, $row );
		} else {
			// This shouldn't happen. :)
			throw new MWException( 'Invalid row type in RevDelRevisionList' );
		}
	}

	public function getCurrent() {
		if ( is_null( $this->currentRevId ) ) {
			$dbw = wfGetDB( DB_MASTER );
			$this->currentRevId = $dbw->selectField(
				'page', 'page_latest', $this->title->pageCond(), __METHOD__ );
		}
		return $this->currentRevId;
	}

	public function getSuppressBit() {
		return Revision::DELETED_RESTRICTED;
	}

	public function doPreCommitUpdates() {
		$this->title->invalidateCache();
		return Status::newGood();
	}

	public function doPostCommitUpdates( array $visibilityChangeMap ) {
		$this->title->purgeSquid();
		// Extensions that require referencing previous revisions may need this
		Hooks::run( 'ArticleRevisionVisibilitySet', [ $this->title, $this->ids, $visibilityChangeMap ] );
		return Status::newGood();
	}
}
