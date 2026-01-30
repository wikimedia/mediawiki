<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\Context\IContextSource;
use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use MediaWiki\User\User;

/**
 * Verify `EditFilterMergedContent` hook
 *
 * @since 1.36
 * @author DannyS712
 * @internal
 */
class EditFilterMergedContentHookConstraint implements IEditConstraint {

	private readonly HookRunner $hookRunner;
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
	}

	public function checkConstraint(): EditPageStatus {
		$status = EditPageStatus::newGood();

		$hookResult = $this->hookRunner->onEditFilterMergedContent(
			$this->hookContext,
			$this->content,
			// Status::wrap() takes references to all internal variables, allowing hook handlers to modify
			// the $status, without changing the hook interface to use the EditPageStatus type.
			Status::wrap( $status ),
			$this->summary,
			$this->hookUser,
			$this->minorEdit
		);
		if ( !$hookResult ) {
			// Error messages etc. could be handled within the hook...
			if ( $status->isGood() ) {
				$status->fatal( 'hookaborted' );
				// Not setting $this->hookError here is a hack to allow the hook
				// to cause a return to the edit page without $this->hookError
				// being set. This is used by ConfirmEdit to display a captcha
				// without any error message cruft.
			} else {
				if ( !$status->getMessages() ) {
					// Provide a fallback error message if none was set
					$status->fatal( 'hookaborted' );
				}
				$this->hookError = $this->formatStatusErrors( $status );
			}
			// Use the existing $status->value if the hook set it
			if ( !$status->value ) {
				// T273354: Should be AS_HOOK_ERROR_EXPECTED to display error message
				$status->value = self::AS_HOOK_ERROR_EXPECTED;
			}
			return $status;
		}

		if ( !$status->isOK() ) {
			// ...or the hook could be expecting us to produce an error
			// FIXME this sucks, we should just use the Status object throughout
			if ( !$status->getMessages() ) {
				// Provide a fallback error message if none was set
				$status->fatal( 'hookaborted' );
			}
			$this->hookError = $this->formatStatusErrors( $status );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return $status;
		}

		return EditPageStatus::newGood();
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
	 */
	private function formatStatusErrors( EditPageStatus $status ): string {
		$ret = '';
		foreach ( $status->getMessages() as $msg ) {
			$msg = Message::newFromSpecifier( $msg );
			$ret .= Html::errorBox( "\n" . $msg->inLanguage( $this->language )->plain() . "\n" );
		}
		return $ret;
	}

}
