<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;

/**
 * Do not allow the user to post an empty comment (only used for new section)
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class MissingCommentConstraint implements IEditConstraint {

	public function __construct(
		private readonly string $section,
		private readonly string $userComment,
	) {
	}

	public function checkConstraint(): EditPageStatus {
		if ( $this->section === 'new' && $this->userComment === '' ) {
			return EditPageStatus::newFatal( 'missingcommenttext' )
				->setValue( self::AS_TEXTBOX_EMPTY );
		}
		return EditPageStatus::newGood();
	}

}
