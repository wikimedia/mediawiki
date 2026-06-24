const AuthPopup = require( './AuthPopup.js' );
const config = require( './config.json' );

/**
 * Success check for a plain login: resolves truthy with the userinfo object once
 * the session belongs to a real (non-anon, non-temp) user.
 *
 * @private
 * @return {Promise<module:mediawiki.authenticationPopup~userinfo|null>}
 */
function checkLoggedIn() {
	return ( new mw.Api() ).get( {
		meta: 'userinfo'
	} ).then( ( resp ) => {
		const userinfo = resp.query.userinfo;
		if ( userinfo.anon !== undefined || userinfo.temp !== undefined ) {
			return null;
		}
		return userinfo;
	} );
}

/**
 * Success check for a security-sensitive *reauthentication*: resolves truthy once
 * AuthManager reports the given operation no longer requires reauth (SEC_OK).
 *
 * @private
 * @param {string} operation Security-sensitive operation name (the `force` value)
 * @return {Promise<boolean>}
 */
function checkReauthenticated( operation ) {
	return ( new mw.Api() ).get( {
		meta: 'authmanagerinfo',
		amisecuritysensitiveoperation: operation
	} ).then( ( resp ) => resp.query.authmanagerinfo.securitysensitiveoperationstatus === 'ok' );
}

const loginTitle = mw.Title.makeTitle( -1, config.specialPageNames.UserLogin );
const successTitle = mw.Title.makeTitle( -1, config.specialPageNames.AuthenticationPopupSuccess );

/**
 * Build the popup URL and the (non-popup) fallback URL for Special:UserLogin,
 * optionally merging extra query parameters such as `force` for reauth.
 *
 * @private
 * @param {Object} [extraParams] Extra query params merged into both URLs
 * @return {{loginPopupUrl:string, loginFallbackUrl:string}}
 */
function buildUrls( extraParams ) {
	extraParams = extraParams || {};
	return {
		loginPopupUrl: loginTitle.getUrl( Object.assign( {
			display: 'popup',
			returnto: successTitle.getPrefixedText(),
			returntoquery: 'display=popup'
		}, extraParams ) ),
		loginFallbackUrl: loginTitle.getUrl( Object.assign( {
			returnto: successTitle.getPrefixedText()
		}, extraParams ) )
	};
}

/**
 * Default backdrop message shown in the parent window while the popup is open.
 *
 * @private
 * @param {string} fallbackUrl URL used by the "open in a new window instead" link
 * @return {jQuery}
 */
function defaultMessage( fallbackUrl ) {
	return $( '<div>' ).append(
		$( '<p>' ).append(
			mw.message(
				'userlogin-authpopup-loggingin-body',
				$( '<a>' ).attr( 'href', fallbackUrl ).attr( 'target', '_blank' )
			).parseDom()
		),
		$.createSpinner( {
			size: 'large',
			type: 'block'
		} )
	);
}

/**
 * `userinfo` object as returned by the
 * {@link https://www.mediawiki.org/wiki/API:Userinfo action=query&meta=userinfo API module}.
 *
 * @typedef {Object} module:mediawiki.authenticationPopup~userinfo
 * @property {string} name
 * @property {number} id
 */

/**
 * Exposes an instance of {@link AuthPopup} configured to display a login dialog for the local
 * instance of MediaWiki.
 *
 * The promises returned by `AuthPopup` methods will be resolved with a {@link mediawiki.authenticationPopup~userinfo} object.
 *
 * For security-sensitive **reauthentication** (an already-logged-in user re-entering credentials
 * to elevate their session security level for a specific operation), use
 * {@link module:mediawiki.authenticationPopup.forReauthentication forReauthentication()} instead
 * of the default login instance.
 *
 * **This library is not stable yet (as of May 2024). We're still testing which of the
 * methods work from the technical side, and which methods are understandable for users.
 * Some methods or the whole library may be removed in the future.**
 *
 * @example
 * const authPopup = require( 'mediawiki.authenticationPopup' );
 * authPopup.startPopupWindow()
 * // or: authPopup.startNewTabOrWindow()
 * // or: authPopup.startIframe()
 *     .then( function ( userinfo ) {
 *         if ( userinfo ) {
 *             // Logged in
 *             console.log( userinfo.name );
 *         } else {
 *             // Cancelled by the user
 *         }
 *     }, function ( error ) {
 *         // Unexpected error stopped the login process
 *     } );
 *
 * @example <caption>Reauthentication for a security-sensitive operation</caption>
 * const authPopup = require( 'mediawiki.authenticationPopup' );
 * const ok = await authPopup.forReauthentication( 'edit' ).startPopupWindow();
 * if ( ok ) {
 *     // Reauthenticated: the operation no longer requires reauth (SEC_OK)
 * } else {
 *     // Cancelled by the user
 * }
 *
 * @module mediawiki.authenticationPopup
 * @type {AuthPopup}
 */
const loginUrls = buildUrls();
const loginPopup = new AuthPopup( Object.assign( {}, loginUrls, {
	checkLoggedIn: checkLoggedIn,
	message: () => defaultMessage( loginUrls.loginFallbackUrl )
} ) );

/**
 * Build an {@link AuthPopup} that performs a security-sensitive reauthentication
 * for the given operation, rather than a fresh login.
 *
 * @memberof module:mediawiki.authenticationPopup
 * @param {string} operation Security-sensitive operation name (the `force` value)
 * @return {AuthPopup}
 */
loginPopup.forReauthentication = function ( operation ) {
	const urls = buildUrls( { force: operation } );
	return new AuthPopup( Object.assign( {}, urls, {
		checkLoggedIn: () => checkReauthenticated( operation ),
		message: () => defaultMessage( urls.loginFallbackUrl )
	} ) );
};

module.exports = loginPopup;
