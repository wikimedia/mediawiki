<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;

/**
 * Helper for the various constraint test classes
 *
 * @author DannyS712
 */
trait EditConstraintTestTrait {

	/**
	 * Assert that the constraint passes and that the status is good
	 */
	public function assertConstraintPassed( IEditConstraint $constraint ) {
		$this->assertStatusGood( $constraint->checkConstraint() );
	}

	/**
	 * Assert that the constraint fails with the specified status code
	 * @param IEditConstraint $constraint
	 * @param int $statusCode
	 */
	public function assertConstraintFailed( IEditConstraint $constraint, int $statusCode ) {
		$status = $constraint->checkConstraint();
		$this->assertStatusNotOK( $status );
		$this->assertStatusValue( $statusCode, $status );
	}

}
