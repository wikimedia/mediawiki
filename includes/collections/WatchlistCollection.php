<?php

/**
 * WatchlistCollection.php
 */

/**
 * A collection of items representing a set of pages that the user is watching.
 */
class WatchlistCollection extends Collection {
	/** @inheritdoc */
	public function __construct( User $user ) {
		parent::__construct( $user );
		$list = array();
		$dbr = wfGetDB( DB_MASTER );

		$res = $dbr->select(
			'watchlist',
			array(
				'wl_namespace',
				'wl_title',
			),
			array(
				'wl_user' => $user->getId(),
			),
			__METHOD__,
			array( 'ORDER BY' => array( 'wl_namespace', 'wl_title' ) )
		);

		if ( $res->numRows() > 0 ) {
			$titles = array();
			foreach ( $res as $row ) {
				$title = Title::makeTitleSafe( $row->wl_namespace, $row->wl_title );
				$titles[] = $title;
			}
			$res->free();

			GenderCache::singleton()->doTitlesArray( $titles );

			foreach ( $titles as $title ) {
				$item = new CollectionItem( $title );
				$this->add( $item );
			}
		}
	}

	/**
	 * Remove all titles from storage for the existing watchlist collection.
	 */
	public function clear() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'watchlist',
			array( 'wl_user' => $this->getOwner()->getId() ),
			__METHOD__
		);
	}
}
