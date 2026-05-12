<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\PageEdit\PageEditStatus;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Verify site is not in read only mode
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ReadOnlyConstraint extends EditConstraint {

	public function __construct(
		private readonly ReadOnlyMode $readOnlyMode,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return PageEditStatus::newFatal( 'readonlytext' )
				->setValue( self::AS_READ_ONLY_PAGE )
				->setErrorFunction( static fn () => throw new ReadOnlyError() );
		}
		return PageEditStatus::newGood();
	}

}
