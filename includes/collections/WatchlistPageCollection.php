<?php

/**
 * WatchlistPageCollection.php
 */

/**
 * A collection of items representing a set of pages that the user is watching.
 */
class WatchlistPageCollection extends PageCollection {

	/**
	 * Get the database associated with the Watchlist.
	 * @return DatabaseBase
	 */
	public function getDatabase() {
		return wfGetDB( DB_MASTER );
	}

	/**
	 * Load data for the watchlist for the current user from the given database
	 * @param DatabaseBase $dbr
	 */
	public function loadFromDatabase() {
		$dbr = $this->getDatabase();
		$this->clear();
		$list = array();

		$res = $dbr->select(
			'watchlist',
			array(
				'wl_namespace',
				'wl_title',
			),
			array(
				'wl_user' => $this->getOwner()->getId(),
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
				$item = new PageCollectionItem( $title );
				$this->add( $item );
			}
		}
	}

	/**
	 * Remove all titles from storage for the existing watchlist collection.
	 */
	public function clearInDatabase() {
		$dbw = $this->getDatabase();
		$dbw->delete(
			'watchlist',
			array( 'wl_user' => $this->getOwner()->getId() ),
			__METHOD__
		);
	}
}
