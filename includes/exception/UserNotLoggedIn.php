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
 * constructor has to be explicitly added to LoginHelper::validErrorMessages or with
 * the LoginFormValidErrorMessages hook. Otherwise, the user will just be shown the message
 * rather than redirected.
 *
 * @par Example:
 * @code
 * if( $user->isAnon() ) {
 *   throw new UserNotLoggedIn();
 * }
 * @endcode
 *
 * Note the parameter order differs from ErrorPageError, this allows you to
 * simply specify a reason without overriding the default title.
 *
 * @par Example:
 * @code
 * if( $user->isAnon() ) {
 *   throw new UserNotLoggedIn( 'action-require-loggedin' );
 * }
 * @endcode
 *
 * @see T39627
 * @since 1.20
 * @ingroup Exception
 */
class UserNotLoggedIn extends PermissionsError {

	private $exMessage;

	/**
	 * @var Title|null
	 */
	private $returnTo;

	/**
	 * @note The value of the $reasonMsg parameter must be put into LoginForm::validErrorMessages or
	 * set with the LoginFormValidErrorMessages Hook if you want the user to be automatically
	 * redirected to the login form.
	 *
	 * @param string $reasonMsg A message key containing the reason for the error.
	 *        Optional, default: 'exception-nologin-text'
	 * @param string $titleMsg A message key to set the page title.
	 *        Optional, default: 'exception-nologin'
	 * @param array $params Parameters to wfMessage().
	 *        Optional, default: []
	 * @param Title|null $returnTo The return to target after a succesfull login, if a redirect
	 *        to login will happen. Optional: Defaults to RequestContext::getMain()->getTitle
	 */
	public function __construct(
		$reasonMsg = 'exception-nologin-text',
		$titleMsg = 'exception-nologin',
		$params = [],
		$returnTo = null
	) {
		$this->exMessage = wfMessage( $reasonMsg, $params );
		parent::__construct( 'doesNotMatter', [] );
		$this->title = $titleMsg;
		$this->msg = $reasonMsg;
		$this->params = $params;

		$this->returnTo = RequestContext::getMain()->getTitle();
		if ( $returnTo instanceof Title ) {
			$this->returnTo = $returnTo;
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function shouldRedirectLogin( $permission, User $user ) {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageObject() {
		return $this->exMessage;
	}
}
