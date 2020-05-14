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

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MWTimestamp;
use ReadOnlyMode;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Manages user talk page notifications
 * @since 1.35
 */
class TalkPageNotificationManager {

	public const CONSTRUCTOR_OPTIONS = [
		'DisableAnonTalk'
	];

	/** @var array */
	private $userMessagesCache = [];

	/** @var bool */
	private $disableAnonTalk;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ReadOnlyMode */
	private $readOnlyMode;

	/** @var RevisionLookup */
	private $revisionLookup;

	/**
	 * @param ServiceOptions $serviceOptions
	 * @param ILoadBalancer $loadBalancer
	 * @param ReadOnlyMode $readOnlyMode
	 * @param RevisionLookup $revisionLookup
	 */
	public function __construct(
		ServiceOptions $serviceOptions,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode,
		RevisionLookup $revisionLookup
	) {
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->disableAnonTalk = $serviceOptions->get( 'DisableAnonTalk' );
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
		$this->revisionLookup = $revisionLookup;
	}

	/**
	 * Check if the user has new messages.
	 * @param UserIdentity $user
	 * @return bool whether the user has new messages
	 */
	public function userHasNewMessages( UserIdentity $user ) : bool {
		$userKey = $this->getCacheKey( $user );

		// Load the newtalk status if it is unloaded
		if ( !isset( $this->userMessagesCache[$userKey] ) ) {
			if ( $this->isTalkDisabled( $user ) ) {
				// Anon disabled by configuration.
				$this->userMessagesCache[$userKey] = false;
			} else {
				$this->userMessagesCache[$userKey] = $this->dbCheckNewUserMessages( $user );
			}
		}

		return (bool)$this->userMessagesCache[$userKey];
	}

	/**
	 * Update the talk page messages status.
	 *
	 * @param UserIdentity $user
	 * @param RevisionRecord|null $curRev New, as yet unseen revision of the user talk page.
	 * 	Null is acceptable in case the revision is not known. This will indicate that new messages
	 * 	exist, but will not affect the latest seen message timestamp
	 */
	public function setUserHasNewMessages(
		UserIdentity $user,
		RevisionRecord $curRev = null
	) : void {
		if ( $this->isTalkDisabled( $user ) ) {
			return;
		}

		$userKey = $this->getCacheKey( $user );
		$this->userMessagesCache[$userKey] = true;
		$this->dbUpdateNewUserMessages( $user, $curRev );
	}

	/**
	 * Remove the new messages status
	 * @param UserIdentity $user
	 */
	public function removeUserHasNewMessages( UserIdentity $user ) : void {
		if ( $this->isTalkDisabled( $user ) ) {
			return;
		}

		$userKey = $this->getCacheKey( $user );
		$this->userMessagesCache[$userKey] = false;

		$this->dbDeleteNewUserMessages( $user );
	}

	/**
	 * Returns the timestamp of the latest revision of the user talkpage
	 * that the user has already seen in TS_MW format.
	 * If the user has no new messages, returns null
	 *
	 * @param UserIdentity $user
	 * @return string|null
	 */
	public function getLatestSeenMessageTimestamp( UserIdentity $user ) : ?string {
		$userKey = $this->getCacheKey( $user );
		// Don't use self::userHasNewMessages here to avoid an extra DB query
		// in case the value is not cached already
		if ( $this->isTalkDisabled( $user ) ||
			isset( $this->userMessagesCache[$userKey] ) && !$this->userMessagesCache[$userKey]
		) {
			return null;
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		list( $field, $id ) = $this->getQueryFieldAndId( $user );
		// Get the "last viewed rev" timestamp from the oldest message notification
		$timestamp = $dbr->selectField(
			'user_newtalk',
			'MIN(user_last_timestamp)',
			[ $field => $id ],
			__METHOD__
		);
		if ( $timestamp ) {
			// TODO: Once passing 'null' as Revision setUserHasNewMessages is removed,
			// null $timestamp would mean no new messages, so negatives can be cached too.
			$this->userMessagesCache[$userKey] = true;
		}
		return $timestamp !== null ? MWTimestamp::convert( TS_MW, $timestamp ) : null;
	}

	/**
	 * Remove the cached newtalk status for the given user
	 * @internal There should be no need to call this other than from User::clearInstanceCache
	 * @param UserIdentity $user
	 */
	public function clearInstanceCache( UserIdentity $user ) : void {
		$userKey = $this->getCacheKey( $user );
		$this->userMessagesCache[$userKey] = null;
	}

	/**
	 * Check whether the talk page is disabled for a user
	 * @param UserIdentity $user
	 * @return bool
	 */
	private function isTalkDisabled( UserIdentity $user ) : bool {
		return !$user->isRegistered() && $this->disableAnonTalk;
	}

	/**
	 * Internal uncached check for new messages
	 * @param UserIdentity $user
	 * @return bool True if the user has new messages
	 */
	private function dbCheckNewUserMessages( UserIdentity $user ) : bool {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		list( $field, $id ) = $this->getQueryFieldAndId( $user );
		$ok = $dbr->selectField(
			'user_newtalk',
			$field,
			[ $field => $id ],
			__METHOD__
		);
		return (bool)$ok;
	}

	/**
	 * Add or update the new messages flag
	 * @param UserIdentity $user
	 * @param RevisionRecord|null $curRev New, as yet unseen revision of the
	 *   user talk page. Ignored if null.
	 * @return bool True if successful, false otherwise
	 */
	private function dbUpdateNewUserMessages(
		UserIdentity $user,
		RevisionRecord $curRev = null
	) : bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		if ( $curRev ) {
			$prevRev = $this->revisionLookup->getPreviousRevision( $curRev );
			$ts = $prevRev ? $prevRev->getTimestamp() : null;
		} else {
			$ts = null;
		}

		// Mark the user as having new messages since this revision
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		list( $field, $id ) = $this->getQueryFieldAndId( $user );
		$dbw->insert(
			'user_newtalk',
			[
				$field => $id,
				'user_last_timestamp' => $dbw->timestampOrNull( $ts )
			],
			__METHOD__,
			[ 'IGNORE' ]
		);
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Clear the new messages flag for the given user
	 * @param UserIdentity $user
	 * @return bool True if successful, false otherwise
	 */
	private function dbDeleteNewUserMessages( UserIdentity $user ) : bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}
		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		list( $field, $id ) = $this->getQueryFieldAndId( $user );
		$dbw->delete(
			'user_newtalk',
			[ $field => $id ],
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Get the field name and id for the user_newtalk table query
	 * @param UserIdentity $user
	 * @return array ( string $field, string|int $id )
	 */
	private function getQueryFieldAndId( UserIdentity $user ) : array {
		if ( $user->isRegistered() ) {
			$field = 'user_id';
			$id = $user->getId();
		} else {
			$field = 'user_ip';
			$id = $user->getName();
		}
		return [ $field, $id ];
	}

	/**
	 * Gets a unique key for various caches.
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getCacheKey( UserIdentity $user ) : string {
		return $user->isRegistered() ? "u:{$user->getId()}" : "anon:{$user->getName()}";
	}
}
