<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\EditPage\IEditObject;

/**
 * Abstract class for all constraints that can prevent edits
 *
 * @since 1.46
 * @internal
 */
abstract class EditConstraint implements IEditObject {

	/**
	 * @return EditPageStatus A status indicating failure or success. A status that is not OK indicates a
	 * failure and will prevent saving the page.
	 */
	abstract public function checkConstraint(): EditPageStatus;

	/**
	 * @return string The name of the constraint. Used for logging, and can be overridden if a constraint class
	 * contains multiple subtypes.
	 */
	public function getName(): string {
		$fullClassName = explode( '\\', $this::class );
		return end( $fullClassName );
	}

}
