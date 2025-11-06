<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\Specials\Redirects;

use MediaWiki\Auth\AuthManager;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\SpecialPage\RedirectSpecialArticle;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\TempUser\TempUserCreator;

/**
 * Special page pointing to current user's talk page.
 *
 * This acts as a redirect to the user's talk page in most situations.
 * Exceptions to this, with temporary accounts enabled:
 * - If the user is logged out and not blocked, prompts the user to log in.
 * - If the user is logged out and the IP is blocked but allows the user
 *   to edit their own talk page, show a form that explains the situation
 *   to the user and allows them to create a temporary account to edit
 *   their own talk page.
 *
 * @ingroup SpecialPage
 */
class SpecialMytalk extends RedirectSpecialArticle {

	private TempUserConfig $tempUserConfig;
	private TempUserCreator $tempUserCreator;
	private AuthManager $authManager;
	private bool $shouldRedirect = true;

	public function __construct(
		TempUserConfig $tempUserConfig,
		TempUserCreator $tempUserCreator,
		AuthManager $authManager
	) {
		parent::__construct( 'Mytalk' );

		$this->tempUserConfig = $tempUserConfig;
		$this->tempUserCreator = $tempUserCreator;
		$this->authManager = $authManager;
	}

	/** @inheritDoc */
	public function execute( $subpage ) {
		if ( $this->tempUserConfig->isEnabled() && $this->getUser()->isAnon() ) {
			$this->shouldRedirect = false;
			$block = $this->getUser()->getBlock();
			if ( $block && $block->isUsertalkEditAllowed() ) {
				// Show the user a form for creating a temporary user to appeal their block
				$this->setHeaders();
				$this->outputHeader( 'mytalk-appeal-summary' );
				$form = $this->getUserTalkAppealForm();
				$status = $form->show();
				if ( $status && $status->isOK() ) {
					// Set the context user to the new temporary user, and continue to
					// redirect to the talk page.
					$this->authManager->setRequestContextUserFromSessionUser();
				} else {
					if ( $status instanceof Status ) {
						$this->getOutput()->addWikiMsg( $status->getMessage() );
					}
					return;
				}
			} else {
				// Redirect to login for anon users when temp accounts are enabled.
				$this->requireLogin();
				return;
			}
		}
		parent::execute( $subpage );
	}

	/**
	 * @return HTMLForm
	 */
	private function getUserTalkAppealForm() {
		$form = HTMLForm::factory( 'ooui', [], $this->getContext() );
		$form->setMethod( 'post' )
			->setSubmitTextMsg( 'mytalk-appeal-submit' )
			->setSubmitCallback( [ $this, 'onSubmit' ] );
		return $form;
	}

	/**
	 * Attempt to create a new temporary user on form submission.
	 *
	 * @return Status
	 */
	public function onSubmit() {
		return $this->tempUserCreator->create(
			null,
			$this->getContext()->getRequest()
		);
	}

	/** @inheritDoc */
	public function getDescription() {
		if ( !$this->shouldRedirect ) {
			return $this->msg( 'mytalk-appeal' );
		}
	}

	/** @inheritDoc */
	public function getRedirect( $subpage ) {
		if ( $this->tempUserConfig->isEnabled() && $this->getUser()->isAnon() ) {
			return false;
		}

		if ( $subpage === null || $subpage === '' ) {
			return Title::makeTitle( NS_USER_TALK, $this->getUser()->getName() );
		}

		return Title::makeTitle( NS_USER_TALK, $this->getUser()->getName() . '/' . $subpage );
	}

	/**
	 * Target identifies a specific User. See T109724.
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function personallyIdentifiableTarget() {
		return true;
	}
}
/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMytalk::class, 'SpecialMytalk' );
