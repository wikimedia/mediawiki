<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use StatusValue;

/**
 * Verify unicode constraint
 *
 * @since 1.36
 * @internal
 */
class UnicodeConstraint implements IEditConstraint {

	/**
	 * Correct unicode
	 */
	public const VALID_UNICODE = 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ';

	/**
	 * @param string $input Unicode string provided, to compare
	 */
	public function __construct(
		private readonly string $input,
	) {
	}

	public function checkConstraint(): StatusValue {
		if ( $this->input !== self::VALID_UNICODE ) {
			return StatusValue::newGood( self::AS_UNICODE_NOT_SUPPORTED )
				->fatal( 'unicode-support-fail' );
		}
		return StatusValue::newGood();
	}

}
