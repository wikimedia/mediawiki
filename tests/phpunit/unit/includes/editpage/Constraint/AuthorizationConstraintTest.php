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

use MediaWiki\EditPage\Constraint\AuthorizationConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\MockBlockTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\EditPage\Constraint\AuthorizationConstraint
 */
class AuthorizationConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;
	use MockTitleTrait;
	use MockBlockTrait;

	/**
	 * @dataProvider provideTestPass
	 */
	public function testPass( Authority $performer, PageIdentity $page, bool $new ): void {
		$constraint = new AuthorizationConstraint(
			$performer,
			$page,
			$new
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function provideTestPass(): iterable {
		yield 'Edit existing page' => [
			'performer' => $this->mockAnonAuthorityWithPermissions( [ 'edit' ] ),
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
		];
		yield 'Create a new page' => [
			'performer' => $this->mockAnonAuthorityWithPermissions( [ 'edit', 'create' ] ),
			'page' => PageIdentityValue::localIdentity( 0, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => true,
		];
	}

	/**
	 * @dataProvider provideTestFailure
	 */
	public function testFailure(
		Authority $performer, PageIdentity $page, bool $new, int $expectedValue
	): void {
		$constraint = new AuthorizationConstraint(
			$performer,
			$page,
			$new
		);
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public function provideTestFailure(): iterable {
		yield 'Anonymous user' => [
			'performer' => $this->mockAnonAuthorityWithoutPermissions( [ 'edit' ] ),
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_ANON,
		];
		yield 'Registered user' => [
			'performer' => $this->mockRegisteredAuthorityWithoutPermissions( [ 'edit' ] ),
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_LOGGED,
		];
		yield 'User without create permission creates a page' => [
			'performer' => $this->mockAnonAuthorityWithoutPermissions( [ 'create' ] ),
			'page' => PageIdentityValue::localIdentity( 0, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => true,
			'expectedValue' => IEditConstraint::AS_NO_CREATE_PERMISSION,
		];
		yield 'Blocked user' => [
			'performer' => $this->mockUserAuthorityWithBlock(
				UserIdentityValue::newRegistered( 42, 'AuthorizationConstraintTest User' ),
				$this->makeMockBlock( [
					'decodedExpiry' => 'infinity',
				] ),
				[ 'edit' ]
			),
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
			'expectedValue' => IEditConstraint::AS_BLOCKED_PAGE_FOR_USER,
		];
	}

}
