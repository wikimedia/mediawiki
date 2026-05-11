<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\NewSectionMissingSubjectConstraint;
use MediaWiki\Language\RawMessage;
use MediaWikiUnitTestCase;

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
		$constraint = new NewSectionMissingSubjectConstraint( 'new', 'Subject', false, new RawMessage( '' ), );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$constraint = new NewSectionMissingSubjectConstraint( 'new', '', false, new RawMessage( '' ), );
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_SUMMARY_NEEDED );
	}

	public function testNonNew() {
		$constraint = new NewSectionMissingSubjectConstraint( 'notnew', '', false, new RawMessage( '' ), );
		$this->assertConstraintPassed( $constraint );
	}

}
