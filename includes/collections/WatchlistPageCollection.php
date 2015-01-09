<?php

/**
 * WatchlistPageCollection.php
 */

/**
 * A collection of items representing a set of pages that the user is watching.
 */
class WatchlistPageCollection extends UserPageCollection {
	protected $tag = 'watchlist';

	public function getTag() {
		return $this->tag;
	}

	public function setTag( $tag ) {
		$this->tag = $tag;
	}

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
				'wl_tag' => $this->getTag(),
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
			array(
				'wl_user' => $this->getOwner()->getId(),
				'wl_tag' => $this->getTag(),
			),
			__METHOD__
		);
	}

	/**
	 * Save to database any changes to the model.
	 */
	public function saveToDatabase() {
		$dbw = $this->getDatabase();
		$rows = array();

		foreach ( $this->items as $item ) {
			$title = $item->getTitle();
			// add page
			$rows[] = array(
				'wl_user' => $this->getOwner()->getId(),
				'wl_namespace' => MWNamespace::getSubject( $title->getNamespace() ),
				'wl_title' => $title->getDBkey(),
				'wl_notificationtimestamp' => null,
				'wl_tag' => $this->getTag(),
				'wl_timestamp' => wfTimestampNow(),
			);
			// add talk page
			$rows[] = array(
				'wl_user' => $this->getOwner()->getId(),
				'wl_namespace' => MWNamespace::getTalk( $title->getNamespace() ),
				'wl_title' => $title->getDBkey(),
				'wl_notificationtimestamp' => null,
				'wl_tag' => $this->getTag(),
				'wl_timestamp' => wfTimestampNow(),
			);
		}

		if ( count( $rows ) > 0 ) {
			$dbw->insert( 'watchlist', $rows, __METHOD__, 'IGNORE' );
		}

		foreach ( $this->removedItems as $item ) {
			$title = $item->getTitle();
			// delete page
			$dbw->delete(
				'watchlist',
				array(
					'wl_user' => $this->getOwner()->getId(),
					'wl_namespace' => MWNamespace::getSubject( $title->getNamespace() ),
					'wl_title' => $title->getDBkey(),
					'wl_tag' => $this->getTag(),
				),
				__METHOD__
			);

			// delete talk page
			$dbw->delete(
				'watchlist',
				array(
					'wl_user' => $this->getOwner()->getId(),
					'wl_namespace' => MWNamespace::getTalk( $title->getNamespace() ),
					'wl_title' => $title->getDBkey(),
					'wl_tag' => $this->getTag(),
				),
				__METHOD__
			);

			$page = WikiPage::factory( $title );
			Hooks::run( 'UnwatchArticleComplete', array( $this->getOwner(), &$page ) );
		}
	}
}
