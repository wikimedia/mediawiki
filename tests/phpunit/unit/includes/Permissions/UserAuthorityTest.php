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

namespace MediaWiki\Tests\Unit\Permissions;

use InvalidArgumentException;
use MediaWiki\Block\AbstractBlock;
use MediaWiki\Language\MessageParser;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Status\StatusFormatter;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;
use MediaWiki\User\User;
use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\Permissions\UserAuthority
 */
class UserAuthorityTest extends MediaWikiUnitTestCase {

	use MockAuthorityTrait;

	public function testGetUser() {
		$user = $this->newUser();
		$authority = $this->newUserAuthority( [ 'actor' => $user ] );

		$this->assertSame( $user, $authority->getUser() );
	}

	public function testGetUserBlockNotBlocked() {
		$authority = $this->newUserAuthority();
		$this->assertNull( $authority->getBlock() );
	}

	public function testGetUserBlockWasBlocked() {
		$block = $this->createNoOpMock( AbstractBlock::class );
		$user = $this->newUser( $block );

		$authority = $this->newUserAuthority( [ 'actor' => $user ] );
		$this->assertSame( $block, $authority->getBlock() );
	}

	public function testRateLimitApplies() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'edit' ], 'limited' => true ] );

		$this->assertTrue( $authority->isAllowed( 'edit' ) );
		$this->assertTrue( $authority->probablyCan( 'edit', $target ) );

		$this->assertFalse( $authority->isDefinitelyAllowed( 'edit' ) );
		$this->assertFalse( $authority->definitelyCan( 'edit', $target ) );

		$this->assertFalse( $authority->authorizeRead( 'edit', $target ) );
		$this->assertFalse( $authority->authorizeWrite( 'edit', $target ) );
		$this->assertFalse( $authority->authorizeAction( 'edit' ) );
	}

	public function testRateLimiterBypassedForReading() {
		$permissionManager = $this->newPermissionsManager( [ 'read' ] );

		// Key assertion: limit() is not called.
		$rateLimiter = $this->createNoOpMock( RateLimiter::class, [ 'isLimitable' ] );
		$rateLimiter->method( 'isLimitable' )->willReturn( false );

		// Key assertion: toRateLimitSubject() is not called.
		$actor = $this->createNoOpMock( User::class, [ 'getBlock', 'isNewbie' ] );
		$actor->method( 'getBlock' )->willReturn( null );
		$actor->method( 'isNewbie' )->willReturn( false );

		$authority = $this->newUserAuthority( [
			'actor' => $actor,
			'permissionManager' => $permissionManager,
			'rateLimiter' => $rateLimiter
		] );

		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$this->assertTrue( $authority->authorizeRead( 'read', $target ) );
	}

	/**
	 * @covers \MediaWiki\Permissions\UserAuthority::limit
	 */
	public function testPingLimiterCaching() {
		$permissionManager = $this->newPermissionsManager( [] );
		$rateLimiter = $this->createNoOpMock( RateLimiter::class, [ 'limit', 'isLimitable' ] );

		$rateLimiter->method( 'isLimitable' )
			->willReturn( true );

		// We expect exactly five calls to go through to the RateLimiter,
		// see the comments below.
		$rateLimiter->expects( $this->exactly( 5 ) )
			->method( 'limit' )
			->willReturn( false );

		$authority = $this->newUserAuthority( [
			'permissionManager' => $permissionManager,
			'rateLimiter' => $rateLimiter
		] );

		// The rate limit cache is usually disabled during testing.
		// Enable it so we can test it.
		$authority->setUseLimitCache( true );

		// The first call should go through to the RateLimiter (count 1).
		$this->assertFalse( $authority->limit( 'edit', 0, null ) );

		// The second call should also go through to the RateLimiter,
		// because now we are incrementing, and before we were just peeking (count 2).
		$this->assertFalse( $authority->limit( 'edit', 1, null ) );

		// The third call should hit the cache
		$this->assertFalse( $authority->limit( 'edit', 0, null ) );

		// The forth call should hit the cache, even if incrementing.
		// This makes sure we don't increment the same counter multiple times
		// during a single request.
		$this->assertFalse( $authority->limit( 'edit', 1, null ) );

		// The fifth call should go to the RateLimiter again, because we are now
		// incrementing by more than one (count 3).
		$this->assertFalse( $authority->limit( 'edit', 5, null ) );

		// The next calls should not go through, since we already hit 5
		$this->assertFalse( $authority->limit( 'edit', 5, null ) );
		$this->assertFalse( $authority->limit( 'edit', 2, null ) );
		$this->assertFalse( $authority->limit( 'edit', 0, null ) );

		// When limiting another action, we should not hit the cache (count 4).
		$this->assertFalse( $authority->limit( 'move', 1, null ) );

		// After disabling  the cache, we should get through to the RateLimiter again (count 5).
		$authority->setUseLimitCache( false );
		$this->assertFalse( $authority->limit( 'move', 1, null ) );
	}

	public function testBlockedUserCanRead() {
		$block = $this->createNoOpMock( AbstractBlock::class );
		$user = $this->newUser( $block );

		$authority = $this->newUserAuthority(
			[ 'permissions' => [ 'read', 'edit' ], 'actor' => $user ]
		);

		$status = PermissionStatus::newEmpty();
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$this->assertTrue( $authority->authorizeRead( 'read', $target, $status ) );
		$this->assertStatusOK( $status );
	}

	public function testBlockedUserCanNotWrite() {
		$block = $this->createNoOpMock( AbstractBlock::class );
		$user = $this->newUser( $block );

		$authority = $this->newUserAuthority(
			[ 'permissions' => [ 'read', 'edit' ], 'actor' => $user ]
		);

		$status = PermissionStatus::newEmpty();
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$this->assertFalse( $authority->authorizeWrite( 'edit', $target, $status ) );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'edit', $status->getPermission() );
		$this->assertSame( $block, $status->getBlock() );
	}

	public function testBlockedUserAction() {
		$block = $this->createNoOpMock( AbstractBlock::class );
		$user = $this->newUser( $block );

		$authority = $this->newUserAuthority(
			[ 'permissions' => [ 'read', 'blocked' ], 'actor' => $user ]
		);

		$status = PermissionStatus::newEmpty();
		$this->assertTrue( $authority->isAllowed( 'blocked' ) );
		$this->assertFalse( $authority->isDefinitelyAllowed( 'blocked' ) );
		$this->assertFalse( $authority->authorizeAction( 'blocked', $status ) );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'blocked', $status->getPermission() );
	}

	public function testPermissions() {
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->isAllowed( 'foo' ) );
		$this->assertTrue( $authority->isAllowed( 'bar' ) );
		$this->assertFalse( $authority->isAllowed( 'quux' ) );

		$this->assertTrue( $authority->isAllowedAll( 'foo', 'bar' ) );
		$this->assertTrue( $authority->isAllowedAny( 'bar', 'quux' ) );

		$this->assertFalse( $authority->isAllowedAll( 'foo', 'quux' ) );
		$this->assertFalse( $authority->isAllowedAny( 'xyzzy', 'quux' ) );
	}

	public function testIsAllowed() {
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->isAllowed( 'foo' ) );
		$this->assertTrue( $authority->isAllowed( 'bar' ) );
		$this->assertFalse( $authority->isAllowed( 'quux' ) );

		$status = new PermissionStatus();
		$authority->isAllowed( 'foo', $status );
		$this->assertStatusOK( $status );

		$authority->isAllowed( 'quux', $status );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'quux', $status->getPermission() );
	}

	public function testProbablyCan() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->probablyCan( 'foo', $target ) );
		$this->assertTrue( $authority->probablyCan( 'bar', $target ) );
		$this->assertFalse( $authority->probablyCan( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->probablyCan( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->probablyCan( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'quux', $status->getPermission() );
	}

	public function testIsDefinitelyAllowed() {
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->isDefinitelyAllowed( 'foo' ) );
		$this->assertTrue( $authority->isDefinitelyAllowed( 'bar' ) );
		$this->assertFalse( $authority->isDefinitelyAllowed( 'quux' ) );

		$status = new PermissionStatus();
		$authority->isDefinitelyAllowed( 'foo', $status );
		$this->assertStatusOK( $status );

		$authority->isDefinitelyAllowed( 'quux', $status );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'quux', $status->getPermission() );
	}

	public function testDefinitelyCan() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->definitelyCan( 'foo', $target ) );
		$this->assertTrue( $authority->definitelyCan( 'bar', $target ) );
		$this->assertFalse( $authority->definitelyCan( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->definitelyCan( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->definitelyCan( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'quux', $status->getPermission() );
	}

	public function testAuthorize() {
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->authorizeAction( 'foo' ) );
		$this->assertTrue( $authority->authorizeAction( 'bar' ) );
		$this->assertFalse( $authority->authorizeAction( 'quux' ) );

		$status = new PermissionStatus();
		$authority->authorizeAction( 'foo', $status );
		$this->assertStatusOK( $status );

		$authority->authorizeAction( 'quux', $status );
		$this->assertStatusNotOK( $status );
		$this->assertSame( 'quux', $status->getPermission() );
	}

	public function testAuthorizeRead() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->authorizeRead( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeRead( 'bar', $target ) );
		$this->assertFalse( $authority->authorizeRead( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeRead( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->authorizeRead( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
	}

	public function testAuthorizeWrite() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->assertTrue( $authority->authorizeWrite( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeWrite( 'bar', $target ) );
		$this->assertFalse( $authority->authorizeWrite( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeWrite( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->authorizeWrite( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
	}

	public function testIsAllowedAnyThrowsOnEmptySet() {
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->expectException( InvalidArgumentException::class );
		$authority->isAllowedAny();
	}

	public function testIsAllowedAllThrowsOnEmptySet() {
		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ] ] );

		$this->expectException( InvalidArgumentException::class );
		$authority->isAllowedAll();
	}

	public function testGetBlock_none() {
		$actor = $this->newUser();

		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ], 'actor' => $actor ] );

		$this->assertNull( $authority->getBlock() );
	}

	public function testGetBlock_blocked() {
		$block = $this->createNoOpMock( AbstractBlock::class );
		$actor = $this->newUser( $block );

		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ], 'actor' => $actor ] );

		$this->assertSame( $block, $authority->getBlock() );
	}

	/**
	 * Regression test for T306494: check that when creating a PermissionStatus,
	 * the message contains all parameters and when converted to a Status, the parameters
	 * are not wikitext escaped.
	 */
	public function testInternalCanWithPermissionStatusMessageFormatting() {
		$block = $this->createNoOpMock( AbstractBlock::class );
		$user = $this->newUser( $block );

		$authority = $this->newUserAuthority( [ 'permissions' => [ 'foo', 'bar' ], 'actor' => $user, 'limited' => true ] );

		$permissionStatus = PermissionStatus::newEmpty();
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );

		$authority->authorizeWrite(
			'edit',
			$target,
			$permissionStatus
		);

		$this->assertStatusError( 'actionthrottledtext', $permissionStatus );
		$this->assertTrue( $permissionStatus->isRateLimitExceeded() );

		$this->assertStatusError( 'blockedtext-partial', $permissionStatus );
		$this->assertNotNull( $permissionStatus->getBlock() );

		$formatter = new StatusFormatter(
			new FakeQqxMessageLocalizer(),
			$this->createNoOpMock( MessageParser::class ),
			new NullLogger()
		);
		// Despite all the futzing around with services, StatusFormatter depends on this global through wfEscapeWikiText
		global $wgEnableMagicLinks;
		$old = $wgEnableMagicLinks;
		$wgEnableMagicLinks = [];

		try {
			$wikitext = $formatter->getWikiText( $permissionStatus );
			// Assert that the formatted wikitext contains the original values of the message parameters,
			// rather than escaped ones
			foreach ( $this->getFakeBlockMessageParams() as $param ) {
				$this->assertStringContainsString( $param, $wikitext );
			}
		} finally {
			$wgEnableMagicLinks = $old;
		}
	}
}
