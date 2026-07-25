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
use MediaWiki\PageEdit\PageEditStatus;
use MediaWiki\Status\Status;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;

/**
 * Verify `EditFilterMergedContent` hook
 *
 * @since 1.36
 * @author DannyS712
 * @internal
 */
class EditFilterMergedContentHookConstraint extends EditConstraint {

	private readonly HookRunner $hookRunner;

	/**
	 * @param HookContainer $hookContainer
	 * @param UserFactory $userFactory
	 * @param Content $content
	 * @param IContextSource $hookContext NOTE: This should only be passed to the hook.
	 * @param string $summary
	 * @param bool $minorEdit
	 * @param UserIdentity $hookUser NOTE: This should only be passed to the hook.
	 */
	public function __construct(
		HookContainer $hookContainer,
		private readonly UserFactory $userFactory,
		private readonly Content $content,
		private readonly IContextSource $hookContext,
		private readonly string $summary,
		private readonly bool $minorEdit,
		private readonly UserIdentity $hookUser,
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	public function checkConstraint(): PageEditStatus {
		$status = PageEditStatus::newGood();

		$hookResult = $this->hookRunner->onEditFilterMergedContent(
			$this->hookContext,
			$this->content,
			// Status::wrap() takes references to all internal variables, allowing hook handlers to modify
			// the $status, without changing the hook interface to use the PageEditStatus type.
			Status::wrap( $status ),
			$this->summary,
			$this->userFactory->newFromUserIdentity( $this->hookUser ),
			$this->minorEdit
		);
		if ( !$hookResult ) {
			// Error messages etc. could be handled within the hook...
			if ( $status->isGood() ) {
				// Not setting a status message here is a hack to allow the hook
				// to cause a return to the edit page without an error being
				// displayed.
				$status->setOK( false );
			} else {
				if ( !$status->getMessages() ) {
					// Provide a fallback error message if none was set
					$status->fatal( 'hookaborted' );
				}
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
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return $status;
		}

		return PageEditStatus::newGood();
	}
}
