<?php

/**
 * Collection.php
 */

/**
 * A collection of PageCollectionItems
 */
class PageCollection implements IteratorAggregate, Countable {
	/**
	 * The internal collection of removed pages.
	 *
	 * @var PageCollectionItem[]
	 */
	protected $removedItems;

	/**
	 * The user who owns the collection.
	 *
	 * @var User $user
	 */
	protected $user;

	/**
	 * The internal collection of pages.
	 *
	 * @var PageCollectionItem[]
	 */
	protected $items = array();

	/**
	 * Constructs an iterator aggregate instance
	 *
	 * @param User $user
	 */
	public function __construct( User $user ) {
		$this->user = $user;
	}

	/**
	 * Returns the size of the collection
	 * @return integer
	 */
	public function count() {
		return count( $this->items );
	}

	/**
	 * Returns the owner of the collection
	 *
	 * @return User
	 */
	public function getOwner() {
		return $this->user;
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

	/**
	 * Pop the last title from the collection
	 */
	public function pop() {
		$item = array_pop( $this->items );
		$this->removedItems[] = $item;
		return $item;
	}

	/** @inheritdoc */
	public function getIterator() {
		return new ArrayIterator( $this->items );
	}
}
