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

/**
 * Redirect a user to the login page
 *
 * This is essentially an ErrorPageError exception which by default uses the
 * 'exception-nologin' as a title and 'exception-nologin-text' for the message.
 *
 * @note In order for this exception to redirect, the error message passed to the
 * constructor has to be explicitly added to LoginForm::validErrorMessages. Otherwise,
 * the user will just be shown the message rather than redirected.
 *
 * @par Example:
 * @code
 * if( $user->isAnon() ) {
 * 	throw new UserNotLoggedIn();
 * }
 * @endcode
 *
 * Note the parameter order differs from ErrorPageError, this allows you to
 * simply specify a reason without overriding the default title.
 *
 * @par Example:
 * @code
 * if( $user->isAnon() ) {
 * 	throw new UserNotLoggedIn( 'action-require-loggedin' );
 * }
 * @endcode
 *
 * @see bug 37627
 * @since 1.20
 * @ingroup Exception
 */
class UserNotLoggedIn extends ErrorPageError {

	/**
	 * @note The value of the $reasonMsg parameter must be put into LoginForm::validErrorMessages
	 * if you want the user to be automatically redirected to the login form.
	 *
	 * @param string $reasonMsg A message key containing the reason for the error.
	 *        Optional, default: 'exception-nologin-text'
	 * @param string $titleMsg A message key to set the page title.
	 *        Optional, default: 'exception-nologin'
	 * @param array $params Parameters to wfMessage().
	 *        Optional, default: array()
	 */
	public function __construct(
		$reasonMsg = 'exception-nologin-text',
		$titleMsg = 'exception-nologin',
		$params = array()
	) {
		parent::__construct( $titleMsg, $reasonMsg, $params );
	}

	/**
	 * Redirect to Special:Userlogin if the specified message is compatible. Otherwise,
	 * show an error page as usual.
	 */
	public function report() {
		// If an unsupported message is used, don't try redirecting to Special:Userlogin,
		// since the message may not be compatible.
		if ( !in_array( $this->msg, LoginForm::$validErrorMessages ) ) {
			parent::report();
		}

		// Message is valid. Redirec to Special:Userlogin

		$context = RequestContext::getMain();

		$output = $context->getOutput();
		$query = $context->getRequest()->getValues();
		// Title will be overridden by returnto
		unset( $query['title'] );
		// Redirect to Special:Userlogin
		$output->redirect( SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( array(
			// Return to this page when the user logs in
			'returnto' => $context->getTitle()->getFullText(),
			'returntoquery' => wfArrayToCgi( $query ),
			'warning' => $this->msg,
		) ) );

		$output->output();
	}
}
