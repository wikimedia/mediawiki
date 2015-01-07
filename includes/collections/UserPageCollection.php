<?php

/**
 * UserCollection.php
 */

/**
 * A collection of PageCollectionItems owned by a specific user
 */
class UserPageCollection extends PageCollection {
	/**
	 * The user who owns the collection.
	 *
	 * @var User $user
	 */
	protected $user;

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
}
