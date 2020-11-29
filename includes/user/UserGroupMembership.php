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
	 * Has the membership expired?
	 *
	 * @return bool
	 */
	public function isExpired() {
		return $this->expired;
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
				// @phan-suppress-next-line SecurityCheck-XSS Okay for html format T183174
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
