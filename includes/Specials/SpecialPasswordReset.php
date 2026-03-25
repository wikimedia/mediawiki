<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\ThrottledError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\Helpers\LoginHelper;
use MediaWiki\Status\Status;
use MediaWiki\User\PasswordReset;
use MediaWiki\User\User;

/**
 * Special page for requesting a password reset email.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent
 * functionality) to be enabled.
 *
 * @ingroup SpecialPage
 */
class SpecialPasswordReset extends FormSpecialPage {
	/** @var LoginHelper */
	protected $loginHelper = null;

	public function __construct(
		private readonly PasswordReset $passwordReset,
	) {
		parent::__construct( 'PasswordReset', 'editmyprivateinfo' );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function userCanExecute( User $user ) {
		return $this->passwordReset->isAllowed( $user )->isGood();
	}

	public function checkExecutePermissions( User $user ) {
		$status = Status::wrap( $this->passwordReset->isAllowed( $user ) );
		if ( !$status->isGood() ) {
			throw new ErrorPageError( 'internalerror', $status->getMessage() );
		}

		parent::checkExecutePermissions( $user );
	}

	/**
	 * Get the login helper.
	 * @return LoginHelper
	 */
	private function getLoginHelper(): LoginHelper {
		if ( $this->loginHelper === null ) {
			$this->loginHelper = new LoginHelper( $this->getContext() );
		}
		return $this->loginHelper;
	}

	/**
	 * @param string|null $par
	 */
	public function execute( $par ) {
		// Use the authentication-popup skin in popup mode.
		if ( $this->getLoginHelper()->isDisplayModePopup() ) {
			$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
			$this->getContext()->setSkin( $skinFactory->makeSkin( 'authentication-popup' ) );
		}

		$out = $this->getOutput();
		$out->disallowUserJs();
		parent::execute( $par );
	}

	/** @inheritDoc */
	protected function getFormFields() {
		$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );
		$a = [];
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$a['Username'] = [
				'type' => 'user',
				'default' => $this->getRequest()->getSession()->suggestLoginUsername(),
				'label-message' => 'passwordreset-username',
				'excludetemp' => true,
			];

			if ( $this->getUser()->isRegistered() ) {
				$a['Username']['default'] = $this->getUser()->getName();
			}
		}

		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$a['Email'] = [
				'type' => 'email',
				'label-message' => 'passwordreset-email',
			];
		}

		return $a;
	}

	/**
	 * Get preserved URL parameters.
	 * @return array
	 */
	private function getPreservedParams(): array {
		$params = [];

		$request = $this->getRequest();
		$value = $request->getRawVal( 'display' );
		if ( $value !== null ) {
			$params['display'] = $value;
		}

		return $params;
	}

	/** @inheritDoc */
	protected function getForm() {
		$form = parent::getForm();
		$form->setAction( $this->getFullTitle()->getFullURL( $this->getPreservedParams() ) );
		return $form;
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'codex';
	}

	public function alterForm( HTMLForm $form ) {
		$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );

		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$i = 0;
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$i++;
		}
		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$i++;
		}

		$message = ( $i > 1 ) ? 'passwordreset-text-many' : 'passwordreset-text-one';

		$form->setHeaderHtml( $this->msg( $message, $i )->parseAsBlock() );
		$form->setSubmitTextMsg( 'mailmypassword' );
	}

	/**
	 * Process the form.
	 * At this point, we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc., then Username
	 * resets are allowed.
	 * @param array $data
	 * @return Status
	 */
	public function onSubmit( array $data ) {
		$username = $data['Username'] ?? null;
		$email = $data['Email'] ?? null;

		$result = Status::wrap(
			$this->passwordReset->execute( $this->getUser(), $username, $email ) );

		if ( $result->hasMessage( 'actionthrottledtext' ) ) {
			throw new ThrottledError;
		}

		// Show a message on the successful processing of the form.
		// This doesn't necessarily mean a reset email was sent.
		if ( $result->isGood() ) {
			$output = $this->getOutput();

			// Information messages.
			$output->addWikiMsg( 'passwordreset-success' );
			$output->addWikiMsg( 'passwordreset-success-details-generic',
				$this->getConfig()->get( MainConfigNames::PasswordReminderResendTime ) );

			// Confirmation of what the user has just submitted.
			$info = "\n";
			if ( $username ) {
				$info .= "* " . $this->msg( 'passwordreset-username' ) . ' '
					. wfEscapeWikiText( $username ) . "\n";
			}
			if ( $email ) {
				$info .= "* " . $this->msg( 'passwordreset-email' ) . ' '
					. wfEscapeWikiText( $email ) . "\n";
			}
			$output->addWikiMsg( 'passwordreset-success-info', $info );

			if ( $this->getLoginHelper()->isDisplayModePopup() ) {
				$linkRenderer = MediaWikiServices::getInstance()->getLinkRendererFactory()->create();
				$linkClasses = [
					'mw-authentication-popup-return-to-login',
					'mw-authentication-popup-link',
					'cdx-button',
					'cdx-button--fake-button',
					'cdx-button--fake-button--enabled',
					'cdx-button--weight-primary',
					'cdx-button--action-progressive',
				];
				$link = $linkRenderer->makeLink(
					SpecialPage::getTitleFor( 'Userlogin' ),
					$this->msg( 'returnto-login' )->text(),
					[ 'class' => $linkClasses ],
					$this->getPreservedParams()
				);
				$output->addHTML( $link );
			} else {
				// Add a return to link to the main page.
				$output->returnToMain();
			}
		}

		return $result;
	}

	/**
	 * Hide the password reset page if resets are disabled.
	 * @return bool
	 */
	public function isListed() {
		if ( !$this->passwordReset->isEnabled()->isGood() ) {
			return false;
		}

		return parent::isListed();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'login';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPasswordReset::class, 'SpecialPasswordReset' );
