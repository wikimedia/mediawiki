<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Manages user talk page notifications
 *
 * @since 1.35
 * @ingroup User
 */
class TalkPageNotificationManager {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::DisableAnonTalk
	];

	private array $userMessagesCache = [];
	private bool $disableAnonTalk;
	private IConnectionProvider $dbProvider;
	private ReadOnlyMode $readOnlyMode;
	private RevisionLookup $revisionLookup;
	private HookRunner $hookRunner;
	private UserFactory $userFactory;

	/**
	 * @param ServiceOptions $serviceOptions
	 * @param IConnectionProvider $dbProvider
	 * @param ReadOnlyMode $readOnlyMode
	 * @param RevisionLookup $revisionLookup
	 * @param HookContainer $hookContainer
	 * @param UserFactory $userFactory
	 */
	public function __construct(
		ServiceOptions $serviceOptions,
		IConnectionProvider $dbProvider,
		ReadOnlyMode $readOnlyMode,
		RevisionLookup $revisionLookup,
		HookContainer $hookContainer,
		UserFactory $userFactory
	) {
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->disableAnonTalk = $serviceOptions->get( MainConfigNames::DisableAnonTalk );
		$this->dbProvider = $dbProvider;
		$this->readOnlyMode = $readOnlyMode;
		$this->revisionLookup = $revisionLookup;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userFactory = $userFactory;
	}

	/**
	 * Check if the user has new messages.
	 * @param UserIdentity $user
	 * @return bool whether the user has new messages
	 */
	public function userHasNewMessages( UserIdentity $user ): bool {
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
	 * Clear notifications when the user's own talk page is viewed
	 *
	 * @param UserIdentity $user
	 * @param RevisionRecord|null $oldRev If it is an old revision view, the
	 *   old revision. If it is a current revision view, this should be null.
	 */
	public function clearForPageView(
		UserIdentity $user,
		?RevisionRecord $oldRev = null
	) {
		// Abort if the hook says so. (Echo doesn't abort, it just queues its own update)
		if ( !$this->hookRunner->onUserClearNewTalkNotification(
			$user,
			$oldRev ? $oldRev->getId() : 0
		) ) {
			return;
		}

		if ( $this->isTalkDisabled( $user ) ) {
			return;
		}

		// Nothing to do if there are no messages
		if ( !$this->userHasNewMessages( $user ) ) {
			return;
		}

		// If there is a subsequent revision after the one being viewed, use
		// its timestamp as the new notification timestamp. If there is no
		// subsequent revision, the notification is cleared.
		if ( $oldRev ) {
			$newRev = $this->revisionLookup->getNextRevision( $oldRev );
			if ( $newRev ) {
				DeferredUpdates::addCallableUpdate(
					function () use ( $user, $newRev ) {
						$this->dbDeleteNewUserMessages( $user );
						$this->dbUpdateNewUserMessages( $user, $newRev );
					}
				);
				return;
			}
		}

		// Update the cache now so that the skin doesn't show a notification
		$userKey = $this->getCacheKey( $user );
		$this->userMessagesCache[$userKey] = false;

		// Defer the DB delete
		DeferredUpdates::addCallableUpdate(
			function () use ( $user ) {
				$this->touchUser( $user );
				$this->dbDeleteNewUserMessages( $user );
			}
		);
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
		?RevisionRecord $curRev = null
	): void {
		if ( $this->isTalkDisabled( $user ) ) {
			return;
		}

		$userKey = $this->getCacheKey( $user );
		$this->userMessagesCache[$userKey] = true;
		$this->touchUser( $user );
		$this->dbUpdateNewUserMessages( $user, $curRev );
	}

	/**
	 * Remove the new messages status
	 */
	public function removeUserHasNewMessages( UserIdentity $user ): void {
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
	public function getLatestSeenMessageTimestamp( UserIdentity $user ): ?string {
		$userKey = $this->getCacheKey( $user );
		// Don't use self::userHasNewMessages here to avoid an extra DB query
		// in case the value is not cached already
		if ( $this->isTalkDisabled( $user ) ||
			( isset( $this->userMessagesCache[$userKey] ) && !$this->userMessagesCache[$userKey] )
		) {
			return null;
		}

		[ $field, $id ] = $this->getQueryFieldAndId( $user );
		// Get the "last viewed rev" timestamp from the oldest message notification
		$timestamp = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'MIN(user_last_timestamp)' )
			->from( 'user_newtalk' )
			->where( [ $field => $id ] )
			->caller( __METHOD__ )->fetchField();
		if ( $timestamp ) {
			// TODO: Now that User::setNewTalk() was removed, it should be possible to
			// cache *not* having a new message as well (if $timestamp is null).
			$this->userMessagesCache[$userKey] = true;
		}
		return $timestamp !== null ? MWTimestamp::convert( TS_MW, $timestamp ) : null;
	}

	/**
	 * Remove the cached newtalk status for the given user
	 * @internal There should be no need to call this other than from User::clearInstanceCache
	 * @param UserIdentity $user
	 */
	public function clearInstanceCache( UserIdentity $user ): void {
		$userKey = $this->getCacheKey( $user );
		$this->userMessagesCache[$userKey] = null;
	}

	/**
	 * Check whether the talk page is disabled for a user
	 * @param UserIdentity $user
	 * @return bool
	 */
	private function isTalkDisabled( UserIdentity $user ): bool {
		return !$user->isRegistered() && $this->disableAnonTalk;
	}

	/**
	 * Internal uncached check for new messages
	 * @param UserIdentity $user
	 * @return bool True if the user has new messages
	 */
	private function dbCheckNewUserMessages( UserIdentity $user ): bool {
		[ $field, $id ] = $this->getQueryFieldAndId( $user );
		$ok = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( $field )
			->from( 'user_newtalk' )
			->where( [ $field => $id ] )
			->caller( __METHOD__ )->fetchField();
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
		?RevisionRecord $curRev = null
	): bool {
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
		$dbw = $this->dbProvider->getPrimaryDatabase();
		[ $field, $id ] = $this->getQueryFieldAndId( $user );
		$dbw->newInsertQueryBuilder()
			->insertInto( 'user_newtalk' )
			->ignore()
			->row( [ $field => $id, 'user_last_timestamp' => $dbw->timestampOrNull( $ts ) ] )
			->caller( __METHOD__ )->execute();
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Clear the new messages flag for the given user
	 * @param UserIdentity $user
	 * @return bool True if successful, false otherwise
	 */
	private function dbDeleteNewUserMessages( UserIdentity $user ): bool {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}
		$dbw = $this->dbProvider->getPrimaryDatabase();
		[ $field, $id ] = $this->getQueryFieldAndId( $user );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'user_newtalk' )
			->where( [ $field => $id ] )
			->caller( __METHOD__ )->execute();
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Get the field name and id for the user_newtalk table query
	 * @param UserIdentity $user
	 * @return array ( string $field, string|int $id )
	 */
	private function getQueryFieldAndId( UserIdentity $user ): array {
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
	private function getCacheKey( UserIdentity $user ): string {
		return $user->isRegistered() ? "u:{$user->getId()}" : "anon:{$user->getName()}";
	}

	/**
	 * Update the user touched timestamp
	 */
	private function touchUser( UserIdentity $user ) {
		// Ideally this would not be in User, it would be in its own service
		// or something
		$this->userFactory->newFromUserIdentity( $user )->touch();
	}
}
