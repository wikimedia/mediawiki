<?php

/**
 * Collection.php
 */

/**
 * A collection of CollectionItems
 */
class Collection implements IteratorAggregate {
	/**
	 * The user who owns the collection.
	 *
	 * @var User $user
	 */
	protected $user;

	/**
	 * The internal collection of pages.
	 *
	 * @var CollectionItem[]
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
	 * @param CollectionItem $item
	 */
	public function add( CollectionItem $item ) {
		$this->items[] = $item;
	}

	/** @inheritdoc */
	public function getIterator() {
		return new ArrayIterator( $this->items );
	}
}
