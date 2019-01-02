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

use Wikimedia\Rdbms\IDatabase;
use MediaWiki\MediaWikiServices;

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
	/** @var int The ID of the user who belongs to the group */
	private $userId;

	/** @var string */
	private $group;

	/** @var string|null Timestamp of expiry in TS_MW format, or null if no expiry */
	private $expiry;

	/**
	 * @param int $userId The ID of the user who belongs to the group
	 * @param string|null $group The internal group name
	 * @param string|null $expiry Timestamp of expiry in TS_MW format, or null if no expiry
	 */
	public function __construct( $userId = 0, $group = null, $expiry = null ) {
		$this->userId = (int)$userId;
		$this->group = $group; // TODO throw on invalid group?
		$this->expiry = $expiry ?: null;
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
	 * @return string|null Timestamp of expiry in TS_MW format, or null if no expiry
	 */
	public function getExpiry() {
		return $this->expiry;
	}

	protected function initFromRow( $row ) {
		$this->userId = (int)$row->ug_user;
		$this->group = $row->ug_group;
		$this->expiry = $row->ug_expiry === null ?
			null :
			wfTimestamp( TS_MW, $row->ug_expiry );
	}

	/**
	 * Creates a new UserGroupMembership object from a database row.
	 *
	 * @param stdClass $row The row from the user_groups table
	 * @return UserGroupMembership
	 */
	public static function newFromRow( $row ) {
		$ugm = new self;
		$ugm->initFromRow( $row );
		return $ugm;
	}

	/**
	 * Returns the list of user_groups fields that should be selected to create
	 * a new user group membership.
	 * @return array
	 */
	public static function selectFields() {
		return [
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
	 * @return bool Whether or not anything was deleted
	 */
	public function delete( IDatabase $dbw = null ) {
		if ( wfReadOnly() ) {
			return false;
		}

		if ( $dbw === null ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		$dbw->delete(
			'user_groups',
			[ 'ug_user' => $this->userId, 'ug_group' => $this->group ],
			__METHOD__ );
		if ( !$dbw->affectedRows() ) {
			return false;
		}

		// Remember that the user was in this group
		$dbw->insert(
			'user_former_groups',
			[ 'ufg_user' => $this->userId, 'ufg_group' => $this->group ],
			__METHOD__,
			[ 'IGNORE' ] );

		return true;
	}

	/**
	 * Insert a user right membership into the database. When $allowUpdate is false,
	 * the function fails if there is a conflicting membership entry (same user and
	 * group) already in the table.
	 *
	 * @throws MWException
	 * @param bool $allowUpdate Whether to perform "upsert" instead of INSERT
	 * @param IDatabase|null $dbw If you have one available
	 * @return bool Whether or not anything was inserted
	 */
	public function insert( $allowUpdate = false, IDatabase $dbw = null ) {
		if ( $dbw === null ) {
			$dbw = wfGetDB( DB_MASTER );
		}

		// Purge old, expired memberships from the DB
		JobQueueGroup::singleton()->push( new UserGroupExpiryJob() );

		// Check that the values make sense
		if ( $this->group === null ) {
			throw new UnexpectedValueException(
				'Don\'t try inserting an uninitialized UserGroupMembership object' );
		} elseif ( $this->userId <= 0 ) {
			throw new UnexpectedValueException(
				'UserGroupMembership::insert() needs a positive user ID. ' .
				'Did you forget to add your User object to the database before calling addGroup()?' );
		}

		$row = $this->getDatabaseArray( $dbw );
		$dbw->insert( 'user_groups', $row, __METHOD__, [ 'IGNORE' ] );
		$affected = $dbw->affectedRows();

		// Don't collide with expired user group memberships
		// Do this after trying to insert, in order to avoid locking
		if ( !$affected ) {
			$conds = [
				'ug_user' => $row['ug_user'],
				'ug_group' => $row['ug_group'],
			];
			// if we're unconditionally updating, check that the expiry is not already the
			// same as what we are trying to update it to; otherwise, only update if
			// the expiry date is in the past
			if ( $allowUpdate ) {
				if ( $this->expiry ) {
					$conds[] = 'ug_expiry IS NULL OR ug_expiry != ' .
						$dbw->addQuotes( $dbw->timestamp( $this->expiry ) );
				} else {
					$conds[] = 'ug_expiry IS NOT NULL';
				}
			} else {
				$conds[] = 'ug_expiry < ' . $dbw->addQuotes( $dbw->timestamp() );
			}

			$row = $dbw->selectRow( 'user_groups', $this::selectFields(), $conds, __METHOD__ );
			if ( $row ) {
				$dbw->update(
					'user_groups',
					[ 'ug_expiry' => $this->expiry ? $dbw->timestamp( $this->expiry ) : null ],
					[ 'ug_user' => $row->ug_user, 'ug_group' => $row->ug_group ],
					__METHOD__ );
				$affected = $dbw->affectedRows();
			}
		}

		return $affected > 0;
	}

	/**
	 * Get an array suitable for passing to $dbw->insert() or $dbw->update()
	 * @param IDatabase $db
	 * @return array
	 */
	protected function getDatabaseArray( IDatabase $db ) {
		return [
			'ug_user' => $this->userId,
			'ug_group' => $this->group,
			'ug_expiry' => $this->expiry ? $db->timestamp( $this->expiry ) : null,
		];
	}

	/**
	 * Has the membership expired?
	 * @return bool
	 */
	public function isExpired() {
		if ( !$this->expiry ) {
			return false;
		}
		return wfTimestampNow() > $this->expiry;
	}

	/**
	 * Purge expired memberships from the user_groups table
	 *
	 * @return int|bool false if purging wasn't attempted (e.g. because of
	 *  readonly), the number of rows purged (might be 0) otherwise
	 */
	public static function purgeExpired() {
		$services = MediaWikiServices::getInstance();
		if ( $services->getReadOnlyMode()->isReadOnly() ) {
			return false;
		}

		$lbFactory = $services->getDBLoadBalancerFactory();
		$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );
		$dbw = $services->getDBLoadBalancer()->getConnection( DB_MASTER );

		$lockKey = $dbw->getDomainID() . ':usergroups-prune'; // specific to this wiki
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 0 );
		if ( !$scopedLock ) {
			return false; // already running
		}

		$now = time();
		$purgedRows = 0;
		do {
			$dbw->startAtomic( __METHOD__ );

			$res = $dbw->select(
				'user_groups',
				self::selectFields(),
				[ 'ug_expiry < ' . $dbw->addQuotes( $dbw->timestamp( $now ) ) ],
				__METHOD__,
				[ 'FOR UPDATE', 'LIMIT' => 100 ]
			);

			if ( $res->numRows() > 0 ) {
				$insertData = []; // array of users/groups to insert to user_former_groups
				$deleteCond = []; // array for deleting the rows that are to be moved around
				foreach ( $res as $row ) {
					$insertData[] = [ 'ufg_user' => $row->ug_user, 'ufg_group' => $row->ug_group ];
					$deleteCond[] = $dbw->makeList(
						[ 'ug_user' => $row->ug_user, 'ug_group' => $row->ug_group ],
						$dbw::LIST_AND
					);
				}
				// Delete the rows we're about to move
				$dbw->delete(
					'user_groups',
					$dbw->makeList( $deleteCond, $dbw::LIST_OR ),
					__METHOD__
				);
				// Push the groups to user_former_groups
				$dbw->insert( 'user_former_groups', $insertData, __METHOD__, [ 'IGNORE' ] );
				// Count how many rows were purged
				$purgedRows += $res->numRows();
			}

			$dbw->endAtomic( __METHOD__ );

			$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
		} while ( $res->numRows() > 0 );
		return $purgedRows;
	}

	/**
	 * Returns UserGroupMembership objects for all the groups a user currently
	 * belongs to.
	 *
	 * @param int $userId ID of the user to search for
	 * @param IDatabase|null $db Optional database connection
	 * @return UserGroupMembership[] Associative array of (group name => UserGroupMembership object)
	 */
	public static function getMembershipsForUser( $userId, IDatabase $db = null ) {
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
		ksort( $ugms );

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
		}
		return false;
	}

	/**
	 * Gets a link for a user group, possibly including the expiry date if relevant.
	 *
	 * @param string|UserGroupMembership $ugm Either a group name as a string, or
	 *   a UserGroupMembership object
	 * @param IContextSource $context
	 * @param string $format Either 'wiki' or 'html'
	 * @param string|null $userName If you want to use the group member message
	 *   ("administrator"), pass the name of the user who belongs to the group; it
	 *   is used for GENDER of the group member message. If you instead want the
	 *   group name message ("Administrators"), omit this parameter.
	 * @return string
	 */
	public static function getLink( $ugm, IContextSource $context, $format,
		$userName = null
	) {
		if ( $format !== 'wiki' && $format !== 'html' ) {
			throw new MWException( 'UserGroupMembership::getLink() $format parameter should be ' .
				"'wiki' or 'html'" );
		}

		if ( $ugm instanceof UserGroupMembership ) {
			$expiry = $ugm->getExpiry();
			$group = $ugm->getGroup();
		} else {
			$expiry = null;
			$group = $ugm;
		}

		if ( $userName !== null ) {
			$groupName = self::getGroupMemberName( $group, $userName );
		} else {
			$groupName = self::getGroupName( $group );
		}

		// link to the group description page, if it exists
		$linkTitle = self::getGroupPage( $group );
		if ( $linkTitle ) {
			if ( $format === 'wiki' ) {
				$linkPage = $linkTitle->getFullText();
				$groupLink = "[[$linkPage|$groupName]]";
			} else {
				$groupLink = Linker::link( $linkTitle, htmlspecialchars( $groupName ) );
			}
		} else {
			$groupLink = htmlspecialchars( $groupName );
		}

		if ( $expiry ) {
			// format the expiry to a nice string
			$uiLanguage = $context->getLanguage();
			$uiUser = $context->getUser();
			$expiryDT = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
			$expiryD = $uiLanguage->userDate( $expiry, $uiUser );
			$expiryT = $uiLanguage->userTime( $expiry, $uiUser );
			if ( $format === 'html' ) {
				$groupLink = Message::rawParam( $groupLink );
			}
			return $context->msg( 'group-membership-link-with-expiry' )
				->params( $groupLink, $expiryDT, $expiryD, $expiryT )->text();
		}
		return $groupLink;
	}

	/**
	 * Gets the localized friendly name for a group, if it exists. For example,
	 * "Administrators" or "Bureaucrats"
	 *
	 * @param string $group Internal group name
	 * @return string Localized friendly group name
	 */
	public static function getGroupName( $group ) {
		$msg = wfMessage( "group-$group" );
		return $msg->isBlank() ? $group : $msg->text();
	}

	/**
	 * Gets the localized name for a member of a group, if it exists. For example,
	 * "administrator" or "bureaucrat"
	 *
	 * @param string $group Internal group name
	 * @param string $username Username for gender
	 * @return string Localized name for group member
	 */
	public static function getGroupMemberName( $group, $username ) {
		$msg = wfMessage( "group-$group-member", $username );
		return $msg->isBlank() ? $group : $msg->text();
	}

	/**
	 * Gets the title of a page describing a particular user group. When the name
	 * of the group appears in the UI, it can link to this page.
	 *
	 * @param string $group Internal group name
	 * @return Title|bool Title of the page if it exists, false otherwise
	 */
	public static function getGroupPage( $group ) {
		$msg = wfMessage( "grouppage-$group" )->inContentLanguage();
		if ( $msg->exists() ) {
			$title = Title::newFromText( $msg->text() );
			if ( is_object( $title ) ) {
				return $title;
			}
		}
		return false;
	}
}
