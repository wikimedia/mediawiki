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
	var $mTitle, $mUser, $id, $ns, $ti;
	private $loaded = false, $watched, $timestamp;

	/**
	 * Create a WatchedItem object with the given user and title
	 * @param $user User: the user to use for (un)watching
	 * @param $title Title: the title we're going to (un)watch
	 * @return WatchedItem object
	 */
	public static function fromUserTitle( $user, $title ) {
		$wl = new WatchedItem;
		$wl->mUser = $user;
		$wl->mTitle = $title;
		$wl->id = $user->getId();
		# Patch (also) for email notification on page changes T.Gries/M.Arndt 11.09.2004
		# TG patch: here we do not consider pages and their talk pages equivalent - why should we ?
		# The change results in talk-pages not automatically included in watchlists, when their parent page is included
		# $wl->ns = $title->getNamespace() & ~1;
		$wl->ns = $title->getNamespace();

		$wl->ti = $title->getDBkey();
		return $wl;
	}

	/**
	 * Return an array of conditions to select or update the appropriate database
	 * row.
	 *
	 * @return array
	 */
	private function dbCond() {
		return array( 'wl_user' => $this->id, 'wl_namespace' => $this->ns, 'wl_title' => $this->ti );
	}

	/**
	 * Load the object from the database
	 */
	private function load() {
		if ( $this->loaded ) {
			return;
		}
		$this->loaded = true;

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
	 * Is mTitle being watched by mUser?
	 * @return bool
	 */
	public function isWatched() {
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
	 */
	public function resetNotificationTimestamp( $force = '' ) {
		if ( $force != 'force' ) {
			$this->load();
			if ( !$this->watched || $this->timestamp === null ) {
				return;
			}
		}

		// If the page is watched by the user (or may be watched), update the timestamp on any
		// any matching rows
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'watchlist', array( 'wl_notificationtimestamp' => null ),
			$this->dbCond(), __METHOD__ );
		$this->timestamp = null;
	}

	/**
	 * Given a title and user (assumes the object is setup), add the watch to the
	 * database.
	 * @return bool (always true)
	 */
	public function addWatch() {
		wfProfileIn( __METHOD__ );

		// Use INSERT IGNORE to avoid overwriting the notification timestamp
		// if there's already an entry for this page
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'watchlist',
		  array(
			'wl_user' => $this->id,
			'wl_namespace' => MWNamespace::getSubject($this->ns),
			'wl_title' => $this->ti,
			'wl_notificationtimestamp' => null
		  ), __METHOD__, 'IGNORE' );

		// Every single watched page needs now to be listed in watchlist;
		// namespace:page and namespace_talk:page need separate entries:
		$dbw->insert( 'watchlist',
		  array(
			'wl_user' => $this->id,
			'wl_namespace' => MWNamespace::getTalk($this->ns),
			'wl_title' => $this->ti,
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

		$success = false;
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'watchlist',
			array(
				'wl_user' => $this->id,
				'wl_namespace' => MWNamespace::getSubject($this->ns),
				'wl_title' => $this->ti
			), __METHOD__
		);
		if ( $dbw->affectedRows() ) {
			$success = true;
		}

		# the following code compensates the new behaviour, introduced by the
		# enotif patch, that every single watched page needs now to be listed
		# in watchlist namespace:page and namespace_talk:page had separate
		# entries: clear them
		$dbw->delete( 'watchlist',
			array(
				'wl_user' => $this->id,
				'wl_namespace' => MWNamespace::getTalk($this->ns),
				'wl_title' => $this->ti
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

		if( empty( $values ) ) {
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
