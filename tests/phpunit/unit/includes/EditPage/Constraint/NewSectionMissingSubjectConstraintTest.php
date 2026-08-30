<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\NewSectionMissingSubjectConstraint;
use MediaWiki\PageEdit\PageEditAttemptState;
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
		$constraint = new NewSectionMissingSubjectConstraint(
			'new',
			'Subject',
			true,
			new PageEditAttemptState(),
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$editAttemptState = new PageEditAttemptState();
		$constraint = new NewSectionMissingSubjectConstraint(
			'new',
			'',
			true,
			$editAttemptState,
		);
		$status = $this->assertConstraintFailed( $constraint, EditConstraint::AS_SUMMARY_NEEDED );
		$this->assertTrue( $editAttemptState->shouldAllowBlankSummary() );
		$this->assertTrue( $status->canResubmit() );
	}

	public function testNonNew() {
		$constraint = new NewSectionMissingSubjectConstraint(
			'notnew',
			'',
			true,
			new PageEditAttemptState(),
		);
		$this->assertConstraintPassed( $constraint );
	}

}
