<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\SpamRegexConstraint;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Page\PageReference;
use MediaWikiUnitTestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;

/**
 * Tests the SpamRegexConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\SpamRegexConstraint
 */
class SpamRegexConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$summary = __METHOD__ . '-summary';
		$sectionHeading = __METHOD__ . '-section-heading';
		$text = __METHOD__ . '-text';

		$spamChecker = $this->createMock( SpamChecker::class );
		$spamChecker->expects( $this->once() )
			->method( 'checkSummary' )
			->with( $summary )
			->willReturn( false );
		$spamChecker->expects( $this->exactly( 2 ) )
			->method( 'checkContent' )
			->willReturnMap( [
				[ $sectionHeading, false ],
				[ $text, false ],
			] );

		$constraint = new SpamRegexConstraint(
			new NullLogger(),
			$spamChecker,
			$summary,
			$sectionHeading,
			$text,
			'Request-IP',
			$this->createNoOpMock( PageReference::class )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$summary = __METHOD__ . '-summary';
		$sectionHeading = __METHOD__ . '-section-heading';
		$text = __METHOD__ . '-text';
		$matchingText = __METHOD__ . '-match';

		$spamChecker = $this->createMock( SpamChecker::class );
		$spamChecker->expects( $this->once() )
			->method( 'checkSummary' )
			->with( $summary )
			->willReturn( $matchingText );

		$page = $this->createMock( PageReference::class );

		$logger = new TestLogger( true, collectContext: true );

		$constraint = new SpamRegexConstraint(
			$logger,
			$spamChecker,
			$summary,
			$sectionHeading,
			$text,
			'Request-IP',
			$page
		);
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_SPAM_ERROR );
		[ $level, $msg, $context ] = $logger->getBuffer()[0];

		$this->assertSame( LogLevel::DEBUG, $level );
		$this->assertSame( '{ip} spam regex hit [[{title}]]: "{match}"', $msg );
		$this->assertSame( $page, $context['title'] );
		$logger->clearBuffer();

		$this->assertSame(
			$matchingText,
			$constraint->getMatch()
		);
	}

}
