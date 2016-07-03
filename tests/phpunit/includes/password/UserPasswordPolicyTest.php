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

class UserPasswordPolicyTest extends MediaWikiTestCase {

	protected $policies = [
		'checkuser' => [
			'MinimalPasswordLength' => 10,
			'MinimumPasswordLengthToLogin' => 6,
			'PasswordCannotMatchUsername' => true,
		],
		'sysop' => [
			'MinimalPasswordLength' => 8,
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchUsername' => true,
		],
		'default' => [
			'MinimalPasswordLength' => 4,
			'MinimumPasswordLengthToLogin' => 1,
			'PasswordCannotMatchBlacklist' => true,
			'MaximalPasswordLength' => 4096,
		],
	];

	protected $checks = [
		'MinimalPasswordLength' => 'PasswordPolicyChecks::checkMinimalPasswordLength',
		'MinimumPasswordLengthToLogin' => 'PasswordPolicyChecks::checkMinimumPasswordLengthToLogin',
		'PasswordCannotMatchUsername' => 'PasswordPolicyChecks::checkPasswordCannotMatchUsername',
		'PasswordCannotMatchBlacklist' => 'PasswordPolicyChecks::checkPasswordCannotMatchBlacklist',
		'MaximalPasswordLength' => 'PasswordPolicyChecks::checkMaximalPasswordLength',
	];

	private function getUserPasswordPolicy() {
		return new UserPasswordPolicy( $this->policies, $this->checks );
	}

	/**
	 * @covers UserPasswordPolicy::getPoliciesForUser
	 */
	public function testGetPoliciesForUser() {

		$upp = $this->getUserPasswordPolicy();

		$user = User::newFromName( 'TestUserPolicy' );
		$user->addGroup( 'sysop' );

		$this->assertArrayEquals(
			[
				'MinimalPasswordLength' => 8,
				'MinimumPasswordLengthToLogin' => 1,
				'PasswordCannotMatchUsername' => 1,
				'PasswordCannotMatchBlacklist' => true,
				'MaximalPasswordLength' => 4096,
			],
			$upp->getPoliciesForUser( $user )
		);
	}

	/**
	 * @covers UserPasswordPolicy::getPoliciesForGroups
	 */
	public function testGetPoliciesForGroups() {
		$effective = UserPasswordPolicy::getPoliciesForGroups(
			$this->policies,
			[ 'user', 'checkuser' ],
			$this->policies['default']
		);

		$this->assertArrayEquals(
			[
				'MinimalPasswordLength' => 10,
				'MinimumPasswordLengthToLogin' => 6,
				'PasswordCannotMatchUsername' => true,
				'PasswordCannotMatchBlacklist' => true,
				'MaximalPasswordLength' => 4096,
			],
			$effective
		);
	}

	/**
	 * @dataProvider provideCheckUserPassword
	 * @covers UserPasswordPolicy::checkUserPassword
	 */
	public function testCheckUserPassword( $username, $groups, $password, $valid, $ok, $msg ) {

		$upp = $this->getUserPasswordPolicy();

		$user = User::newFromName( $username );
		foreach ( $groups as $group ) {
			$user->addGroup( $group );
		}

		$status = $upp->checkUserPassword( $user, $password );
		$this->assertSame( $valid, $status->isGood(), $msg . ' - password valid' );
		$this->assertSame( $ok, $status->isOK(), $msg . ' - can login' );
	}

	public function provideCheckUserPassword() {
		return [
			[
				'PassPolicyUser',
				[],
				'',
				false,
				false,
				'No groups, default policy, password too short to login'
			],
			[
				'PassPolicyUser',
				[ 'user' ],
				'aaa',
				false,
				true,
				'Default policy, short password'
			],
			[
				'PassPolicyUser',
				[ 'sysop' ],
				'abcdabcdabcd',
				true,
				true,
				'Sysop with good password'
			],
			[
				'PassPolicyUser',
				[ 'sysop' ],
				'abcd',
				false,
				true,
				'Sysop with short password'
			],
			[
				'PassPolicyUser',
				[ 'sysop', 'checkuser' ],
				'abcdabcd',
				false,
				true,
				'Checkuser with short password'
			],
			[
				'PassPolicyUser',
				[ 'sysop', 'checkuser' ],
				'abcd',
				false,
				false,
				'Checkuser with too short password to login'
			],
			[
				'Useruser',
				[ 'user' ],
				'Passpass',
				false,
				true,
				'Username & password on blacklist'
			],
		];
	}

	/**
	 * @dataProvider provideMaxOfPolicies
	 * @covers UserPasswordPolicy::maxOfPolicies
	 */
	public function testMaxOfPolicies( $p1, $p2, $max, $msg ) {
		$this->assertArrayEquals(
			$max,
			UserPasswordPolicy::maxOfPolicies( $p1, $p2 ),
			$msg
		);
	}

	public function provideMaxOfPolicies() {
		return [
			[
				[ 'MinimalPasswordLength' => 8 ], // p1
				[ 'MinimalPasswordLength' => 2 ], // p2
				[ 'MinimalPasswordLength' => 8 ], // max
				'Basic max in p1'
			],
			[
				[ 'MinimalPasswordLength' => 2 ], // p1
				[ 'MinimalPasswordLength' => 8 ], // p2
				[ 'MinimalPasswordLength' => 8 ], // max
				'Basic max in p2'
			],
			[
				[ 'MinimalPasswordLength' => 8 ], // p1
				[
					'MinimalPasswordLength' => 2,
					'PasswordCannotMatchUsername' => 1,
				], // p2
				[
					'MinimalPasswordLength' => 8,
					'PasswordCannotMatchUsername' => 1,
				], // max
				'Missing items in p1'
			],
			[
				[
					'MinimalPasswordLength' => 8,
					'PasswordCannotMatchUsername' => 1,
				], // p1
				[
					'MinimalPasswordLength' => 2,
				], // p2
				[
					'MinimalPasswordLength' => 8,
					'PasswordCannotMatchUsername' => 1,
				], // max
				'Missing items in p2'
			],
		];
	}

}
