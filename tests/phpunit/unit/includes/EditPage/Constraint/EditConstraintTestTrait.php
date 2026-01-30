<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\EditPageStatus;

/**
 * Helper for the various constraint test classes
 *
 * @author DannyS712
 */
trait EditConstraintTestTrait {

	/**
	 * Assert that the constraint passes and that the status is good
	 */
	public function assertConstraintPassed( IEditConstraint $constraint ): EditPageStatus {
		$status = $constraint->checkConstraint();
		$this->assertStatusGood( $status );
		return $status;
	}

	/**
	 * Assert that the constraint fails with the specified status code
	 */
	public function assertConstraintFailed( IEditConstraint $constraint, int $statusCode ): EditPageStatus {
		$status = $constraint->checkConstraint();
		$this->assertStatusNotOK( $status );
		$this->assertStatusValue( $statusCode, $status );
		return $status;
	}

}
