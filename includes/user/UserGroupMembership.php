<?php
/**
 * Represents the membership of a user to a user group.
 *
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

/**
 * Represents a "user group membership" -- a specific instance of a user belonging
 * to a group. For example, the fact that user Mary belongs to the sysop group is a
 * user group membership.
 *
 * The class encapsulates rows in the user_groups table. The logic is low-level and
 * doesn't run any hooks. Often, you will want to call User::addGroup() or
 * User::removeGroup() instead.
 *
 * @since 1.29
 */
class UserGroupMembership {
	/** @var int Primary key of the user_groups row */
	protected $id;

	/** @var int The ID of the user who belongs to the group */
	protected $userId;

	/** @var string */
	protected $group;

	/** @var string|null Timestamp of expiry, or null if no expiry */
	protected $expiry;

	public function __construct( $userId = 0, $group = null, $expiry = null ) {
		$this->userId = intval( $userId );
		$this->group = $group; // TODO throw on invalid group?
		$this->expiry = $expiry ?: null;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * @return string|null Timestamp of expiry, or null if no expiry
	 */
	public function getExpiry() {
		return $this->expiry;
	}

	protected function initFromRow( $row ) {
		$this->id = intval( $row->ug_id );
		$this->userId = intval( $row->ug_user );
		$this->group = $row->ug_group;
		$this->expiry = $row->ug_expiry ?: null;
	}

	public static function newFromRow( $row ) {
		$ugm = new self;
		$ugm->initFromRow( $row );
		return $ugm;
	}

	/**
	 * Return the list of user_groups fields that should be selected to create
	 * a new user group membership.
	 * @return array
	 */
	public static function selectFields() {
		return [
			'ug_id',
			'ug_user',
			'ug_group',
			'ug_expiry',
		];
	}

	/**
	 * Delete the row from the user_groups table.
	 *
	 * @throws MWException
	 * @param IDatabase|null $dbw Optional master database connection to use
	 * @return bool
	 */
	public function delete( IDatabase $dbw = null ) {
		if ( wfReadOnly() ) {
			return false;
		}

		if ( !$this->getId() ) {
			throw new MWException( 'UserGroupMembership::delete() requires that the id member be filled' );
		}

		if ( $dbw === null ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		$dbw->delete( 'user_groups', [ 'ug_id' => $this->id ], __METHOD__ );
		// Remember that the user was in this group
		$dbw->insert( 'user_former_groups',
			[
				'ufg_user' => $this->userId,
				'ufg_group' => $this->group,
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		return $dbw->affectedRows() > 0;
	}

	/**
	 * Insert a user right membership into the database. The function fails if there
	 * is a conflicting membership entry (same user and group) already in the table.
	 *
	 * @throws MWException
	 * @param bool $allowUpdate Whether to perform "upsert" instead of INSERT
	 * @param IDatabase|null $dbw If you have one available
	 * @return bool|int False on failure, primary key (ug_id) value of newly
	 *   inserted row on success
	 */
	public function insert( $allowUpdate = false, IDatabase $dbw = null ) {
		if ( $dbw === null ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		// Periodically purge old, expired memberships from the DB
		self::purgeExpired( $dbw );

		// Check that the values make sense
		if ( $this->group === null ) {
			throw new MWException( 'Don\'t try inserting an uninitialized UserGroupMembership object' );
		} elseif ( $this->userId <= 0 ) {
			throw new MWException( 'UserGroupMembership::insert() needs a positive user ID. ' .
				'Did you forget to add your User object to the database before calling addGroup()?' );
		}

		$row = $this->getDatabaseArray();
		$row['ug_id'] = $dbw->nextSequenceValue( 'user_groups_ug_id_seq' );

		$dbw->insert( 'user_groups', $row, __METHOD__, [ 'IGNORE' ] );
		$affected = $dbw->affectedRows();
		$this->id = intval( $dbw->insertId() );

		// Don't collide with expired user group memberships
		// Do this after trying to insert, in order to avoid locking
		if ( !$affected ) {
			// Using SELECT + DELETE per T96428
			$conds = [
				'ug_user' => $row['ug_user'],
				'ug_group' => $row['ug_group'],
			];
			$existingRow = $dbw->selectRow( 'user_groups', [ 'ug_id', 'ug_expiry' ],
				$conds, __METHOD__ );
			if ( $existingRow ) {
				// if we're unconditionally updating, check that the expiry is not the same;
				// otherwise, only delete+insert if the expiry date is in the past
				if ( $allowUpdate ?
					( $existingRow->ug_expiry != $this->expiry ) :
					( $existingRow->ug_expiry < wfTimestampNow() )
				) {
					$dbw->delete( 'user_groups', [ 'ug_id' => $existingRow->ug_id ], __METHOD__ );
					$dbw->insert( 'user_groups', $row, __METHOD__, [ 'IGNORE' ] );
					$affected = $dbw->affectedRows();
					$this->id = intval( $dbw->insertId() );
				}
			}
		}

		return ( $affected ? $this->id : false );
	}

	/**
	 * Update a block in the DB with a new expiry date.
	 * The ID field needs to be loaded first.
	 *
	 * @return bool|int False on failure, primary key (ug_id) value of updated row
	 *   on success
	 */
	public function update() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->update(
			'user_groups',
			$this->getDatabaseArray( $dbw ),
			[ 'ug_id' => $this->getId() ],
			__METHOD__
		);

		$affected = $dbw->affectedRows();
		return ( $affected ? $this->id : false );
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 * @param IDatabase|null $db
	 * @return array
	 */
	protected function getDatabaseArray( $db = null ) {
		if ( !$db ) {
			$db = wfGetDB( DB_REPLICA );
		}

		return [
			'ug_user' => $this->userId,
			'ug_group' => $this->group,
			'ug_expiry' => $this->expiry ? $db->timestamp( $this->expiry ) : null,
		];
	}

	/**
	 * Check if this membership has expired. Delete it if it is.
	 * @return bool
	 */
	public function deleteIfExpired() {
		if ( $this->isExpired() ) {
			$this->delete();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Has the membership expired?
	 * @return bool
	 */
	public function isExpired() {
		if ( !$this->expiry ) {
			return false;
		} else {
			return wfTimestampNow() > $this->expiry;
		}
	}

	/**
	 * Purge expired memberships from the user_groups table
	 *
	 * @param IDatabase $dbw
	 */
	public static function purgeExpired( IDatabase $dbw = null ) {
		if ( wfReadOnly() ) {
			return;
		}

		if ( $dbw === null ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		DeferredUpdates::addUpdate( new AtomicSectionUpdate(
			$dbw,
			__METHOD__,
			function ( IDatabase $dbw, $fname ) {
				$ids = $dbw->selectFieldValues(
					'user_groups',
					'ug_id',
					[ 'ug_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ],
					$fname
				);
				// create a light UserGroupMembership object just with ID
				$ugm = new self;
				foreach ( $ids as $id ) {
					$ugm->id = $id;
					$ugm->delete( $dbw );
				}
			}
		) );
	}

	/**
	 * Gets a HTML link to a user group, possibly including the expiry date if
	 * relevant.
	 *
	 * @param string|UserGroupMembership $ugm Either a group name as a string, or
	 *   a UserGroupMembership object
	 * @param IContextSource $context
	 * @param bool $isMember Whether to use the group member message ("administrator")
	 *   instead of the group name message ("administrators")
	 * @param string $userName Name of the user who belongs to the group, for GENDER
	 *   of the group member message (only needed if $isMember is true)
	 * @return string
	 */
	public static function getLinkHTML( $ugm, IContextSource $context, $isMember = false,
		$userName = '' ) {

		if ( $ugm instanceof UserGroupMembership ) {
			$expiry = $ugm->getExpiry();
			$group = $ugm->getGroup();
		} else {
			$expiry = null;
			$group = $ugm;
		}

		if ( $isMember ) {
			$groupName = User::getGroupMember( $group, $userName );
		} else {
			$groupName = User::getGroupName( $group );
		}

		if ( $expiry ) {
			// format the expiry to a nice string
			$uiLanguage = $context->getLanguage();
			$uiUser = $context->getUser();
			$expiry = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
			$expiryD = $uiLanguage->userDate( $expiry, $uiUser );
			$expiryT = $uiLanguage->userTime( $expiry, $uiUser );
			return $context->msg( 'group-membership-link-with-expiry' )->rawParams(
				User::makeGroupLinkHTML( $group, $groupName ) )->params( $expiry,
				$expiryD, $expiryT )->text();
		} else {
			return User::makeGroupLinkHTML( $group, $groupName );
		}
	}

	/**
	 * Returns UserGroupMembership objects for all the groups a user currently
	 * belongs to.
	 *
	 * @param int $userId ID of the user to search for
	 * @param IDatabase|null $db Optional database connection
	 * @return array Associative array of (group name => UserGroupMembership object)
	 */
	public static function getMembershipsForUser( $userId, $db = null ) {
		if ( !$db ) {
			$db = wfGetDB( DB_REPLICA );
		}

		$res = $db->select( 'user_groups',
			self::selectFields(),
			[ 'ug_user' => $userId ],
			__METHOD__ );

		$ugms = [];
		foreach ( $res as $row ) {
			$ugm = self::newFromRow( $row );
			if ( !$ugm->isExpired() ) {
				$ugms[$ugm->group] = $ugm;
			}
		}

		return $ugms;
	}

	/**
	 * Returns a UserGroupMembership object that pertains to the given user and group,
	 * or false if the user does not belong to that group (or the assignment has
	 * expired).
	 *
	 * @param int $userId ID of the user to search for
	 * @param string $group User group name
	 * @param IDatabase|null $db Optional database connection
	 * @return UserGroupMembership|false
	 */
	public static function getMembership( $userId, $group, IDatabase $db = null ) {
		if ( !$db ) {
			$db = wfGetDB( DB_REPLICA );
		}

		$row = $db->selectRow( 'user_groups',
			self::selectFields(),
			[ 'ug_user' => $userId, 'ug_group' => $group ],
			__METHOD__ );
		if ( !$row ) {
			return false;
		}

		$ugm = self::newFromRow( $row );
		if ( !$ugm->isExpired() ) {
			return $ugm;
		} else {
			return false;
		}
	}
}
