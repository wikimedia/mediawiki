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
 * Item class for a logging table row
 */
class RevDelLogItem extends RevDelItem {
	public function getIdField() {
		return 'log_id';
	}

	public function getTimestampField() {
		return 'log_timestamp';
	}

	public function getAuthorIdField() {
		return 'log_user';
	}

	public function getAuthorNameField() {
		return 'log_user_text';
	}

	public function getAuthorActorField() {
		return 'log_actor';
	}

	public function canView() {
		return LogEventsList::userCan( $this->row, Revision::DELETED_RESTRICTED, $this->list->getUser() );
	}

	public function canViewContent() {
		return true; // none
	}

	public function getBits() {
		return (int)$this->row->log_deleted;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update( 'logging',
			[ 'log_deleted' => $bits ],
			[
				'log_id' => $this->row->log_id,
				'log_deleted' => $this->getBits() // cas
			],
			__METHOD__
		);

		if ( !$dbw->affectedRows() ) {
			// Concurrent fail!
			return false;
		}

		$dbw->update( 'recentchanges',
			[
				'rc_deleted' => $bits,
				'rc_patrolled' => RecentChange::PRC_PATROLLED
			],
			[
				'rc_logid' => $this->row->log_id,
				'rc_timestamp' => $this->row->log_timestamp // index
			],
			__METHOD__
		);

		return true;
	}

	public function getHTML() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->row->log_timestamp, $this->list->getUser() ) );
		$title = Title::makeTitle( $this->row->log_namespace, $this->row->log_title );
		$formatter = LogFormatter::newFromRow( $this->row );
		$formatter->setContext( $this->list->getContext() );
		$formatter->setAudience( LogFormatter::FOR_THIS_USER );

		// Log link for this page
		$loglink = $this->getLinkRenderer()->makeLink(
			SpecialPage::getTitleFor( 'Log' ),
			$this->list->msg( 'log' )->text(),
			[],
			[ 'page' => $title->getPrefixedText() ]
		);
		$loglink = $this->list->msg( 'parentheses' )->rawParams( $loglink )->escaped();
		// User links and action text
		$action = $formatter->getActionText();
		// Comment
		$comment = CommentStore::getStore()->getComment( 'log_comment', $this->row )->text;
		$comment = $this->list->getLanguage()->getDirMark()
			. Linker::commentBlock( $comment );

		if ( LogEventsList::isDeleted( $this->row, LogPage::DELETED_COMMENT ) ) {
			$comment = '<span class="history-deleted">' . $comment . '</span>';
		}

		return "<li>$loglink $date $action $comment</li>";
	}

	public function getApiData( ApiResult $result ) {
		$logEntry = DatabaseLogEntry::newFromRow( $this->row );
		$user = $this->list->getUser();
		$ret = [
			'id' => $logEntry->getId(),
			'type' => $logEntry->getType(),
			'action' => $logEntry->getSubtype(),
			'userhidden' => (bool)$logEntry->isDeleted( LogPage::DELETED_USER ),
			'commenthidden' => (bool)$logEntry->isDeleted( LogPage::DELETED_COMMENT ),
			'actionhidden' => (bool)$logEntry->isDeleted( LogPage::DELETED_ACTION ),
		];

		if ( LogEventsList::userCan( $this->row, LogPage::DELETED_ACTION, $user ) ) {
			$ret['params'] = LogFormatter::newFromEntry( $logEntry )->formatParametersForApi();
		}
		if ( LogEventsList::userCan( $this->row, LogPage::DELETED_USER, $user ) ) {
			$ret += [
				'userid' => $this->row->log_user,
				'user' => $this->row->log_user_text,
			];
		}
		if ( LogEventsList::userCan( $this->row, LogPage::DELETED_COMMENT, $user ) ) {
			$ret += [
				'comment' => CommentStore::getStore()->getComment( 'log_comment', $this->row )
					->text,
			];
		}

		return $ret;
	}
}
