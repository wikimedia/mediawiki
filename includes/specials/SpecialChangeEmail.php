<?php
/**
 * Implements Special:ChangeEmail
 *
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
 * @ingroup SpecialPage
 */

/**
 * Let users change their email address.
 *
 * @ingroup SpecialPage
 */
class SpecialChangeEmail extends FormSpecialPage {
	/**
	 * @var Status
	 */
	private $status;

	public function __construct() {
		parent::__construct( 'ChangeEmail', 'editmyprivateinfo' );
	}

	/**
	 * @return bool
	 */
	function isListed() {
		global $wgAuth;

		return $wgAuth->allowPropChange( 'emailaddress' );
	}

	/**
	 * Main execution point
	 * @param string $par
	 */
	function execute( $par ) {
		$out = $this->getOutput();
		$out->disallowUserJs();
		$out->addModules( 'mediawiki.special.changeemail' );

		return parent::execute( $par );
	}

	protected function checkExecutePermissions( User $user ) {
		global $wgAuth;

		if ( !$wgAuth->allowPropChange( 'emailaddress' ) ) {
			throw new ErrorPageError( 'changeemail', 'cannotchangeemail' );
		}

		$this->requireLogin( 'changeemail-no-info' );

		// This could also let someone check the current email address, so
		// require both permissions.
		if ( !$this->getUser()->isAllowed( 'viewmyprivateinfo' ) ) {
			throw new PermissionsError( 'viewmyprivateinfo' );
		}

		parent::checkExecutePermissions( $user );
	}

	protected function getFormFields() {
		global $wgRequirePasswordforEmailChange;

		$user = $this->getUser();
		$request = $this->getRequest();

		$oldEmailText = $user->getEmail()
			? $user->getEmail()
			: $this->msg( 'changeemail-none' )->text();

		$fields = array(
			'Name' => array(
				'type' => 'info',
				'label-message' => 'username',
				'default' => $user->getName(),
			),
			'OldEmail' => array(
				'type' => 'info',
				'label-message' => 'changeemail-oldemail',
				'default' => $oldEmailText,
			),
			'NewEmail' => array(
				'type' => 'email',
				'label-message' => 'changeemail-newemail',
				'default' => $request->getVal( 'wpNewEmail' ),
			),
		);

		if ( $wgRequirePasswordforEmailChange ) {
			$fields['Password'] = array(
				'type' => 'password',
				'label-message' => 'changeemail-password',
				'default' => $request->getVal( 'wpPassword' ),
				'autofocus' => true,
			);
		}

		return $fields;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setId( 'mw-changeemail-form' );
		$form->setTableId( 'mw-changeemail-table' );
		$form->setWrapperLegendMsg( 'changeemail-header' );
		$form->setSubmitTextMsg( 'changeemail-submit' );
		$form->addButton( 'wpCancel',  $this->msg( 'changeemail-cancel' )->text() );
		$form->addHiddenField( 'returnto', $this->getRequest()->getVal( 'returnto' ) );
	}

	public function onSubmit( array $data ) {
		if ( $this->getRequest()->getBool( 'wpCancel' ) ) {
			$status = Status::newGood( true );
		} else {
			$password = isset( $data['Password'] ) ? $data['Password'] : null;
			$status = $this->attemptChange( $this->getUser(), $password, $data['NewEmail'] );
		}

		$this->status = $status;

		return $status;
	}

	public function onSuccess() {
		if ( $this->status->value === true ) {
			$this->doReturnTo();
		} elseif ( $this->status->value === 'eauth' ) {
			# Notify user that a confirmation email has been sent...
			$this->getOutput()->wrapWikiMsg( "<div class='error' style='clear: both;'>\n$1\n</div>",
				'eauthentsent', $this->getUser()->getName() );
			$this->doReturnTo( 'soft' ); // just show the link to go back
		}
	}

	/**
	 * @param string $type
	 */
	protected function doReturnTo( $type = 'hard' ) {
		$titleObj = Title::newFromText( $this->getRequest()->getVal( 'returnto' ) );
		if ( !$titleObj instanceof Title ) {
			$titleObj = Title::newMainPage();
		}
		if ( $type == 'hard' ) {
			$this->getOutput()->redirect( $titleObj->getFullURL() );
		} else {
			$this->getOutput()->addReturnTo( $titleObj );
		}
	}

	/**
	 * @param $user User
	 * @param $pass string
	 * @param $newaddr string
	 * @return Status
	 */
	protected function attemptChange( User $user, $pass, $newaddr ) {
		global $wgAuth, $wgPasswordAttemptThrottle;

		if ( $newaddr != '' && !Sanitizer::validateEmail( $newaddr ) ) {
			return Status::newFatal( 'invalidemailaddress' );
		}

		$throttleCount = LoginForm::incLoginThrottle( $user->getName() );
		if ( $throttleCount === true ) {
			$lang = $this->getLanguage();
			return Status::newFatal(
				'changeemail-throttled',
				$lang->formatDuration( $wgPasswordAttemptThrottle['seconds'] )
			);
		}

		global $wgRequirePasswordforEmailChange;
		if ( $wgRequirePasswordforEmailChange
			&& !$user->checkTemporaryPassword( $pass )
			&& !$user->checkPassword( $pass )
		) {
			return Status::newFatal( 'wrongpassword' );
		}

		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $user->getName() );
		}

		$oldaddr = $user->getEmail();
		$status = $user->setEmailWithConfirmation( $newaddr );
		if ( !$status->isGood() ) {
			return $status;
		}

		wfRunHooks( 'PrefsEmailAudit', array( $user, $oldaddr, $newaddr ) );

		$user->saveSettings();

		$wgAuth->updateExternalDB( $user );

		return $status;
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}
