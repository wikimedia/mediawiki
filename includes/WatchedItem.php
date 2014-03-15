<?php
/**
 * Accessor and mutator for watchlist entries.
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
 * @ingroup Watchlist
 */

/**
 * Representation of a pair of user and title for watchlist entries.
 *
 * @ingroup Watchlist
 */
class WatchedItem {
	/**
	 * Constant to specify that user rights 'editmywatchlist' and
	 * 'viewmywatchlist' should not be checked.
	 * @since 1.22
	 */
	const IGNORE_USER_RIGHTS = 0;

	/**
	 * Constant to specify that user rights 'editmywatchlist' and
	 * 'viewmywatchlist' should be checked.
	 * @since 1.22
	 */
	const CHECK_USER_RIGHTS = 1;

	var $mTitle, $mUser, $mCheckRights;
	private $loaded = false, $watched, $timestamp;

	/**
	 * Create a WatchedItem object with the given user and title
	 * @since 1.22 $checkRights parameter added
	 * @param $user User: the user to use for (un)watching
	 * @param $title Title: the title we're going to (un)watch
	 * @param $checkRights int: Whether to check the 'viewmywatchlist' and 'editmywatchlist' rights.
	 *     Pass either WatchedItem::IGNORE_USER_RIGHTS or WatchedItem::CHECK_USER_RIGHTS.
	 * @return WatchedItem object
	 */
	public static function fromUserTitle( $user, $title, $checkRights = WatchedItem::CHECK_USER_RIGHTS ) {
		$wl = new WatchedItem;
		$wl->mUser = $user;
		$wl->mTitle = $title;
		$wl->mCheckRights = $checkRights;

		return $wl;
	}

	/**
	 * Title being watched
	 * @return Title
	 */
	protected function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Helper to retrieve the title namespace
	 * @return int
	 */
	protected function getTitleNs() {
		return $this->getTitle()->getNamespace();
	}

	/**
	 * Helper to retrieve the title DBkey
	 * @return string
	 */
	protected function getTitleDBkey() {
		return $this->getTitle()->getDBkey();
	}

	/**
	 * Helper to retrieve the user id
	 * @return int
	 */
	protected function getUserId() {
		return $this->mUser->getId();
	}

	/**
	 * Return an array of conditions to select or update the appropriate database
	 * row.
	 *
	 * @return array
	 */
	private function dbCond() {
		return array(
			'wl_user' => $this->getUserId(),
			'wl_namespace' => $this->getTitleNs(),
			'wl_title' => $this->getTitleDBkey(),
		);
	}

	/**
	 * Load the object from the database
	 */
	private function load() {
		if ( $this->loaded ) {
			return;
		}
		$this->loaded = true;

		// Only loggedin user can have a watchlist
		if ( $this->mUser->isAnon() ) {
			$this->watched = false;
			return;
		}

		// some pages cannot be watched
		if ( !$this->getTitle()->isWatchable() ) {
			$this->watched = false;
			return;
		}

		# Pages and their talk pages are considered equivalent for watching;
		# remember that talk namespaces are numbered as page namespace+1.

		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'watchlist', 'wl_notificationtimestamp',
			$this->dbCond(), __METHOD__ );

		if ( $row === false ) {
			$this->watched = false;
		} else {
			$this->watched = true;
			$this->timestamp = $row->wl_notificationtimestamp;
		}
	}

	/**
	 * Check permissions
	 * @param $what string: 'viewmywatchlist' or 'editmywatchlist'
	 */
	private function isAllowed( $what ) {
		return !$this->mCheckRights || $this->mUser->isAllowed( $what );
	}

	/**
	 * Is mTitle being watched by mUser?
	 * @return bool
	 */
	public function isWatched() {
		if ( !$this->isAllowed( 'viewmywatchlist' ) ) {
			return false;
		}

		$this->load();
		return $this->watched;
	}

	/**
	 * Get the notification timestamp of this entry.
	 *
	 * @return false|null|string: false if the page is not watched, the value of
	 *         the wl_notificationtimestamp field otherwise
	 */
	public function getNotificationTimestamp() {
		if ( !$this->isAllowed( 'viewmywatchlist' ) ) {
			return false;
		}

		$this->load();
		if ( $this->watched ) {
			return $this->timestamp;
		} else {
			return false;
		}
	}

	/**
	 * Reset the notification timestamp of this entry
	 *
	 * @param $force Whether to force the write query to be executed even if the
	 *        page is not watched or the notification timestamp is already NULL.
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 */
	public function resetNotificationTimestamp( $force = '', $oldid = 0 ) {
		// Only loggedin user can have a watchlist
		if ( wfReadOnly() || $this->mUser->isAnon() || !$this->isAllowed( 'editmywatchlist' ) ) {
			return;
		}

		if ( $force != 'force' ) {
			$this->load();
			if ( !$this->watched || $this->timestamp === null ) {
				return;
			}
		}

		$title = $this->getTitle();
		if ( !$oldid ) {
			// No oldid given, assuming latest revision; clear the timestamp.
			$notificationTimestamp = null;
		} elseif ( !$title->getNextRevisionID( $oldid ) ) {
			// Oldid given and is the latest revision for this title; clear the timestamp.
			$notificationTimestamp = null;
		} else {
			// See if the version marked as read is more recent than the one we're viewing.
			// Call load() if it wasn't called before due to $force.
			$this->load();

			if ( $this->timestamp === null ) {
				// This can only happen if $force is enabled.
				$notificationTimestamp = null;
			} else {
				// Oldid given and isn't the latest; update the timestamp.
				// This will result in no further notification emails being sent!
				$dbr = wfGetDB( DB_SLAVE );
				$notificationTimestamp = $dbr->selectField(
					'revision', 'rev_timestamp',
					array( 'rev_page' => $title->getArticleID(), 'rev_id' => $oldid )
				);
				// We need to go one second to the future because of various strict comparisons
				// throughout the codebase
				$ts = new MWTimestamp( $notificationTimestamp );
				$ts->timestamp->add( new DateInterval( 'PT1S' ) );
				$notificationTimestamp = $ts->getTimestamp( TS_MW );

				if ( $notificationTimestamp < $this->timestamp ) {
					if ( $force != 'force' ) {
						return;
					} else {
						// This is a little silly…
						$notificationTimestamp = $this->timestamp;
					}
				}
			}
		}

		// If the page is watched by the user (or may be watched), update the timestamp on any
		// any matching rows
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'watchlist', array( 'wl_notificationtimestamp' => $notificationTimestamp ),
			$this->dbCond(), __METHOD__ );
		$this->timestamp = null;
	}

	/**
	 * Given a title and user (assumes the object is setup), add the watch to the
	 * database.
	 * @return bool
	 */
	public function addWatch() {
		wfProfileIn( __METHOD__ );

		// Only loggedin user can have a watchlist
		if ( wfReadOnly() || $this->mUser->isAnon() || !$this->isAllowed( 'editmywatchlist' ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		// Use INSERT IGNORE to avoid overwriting the notification timestamp
		// if there's already an entry for this page
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'watchlist',
			array(
				'wl_user' => $this->getUserId(),
				'wl_namespace' => MWNamespace::getSubject( $this->getTitleNs() ),
				'wl_title' => $this->getTitleDBkey(),
				'wl_notificationtimestamp' => null
			), __METHOD__, 'IGNORE' );

		// Every single watched page needs now to be listed in watchlist;
		// namespace:page and namespace_talk:page need separate entries:
		$dbw->insert( 'watchlist',
			array(
				'wl_user' => $this->getUserId(),
				'wl_namespace' => MWNamespace::getTalk( $this->getTitleNs() ),
				'wl_title' => $this->getTitleDBkey(),
				'wl_notificationtimestamp' => null
			), __METHOD__, 'IGNORE' );

		$this->watched = true;

		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * Same as addWatch, only the opposite.
	 * @return bool
	 */
	public function removeWatch() {
		wfProfileIn( __METHOD__ );

		// Only loggedin user can have a watchlist
		if ( wfReadOnly() || $this->mUser->isAnon() || !$this->isAllowed( 'editmywatchlist' ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$success = false;
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'watchlist',
			array(
				'wl_user' => $this->getUserId(),
				'wl_namespace' => MWNamespace::getSubject( $this->getTitleNs() ),
				'wl_title' => $this->getTitleDBkey(),
			), __METHOD__
		);
		if ( $dbw->affectedRows() ) {
			$success = true;
		}

		# the following code compensates the new behavior, introduced by the
		# enotif patch, that every single watched page needs now to be listed
		# in watchlist namespace:page and namespace_talk:page had separate
		# entries: clear them
		$dbw->delete( 'watchlist',
			array(
				'wl_user' => $this->getUserId(),
				'wl_namespace' => MWNamespace::getTalk( $this->getTitleNs() ),
				'wl_title' => $this->getTitleDBkey(),
			), __METHOD__
		);

		if ( $dbw->affectedRows() ) {
			$success = true;
		}

		$this->watched = false;

		wfProfileOut( __METHOD__ );
		return $success;
	}

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add watches on a new title. To be used for page renames and such.
	 *
	 * @param $ot Title: page title to duplicate entries from, if present
	 * @param $nt Title: page title to add watches on
	 */
	public static function duplicateEntries( $ot, $nt ) {
		WatchedItem::doDuplicateEntries( $ot->getSubjectPage(), $nt->getSubjectPage() );
		WatchedItem::doDuplicateEntries( $ot->getTalkPage(), $nt->getTalkPage() );
	}

	/**
	 * Handle duplicate entries. Backend for duplicateEntries().
	 *
	 * @param $ot Title
	 * @param $nt Title
	 *
	 * @return bool
	 */
	private static function doDuplicateEntries( $ot, $nt ) {
		$oldnamespace = $ot->getNamespace();
		$newnamespace = $nt->getNamespace();
		$oldtitle = $ot->getDBkey();
		$newtitle = $nt->getDBkey();

		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select( 'watchlist', 'wl_user',
			array( 'wl_namespace' => $oldnamespace, 'wl_title' => $oldtitle ),
			__METHOD__, 'FOR UPDATE'
		);
		# Construct array to replace into the watchlist
		$values = array();
		foreach ( $res as $s ) {
			$values[] = array(
				'wl_user' => $s->wl_user,
				'wl_namespace' => $newnamespace,
				'wl_title' => $newtitle
			);
		}

		if ( empty( $values ) ) {
			// Nothing to do
			return true;
		}

		# Perform replace
		# Note that multi-row replace is very efficient for MySQL but may be inefficient for
		# some other DBMSes, mostly due to poor simulation by us
		$dbw->replace( 'watchlist', array( array( 'wl_user', 'wl_namespace', 'wl_title' ) ), $values, __METHOD__ );
		return true;
	}
}
