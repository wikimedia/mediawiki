<?php
/**
 * Testing password-policy check functions
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

/**
 * See also the unit tests at \MediaWiki\Tests\Unit\PasswordPolicyChecksTest
 */
class PasswordPolicyChecksTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers PasswordPolicyChecks::checkPasswordCannotMatchUsername
	 *
	 * Uses MediaWikiServices for the content language, so can't move to unit tests
	 */
	public function testCheckPasswordCannotMatchUsername() {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( 'user' );

		$statusOK = PasswordPolicyChecks::checkPasswordCannotMatchUsername(
			1, // policy value
			$user, // User
			'password'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password does not match username' );
		$statusLong = PasswordPolicyChecks::checkPasswordCannotMatchUsername(
			1, // policy value
			$user, // User
			'user'  // password
		);
		$this->assertFalse( $statusLong->isGood(), 'Password matches username' );
		$this->assertTrue( $statusLong->isOK(), 'Password matches username, not fatal' );
	}
}
