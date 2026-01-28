<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\EditPage\IEditObject;

/**
 * Interface for all constraints that can prevent edits
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
interface IEditConstraint extends IEditObject {

	/**
	 * @return EditPageStatus A status indicating failure or success. A status that is not OK indicates a
	 * failure and will prevent saving the page.
	 */
	public function checkConstraint(): EditPageStatus;

}
