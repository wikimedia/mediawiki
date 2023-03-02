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
