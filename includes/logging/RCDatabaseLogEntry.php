<?php
/**
 * Contains a class for dealing with recent changes database log entries
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
 * @license GPL-2.0-or-later
 * @since 1.19
 */

/**
 * A subclass of DatabaseLogEntry for objects constructed from entries in the
 * recentchanges table (rather than the logging table).
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
			$actorId = isset( $this->row->rc_actor ) ? (int)$this->row->rc_actor : 0;
			$userId = (int)$this->row->rc_user;
			if ( $actorId !== 0 ) {
				$this->performer = User::newFromActorId( $actorId );
			} elseif ( $userId !== 0 ) {
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
		return Title::makeTitle( $namespace, $page );
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->rc_timestamp );
	}

	public function getComment() {
		return CommentStore::getStore()
			// Legacy because the row may have used RecentChange::selectFields()
			->getCommentLegacy( wfGetDB( DB_REPLICA ), 'rc_comment', $this->row )->text;
	}

	public function getDeleted() {
		return $this->row->rc_deleted;
	}
}
