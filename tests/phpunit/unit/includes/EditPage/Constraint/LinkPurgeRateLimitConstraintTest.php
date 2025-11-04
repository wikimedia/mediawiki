<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\LinkPurgeRateLimitConstraint;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Tests the LinkPurgeRateLimitConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\LinkPurgeRateLimitConstraint
 */
class LinkPurgeRateLimitConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	/**
	 * @param bool $fail
	 *
	 * @return MockObject&RateLimiter
	 */
	private function getRateLimiter( $fail ) {
		$mock = $this->createNoOpMock( RateLimiter::class, [ 'limit' ] );
		$mock->expects( $this->once() )
			->method( 'limit' )
			->with( self::anything(), 'linkpurge', 0 )
			->willReturn( $fail );
		return $mock;
	}

	public function testPass() {
		$limiter = $this->getRateLimiter( false );

		$subject = new RateLimitSubject( new UserIdentityValue( 1, 'test' ), null, [] );

		$constraint = new LinkPurgeRateLimitConstraint( $limiter, $subject );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$limiter = $this->getRateLimiter( true );

		$subject = new RateLimitSubject( new UserIdentityValue( 1, 'test' ), null, [] );

		$constraint = new LinkPurgeRateLimitConstraint( $limiter, $subject );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_RATE_LIMITED );
	}

}
