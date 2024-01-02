<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use StatusValue;

/**
 * Verify user doesn't exceed rate limits
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class UserRateLimitConstraint implements IEditConstraint {

	private RateLimitSubject $subject;
	private string $oldContentModel;
	private string $newContentModel;
	private RateLimiter $limiter;

	private string $result;

	public function __construct(
		RateLimiter $limiter,
		RateLimitSubject $subject,
		string $oldContentModel,
		string $newContentModel
	) {
		$this->limiter = $limiter;
		$this->subject = $subject;
		$this->oldContentModel = $oldContentModel;
		$this->newContentModel = $newContentModel;
	}

	private function limit( string $action, int $inc = 1 ) {
		return $this->limiter->limit( $this->subject, $action, $inc );
	}

	public function checkConstraint(): string {
		// Need to check for rate limits on `editcontentmodel` if it is changing
		$contentModelChange = ( $this->newContentModel !== $this->oldContentModel );

		// TODO inject and use a ThrottleStore once available, see T261744
		// Checking if the user is rate limited increments the counts, so we cannot perform
		// the check again when getting the status; thus, store the result
		if ( $this->limit( 'edit' )
			|| $this->limit( 'linkpurge', 0 ) // only counted after the fact
			|| ( $contentModelChange && $this->limit( 'editcontentmodel' ) )
		) {
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
