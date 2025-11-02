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

	public function checkConstraint(): string {
		if ( $this->input === self::VALID_UNICODE ) {
			return self::CONSTRAINT_PASSED;
		}
		return self::CONSTRAINT_FAILED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->input !== self::VALID_UNICODE ) {
			$statusValue->fatal( 'unicode-support-fail' );
			$statusValue->value = self::AS_UNICODE_NOT_SUPPORTED;
		}
		return $statusValue;
	}

}
