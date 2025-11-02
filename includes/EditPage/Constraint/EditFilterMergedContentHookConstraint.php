<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
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

	private readonly HookRunner $hookRunner;
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
		private readonly Content $content,
		private readonly IContextSource $hookContext,
		private readonly string $summary,
		private readonly bool $minorEdit,
		private readonly Language $language,
		private readonly User $hookUser,
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
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
				if ( !$this->status->getMessages() ) {
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
			if ( !$this->status->getMessages() ) {
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
	 * will be used as wikitext, which is what the hookError represents, rather
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
		foreach ( $status->getMessages() as $msg ) {
			$msg = Message::newFromSpecifier( $msg );
			$ret .= Html::errorBox( "\n" . $msg->inLanguage( $this->language )->plain() . "\n" );
		}
		return $ret;
	}

}
