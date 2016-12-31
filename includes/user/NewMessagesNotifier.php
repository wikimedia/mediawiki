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

/**
 * Handles the "You have new messages" bar/orange bar of doom/OBOD that is shown
 * when a user has new, unread messages on their talk page. Encapsulates the
 * user_newtalk table
 *
 * @since 1.29
 */
class NewMessagesNotifier {
	/** @var User The user or IP whose notifications we are dealing with */
	protected $user;

	/** @var bool|null Whether the user has new messages, or null if we don't know */
	protected $hasNewMessages;

	/**
	 * @param User $user The user or IP whose notifications we are dealing with
	 */
	function __construct( User $user ) {
		$this->user = $user;
		$this->hasNewMessages = null;
	}

	/**
	 * Check if the user has new messages.
	 * @return bool True if the user has new messages
	 */
	public function hasNewMessages() {
		// Load the newtalk status if it is unloaded (newtalk === null)
		if ( $this->hasNewMessages === null ) {
			// reset talk page status
			$this->hasNewMessages = false;

			// Check memcached separately for anons, who have no
			// entire User object stored in there.
			if ( !$this->user->getId() ) {
				global $wgDisableAnonTalk;
				if ( $wgDisableAnonTalk ) {
					// Anon newtalk disabled by configuration.
					$this->hasNewMessages = false;
				} else {
					$this->hasNewMessages = $this->checkNewtalk( 'user_ip', $this->user->getName() );
				}
			} else {
				$this->hasNewMessages = $this->checkNewtalk( 'user_id', $this->user->getId() );
			}
		}

		return (bool)$this->hasNewMessages;
	}

	/**
	 * Update the 'You have new messages!' status.
	 *
	 * @param bool $val Whether the user has new messages
	 * @param Revision|null $curRev New, as yet unseen revision of the user talk
	 *   page. Ignored if null or if $val is false.
	 */
	public function setNewMessages( $val, Revision $curRev = null ) {
		if ( wfReadOnly() ) {
			return;
		}

		$this->hasNewMessages = $val;

		if ( $this->isAnon() ) {
			$field = 'user_ip';
			$id = $this->user->getName();
		} else {
			$field = 'user_id';
			$id = $this->user->getId();
		}

		if ( $val ) {
			$changed = $this->updateNewtalk( $field, $id, $curRev );
		} else {
			$changed = $this->deleteNewtalk( $field, $id );
		}

		if ( $changed ) {
			$this->user->invalidateCache();
		}
	}

	/**
	 * Return the data needed to construct links for new talk page message
	 * alerts. If there are new messages, this will return an array whose only
	 * element is an associative array with the following data:
	 *     wiki: The database name of the wiki
	 *     link: Root-relative link to the user's talk page
	 *     rev: The last talk page revision that the user has seen or null. This
	 *         is useful for building diff links.
	 * If there are no new messages, it returns an empty array.
	 *
	 * @note This function was designed to accomodate multiple talk pages, but
	 * currently only returns a single link and revision.
	 *
	 * @return array
	 */
	public function getNewMessageLinks() {
		$talks = [];
		if ( !Hooks::run( 'UserRetrieveNewTalks', [ &$this->user, &$talks ] ) ) {
			return $talks;
		} elseif ( !$this->hasNewMessages() ) {
			return [];
		}

		$utp = $this->user->getTalkPage();
		$dbr = wfGetDB( DB_REPLICA );

		// Get the "last viewed rev" timestamp from the oldest message notification
		$timestamp = $dbr->selectField( 'user_newtalk',
			'MIN(user_last_timestamp)',
			$this->isAnon() ?
				[ 'user_ip' => $this->user->getName() ] :
				[ 'user_id' => $this->user->getId() ],
			__METHOD__ );
		$rev = $timestamp ? Revision::loadFromTimestamp( $dbr, $utp, $timestamp ) : null;
		return [ [ 'wiki' => wfWikiID(), 'link' => $utp->getLocalURL(), 'rev' => $rev ] ];
	}

	/**
	 * Get the revision ID for the last talk page revision viewed by the talk
	 * page owner.
	 *
	 * @return int|null Revision ID or null
	 */
	public function getNewMessageRevisionId() {
		$newMessageRevisionId = null;
		$newMessageLinks = $this->getNewMessageLinks();

		if ( $newMessageLinks ) {
			// Note: getNewMessageLinks() never returns more than a single link
			// and it is always for the same wiki, but we double-check here in
			// case that changes some time in the future.
			if ( count( $newMessageLinks ) === 1
				&& $newMessageLinks[0]['wiki'] === wfWikiID()
				&& $newMessageLinks[0]['rev']
			) {
				/** @var Revision $newMessageRevision */
				$newMessageRevision = $newMessageLinks[0]['rev'];
				$newMessageRevisionId = $newMessageRevision->getId();
			}
		}
		return $newMessageRevisionId;
	}

	/**
	 * Internal uncached check for new messages
	 *
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, user ID otherwise
	 * @return bool True if the user has new messages
	 */
	protected function checkNewtalk( $field, $id ) {
		$dbr = wfGetDB( DB_REPLICA );
		$ok = $dbr->selectField( 'user_newtalk', $field, [ $field => $id ], __METHOD__ );
		return $ok !== false;
	}

	/**
	 * Add or update the new messages flag
	 *
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, user ID otherwise
	 * @param Revision|null $curRev New, as yet unseen revision of the user talk page.
	 *   Ignored if null.
	 * @return bool True if successful, false otherwise
	 */
	protected function updateNewtalk( $field, $id, Revision $curRev = null ) {
		// Get timestamp of the talk page revision prior to the current one
		$prevRev = $curRev ? $curRev->getPrevious() : false;
		$ts = $prevRev ? $prevRev->getTimestamp() : null;

		// Mark the user as having new messages since this revision
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'user_newtalk',
			[ $field => $id, 'user_last_timestamp' => $dbw->timestampOrNull( $ts ) ],
			__METHOD__,
			'IGNORE' );

		if ( $dbw->affectedRows() ) {
			wfDebug( __METHOD__ . ": set on ($field, $id)\n" );
			return true;
		} else {
			wfDebug( __METHOD__ . " already set ($field, $id)\n" );
			return false;
		}
	}

	/**
	 * Clear the new messages flag for the given user
	 *
	 * @param string $field 'user_ip' for anonymous users, 'user_id' otherwise
	 * @param string|int $id User's IP address for anonymous users, user ID otherwise
	 * @return bool True if successful, false otherwise
	 */
	protected function deleteNewtalk( $field, $id ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'user_newtalk',
			[ $field => $id ],
			__METHOD__ );

		if ( $dbw->affectedRows() ) {
			wfDebug( __METHOD__ . ": killed on ($field, $id)\n" );
			return true;
		} else {
			wfDebug( __METHOD__ . ": already gone ($field, $id)\n" );
			return false;
		}
	}
}
