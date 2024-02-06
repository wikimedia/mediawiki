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

use MediaWiki\Api\ApiResult;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\MediaWikiServices;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\RevisionList\RevisionListBase;

/**
 * Item class for a live revision table row
 *
 * @property RevDelRevisionList $list
 */
class RevDelRevisionItem extends RevDelItem {
	/** @var RevisionRecord */
	public $revisionRecord;

	public function __construct( RevisionListBase $list, $row ) {
		parent::__construct( $list, $row );
		$this->revisionRecord = static::initRevisionRecord( $list, $row );
	}

	/**
	 * Create RevisionRecord object from $row sourced from $list
	 *
	 * @param RevisionListBase $list
	 * @param mixed $row
	 * @return RevisionRecord
	 */
	protected static function initRevisionRecord( $list, $row ) {
		return MediaWikiServices::getInstance()
			->getRevisionFactory()
			->newRevisionFromRow( $row );
	}

	/**
	 * Get the RevisionRecord for the item
	 */
	protected function getRevisionRecord(): RevisionRecord {
		return $this->revisionRecord;
	}

	public function getIdField() {
		return 'rev_id';
	}

	public function getTimestampField() {
		return 'rev_timestamp';
	}

	public function getAuthorIdField() {
		return 'rev_user';
	}

	public function getAuthorNameField() {
		return 'rev_user_text';
	}

	public function getAuthorActorField() {
		return 'rev_actor';
	}

	public function canView() {
		return $this->getRevisionRecord()->userCan(
			RevisionRecord::DELETED_RESTRICTED,
			$this->list->getAuthority()
		);
	}

	public function canViewContent() {
		return $this->getRevisionRecord()->userCan(
			RevisionRecord::DELETED_TEXT,
			$this->list->getAuthority()
		);
	}

	public function getBits() {
		return $this->getRevisionRecord()->getVisibility();
	}

	public function setBits( $bits ) {
		$revRecord = $this->getRevisionRecord();

		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		// Update revision table
		$dbw->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_deleted' => $bits ] )
			->where( [
				'rev_id' => $revRecord->getId(),
				'rev_page' => $revRecord->getPageId(),
				'rev_deleted' => $this->getBits() // cas
			] )
			->caller( __METHOD__ )->execute();

		if ( !$dbw->affectedRows() ) {
			// Concurrent fail!
			return false;
		}
		// Update recentchanges table
		$dbw->newUpdateQueryBuilder()
			->update( 'recentchanges' )
			->set( [
				'rc_deleted' => $bits,
				'rc_patrolled' => RecentChange::PRC_AUTOPATROLLED
			] )
			->where( [ 'rc_this_oldid' => $revRecord->getId() ] )
			->caller( __METHOD__ )->execute();

		return true;
	}

	public function isDeleted() {
		return $this->getRevisionRecord()->isDeleted( RevisionRecord::DELETED_TEXT );
	}

	public function isHideCurrentOp( $newBits ) {
		return ( $newBits & RevisionRecord::DELETED_TEXT )
			&& $this->list->getCurrent() == $this->getId();
	}

	/**
	 * Get the HTML link to the revision text.
	 * Overridden by RevDelArchiveItem.
	 * @return string
	 */
	protected function getRevisionLink() {
		$date = $this->list->getLanguage()->userTimeAndDate(
			$this->getRevisionRecord()->getTimestamp(),
			$this->list->getUser()
		);

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return htmlspecialchars( $date );
		}

		return $this->getLinkRenderer()->makeKnownLink(
			$this->list->getPage(),
			$date,
			[],
			[
				'oldid' => $this->getRevisionRecord()->getId(),
				'unhide' => 1
			]
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * Overridden by RevDelArchiveItem
	 * @return string
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $this->list->msg( 'diff' )->escaped();
		} else {
			return $this->getLinkRenderer()->makeKnownLink(
				$this->list->getPage(),
				$this->list->msg( 'diff' )->text(),
				[],
				[
					'diff' => $this->getRevisionRecord()->getId(),
					'oldid' => 'prev',
					'unhide' => 1
				]
			);
		}
	}

	/**
	 * @return string A HTML <li> element representing this revision, showing
	 * change tags and everything
	 */
	public function getHTML() {
		$revRecord = $this->getRevisionRecord();

		$difflink = $this->list->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $revRecord );
		$comment = MediaWikiServices::getInstance()->getCommentFormatter()
			->formatRevision( $revRecord, $this->list->getAuthority() );
		if ( $this->isDeleted() ) {
			$class = Linker::getRevisionDeletedClass( $revRecord );
			$revlink = "<span class=\"$class\">$revlink</span>";
		}
		$content = "$difflink $revlink $userlink $comment";
		$attribs = [];
		$tags = $this->getTags();
		if ( $tags ) {
			[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
				$tags,
				'revisiondelete',
				$this->list->getContext()
			);
			$content .= " $tagSummary";
			$attribs['class'] = implode( ' ', $classes );
		}
		return Html::rawElement( 'li', $attribs, $content );
	}

	/**
	 * @return string Comma-separated list of tags
	 */
	public function getTags() {
		return $this->row->ts_tags;
	}

	public function getApiData( ApiResult $result ) {
		$revRecord = $this->getRevisionRecord();
		$authority = $this->list->getAuthority();
		$ret = [
			'id' => $revRecord->getId(),
			'timestamp' => wfTimestamp( TS_ISO_8601, $revRecord->getTimestamp() ),
			'userhidden' => (bool)$revRecord->isDeleted( RevisionRecord::DELETED_USER ),
			'commenthidden' => (bool)$revRecord->isDeleted( RevisionRecord::DELETED_COMMENT ),
			'texthidden' => (bool)$revRecord->isDeleted( RevisionRecord::DELETED_TEXT ),
		];
		if ( $revRecord->userCan( RevisionRecord::DELETED_USER, $authority ) ) {
			$revUser = $revRecord->getUser( RevisionRecord::FOR_THIS_USER, $authority );
			$ret += [
				'userid' => $revUser ? $revUser->getId() : 0,
				'user' => $revUser ? $revUser->getName() : '',
			];
		}
		if ( $revRecord->userCan( RevisionRecord::DELETED_COMMENT, $authority ) ) {
			$revComment = $revRecord->getComment( RevisionRecord::FOR_THIS_USER, $authority );
			$ret += [
				'comment' => $revComment ? $revComment->text : ''
			];
		}

		return $ret;
	}
}
