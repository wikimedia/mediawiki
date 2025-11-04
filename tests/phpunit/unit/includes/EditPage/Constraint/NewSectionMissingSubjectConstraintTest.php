<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\NewSectionMissingSubjectConstraint;

/**
 * Tests the NewSectionMissingSubjectConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\NewSectionMissingSubjectConstraint
 */
class NewSectionMissingSubjectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new NewSectionMissingSubjectConstraint( 'new', 'Subject', false );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$constraint = new NewSectionMissingSubjectConstraint( 'new', '', false );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_SUMMARY_NEEDED );
	}

	public function testNonNew() {
		$constraint = new NewSectionMissingSubjectConstraint( 'notnew', '', false );
		$this->assertConstraintPassed( $constraint );
	}

}
