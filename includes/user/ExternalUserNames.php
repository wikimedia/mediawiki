<?php
/**
 * Class to parse and build external user names
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

/**
 * Class to parse and build external user names
 * @since 1.31
 */
class ExternalUserNames {

	/**
	 * @var string
	 */
	private $usernamePrefix = 'imported';

	/**
	 * @var bool
	 */
	private $assignKnownUsers = false;

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
		if ( $pos !== false ) {
			$iw = explode( ':', substr( $userName, 0, $pos ) );
			$firstIw = array_shift( $iw );
			$services = MediaWikiServices::getInstance();
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
			return SpecialPage::getTitleFor( 'Contributions', $userName );
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
		if ( !User::isUsableName( $name ) ) {
			return $name;
		}

		if ( $this->assignKnownUsers ) {
			if ( User::idFromName( $name ) ) {
				return $name;
			}

			// See if any extension wants to create it.
			if ( !isset( $this->triedCreations[$name] ) ) {
				$this->triedCreations[$name] = true;
				if ( !Hooks::run( 'ImportHandleUnknownUser', [ $name ] ) &&
					User::idFromName( $name, User::READ_LATEST )
				) {
					return $name;
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
		return strpos( $username, '>' ) !== false;
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
