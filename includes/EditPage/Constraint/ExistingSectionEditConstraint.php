<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use StatusValue;
use Wikimedia\Message\MessageValue;

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

	public function __construct(
		private readonly string $section,
		private readonly string $userSummary,
		private readonly string $autoSummary,
		private readonly bool $allowBlankSummary,
		private readonly Content $newContent,
		private readonly ?Content $originalContent,
		private readonly string $submitButtonLabel,
	) {
	}

	public function checkConstraint(): StatusValue {
		if ( $this->section === 'new' ) {
			// Constraint is not applicable
			return StatusValue::newGood();
		}

		if ( $this->originalContent === null ) {
			// T301947: User loses access to revision after loading
			// The error message, rev-deleted-text-permission, is not
			// really in use currently. It's added for completeness and in
			// case any code path wants to know the error.
			return StatusValue::newGood( self::AS_REVISION_WAS_DELETED )
				->fatal( 'rev-deleted-text-permission' );
		}
		if (
			!$this->allowBlankSummary &&
			!$this->newContent->equals( $this->originalContent ) &&
			!$this->newContent->isRedirect() &&
			md5( $this->userSummary ) === $this->autoSummary
		) {
			return StatusValue::newGood()
				->setResult( false, self::AS_SUMMARY_NEEDED )
				->warning(
					'missingsummary',
					MessageValue::new( $this->submitButtonLabel )
				);
		}

		return StatusValue::newGood();
	}

}
