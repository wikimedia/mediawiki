<?php

/**
 * Storage layer class for WatchedItems.
 * Database interaction
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
	 * @var HashBagOStuff
	 */
	private $cache;

	public function __construct( LoadBalancer $loadBalancer, HashBagOStuff $cache ) {
		$this->loadBalancer = $loadBalancer;
		$this->cache = $cache;
	}

	/**
	 * @return self
	 */
	public static function getDefaultInstance() {
		static $instance;
		if ( !$instance ) {
			$instance = new self(
				wfGetLB(),
				new HashBagOStuff( array( 'maxKeys' => 100 ) )
			);
		}
		return $instance;
	}

	private function getCacheKey( User $user, LinkTarget $target ) {
		return $target->getNamespace() . ':' . $target->getDBkey() . ':' . $user->getId();
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
		return array(
			'wl_user' => $user->getId(),
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey(),
		);
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

		$dbr = $this->loadBalancer->getConnection( DB_SLAVE, array( 'watchlist' ) );
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
		$this->addWatchBatch( array( array( $user, $target ) ) );
	}

	/**
	 * @param array[] $userTargetCombinations array of arrays containing [0] => User [1] => LinkTarget
	 *
	 * @return bool success
	 */
	public function addWatchBatch( array $userTargetCombinations ) {
		if ( wfReadOnly() ) {
			return false;
		}

		$rows = array();
		foreach ( $userTargetCombinations as list( $user, $target ) ) {
			/**
			 * @var User $user
			 * @var LinkTarget $target
			 */

			// Only loggedin user can have a watchlist
			if ( $user->isAnon() ) {
				continue;
			}
			$rows[] = array(
				'wl_user' => $user->getId(),
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
				'wl_notificationtimestamp' => null,
			);
			$this->uncache( $user, $target );
		}

		if ( !$rows ) {
			return false;
		}

		$dbw = $this->loadBalancer->getConnection( DB_MASTER, array( 'watchlist' ) );
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
		if ( wfReadOnly() || $user->isAnon() ) {
			return false;
		}

		$this->uncache( $user, $target );

		$dbw = $this->loadBalancer->getConnection( DB_MASTER, array( 'watchlist' ) );
		$dbw->delete( 'watchlist',
			array(
				'wl_user' => $user->getId(),
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
			), __METHOD__
		);
		$success = (bool)$dbw->affectedRows();
		$this->loadBalancer->reuseConnection( $dbw );

		return $success;
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
		if ( wfReadOnly() || $user->isAnon() ) {
			return false;
		}

		if ( $force != 'force' ) {
			$item = $this->loadWatchedItem( $user, $title );
			if ( !$item || $item->getNotificationTimestamp() === null ) {
				return false;
			}
		}

		if ( !$oldid ) {
			// No oldid given, assuming latest revision; clear the timestamp.
			$notificationTimestamp = null;
		} elseif ( !$title->getNextRevisionID( $oldid ) ) {
			// Oldid given and is the latest revision for this title; clear the timestamp.
			$notificationTimestamp = null;
		} else {
			if ( !isset( $item ) ) {
				$item = $this->loadWatchedItem( $user, $title );
			}

			if ( !$item ) {
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

				if ( $notificationTimestamp < $item->getNotificationTimestamp() ) {
					if ( $force != 'force' ) {
						return false;
					} else {
						// This is a little silly…
						$notificationTimestamp = $item->getNotificationTimestamp();
					}
				}
			}
		}

		// If the page is watched by the user (or may be watched), update the timestamp
		$job = new ActivityUpdateJob(
			$title,
			array(
				'type'      => 'updateWatchlistNotification',
				'userid'    => $user->getId(),
				'notifTime' => $notificationTimestamp,
				'curTime'   => time()
			)
		);
		// Try to run this post-send
		DeferredUpdates::addCallableUpdate( function() use ( $job ) {
			$job->run();
		} );

		$this->uncache( $user, $title );

		return true;
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
