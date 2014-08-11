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

/**
 * Item class for a live revision table row
 */
class RevDelRevisionItem extends RevDelItem {
	/** @var Revision */
	public $revision;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = new Revision( $row );
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

	public function canView() {
		return $this->revision->userCan( Revision::DELETED_RESTRICTED, $this->list->getUser() );
	}

	public function canViewContent() {
		return $this->revision->userCan( Revision::DELETED_TEXT, $this->list->getUser() );
	}

	public function getBits() {
		return $this->revision->getVisibility();
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		// Update revision table
		$dbw->update( 'revision',
			array( 'rev_deleted' => $bits ),
			array(
				'rev_id' => $this->revision->getId(),
				'rev_page' => $this->revision->getPage(),
				'rev_deleted' => $this->getBits()
			),
			__METHOD__
		);
		if ( !$dbw->affectedRows() ) {
			// Concurrent fail!
			return false;
		}
		// Update recentchanges table
		$dbw->update( 'recentchanges',
			array(
				'rc_deleted' => $bits,
				'rc_patrolled' => 1
			),
			array(
				'rc_this_oldid' => $this->revision->getId(), // condition
				// non-unique timestamp index
				'rc_timestamp' => $dbw->timestamp( $this->revision->getTimestamp() ),
			),
			__METHOD__
		);

		return true;
	}

	public function isDeleted() {
		return $this->revision->isDeleted( Revision::DELETED_TEXT );
	}

	public function isHideCurrentOp( $newBits ) {
		return ( $newBits & Revision::DELETED_TEXT )
			&& $this->list->getCurrent() == $this->getId();
	}

	/**
	 * Get the HTML link to the revision text.
	 * Overridden by RevDelArchiveItem.
	 * @return string
	 */
	protected function getRevisionLink() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->revision->getTimestamp(), $this->list->getUser() ) );

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}

		return Linker::linkKnown(
			$this->list->title,
			$date,
			array(),
			array(
				'oldid' => $this->revision->getId(),
				'unhide' => 1
			)
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
			return Linker::linkKnown(
					$this->list->title,
					$this->list->msg( 'diff' )->escaped(),
					array(),
					array(
						'diff' => $this->revision->getId(),
						'oldid' => 'prev',
						'unhide' => 1
					)
				);
		}
	}

	public function getHTML() {
		$difflink = $this->list->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $this->revision );
		$comment = Linker::revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}

		return "<li>$difflink $revlink $userlink $comment</li>";
	}

	public function getApiData( ApiResult $result ) {
		$rev = $this->revision;
		$user = $this->list->getUser();
		$ret = array(
			'id' => $rev->getId(),
			'timestamp' => wfTimestamp( TS_ISO_8601, $rev->getTimestamp() ),
		);
		$ret += $rev->isDeleted( Revision::DELETED_USER ) ? array( 'userhidden' => '' ) : array();
		$ret += $rev->isDeleted( Revision::DELETED_COMMENT ) ? array( 'commenthidden' => '' ) : array();
		$ret += $rev->isDeleted( Revision::DELETED_TEXT ) ? array( 'texthidden' => '' ) : array();
		if ( $rev->userCan( Revision::DELETED_USER, $user ) ) {
			$ret += array(
				'userid' => $rev->getUser( Revision::FOR_THIS_USER ),
				'user' => $rev->getUserText( Revision::FOR_THIS_USER ),
			);
		}
		if ( $rev->userCan( Revision::DELETED_COMMENT, $user ) ) {
			$ret += array(
				'comment' => $rev->getComment( Revision::FOR_THIS_USER ),
			);
		}

		return $ret;
	}
}
