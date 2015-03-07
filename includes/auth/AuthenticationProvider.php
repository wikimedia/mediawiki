<?php
/**
 * Authentication provider interface
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
 * @ingroup Auth
 */

/**
 * An AuthenticationProvider is used by AuthManager when authenticating users.
 * @ingroup Auth
 * @since 1.25
 */
interface AuthenticationProvider {

	/**
	 * Return the applicable list of AuthenticationRequests
	 *
	 * Possible values for $which:
	 *  - login: Valid for passing to beginAuthentication
	 *  - login-continue: Valid for passing to continueAuthentication in the current state
	 *  - create: Valid for passing to beginAccountCreation
	 *  - create-continue: Valid for passing to continueAccountCreation
	 *  - all: All possible
	 *
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which );

}
