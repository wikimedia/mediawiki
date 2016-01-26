<?php

/**
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
