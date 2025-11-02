<?php

namespace MediaWiki\User\Options;

use MediaWiki\User\UserIdentity;

/**
 * Get or change options for a given user in a given backend store.
 *
 * #### Usage
 *
 * To read or save a user option, use the UserOptionsManager service instead.
 * UserOptionsStore is the backend key-value abstraction used to differentiate
 * between local and global preferences. This interface is considered internal
 * and should only be called by the UserOptionsManager service. Unless you know
 * which backend store an option belongs to, do not call this.
 *
 * Extension such as GlobalPreferences may implement this interface to create
 * additional backend stores, but extensions should not instantiate or call
 * classes with this interface.
 *
 * When creating a new implementation, register it via the UserOptionsStoreProviders
 * extension attribute. The UserOptionsManager service will automatically discover
 * and merge values from your backend. When calling UserOptionsManager::setOption
 * or ApiOptions, we automatically write to the appropiate store, based on where
 * a previous value existed, and the `global` flag.
 *
 * Launch task: https://phabricator.wikimedia.org/T323076
 *
 * #### See also
 *
 * Default implementation is MediaWiki\User\Options\LocalUserOptionsStore.
 *
 * For the frontend "Preferences" concept, and for registering and describing
 * which user options can exist, refer to PreferencesFactory, $wgDefaultUserOptions
 * and DefaultPreferencesFactory instead.
 *
 * @stable to implement
 * @since 1.43
 * @ingroup User
 */
interface UserOptionsStore {
	/**
	 * Fetch all options for a given user from the store.
	 *
	 * Note that OptionsStore does not handle fallback to default. Options are
	 * either present or absent.
	 *
	 * @param UserIdentity $user A user with a non-zero ID
	 * @param int $recency a bit field composed of READ_XXX flags
	 * @return array<string,string>
	 */
	public function fetch( UserIdentity $user, int $recency );

	/**
	 * Fetch specific options for multiple users from the store. Return an array
	 * indexed first by option key, and second by user name.
	 *
	 * @since 1.44
	 *
	 * @param array $keys
	 * @param array $userNames
	 * @return array<string,array<string,string>>
	 */
	public function fetchBatchForUserNames( array $keys, array $userNames );

	/**
	 * Process a batch of option updates.
	 *
	 * The store may assume that fetch() was previously called with a recency
	 * sufficient to provide reference values for a differential update. It is
	 * the caller's responsibility to manage recency.
	 *
	 * Note that OptionsStore does not have a concept of defaults. The store is
	 * not required to check whether the value matches the default.
	 *
	 * @param UserIdentity $user A user with a non-zero ID
	 * @param array<string,string|null> $updates A map of option names to new
	 *   values. If the value is null, the key should be deleted from the store
	 *   and subsequently not returned from fetch(). Absent keys should be left
	 *   unchanged.
	 * @return bool Whether any change was made
	 */
	public function store( UserIdentity $user, array $updates );
}
