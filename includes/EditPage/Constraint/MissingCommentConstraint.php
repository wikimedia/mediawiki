<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\PageEdit\PageEditStatus;

/**
 * Do not allow the user to post an empty comment (only used for new section)
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class MissingCommentConstraint extends EditConstraint {

	public function __construct(
		private readonly string $section,
		private readonly string $userComment,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		if ( $this->section === 'new' && $this->userComment === '' ) {
			return PageEditStatus::newFatal( 'missingcommenttext' )
				->setValue( self::AS_TEXTBOX_EMPTY );
		}
		return PageEditStatus::newGood();
	}

}
