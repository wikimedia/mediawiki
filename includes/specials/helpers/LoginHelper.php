<?php

/**
 * Helper functions for the login form that need to be shared with other special pages
 * (such as CentralAuth's SpecialCentralLogin).
 * @since 1.27
 */
class LoginHelper extends ContextSource {
	/**
	 * Valid error and warning messages
	 *
	 * Special:Userlogin can show an error or warning message on the form when
	 * coming from another page. This is done via the ?error= or ?warning= GET
	 * parameters.
	 *
	 * This array is the list of valid message keys. Further keys can be added by the
	 * LoginFormValidErrorMessages hook. All other values will be ignored.
	 *
	 * @var string[]
	 */
	public static $validErrorMessages = [
		'exception-nologin-text',
		'watchlistanontext',
		'changeemail-no-info',
		'resetpass-no-info',
		'confirmemail_needlogin',
		'prefsnologintext2',
	];

	/**
	 * Returns an array of all valid error messages.
	 *
	 * @return array
	 * @see LoginHelper::$validErrorMessages
	 */
	public static function getValidErrorMessages() {
		static $messages = null;
		if ( !$messages ) {
			$messages = self::$validErrorMessages;
			Hooks::run( 'LoginFormValidErrorMessages', [ &$messages ] );
		}

		return $messages;
	}

	public function __construct( IContextSource $context ) {
		$this->setContext( $context );
	}

	/**
	 * Show a return link or redirect to it.
	 * Extensions can change where the link should point or inject content into the page
	 * (which will change it from redirect to link mode).
	 *
	 * @param string $type One of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
	 *    - success: display a return to link using $wgRedirectOnLogin if needed
	 *    - successredirect: send an HTTP redirect using $wgRedirectOnLogin if needed
	 * @param string $returnTo
	 * @param array|string $returnToQuery
	 * @param bool $stickHTTPS Keep redirect link on HTTPS
	 */
	public function showReturnToPage(
		$type, $returnTo = '', $returnToQuery = '', $stickHTTPS = false
	) {
		global $wgRedirectOnLogin, $wgSecureLogin;

		if ( $type !== 'error' && $wgRedirectOnLogin !== null ) {
			$returnTo = $wgRedirectOnLogin;
			$returnToQuery = [];
		} elseif ( is_string( $returnToQuery ) ) {
			$returnToQuery = wfCgiToArray( $returnToQuery );
		}

		// Allow modification of redirect behavior
		Hooks::run( 'PostLoginRedirect', [ &$returnTo, &$returnToQuery, &$type ] );

		$returnToTitle = Title::newFromText( $returnTo ) ?:  Title::newMainPage();

		if ( $wgSecureLogin && !$stickHTTPS ) {
			$options = [ 'http' ];
			$proto = PROTO_HTTP;
		} elseif ( $wgSecureLogin ) {
			$options = [ 'https' ];
			$proto = PROTO_HTTPS;
		} else {
			$options = [];
			$proto = PROTO_RELATIVE;
		}

		if ( $type === 'successredirect' ) {
			$redirectUrl = $returnToTitle->getFullURL( $returnToQuery, false, $proto );
			$this->getOutput()->redirect( $redirectUrl );
		} else {
			$this->getOutput()->addReturnTo( $returnToTitle, $returnToQuery, null, $options );
		}
	}
}
