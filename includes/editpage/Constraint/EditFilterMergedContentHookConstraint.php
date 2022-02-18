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

use Content;
use IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use Status;
use StatusValue;

/**
 * Verify `EditFilterMergedContent` hook
 *
 * @since 1.36
 * @author DannyS712
 * @internal
 */
class EditFilterMergedContentHookConstraint implements IEditConstraint {

	/** @var HookRunner */
	private $hookRunner;

	/** @var Content */
	private $content;

	/** @var IContextSource */
	private $context;

	/** @var string */
	private $summary;

	/** @var bool */
	private $minorEdit;

	/** @var Status */
	private $status;

	/** @var string */
	private $hookError = '';

	/**
	 * @param HookContainer $hookContainer
	 * @param Content $content
	 * @param IContextSource $context
	 * @param string $summary
	 * @param bool $minorEdit
	 */
	public function __construct(
		HookContainer $hookContainer,
		Content $content,
		IContextSource $context,
		string $summary,
		bool $minorEdit
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->content = $content;
		$this->context = $context;
		$this->summary = $summary;
		$this->minorEdit = $minorEdit;
		$this->status = Status::newGood();
	}

	public function checkConstraint(): string {
		$hookResult = $this->hookRunner->onEditFilterMergedContent(
			$this->context,
			$this->content,
			$this->status,
			$this->summary,
			$this->context->getUser(),
			$this->minorEdit
		);
		if ( !$hookResult ) {
			// Error messages etc. could be handled within the hook...
			if ( $this->status->isGood() ) {
				$this->status->fatal( 'hookaborted' );
				// Not setting $this->hookError here is a hack to allow the hook
				// to cause a return to the edit page without $this->hookError
				// being set. This is used by ConfirmEdit to display a captcha
				// without any error message cruft.
			} else {
				$this->hookError = $this->formatStatusErrors( $this->status );
			}
			// Use the existing $status->value if the hook set it
			if ( !$this->status->value ) {
				// T273354: Should be AS_HOOK_ERROR_EXPECTED to display error message
				$this->status->value = self::AS_HOOK_ERROR_EXPECTED;
			}
			return self::CONSTRAINT_FAILED;
		}

		if ( !$this->status->isOK() ) {
			// ...or the hook could be expecting us to produce an error
			// FIXME this sucks, we should just use the Status object throughout
			if ( !$this->status->getErrors() ) {
				// Provide a fallback error message if none was set
				$this->status->fatal( 'hookaborted' );
			}
			$this->hookError = $this->formatStatusErrors( $this->status );
			$this->status->value = self::AS_HOOK_ERROR_EXPECTED;
			return self::CONSTRAINT_FAILED;
		}

		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		// This returns a Status instead of a StatusValue since a Status object is
		// used in the hook
		return $this->status;
	}

	/**
	 * TODO this is really ugly. The constraint shouldn't know that the status
	 * will be used as wikitext, with is what the hookError represents, rather
	 * than just the error code. This needs a big refactor to remove the hook
	 * error string and just rely on the status object entirely.
	 *
	 * @internal
	 * @return string
	 */
	public function getHookError(): string {
		return $this->hookError;
	}

	/**
	 * Wrap status errors in an errorbox for increased visibility
	 * @param Status $status
	 * @return string
	 */
	private function formatStatusErrors( Status $status ): string {
		$errmsg = $status->getWikiText(
			'edit-error-short',
			'edit-error-long',
			$this->context->getLanguage()
		);
		return <<<ERROR
<div class="errorbox">
{$errmsg}
</div>
<br clear="all" />
ERROR;
	}

}
