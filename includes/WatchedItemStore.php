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
		$dbw = $this->loadBalancer->getConnection( DB_MASTER, array( 'watchlist' ) );

		$result = $dbw->select(
			'watchlist',
			array( 'wl_user', 'wl_notificationtimestamp' ),
			array(
				'wl_namespace' => $oldTarget->getNamespace(),
				'wl_title' => $oldTarget->getDBkey(),
			),
			__METHOD__,
			array( 'FOR UPDATE' )
		);

		$newNamespace = $newTarget->getNamespace();
		$newDBkey = $newTarget->getDBkey();

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

		$this->loadBalancer->reuseConnection( $dbw );
	}

}
