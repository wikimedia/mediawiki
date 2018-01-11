<?php

use MediaWiki\MediaWikiServices;

/**
 * Class to parser and build external user name
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

class ExternalUsernameHandler {
	private $usernamePrefix = 'imported';
	private $assignKnownUsers = false;
	private $triedCreations = [];

	public static function getExternalUsernameTitle( $userName ) {
		$pos = strpos( $userName, '>' );
		if ( $pos !== false ) {
			$iw = explode( ':', substr( $userName, 0, $pos ) );
			$firstIw = array_shift( $iw );
			$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
			if ( $interwikiLookup->isValidInterwiki( $firstIw ) ) {
				$title = MWNamespace::getCanonicalName( NS_USER ) . ':' . substr( $userName, $pos + 1 );
				if ( $iw ) {
					$title = join( ':', $iw ) . ':' . $title;
				}
				return Title::makeTitle( NS_MAIN, $title, '', $firstIw );
			}
			return null;
		} else {
			return SpecialPage::getTitleFor( 'Contributions', $userName );
		}
	}

	/**
	 * @since 1.31
	 * @param string $usernamePrefix Prefix to apply to unknown (and possibly also known) usernames
	 * @param bool $assignKnownUsers Whether to apply the prefix to usernames that exist locally
	 */
	public function setUsernamePrefix( $usernamePrefix, $assignKnownUsers ) {
		$this->usernamePrefix = rtrim( (string)$usernamePrefix, ':>' );
		$this->assignKnownUsers = (bool)$assignKnownUsers;
	}

	/**
	 * Add an interwiki prefix to the username, if appropriate
	 * @since 1.31
	 * @param string $name Name being imported
	 * @return string Name, possibly with the prefix prepended.
	 */
	public function prefixUsername( $name ) {
		if ( !User::isUsableName( $name ) ) {
			return $name;
		}

		if ( $this->assignKnownUsers ) {
			if ( User::idFromName( $name ) ) {
				return $name;
			}

			$name = $this->tryCreatingUser( $name );

		}

		return substr( $this->usernamePrefix . '>' . $name, 0, 255 );
	}

	private function tryCreatingUser( $name ) {
		if ( !isset( $this->triedCreations[$name] ) ) {
			$this->triedCreations[$name] = true;
			if ( !Hooks::run( 'ImportHandleUnknownUser', [ $name ] ) &&
			     User::idFromName( $name, User::READ_LATEST )
			) {
				return $name;
			}
		}

		return $name;
	}
}