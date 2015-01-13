<?php

/**
 * Collection.php
 */

/**
 * A collection of PageCollectionItems
 */
class PageCollection implements IteratorAggregate, Countable {
	/**
	 * Maximum number of pages to show on single subpage.
	 *
	 * @var int $maxPerPage
	 */
	protected $maxPerPage = 345;

	/**
	 * Get the database associated with the Watchlist.
	 * @return DatabaseBase
	 */
	public function getDatabase() {
		return wfGetDB( DB_MASTER );
	}

	/**
	 * The internal collection of pages.
	 *
	 * @var PageCollectionItem[]
	 */
	protected $items = array();

	/**
	 * Internal namespace to restrict collection to
	 *
	 * @var Number|False
	 */
	protected $ns;

	/**
	 * From key
	 *
	 * @var string
	 */
	protected $fromKey;

	/**
	 * To key
	 *
	 * @var string
	 */
	protected $toKey;

	/**
	 * Returns the size of the collection
	 * @return integer
	 */
	public function count() {
		return count( $this->items );
	}

	/**
	 * Adds an item to the collection.
	 *
	 * @param PageCollectionItem $item
	 */
	public function add( PageCollectionItem $item ) {
		$this->items[] = $item;
	}

	/**
	 * Clears the collection of all existing entries.
	 *
	 */
	public function clear() {
		$this->items = array();
	}

	/** @inheritdoc */
	public function getIterator() {
		return new ArrayIterator( $this->items );
	}

	/**
	 * Restrict namespace for collection.
	 * @param Integer|False $ns
	 */
	public function setNamespaceId( $ns ) {
		$this->ns = $ns;
	}

	public function setDatabaseRange( $fromKey, $toKey ) {
		$this->fromKey = $fromKey;
		$this->toKey = $toKey;
	}

	protected function loadPreviousItem() {
		$dbr = $this->getDatabase();
		$fromKey = $this->fromKey;
		$this->previousItem = null;
		if ( $from !== '' ) {
			// FIXME: use slave?
			$resPrev = $dbr->select(
				'page',
				'page_title',
				array(
					'page_namespace' => $this->ns,
					'page_title < ' . $dbr->addQuotes( $fromKey )
				),
				__METHOD__,
				array(
					'ORDER BY' => 'page_title DESC',
					'LIMIT' => $this->maxPerPage,
					'OFFSET' => ( $this->maxPerPage - 1 ),
				)
			);

			# Get first title of previous complete chunk
			if ( $dbr->numrows( $resPrev ) >= $this->maxPerPage ) {
				$pt = $dbr->fetchObject( $resPrev );
				$this->previousItem = new PageCollectionItem( Title::makeTitle( $namespace, $pt->page_title ) );
			} else {
				# The previous chunk is not complete, need to link to the very first title
				# available in the database
				$options = array( 'LIMIT' => 1 );
				if ( !$dbr->implicitOrderby() ) {
					$options['ORDER BY'] = 'page_title';
				}
				$titleText = $dbr->selectField( 'page', 'page_title',
					array(
						'page_namespace' => $namespace
					),
					 __METHOD__,
					 $options
				);

				# Show the previous link if it s not the current requested chunk
				if ( $from != $titleText ) {
					$this->previousItem = new PageCollectionItem( Title::makeTitle( $namespace, $titleText ) );
				}
			}
		}
	}
	/**
	 * Load data for the watchlist for the current user from the given database
	 */
	public function loadFromDatabase() {
		$namespace = $this->ns;
		$fromKey = $this->fromKey;
		$toKey = $this->toKey;

		$dbr = $this->getDatabase();
		$conds = array(
			'page_namespace' => $namespace,
			'page_title >= ' . $dbr->addQuotes( $this->fromKey )
		);

		if ( $hideredirects ) {
			$conds['page_is_redirect'] = 0;
		}

		if ( $this->toKey !== "" ) {
			$conds[] = 'page_title <= ' . $dbr->addQuotes( $this->toKey );
		}

		$res = $dbr->select( 'page',
			array( 'page_namespace', 'page_title', 'page_is_redirect', 'page_id' ),
			$conds,
			__METHOD__,
			array(
				'ORDER BY' => 'page_title',
				'LIMIT' => $this->maxPerPage + 1,
				'USE INDEX' => 'name_title',
			)
		);

		if ( $res->numRows() > 0 ) {
			while ( ( $n < $this->maxPerPage ) && ( $s = $res->fetchObject() ) ) {
				$this->add( new PageCollectionItem( Title::newFromRow( $s ) ) );
				$n++;
			}
			$this->nextItem = new PageCollectionItem( Title::newFromRow( $res->fetchObject() ) );
		}
		$this->loadPreviousItem();
	}

	/**
	 * Load data for the watchlist for the current user from the given database
	 * @return PageCollectionItem
	 */
	public function getPreviousItem() {
		return $this->previousItem;
	}

	/**
	 * Load data for the watchlist for the current user from the given database
	 * @return PageCollectionItem
	 */
	public function getNextItem() {
		return $this->nextItem;
	}
}
