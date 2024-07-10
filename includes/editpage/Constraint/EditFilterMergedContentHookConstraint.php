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

use ApiMessage;
use Content;
use Language;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use StatusValue;

/**
 * Verify `EditFilterMergedContent` hook
 *
 * @since 1.36
 * @author DannyS712
 * @internal
 */
class EditFilterMergedContentHookConstraint implements IEditConstraint {

	private HookRunner $hookRunner;
	private Content $content;
	private IContextSource $hookContext;
	private string $summary;
	private bool $minorEdit;
	private Language $language;
	private User $hookUser;
	private Status $status;
	private string $hookError = '';

	/**
	 * @param HookContainer $hookContainer
	 * @param Content $content
	 * @param IContextSource $hookContext NOTE: This should only be passed to the hook.
	 * @param string $summary
	 * @param bool $minorEdit
	 * @param Language $language
	 * @param User $hookUser NOTE: This should only be passed to the hook.
	 */
	public function __construct(
		HookContainer $hookContainer,
		Content $content,
		IContextSource $hookContext,
		string $summary,
		bool $minorEdit,
		Language $language,
		User $hookUser
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->content = $content;
		$this->hookContext = $hookContext;
		$this->summary = $summary;
		$this->minorEdit = $minorEdit;
		$this->language = $language;
		$this->hookUser = $hookUser;
		$this->status = Status::newGood();
	}

	public function checkConstraint(): string {
		$hookResult = $this->hookRunner->onEditFilterMergedContent(
			$this->hookContext,
			$this->content,
			$this->status,
			$this->summary,
			$this->hookUser,
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
				if ( !$this->status->getErrors() ) {
					// Provide a fallback error message if none was set
					$this->status->fatal( 'hookaborted' );
				}
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
	 * Wrap status errors in error boxes for increased visibility.
	 * @param Status $status
	 * @return string
	 */
	private function formatStatusErrors( Status $status ): string {
		$ret = '';
		foreach ( $status->getErrors() as $rawError ) {
			// XXX: This interface is ugly, but it seems to be the only convenient way to convert a message specifier
			// as used in Status to a Message without all the cruft that Status::getMessage & friends add.
			$msg = Message::newFromSpecifier( ApiMessage::create( $rawError ) );
			$ret .= Html::errorBox( "\n" . $msg->inLanguage( $this->language )->plain() . "\n" );
		}
		return $ret;
	}

}
