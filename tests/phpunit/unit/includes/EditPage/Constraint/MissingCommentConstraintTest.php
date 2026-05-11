<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\MissingCommentConstraint;
use MediaWikiUnitTestCase;

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
		$this->assertConstraintFailed( $constraint, EditConstraint::AS_TEXTBOX_EMPTY );
	}

	public function testNonNew() {
		$constraint = new MissingCommentConstraint( 'notnew', '' );
		$this->assertConstraintPassed( $constraint );
	}

}
