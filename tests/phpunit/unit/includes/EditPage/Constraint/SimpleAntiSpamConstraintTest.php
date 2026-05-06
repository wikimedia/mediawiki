<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\SimpleAntiSpamConstraint;
use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;

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
		$page = $this->createMock( PageReference::class );

		$constraint = new SimpleAntiSpamConstraint(
			$logger,
			'',
			$user,
			$page
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$logger = new TestLogger( true, collectContext: true );
		$user = new UserIdentityValue( 5, 'UserNameGoesHere' );
		$page = $this->createMock( PageReference::class );

		$constraint = new SimpleAntiSpamConstraint(
			$logger,
			'SpamContent',
			$user,
			$page,
		);
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_SPAM_ERROR );
		[ $level, $msg, $context ] = $logger->getBuffer()[0];

		$this->assertSame( LogLevel::DEBUG, $level );
		$this->assertSame( '{name} editing "{title}" submitted bogus field "{input}"', $msg );
		$this->assertSame( $page, $context['title'] );

		$logger->clearBuffer();
	}

}
