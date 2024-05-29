<?php

namespace MediaWiki\User\Options;

use MediaWiki\User\UserIdentity;

/**
 * @since 1.43
 * @stable to implement
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
