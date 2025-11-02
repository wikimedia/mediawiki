<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use StatusValue;

/**
 * Make sure user doesn't accidentally recreate a page deleted after they started editing
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class AccidentalRecreationConstraint implements IEditConstraint {

	public function __construct(
		private readonly bool $deletedSinceLastEdit,
		private readonly bool $allowRecreation,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->deletedSinceLastEdit && !$this->allowRecreation ) {
			return self::CONSTRAINT_FAILED;
		}
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->deletedSinceLastEdit && !$this->allowRecreation ) {
			$statusValue->setResult( false, self::AS_ARTICLE_WAS_DELETED );
		}
		return $statusValue;
	}

}
