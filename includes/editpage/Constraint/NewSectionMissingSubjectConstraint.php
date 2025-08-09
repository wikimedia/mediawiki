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
