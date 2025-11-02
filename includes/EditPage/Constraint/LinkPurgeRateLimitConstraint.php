<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use StatusValue;

/**
 * Verify that the user doesn't exceed 'linkpurge' limits, which are weird and special.
 * Other rate limits have been integrated into their respective permission checks.
 *
 * @since 1.44
 * @internal
 * @author DannyS712
 */
class LinkPurgeRateLimitConstraint implements IEditConstraint {

	private string $result;

	public function __construct(
		private readonly RateLimiter $limiter,
		private readonly RateLimitSubject $subject,
	) {
	}

	private function limit( string $action, int $inc = 1 ): bool {
		return $this->limiter->limit( $this->subject, $action, $inc );
	}

	public function checkConstraint(): string {
		// TODO inject and use a ThrottleStore once available, see T261744
		// Checking if the user is rate limited increments the counts, so we cannot perform
		// the check again when getting the status; thus, store the result
		if ( $this->limit( 'linkpurge', /* only counted after the fact */ 0 ) ) {
			$this->result = self::CONSTRAINT_FAILED;
		} else {
			$this->result = self::CONSTRAINT_PASSED;
		}

		return $this->result;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$statusValue->fatal( 'actionthrottledtext' );
			$statusValue->value = self::AS_RATE_LIMITED;
		}

		return $statusValue;
	}

}
