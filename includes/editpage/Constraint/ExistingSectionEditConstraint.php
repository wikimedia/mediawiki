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

use MediaWiki\Content\Content;
use StatusValue;

/**
 * To simplify the logic in EditPage, this constraint may be created even if the section being
 * edited does not currently exist, in which case $section will be 'new' and this constraint
 * will just short-circuit to CONSTRAINT_PASSED since the checks are not applicable.
 *
 * This constraint will only be used if the editor is trying to edit an existing page; if there
 * is no content, then the user has lost access to the revision after it was loaded. (T301947)
 *
 * For an edit to an existing page but not with a new section, do not allow the user to post with
 * a summary that matches the automatic summary if
 *   - the content has changed (to allow null edits without a summary, see T7365),
 *   - the new content is not a redirect (since redirecting a page has an informative automatic
 *       edit summary, see T9889), and
 *   - the user has not explicitly chosen to allow the automatic summary to be used
 *
 * For most edits, the automatic summary is blank, so checking against the automatic summary means
 * checking that any summary was given.
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ExistingSectionEditConstraint implements IEditConstraint {

	private string $result;

	/**
	 * @param string $section
	 * @param string $userSummary
	 * @param string $autoSummary
	 * @param bool $allowBlankSummary
	 * @param Content $newContent
	 * @param ?Content $originalContent
	 */
	public function __construct(
		private readonly string $section,
		private readonly string $userSummary,
		private readonly string $autoSummary,
		private readonly bool $allowBlankSummary,
		private readonly Content $newContent,
		private readonly ?Content $originalContent,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->section === 'new' ) {
			// Constraint is not applicable
			$this->result = self::CONSTRAINT_PASSED;
			return self::CONSTRAINT_PASSED;
		}
		if ( $this->originalContent === null ) {
			// T301947: User loses access to revision after loading
			$this->result = self::CONSTRAINT_FAILED;
			return self::CONSTRAINT_FAILED;
		}
		if (
			!$this->allowBlankSummary &&
			!$this->newContent->equals( $this->originalContent ) &&
			!$this->newContent->isRedirect() &&
			md5( $this->userSummary ) === $this->autoSummary
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
			if ( $this->originalContent === null ) {
				// T301947: User loses access to revision after loading
				// The error message, rev-deleted-text-permission, is not
				// really in use currently. It's added for completeness and in
				// case any code path wants to know the error.
				$statusValue->fatal( 'rev-deleted-text-permission' );
				$statusValue->value = self::AS_REVISION_WAS_DELETED;
			} else {
				$statusValue->fatal( 'missingsummary' );
				$statusValue->value = self::AS_SUMMARY_NEEDED;
			}
		}
		return $statusValue;
	}

}
