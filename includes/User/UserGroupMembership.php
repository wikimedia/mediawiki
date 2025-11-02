<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use MediaWiki\Context\IContextSource;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;

/**
 * Represents the membership of one user in one user group.
 *
 * For example, if user "Mary" belongs to "sysop" and "bureaucrat" groups,
 * those memberships can be represented by two UserGroupMembership objects.
 *
 * The class is a value object. Use UserGroupManager to modify user group memberships.
 *
 * @since 1.29
 * @ingroup User
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
	public function __construct( int $userId = 0, ?string $group = null, ?string $expiry = null ) {
		$this->userId = $userId;
		$this->group = $group;
		$this->expiry = $expiry ?: null;
		$this->expired = $expiry && wfTimestampNow() > $expiry;
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
	 * @deprecated since 1.41 use getLinkWiki or getLinkHTML directly
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
	public static function getLink( $ugm, IContextSource $context, string $format, $userName = null ) {
		switch ( $format ) {
			case 'wiki':
				return self::getLinkWiki( $ugm, $context, $userName );
			case 'html':
				return self::getLinkHTML( $ugm, $context, $userName );
			default:
				throw new InvalidArgumentException( 'UserGroupMembership::getLink() $format parameter should be ' .
					"'wiki' or 'html'" );
		}
	}

	/**
	 * Gets a link for a user group, possibly including the expiry date if relevant.
	 * @since 1.41
	 *
	 * @param string|UserGroupMembership $ugm Either a group name as a string, or
	 *   a UserGroupMembership object
	 * @param IContextSource $context
	 * @param string|null $userName If you want to use the group member message
	 *   ("administrator"), pass the name of the user who belongs to the group; it
	 *   is used for GENDER of the group member message. If you instead want the
	 *   group name message ("Administrators"), omit this parameter.
	 * @return string
	 */
	public static function getLinkHTML( $ugm, IContextSource $context, $userName = null ): string {
		[
			'expiry' => $expiry,
			'linkTitle' => $linkTitle,
			'groupName' => $groupName
		] = self::getLinkInfo( $ugm, $context, $userName );

		// link to the group description page, if it exists
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( $linkTitle ) {
			$groupLink = $linkRenderer->makeLink( $linkTitle, $groupName );
		} else {
			$groupLink = htmlspecialchars( $groupName );
		}

		if ( $expiry ) {
			[
				'expiryDT' => $expiryDT,
				'expiryD' => $expiryD,
				'expiryT' => $expiryT
			] = self::getLinkExpiryParams( $context, $expiry );
			$groupLink = Message::rawParam( $groupLink );
			return $context->msg( 'group-membership-link-with-expiry' )
				->params( $groupLink, $expiryDT, $expiryD, $expiryT )->escaped();
		}
		return $groupLink;
	}

	/**
	 * Gets a link for a user group, possibly including the expiry date if relevant.
	 * @since 1.41
	 *
	 * @param string|UserGroupMembership $ugm Either a group name as a string, or
	 *   a UserGroupMembership object
	 * @param IContextSource $context
	 * @param string|null $userName If you want to use the group member message
	 *   ("administrator"), pass the name of the user who belongs to the group; it
	 *   is used for GENDER of the group member message. If you instead want the
	 *   group name message ("Administrators"), omit this parameter.
	 * @return string
	 */
	public static function getLinkWiki( $ugm, IContextSource $context, $userName = null ): string {
		[
			'expiry' => $expiry,
			'linkTitle' => $linkTitle,
			'groupName' => $groupName
		] = self::getLinkInfo( $ugm, $context, $userName );

		// link to the group description page, if it exists
		if ( $linkTitle ) {
			$linkPage = $linkTitle->getFullText();
			$groupLink = "[[$linkPage|$groupName]]";
		} else {
			$groupLink = $groupName;
		}

		if ( $expiry ) {
			[
				'expiryDT' => $expiryDT,
				'expiryD' => $expiryD,
				'expiryT' => $expiryT
			] = self::getLinkExpiryParams( $context, $expiry );
			return $context->msg( 'group-membership-link-with-expiry' )
				->params( $groupLink, $expiryDT, $expiryD, $expiryT )->text();
		}
		return $groupLink;
	}

	/**
	 * @param self|string $ugm
	 * @param IContextSource $context
	 * @param string|null $userName
	 * @return array
	 */
	private static function getLinkInfo( $ugm, $context, $userName = null ): array {
		if ( $ugm instanceof UserGroupMembership ) {
			$expiry = $ugm->getExpiry();
			$group = $ugm->getGroup();
		} else {
			$expiry = null;
			$group = $ugm;
		}

		$uiLanguage = $context->getLanguage();
		if ( $userName !== null ) {
			$groupName = $uiLanguage->getGroupMemberName( $group, $userName );
		} else {
			$groupName = $uiLanguage->getGroupName( $group );
		}
		$linkTitle = self::getGroupPage( $group );
		return [ 'expiry' => $expiry, 'linkTitle' => $linkTitle, 'groupName' => $groupName ];
	}

	/**
	 * @param IContextSource $context
	 * @param string $expiry
	 * @return array
	 */
	private static function getLinkExpiryParams( IContextSource $context, string $expiry ): array {
		// format the expiry to a nice string
		$uiLanguage = $context->getLanguage();
		$uiUser = $context->getUser();
		$expiryDT = $uiLanguage->userTimeAndDate( $expiry, $uiUser );
		$expiryD = $uiLanguage->userDate( $expiry, $uiUser );
		$expiryT = $uiLanguage->userTime( $expiry, $uiUser );
		return [ 'expiryDT' => $expiryDT, 'expiryD' => $expiryD, 'expiryT' => $expiryT ];
	}

	/**
	 * Gets the title of a page describing a particular user group. When the name
	 * of the group appears in the UI, it can link to this page.
	 *
	 * @param string $group Internal group name
	 * @return Title|false Title of the page if it exists, false otherwise
	 */
	public static function getGroupPage( $group ) {
		$msg = wfMessage( "grouppage-$group" )->inContentLanguage();
		if ( $msg->exists() ) {
			$title = Title::newFromText( $msg->text() );
			if ( $title ) {
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

/** @deprecated class alias since 1.41 */
class_alias( UserGroupMembership::class, 'UserGroupMembership' );
