<?php

/**
 * This interface represents a User-like object that can be a member of user groups.
 * UserGroupMember objects can be added to and removed from groups, and the list
 * of groups they belong to can be retrieved.
 *
 * @since 1.29
 */
interface UserGroupMember {

	/**
	 * Return the user's username
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Return the user's user ID
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Return the groups the user is currently in
	 *
	 * @return string[]
	 */
	public function getGroups();

	/**
	 * Remove the user from the provided group
	 *
	 * @param string $group
	 */
	public function removeGroup( $group );

	/**
	 * Add the user to the provided group
	 *
	 * @param string $group
	 */
	public function addGroup( $group );

	/**
	 * Clear any caches if necessary
	 */
	public function invalidateCache();

	/**
	 * Get a Title corresponding to the user's userpage
	 *
	 * @return Title
	 */
	public function getUserPage();
}
