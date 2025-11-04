<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\AuthorizationConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
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
	public function testPass( array $permissions, PageIdentity $page, bool $new ): void {
		$performer = $this->mockAnonAuthorityWithPermissions( $permissions );
		$constraint = new AuthorizationConstraint(
			$performer,
			$page,
			$new
		);
		$this->assertConstraintPassed( $constraint );
	}

	public static function provideTestPass(): iterable {
		yield 'Edit existing page' => [
			'permissions' => [ 'edit' ],
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
		];
		yield 'Create a new page' => [
			'permissions' => [ 'edit', 'create' ],
			'page' => PageIdentityValue::localIdentity( 0, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => true,
		];
	}

	/**
	 * @dataProvider provideTestFailure
	 */
	public function testFailure(
		$performerSpec, PageIdentity $page, bool $new, int $expectedValue
	): void {
		if ( is_array( $performerSpec ) ) {
			$performer = $this->mockAnonAuthorityWithoutPermissions( $performerSpec );
		} else {
			$performer = $performerSpec === 'registered'
				? $this->mockRegisteredAuthorityWithoutPermissions( [ 'edit' ] )
				: $this->mockUserAuthorityWithBlock(
					UserIdentityValue::newRegistered( 42, 'AuthorizationConstraintTest User' ),
					$this->makeMockBlock( [
						'decodedExpiry' => 'infinity',
					] ),
					[ 'edit' ]
				);
		}
		$constraint = new AuthorizationConstraint(
			$performer,
			$page,
			$new
		);
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public static function provideTestFailure(): iterable {
		yield 'Anonymous user' => [
			'performerSpec' => [ 'edit' ],
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_ANON,
		];
		yield 'Registered user' => [
			'performerSpec' => 'registered',
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_LOGGED,
		];
		yield 'User without create permission creates a page' => [
			'performerSpec' => [ 'create' ],
			'page' => PageIdentityValue::localIdentity( 0, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => true,
			'expectedValue' => IEditConstraint::AS_NO_CREATE_PERMISSION,
		];
		yield 'Blocked user' => [
			'performerSpec' => 'blocked',
			'page' => PageIdentityValue::localIdentity( 123, NS_MAIN, 'AuthorizationConstraintTest' ),
			'new' => false,
			'expectedValue' => IEditConstraint::AS_BLOCKED_PAGE_FOR_USER,
		];
	}

}
