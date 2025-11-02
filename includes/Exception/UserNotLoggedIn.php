<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use LoginHelper;
use MediaWiki\Context\RequestContext;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * Redirect a user to the login page or account creation page
 *
 * This is essentially an ErrorPageError exception which by default uses the
 * 'exception-nologin' as a title and 'exception-nologin-text' for the message.
 *
 * When the user is a temporary account, the redirect will point to the Special:CreateAccount page unless
 * specifically set not to. In all other cases, the redirect is to the Special:UserLogin page.
 *
 * The message key for the reason will be modified to include '-for-temp-user' when the user is logged
 * in to a temporary account and this message key exists (i.e. defined and not empty).
 *
 * @note In order for this exception to redirect, the error message passed to the
 * constructor has to be explicitly added to LoginHelper::validErrorMessages or with
 * the LoginFormValidErrorMessages hook. Otherwise, the user will just be shown the message
 * rather than redirected.
 *
 * @par Example:
 * @code
 * if ( $user->isAnon() ) {
 *   throw new UserNotLoggedIn();
 * }
 * @endcode
 *
 * Note the parameter order differs from ErrorPageError, this allows you to
 * simply specify a reason without overriding the default title.
 *
 * @par Example:
 * @code
 * if ( $user->isAnon() ) {
 *   throw new UserNotLoggedIn( 'action-require-loggedin' );
 * }
 * @endcode
 *
 * You can use {@link SpecialPage::requireLogin} and {@link SpecialPage::requireNamedUser} to throw this
 * exception when the user is an anon user or not named respectively.
 *
 * @newable
 * @see T39627
 * @since 1.20
 * @ingroup Exception
 */
class UserNotLoggedIn extends ErrorPageError {

	private bool $alwaysRedirectToLoginPage;

	/**
	 * @stable to call
	 *
	 * @note The value of the $reasonMsg parameter must be set with the LoginFormValidErrorMessages
	 * hook if you want the user to be automatically redirected to the login form.
	 *
	 * @param string $reasonMsg A message key containing the reason for the error. '-for-temp-user' will be
	 *        appended to the end of the message key if the user is a temporary account and the redirect is
	 *        to the Special:CreateAccount page. The modification is skipped if the message key does not
	 *        exist.
	 *        Optional, default: 'exception-nologin-text'
	 * @param string $titleMsg A message key to set the page title.
	 *        Optional, default: 'exception-nologin'
	 * @param array $params Parameters to wfMessage() for $reasonMsg and $tempUserReasonMsg
	 *        Optional, default: []
	 * @param bool $alwaysRedirectToLoginPage Whether we should always redirect to the login page, even if the
	 *        user is a temporary account. If false (the default), the redirect will be to Special:CreateAccount
	 *        when the user is logged in to a temporary account.
	 */
	public function __construct(
		$reasonMsg = 'exception-nologin-text',
		$titleMsg = 'exception-nologin',
		$params = [],
		bool $alwaysRedirectToLoginPage = false
	) {
		$context = RequestContext::getMain();
		// Replace the reason message for one that describes creating account when the user is a temporary account
		// when such a custom message exists (T358586).
		if ( $context->getUser()->isTemp() && !$alwaysRedirectToLoginPage ) {
			// For grep to find usages: exception-nologin-text-for-temp-user
			$tempUserReasonMsg = $reasonMsg . '-for-temp-user';
			if ( $context->msg( $tempUserReasonMsg )->exists() ) {
				$reasonMsg = $tempUserReasonMsg;
			}
		}
		parent::__construct( $titleMsg, $reasonMsg, $params );
		$this->alwaysRedirectToLoginPage = $alwaysRedirectToLoginPage;
	}

	/**
	 * Redirect to Special:Userlogin or Special:CreateAccount if the specified message is compatible. Otherwise,
	 * show an error page as usual.
	 * @param int $action
	 */
	public function report( $action = self::SEND_OUTPUT ) {
		// If an unsupported message is used, don't try redirecting to Special:Userlogin,
		// since the message may not be compatible.
		if ( !in_array( $this->msg, LoginHelper::getValidErrorMessages() ) ) {
			parent::report( $action );
			return;
		}

		$context = RequestContext::getMain();

		// Message is valid. Redirect to Special:Userlogin, unless the user is a temporary account in which case
		// redirect to Special:CreateAccount (T358586).
		$specialPageName = 'Userlogin';
		if ( $context->getUser()->isTemp() && !$this->alwaysRedirectToLoginPage ) {
			$specialPageName = 'CreateAccount';
		}

		$output = $context->getOutput();
		$query = $context->getRequest()->getQueryValues();
		// Title will be overridden by returnto
		unset( $query['title'] );
		// Redirect to Special:Userlogin
		$output->redirect( SpecialPage::getTitleFor( $specialPageName )->getFullURL( [
			// Return to this page when the user logs in
			'returnto' => $context->getTitle()->getFullText(),
			'returntoquery' => wfArrayToCgi( $query ),
			'warning' => $this->msg,
			// Forward the 'display' parameter if provided
			'display' => $query['display'] ?? null,
		] ) );

		if ( $action === self::SEND_OUTPUT ) {
			$output->output();
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( UserNotLoggedIn::class, 'UserNotLoggedIn' );
