<?php
/**
 * Recent changes database log entry
 *
 * This is how I see the log system history:
 * - appending to plain wiki pages
 * - formatting log entries based on database fields
 * - user is now part of the action message
 *
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
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.19
 */

class RCDatabaseLogEntry extends DatabaseLogEntry {

	public function getId() {
		return $this->row->rc_logid;
	}

	protected function getRawParameters() {
		return $this->row->rc_params;
	}

	public function getAssociatedRevId() {
		return $this->row->rc_this_oldid;
	}

	public function getType() {
		return $this->row->rc_log_type;
	}

	public function getSubtype() {
		return $this->row->rc_log_action;
	}

	public function getPerformer() {
		if ( !$this->performer ) {
			$userId = (int)$this->row->rc_user;
			if ( $userId !== 0 ) {
				$this->performer = User::newFromId( $userId );
			} else {
				$userText = $this->row->rc_user_text;
				// Might be an IP, don't validate the username
				$this->performer = User::newFromName( $userText, false );
			}
		}

		return $this->performer;
	}

	public function getTarget() {
		$namespace = $this->row->rc_namespace;
		$page = $this->row->rc_title;
		$title = Title::makeTitle( $namespace, $page );

		return $title;
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->rc_timestamp );
	}

	public function getComment() {
		return CommentStore::newKey( 'rc_comment' )
			// Legacy because the row may have used RecentChange::selectFields()
			->getCommentLegacy( wfGetDB( DB_REPLICA ), $this->row )->text;
	}

	public function getDeleted() {
		return $this->row->rc_deleted;
	}
}
