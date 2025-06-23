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
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Permissions\UltimateAuthority
 */
class UltimateAuthorityTest extends MediaWikiUnitTestCase {

	public function testGetAuthor() {
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertSame( $actor, $authority->getUser() );
	}

	public function testPermissions() {
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertTrue( $authority->isAllowed( 'foo' ) );
		$this->assertTrue( $authority->isAllowed( 'bar' ) );

		$this->assertTrue( $authority->isAllowedAll( 'foo', 'bar' ) );
		$this->assertTrue( $authority->isAllowedAny( 'bar', 'quux' ) );
	}

	public function testProbablyCan() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertTrue( $authority->probablyCan( 'foo', $target ) );
		$this->assertTrue( $authority->probablyCan( 'bar', $target ) );

		$status = new PermissionStatus();
		$authority->probablyCan( 'foo', $target, $status );
		$this->assertStatusOK( $status );
	}

	public function testDefinitelyCan() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertTrue( $authority->definitelyCan( 'foo', $target ) );
		$this->assertTrue( $authority->definitelyCan( 'bar', $target ) );

		$status = new PermissionStatus();
		$authority->definitelyCan( 'foo', $target, $status );
		$this->assertStatusOK( $status );
	}

	public function testAuthorizeRead() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertTrue( $authority->authorizeRead( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeRead( 'bar', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeRead( 'foo', $target, $status );
		$this->assertStatusOK( $status );
	}

	public function testAuthorizeWrite() {
		$target = PageIdentityValue::localIdentity( 321, NS_MAIN, __METHOD__ );
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertTrue( $authority->authorizeWrite( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeWrite( 'bar', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeWrite( 'foo', $target, $status );
		$this->assertStatusOK( $status );
	}

	public function testIsAllowedAnyThrowsOnEmptySet() {
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->expectException( InvalidArgumentException::class );
		$authority->isAllowedAny();
	}

	public function testIsAllowedAllThrowsOnEmptySet() {
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->expectException( InvalidArgumentException::class );
		$authority->isAllowedAll();
	}

	public function testGetBlock() {
		$actor = new UserIdentityValue( 12, 'Test' );
		$authority = new UltimateAuthority( $actor );

		$this->assertNull( $authority->getBlock() );
	}

}
