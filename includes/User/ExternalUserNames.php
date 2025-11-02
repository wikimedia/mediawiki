<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Class to parse and build external user names
 * @since 1.31
 */
class ExternalUserNames {

	private string $usernamePrefix;
	private bool $assignKnownUsers;

	/**
	 * @var bool[]
	 */
	private $triedCreations = [];

	/**
	 * @param string $usernamePrefix Prefix to apply to unknown (and possibly also known) usernames
	 * @param bool $assignKnownUsers Whether to apply the prefix to usernames that exist locally
	 */
	public function __construct( $usernamePrefix, $assignKnownUsers ) {
		$this->usernamePrefix = rtrim( (string)$usernamePrefix, ':>' );
		$this->assignKnownUsers = (bool)$assignKnownUsers;
	}

	/**
	 * Get a target Title to link a username.
	 *
	 * @param string $userName Username to link
	 *
	 * @return null|Title
	 */
	public static function getUserLinkTitle( $userName ) {
		$pos = strpos( $userName, '>' );
		$services = MediaWikiServices::getInstance();
		if ( $pos !== false ) {
			$iw = explode( ':', substr( $userName, 0, $pos ) );
			$firstIw = array_shift( $iw );
			$interwikiLookup = $services->getInterwikiLookup();
			if ( $interwikiLookup->isValidInterwiki( $firstIw ) ) {
				$title = $services->getNamespaceInfo()->getCanonicalName( NS_USER ) .
					':' . substr( $userName, $pos + 1 );
				if ( $iw ) {
					$title = implode( ':', $iw ) . ':' . $title;
				}
				return Title::makeTitle( NS_MAIN, $title, '', $firstIw );
			}
			return null;
		} else {
			// Protect against invalid user names from old corrupt database rows, T232451
			if (
				$services->getUserNameUtils()->isIP( $userName )
				|| $services->getUserNameUtils()->isValidIPRange( $userName )
				|| $services->getUserNameUtils()->isValid( $userName )
			) {
				return SpecialPage::getTitleFor( 'Contributions', $userName );
			} else {
				// Bad user name, no link
				return null;
			}
		}
	}

	/**
	 * Add an interwiki prefix to the username, if appropriate
	 *
	 * This method does have a side-effect on SUL (single user login) wikis: Calling this calls the
	 * ImportHandleUnknownUser hook from the CentralAuth extension, which assigns a local ID to the
	 * global user name, if possible. No prefix is applied if this is successful.
	 *
	 * @see https://meta.wikimedia.org/wiki/Help:Unified_login
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ImportHandleUnknownUser
	 *
	 * @param string $name
	 * @return string Either the unchanged username if it's a known local user (or not a valid
	 *  username), otherwise the name with the prefix prepended.
	 */
	public function applyPrefix( $name ) {
		$services = MediaWikiServices::getInstance();
		$userNameUtils = $services->getUserNameUtils();
		if ( $userNameUtils->getCanonical( $name, UserRigorOptions::RIGOR_USABLE ) === false ) {
			return $name;
		}

		if ( $this->assignKnownUsers ) {
			$userIdentityLookup = $services->getUserIdentityLookup();
			$userIdentity = $userIdentityLookup->getUserIdentityByName( $name );
			if ( $userIdentity && $userIdentity->isRegistered() ) {
				return $name;
			}

			// See if any extension wants to create it.
			if ( !isset( $this->triedCreations[$name] ) ) {
				$this->triedCreations[$name] = true;
				if ( !( new HookRunner( $services->getHookContainer() ) )->onImportHandleUnknownUser( $name ) ) {
					$userIdentity = $userIdentityLookup->getUserIdentityByName( $name, IDBAccessObject::READ_LATEST );
					if ( $userIdentity && $userIdentity->isRegistered() ) {
						return $name;
					}
				}
			}
		}

		return $this->addPrefix( $name );
	}

	/**
	 * Add an interwiki prefix to the username regardless of circumstances
	 *
	 * @param string $name
	 * @return string Prefixed username, using ">" as separator
	 */
	public function addPrefix( $name ) {
		return substr( $this->usernamePrefix . '>' . $name, 0, 255 );
	}

	/**
	 * Tells whether the username is external or not
	 *
	 * @param string $username Username to check
	 * @return bool true if it's external, false otherwise.
	 */
	public static function isExternal( $username ) {
		return str_contains( $username, '>' );
	}

	/**
	 * Get local part of the user name
	 *
	 * @param string $username Username to get
	 * @return string
	 */
	public static function getLocal( $username ) {
		if ( !self::isExternal( $username ) ) {
			return $username;
		}

		return substr( $username, strpos( $username, '>' ) + 1 );
	}

}

/** @deprecated class alias since 1.41 */
class_alias( ExternalUserNames::class, 'ExternalUserNames' );
