<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Permissions\Authority;
use StatusValue;

/**
 * Verify user can add change tags
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ChangeTagsConstraint implements IEditConstraint {

	/** @var StatusValue|string */
	private $result;

	/**
	 * @param Authority $performer
	 * @param string[] $tags
	 */
	public function __construct(
		private readonly Authority $performer,
		private readonly array $tags,
	) {
	}

	public function checkConstraint(): string {
		if ( !$this->tags ) {
			$this->result = self::CONSTRAINT_PASSED;
			return self::CONSTRAINT_PASSED;
		}

		// TODO inject a service once canAddTagsAccompanyingChange is moved to a
		// service as part of T245964
		$changeTagStatus = ChangeTags::canAddTagsAccompanyingChange(
			$this->tags,
			$this->performer,
			false
		);

		if ( $changeTagStatus->isOK() ) {
			$this->result = self::CONSTRAINT_PASSED;
			return self::CONSTRAINT_PASSED;
		}

		$this->result = $changeTagStatus; // The same status object is returned
		return self::CONSTRAINT_FAILED;
	}

	public function getLegacyStatus(): StatusValue {
		if ( $this->result === self::CONSTRAINT_PASSED ) {
			$statusValue = StatusValue::newGood();
		} else {
			$statusValue = $this->result;
			$statusValue->value = self::AS_CHANGE_TAG_ERROR;
		}
		return $statusValue;
	}

}
