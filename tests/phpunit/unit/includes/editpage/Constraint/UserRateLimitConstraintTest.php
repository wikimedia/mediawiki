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

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\UserRateLimitConstraint;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests the UserRateLimitConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\UserRateLimitConstraint
 */
class UserRateLimitConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	/**
	 * @param bool $fail
	 *
	 * @return MockObject&RateLimiter
	 */
	private function getRateLimiter( $fail ) {
		$mock = $this->createNoOpMock( RateLimiter::class, [ 'limit' ] );
		$expectedArgs = [
			[ 'edit', 1, false ],
			[ 'linkpurge', 0, false ],
			[ 'editcontentmodel', 1, $fail ]
		];
		$mock->expects( $this->exactly( 3 ) )
			->method( 'limit' )
			->willReturnCallback( function ( $_, $action, $incrBy ) use ( &$expectedArgs ) {
				$curExpectedArgs = array_shift( $expectedArgs );
				$this->assertSame( $curExpectedArgs[0], $action );
				$this->assertSame( $curExpectedArgs[1], $incrBy );
				return $curExpectedArgs[2];
			} );
		return $mock;
	}

	public function testPass() {
		$limiter = $this->getRateLimiter( false );

		$subject = new RateLimitSubject( new UserIdentityValue( 1, 'test' ), null, [] );

		$constraint = new UserRateLimitConstraint( $limiter, $subject, 'OldContentModel', 'NewContentModel' );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$limiter = $this->getRateLimiter( true );

		$subject = new RateLimitSubject( new UserIdentityValue( 1, 'test' ), null, [] );

		$constraint = new UserRateLimitConstraint( $limiter, $subject, 'OldContentModel', 'NewContentModel' );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_RATE_LIMITED );
	}

}
