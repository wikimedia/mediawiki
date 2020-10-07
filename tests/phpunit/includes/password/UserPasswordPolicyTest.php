<?php
/**
 * Testing for password-policy enforcement, based on a user's groups.
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
 * @group Database
 * @covers UserPasswordPolicy
 */
class UserPasswordPolicyTest extends MediaWikiIntegrationTestCase {

	protected $tablesUsed = [ 'user', 'user_groups' ];

	protected $policies = [
		'checkuser' => [
			'MinimalPasswordLength' => [ 'value' => 10, 'forceChange' => true ],
			'MinimumPasswordLengthToLogin' => 6,
			'PasswordCannotMatchUsername' => true,
		],
		'sysop' => [
			'MinimalPasswordLength' => [ 'value' => 8, 'suggestChangeOnLogin' => true ],
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchUsername' => true,
		],
		'bureaucrat' => [
			'MinimalPasswordLength' => [
				'value' => 6,
				'suggestChangeOnLogin' => false,
				'forceChange' => true,
			],
			'PasswordCannotMatchUsername' => true,
		],
		'default' => [
			'MinimalPasswordLength' => 4,
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchDefaults' => true,
			'MaximalPasswordLength' => 4096,
			// test null handling
			'PasswordCannotMatchUsername' => null,
			'PasswordCannotBeSubstringInUsername' => true,
		],
	];

	protected $checks = [
		'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
		'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
		'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
		'PasswordCannotBeSubstringInUsername' =>
			'PasswordPolicyChecks::checkPasswordCannotBeSubstringInUsername',
		'PasswordCannotMatchDefaults' => 'PasswordPolicyChecks::checkPasswordCannotMatchDefaults',
		'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
	];

	private function getUserPasswordPolicy() {
		return new UserPasswordPolicy( $this->policies, $this->checks );
	}

	public function testGetPoliciesForUser() {
		$upp = $this->getUserPasswordPolicy();

		$user = $this->getTestUser( [ 'sysop' ] )->getUser();
		$this->assertArrayEquals(
			[
				'MinimalPasswordLength' => [ 'value' => 8, 'suggestChangeOnLogin' => true ],
				'MinimumPasswordLengthToLogin' => 1,
				'PasswordCannotMatchUsername' => true,
				'PasswordCannotBeSubstringInUsername' => true,
				'PasswordCannotMatchBlacklist' => true,
				'MaximalPasswordLength' => 4096,
			],
			$upp->getPoliciesForUser( $user )
		);

		$user = $this->getTestUser( [ 'sysop', 'checkuser' ] )->getUser();
		$this->assertArrayEquals(
			[
				'MinimalPasswordLength' => [
					'value' => 10,
					'forceChange' => true,
					'suggestChangeOnLogin' => true
				],
				'MinimumPasswordLengthToLogin' => 6,
				'PasswordCannotMatchUsername' => true,
				'PasswordCannotBeSubstringInUsername' => true,
				'PasswordCannotMatchBlacklist' => true,
				'MaximalPasswordLength' => 4096,
			],
			$upp->getPoliciesForUser( $user )
		);
	}

	public function testGetPoliciesForGroups() {
		$effective = UserPasswordPolicy::getPoliciesForGroups(
			$this->policies,
			[ 'user', 'checkuser', 'sysop' ],
			$this->policies['default']
		);

		$this->assertArrayEquals(
			[
				'MinimalPasswordLength' => [
					'value' => 10,
					'forceChange' => true,
					'suggestChangeOnLogin' => true
				],
				'MinimumPasswordLengthToLogin' => 6,
				'PasswordCannotMatchUsername' => true,
				'PasswordCannotBeSubstringInUsername' => true,
				'PasswordCannotMatchBlacklist' => true,
				'MaximalPasswordLength' => 4096,
			],
			$effective
		);
	}

	/**
	 * @dataProvider provideCheckUserPassword
	 */
	public function testCheckUserPassword( $groups, $password, StatusValue $expectedStatus ) {
		$upp = $this->getUserPasswordPolicy();
		$user = $this->getTestUser( $groups )->getUser();

		$status = $upp->checkUserPassword( $user, $password );
		$this->assertSame( $expectedStatus->isGood(), $status->isGood(), 'password valid' );
		$this->assertSame( $expectedStatus->isOK(), $status->isOK(), 'can login' );
		$this->assertSame( $expectedStatus->getValue(), $status->getValue(), 'flags' );
	}

	public function provideCheckUserPassword() {
		$success = Status::newGood( [] );
		$warning = Status::newGood( [] );
		$forceChange = Status::newGood( [ 'forceChange' => true ] );
		$suggestChangeOnLogin = Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$fatal = Status::newGood( [] );

		// the message does not matter, we only test for state and value
		$warning->warning( 'invalid-password' );
		$forceChange->warning( 'invalid-password' );
		$suggestChangeOnLogin->warning( 'invalid-password' );
		$warning->warning( 'invalid-password' );
		$fatal->fatal( 'invalid-password' );

		return [
			'No groups, default policy, password too short to login' => [
				[],
				'',
				$fatal,
			],
			'Default policy, short password' => [
				[ 'user' ],
				'aaa',
				$warning,
			],
			'Sysop with good password' => [
				[ 'sysop' ],
				'abcdabcdabcd',
				$success,
			],
			'Sysop with short password and suggestChangeOnLogin set to true' => [
				[ 'sysop' ],
				'abcd',
				$suggestChangeOnLogin,
			],
			'Checkuser with short password' => [
				[ 'checkuser' ],
				'abcdabcd',
				$forceChange,
			],
			'Bureaucrat bad password with forceChange true, suggestChangeOnLogin false' => [
				[ 'bureaucrat' ],
				'short',
				$forceChange,
			],
			'Checkuser with too short password to login' => [
				[ 'sysop', 'checkuser' ],
				'abcd',
				$fatal,
			],
		];
	}

	public function testCheckUserPassword_blacklist() {
		$upp = $this->getUserPasswordPolicy();
		$user = User::newFromName( 'Useruser' );
		$user->addToDatabase();

		$status = $upp->checkUserPassword( $user, 'Passpass' );
		$this->assertFalse( $status->isGood(), 'password invalid' );
		$this->assertTrue( $status->isOK(), 'can login' );
	}

	/**
	 * @dataProvider provideMaxOfPolicies
	 */
	public function testMaxOfPolicies( $p1, $p2, $max ) {
		$this->assertArrayEquals(
			$max,
			UserPasswordPolicy::maxOfPolicies( $p1, $p2 )
		);
	}

	public function provideMaxOfPolicies() {
		return [
			'Basic max in p1' => [
				[ 'MinimalPasswordLength' => 8 ], // p1
				[ 'MinimalPasswordLength' => 2 ], // p2
				[ 'MinimalPasswordLength' => 8 ], // max
			],
			'Basic max in p2' => [
				[ 'MinimalPasswordLength' => 2 ], // p1
				[ 'MinimalPasswordLength' => 8 ], // p2
				[ 'MinimalPasswordLength' => 8 ], // max
			],
			'Missing items in p1' => [
				[
					'MinimalPasswordLength' => 8,
				], // p1
				[
					'MinimalPasswordLength' => 2,
					'PasswordCannotMatchUsername' => 1,
					'PasswordCannotBeSubstringInUsername' => 1,
				], // p2
				[
					'MinimalPasswordLength' => 8,
					'PasswordCannotMatchUsername' => 1,
					'PasswordCannotBeSubstringInUsername' => 1,
				], // max
			],
			'Missing items in p2' => [
				[
					'MinimalPasswordLength' => 8,
					'PasswordCannotMatchUsername' => 1,
					'PasswordCannotBeSubstringInUsername' => 1,
				], // p1
				[
					'MinimalPasswordLength' => 2,
				], // p2
				[
					'MinimalPasswordLength' => 8,
					'PasswordCannotMatchUsername' => 1,
					'PasswordCannotBeSubstringInUsername' => 1,
				], // max
			],
			'complex value in p1' => [
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
					],
				], // p1
				[
					'MinimalPasswordLength' => 2,
				], // p2
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
					],
				], // max
			],
			'complex value in p2' => [
				[
					'MinimalPasswordLength' => 8,
				], // p1
				[
					'MinimalPasswordLength' => [
						'value' => 2,
						'foo' => 1,
					],
				], // p2
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
					],
				], // max
			],
			'complex value in both p1 and p2' => [
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
						'baz' => false,
					],
				], // p1
				[
					'MinimalPasswordLength' => [
						'value' => 2,
						'bar' => 2,
						'baz' => true,
					],
				], // p2
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
						'bar' => 2,
						'baz' => true,
					],
				], // max
			],
			'complex value in both p1 and p2 #2' => [
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
						'baz' => false,
					],
				], // p1
				[
					'MinimalPasswordLength' => [
						'value' => 2,
						'bar' => true
					],
				], // p2
				[
					'MinimalPasswordLength' => [
						'value' => 8,
						'foo' => 1,
						'bar' => true,
						'baz' => false,
					],
				], // max
			],
		];
	}

}
