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

use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterTypeException;
use Wikimedia\Rdbms\IDatabase;

/**
 * Represents a "user group membership" -- a specific instance of a user belonging
 * to a group. For example, the fact that user Mary belongs to the sysop group is a
 * user group membership.
 *
 * The class is a pure value object. Use UserGroupManager to modify user group memberships.
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

	/** @var bool Expiration flag */
	private $expired;

	/**
	 * @param int $userId The ID of the user who belongs to the group
	 * @param string|null $group The internal group name
	 * @param string|null $expiry Timestamp of expiry in TS_MW format, or null if no expiry
	 */
	public function __construct( $userId = 0, $group = null, $expiry = null ) {
		self::assertValidSpec( $userId, $group, $expiry );
		$this->userId = (int)$userId;
		$this->group = $group;
		$this->expiry = $expiry ?: null;
		$this->expired = $expiry ? wfTimestampNow() > $expiry : false;
	}

	/**
	 * Asserts that the given parameters could be used to construct a UserGroupMembership object
	 *
	 * @param int $userId
	 * @param string|null $group
	 * @param string|null $expiry
	 *
	 * @throws ParameterTypeException
	 */
	private static function assertValidSpec( $userId, $group, $expiry ) {
		Assert::parameterType( 'integer', $userId, '$userId' );
		Assert::parameterType( [ 'string', 'null' ], $group, '$group' );
		Assert::parameterType( [ 'string', 'null' ], $expiry, '$expiry' );
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

	/**
	 * @deprecated since 1.35
	 * @param $row
	 */
	protected function initFromRow( $row ) {
		wfDeprecated( __METHOD__, '1.35' );
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
	 *
	 * @deprecated since 1.35, use UserGroupMembership constructor instead
	 */
	public static function newFromRow( $row ) {
		wfDeprecated( __METHOD__, '1.35' );
		return new self(
			(int)$row->ug_user,
			$row->ug_group,
			$row->ug_expiry === null ? null : wfTimestamp( TS_MW, $row->ug_expiry )
		);
	}

	/**
	 * Returns the list of user_groups fields that should be selected to create
	 * a new user group membership.
	 * @return array
	 *
	 * @deprecated since 1.35, use UserGroupManager::getQueryInfo instead
	 */
	public static function selectFields() {
		wfDeprecated( __METHOD__, '1.35' );
		return MediaWikiServices::getInstance()->getUserGroupManager()->getQueryInfo()['fields'];
	}

	/**
	 * Delete the row from the user_groups table.
	 *
	 * @throws MWException
	 * @param IDatabase|null $dbw Optional master database connection to use
	 * @return bool Whether or not anything was deleted
	 *
	 * @deprecated since 1.35, use UserGroupManager::removeUserFromGroup instead
	 */
	public function delete( IDatabase $dbw = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->removeUserFromGroup(
				// TODO: we're forced to forge a User instance here because we don't have
				// a username around to create an artificial UserIdentityValue
				// and the username is being used down the tree. This will be gone once the
				// deprecated method is removed
				User::newFromId( $this->getUserId() ),
				$this->group
			);
	}

	/**
	 * Insert a user right membership into the database. When $allowUpdate is false,
	 * the function fails if there is a conflicting membership entry (same user and
	 * group) already in the table.
	 *
	 * @throws UnexpectedValueException
	 * @param bool $allowUpdate Whether to perform "upsert" instead of INSERT
	 * @param IDatabase|null $dbw If you have one available
	 * @return bool Whether or not anything was inserted
	 *
	 * @deprecated since 1.35, use UserGroupManager::addUserToGroup instead
	 */
	public function insert( $allowUpdate = false, IDatabase $dbw = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->addUserToGroup(
				// TODO: we're forced to forge a User instance here because we don't have
				// a username around to create an artificial UserIdentityValue
				// and the username is being used down the tree. This will be gone once the
				// deprecated method is removed
				User::newFromId( $this->getUserId() ),
				$this->group,
				$this->expiry,
				$allowUpdate
			);
	}

	/**
	 * Has the membership expired?
	 *
	 * @return bool
	 */
	public function isExpired() {
		return $this->expired;
	}

	/**
	 * Purge expired memberships from the user_groups table
	 *
	 * @return int|bool false if purging wasn't attempted (e.g. because of
	 *  readonly), the number of rows purged (might be 0) otherwise
	 *
	 * @deprecated since 1.35, use UserGroupManager::purgeExpired instead
	 */
	public static function purgeExpired() {
		wfDeprecated( __METHOD__, '1.35' );
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->purgeExpired();
	}

	/**
	 * Returns UserGroupMembership objects for all the groups a user currently
	 * belongs to.
	 *
	 * @param int $userId ID of the user to search for
	 * @param IDatabase|null $db unused since 1.35
	 * @return UserGroupMembership[] Associative array of (group name => UserGroupMembership object)
	 *
	 * @deprecated since 1.35, use UserGroupManager::getUserGroupMemberships instead
	 */
	public static function getMembershipsForUser( $userId, IDatabase $db = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserGroupMemberships(
				// TODO: we're forced to forge a User instance here because we don't have
				// a username around to create an artificial UserIdentityValue
				// and the username is being used down the tree. This will be gone once the
				// deprecated method is removed
				User::newFromId( $userId )
			);
	}

	/**
	 * Returns a UserGroupMembership object that pertains to the given user and group,
	 * or false if the user does not belong to that group (or the assignment has
	 * expired).
	 *
	 * @param int $userId ID of the user to search for
	 * @param string $group User group name
	 * @param IDatabase|null $db unused since 1.35
	 * @return UserGroupMembership|false
	 *
	 * @deprecated since 1.35, use UserGroupManager::getUserGroupMemberships instead
	 */
	public static function getMembership( $userId, $group, IDatabase $db = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		$ugms = MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserGroupMemberships(
				// TODO: we're forced to forge a User instance here because we don't have
				// a username around to create an artificial UserIdentityValue
				// and the username is being used down the tree. This will be gone once the
				// deprecated method is removed
				User::newFromId( $userId )
			);
		return $ugms[$group] ?? false;
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
	public static function getLink( $ugm, IContextSource $context, $format, $userName = null ) {
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
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( $format === 'wiki' ) {
			if ( $linkTitle ) {
				$linkPage = $linkTitle->getFullText();
				$groupLink = "[[$linkPage|$groupName]]";
			} else {
				$groupLink = $groupName;
			}
		} else {
			if ( $linkTitle ) {
				$groupLink = $linkRenderer->makeLink( $linkTitle, $groupName );
			} else {
				$groupLink = htmlspecialchars( $groupName );
			}
		}

		if ( $expiry ) {
			// format the expiry to a nice string
			$uiLanguage = $context->getLanguage();
			$uiUser = $context->getUser();
			$expiryDT = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
			$expiryD = $uiLanguage->userDate( $expiry, $uiUser );
			$expiryT = $uiLanguage->userTime( $expiry, $uiUser );

			if ( $format === 'wiki' ) {
				return $context->msg( 'group-membership-link-with-expiry' )
					->params( $groupLink, $expiryDT, $expiryD, $expiryT )->text();
			} else {
				$groupLink = Message::rawParam( $groupLink );
				return $context->msg( 'group-membership-link-with-expiry' )
					->params( $groupLink, $expiryDT, $expiryD, $expiryT )->escaped();
			}
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

	/**
	 * Compares two pure value objects
	 *
	 * @param UserGroupMembership $ugm
	 * @return bool
	 *
	 * @since 1.35
	 */
	public function equals( UserGroupMembership $ugm ) {
		return (
			$ugm->getUserId() === $this->userId
			&& $ugm->getGroup() === $this->group
		);
	}

}
