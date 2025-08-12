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

namespace MediaWiki\Auth;

/**
 * Authentication providers are used by AuthManager when authenticating users.
 *
 * This interface should not be implemented directly; use one of its children.
 *
 * Authentication providers can be registered via $wgAuthManagerAutoConfig.
 *
 * @ingroup Auth
 * @since 1.27
 */
interface AuthenticationProvider {

	/**
	 * Return a unique identifier for this instance
	 *
	 * This must be the same across requests. If multiple instances return the
	 * same ID, exceptions will be thrown from AuthManager.
	 *
	 * @return string
	 */
	public function getUniqueId();

	/**
	 * Return the applicable list of AuthenticationRequests
	 *
	 * Possible values for $action depend on whether the implementing class is
	 * also a PreAuthenticationProvider, PrimaryAuthenticationProvider, or
	 * SecondaryAuthenticationProvider.
	 *  - ACTION_LOGIN: Valid for passing to beginAuthentication. Called on all
	 *    providers.
	 *  - ACTION_CREATE: Valid for passing to beginAccountCreation. Called on
	 *    all providers.
	 *  - ACTION_LINK: Valid for passing to beginAccountLink. Called on linking
	 *    primary providers only.
	 *  - ACTION_CHANGE: Valid for passing to AuthManager::changeAuthenticationData
	 *    to change credentials. Called on primary and secondary providers.
	 *  - ACTION_REMOVE: Valid for passing to AuthManager::changeAuthenticationData
	 *    to remove credentials. Must work without additional user input (i.e.
	 *    without calling loadFromSubmission). Called on primary and secondary
	 *    providers.
	 *
	 * @see AuthManager::getAuthenticationRequests()
	 * @param string $action
	 * @param array $options Options are:
	 *  - username: Username related to the action, or null/unset if anon.
	 *    - ACTION_LOGIN: The currently logged-in user, if any.
	 *    - ACTION_CREATE: The account creator, if non-anonymous.
	 *    - ACTION_LINK: The local user being linked to.
	 *    - ACTION_CHANGE: The user having data changed.
	 *    - ACTION_REMOVE: The user having data removed.
	 *    If you leave the username property of the returned requests empty, this
	 *    will automatically be copied there (except for ACTION_CREATE and
	 *    ACTION_LOGIN).
	 * @return AuthenticationRequest[]
	 */
	public function getAuthenticationRequests( $action, array $options );

}
