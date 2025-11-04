<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\UnicodeConstraint;

/**
 * Tests the UnicodeConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\UnicodeConstraint
 */
class UnicodeConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new UnicodeConstraint( UnicodeConstraint::VALID_UNICODE );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$constraint = new UnicodeConstraint( 'NotTheCorrectUnicode' );
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_UNICODE_NOT_SUPPORTED
		);
	}

}
