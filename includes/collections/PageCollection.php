<?php

/**
 * Collection.php
 */

/**
 * A collection of PageCollectionItems
 */
class PageCollection implements IteratorAggregate {
	/**
	 * The internal collection of pages.
	 *
	 * @var PageCollectionItem[]
	 */
	protected $items = array();

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
}
