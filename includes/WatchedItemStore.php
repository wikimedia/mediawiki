<?php

/**
 * @author Addshore
 *
 * @since 1.27
 */
class WatchedItemStore {

	/**
	 * @var int Maximum items in $this->items
	 */
	const MAX_WATCHED_ITEMS_CACHE = 100;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var WatchedItem[]
	 */
	private $cachedItems = array();

	public function __construct( LoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @return self
	 */
	public static function getDefaultInstance() {
		static $instance;
		if ( !$instance ) {
			$instance = new self( wfGetLB() );
		}
		return $instance;
	}

	private function getCacheKey( User $user, Title $title ) {
		return $title->getNamespace() . ':' . $title->getDBkey() . ':' . $user->getId();
	}

	private function cache( WatchedItem $item ) {
		if ( count( $this->cachedItems ) >= self::MAX_WATCHED_ITEMS_CACHE ) {
			$this->cachedItems = array();
		}
		$this->cachedItems[$this->getCacheKey( $item->getUser(), $item->getTitle() )] = $item;
	}

	private function uncache( User $user, Title $title ) {
		unset( $this->cachedItems[$this->getCacheKey( $user, $title )] );
	}

	/**
	 * @param User $user
	 * @param Title $title
	 *
	 * @return WatchedItem|null
	 */
	private function getCached( User $user, Title $title ) {
		$key = $this->getCacheKey( $user, $title );
		if ( array_key_exists( $key, $this->cachedItems ) ) {
			return $this->cachedItems[$key];
		}
		return null;
	}

	/**
	 * Return an array of conditions to select or update the appropriate database
	 * row.
	 *
	 * @param User $user
	 * @param Title $title
	 *
	 * @return array
	 */
	private function dbCond( User $user, Title $title ) {
		return array(
			'wl_user' => $user->getId(),
			'wl_namespace' => $title->getNamespace(),
			'wl_title' => $title->getDBkey(),
		);
	}

	/**
	 * Get an item (may be cached)
	 *
	 * @param User $user
	 * @param Title $title
	 *
	 * @return WatchedItem|false
	 */
	public function getWatchedItem( User $user, Title $title ) {
		$cached = $this->getCached( $user, $title );
		if ( $cached ) {
			return $cached;
		}
		return $this->loadWatchedItem( $user, $title );
	}

	/**
	 * Loads an item from the db
	 *
	 * @param User $user
	 * @param Title $title
	 *
	 * @return WatchedItem|false
	 */
	public function loadWatchedItem( User $user, Title $title ) {
		// Only loggedin user can have a watchlist & some pages cannot be watched
		if ( $user->isAnon() || !$title->isWatchable() ) {
			return false;
		}

		# Pages and their talk pages are considered equivalent for watching;
		# remember that talk namespaces are numbered as page namespace+1.

		$dbr = $this->loadBalancer->getConnection( DB_SLAVE );
		$row = $dbr->selectRow(
			'watchlist',
			'wl_notificationtimestamp',
			$this->dbCond( $user, $title ),
			__METHOD__
		);

		if ( $row ) {
			$item = new WatchedItem(
				$user,
				$title,
				$row->wl_notificationtimestamp
			);
			$this->cache( $item );
			return $item;
		} else {
			return false;
		}
	}

	/**
	 * @param User $user
	 * @param Title $title
	 *
	 * @return bool
	 */
	public function isWatched( User $user, Title $title ) {
		return (bool)$this->getWatchedItem( $user, $title );
	}

	public function addWatch( User $user, Title $title ) {
		$this->addWatchBatch( array( array( $user, $title ) ) );
	}

	/**
	 * @param array[] $userTitleCombinations array of arrays containing [0] => User [1] => Title
	 *
	 * @return bool success
	 */
	public function addWatchBatch( array $userTitleCombinations ) {
		if ( wfReadOnly() ) {
			return false;
		}

		$rows = array();
		foreach ( $userTitleCombinations as $userTitleArray ) {
			/**
			 * @var User $user
			 * @var Title $title
			 * @note once MW depends on 5.5 this can be done in the foreach!
			 */
			list( $user, $title ) = $userTitleArray;

			// Only loggedin user can have a watchlist
			if ( $user->isAnon() ) {
				continue;
			}
			$rows[] = array(
				'wl_user' => $user->getId(),
				'wl_namespace' => MWNamespace::getSubject( $title->getNamespace() ),
				'wl_title' => $title->getDBkey(),
				'wl_notificationtimestamp' => null,
			);
			// Every single watched page needs now to be listed in watchlist;
			// namespace:page and namespace_talk:page need separate entries:
			$rows[] = array(
				'wl_user' => $user->getId(),
				'wl_namespace' => MWNamespace::getTalk( $title->getNamespace() ),
				'wl_title' => $title->getDBkey(),
				'wl_notificationtimestamp' => null
			);
			$this->uncache( $user, $title );
		}

		if ( !$rows ) {
			return false;
		}

		$dbw = $this->loadBalancer->getConnection( DB_MASTER );
		foreach ( array_chunk( $rows, 100 ) as $toInsert ) {
			// Use INSERT IGNORE to avoid overwriting the notification timestamp
			// if there's already an entry for this page
			$dbw->insert( 'watchlist', $toInsert, __METHOD__, 'IGNORE' );
		}

		return true;
	}

	/**
	 * @param User $user
	 * @param Title $title
	 *
	 * @return bool success
	 * @throws DBUnexpectedError
	 * @throws MWException
	 */
	public function removeWatch( User $user, Title $title ) {
		// Only loggedin user can have a watchlist
		if ( wfReadOnly() || $user->isAnon() ) {
			return false;
		}

		$this->uncache( $user, $title );

		$success = false;
		$dbw = $this->loadBalancer->getConnection( DB_MASTER );
		$dbw->delete( 'watchlist',
			array(
				'wl_user' => $user->getId(),
				'wl_namespace' => MWNamespace::getSubject( $title->getNamespace() ),
				'wl_title' => $title->getDBkey(),
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
				'wl_user' => $user->getId(),
				'wl_namespace' => MWNamespace::getTalk( $title->getNamespace() ),
				'wl_title' => $title->getDBkey(),
			), __METHOD__
		);

		if ( $dbw->affectedRows() ) {
			$success = true;
		}

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
	 * add watches on a new title. To be used for page renames and such.
	 *
	 * @param Title $ot Page title to duplicate entries from, if present
	 * @param Title $nt Page title to add watches on
	 */
	public function duplicateEntries( Title $ot, Title $nt ) {
		$this->doDuplicateEntries( $ot->getSubjectPage(), $nt->getSubjectPage() );
		$this->doDuplicateEntries( $ot->getTalkPage(), $nt->getTalkPage() );
	}

	/**
	 * Handle duplicate entries. Backend for duplicateEntries().
	 *
	 * @param Title $oldTitle
	 * @param Title $newTitle
	 *
	 * @return bool
	 */
	private function doDuplicateEntries( Title $oldTitle, Title $newTitle ) {
		$oldNamespace = $oldTitle->getNamespace();
		$newNamespace = $newTitle->getNamespace();
		$oldDBkey = $oldTitle->getDBkey();
		$newDBkey = $newTitle->getDBkey();

		$dbw = $this->loadBalancer->getConnection( DB_MASTER );

		$result = $dbw->select(
			'watchlist',
			array( 'wl_user', 'wl_notificationtimestamp' ),
			array( 'wl_namespace' => $oldNamespace, 'wl_title' => $oldDBkey ),
			__METHOD__,
			'FOR UPDATE'
		);

		# Construct array to replace into the watchlist
		$values = array();
		foreach ( $result as $row ) {
			$values[] = array(
				'wl_user' => $row->wl_user,
				'wl_namespace' => $newNamespace,
				'wl_title' => $newDBkey,
				'wl_notificationtimestamp' => $row->wl_notificationtimestamp,
			);
		}

		if ( !empty( $values ) ) {
			# Perform replace
			# Note that multi-row replace is very efficient for MySQL but may be inefficient for
			# some other DBMSes, mostly due to poor simulation by us
			$dbw->replace(
				'watchlist',
				array( array( 'wl_user', 'wl_namespace', 'wl_title' ) ),
				$values,
				__METHOD__
			);
		}
	}

}
