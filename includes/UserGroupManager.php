<?php

/**
 * Interface to represent a User that is a member
 * of groups that can be added or removed.
 *
 * @since 1.25
 */
interface UserGroupManager {

	/**
	 * Return the user's username
	 *
	 * @return string
	 */
	public function getName();

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
