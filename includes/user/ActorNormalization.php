<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\User;

use CannotCreateActorException;
use InvalidArgumentException;
use stdClass;
use Wikimedia\Rdbms\IDatabase;

/**
 * Service for dealing with the actor table.
 * @since 1.36
 */
interface ActorNormalization {

	/**
	 * Instantiate a new UserIdentity object based on a $row from the actor table.
	 *
	 * Use this method when an actor row was already fetched from the DB via a join.
	 * This method just constructs a new instance and does not try fetching missing
	 * values from the DB again, use {@link UserIdentityLookup} for that.
	 *
	 * @param stdClass $row with the following fields:
	 *  - int actor_id
	 *  - string actor_name
	 *  - int|null actor_user
	 * @return UserIdentity
	 * @throws InvalidArgumentException
	 */
	public function newActorFromRow( stdClass $row ): UserIdentity;

	/**
	 * Instantiate a new UserIdentity object based on field values from a DB row.
	 *
	 * Until {@link ActorMigration} is completed, the actor table joins alias actor field names
	 * to legacy field names. This method is convenience to construct the UserIdentity based on
	 * legacy field names. It's more relaxed with typing then ::newFromRow to better support legacy
	 * code, so always prefer ::newFromRow in new code. Eventually, once {@link ActorMigration}
	 * is completed and all queries use explicit join with actor table, this method will be
	 * deprecated and removed.
	 *
	 * @throws InvalidArgumentException
	 * @param int|null $userId
	 * @param string|null $name
	 * @param int|null $actorId
	 * @return UserIdentity
	 */
	public function newActorFromRowFields( $userId, $name, $actorId ): UserIdentity;

	/**
	 * Find the actor_id for the given name.
	 *
	 * @param string $name
	 * @param IDatabase $db The database connection to operate on.
	 *        The database must correspond to the wiki this ActorNormalization is bound to.
	 * @return int|null
	 */
	public function findActorIdByName( string $name, IDatabase $db ): ?int;

	/**
	 * Find the actor_id of the given $user.
	 *
	 * @param UserIdentity $user
	 * @param IDatabase $db The database connection to operate on.
	 *        The database must correspond to the wiki this ActorNormalization is bound to.
	 * @return int|null
	 */
	public function findActorId( UserIdentity $user, IDatabase $db ): ?int;

	/**
	 * Attempt to assign an actor ID to the given $user
	 * If it is already assigned, return the existing ID.
	 *
	 * @param UserIdentity $user
	 * @param IDatabase $dbw The database connection to acquire the ID from.
	 *        The database must correspond to the wiki this ActorNormalization is bound to.
	 * @return int greater then 0
	 * @throws CannotCreateActorException if no actor ID has been assigned to this $user
	 */
	public function acquireActorId( UserIdentity $user, IDatabase $dbw ): int;

	/**
	 * Find an actor by $id.
	 *
	 * @param int $actorId
	 * @param IDatabase $db The database connection to operate on.
	 *        The database must correspond to the wiki this ActorNormalization is bound to.
	 * @return UserIdentity|null Returns null if no actor with this $actorId exists in the database.
	 */
	public function getActorById( int $actorId, IDatabase $db ): ?UserIdentity;

	/**
	 * In case all reasonable attempts of initializing a proper actor from the
	 * database have failed, entities can be attributed to special 'Unknown user' actor.
	 *
	 * @return UserIdentity
	 */
	public function getUnknownActor(): UserIdentity;
}
