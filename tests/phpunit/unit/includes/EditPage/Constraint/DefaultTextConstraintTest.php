<?php
/**
 * @license GPL-2.0-or-later
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
