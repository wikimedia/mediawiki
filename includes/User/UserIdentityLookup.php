<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Service for looking up UserIdentity
 *
 * Default implementation is MediaWiki\User\ActorStore.
 *
 * @since 1.36
 * @ingroup User
 */
interface UserIdentityLookup {

	/**
	 * Find an identity of a user by $name
	 *
	 * This method can't be used to check whether a name is valid, as it returns null both for invalid
	 * user names, and for valid user names (or IP addresses) that haven't been used on this wiki yet.
	 * Use UserNameUtils for that purpose.
	 *
	 * @param string $name
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserIdentity|null
	 */
	public function getUserIdentityByName(
		string $name,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): ?UserIdentity;

	/**
	 * Find an identity of a user by $userId
	 *
	 * @param int $userId
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserIdentity|null
	 */
	public function getUserIdentityByUserId(
		int $userId,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): ?UserIdentity;

	/**
	 * Returns a specialized SelectQueryBuilder for querying the UserIdentity objects.
	 *
	 * @param IReadableDatabase|int $dbOrQueryFlags The database connection to perform the query on,
	 *   or one of the IDBAccessObject::READ_* constants.
	 * @return UserSelectQueryBuilder
	 */
	public function newSelectQueryBuilder( $dbOrQueryFlags = IDBAccessObject::READ_NORMAL ): UserSelectQueryBuilder;
}
