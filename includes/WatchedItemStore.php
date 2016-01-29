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
	private $items = array();

	public function __construct( LoadBalancer $loadBalancer, Config $config ) {
		$this->loadBalancer = $loadBalancer;
		$this->config = $config;
	}

	/**
	 * @return self
	 */
	public static function getDefaultInstance() {
		static $instance;
		if ( !$instance ) {
			$instance = new self( wfGetLB(), RequestContext::getMain()->getConfig() );
		}
		return $instance;
	}

	/**
	 * @param User $user
	 * @param Title $title
	 * @param int $checkRights Whether to check 'viewmywatchlist'/'editmywatchlist' rights.
	 *     Pass WatchedItem::CHECK_USER_RIGHTS or WatchedItem::IGNORE_USER_RIGHTS.
	 *
	 * @return WatchedItem
	 */
	public function getWatchedItem(
		User $user,
		Title $title,
		$checkRights = WatchedItem::CHECK_USER_RIGHTS
	) {
		$key = $this->getCacheKey( $user, $title, $checkRights );

		if ( isset( $this->items[$key] ) ) {
			return $this->items[$key];
		}

		if ( count( $this->items ) >= self::MAX_WATCHED_ITEMS_CACHE ) {
			$this->items = array();
		}

		$this->items[$key] = WatchedItem::fromUserTitle( $user, $title );
		return $this->items[$key];
	}

	/**
	 * @param User $user
	 * @param Title $title
	 * @param int $checkRights
	 *
	 * @return string
	 */
	private function getCacheKey( User $user, Title $title, $checkRights ) {
		return $title->getNamespace() . ':' .
			$title->getDBkey() . ':' .
			$user->getId() . ':' .
			$checkRights;
	}

	/**
	 * @param WatchedItem $watchedItem
	 */
	private function uncacheItem( WatchedItem $watchedItem ) {
		$keys = array(
			$this->getCacheKey( $watchedItem->mUser, $watchedItem->mTitle, WatchedItem::IGNORE_USER_RIGHTS ),
			$this->getCacheKey( $watchedItem->mUser, $watchedItem->mTitle, WatchedItem::CHECK_USER_RIGHTS )
		);
		foreach ( $keys as $key ) {
			unset( $this->items[$key] );
		}
	}

	/**
	 * @param WatchedItem $item
	 *
	 * @return bool success
	 * @throws DBUnexpectedError
	 * @throws MWException
	 */
	public function remove( WatchedItem $item ) {
		$user = $item->mUser;
		$title = $item->mTitle;

		// Only loggedin user can have a watchlist
		// TODO loudly fail when permission check fails
		if ( wfReadOnly() || $user->isAnon() || !$user->isAllowed( 'editmywatchlist' ) ) {
			return false;
		}

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

		$this->uncacheItem( $item );
		return $success;
	}

	/**
	 * @param TitleValue $titleValue
	 *
	 * @return int
	 */
	public function countWatchers( TitleValue $titleValue ) {
		return (int)$this->loadBalancer->getConnection( DB_SLAVE )->selectField(
			'watchlist',
			'COUNT(*)',
			array(
				'wl_namespace' => $titleValue->getNamespace(),
				'wl_title' => $titleValue->getDBkey(),
			),
			__METHOD__
		);
	}

	/**
	 * @param TitleValue[]|Title[] $titleValues
	 * @param bool $unwatchedPages show unwatched pages obeying UnwatchedPageThreshold setting
	 *
	 * @return array multi dimensional like $return[$namespaceId][$titleString] = $watchers
	 */
	public function countWatchersMultiple( array $titleValues, $unwatchedPages = false ) {
		$unwatchedPageThreshold = $this->config->get( 'UnwatchedPageThreshold' );

		if ( !$unwatchedPages && !is_int( $unwatchedPageThreshold ) ) {
			// TODO throw exception?
			return array();
		}

		$options = array( 'GROUP BY' => array( 'wl_namespace', 'wl_title' ) );
		if ( !$unwatchedPages ) {
			$options['HAVING'] = "COUNT(*) >= $unwatchedPageThreshold";
		}

		$dbr = $this->loadBalancer->getConnection( DB_SLAVE );

		$lb = new LinkBatch( $titleValues );
		$res = $dbr->select(
			'watchlist',
			array( 'wl_title', 'wl_namespace', 'count' => 'COUNT(*)' ),
			array( $lb->constructSet( 'wl', $dbr ) ),
			__METHOD__,
			$options
		);

		$watchCounts = array();
		foreach ( $res as $row ) {
			$watchCounts[$row->wl_namespace][$row->wl_title] = (int)$row->count;
		}

		return $watchCounts;
	}

	/**
	 * Check if the given title already is watched by the user, and if so
	 * add watches on a new title. To be used for page renames and such.
	 *
	 * @param TitleValue $oldTitle Page title to duplicate entries from, if present
	 * @param TitleValue $newTitle Page title to add watches on
	 */
	public function duplicateEntries( TitleValue $oldTitle, TitleValue $newTitle ) {
		$oldNamespace = $oldTitle->getNamespace();
		if ( MWNamespace::isSubject( $oldNamespace ) ) {
			$otherNs = MWNamespace::getTalk( $oldNamespace );
		} else {
			$otherNs = MWNamespace::getSubject( $oldNamespace );
		}

		$this->doDuplicateEntries( $oldTitle, $newTitle );
		$this->doDuplicateEntries(
			new TitleValue( $otherNs, $oldTitle->getDBkey(), $oldTitle->getFragment() ),
			new TitleValue( $otherNs, $newTitle->getDBkey(), $newTitle->getFragment() )
		);
	}

	/**
	 * Handle duplicate entries. Backend for duplicateEntries().
	 *
	 * @param TitleValue $oldTitle
	 * @param TitleValue $newTitle
	 *
	 * @return bool
	 */
	private function doDuplicateEntries( TitleValue $oldTitle, TitleValue $newTitle ) {
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
