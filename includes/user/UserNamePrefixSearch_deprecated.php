<?php
/**
 * Prefix search of user names.
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
use MediaWiki\User\User;

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch
/**
 * Handles searching prefixes of user names
 *
 * @note There are two classes called UserNamePrefixSearch.  You should use the first one, in
 * namespace MediaWiki\User, which is a service.  \UserNamePrefixSearch is a deprecated static wrapper
 * that forwards to the global service.
 *
 * @deprecated since 1.36, use the MediaWiki\User\UserNamePrefixSearch service; hard deprecated
 *   since 1.41
 *
 * @since 1.27
 */
class UserNamePrefixSearch {

	/**
	 * Do a prefix search of user names and return a list of matching user names.
	 *
	 * @deprecated since 1.36, use the MediaWiki\User\UserNamePrefixSearch service instead; hard
	 *   deprecated since 1.41
	 *
	 * @param string|User $audience The string 'public' or a user object to show the search for
	 * @param string $search
	 * @param int $limit
	 * @param int $offset How many results to offset from the beginning
	 * @return string[]
	 */
	public static function search( $audience, $search, $limit, $offset = 0 ) {
		wfDeprecated( __METHOD__, '1.36' );
		return MediaWikiServices::getInstance()
			->getUserNamePrefixSearch()
			->search( $audience, (string)$search, (int)$limit, (int)$offset );
	}
}
