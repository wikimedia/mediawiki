<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\SimpleAntiSpamConstraint;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * Tests the SimpleAntiSpamConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\SimpleAntiSpamConstraint
 */
class SimpleAntiSpamConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$logger = new NullLogger();
		$user = new UserIdentityValue( 5, 'UserNameGoesHere' );
		$title = $this->createMock( Title::class );

		$constraint = new SimpleAntiSpamConstraint(
			$logger,
			'',
			$user,
			$title
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$logger = new TestLogger( true );
		$user = new UserIdentityValue( 5, 'UserNameGoesHere' );
		$title = $this->createMock( Title::class );
		$title->expects( $this->once() )
			->method( 'getPrefixedText' )
			->willReturn( 'TitlePrefixedTextGoesHere' );

		$constraint = new SimpleAntiSpamConstraint(
			$logger,
			'SpamContent',
			$user,
			$title
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_SPAM_ERROR );

		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'{name} editing "{title}" submitted bogus field "{input}"'
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();
	}

}
