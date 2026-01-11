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

	private ?int $status = null;

	public function __construct(
		private readonly string $section,
		private readonly string $subject,
		private readonly bool $allowBlankSubject,
		private readonly string $submitButtonLabel,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->section === 'new' &&
			!$this->allowBlankSubject &&
			trim( $this->subject ) === ''
		) {
			$this->status = self::AS_SUMMARY_NEEDED;
			return self::CONSTRAINT_FAILED;
		}

		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood( $this->status );

		if ( $this->status === self::AS_SUMMARY_NEEDED ) {
			$statusValue->setOK( false );
			$statusValue->warning(
				'missingcommentheader',
				MessageValue::new( $this->submitButtonLabel )
			);
		}

		return $statusValue;
	}

}
