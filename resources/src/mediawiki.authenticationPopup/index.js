const AuthPopup = require( './AuthPopup.js' );
const config = require( './config.json' );

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

const loginTitle = mw.Title.makeTitle( -1, config.specialPageNames.UserLogin );
const successTitle = mw.Title.makeTitle( -1, config.specialPageNames.AuthenticationPopupSuccess );

const loginPopupUrl = loginTitle.getUrl( {
	display: 'popup',
	returnto: successTitle.getPrefixedText(),
	returntoquery: 'display=popup'
} );
const loginFallbackUrl = loginTitle.getUrl( {
	returnto: successTitle.getPrefixedText()
} );

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
 * The promises returned by `AuthPopup` methods will be resolved with a {@link userinfo} object.
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
 * @example <caption>Example using `await` syntax</caption>
 * const userinfo = await authPopup.startPopupWindow(); // etc.
 * if ( userinfo ) {
 *     // Logged in
 * } else {
 *     // Cancelled by the user
 * }
 *
 * @module mediawiki.authenticationPopup
 * @type {AuthPopup}
 */
module.exports = new AuthPopup( {
	loginPopupUrl: loginPopupUrl,
	loginFallbackUrl: loginFallbackUrl,
	checkLoggedIn: checkLoggedIn,
	message: () => $( '<div>' ).append(
		$( '<p>' ).append(
			mw.message(
				'userlogin-authpopup-loggingin-body',
				$( '<a>' ).attr( 'href', loginFallbackUrl ).attr( 'target', '_blank' )
			).parseDom()
		),
		$.createSpinner( {
			size: 'large',
			type: 'block'
		} )
	)
} );
