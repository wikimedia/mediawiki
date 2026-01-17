<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * For a new section, do not allow the user to post with an empty subject (section title) unless they choose to
 *
 * @since 1.39
 * @internal
 * @author DannyS712
 */
class NewSectionMissingSubjectConstraint implements IEditConstraint {

	public function __construct(
		private readonly string $section,
		private readonly string $subject,
		private readonly bool $allowBlankSubject,
		private readonly string $submitButtonLabel,
	) {
	}

	public function checkConstraint(): StatusValue {
		if ( $this->section === 'new' &&
			!$this->allowBlankSubject &&
			trim( $this->subject ) === ''
		) {
			return StatusValue::newGood( self::AS_SUMMARY_NEEDED )
				->setOK( false )
				->warning(
					'missingcommentheader',
					MessageValue::new( $this->submitButtonLabel )
				);
		}

		return StatusValue::newGood();
	}

}
