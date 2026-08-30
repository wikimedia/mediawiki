<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\PageEdit\PageEditAttemptState;
use MediaWiki\PageEdit\PageEditStatus;

/**
 * For a new section, do not allow the user to post with an empty subject (section title) unless they choose to
 *
 * @since 1.39
 * @internal
 * @author DannyS712
 */
class NewSectionMissingSubjectConstraint extends EditConstraint {

	public function __construct(
		private readonly string $section,
		private readonly string $subject,
		private readonly bool $forceEditSummary,
		private readonly PageEditAttemptState $editAttemptState,
	) {
	}

	public function checkConstraint(): PageEditStatus {
		if ( $this->section === 'new' &&
			$this->forceEditSummary &&
			!$this->editAttemptState->shouldAllowBlankSummary() &&
			trim( $this->subject ) === ''
		) {
			$this->editAttemptState->setAllowBlankSummary( true );
			return PageEditStatus::newGood( self::AS_SUMMARY_NEEDED )
				->setOK( false )
				->setCanResubmit( true )
				->warning( 'edit-constraint-newsectionmissingsubject-warning' );
		}

		return PageEditStatus::newGood();
	}

}
