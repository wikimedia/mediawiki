<?php

use Wikimedia\Assert\Assert;

/**
 * Storage layer class for WatchedItems.
 * Database interaction.
 *
 * @author Addshore
 *
 * @since 1.27
 */
class WatchedItemStore {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var BagOStuff
	 */
	private $cache;

	/**
	 * @var callable|null
	 */
	private $deferredUpdatesAddCallableUpdateCallback;

	/**
	 * @var callable|null
	 */
	private $revisionGetTimestampFromIdCallback;

	/**
	 * @var self|null
	 */
	private static $instance;

	public function __construct( LoadBalancer $loadBalancer, BagOStuff $cache ) {
		$this->loadBalancer = $loadBalancer;
		$this->cache = $cache;
		$this->deferredUpdatesAddCallableUpdateCallback = [ 'DeferredUpdates', 'addCallableUpdate' ];
		$this->revisionGetTimestampFromIdCallback = [ 'Revision', 'getTimestampFromId' ];
	}

	/**
	 * Overrides the DeferredUpdates::addCallableUpdate callback
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param callable $callback
	 * @see DeferredUpdates::addCallableUpdate for callback signiture
	 *
	 * @throws MWException
	 */
	public function overrideDeferredUpdatesAddCallableUpdateCallback( $callback ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException(
				'Cannot override DeferredUpdates::addCallableUpdate callback in operation.'
			);
		}
		Assert::parameterType( 'callable', $callback, '$callback' );
		$this->deferredUpdatesAddCallableUpdateCallback = $callback;
	}

	/**
	 * Overrides the Revision::getTimestampFromId callback
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param callable $callback
	 * @see Revision::getTimestampFromId for callback signiture
	 *
	 * @throws MWException
	 */
	public function overrideRevisionGetTimestampFromIdCallback( $callback ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException(
				'Cannot override Revision::getTimestampFromId callback in operation.'
			);
		}
		Assert::parameterType( 'callable', $callback, '$callback' );
		$this->revisionGetTimestampFromIdCallback = $callback;
	}

	/**
	 * Overrides the default instance of this class
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param WatchedItemStore $store
	 *
	 * @throws MWException
	 */
	public static function overrideDefaultInstance( WatchedItemStore $store ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException(
				'Cannot override ' . __CLASS__ . 'default instance in operation.'
			);
		}
		self::$instance = $store;
	}

	/**
	 * @return self
	 */
	public static function getDefaultInstance() {
		if ( !self::$instance ) {
			self::$instance = new self(
				wfGetLB(),
				new HashBagOStuff( [ 'maxKeys' => 100 ] )
			);
		}
		return self::$instance;
	}

	private function getCacheKey( User $user, LinkTarget $target ) {
		return $this->cache->makeKey(
			(string)$target->getNamespace(),
			$target->getDBkey(),
			(string)$user->getId()
		);
	}

	private function cache( WatchedItem $item ) {
		$this->cache->set(
			$this->getCacheKey( $item->getUser(), $item->getLinkTarget() ),
			$item
		);
	}

	private function uncache( User $user, LinkTarget $target ) {
		$this->cache->delete( $this->getCacheKey( $user, $target ) );
	}

	/**
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return WatchedItem|null
	 */
	private function getCached( User $user, LinkTarget $target ) {
		return $this->cache->get( $this->getCacheKey( $user, $target ) );
	}

	/**
	 * Return an array of conditions to select or update the appropriate database
	 * row.
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return array
	 */
	private function dbCond( User $user, LinkTarget $target ) {
		return [
			'wl_user' => $user->getId(),
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey(),
		];
	}

	/**
	 * Get an item (may be cached)
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return WatchedItem|false
	 */
	public function getWatchedItem( User $user, LinkTarget $target ) {
		$cached = $this->getCached( $user, $target );
		if ( $cached ) {
			return $cached;
		}
		return $this->loadWatchedItem( $user, $target );
	}

	/**
	 * Loads an item from the db
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return WatchedItem|false
	 */
	public function loadWatchedItem( User $user, LinkTarget $target ) {
		// Only loggedin user can have a watchlist
		if ( $user->isAnon() ) {
			return false;
		}

		$dbr = $this->loadBalancer->getConnection( DB_SLAVE, [ 'watchlist' ] );
		$row = $dbr->selectRow(
			'watchlist',
			'wl_notificationtimestamp',
			$this->dbCond( $user, $target ),
			__METHOD__
		);
		$this->loadBalancer->reuseConnection( $dbr );

		if ( !$row ) {
			return false;
		}

		$item = new WatchedItem(
			$user,
			$target,
			$row->wl_notificationtimestamp
		);
		$this->cache( $item );

		return $item;
	}

	/**
	 * Must be called separately for Subject & Talk namespaces
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return bool
	 */
	public function isWatched( User $user, LinkTarget $target ) {
		return (bool)$this->getWatchedItem( $user, $target );
	}

	/**
	 * Must be called separately for Subject & Talk namespaces
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 */
	public function addWatch( User $user, LinkTarget $target ) {
		$this->addWatchBatch( [ [ $user, $target ] ] );
	}

	/**
	 * @param array[] $userTargetCombinations array of arrays containing [0] => User [1] => LinkTarget
	 *
	 * @return bool success
	 */
	public function addWatchBatch( array $userTargetCombinations ) {
		if ( $this->loadBalancer->getReadOnlyReason() !== false ) {
			return false;
		}

		$rows = [];
		foreach ( $userTargetCombinations as list( $user, $target ) ) {
			/**
			 * @var User $user
			 * @var LinkTarget $target
			 */

			// Only loggedin user can have a watchlist
			if ( $user->isAnon() ) {
				continue;
			}
			$rows[] = [
				'wl_user' => $user->getId(),
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
				'wl_notificationtimestamp' => null,
			];
			$this->uncache( $user, $target );
		}

		if ( !$rows ) {
			return false;
		}

		$dbw = $this->loadBalancer->getConnection( DB_MASTER, [ 'watchlist' ] );
		foreach ( array_chunk( $rows, 100 ) as $toInsert ) {
			// Use INSERT IGNORE to avoid overwriting the notification timestamp
			// if there's already an entry for this page
			$dbw->insert( 'watchlist', $toInsert, __METHOD__, 'IGNORE' );
		}
		$this->loadBalancer->reuseConnection( $dbw );

		return true;
	}

	/**
	 * Removes the an entry for the User watching the LinkTarget
	 * Must be called separately for Subject & Talk namespaces
	 *
	 * @param User $user
	 * @param LinkTarget $target
	 *
	 * @return bool success
	 * @throws DBUnexpectedError
	 * @throws MWException
	 */
	public function removeWatch( User $user, LinkTarget $target ) {
		// Only logged in user can have a watchlist
		if ( $this->loadBalancer->getReadOnlyReason() !== false || $user->isAnon() ) {
			return false;
		}

		$this->uncache( $user, $target );

		$dbw = $this->loadBalancer->getConnection( DB_MASTER, [ 'watchlist' ] );
		$dbw->delete( 'watchlist',
			[
				'wl_user' => $user->getId(),
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
			], __METHOD__
		);
		$success = (bool)$dbw->affectedRows();
		$this->loadBalancer->reuseConnection( $dbw );

		return $success;
	}

	/**
	 * @param User $editor The editor that triggered the update. Their notification
	 *  timestamp will not be updated(they have already seen it)
	 * @param LinkTarget $target The target to update timestamps for
	 * @param string $timestamp Set the update timestamp to this value
	 *
	 * @return int[] Array of user IDs the timestamp has been updated for
	 */
	public function updateNotificationTimestamp( User $editor, LinkTarget $target, $timestamp ) {
		$dbw = $this->loadBalancer->getConnection( DB_MASTER, [ 'watchlist' ] );
		$res = $dbw->select( [ 'watchlist' ],
			[ 'wl_user' ],
			[
				'wl_user != ' . intval( $editor->getId() ),
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
				'wl_notificationtimestamp IS NULL',
			], __METHOD__
		);

		$watchers = [];
		foreach ( $res as $row ) {
			$watchers[] = intval( $row->wl_user );
		}

		if ( $watchers ) {
			// Update wl_notificationtimestamp for all watching users except the editor
			$fname = __METHOD__;
			$dbw->onTransactionIdle(
				function () use ( $dbw, $timestamp, $watchers, $target, $fname ) {
					$dbw->update( 'watchlist',
						[ /* SET */
							'wl_notificationtimestamp' => $dbw->timestamp( $timestamp )
						], [ /* WHERE */
							'wl_user' => $watchers,
							'wl_namespace' => $target->getNamespace(),
							'wl_title' => $target->getDBkey(),
						], $fname
					);
				}
			);
		}

		$this->loadBalancer->reuseConnection( $dbw );

		return $watchers;
	}

	/**
	 * Reset the notification timestamp of this entry
	 *
	 * @param User $user
	 * @param Title $title
	 * @param string $force Whether to force the write query to be executed even if the
	 *    page is not watched or the notification timestamp is already NULL.
	 *    'force' in order to force
	 * @param int $oldid The revision id being viewed. If not given or 0, latest revision is assumed.
	 *
	 * @return bool success
	 */
	public function resetNotificationTimestamp( User $user, Title $title, $force = '', $oldid = 0 ) {
		// Only loggedin user can have a watchlist
		if ( $this->loadBalancer->getReadOnlyReason() !== false || $user->isAnon() ) {
			return false;
		}

		$item = null;
		if ( $force != 'force' ) {
			$item = $this->loadWatchedItem( $user, $title );
			if ( !$item || $item->getNotificationTimestamp() === null ) {
				return false;
			}
		}

		// If the page is watched by the user (or may be watched), update the timestamp
		$job = new ActivityUpdateJob(
			$title,
			[
				'type'      => 'updateWatchlistNotification',
				'userid'    => $user->getId(),
				'notifTime' => $this->getNotificationTimestamp( $user, $title, $item, $force, $oldid ),
				'curTime'   => time()
			]
		);

		// Try to run this post-send
		// Calls DeferredUpdates::addCallableUpdate in normal operation
		call_user_func(
			$this->deferredUpdatesAddCallableUpdateCallback,
			function() use ( $job ) {
				$job->run();
			}
		);

		$this->uncache( $user, $title );

		return true;
	}

	private function getNotificationTimestamp( User $user, Title $title, $item, $force, $oldid ) {
		if ( !$oldid ) {
			// No oldid given, assuming latest revision; clear the timestamp.
			return null;
		}

		if ( !$title->getNextRevisionID( $oldid ) ) {
			// Oldid given and is the latest revision for this title; clear the timestamp.
			return null;
		}

		if ( $item === null ) {
			$item = $this->loadWatchedItem( $user, $title );
		}

		if ( !$item ) {
			// This can only happen if $force is enabled.
			return null;
		}

		// Oldid given and isn't the latest; update the timestamp.
		// This will result in no further notification emails being sent!
		// Calls Revision::getTimestampFromId in normal operation
		$notificationTimestamp = call_user_func(
			$this->revisionGetTimestampFromIdCallback,
			$title,
			$oldid
		);

		// We need to go one second to the future because of various strict comparisons
		// throughout the codebase
		$ts = new MWTimestamp( $notificationTimestamp );
		$ts->timestamp->add( new DateInterval( 'PT1S' ) );
		$notificationTimestamp = $ts->getTimestamp( TS_MW );

		if ( $notificationTimestamp < $item->getNotificationTimestamp() ) {
			if ( $force != 'force' ) {
				return false;
			} else {
				// This is a little silly…
				return $item->getNotificationTimestamp();
			}
		}

		return $notificationTimestamp;
	}

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add a watch for the new title.
	 *
	 * To be used for page renames and such.
	 *
	 * @param LinkTarget $oldTarget
	 * @param LinkTarget $newTarget
	 */
	public function duplicateAllAssociatedEntries( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		if ( !$oldTarget instanceof Title ) {
			$oldTarget = Title::newFromLinkTarget( $oldTarget );
		}
		if ( !$newTarget instanceof Title ) {
			$newTarget = Title::newFromLinkTarget( $newTarget );
		}

		$this->duplicateEntry( $oldTarget->getSubjectPage(), $newTarget->getSubjectPage() );
		$this->duplicateEntry( $oldTarget->getTalkPage(), $newTarget->getTalkPage() );
	}

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add a watch for the new title.
	 *
	 * To be used for page renames and such.
	 * This must be called separately for Subject and Talk pages
	 *
	 * @param LinkTarget $oldTarget
	 * @param LinkTarget $newTarget
	 */
	public function duplicateEntry( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		$dbw = $this->loadBalancer->getConnection( DB_MASTER, [ 'watchlist' ] );

		$result = $dbw->select(
			'watchlist',
			[ 'wl_user', 'wl_notificationtimestamp' ],
			[
				'wl_namespace' => $oldTarget->getNamespace(),
				'wl_title' => $oldTarget->getDBkey(),
			],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);

		$newNamespace = $newTarget->getNamespace();
		$newDBkey = $newTarget->getDBkey();

		# Construct array to replace into the watchlist
		$values = [];
		foreach ( $result as $row ) {
			$values[] = [
				'wl_user' => $row->wl_user,
				'wl_namespace' => $newNamespace,
				'wl_title' => $newDBkey,
				'wl_notificationtimestamp' => $row->wl_notificationtimestamp,
			];
		}

		if ( !empty( $values ) ) {
			# Perform replace
			# Note that multi-row replace is very efficient for MySQL but may be inefficient for
			# some other DBMSes, mostly due to poor simulation by us
			$dbw->replace(
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				$values,
				__METHOD__
			);
		}

		$this->loadBalancer->reuseConnection( $dbw );
	}

}
