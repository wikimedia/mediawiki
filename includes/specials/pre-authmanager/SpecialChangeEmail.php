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
class SpecialChangeEmailPreAuthManager extends FormSpecialPage {
	/**
	 * @var Status
	 */
	private $status;

	public function __construct() {
		parent::__construct( 'ChangeEmail', 'editmyprivateinfo' );
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function isListed() {
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

		parent::execute( $par );
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
		$user = $this->getUser();

		$fields = [
			'Name' => [
				'type' => 'info',
				'label-message' => 'username',
				'default' => $user->getName(),
			],
			'OldEmail' => [
				'type' => 'info',
				'label-message' => 'changeemail-oldemail',
				'default' => $user->getEmail() ?: $this->msg( 'changeemail-none' )->text(),
			],
			'NewEmail' => [
				'type' => 'email',
				'label-message' => 'changeemail-newemail',
				'autofocus' => true,
				'help-message' => 'changeemail-newemail-help',
			],
		];

		if ( $this->getConfig()->get( 'RequirePasswordforEmailChange' ) ) {
			$fields['Password'] = [
				'type' => 'password',
				'label-message' => 'changeemail-password'
			];
		}

		return $fields;
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setId( 'mw-changeemail-form' );
		$form->setTableId( 'mw-changeemail-table' );
		$form->setSubmitTextMsg( 'changeemail-submit' );
		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$form->addHeaderText( $this->msg( 'changeemail-header' )->parseAsBlock() );
		if ( $this->getConfig()->get( 'RequirePasswordforEmailChange' ) ) {
			$form->addHeaderText( $this->msg( 'changeemail-passwordrequired' )->parseAsBlock() );
		}
	}

	public function onSubmit( array $data ) {
		$password = isset( $data['Password'] ) ? $data['Password'] : null;
		$status = $this->attemptChange( $this->getUser(), $password, $data['NewEmail'] );

		$this->status = $status;

		return $status;
	}

	public function onSuccess() {
		$request = $this->getRequest();

		$returnto = $request->getVal( 'returnto' );
		$titleObj = $returnto !== null ? Title::newFromText( $returnto ) : null;
		if ( !$titleObj instanceof Title ) {
			$titleObj = Title::newMainPage();
		}
		$query = $request->getVal( 'returntoquery' );

		if ( $this->status->value === true ) {
			$this->getOutput()->redirect( $titleObj->getFullURL( $query ) );
		} elseif ( $this->status->value === 'eauth' ) {
			# Notify user that a confirmation email has been sent...
			$this->getOutput()->wrapWikiMsg( "<div class='error' style='clear: both;'>\n$1\n</div>",
				'eauthentsent', $this->getUser()->getName() );
			// just show the link to go back
			$this->getOutput()->addReturnTo( $titleObj, wfCgiToArray( $query ) );
		}
	}

	/**
	 * @param User $user
	 * @param string $pass
	 * @param string $newaddr
	 * @return Status
	 */
	private function attemptChange( User $user, $pass, $newaddr ) {
		global $wgAuth;

		if ( $newaddr != '' && !Sanitizer::validateEmail( $newaddr ) ) {
			return Status::newFatal( 'invalidemailaddress' );
		}

		if ( $newaddr === $user->getEmail() ) {
			return Status::newFatal( 'changeemail-nochange' );
		}

		$throttleInfo = LoginForm::incrementLoginThrottle( $user->getName() );
		if ( $throttleInfo ) {
			$lang = $this->getLanguage();
			return Status::newFatal(
				'changeemail-throttled',
				$lang->formatDuration( $throttleInfo['wait'] )
			);
		}

		if ( $this->getConfig()->get( 'RequirePasswordforEmailChange' )
			&& !$user->checkTemporaryPassword( $pass )
			&& !$user->checkPassword( $pass )
		) {
			return Status::newFatal( 'wrongpassword' );
		}

		LoginForm::clearLoginThrottle( $user->getName() );

		$oldaddr = $user->getEmail();
		$status = $user->setEmailWithConfirmation( $newaddr );
		if ( !$status->isGood() ) {
			return $status;
		}

		Hooks::run( 'PrefsEmailAudit', [ $user, $oldaddr, $newaddr ] );

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
