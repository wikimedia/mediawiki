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

	/**
	 * @return StatusValue A status indicating failure or success. The value is an IEditObject constant. A
	 * status that is not OK indicates a failure and will prevent saving the page.
	 */
	public function checkConstraint(): StatusValue;

}
