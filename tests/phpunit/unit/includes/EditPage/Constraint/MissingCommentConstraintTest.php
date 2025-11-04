<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\MissingCommentConstraint;

/**
 * Tests the MissingCommentConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\MissingCommentConstraint
 */
class MissingCommentConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$constraint = new MissingCommentConstraint( 'new', 'Comment' );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$constraint = new MissingCommentConstraint( 'new', '' );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_TEXTBOX_EMPTY );
	}

	public function testNonNew() {
		$constraint = new MissingCommentConstraint( 'notnew', '' );
		$this->assertConstraintPassed( $constraint );
	}

}
