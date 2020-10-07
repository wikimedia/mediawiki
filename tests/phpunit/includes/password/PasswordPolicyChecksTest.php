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

class PasswordPolicyChecksTest extends MediaWikiIntegrationTestCase {

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
			$statusShort->isOK(),
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
			$statusShort->isOK(),
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
		$this->assertFalse( $statusLong->isOK(),
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
		$this->assertTrue( $statusLong->isOK(), 'Password matches username, not fatal' );
	}

	/**
	 * @covers PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername
	 */
	public function testCheckPasswordCannotBeSubstringInUsername() {
		$statusOK = PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername(
			1, // policy value
			User::newFromName( 'user' ), // User
			'password'  // password
		);
		$this->assertTrue( $statusOK->isGood(), 'Password is not a substring of username' );
		$statusLong = PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername(
			1, // policy value
			User::newFromName( '123user123' ), // User
			'user'  // password
		);
		$this->assertFalse( $statusLong->isGood(), 'Password is a substring of username' );
		$this->assertTrue( $statusLong->isOK(), 'Password is a substring of username, not fatal' );
	}

	/**
	 * @covers PasswordPolicyChecks::checkPasswordCannotMatchDefaults
	 * @dataProvider provideCheckPasswordCannotMatchDefaults
	 */
	public function testCheckPasswordCannotMatchDefaults(
		bool $failureExpected,
		bool $policyValue,
		string $username,
		string $password
	) {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( $username );
		/** @var User $user */

		$status = PasswordPolicyChecks::checkPasswordCannotMatchDefaults(
			$policyValue,
			$user,
			$password
		);

		if ( $failureExpected ) {
			$this->assertFalse( $status->isGood(), 'Password matches defaults list' );
			$this->assertTrue( $status->isOK(), 'Password matches default list, not fatal' );
			$this->assertTrue( $status->hasMessage( 'password-login-forbidden' ) );
		} else {
			$this->assertTrue( $status->isGood(), 'Password is not on defaults list' );
		}
	}

	public function provideCheckPasswordCannotMatchDefaults() {
		return [
			'Unique username and password' => [ false, true, 'Unique username', 'AUniquePassword' ],
			'Invalid combination' => [ true, true, 'Useruser1', 'Passpass1' ],
			'Invalid password' => [ true, true, 'Whatever username', 'ExamplePassword' ],
			'Uniques but no policy' => [ false, false, 'Unique username', 'AUniquePassword' ],
			'Invalid combination but no policy' => [ false, false, 'Useruser1', 'Passpass1' ],
			'Invalid password but no policy' => [ false, false, 'Whatever username', 'ExamplePassword' ],
		];
	}

	public static function provideCommonList() {
		return [
			[ false, 'testpass' ],
			[ false, 'password' ],
			[ false, '12345' ],
			[ true, 'DKn17egcA4' ],
			[ true, 'testwikijenkinspass' ],
		];
	}

	/**
	 * @covers PasswordPolicyChecks::checkPasswordNotInCommonList
	 * @dataProvider provideCommonList
	 */
	public function testCheckNotInCommonList( $expected, $password ) {
		$user = User::newFromName( 'username' );
		$status = PasswordPolicyChecks::checkPasswordNotInCommonList( true, $user, $password );
		$this->assertSame( $expected, $status->isGood() );
	}

	/**
	 * Verify that all password policy description messages actually exist.
	 * Messages used on Special:PasswordPolicies
	 * @coversNothing
	 */
	public function testPasswordPolicyDescriptionsExist() {
		global $wgPasswordPolicy;

		foreach ( array_keys( $wgPasswordPolicy['checks'] ) as $check ) {
			$msgKey = 'passwordpolicies-policy-' . strtolower( $check );
			$this->assertTrue(
				wfMessage( $msgKey )->useDatabase( false )->inLanguage( 'en' )->exists(),
				"Message '$msgKey' required by '$check' must exist"
			);
		}
	}
}
