<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Exception\ReadOnlyError;
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

	public function checkConstraint(): EditPageStatus {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return EditPageStatus::newFatal( 'readonlytext' )
				->setValue( self::AS_READ_ONLY_PAGE )
				->setErrorFunction( static fn () => throw new ReadOnlyError() );
		}
		return EditPageStatus::newGood();
	}

}
