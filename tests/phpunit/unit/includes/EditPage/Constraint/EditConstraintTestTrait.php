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
		$this->assertSame(
			IEditConstraint::CONSTRAINT_PASSED,
			$constraint->checkConstraint()
		);

		$status = $constraint->getLegacyStatus();
		$this->assertStatusGood( $status );
	}

	/**
	 * Assert that the constraint fails with the specified status code
	 * @param IEditConstraint $constraint
	 * @param int $statusCode
	 */
	public function assertConstraintFailed( IEditConstraint $constraint, int $statusCode ) {
		$this->assertSame(
			IEditConstraint::CONSTRAINT_FAILED,
			$constraint->checkConstraint()
		);

		$status = $constraint->getLegacyStatus();
		$this->assertStatusNotGood( $status );
		$this->assertStatusValue( $statusCode, $status );
	}

}
