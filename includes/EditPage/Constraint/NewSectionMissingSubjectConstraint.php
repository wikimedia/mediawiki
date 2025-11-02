<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use StatusValue;

/**
 * For a new section, do not allow the user to post with an empty subject (section title) unless they choose to
 *
 * @since 1.39
 * @internal
 * @author DannyS712
 */
class NewSectionMissingSubjectConstraint implements IEditConstraint {

	private string $result;

	/**
	 * @param string $section
	 * @param string $subject
	 * @param bool $allowBlankSubject
	 */
	public function __construct(
		private readonly string $section,
		private readonly string $subject,
		private readonly bool $allowBlankSubject,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->section === 'new' &&
			!$this->allowBlankSubject &&
			trim( $this->subject ) === ''
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
			// From EditPage, regarding the fatal:
			// or 'missingcommentheader' if $section === 'new'. Blegh
			// For new sections, the subject is also used for the summary,
			// so we report missing summaries if the section is missing
			$statusValue->fatal( 'missingsummary' );
			$statusValue->value = self::AS_SUMMARY_NEEDED;
		}
		return $statusValue;
	}

}
