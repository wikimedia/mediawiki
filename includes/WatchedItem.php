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
	/** @var Title */
	public $mTitle;

	/** @var User */
	public $mUser;

	/** @var int */
	public $mCheckRights;

	/** @var bool */
	private $loaded = false;

	/** @var bool */
	private $watched;

	/** @var string */
	private $timestamp;

	/** @var string MW_TS format */
	private $expiry;

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

	/**
	 * Do DB master updates right now
	 * @since 1.26
	 */
	const IMMEDIATE = 0;
	/**
	 * Do DB master updates via the job queue
	 * @since 1.26
	 */
	const DEFERRED = 1;

	/**
	 * Create a WatchedItem object with the given user and title
	 * @since 1.22 $checkRights parameter added
	 * @since 1.27 $expiry parameter added
	 * @param User $user The user to use for (un)watching
	 * @param Title $title The title we're going to (un)watch
	 * @param string|null $expiry MW_TS format
	 * @param int $checkRights Whether to check the 'viewmywatchlist' and 'editmywatchlist' rights.
	 *     Pass either WatchedItem::IGNORE_USER_RIGHTS or WatchedItem::CHECK_USER_RIGHTS.
	 * @return WatchedItem
	 */
	public static function fromUserTitle( $user, $title, $expiry = null,
		$checkRights = WatchedItem::CHECK_USER_RIGHTS
	) {
		// Maintain back compat pre 1.27 method signiture
		if ( $expiry === WatchedItem::CHECK_USER_RIGHTS || $expiry === WatchedItem::IGNORE_USER_RIGHTS ) {
			// TODO log deprecated use of method signiture
			$checkRights = $expiry;
			$expiry = null;
		}

		$wl = new WatchedItem;
		$wl->mUser = $user;
		$wl->mTitle = $title;
		$wl->mCheckRights = $checkRights;
		$wl->expiry = $expiry;

		return $wl;
	}

	/**
	 * @return string MW_TS format
	 */
	public function getExpiry() {
		return $this->expiry;
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
		$row = $dbr->selectRow(
			'watchlist',
			array( 'wl_notificationtimestamp', 'wl_expirytimestamp' ),
			$this->dbCond(),
			__METHOD__
		);

		if ( $row === false ) {
			$this->watched = false;
		} else {
			$this->watched = true;
			$this->timestamp = $row->wl_notificationtimestamp;
			$this->expiry = $row->wl_expirytimestamp;
		}
	}

	/**
	 * Check permissions
	 * @param string $what 'viewmywatchlist' or 'editmywatchlist'
	 * @return bool
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

		return $this->watched && wfTimestamp( TS_MW, $this->expiry ) > wfTimestamp( TS_MW );
	}

	/**
	 * Get the notification timestamp of this entry.
	 *
	 * @return bool|null|string False if the page is not watched, the value of
	 *   the wl_notificationtimestamp field otherwise
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
	 * @param bool $force Whether to force the write query to be executed even if the
	 *    page is not watched or the notification timestamp is already NULL.
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 * @mode int $mode WatchedItem::DEFERRED/IMMEDIATE
	 */
	public function resetNotificationTimestamp(
		$force = '', $oldid = 0, $mode = self::IMMEDIATE
	) {
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
				$notificationTimestamp = Revision::getTimestampFromId( $title, $oldid );
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

		// If the page is watched by the user (or may be watched), update the timestamp
		if ( $mode === self::DEFERRED ) {
			$job = new ActivityUpdateJob(
				$title,
				array(
					'type'      => 'updateWatchlistNotification',
					'userid'    => $this->getUserId(),
					'notifTime' => $notificationTimestamp,
					'curTime'   => time()
				)
			);
			// Try to run this post-send
			DeferredUpdates::addCallableUpdate( function() use ( $job ) {
				$job->run();
			} );
		} else {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update( 'watchlist',
				array( 'wl_notificationtimestamp' => $notificationTimestamp ),
				$this->dbCond(),
				__METHOD__
			);
		}

		$this->timestamp = null;
	}

	/**
	 * @param WatchedItem[] $items
	 * @return bool
	 */
	public static function batchAddWatch( array $items ) {

		if ( wfReadOnly() ) {
			return false;
		}

		$rows = array();
		foreach ( $items as $item ) {
			// Only loggedin user can have a watchlist
			if ( $item->mUser->isAnon() || !$item->isAllowed( 'editmywatchlist' ) ) {
				continue;
			}
			$rows[] = array(
				'wl_user' => $item->getUserId(),
				'wl_namespace' => MWNamespace::getSubject( $item->getTitleNs() ),
				'wl_title' => $item->getTitleDBkey(),
				'wl_notificationtimestamp' => null,
				'wl_expirytimestamp' => $item->getExpiry(),
			);
			// Every single watched page needs now to be listed in watchlist;
			// namespace:page and namespace_talk:page need separate entries:
			$rows[] = array(
				'wl_user' => $item->getUserId(),
				'wl_namespace' => MWNamespace::getTalk( $item->getTitleNs() ),
				'wl_title' => $item->getTitleDBkey(),
				'wl_notificationtimestamp' => null,
				'wl_expirytimestamp' => $item->getExpiry(),
			);
			$item->watched = true;
		}

		if ( !$rows ) {
			return false;
		}

		$dbw = wfGetDB( DB_MASTER );
		foreach ( array_chunk( $rows, 100 ) as $toInsert ) {
			$dbw->upsert(
				'watchlist',
				$toInsert,
				array( 'wl_user', 'wl_namespace', 'wl_title' ),
				array( 'wl_expirytimestamp = VALUES(wl_expirytimestamp)' ),
				__METHOD__
			);
		}

		return true;
	}

	/**
	 * Given a title and user (assumes the object is setup), add the watch to the database.
	 * @return bool
	 */
	public function addWatch() {
		return self::batchAddWatch( array( $this ) );
	}

	/**
	 * Same as addWatch, only the opposite.
	 * @return bool
	 */
	public function removeWatch() {

		// Only loggedin user can have a watchlist
		if ( wfReadOnly() || $this->mUser->isAnon() || !$this->isAllowed( 'editmywatchlist' ) ) {
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

		return $success;
	}

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add watches on a new title. To be used for page renames and such.
	 *
	 * @param Title $ot Page title to duplicate entries from, if present
	 * @param Title $nt Page title to add watches on
	 */
	public static function duplicateEntries( $ot, $nt ) {
		WatchedItem::doDuplicateEntries( $ot->getSubjectPage(), $nt->getSubjectPage() );
		WatchedItem::doDuplicateEntries( $ot->getTalkPage(), $nt->getTalkPage() );
	}

	/**
	 * Handle duplicate entries. Backend for duplicateEntries().
	 *
	 * @param Title $ot
	 * @param Title $nt
	 *
	 * @return bool
	 */
	private static function doDuplicateEntries( $ot, $nt ) {
		$oldnamespace = $ot->getNamespace();
		$newnamespace = $nt->getNamespace();
		$oldtitle = $ot->getDBkey();
		$newtitle = $nt->getDBkey();

		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select( 'watchlist',
			array( 'wl_user', 'wl_notificationtimestamp', 'wl_expirytimestamp' ),
			array( 'wl_namespace' => $oldnamespace, 'wl_title' => $oldtitle ),
			__METHOD__, 'FOR UPDATE'
		);
		# Construct array to replace into the watchlist
		$values = array();
		foreach ( $res as $s ) {
			$values[] = array(
				'wl_user' => $s->wl_user,
				'wl_namespace' => $newnamespace,
				'wl_title' => $newtitle,
				'wl_notificationtimestamp' => $s->wl_notificationtimestamp,
				'wl_expirytimestamp' => $s->wl_expirytimestamp,
			);
		}

		if ( empty( $values ) ) {
			// Nothing to do
			return true;
		}

		# Perform replace
		# Note that multi-row replace is very efficient for MySQL but may be inefficient for
		# some other DBMSes, mostly due to poor simulation by us
		$dbw->replace(
			'watchlist',
			array( array( 'wl_user', 'wl_namespace', 'wl_title' ) ),
			$values,
			__METHOD__
		);

		return true;
	}

}
