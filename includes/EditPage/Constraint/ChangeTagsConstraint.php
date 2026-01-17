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

	/**
	 * @param Authority $performer
	 * @param string[] $tags
	 */
	public function __construct(
		private readonly Authority $performer,
		private readonly array $tags,
	) {
	}

	public function checkConstraint(): StatusValue {
		if ( !$this->tags ) {
			return StatusValue::newGood();
		}

		// TODO inject a service once canAddTagsAccompanyingChange is moved to a
		// service as part of T245964
		$changeTagStatus = ChangeTags::canAddTagsAccompanyingChange(
			$this->tags,
			$this->performer,
			false
		);

		if ( !$changeTagStatus->isOK() ) {
			$changeTagStatus->value = self::AS_CHANGE_TAG_ERROR;
		}

		return $changeTagStatus;
	}

}
