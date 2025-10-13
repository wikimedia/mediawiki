<?php

/**
 * Testing password-policy check functions
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit;

use MediaWiki\Password\PasswordPolicyChecks;
use MediaWiki\User\User;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Split from \PasswordPolicyChecksTest integration tests
 *
 * @covers \MediaWiki\Password\PasswordPolicyChecks
 */
class PasswordPolicyChecksTest extends MediaWikiUnitTestCase {

	/**
	 * @param string $name
	 * @return User|MockObject
	 */
	private function getUser( $name = 'user' ) {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( $name );
		return $user;
	}

	public function testCheckMinimalPasswordLength() {
		$statusOK = PasswordPolicyChecks::checkMinimalPasswordLength(
			3,
			$this->getUser(),
			'password'
		);
		$this->assertStatusGood( $statusOK, 'Password is longer than minimal policy' );
		$statusShort = PasswordPolicyChecks::checkMinimalPasswordLength(
			10,
			$this->getUser(),
			'password'
		);
		$this->assertStatusWarning( 'passwordtooshort', $statusShort,
			'Password is shorter than minimal policy, not fatal'
		);
	}

	public function testCheckMinimumPasswordLengthToLogin() {
		$statusOK = PasswordPolicyChecks::checkMinimumPasswordLengthToLogin(
			3,
			$this->getUser(),
			'password'
		);
		$this->assertStatusGood( $statusOK, 'Password is longer than minimal policy' );
		$statusShort = PasswordPolicyChecks::checkMinimumPasswordLengthToLogin(
			10,
			$this->getUser(),
			'password'
		);
		$this->assertStatusError( 'passwordtooshort', $statusShort,
			'Password is shorter than minimum login policy, fatal'
		);
	}

	public function testCheckMaximalPasswordLength() {
		$statusOK = PasswordPolicyChecks::checkMaximalPasswordLength(
			100,
			$this->getUser(),
			'password'
		);
		$this->assertStatusGood( $statusOK, 'Password is shorter than maximal policy' );
		$statusLong = PasswordPolicyChecks::checkMaximalPasswordLength(
			4,
			$this->getUser(),
			'password'
		);
		$this->assertStatusError( 'passwordtoolong', $statusLong,
			'Password is longer than maximal policy, fatal'
		);
	}

	public function testCheckPasswordCannotBeSubstringInUsername() {
		$statusOK = PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername(
			1,
			$this->getUser(),
			'password'
		);
		$this->assertStatusGood( $statusOK, 'Password is not a substring of username' );
		$statusLong = PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername(
			1,
			$this->getUser( '123user123' ),
			'user'
		);
		$this->assertStatusWarning( 'password-substring-username-match', $statusLong,
			'Password is a substring of username, not fatal' );
	}

	/**
	 * @dataProvider provideCheckPasswordCannotMatchDefaults
	 */
	public function testCheckPasswordCannotMatchDefaults(
		bool $failureExpected,
		bool $policyValue,
		string $username,
		string $password
	) {
		$status = PasswordPolicyChecks::checkPasswordCannotMatchDefaults(
			$policyValue,
			$this->getUser( $username ),
			$password
		);

		if ( $failureExpected ) {
			$this->assertStatusWarning( 'password-login-forbidden', $status );
		} else {
			$this->assertStatusGood( $status, 'Password is not on defaults list' );
		}
	}

	public static function provideCheckPasswordCannotMatchDefaults() {
		return [
			'Unique username and password' => [ false, true, 'Unique username', 'AUniquePassword' ],
			'Invalid combination' => [ true, true, 'Useruser1', 'Passpass1' ],
			'Invalid password' => [ true, true, 'Whatever username', 'ExamplePassword' ],
			'Uniques but no policy' => [ false, false, 'Unique username', 'AUniquePassword' ],
			'Invalid combination but no policy' => [ false, false, 'Useruser1', 'Passpass1' ],
			'Invalid password but no policy' => [ false, false, 'Whatever username', 'ExamplePassword' ],
		];
	}

	/**
	 * @dataProvider provideCommonList
	 */
	public function testCheckNotInCommonList( $expected, $password ) {
		$status = PasswordPolicyChecks::checkPasswordNotInCommonList(
			true,
			$this->getUser( 'username' ),
			$password
		);
		$this->assertSame( $expected, $status->isGood() );
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
}
