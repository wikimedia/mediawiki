<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;

/**
 * Tests the AccidentalRecreationConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint
 */
class AccidentalRecreationConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new AccidentalRecreationConstraint( false, true );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$constraint = new AccidentalRecreationConstraint( true, false );
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_ARTICLE_WAS_DELETED
		);
	}

}
