<?php

use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

/**
 * Helper functions for the login form that need to be shared with other special pages
 * (such as CentralAuth's SpecialCentralLogin).
 * @since 1.27
 */
class LoginHelper extends ContextSource {
	use ProtectedHookAccessorTrait;

	/**
	 * @deprecated Direct access to this static property is deprecated since 1.45.
	 *   Use {@link LoginHelper::getValidErrorMessages} instead.
	 * @var string[]
	 */
	public static $validErrorMessages = [
		'exception-nologin-text',
		'exception-nologin-text-for-temp-user',
		'watchlistanontext',
		'watchlistanontext-for-temp-user',
		'changeemail-no-info',
		'confirmemail_needlogin',
		'prefsnologintext2',
		'prefsnologintext2-for-temp-user',
		'specialmute-login-required',
		'specialmute-login-required-for-temp-user',
	];

	/** @var array|null Cache for {@link LoginHelper::getValidErrorMessages} */
	private static ?array $validErrorMessagesCache = null;

	/**
	 * Returns an array of all error and warning messages that can be displayed on Special:UserLogin or
	 * Special:CreateAccount through the ?error or ?warning GET parameters.
	 *
	 * Special:UserLogin and Special:CreateAccount can show an error or warning message on the form when
	 * coming from another page using the aforementioned GET parameters.
	 *
	 * Further keys can be added by the LoginFormValidErrorMessages hook. If a message key is not in this
	 * list, then no redirect will be performed to Special:UserLogin or Special:CreateAccount.
	 *
	 * @return string[]
	 * @see LoginHelper::$validErrorMessages
	 */
	public static function getValidErrorMessages(): array {
		if ( !static::$validErrorMessagesCache ) {
			static::$validErrorMessagesCache = self::$validErrorMessages;
			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onLoginFormValidErrorMessages( static::$validErrorMessagesCache );
		}

		return static::$validErrorMessagesCache;
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
		if ( $returnToAnchor !== '' && $returnToAnchor[0] !== '#' ) {
			$returnToAnchor = '';
		}

		// Allow modification of redirect behavior
		$oldReturnTo = $returnTo;
		$oldReturnToQuery = $returnToQuery;
		$this->getHookRunner()->onPostLoginRedirect( $returnTo, $returnToQuery, $type );
		if ( $returnTo !== $oldReturnTo || $returnToQuery !== $oldReturnToQuery ) {
			// PostLoginRedirect does not handle $returnToAnchor, and changing hooks is hard.
			// At least don't add the anchor if the hook changed the URL.
			$returnToAnchor = '';
		}

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
