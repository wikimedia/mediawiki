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
 * @since 1.42
 */

namespace MediaWiki\Tests\Message;

use MediaWiki\Message\UserGroupMembershipParam;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Message\UserGroupMembershipParam
 */
class UserGroupMembershipParamTest extends MediaWikiUnitTestCase {

	protected string $group;
	protected UserIdentity $member;

	protected function setUp(): void {
		parent::setUp();
		$this->group = 'users';
		$this->member = new UserIdentityValue( 1, 'MockUser' );
	}

	public function testConstruct() {
		$param = @new UserGroupMembershipParam( $this->group, $this->member );
		$this->assertSame( $this->group, $param->getGroup() );
		$this->assertSame( $this->member, $param->getMember() );
	}

	public function testGetGroup() {
		$groupMock = 'users';
		$userNameMock = 'MockUser';
		$userIdentityMock = new UserIdentityValue( 1, $userNameMock );
		$param = @new UserGroupMembershipParam( $groupMock, $userIdentityMock );
		$this->assertSame( $groupMock, $param->getGroup(),
			'Group name should match the constructor argument.' );
	}

	public function testGetMember() {
		$groupMock = 'users';
		$userNameMock = 'MockUser';
		$userIdentityMock = new UserIdentityValue( 1, $userNameMock );
		$param = @new UserGroupMembershipParam( $groupMock, $userIdentityMock );
		$this->assertSame( $userIdentityMock, $param->getMember(),
			'User identity object should match the constructor argument.' );
	}

	public function testToString() {
		// More mock parameters aimed at a different group and user.
		$groupMock = 'sysop';
		$userIdentityMock = new UserIdentityValue( 2, 'MockAdminUser' );

		// Create the UserGroupMembershipParam instance with mocks.
		$param = @new UserGroupMembershipParam( $groupMock, $userIdentityMock );
		$expectedString = 'sysop:MockAdminUser';
		// Asserting if the string representation is as expected.
		$this->assertSame( $expectedString, (string)$param,
			'String representation should combine group and user name.' );
	}
}
