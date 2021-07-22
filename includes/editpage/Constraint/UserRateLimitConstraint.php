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

use StatusValue;
use Title;
use User;

/**
 * Verify user doesn't exceed rate limits
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class UserRateLimitConstraint implements IEditConstraint {

	/** @var User */
	private $user;

	/** @var Title */
	private $title;

	/** @var string */
	private $newContentModel;

	/** @var string|null */
	private $result;

	/**
	 * @param User $user
	 * @param Title $title
	 * @param string $newContentModel
	 */
	public function __construct(
		User $user,
		Title $title,
		string $newContentModel
	) {
		$this->user = $user;
		$this->title = $title;
		$this->newContentModel = $newContentModel;
	}

	public function checkConstraint(): string {
		// Need to check for rate limits on `editcontentmodel` if it is changing
		$contentModelChange = ( $this->newContentModel !== $this->title->getContentModel() );

		// TODO inject and use a ThrottleStore once available, see T261744
		// Checking if the user is rate limited increments the counts, so we cannot perform
		// the check again when getting the status; thus, store the result
		if ( $this->user->pingLimiter()
			|| $this->user->pingLimiter( 'linkpurge', 0 ) // only counted after the fact
			|| ( $contentModelChange && $this->user->pingLimiter( 'editcontentmodel' ) )
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
