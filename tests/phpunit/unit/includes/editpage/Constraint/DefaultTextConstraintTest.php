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

use MediaWiki\EditPage\Constraint\DefaultTextConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Title\Title;

/**
 * Tests the DefaultTextConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\DefaultTextConstraint
 */
class DefaultTextConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getTitle( $defaultText ) {
		$title = $this->createMock( Title::class );
		$title->method( 'getNamespace' )->willReturn( NS_MEDIAWIKI );
		$title->method( 'getDefaultMessageText' )->willReturn( $defaultText );
		return $title;
	}

	public function testPass() {
		$constraint = new DefaultTextConstraint(
			$this->getTitle( 'DefaultMessageTextGoesHere' ),
			false, // Allow blank
			'User provided text goes here',
			''
		);
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 * @param string|bool $defaultText
	 * @param string $userInput
	 */
	public function testFailure( $defaultText, $userInput ) {
		$constraint = new DefaultTextConstraint(
			$this->getTitle( $defaultText ),
			false, // Allow blank
			$userInput,
			''
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_BLANK_ARTICLE );
	}

	public static function provideTestFailure() {
		yield 'Matching message text' => [ 'MessageText', 'MessageText' ];
		yield 'Blank page and no default' => [ false, '' ];
	}

}
