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
use MediaWiki\EditPage\Constraint\SpamRegexConstraint;
use MediaWiki\EditPage\SpamChecker;
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

	public function testPass() {
		$summary = __METHOD__ . '-summary';
		$sectionHeading = __METHOD__ . '-section-heading';
		$text = __METHOD__ . '-text';

		$spamChecker = $this->createMock( SpamChecker::class );
		$spamChecker->expects( $this->once() )
			->method( 'checkSummary' )
			->with( $this->equalTo( $summary ) )
			->willReturn( false );
		$spamChecker->expects( $this->exactly( 2 ) )
			->method( 'checkContent' )
			->withConsecutive(
				[ $this->equalTo( $sectionHeading ) ],
				[ $this->equalTo( $text ) ]
			)
			->willReturn( false );

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
		$this->assertSame(
			IEditConstraint::CONSTRAINT_PASSED,
			$constraint->checkConstraint()
		);

		$status = $constraint->getLegacyStatus();
		$this->assertTrue( $status->isGood() );
	}

	public function testFailure() {
		$summary = __METHOD__ . '-summary';
		$sectionHeading = __METHOD__ . '-section-heading';
		$text = __METHOD__ . '-text';
		$matchingText = __METHOD__ . '-match';

		$spamChecker = $this->createMock( SpamChecker::class );
		$spamChecker->expects( $this->once() )
			->method( 'checkSummary' )
			->with( $this->equalTo( $summary ) )
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
		$this->assertSame(
			IEditConstraint::CONSTRAINT_FAILED,
			$constraint->checkConstraint()
		);

		$status = $constraint->getLegacyStatus();
		$this->assertFalse( $status->isGood() );
		$this->assertSame(
			IEditConstraint::AS_SPAM_ERROR,
			$status->getValue()
		);

		$this->assertSame( [
			[
				LogLevel::DEBUG,
				"Request-IP spam regex hit [[$prefixedDBKey]]: \"$matchingText\""
			],
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$this->assertSame(
			$matchingText,
			$constraint->getMatch()
		);
	}

}
