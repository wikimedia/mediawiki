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

// phpcs:disable MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgUser
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\User\User;

/**
 * Tests the MediaWiki\StubObject\StubGlobalUser, including magic support for __get() and __set()
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\StubObject\StubGlobalUser
 */
class StubGlobalUserTest extends MediaWikiIntegrationTestCase {

	/** @var int */
	private $oldErrorLevel;

	protected function setUp(): void {
		parent::setUp();

		// Make sure deprecation notices are seen
		$this->oldErrorLevel = error_reporting( -1 );

		// Using User::newFromRow() to avoid needing any integration
		$userFields = [
			'user_id' => 12345,
		];
		$realUser = User::newFromRow( (object)$userFields );
		StubGlobalUser::setUser( $realUser );
	}

	protected function tearDown(): void {
		error_reporting( $this->oldErrorLevel );
		parent::tearDown();
	}

	public function testRealUser() {
		// Should not be emitting deprecation warnings
		global $wgUser;

		$this->assertInstanceOf( StubGlobalUser::class, $wgUser );

		$real = StubGlobalUser::getRealUser( $wgUser );
		$this->assertInstanceOf( User::class, $real );

		$real2 = StubGlobalUser::getRealUser( $real );
		$this->assertSame( $real, $real2 );
	}

	public function testRealUser_exception() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage(
			'$globalUser must be a User (or MediaWiki\StubObject\StubGlobalUser), got int'
		);
		StubGlobalUser::getRealUser( 12345 );
	}

	public function testMagicCall() {
		$this->expectDeprecationAndContinue( '/Use of \$wgUser was deprecated in MediaWiki 1\.35/' );
		$this->expectDeprecationAndContinue( '/\$wgUser reassignment detected/' );

		global $wgUser;
		$this->assertInstanceOf(
			StubGlobalUser::class,
			$wgUser,
			'Check: $wgUser should be a MediaWiki\StubObject\StubGlobalUser at the start of the test'
		);
		$this->assertSame(
			12345,
			$wgUser->getId(),
			'__call() based on id set in ::setUp()'
		);
		$this->assertInstanceOf(
			User::class,
			$wgUser,
			'__call() resulted in unstubbing'
		);
	}

	public function testGetMagic() {
		$this->expectDeprecationAndContinue( '/Use of \$wgUser was deprecated in MediaWiki 1\.35/' );
		$this->expectDeprecationAndContinue( '/\$wgUser reassignment detected/' );

		global $wgUser;
		$this->assertInstanceOf(
			StubGlobalUser::class,
			$wgUser,
			'Check: $wgUser should be a MediaWiki\StubObject\StubGlobalUser at the start of the test'
		);
		$this->assertSame(
			12345,
			$wgUser->mId,
			'__get() based on id set in ::setUp()'
		);
		$this->assertInstanceOf(
			User::class,
			$wgUser,
			'__get() resulted in unstubbing'
		);
	}

	public function testSetMagic() {
		// This test is why we need MediaWiki\StubObject\StubGlobalUser::_unstub to override MediaWiki\StubObject\StubObject::_unstub
		// and not try to detect and throw exceptions in unstub loops - for some reason it
		// thinks this creates a loop.

		$this->expectDeprecationAndContinue( '/Use of \$wgUser was deprecated in MediaWiki 1\.35/' );
		$this->expectDeprecationAndContinue( '/\$wgUser reassignment detected/' );

		global $wgUser;
		$this->assertInstanceOf(
			StubGlobalUser::class,
			$wgUser,
			'Check: $wgUser should be a MediaWiki\StubObject\StubGlobalUser at the start of the test'
		);
		$wgUser->mId = 2000;
		$this->assertInstanceOf(
			User::class,
			$wgUser,
			'__set() resulted in unstubbing'
		);
		$this->assertSame(
			2000,
			$wgUser->mId,
			'__set() call worked'
		);
	}

	public function testDeprecationEmittedWhenReassigned() {
		$this->expectDeprecationAndContinue( '/\$wgUser reassignment detected/' );
		global $wgUser;
		$wgUser = new User;
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testReassignmentWithRestoring() {
		global $wgUser;
		$oldUser = $wgUser;
		$wgUser = new User;
		$wgUser = $oldUser;
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testSetUserNoDeprecation() {
		StubGlobalUser::setUser( new User );
	}
}
