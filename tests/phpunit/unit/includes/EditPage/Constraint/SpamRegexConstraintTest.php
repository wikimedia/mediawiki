<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\SpamRegexConstraint;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Title\Title;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * Tests the SpamRegexContraint
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

		$title = $this->createMock( Title::class );
		$title->expects( $this->never() )
			->method( 'getPrefixedDBkey' );

		$logger = new NullLogger();

		$constraint = new SpamRegexConstraint(
			$logger,
			$spamChecker,
			$summary,
			$sectionHeading,
			$text,
			'Request-IP',
			$title
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

		$prefixedDBKey = 'PrefixedDBKeyGoesHere';
		$title = $this->createMock( Title::class );
		$title->expects( $this->once() )
			->method( 'getPrefixedDBkey' )
			->willReturn( $prefixedDBKey );

		$logger = new TestLogger( true );

		$constraint = new SpamRegexConstraint(
			$logger,
			$spamChecker,
			$summary,
			$sectionHeading,
			$text,
			'Request-IP',
			$title
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_SPAM_ERROR );

		$this->assertSame( [
			[
				LogLevel::DEBUG,
				'{ip} spam regex hit [[{title}]]: "{match}"'
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->assertSame(
			$matchingText,
			$constraint->getMatch()
		);
	}

}
