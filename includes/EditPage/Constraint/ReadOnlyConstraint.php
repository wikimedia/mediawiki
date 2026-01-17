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

	public function __construct(
		private readonly ReadOnlyMode $readOnlyMode,
	) {
	}

	public function checkConstraint(): StatusValue {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return StatusValue::newGood( self::AS_READ_ONLY_PAGE )
				->fatal( 'readonlytext' );
		}
		return StatusValue::newGood();
	}

}
