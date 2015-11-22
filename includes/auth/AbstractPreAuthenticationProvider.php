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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

/**
 * A base class that implements some of the boilerplate for a PreAuthenticationProvider
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractPreAuthenticationProvider extends AbstractAuthenticationProvider
	implements PreAuthenticationProvider
{

	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	public function testForAuthentication( array $reqs ) {
		return \StatusValue::newGood();
	}

	public function postAuthentication( $user, AuthenticationResponse $response ) {
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return \StatusValue::newGood();
	}

	public function testUserForCreation( $user, $autocreate ) {
		return \StatusValue::newGood();
	}

	public function postAccountCreation( $user, $creator, AuthenticationResponse $response ) {
	}

	public function testForAccountLink( $user ) {
		return \StatusValue::newGood();
	}

	public function postAccountLink( $user, AuthenticationResponse $response ) {
	}

}
