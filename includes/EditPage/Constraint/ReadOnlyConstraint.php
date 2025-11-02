<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use StatusValue;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Verify site is not in read only mode
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ReadOnlyConstraint implements IEditConstraint {

	private string $result;

	public function __construct(
		private readonly ReadOnlyMode $readOnlyMode,
	) {
	}

	public function checkConstraint(): string {
		$this->result = $this->readOnlyMode->isReadOnly() ?
			self::CONSTRAINT_FAILED :
			self::CONSTRAINT_PASSED;
		return $this->result;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			// Saved that this is read only in case getLegacyStatus is called
			// after the read only ends, because it still caused the failure
			$statusValue->fatal( 'readonlytext' );
			$statusValue->value = self::AS_READ_ONLY_PAGE;
		}

		return $statusValue;
	}

}
