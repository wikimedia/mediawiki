<?php

/**
 * Implements Special:ChangePassword
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
 * Let users change their password or recover their password using an email-sent
 * recovery token.
 *
 * @author Tyler Romeo
 * @ingroup SpecialPage
 */
class SpecialChangePassword extends FormSpecialPage {
	/**
	 * Whether the user is logged in and just changing their password or
	 * is not logged in and is recovering their password from email.
	 */
	private $loggedin;

	/**
	 * Authentication token for users recovering their password from email.
	 */
	private $token;

	/**
	 * Call parent constructor and then see if user passed an authentication
	 * token (for users recovering their password from email).
	 */
	function __construct() {
		parent::__construct( 'ChangePassword' );
		$this->token = $this->getRequest()->getVal( 'token', null );
	}

	/**
	 * Allow users to change their password even if they're blocked.
	 *
	 * @return bool false
	 */
	function requiresUnblock() {
		return false;
	}

	/**
	 * Only list this special page if the user is logged in.
	 *
	 * @return bool
	 */
	function isListed() {
		return $this->getUser()->isLoggedIn();
	}

	/**
	 * First check if secure login is enabled, and redirect to HTTPS if we
	 * need to. Otherwise, let FormSpecialPage::execute handle the rest.
	 *
	 * @see FormSpecialPage::execute
	 * @param string $par Subpage
	 */
	function execute( $par ) {
		if( session_id() == '' ) {
			wfSetupSession();
		}

		global $wgSecureLogin;
		$request = $this->getRequest();
		if( $wgSecureLogin && WebRequest::detectProtocol() !== 'https' ) {
			$title = $this->getFullTitle();
			$query = array(
				'returnto' => $request->getVal( 'returnto' ),
				'returntoquery' => $request->getVal( 'returntoquery' ),
			);
			$url = $title->getFullURL( $query, false, PROTO_HTTPS );

			$this->getOutput()->redirect( $url );
		} else {
			parent::execute( $par );
		}
	}

	/**
	 * Parse the sub-page as a username. If the user is currently not logged in
	 * and has given both a username and token (not necessarily valid), use that
	 * as the User instead of the current anonymous IP address user.
	 *
	 * @param $par Sub-page parameter
	 */
	function setParameter( $par ) {
		// If the user is already logged in, or if no username is given,
		// or if no token was given for a not-logged-in user, do nothing.
		$this->loggedin = $this->getUser()->isLoggedIn();
		if( $par == '' || $this->token === null || $this->loggedin ) {
			return;
		}

		$user = User::newFromName( $par );
		// Check if user is invalid and token is correct.
		if( !$user instanceof User || $user->isAnon() || !$user->checkTemporaryPassword( $this->token ) ) {
			return;
		}

		// User is good. We are now that user.
		$this->getContext()->setUser( $user );
	}

	/**
	 * Check if user is not logged in.
	 *
	 * Since SpecialChangePassword::setParameter() changes the user before this
	 * is executed, we do not have to worry about users recovering their passwords
	 * from email being seen as not logged in.
	 *
	 * @param User $user User attempting request
	 * @throws UserNotLoggedIn When user is not logged in
	 */
	function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );
		if( $user->isAnon() ) {
			throw new UserNotLoggedIn;
		}
	}

	/**
	 * Add header text, set the submit button text, and disallow user JavaScript.
	 *
	 * @param HTMLForm $form Form for the request
	 */
	function alterForm( HTMLForm $form ) {
		$form->addHeaderText( $this->msg( 'resetpass_text' )->parseAsBlock() );
		$form->setSubmitTextMsg( $this->loggedin ? 'resetpass-submit-loggedin' : 'resetpass_submit' );
		$this->getOutput()->disallowUserJs();
	}

	/**
	 * Get a list of form fields for the form.
	 *
	 * @return array
	 */
	function getFormFields() {
		global $wgCookieExpiration;

		$a = array();
		$user = $this->getUser();

		$a['Token'] = array(
			'name' => 'token',
			'type' => 'hidden',
			'default' => $this->token
		);

		$a['Username'] = array(
			'type' => 'info',
			'label-message' => 'username',
			'default' => $user->getName(),
		);

		// If user is recovering password from email, i.e., a token has been passed to this form,
		// then don't show the old password field, as they probably don't know their password.
		if( $this->loggedin ) {
			$a['Password'] = array(
				'type' => $this->loggedin ? 'password' : 'hidden',
				'label-message' => 'oldpassword',
				'required' => true
			);
		}

		$a['NewPassword'] = array(
			'type' => 'password',
			'label-message' => 'newpassword',
			'validation-callback' => array( $user, 'isValidPassword' ),
			'required' => true
		);

		$a['Retype'] = array(
			'type' => 'password',
			'label-message' => 'retypenew',
			'validation-callback' => array( $this, 'validateRetype' ),
			'required' => true
		);

		// If user is recovering password from email, this form will log them in. If cookie expiration
		// is enabled, then show a Remember Me checkbox.
		if( !$user->isLoggedIn() && $wgCookieExpiration > 0 ) {
			$a['Remember'] = array(
				'type' => 'check',
				'label' => $this->msg( 'remembermypassword' )->numParams( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) )->text()
			);
		}

		// Legacy hook from old form.
		$extraFields = array();
		wfRunHooks( 'ChangePasswordForm', array( &$extraFields ) );
		if( $extraFields ) {
			wfDeprecated( 'ChangePasswordForm hook', 1.21 );
			$a['ExtraFields'] = array(
				'type' => 'info',
				'raw' => true,
				'rawrow' => true,
				'default' => $this->pretty( $extraFields )
			);
		}

		$request = $this->getRequest();
		$passthroughFields = array( 'returnto', 'returntoquery' );
		foreach( $passthroughFields as $field ) {
			$val = $request->getVal( $field );
			if( !$val ) {
				continue;
			}
			$a[$field] = array(
				'type' => 'hidden',
				'name' => $field,
				'default' => $val
			);
		}

		// New HTMLForm compatible hook.
		wfRunHooks( 'ChangePasswordFields', array( &$a ) );

		return $a;
	}

	/**
	 * Verify the form data and change the user's password if appropriate.
	 *
	 * First check if new password and retype match and if the throttle has
	 * kicked in. Then check the old password: if user is recovering from email,
	 * the token is checked as the old password; otherwise, the old password
	 * field is checked. Finally, try and set the user's password. If all is OK,
	 * log the user and email-confirm if necessary.
	 *
	 * @param array $data Data from HTMLForm
	 * @return Status
	 */
	function onSubmit( array $data ) {
		$status = Status::newGood();
		$user = $this->getUser();

		if( LoginForm::incLoginThrottle( $data['Username'] ) === true ) {
			$status->fatal( 'login-throttled' );
		} elseif(
			!$this->loggedin && !$user->checkTemporaryPassword( $this->token ) ||
			$this->loggedin && !$user->checkPassword( $data['Password'] )
		) {
			$status->fatal( 'wrongpassword' );
		} else {
			// User::setPassword will throw an exception for bad passwords,
			// although it should be valid since HTMLForm checked it.
			try {
				$user->setPassword( $data['NewPassword'] );
				$user->saveSettings();
				// If we've gotten to here without an exception, the password was successfully set.
				LoginForm::clearLoginThrottle( $data['Username'] );
			} catch( PasswordError $e ) {
				$status->fatal( $e->getMessage() );
			}
		}

		$errors = $status->getErrorsArray();
		$hookMessage = $status->isOK() ? 'success' : $errors[0][0];
		wfRunHooks( 'PrefsPasswordAudit', array( $user, $data['NewPassword'], $hookMessage ) );

		if( $status->isOK() ) {
			// User came from email, so a success means their email is confirmed.
			if( !$this->loggedin && !$user->isEmailConfirmed() ) {
				$user->confirmEmail();
				$user->saveSettings();
			}

			// Set the user's cookies since their token changed.
			$user->setCookies();
		}

		return $status;
	}

	/**
	 * If user is logged in and has not specified a returnto, show a success message.
	 * Otherwise, redirect to the returnto.
	 */
	function onSuccess() {
		$this->getOutput()->addWikiMsg( 'resetpass_success' );

		$titleObj = Title::newFromText( $this->getRequest()->getVal( 'returnto' ) );
		if ( $titleObj instanceof Title ) {
			$query = $this->getRequest()->getVal( 'returntoquery' );
			$this->getOutput()->redirect( $titleObj->getFullURL( $query, false, PROTO_CURRENT ) );
		} elseif( !$this->loggedin ) {
			// Only return to Main Page when the user is coming from email and has
			// not specified a returnto.
			$this->getOutput()->redirect( Title::newMainPage()->getFullURL() );
		}
	}

	/**
	 * Validation callback to check if the retype is equal to the
	 * new password.
	 *
	 * @param string $value The retype of the new password
	 * @param array $alldata Other data from the form
	 * @param HTMLForm $form Form that was submitted
	 * @return Status
	 */
	public function validateRetype( $value, array $alldata, HTMLForm $form ) {
		return $value === $alldata['NewPassword'] ? true : $this->msg( 'badretype' );
	}

	/**
	 * Legacy function for the old ChangePasswordForm hook. Take an array
	 * of fields from the hook and format them for the form.
	 *
	 * @param array $fields Fields to parse
	 * @deprecated Do not use the ChangePasswordForm hook
	 * @return string HTML to output
	 */
	private function pretty( array $fields ) {
		$out = '';
		foreach ( $fields as $list ) {
			list( $name, $label, $type, $value ) = $list;
			if ( $type == 'text' ) {
				$field = htmlspecialchars( $value );
			} else {
				$attribs = array( 'id' => $name );
				if ( $name == 'wpNewPassword' || $name == 'wpRetype' ) {
					$attribs = array_merge( $attribs,
						User::passwordChangeInputAttribs() );
				}
				if ( $name == 'wpPassword' ) {
					$attribs[] = 'autofocus';
				}
				$field = Html::input( $name, $value, $type, $attribs );
			}
			$out .= "<tr>\n";
			$out .= "\t<td class='mw-label'>";

			if ( $type != 'text' ) {
				$out .= Xml::label( $this->msg( $label )->text(), $name );
			} else {
				$out .= $this->msg( $label )->escaped();
			}

			$out .= "</td>\n";
			$out .= "\t<td class='mw-input'>";
			$out .= $field;
			$out .= "</td>\n";
			$out .= "</tr>";
		}

		return $out;
	}
}
