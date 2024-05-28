const AuthPopup = require( './AuthPopup.js' );
const config = require( './config.json' );

function checkLoggedIn() {
	return ( new mw.Api() ).get( {
		meta: 'userinfo'
	} ).then( function ( resp ) {
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
