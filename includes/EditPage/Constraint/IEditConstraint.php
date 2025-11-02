<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\IEditObject;
use StatusValue;

/**
 * Interface for all constraints that can prevent edits
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
interface IEditConstraint extends IEditObject {

	/** @var string - Constraint passed, no error */
	public const CONSTRAINT_PASSED = 'constraint-passed';

	/** @var string - Constraint failed, use getLegacyStatus to see the failure */
	public const CONSTRAINT_FAILED = 'constraint-failed';

	/**
	 * @return string whether the constraint passed, either CONSTRAINT_PASSED or CONSTRAINT_FAILED
	 */
	public function checkConstraint(): string;

	/**
	 * Get the legacy status for failure (or success)
	 *
	 * Called "legacy" status because this part of the interface should probably be redone;
	 * Currently Status objects have a value of an IEditObject constant, as well as a fatal
	 * message
	 *
	 * @return StatusValue
	 */
	public function getLegacyStatus(): StatusValue;

}
