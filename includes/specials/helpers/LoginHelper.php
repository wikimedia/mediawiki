<?php

use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\MainConfigNames;

/**
 * Helper functions for the login form that need to be shared with other special pages
 * (such as CentralAuth's SpecialCentralLogin).
 * @since 1.27
 */
class LoginHelper extends ContextSource {
	use ProtectedHookAccessorTrait;

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
		'specialmute-login-required',
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
			Hooks::runner()->onLoginFormValidErrorMessages( $messages );
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
	 *    - signup: used during signup, functionally identical to 'success'
	 * @param string $returnTo Title of page to return to. Overriden by $wgRedirectOnLogin
	 *   when that is set (and $type is not 'error').
	 * @param array|string $returnToQuery Query parameters to return to.
	 * @param bool $stickHTTPS Keep redirect link on HTTPS. Ignored (treated as
	 *   true) if $wgForceHTTPS is true.
	 * @param string $returnToAnchor A string to append to the URL, presumed to
	 *   be either a fragment including the leading hash or an empty string.
	 */
	public function showReturnToPage(
		$type, $returnTo = '', $returnToQuery = '', $stickHTTPS = false, $returnToAnchor = ''
	) {
		$config = $this->getConfig();
		if ( $type !== 'error' && $config->get( MainConfigNames::RedirectOnLogin ) !== null ) {
			$returnTo = $config->get( MainConfigNames::RedirectOnLogin );
			$returnToQuery = [];
		} elseif ( is_string( $returnToQuery ) ) {
			$returnToQuery = wfCgiToArray( $returnToQuery );
		}

		// Allow modification of redirect behavior
		$this->getHookRunner()->onPostLoginRedirect( $returnTo, $returnToQuery, $type );

		$returnToTitle = Title::newFromText( $returnTo ) ?: Title::newMainPage();

		if ( $config->get( MainConfigNames::ForceHTTPS )
			|| ( $config->get( MainConfigNames::SecureLogin ) && $stickHTTPS )
		) {
			$options = [ 'https' ];
			$proto = PROTO_HTTPS;
		} elseif ( $config->get( MainConfigNames::SecureLogin ) && !$stickHTTPS ) {
			$options = [ 'http' ];
			$proto = PROTO_HTTP;
		} else {
			$options = [];
			$proto = PROTO_RELATIVE;
		}

		if ( $type === 'successredirect' ) {
			$redirectUrl = $returnToTitle->getFullUrlForRedirect( $returnToQuery, $proto )
				. $returnToAnchor;
			$this->getOutput()->redirect( $redirectUrl );
		} else {
			$this->getOutput()->addReturnTo( $returnToTitle, $returnToQuery, null, $options );
		}
	}
}
