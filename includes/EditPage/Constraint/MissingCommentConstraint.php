<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use StatusValue;

/**
 * Do not allow the user to post an empty comment (only used for new section)
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class MissingCommentConstraint implements IEditConstraint {

	private bool $failed = false;

	public function __construct(
		private readonly string $section,
		private readonly string $userComment,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->section === 'new' && $this->userComment === '' ) {
			$this->failed = true;
			return self::CONSTRAINT_FAILED;
		}
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->failed ) {
			$statusValue->fatal( 'missingcommenttext' );
			$statusValue->value = self::AS_TEXTBOX_EMPTY;
		}
		return $statusValue;
	}

}
