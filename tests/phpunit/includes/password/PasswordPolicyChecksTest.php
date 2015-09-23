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

class PasswordPolicyChecksTest extends MediaWikiTestCase {

	/**
	 * @covers PasswordPolicyChecks::checkMinimalPasswordLength
	 */
	public function testCheckMinimalPasswordLength() {
		$statusOK = PasswordPolicyChecks::checkMinimalPasswordLength(
			3, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password is longer than minimal policy' );
		$statusShort = PasswordPolicyChecks::checkMinimalPasswordLength(
			10, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertFalse(
			$statusShort->isGood(),
			'Password is shorter than minimal policy'
		);
		$this->assertTrue(
			$statusShort->isOk(),
			'Password is shorter than minimal policy, not fatal'
		);
	}

	/**
	 * @covers PasswordPolicyChecks::checkMinimumPasswordLengthToLogin
	 */
	public function testCheckMinimumPasswordLengthToLogin() {
		$statusOK = PasswordPolicyChecks::checkMinimumPasswordLengthToLogin(
			3, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password is longer than minimal policy' );
		$statusShort = PasswordPolicyChecks::checkMinimumPasswordLengthToLogin(
			10, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertFalse(
			$statusShort->isGood(),
			'Password is shorter than minimum login policy'
		);
		$this->assertFalse(
			$statusShort->isOk(),
			'Password is shorter than minimum login policy, fatal'
		);
	}

	/**
	 * @covers PasswordPolicyChecks::checkMaximalPasswordLength
	 */
	public function testCheckMaximalPasswordLength() {
		$statusOK = PasswordPolicyChecks::checkMaximalPasswordLength(
			100, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password is shorter than maximal policy' );
		$statusLong = PasswordPolicyChecks::checkMaximalPasswordLength(
			4, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertFalse( $statusLong->isGood(),
			'Password is longer than maximal policy'
		);
		$this->assertFalse( $statusLong->isOk(),
			'Password is longer than maximal policy, fatal'
		);
	}

	/**
	 * @covers PasswordPolicyChecks::checkPasswordCannotMatchUsername
	 */
	public function testCheckPasswordCannotMatchUsername() {
		$statusOK = PasswordPolicyChecks::checkPasswordCannotMatchUsername(
			1, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password does not match username' );
		$statusLong = PasswordPolicyChecks::checkPasswordCannotMatchUsername(
			1, // policy value
			User::newFromName( 'user' ), // User
			'user'  // password
		);
		$this->assertFalse( $statusLong->isGood(), 'Password matches username' );
		$this->assertTrue( $statusLong->isOk(), 'Password matches username, not fatal' );
	}

	/**
	 * @covers PasswordPolicyChecks::checkPasswordCannotMatchBlacklist
	 */
	public function testCheckPasswordCannotMatchBlacklist() {
		$statusOK = PasswordPolicyChecks::checkPasswordCannotMatchBlacklist(
			true, // policy value
			User::newFromName( 'Username' ), // User
			'AUniquePassword'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password is not on blacklist' );
		$statusLong = PasswordPolicyChecks::checkPasswordCannotMatchBlacklist(
			true, // policy value
			User::newFromName( 'Useruser1' ), // User
			'Passpass1'  // password
		);
		$this->assertFalse( $statusLong->isGood(), 'Password matches blacklist' );
		$this->assertTrue( $statusLong->isOk(), 'Password matches blacklist, not fatal' );
	}

}
